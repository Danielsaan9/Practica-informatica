/* global TiptapCore */

const { Extension } = TiptapCore;

/**
 * TipTap extension that intercepts base64-encoded images inserted into the
 * editor (via paste or drag-and-drop) and converts them to server-side uploads.
 *
 * On each document-changing transaction, scans for image nodes whose src
 * starts with "data:", replaces them with a temporary placeholder URI, and
 * fires an async upload. Once the upload resolves, the placeholder is swapped
 * for the final server URL. If the upload fails, the placeholder node is removed.
 *
 * Options:
 *   uploadHandler(dataUri: string): Promise<string|null>
 *     Called with the full data: URI. Must return the final image URL on
 *     success, or null to remove the image node.
 */
export const Base64ImageHandler = Extension.create({
    name: 'base64ImageHandler',

    addOptions() {
        return {
            uploadHandler: null,
        };
    },

    onTransaction({ transaction }) {
        if (!this.options.uploadHandler || !transaction.docChanged || !this.editor.isEditable) {
            return;
        }

        const editor = this.editor;
        const uploadHandler = this.options.uploadHandler;

        // Collect base64 image nodes to process (avoid mutating during iteration)
        const toProcess = [];

        editor.state.doc.descendants((node, pos) => {
            if (node.type.name === 'image' && (node.attrs.src || '').startsWith('data:')) {
                toProcess.push({ pos, attrs: { ...node.attrs } });
            }
        });

        if (toProcess.length === 0) {
            return;
        }

        // Replace all base64 images with placeholders in a single transaction
        let tr = editor.state.tr;
        for (const { pos, attrs } of toProcess) {
            const uuid = crypto.randomUUID();
            const placeholderSrc = `about:uploading-${uuid}`;
            const dataUri = attrs.src;

            tr = tr.setNodeMarkup(pos, undefined, {
                ...attrs,
                src: placeholderSrc,
            });

            // Fire the async upload (non-blocking)
            uploadHandler(dataUri).then((finalUrl) => {
                if (editor.isDestroyed) {
                    return;
                }

                // Find the placeholder in the current document state
                editor.state.doc.descendants((n, p) => {
                    if (n.type.name === 'image' && n.attrs.src === placeholderSrc) {
                        if (finalUrl) {
                            editor.view.dispatch(
                                editor.state.tr.setNodeMarkup(p, undefined, {
                                    ...n.attrs,
                                    src: finalUrl,
                                })
                            );
                        } else {
                            // Upload returned null — remove the node
                            editor.view.dispatch(
                                editor.state.tr.delete(p, p + n.nodeSize)
                            );
                        }
                        return false; // stop iteration
                    }
                });
            }).catch(() => {
                if (editor.isDestroyed) {
                    return;
                }

                // On error, remove the placeholder node
                editor.state.doc.descendants((n, p) => {
                    if (n.type.name === 'image' && n.attrs.src === placeholderSrc) {
                        editor.view.dispatch(
                            editor.state.tr.delete(p, p + n.nodeSize)
                        );
                        return false;
                    }
                });
            });
        }

        // Defer the dispatch to avoid re-entrant dispatch inside
        // ProseMirror's transaction processing (onTransaction fires
        // synchronously during dispatchTransaction).
        queueMicrotask(() => {
            if (!editor.isDestroyed) {
                editor.view.dispatch(tr);
            }
        });
    },
});

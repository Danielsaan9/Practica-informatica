import { locateAnchor } from '/js/modules/Knowbase/CommentAnchor.js';
import { buildDomTextIndex, wrapOffsetsInMarks } from '/js/modules/Knowbase/DomTextIndex.js';

/**
 * Remove any highlight marks previously added by this module, merging their
 * text back into the surrounding text node.
 * @param {Element} container
 */
function clearHighlights(container) {
    container.querySelectorAll('mark.kb-comment-highlight').forEach((mark) => {
        const parent = mark.parentNode;
        while (mark.firstChild) {
            parent.insertBefore(mark.firstChild, mark);
        }
        parent.removeChild(mark);
        parent.normalize();
    });
}

/**
 * Highlight every anchored comment's quoted text still locatable in `container`'s
 * rendered HTML. Unresolvable anchors are silently skipped. Safe to call repeatedly.
 * @param {Element} container
 * @param {Array<{id: number|string, prefix: string, exact: string, suffix: string, occurrence: number}>} anchors
 * @returns {Array<{id: string, text: string}>} Resolved anchors, with the text actually
 * located (which can drift from the stored `exact` when resolved by bracketing).
 */
export function highlightComments(container, anchors) {
    clearHighlights(container);

    const resolved = [];
    if (!anchors || anchors.length === 0) {
        return resolved;
    }

    // Rebuilt per anchor: wrapOffsetsInMarks() mutates the DOM (splits text
    // nodes), invalidating a shared index for later anchors.
    for (const anchor of anchors) {
        const index = buildDomTextIndex(container);
        const located = locateAnchor(index.text, anchor);
        if (!located) {
            continue;
        }
        const [start, end] = located;
        wrapOffsetsInMarks(index, start, end, anchor.id);
        resolved.push({ id: String(anchor.id), text: index.text.slice(start, end) });
    }

    return resolved;
}

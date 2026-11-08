/**
 * Build a plain-text index of a ProseMirror doc's leaf text nodes, concatenated
 * with no separator — matches DomTextIndex.js so anchors work across both modes.
 * @param {import('prosemirror-model').Node} doc
 * @returns {{text: string, segments: Array<{pos: number, start: number, end: number}>}}
 */
export function buildPmTextIndex(doc) {
    const segments = [];
    let text = '';
    doc.descendants((node, pos) => {
        if (node.isText) {
            const start = text.length;
            text += node.text;
            segments.push({ pos, start, end: start + node.text.length });
        }
        return true;
    });
    return { text, segments };
}

/**
 * Convert a ProseMirror document position into a plain-text offset from an
 * index built by buildPmTextIndex(). Falls back to the nearest text boundary.
 * @param {Array<{pos: number, start: number, end: number}>} segments
 * @param {number} pos
 * @returns {number}
 */
export function pmPositionToOffset(segments, pos) {
    if (segments.length === 0) {
        return 0;
    }
    // segment[i].end == segment[i+1].start (no separator), so a `pos` in the
    // gap between segments maps unambiguously to that shared boundary offset.
    for (const segment of segments) {
        const pm_end = segment.pos + (segment.end - segment.start);
        if (pos < segment.pos) {
            return segment.start;
        }
        if (pos <= pm_end) {
            return segment.start + (pos - segment.pos);
        }
    }
    return segments[segments.length - 1].end;
}

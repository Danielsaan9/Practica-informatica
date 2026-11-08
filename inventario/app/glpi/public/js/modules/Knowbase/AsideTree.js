/**
 * Reading an occurrence's place in the aside tree.
 *
 * An article may have several parents and is rendered under each of them, so
 * an occurrence has no identity beyond its DOM element: its parent is read
 * from the ancestry, never from an attribute the server could drift from.
 */

/**
 * The `<li>` of an occurrence's parent, null when it sits at the root.
 *
 * @param {HTMLElement} row
 * @returns {HTMLElement|null}
 */
export function parentRowOf(row)
{
    // From `parentElement`: `closest()` includes its own start element.
    return row.parentElement?.closest('li[data-glpi-kb-article-id]') ?? null;
}

/**
 * Article id of an occurrence's parent, 0 at the root, matching the server.
 *
 * @param {HTMLElement} row
 * @returns {number}
 */
export function parentIdOf(row)
{
    return Number(parentRowOf(row)?.dataset.glpiKbArticleId ?? 0);
}

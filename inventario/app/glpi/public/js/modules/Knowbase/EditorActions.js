import { post } from "/js/modules/Ajax.js";

/**
 * Mirror of the PHP Glpi\Knowbase\EditorActionType enum. Shared between the
 * article editor kebab menu and the knowledge base aside tree.
 */
export const EditorActionType = Object.freeze({
    LOAD_SIDE_PANEL: 'LOAD_SIDE_PANEL',
    TOGGLE_VALUE:    'TOGGLE_VALUE',
    TOGGLE_FAVORITE: 'TOGGLE_FAVORITE',
    DELETE_ARTICLE:  'DELETE_ARTICLE',
    OPEN_MODAL:      'OPEN_MODAL',
});

// In-flight toggle requests keyed `${type}:${id}`, to prevent overlapping add/remove races.
const pending_toggles = new Set();

function toggleKey(type, id)
{
    return `${type}:${id}`;
}

/**
 * @param {string} type
 * @param {number|string} id
 * @returns {boolean}
 */
export function isTogglePending(type, id)
{
    return pending_toggles.has(toggleKey(type, id));
}

/**
 * Run a toggle request while marking (type, id) in-flight; always cleared afterwards.
 *
 * @param {string} type
 * @param {number|string} id
 * @param {() => Promise<T>} request
 * @returns {Promise<T>}
 * @template T
 */
export async function runToggle(type, id, request)
{
    const key = toggleKey(type, id);
    pending_toggles.add(key);
    try {
        return await request();
    } finally {
        pending_toggles.delete(key);
    }
}

/**
 * Extract `data-glpi-kb-action-param-*` values from a dataset into a plain
 * object keyed by the (lower-cased) param name.
 *
 * @param {DOMStringMap} dataset
 * @returns {Object<string, string>}
 */
export function extractParamsFromDataset(dataset)
{
    const params = {};
    const prefix = 'glpiKbActionParam';

    for (const [key, value] of Object.entries(dataset)) {
        if (key.startsWith(prefix)) {
            const param_name = key.slice(prefix.length).toLowerCase();
            params[param_name] = value;
        }
    }

    return params;
}

/**
 * Add or remove an article from the current user's favorites.
 *
 * @param {number} id
 * @param {boolean} value
 * @returns {Promise<{favorite: boolean}>} The authoritative state after the call.
 */
export async function toggleFavorite(id, value)
{
    const response = await post(`Knowbase/${id}/ToggleFavorite`, { value: value });
    return response.json();
}

/**
 * Toggle a boolean field (e.g. `is_faq`) on an article.
 *
 * @param {number} id
 * @param {string} field
 * @param {boolean} value
 * @returns {Promise<Response>}
 */
export function toggleField(id, field, value)
{
    return post(`Knowbase/${id}/ToggleField`, { field: field, value: value });
}

/**
 * Delete an article. A 409 carries the reason the server refused it.
 *
 * @param {number} id
 * @returns {Promise<Response>}
 */
export function deleteArticle(id)
{
    return post(`Knowbase/KnowbaseItem/${id}/Delete`, {}, [409]);
}

/**
 * Keep every rendered menu checkbox for a given article/action in sync across
 * the whole page (article editor header + aside tree + aside favorites). The
 * same article is shown in several places, each with its own dots menu.
 *
 * Lazy aside menus that are not loaded yet simply have no checkbox to update;
 * they fetch fresh state when opened.
 *
 * @param {number|string} id
 * @param {string} type EditorActionType.TOGGLE_FAVORITE or TOGGLE_VALUE
 * @param {boolean} checked
 * @param {string|null} field field name for TOGGLE_VALUE (e.g. 'is_faq')
 */
export function syncToggleCheckboxes(id, type, checked, field = null)
{
    let selector = `[data-glpi-kb-action="${CSS.escape(type)}"][data-glpi-kb-action-param-id="${CSS.escape(id)}"]`;
    if (field !== null) {
        selector += `[data-glpi-kb-action-param-field="${CSS.escape(field)}"]`;
    }

    for (const button of document.querySelectorAll(selector)) {
        const toggle = button.querySelector('input[type="checkbox"]');
        if (toggle) {
            toggle.checked = checked;
        }
    }
}

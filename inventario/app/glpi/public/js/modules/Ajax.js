/* global glpi_toast_error */

/**
 * Perform a POST request to a GLPI endpoint.
 *
 * @param {string} url - The relative URL path (without root_doc prefix).
 * @param {Object | FormData | URLSearchParams | null} values - The data to send
 *   in the request body, or null if no data.
 *   Plain objects are serialized as JSON. FormData and URLSearchParams are sent
 *   as-is (form data).
 * @param {number[]} handled_statuses - Error statuses the caller reports itself:
 *   the response is returned instead of raising the generic error.
 * @returns {Promise<Response>} The fetch Response object.
 * @throws {Error} If the request fails or returns an unhandled non-ok status.
 */
export async function post(url, values = null, handled_statuses = [])
{
    try {
        const is_plain_object = values !== null
            && !(values instanceof FormData)
            && !(values instanceof URLSearchParams)
        ;

        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
        };

        if (is_plain_object) {
            headers['Content-Type'] = 'application/json';
        }

        const params = {
            method: 'POST',
            headers,
        };

        if (values !== null) {
            params.body = is_plain_object ? JSON.stringify(values) : values;
        }

        const response = await fetch(`${CFG_GLPI.root_doc}/${url}`, params);
        if (!response.ok && !handled_statuses.includes(response.status)) {
            throw new Error("POST request failed");
        }

        return response;
    } catch (e) {
        glpi_toast_error(__("An unexpected error occurred."));
        throw e;
    }
}

/**
 * Perform a GET request to a GLPI endpoint.
 *
 * @param {string} url - The relative URL path (without root_doc prefix).
 * @param {Object} [options]
 * @param {boolean} [options.quiet=false] Suppress the generic error toast on failure.
 *   For a speculative/background request whose caller already degrades gracefully on failure,
 *   where a toast would be noise the reader never asked to see, not signal.
 * @returns {Promise<Response>} The fetch Response object.
 * @throws {Error} If the request fails or returns a non-ok status.
 */
export async function get(url, { quiet = false } = {})
{
    try {
        const params = {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        };

        const response = await fetch(`${CFG_GLPI.root_doc}/${url}`, params);
        if (!response.ok) {
            throw new Error("GET request failed");
        }

        return response;
    } catch (e) {
        if (!quiet) {
            glpi_toast_error(__("An unexpected error occurred."));
        }
        throw e;
    }
}

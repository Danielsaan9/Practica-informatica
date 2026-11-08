/* global _, glpi_toast_error */

export class GlpiHelpdeskIndexController
{
    constructor()
    {
        const input = this.#getSearchInput();
        const results_div = this.#getSearchResultsDiv();

        // Toggle search view when the search input is active.
        // We can't just use focus / focusout here because it is a bit too strict.
        // It is better to watch for clicks directly as we can keep the search
        // view enabled when the user click on the results.
        input.addEventListener('focus', (e) => this.#enableSearchView(e));
        input.addEventListener('click', (e) => this.#enableSearchView(e));
        results_div.addEventListener('click', (e) => this.#enableSearchView(e));
        document.addEventListener('click', (e) => this.#disableSearchView(e));

        // Execute search when someting is typed
        const searchDebounced = _.debounce(
            this.#executeSearch.bind(this), // .bind keep the correct "this" context
            400,
            false
        );
        input.addEventListener('input', searchDebounced);
    }

    async #executeSearch()
    {
        const input = this.#getSearchInput();
        try {
            const url = `${CFG_GLPI.root_doc}/Helpdesk/Search`;
            const url_params = new URLSearchParams({
                filter: input.value,
            });
            const response = await fetch(`${url}?${url_params}`);

            if (!response.ok) {
                throw new Error("Failed to load results");
            }
            this.#getSearchResultsDiv().innerHTML = await response.text();
        } catch (e) {
            console.error(e);
            glpi_toast_error(
                __("Unexpected error")
            );
        }
    }

    #enableSearchView(event)
    {
        event.stopPropagation();
        this.#getSearchOverlayDiv().style.opacity = 0.4;
        this.#getSearchOverlayDiv().style.visibility = "visible";
        this.#getSearchInput().classList.add('remove-bottom-border-radius');
        this.#getSearchResultsDiv().classList.remove('d-none');
    }

    #disableSearchView(event)
    {
        event.stopPropagation();
        this.#getSearchOverlayDiv().style.opacity = 0;
        this.#getSearchOverlayDiv().style.visibility = "hidden";
        this.#getSearchInput().classList.remove('remove-bottom-border-radius');
        this.#getSearchResultsDiv().classList.add('d-none');
    }

    #getSearchOverlayDiv()
    {
        return document.querySelector("#search-overlay");
    }

    #getSearchInput()
    {
        return document.querySelector("#search-input");
    }

    #getSearchResultsDiv()
    {
        return document.querySelector("#search-results");
    }
}

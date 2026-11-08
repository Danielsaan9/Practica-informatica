export class GlpiHelpdeskConfigForEmptyEntityController
{
    /** @type {HTMLElement} */
    #container;

    constructor(container)
    {
        this.#container = container;
        this.#initEventsHandlers();
        this.#enableActions();
    }

    #initEventsHandlers()
    {
        // Watch for click on the "define tiles" button.
        this.#getDefineTilesButton().addEventListener('click', () => {
            this.#getSpecificConfigDiv().classList.add('d-none');
            this.#getOriginalHelpdeskConfigDiv()
                .classList
                .remove('helpdesk-home-config-for-empty-entity-wrapper')
            ;
        });
    }

    #enableActions()
    {
        this.#getDefineTilesButton().classList.remove('pointer-events-none');
        this.#getCopyTilesButton().classList.remove('pointer-events-none');
    }

    /** @return {HTMLElement} */
    #getDefineTilesButton()
    {
        return this.#container.querySelector(
            '[data-glpi-helpdesk-config-tiles-empty-entity-define-tiles]'
        );
    }

    /** @return {HTMLElement} */
    #getCopyTilesButton()
    {
        return this.#container.querySelector(
            '[data-glpi-helpdesk-config-tiles-empty-entity-copy-tiles]'
        );
    }

    /** @return {HTMLElement} */
    #getOriginalHelpdeskConfigDiv()
    {
        return this.#container.querySelector(
            '[data-glpi-helpdesk-config-tiles-empty-entity-original-content]'
        );
    }

    /** @return {HTMLElement} */
    #getSpecificConfigDiv()
    {
        return this.#container.querySelector(
            '[data-glpi-helpdesk-config-tiles-empty-entity-specific]'
        );
    }
}

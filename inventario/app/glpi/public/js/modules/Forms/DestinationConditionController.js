/**
 * Controller to handle destination visibility badges update
 */
export class GlpiFormDestinationConditionController {
    /**
     * Initialize the controller and event listeners
     */
    constructor() {
        this.#initEventHandlers();
    }

    /**
     * Initialize event listeners for strategy changes
     */
    #initEventHandlers() {
        // Listen for strategy change events
        document.addEventListener('updated_strategy', (e) => {
            if (e.detail && e.detail.container) {
                // Get the destination container
                const container = $(e.detail.container).closest('.accordion-item');
                if (container.length > 0) {
                    this.#updateConditionBadge(container, e.detail.strategy);
                }
            }
        });

        // Handle conditions count changes
        document.addEventListener('conditions_count_changed', (e) => {
            this.#updateConditionsCount(
                $(e.detail.container).closest('.accordion-item'),
                e.detail.conditions_count
            );
        });

        // [data-glpi-destination-click-on-space] should be a button but we can't
        // because it contains an input.
        // It is thus a div instead but we want to keep the behavior of pressing
        // space while focusing this div as a way to toggle the accordion.
        const divs = document.querySelectorAll('[data-glpi-destination-click-on-space]');
        for (const div of divs) {
            div.addEventListener('keyup', (e) => {
                // Dispatch click event if space is pressed outside of an input
                if (e.key == " " && e.target.tagName !== "INPUT") {
                    div.dispatchEvent(new Event("click"));
                }
            });
        }
    }

    /**
     * Update the creation condition badge
     *
     * @param {jQuery} container The destination container
     * @param {string} value The selected strategy value
     */
    #updateConditionBadge(container, value) {
        // Show/hide badges in the container
        container.find('[data-glpi-editor-condition-badge]')
            .removeClass('d-flex')
            .addClass('d-none')
        ;
        container.find(`[data-glpi-editor-condition-badge="${CSS.escape(value)}"]`)
            .removeClass('d-none')
            .addClass('d-flex')
        ;
    }

    /**
     * Update the conditions count badge in the container
     *
     * @param {jQuery} container
     * @param {int} value
     */
    #updateConditionsCount(container, value) {
        container.find('[data-glpi-editor-conditions-count-badge]')
            .html(value);
    }
}

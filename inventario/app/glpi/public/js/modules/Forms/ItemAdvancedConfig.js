export class GlpiFormItemAdvancedConfig {
    // Static instance for singleton pattern
    static instance = null;

    #common_tree_dropdown_itemtypes = [];

    /**
     * Constructor for the Item Advanced Configuration
     *
     * @param {Array} common_tree_dropdown_itemtypes - List of itemtypes that are CommonTreeDropdown
     */
    constructor(common_tree_dropdown_itemtypes = []) {
        // Prevent multiple initializations
        if (GlpiFormItemAdvancedConfig.instance !== null) {
            return GlpiFormItemAdvancedConfig.instance;
        }

        // Register event listener for question sub-type changes
        this.registerEventListeners();

        // Store instance
        GlpiFormItemAdvancedConfig.instance = this;

        // Store the itemtypes that are CommonTreeDropdown
        this.#common_tree_dropdown_itemtypes = common_tree_dropdown_itemtypes;
    }

    /**
     * Find the container element for the dropdown advanced configuration
     *
     * @param {jQuery} question The question element
     * @returns {jQuery|null} The container element or null if not found
     */
    findContainer(question) {
        const container = question.find(
            `[data-glpi-form-editor-item-dropdown-advanced-configuration]`
        );

        return container.length > 0 ? container : null;
    }

    /**
     * Register all necessary event listeners
     */
    registerEventListeners() {
        $(document).on('glpi-form-editor-question-sub-type-changed',
            (event, question, sub_type) => {
                // Ensure the event is for an Item question
                if (
                    question.find('[data-glpi-form-editor-original-name="type"], [name="type"]').length === 0
                    || question.find('[data-glpi-form-editor-original-name="type"], [name="type"]').val() !== 'Glpi\\Form\\QuestionType\\QuestionTypeItem'
                ) {
                    return;
                }

                const container = this.findContainer(question);
                if (!container) {
                    return;
                }

                this.updateAdvancedConfigVisibility(container, sub_type);
            }
        );
    }

    updateAdvancedConfigVisibility(container, new_sub_type) {
        const dropdown_container = container.closest('[data-glpi-form-editor-advanced-question-configuration]')
            .parents('[data-glpi-form-editor-question-extra-details]');

        // Show button only for sub-type that are CommonTreeDropdown
        if (this.#common_tree_dropdown_itemtypes.includes(new_sub_type)) {
            dropdown_container.show();
            dropdown_container.attr('data-glpi-form-editor-advanced-question-configuration-visible', 'true');
        } else {
            dropdown_container.hide();
            dropdown_container.removeAttr('data-glpi-form-editor-advanced-question-configuration-visible');
        }
    }
}

export class GlpiFormItemDropdownAdvancedConfig {
    // Static instance for singleton pattern
    static instance = null;

    /**
     * Constructor for the ItemDropdown Advanced Configuration
     */
    constructor() {
        // Prevent multiple initializations
        if (GlpiFormItemDropdownAdvancedConfig.instance !== null) {
            return GlpiFormItemDropdownAdvancedConfig.instance;
        }

        // Register event listener for question sub-type changes
        this.registerEventListeners();

        // Store instance
        GlpiFormItemDropdownAdvancedConfig.instance = this;
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
                const container = this.findContainer(question);
                if (!container) {
                    return;
                }

                this.updateFieldsVisibility(container, sub_type);
                this.updateRootItemsDropdown(question, sub_type);
            }
        );
    }

    /**
     * Update visibility of fields based on the selected sub type
     *
     * @param {jQuery} container The container element
     * @param {string} sub_type The selected sub type
     */
    updateFieldsVisibility(container, sub_type) {
        // Find all form fields with visibility rules
        const formFields = container.find('[data-glpi-form-editor-item-dropdown-advanced-configuration-visible-for-itemtype]');

        formFields.each((index, element) => {
            const formField = $(element).closest('.form-field');
            const visibleType = $(element).attr('data-glpi-form-editor-item-dropdown-advanced-configuration-visible-for-itemtype');

            // Show the field if current sub_type matches the visible type
            const isVisible = visibleType == sub_type;
            formField.toggleClass('d-none', !isVisible);
        });
    }

    /**
     * Update the root items dropdown based on the selected sub type
     *
     * @param {jQuery} question The question element
     * @param {string} sub_type The selected sub type
     */
    updateRootItemsDropdown(question, sub_type) {
        const select = question.find(
            '[name="extra_data[root_items_id]"], [data-glpi-form-editor-original-name="extra_data[root_items_id]"]'
        );
        const container = select.parent();

        // Mark existing elements for removal
        container.children().attr('data-to-remove', 'true');

        // Load the new dropdown content via AJAX
        container.load(
            `${CFG_GLPI.root_doc}/ajax/dropdownAllItems.php`,
            {
                'idtable': sub_type,
                'width': '100%',
                'name': select.data('glpi-form-editor-original-name') || select.attr('name'),
                'aria-label': select.attr('aria-label'),
            },
            () => {
                const new_select = question.find(
                    '[name="extra_data[root_items_id]"], [data-glpi-form-editor-original-name="extra_data[root_items_id]"]'
                );

                // Update old reference to the new select element
                question.find(`[for="${CSS.escape(select.attr('id'))}"]`).attr('for', new_select.attr('id'));

                // Remove elements marked for removal
                container.find('[data-to-remove]').remove();
            }
        );
    }
}

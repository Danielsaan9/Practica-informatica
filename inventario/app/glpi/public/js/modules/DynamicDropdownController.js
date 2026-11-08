/**
 * Helper class to easily manage items that should be hidden / shown depending
 * on a dropdown value.
 *
 * The item must define two data attributes:
 * - data-glpi-parent-dropdown: the name of the parent dropdown
 * - data-glpi-parent-dropdown-condition: the value that the parent dropdown
 *  must have for this item to be displayed
 */
export class DynamicDropdownController
{
    constructor() {
        this.#watchForDropdownChanges();
    }

    #watchForDropdownChanges() {
        const dropdowns = $('select[data-select2-id]');

        $(dropdowns).on('change', (e) => {
            this.#updateItemsVisiblity($(e.target));
        });
    }

    #updateItemsVisiblity(select) {
        const name = $.escapeSelector(select.prop("name"));
        const items = $(`[data-glpi-parent-dropdown="${CSS.escape(name)}"]`);
        const value = select.val();

        items.each((i, dropdown) => {
            const expected_value = $(dropdown).data(
                'glpi-parent-dropdown-condition'
            );
            $(dropdown).toggleClass('d-none', expected_value !== value);
        });
    }
}

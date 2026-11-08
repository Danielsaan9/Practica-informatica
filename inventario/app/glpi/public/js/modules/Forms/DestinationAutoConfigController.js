/* global tinymce */

export class GlpiFormDestinationAutoConfigController
{
    constructor() {
        this.#watchForAutoConfigToggle();
    }

    #watchForAutoConfigToggle() {
        const checkboxes = $('[data-glpi-itildestination-toggle-auto-config]');

        $(checkboxes).on('change', (e) => {
            const is_auto_config_enabled = $(e.target).is(":checked");
            const container = $(e.target).closest('[data-glpi-itildestination-field]');

            this.#toggleInputs(container, is_auto_config_enabled);
            this.#toggleRichTextEditors(container, is_auto_config_enabled);
        });
    }

    #toggleInputs(container, is_auto_config_enabled) {
        const inputs = container.find('input');

        inputs.each((i, input) => {
            input = $(input);

            // Prevent disabling the checkbox itself
            const is_excluded = input.data('glpi-itildestination-toggle-do-not-disable') !== undefined;
            if (is_excluded) {
                return;
            }

            input.prop('disabled', is_auto_config_enabled);
        });
    }

    #toggleRichTextEditors(container, is_auto_config_enabled) {
        const textareas = container.find('textarea');

        textareas.each((i, textarea) => {
            textarea = $(textarea);
            const editor = tinymce.get(textarea.prop("id"));

            if (is_auto_config_enabled) {
                editor.mode.set("readonly");
                textarea.attr('disabled', 'disabled');
            } else {
                editor.mode.set("design");
                textarea.removeAttr('disabled');
            }
        });
    }
}

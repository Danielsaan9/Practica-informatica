import { ProgressIndicator } from '/js/modules/ProgressIndicator.js';

export function init_database()
{
    const messages_container = document.getElementById('glpi_install_messages_container');
    const success_container = document.getElementById('glpi_install_success');
    const back_button_container = document.getElementById('glpi_install_back');

    const request = new Request(
        `${CFG_GLPI.root_doc}/Install/InitDatabase`,
        {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded;',
                'X-Requested-With': 'XMLHttpRequest',
            },
        },
    );

    const progress_indicator = new ProgressIndicator({
        container: messages_container,
        request: request,
        success_callback: () => {
            success_container.querySelector('button[type="submit"]').removeAttribute('disabled');
            success_container.setAttribute('class', 'd-inline');
        },
        error_callback: () => {
            back_button_container.querySelector('button[type="submit"]').removeAttribute('disabled');
            back_button_container.setAttribute('class', 'd-inline');
        },
    });

    progress_indicator.start();
}

export async function update_database()
{
    const messages_container = document.getElementById('glpi_update_messages_container');
    const success_container = document.getElementById('glpi_update_success');

    const request = new Request(
        `${CFG_GLPI.root_doc}/Install/UpdateDatabase`,
        {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded;',
                'X-Requested-With': 'XMLHttpRequest',
            },
        },
    );

    const progress_indicator = new ProgressIndicator({
        container: messages_container,
        request: request,
        success_callback: () => {
            success_container.querySelector('button[type="submit"]').removeAttribute('disabled');
            success_container.setAttribute('class', 'd-inline');
        },
    });

    progress_indicator.start();
}

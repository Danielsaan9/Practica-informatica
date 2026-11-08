/* global bootstrap */

// Explicitly bind to window so Jest tests work properly
window.GLPI = window.GLPI || {};
window.GLPI.Search = window.GLPI.Search || {};

window.GLPI.Search.GenericView = class GenericView {

    constructor(element_id) {
        this.element_id = element_id;

        if (this.getElement()) {
            this.registerListeners();
        }
    }

    postInit() {}

    getElement() {
        return $(`#${CSS.escape(this.element_id)}`);
    }

    getResultsView() {
        return this.getElement().closest('.ajax-container.search-display-data').data('js_class');
    }

    showLoadingSpinner() {
        const el = this.getElement();
        const container = el.parent();
        let loading_overlay = container.find('div.spinner-overlay');

        if (loading_overlay.length === 0) {
            container.append(`
            <div class="spinner-overlay text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">${__('Loading...')}</span>
                </div>
            </div>`);
            loading_overlay = container.find('div.spinner-overlay');
        } else {
            loading_overlay.css('visibility', 'visible');
        }
    }

    hideLoadingSpinner() {
        const loading_overlay = this.getElement().parent().find('div.spinner-overlay');
        loading_overlay.css('visibility', 'hidden');
    }

    registerListeners() {
        const ajax_container = this.getResultsView().getAJAXContainer();
        const search_container = ajax_container.closest('.search-container');

        $(search_container).on('click', 'button[name="save_bookmark_record"]', () => {
            const modal = $('#savedsearch-modal');
            //move the modal to the body so it can be displayed above the rest of the page
            modal.appendTo('body');
            modal.empty();
            modal.html(`
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">${__('Save current search')}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${__('Close')}"></button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
            `);
            const bs_modal = new bootstrap.Modal(modal.get(0), {show: false});
            modal.off('show.bs.modal').on('show.bs.modal', () => {
                const params = JSON.parse(modal.attr('data-params'));
                params['url'] = window.location.pathname + window.location.search;
                modal.find('.modal-body').load(`${CFG_GLPI.root_doc}/ajax/savedsearch.php`, params);
            });
            bs_modal.show();
        });
    }

    onSearch() {
        this.refreshResults();
    }

    refreshResults() {}
};
export default window.GLPI.Search.GenericView;

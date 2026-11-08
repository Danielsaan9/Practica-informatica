// Explicitly bind to window so Jest tests work properly
window.GLPI = window.GLPI || {};
window.GLPI.Search = window.GLPI.Search || {};

window.GLPI.Search.ResultsView = class ResultsView {

    constructor(element_id, view_class, push_history = true, forced_params = {}) {
        this.element_id = element_id;

        if (this.getElement()) {
            this.getAJAXContainer().data('js_class', this);
            this.view = new view_class(this.element_id, push_history, forced_params);
        }
    }

    setID(id) {
        this.element_id = id;
    }

    getElement() {
        return $(`#${this.element_id}`);
    }

    getAJAXContainer() {
        return this.getElement().closest('div.ajax-container.search-display-data');
    }

    getView() {
        return this.view;
    }
};
export default window.GLPI.Search.ResultsView;

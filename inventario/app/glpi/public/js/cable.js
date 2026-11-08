function refreshAssetBreadcrumb(itemtype, items_id, dom_to_update) {
    // get asset breadcrumb
    $.ajax({
        method: 'GET',
        url: `${CFG_GLPI.root_doc}/ajax/cable.php`,
        data: {
            action: 'get_item_breadcrum',
            items_id: items_id,
            itemtype: itemtype,
        }
    }).then((html_breadcrum) => {
        $(`#${CSS.escape(dom_to_update)}`).empty();
        $(`#${CSS.escape(dom_to_update)}`).append(html_breadcrum);
    });

}

function refreshNetworkPortDropdown(itemtype, items_id, dom_to_update) {
    // get networkport dropdown
    $.ajax({
        method: 'GET',
        url: `${CFG_GLPI.root_doc}/ajax/cable.php`,
        data: {
            action: 'get_networkport_dropdown',
            items_id: items_id,
            itemtype: itemtype,
        }
    }).then((html_data) => {
        $(`#${CSS.escape(dom_to_update)}`).empty();
        $(`#${CSS.escape(dom_to_update)}`).append(html_data);
    });
}

function refreshSocketDropdown(itemtype, items_id, socketmodels_id, dom_name) {
    // get networkport dropdown
    $.ajax({
        method: 'GET',
        url: `${CFG_GLPI.root_doc}/ajax/cable.php`,
        data: {
            action: 'get_socket_dropdown',
            items_id: items_id,
            itemtype: itemtype,
            socketmodels_id: socketmodels_id,
            dom_name: dom_name
        }
    }).then((html_data) => {
        const parent_dom = $(`select[name="${CSS.escape(dom_name)}"]`).parent().parent();
        parent_dom.empty();
        parent_dom.append(html_data);
    });
}

/* eslint-disable no-undef */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        refreshAssetBreadcrumb,
        refreshNetworkPortDropdown,
        refreshSocketDropdown
    };
}

/* eslint no-var: 0 */
/* global glpi_alert, initMessagesAfterRedirectToasts */

/*
 * Redefine 'window.alert' javascript function by a prettier dialog.
 */
window.old_alert = window.alert;
window.alert = function(message, caption) {
    // Don't apply methods on undefined objects... ;-) #3866
    if(typeof message == 'string') {
        message = message.replaceAll("\n", '<br>');
    }
    caption = caption || _n('Information', 'Information', 1);

    glpi_alert({
        title: caption,
        message: message,
    });
};

window.displayAjaxMessageAfterRedirect = function() {
    var display_container = ($('#messages_after_redirect').length  == 0);

    $.ajax({
        url: `${CFG_GLPI.root_doc}/ajax/displayMessageAfterRedirect.php`,
        data: {
            'display_container': display_container
        },
        success: function(html) {
            if (display_container) {
                $('body').append(html);
            } else {
                $('#messages_after_redirect').append(html);
                initMessagesAfterRedirectToasts();
            }
        }
    });
};

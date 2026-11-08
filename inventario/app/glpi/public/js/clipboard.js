$(() => {
    // set a function to track drag hover event
    $(document).on("click", ".copy_to_clipboard_wrapper", (event) => {

        // find the good element
        let target = $(event.target);
        if (target.attr('class') == 'copy_to_clipboard_wrapper') {
            target = target.find('*');
        }

        // copy text
        target.select();
        let succeed;
        try {
            succeed = document.execCommand("copy");
        } catch {
            succeed = false;
        }
        target.blur();

        // indicate success
        if (succeed) {
            $('.copy_to_clipboard_wrapper.copied').removeClass('copied');
            target.parent('.copy_to_clipboard_wrapper').addClass('copied');
        } else {
            target.parent('.copy_to_clipboard_wrapper').addClass('copyfail');
        }
    });

    /**
     * For each input of type text with name 'name'
     * if the input get a paste event, the text is trimmed before being pasted
     */
    $(document).on("paste", "input[type='text'][name='name']", (event) => {
        event.preventDefault();
        const pastedData = event.originalEvent.clipboardData || window.clipboardData;
        const pastedText = pastedData.getData('text');
        document.execCommand('insertText', false, pastedText.trim());
    });
});

/**
 * Copy a text to the clipboard
 *
 * @param {string} text
 *
 * @return {void}
 */
function copyTextToClipboard(text) {
    // Create a textarea to be able to select its content
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', ''); // readonly to prevent focus
    textarea.style = { position: 'absolute', visibility: 'hidden' };
    document.body.appendChild(textarea);

    // Select and copy text to clipboard
    textarea.select();
    document.execCommand('copy');

    // Remove textarea
    document.body.removeChild(textarea);
}

/* global tinymce */
/* global _ */

window.GLPI = window.GLPI || {};
window.GLPI.RichText = window.GLPI.RichText || {};

/**
 * User mention rich text autocompleter.
 *
 * @since 10.0.0
 */
window.GLPI.RichText.UserMention = class {

    /**
    * @param {Editor} editor
    * @param {number} activeEntity
    * @param {string} idorToken
    * @param {Array} mentionsOptions
    */
    constructor(editor, activeEntity, idorToken, mentionsOptions) {
        this.editor = editor;
        this.activeEntity = activeEntity;
        this.idorToken = idorToken;
        this.mentionsOptions = mentionsOptions;
    }

    /**
    * Register as autocompleter to editor.
    *
    * @returns {void}
    */
    register() {
        // Register autocompleter
        this.editor.ui.registry.addAutocompleter(
            'user_mention',
            {
                trigger: '@',
                minChars: 0,
                fetch: (pattern) => {
                    return this.fetchItems(pattern);
                },
                onAction: (autocompleteApi, range, value) => {
                    this.mentionUser(autocompleteApi, range, value);
                }
            }
        );
    }

    /**
    * Fetch autocompleter items.
    *
    * @private
    *
    * @param {string} pattern
    *
    * @returns {Promise}
    */
    fetchItems(pattern) {
        return new Promise(
            (resolve) => {
                $.post(
                    `${CFG_GLPI.root_doc}/ajax/getDropdownUsers.php`,
                    {
                        entity_restrict: this.activeEntity,
                        right: 'all',
                        display_emptychoice: 0,
                        searchText: pattern,
                        _idor_token: this.idorToken,
                    }
                ).then(
                    (data) => {
                        let results = data.results;

                        if (!this.mentionsOptions.full) {
                            const allowedIds = this.mentionsOptions.users;
                            results = results.filter(user => allowedIds.includes(user.id));
                        }

                        const items = results.map(
                            (user) => {
                                return {
                                    type: 'autocompleteitem',
                                    value: JSON.stringify({id: user.id, name: user.text}),
                                    text: user.text,
                                    // TODO user picture icon: ''
                                };
                            }
                        );
                        resolve(items);
                    }
                );
            }
        );
    }

    /**
    * Add mention to selected user in editor.
    *
    * @private
    *
    * @param {AutocompleterInstanceApi} autocompleteApi
    * @param {Range} range
    * @param {string} value
    *
    * @returns {void}
    */
    mentionUser(autocompleteApi, range, value) {
        const user = JSON.parse(value);

        this.editor.selection.setRng(range);
        this.editor.insertContent(this.generateUserMentionHtml(user));

        autocompleteApi.hide();
    }

    /**
    * Generates HTML code to insert in editor.
    *
    * @private
    *
    * @param {Object} user
    *
    * @returns {string}
    */
    generateUserMentionHtml(user) {
        return `<span contenteditable="false"
                    data-user-mention="true"
                    data-user-id="${_.escape(user.id)}">@${_.escape(user.name)}</span>&nbsp;`;
    }
};

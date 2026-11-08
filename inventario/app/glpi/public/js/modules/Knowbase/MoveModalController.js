import { post } from "/js/modules/Ajax.js";

/**
 * The "Move article" modal: posts one reparenting to the same endpoint the
 * aside drag uses, then reloads so the tree comes back from the server.
 */
export class GlpiKnowbaseMoveModalController
{
    /** @type {HTMLFormElement|null} */
    #form = null;

    /** @type {HTMLSelectElement|null} */
    #select = null;

    /** @type {HTMLButtonElement|null} */
    #submit = null;

    /** @type {string} Selection on open, i.e. the current parent. */
    #initial_value = '';

    /**
     * @param {HTMLElement} modal
     */
    constructor(modal)
    {
        this.#form = modal.querySelector('[data-glpi-kb-move-form]');
        if (!this.#form) {
            return;
        }

        this.#select = this.#form.querySelector('select[name="to_parent_id"]');
        this.#submit = this.#form.querySelector('[data-glpi-kb-move-submit]');
        this.#initial_value = this.#select.value;

        // Note: select2 events only work with jQuery handlers
        $(this.#select).on('change', () => this.#refreshSubmitState());
        this.#form.addEventListener('submit', (e) => this.#onSubmit(e));
    }

    #refreshSubmitState()
    {
        this.#submit.disabled = this.#select.value === this.#initial_value;
    }

    /**
     * @param {SubmitEvent} e
     */
    async #onSubmit(e)
    {
        e.preventDefault();
        // Also guards against a double click posting twice.
        this.#submit.disabled = true;

        const id = this.#form.dataset.glpiKbMoveArticleId;
        const from_parent_id = Number(
            this.#form.querySelector('input[name="from_parent_id"]').value
        );

        try {
            await post(`Knowbase/Aside/Article/${encodeURIComponent(id)}/Move`, {
                from_parent_id,
                to_parent_id: Number(this.#select.value),
            });
            // Reload rather than mutate the tree: the server is the only state that cannot drift.
            window.location.reload();
        } catch {
            // `post()` already raised a toast; keep the modal open to correct the selection.
            this.#refreshSubmitState();
        }
    }
}

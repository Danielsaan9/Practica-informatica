import { extractAnchor } from '/js/modules/Knowbase/CommentAnchor.js';
import { buildDomTextIndex, domRangeToOffsets } from '/js/modules/Knowbase/DomTextIndex.js';

/**
 * Minimal "Comment"-only bubble shown on text selection in a KB article that
 * isn't being edited. Reuses `.bubble-menu` styling for visual consistency.
 */
export class ReadModeSelectionBubble {
    /** @type {Element} */
    #container;

    /** @type {HTMLElement} */
    #bubble;

    /** @type {HTMLButtonElement} */
    #button;

    /** @type {number} */
    #max_anchor_length;

    /** @type {() => void} */
    #onSelectionChange = () => this.#update();

    /** @type {(event: KeyboardEvent) => void} */
    #onKeyDown = (event) => this.#handleKeyDown(event);

    /** @type {(event: FocusEvent) => void} */
    #onFocusIn = (event) => this.#handleFocusIn(event);

    /** True right after a selection was made and nothing else took focus since. */
    #justSelected = false;

    /**
     * @param {Element} container - The KB article content container
     *  (`[data-glpi-kb-content]`).
     * @param {number} max_anchor_length - Longest quotable passage; also enforced server-side.
     */
    constructor(container, max_anchor_length) {
        this.#container = container;
        this.#max_anchor_length = max_anchor_length;
        this.#bubble = this.#createBubble();
        document.addEventListener('selectionchange', this.#onSelectionChange);
        document.addEventListener('keydown', this.#onKeyDown);
        document.addEventListener('focusin', this.#onFocusIn);
    }

    /** Any focus elsewhere means the selection is no longer "fresh". */
    #handleFocusIn(event) {
        if (event.target !== this.#button) {
            this.#justSelected = false;
        }
    }

    /**
     * Let a keyboard user reach the comment button directly from a text selection
     * @param {KeyboardEvent} event
     */
    #handleKeyDown(event) {
        if (event.key !== 'Tab' || event.shiftKey) {
            return;
        }
        if (this.#bubble.style.display === 'none' || this.#button.disabled) {
            return;
        }
        if (!this.#justSelected) {
            return;
        }
        event.preventDefault();
        this.#justSelected = false;
        this.#button.focus();
    }

    #createBubble() {
        const bubble = document.createElement('div');
        bubble.className = 'bubble-menu kb-read-mode-comment-bubble';
        bubble.style.position = 'absolute';
        bubble.style.width = 'max-content';
        bubble.style.display = 'none';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'bubble-menu-btn';

        const label = document.createElement('span');
        label.textContent = _x('button', 'Comment');
        button.appendChild(label);

        const icon = document.createElement('i');
        icon.className = 'ti ti-message-circle-plus';
        // Decorative: the label already names the button.
        icon.setAttribute('aria-hidden', 'true');
        button.appendChild(icon);

        button.addEventListener('mousedown', (e) => {
            // Prevent the browser from clearing the selection before click fires.
            e.preventDefault();
        });
        button.addEventListener('click', () => this.#dispatchCommentSelection());
        button.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                e.preventDefault();
                this.#hide();
                button.blur();
            }
        });

        bubble.appendChild(button);
        this.#button = button;
        return bubble;
    }

    /** @param {number} length - Length of the currently selected text. */
    #setButtonAvailability(length) {
        this.#button.disabled = length > this.#max_anchor_length;
    }

    /**
     * True if a live, editable Tiptap instance owns selection instead of this module.
     * @returns {boolean}
     */
    #isEditing() {
        return this.#container.querySelector('.ProseMirror[contenteditable="true"]') !== null;
    }

    #update() {
        if (this.#isEditing()) {
            this.#hide();
            return;
        }

        const selection = document.getSelection();
        if (!selection || selection.isCollapsed || selection.rangeCount === 0) {
            this.#hide();
            return;
        }

        const range = selection.getRangeAt(0);
        if (!this.#container.contains(range.commonAncestorContainer)) {
            this.#hide();
            return;
        }

        this.#setButtonAvailability(range.toString().length);
        this.#show(range);
    }

    #show(range) {
        if (!this.#bubble.isConnected) {
            document.body.appendChild(this.#bubble);
        }

        const rect = range.getBoundingClientRect();
        this.#bubble.style.display = '';

        // Centered on the selection's bounding box, clamped to the viewport.
        const max_left = document.documentElement.clientWidth - this.#bubble.offsetWidth;
        const left = Math.min(Math.max(0, rect.left + (rect.width - this.#bubble.offsetWidth) / 2), max_left);

        this.#bubble.style.top = `${window.scrollY + rect.top - this.#bubble.offsetHeight - 8}px`;
        this.#bubble.style.left = `${window.scrollX + left}px`;
        this.#justSelected = true;
    }

    #hide() {
        this.#bubble.style.display = 'none';
        this.#justSelected = false;
    }

    #dispatchCommentSelection() {
        const selection = document.getSelection();
        if (!selection || selection.isCollapsed || selection.rangeCount === 0) {
            return;
        }

        const range = selection.getRangeAt(0);
        const index = buildDomTextIndex(this.#container);
        const offsets = domRangeToOffsets(index, range);
        if (!offsets) {
            return;
        }

        const [start, end] = offsets;
        const anchor = extractAnchor(index.text, start, end);

        // The button is gated on range.toString(); re-check what actually gets stored.
        if (anchor.exact.length > this.#max_anchor_length) {
            return;
        }

        this.#hide();
        this.#container.dispatchEvent(new CustomEvent('glpi:kb:comment-selection', {
            bubbles: true,
            detail: { anchor },
        }));
    }
}

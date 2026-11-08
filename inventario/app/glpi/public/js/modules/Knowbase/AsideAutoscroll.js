/**
 * Edge-triggered scrolling while a pointer gesture is in progress.
 *
 * Owns the scrolled element and the animation frame so the gesture controller
 * does not have to: `start()` on arming, `update()` per pointer move, `stop()`
 * on teardown.
 */
export class GlpiKnowbaseAsideAutoscroll
{
    /** Distance to the scroll container edge that triggers scrolling. */
    static #EDGE_PX = 40;

    /** Pixels scrolled per frame. */
    static #STEP_PX = 8;

    /** @type {Element|null} Element actually scrolled. */
    #container = null;

    /** @type {boolean} Whether that element has anything to scroll at all. */
    #enabled = false;

    /** @type {number} -1 up, 1 down, 0 idle. */
    #direction = 0;

    /** @type {number|null} */
    #frame = null;

    /**
     * @param {HTMLElement} tree
     */
    start(tree)
    {
        // Idempotent: a restart without stop would orphan the running frame.
        this.stop();
        this.#container = this.#resolveContainer(tree);
        // Nothing to scroll: stay idle instead of scrolling nowhere.
        this.#enabled = this.#container.scrollHeight > this.#container.clientHeight;
    }

    /**
     * @param {number} y
     */
    update(y)
    {
        if (!this.#enabled) {
            return;
        }

        // Measured on the scrolled element: "near the edge" means the edge that scrolls.
        const bounds = this.#bounds();
        const edge = GlpiKnowbaseAsideAutoscroll.#EDGE_PX;

        if (y < bounds.top + edge) {
            this.#direction = -1;
        } else if (y > bounds.bottom - edge) {
            this.#direction = 1;
        } else {
            this.#direction = 0;
        }

        if (this.#direction !== 0 && this.#frame === null) {
            this.#frame = window.requestAnimationFrame(() => this.#step());
        }
    }

    stop()
    {
        if (this.#frame !== null) {
            window.cancelAnimationFrame(this.#frame);
        }
        this.#frame = null;
        this.#direction = 0;
        this.#container = null;
        this.#enabled = false;
    }

    #step()
    {
        this.#frame = null;
        if (this.#direction === 0) {
            return;
        }
        this.#container.scrollTop += this.#direction * GlpiKnowbaseAsideAutoscroll.#STEP_PX;
        this.#frame = window.requestAnimationFrame(() => this.#step());
    }

    /**
     * Nearest scrollable ancestor of the tree: depending on the layout, the
     * tree container itself is not always the element that scrolls.
     *
     * @param {HTMLElement} tree
     * @returns {Element}
     */
    #resolveContainer(tree)
    {
        for (let node = tree; node instanceof HTMLElement; node = node.parentElement) {
            const overflow = window.getComputedStyle(node).overflowY;
            if (
                node.scrollHeight > node.clientHeight
                && (overflow === 'auto' || overflow === 'scroll')
            ) {
                return node;
            }
        }
        return document.scrollingElement ?? document.documentElement;
    }

    /**
     * Viewport-space top and bottom edges of the scrolled element. The page
     * scroller has no meaningful rect of its own: its edges are the viewport's.
     *
     * @returns {{ top: number, bottom: number }}
     */
    #bounds()
    {
        const page_scroller = document.scrollingElement ?? document.documentElement;
        if (this.#container === page_scroller || this.#container === document.body) {
            return { top: 0, bottom: window.innerHeight };
        }

        const rect = this.#container.getBoundingClientRect();
        return { top: rect.top, bottom: rect.bottom };
    }
}

import { GlpiFormEditorConvertedExtractedDefaultValue, DATATYPE } from "/js/modules/Forms/EditorConvertedExtractedDefaultValue.js";

/**
 * Represents a converted extracted default value for selectable fields (dropdowns, checkboxes, etc.)
 *
 * @extends GlpiFormEditorConvertedExtractedDefaultValue
 */
export class GlpiFormEditorConvertedExtractedSelectableDefaultValue extends GlpiFormEditorConvertedExtractedDefaultValue {
    /**
     * The selectable options with their values and states
     * @type {Object<string, {value: string, checked: boolean, uuid: string, order: number}>}
     * @private
     */
    #options;

    /**
     * Creates a new selectable default value instance
     *
     * @param {Object<string, {value: string, checked: boolean, uuid: string, order: number}>} options - The selectable options
     */
    constructor(options) {
        super(DATATYPE.ARRAY_OF_STRINGS, Object.entries(options).map((values) => values[1].value));
        this.#options = options;
    }

    /**
     * Gets the selectable options
     *
     * @returns {Object<string, {value: string, checked: boolean, uuid: string}>} The options
     */
    getOptions() {
        return this.#options;
    }
}

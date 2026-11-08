export class GlpiFormEditorConvertedExtractedDefaultValue
{
    /**
     * Enum for data types
     */
    static DATATYPE = {
        STRING: 'string',
        ARRAY_OF_STRINGS: 'array_of_strings',
        DROPDOWN_ID: 'dropdown_id',
        ARRAY_OF_DROPDOWN_IDS: 'array_of_dropdown_ids',
    };

    /**
     * @type {string}
     */
    #datatype;

    /**
     * @type {mixed}
     */
    #defaultValue;

    /**
     * @param {string} datatype
     * @param {mixed} defaultValue
     */
    constructor(datatype, defaultValue) {
        this.#datatype = datatype;
        this.#defaultValue = defaultValue;
    }

    /**
     * @returns {string}
     */
    getDatatype() {
        return this.#datatype;
    }

    /**
     * @returns {mixed}
     */
    getDefaultValue() {
        return this.#defaultValue;
    }
}

export const DATATYPE = GlpiFormEditorConvertedExtractedDefaultValue.DATATYPE;

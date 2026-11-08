import { BaseConditionEditorController } from '/js/modules/Forms/BaseConditionEditorController.js';

export class GlpiFormConditionValidationEditorController extends BaseConditionEditorController {
    constructor(container, item_uuid, item_type, forms_sections, form_questions, form_comments) {
        super(
            container,
            item_uuid,
            item_type,
            forms_sections,
            form_questions,
            form_comments,
            `${CFG_GLPI.root_doc}/Form/Condition/Validation/Editor`
        );
    }
}

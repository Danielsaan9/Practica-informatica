import { GlpiFormDestinationAutoConfigController } from "/js/modules/Forms/DestinationAutoConfigController.js";
import { GlpiFormDestinationConditionController } from "/js/modules/Forms/DestinationConditionController.js";

export class GlpiFormDestinationAccordionController
{
    constructor() {
        this.#watchForAccordionToggle();
    }

    triggerWatchers() {
        new GlpiFormDestinationAutoConfigController();
        new GlpiFormDestinationConditionController();
    }

    #watchForAccordionToggle() {
        const accordionWrapper = document.querySelector('#glpi-destinations-accordion');

        accordionWrapper.addEventListener('show.bs.collapse', async (e) => {
            const accordionItem = e.target;
            const accordionItemContent = accordionItem.querySelector('.accordion-body');
            if (accordionItemContent.innerHTML.trim() !== '') {
                return;
            }

            accordionItemContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary mb-3" role="status"></div></div>';

            const content = await $.ajax({
                url: `${CFG_GLPI.root_doc}/Form/${accordionItem.dataset.form}/Destinations/${accordionItem.dataset.formDestination}`,
                method: 'GET',
            });

            // Note: must use `$().html` to make sure we trigger scripts
            $(accordionItemContent).html(content);

            // We trigger the watcher
            this.triggerWatchers();
        });

        accordionWrapper.classList.remove('pe-none');
    }
}

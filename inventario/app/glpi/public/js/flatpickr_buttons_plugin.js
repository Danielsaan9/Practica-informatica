window.CustomFlatpickrButtons = (config = {}) => {

    return (fp) => {
        let wrapper;
        let buttonsInitialized = false;

        const initButtons = () => {
            // Avoid initializing buttons multiple times
            if (buttonsInitialized) {
                return;
            }

            if (config.buttons === undefined) {
                config.buttons = [{
                    label: fp.config.enableTime ? __('Now') : __('Today'),
                    attributes: {
                        'class': 'btn btn-outline-secondary'
                    },
                    onClick: (e, fp) => {
                        fp.setDate(new Date());
                    }
                },
                {
                    label: __('Save'),
                    attributes: {
                        'class': 'btn btn-primary'
                    },
                    onClick: (e, fp) => {
                        const current_date = new Date();
                        // If the input is empty, we need to set a date
                        if ($(fp.input).val().length == 0) {
                            if (fp.config.enableTime) {
                                // Reuse the default date that is displayed to the user
                                fp.setDate(`${current_date.getFullYear()}-${current_date.getMonth() + 1}-${current_date.getDate()} ${fp.config.defaultHour}:${fp.config.defaultMinute}:${fp.config.defaultSeconds}`);
                            } else {
                                // No time, we can directly use the current date as it is the default value
                                fp.setDate(new Date());
                            }
                        }
                        fp.close();
                    }
                }];
            }

            wrapper = `<div class="flatpickr-custom-buttons pb-1 text-start"><div class="buttons-container">`;

            (config.buttons).forEach((b, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.classList.add('ms-2');
                button.innerHTML = b.label;
                button.setAttribute('btn-id', index);
                if (typeof b.attributes !== 'undefined') {
                    Object.keys(b.attributes).forEach((key) => {
                        if (key === 'class') {
                            button.classList.add(...b.attributes[key].split(' '));
                            return;
                        }

                        button.setAttribute(key, b.attributes[key]);
                    });
                }

                wrapper += button.outerHTML;

                fp.pluginElements.push(button);
            });
            wrapper += '</div></div>';

            fp.calendarContainer.appendChild($.parseHTML(wrapper)[0]);

            $(fp.calendarContainer).on('click', '.flatpickr-custom-buttons button', (e) => {
                e.stopPropagation();
                e.preventDefault();

                const btn = $(e.target);
                const btn_id = btn.attr('btn-id');
                const click_handler = config.buttons[btn_id].onClick;

                if (typeof click_handler !== 'function') {
                    return;
                }

                click_handler(e, fp);
            });

            buttonsInitialized = true;
        };

        return {
            onReady: () => {
                // Calendar container is ready, but buttons will be created on first open
            },

            onOpen: () => {
                // Initialize buttons when calendar opens to ensure translations are loaded
                initButtons();
            },

            onDestroy: () => {
                if (wrapper) {
                    $(wrapper).remove();
                }
                buttonsInitialized = false;
            },
        };
    };
};

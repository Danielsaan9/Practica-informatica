/* eslint no-var: 0 */
/* global reloadTab */

$(() => {
    var bindShowFiltersBtn = function () {
        $('.show_filters').on('click', showFilters);
    };

    var showFilters = function (event) {
        event.preventDefault();

        // Toggle filters
        if ($('.show_filters').hasClass('active')) {
            reloadTab('');
        } else {
            reloadTab('filters[active]=1');
        }
    };

    var delay_timer = null;

    var bindFilterChange = function () {
        // Workaround to prevent opening of dropdown when removing item using the "x" button.
        // Without this workaround, orphan dropdowns remains in page when reloading tab.
        $(document).on('select2:unselecting', '.filter_row .select2-hidden-accessible', (ev) => {
            if (ev.params.args.originalEvent) {
                ev.params.args.originalEvent.stopPropagation();
            }
        });

        $('.filter_row [name^="filters\\["]').on('input', () => {
            clearTimeout(delay_timer);
            delay_timer = setTimeout(() => {
                handleFilterChange();
            }, 800);
        });
        $('.filter_row select[name^="filters\\["]').on('change', handleFilterChange);

        // prevent submit of parent form when pressing enter
        $('.filter_row [name^="filters\\["]').on('keypress', (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                handleFilterChange();
            }
        });
    };

    var handleFilterChange = function () {
        if (delay_timer !== null) {
            clearTimeout(delay_timer);
        }
        // Prevent dropdown to remain in page after tab has been reload.
        $('.filter_row .select2-hidden-accessible').select2('close');

        reloadTab($('[name^="filters\\["]').serialize());
    };

    $('main').on('glpi.tab.loaded', () => {
        bindShowFiltersBtn();
        bindFilterChange();
    });
});

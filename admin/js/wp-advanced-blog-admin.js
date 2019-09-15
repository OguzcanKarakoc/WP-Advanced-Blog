(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    console.log('LOOL');

    function getMonthName(v) {
        const n = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return n[v]
    }

    function getDayName(v) {
        const n = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        return n[v]
    }

    /**
     * Get field list based on the compare functionality
     *
     * @param compare
     * @return {{fieldTypes: string[], multi: boolean}}
     */
    function getFieldList(compare) {
        switch (compare) {
            case '=':
            case '!=':
            case '>':
            case '>=':
            case  '<':
            case '<=':
                return {multi: false, fieldTypes: ['compare', 'day', 'month', 'year', 'dayofweek', 'dayofyear']};
            case 'IN':
            case 'NOT IN':
                return {multi: true, fieldTypes: ['compare', 'day', 'month', 'year', 'dayofweek', 'dayofyear']};
            case 'BETWEEN':
            case  'NOT BETWEEN':
                return {multi: false, fieldTypes: ['compare', 'before', 'after']};
        }
    }

    $('div.root').on('click', 'a.wp-advanced-feed', function (event) {
        let $this = $(this);
        $this.parent().parent().remove()
    });

    $('body').on('click', 'button.add-date-filter', function (event) {
        let $this = $(this);
        let compare = $this.parent().find('select').find(':selected').val();
        // Get default field name
        let field = $this.parent().find('input[type=hidden]');
        let fieldName = field.val();
        let page = $this.parent().parent().find('div.root');

        // Root element
        let root = $('<div />');
        // get list of input fields for each different compare option
        let fielList = getFieldList(compare);
        for (let fieldType of fielList.fieldTypes) {
            root = generateField(root, fielList.multi, fieldType, fieldName, compare)
        }
        root.append($('<span id="delete-link" />', {
            'id': 'delete-link'
        }).append($('<a />', {
            'class': 'delete wp-advanced-feed',
            text: 'Remove filter'
        })));
        root.append('<hr />');
        page.append(root);

        // Update field name
        let match = fieldName.match(/\[([0-9]{1,5})]$/);
        field.val(fieldName.replace(match[0], `[${parseInt(match[1]) + 1}]`));
        console.log({this: $this, compare: compare, match, fieldName: fieldName, number: parseInt(match[1]) + 1})
    });

    function generateField(root, multi, fieldType, fieldName, compare = null) {
        let p = $('<p />');
        let select = null;
        let name = `${fieldName}[${fieldType}]`;
        let label = $('<label />', {'for': name});
        let date = new Date();

        if (fieldType !== 'before' && fieldType !== 'after' && fieldType !== 'compare') {
            select = $('<select />', {
                'type': 'text',
                'name': name,
                'id': name,
                'class': 'widefat'
            });
            if (multi) {
                select.prop('multiple', true);
                select.attr('name', name + '[]')
            }
        }
        switch (fieldType) {
            case 'compare':
                p.append($('<b />', {text: `Compare option: ${compare}`}));
                p.append($('<input />', {
                    type: 'hidden',
                    'name': fieldName + '[compare]',
                    'value': compare
                }));
                break;
            case 'month':
                label.html('Month');
                select.append($('<option />', {'value': '', text: '---'}));
                for (let m = 1; m <= 12; m++) {
                    select.append($('<option />', {'value': m, text: getMonthName(m - 1)}));
                }
                p.append(label);
                p.append(select);
                break;
            case 'day':
                label.html('Day');
                select.append($('<option />', {'value': '', text: '---'}));
                for (let d = 1; d <= 31; d++) {
                    select.append($('<option />', {'value': d, text: d}));
                }
                p.append(label);
                p.append(select);
                break;
            case 'year':
                label.html('Year');
                select.append($('<option />', {'value': '', text: '---'}));
                for (let y = date.getFullYear(); y >= date.getFullYear() - 60; y--) {
                    select.append($('<option />', {'value': y, text: y}));
                }
                p.append(label);
                p.append(select);
                break;
            case 'dayofweek':
                label.html('Day of week');
                select.append($('<option />', {'value': '', text: '---'}));
                for (let d = 1; d <= 7; d++) {
                    select.append($('<option />', {'value': d, text: getDayName(d - 1)}));
                }
                p.append(label);
                p.append(select);
                break;
            case 'dayofyear':
                label.html('Day of year');
                select.append($('<option />', {'value': '', text: '---'}));
                for (let d = 1; d <= 366; d++) {
                    select.append($('<option />', {'value': d, text: d}));
                }
                p.append(label);
                p.append(select);
                break;
            case 'before':
            case 'after':
                label.html(fieldType === 'before' ? 'Before' : 'After');
                let inputDate = $('<input />', {
                    'type': 'date',
                    'name': name + '[date]',
                    'id': name + '[date]',
                    'class': 'widefat'
                });
                let inputTime = $('<input />', {
                    'type': 'time',
                    'name': name + '[time]',
                    'id': name + '[time]',
                    'class': 'widefat'
                });
                p.append(label);
                p.append(inputDate);
                p.append(inputTime);
                break;
        }
        p.appendTo(root);
        return root
    }

})(jQuery);

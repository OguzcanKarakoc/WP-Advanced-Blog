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
        const n = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday",];
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

    $('button.add-date-filter').on('click', function (event) {
        let $this = $(this);
        let compare = $this.parent().find('select').find(':selected').val();
        let field = $this.parent().find('input[type=hidden]');
        let fieldName = field.val();
        // let root = $('<div />');
        // let fielList = getFieldList(compare);
        // for (let fieldType of fielList.fieldTypes) {
        //     root = generateField(root, fielList.multi, fieldType, fieldName, compare)
        // }
        let match = fieldName.match(/\[([0-9]{1,5})]$/)[1];
        field.val(fieldName.replace(match[0], `[${Number(match[1]) + 1}]`));
        console.log({this: $this, compare: compare, match, fieldName: fieldName,})
    });

    function generateField(root, multi, fieldType, fieldName, compare = null) {
        let p = $('<p />');
        let select = null;
        let label = null;
        let date = new Date();

        switch (fieldType) {
            case 'compare':
                p.append('<input />', {
                    'type': 'hidden',
                    'name': fieldName + '[compare]',
                    'value': compare
                });
                break;
            case 'month':
                label = $('<label />', {
                    'for': fieldName + '[month]'
                });
                select = $('<select />', {
                    'type': 'text',
                    'name': fieldName + '[month]',
                    'id': fieldName + '[month]',
                    'class': 'widefat'
                });
                for (let m = 1; m <= 12; m++) {
                    select.append($('<option />', {
                        'value': m,
                        textContent: getMonthName(m)
                    }));
                }
                p.append(label);
                p.append(select);
                break;
            case 'day':
                label = $('<label />', {
                    'for': fieldName + '[day]'
                });
                select = $('<select />', {
                    'type': 'text',
                    'name': fieldName + '[day]',
                    'id': fieldName + '[day]',
                    'class': 'widefat'
                });

                for (let d = 1; d <= 31; d++) {
                    select.append($('<option />', {
                        'value': d,
                        textContent: d
                    }));
                }
                p.append(label);
                p.append(select);
                break;
            case 'year':
                label = $('<label />', {
                    'for': fieldName + '[year]'
                });
                select = $('<select />', {
                    'type': 'text',
                    'name': fieldName + '[year]',
                    'id': fieldName + '[year]',
                    'class': 'widefat'
                });
                for (let y = date.getFullYear(); y <= date.getFullYear() - 60; d--) {
                    select.append($('<option />', {
                        'value': y,
                        textContent: y
                    }));
                }
                p.append(label);
                p.append(select);
                break;
            case 'dayofweek':
                label = $('<label />', {
                    'for': fieldName + '[dayofweek]'
                });
                select = $('<select />', {
                    'type': 'text',
                    'name': fieldName + '[dayofweek]',
                    'id': fieldName + '[dayofweek]',
                    'class': 'widefat',
                });
                for (let d = 1; d <= 7; d++) {
                    select.append($('<option />', {
                        'value': d,
                        textContent: getDayName(d)
                    }));
                }
                p.append(label);
                p.append(select);
                break;
            case 'dayofyear':
                label = $('<label />', {
                    'for': fieldName + '[day]'
                });
                select = $('<select />', {
                    'type': 'text',
                    'name': fieldName + '[day]',
                    'id': fieldName + '[day]',
                    'class': 'widefat',
                });
                for (let d = 1; d <= 366; d++) {
                    select.append($('<option />', {
                        'value': d,
                        textContent: d
                    }));
                }
                p.append(label);
                p.append(select);
                break;
        }
        p.appendTo(root);
        return root

        //<div>
        // 						<?php
        // 						echo "<pre>";
        // 						var_dump( $instance );
        // 						echo "</pre>";
        // 						?>
        //                         <p>
        //                             <b>Compare option: <=</b>
        //                             <input type="hidden" name="<?= $this->get_field_name( $prefix . 'date_query[0][compare]' ) ?>" value="<=">
        //                         </p>

        //                         <span id="delete-link">
        //                             <a class="delete" href>Remove filter</a>
        //                         </span>
        //                         <hr/>
        //                     </div>
        //
        //                     <div>
        //                         <p>
        //                             <label for="compare">Compare</label>
        //                             <select class="widefat" name="compare" id="compare">
        // 								<?php
        // 								$array = [ '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' ];
        // 								foreach ( $array as $compare ) {
        // 									echo "<option value='{$compare}'>{$compare}</option>";
        // 								} ?>
        //                             </select>
        //                         </p>
        //                         <input type="hidden" value="<?= $this->get_field_name( $prefix . 'date_query[]' ) ?>">
        //                         <button type='button' class="button-secondary widefat add-date-filter"><span class="dashicons dashicons-plus-alt" style="vertical-align: text-top;"></span> Add date filter</button>
        //                     </div>
    }

})(jQuery);

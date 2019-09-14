<?php

class Wp_Advanced_Blog_Widget extends WP_Widget {
	private $prefix = 'wp-ab-';

	public function __construct() {
		parent::__construct(// Base ID of your widget
			false, // Widget name will appear in UI
			__( 'WP advanced blog' ), // Widget description
			[ 'description' => __( 'The most advanced blog plguin', 'wp-advanced-blog' ) ] );
	}

	public function update( $new_instance, $old_instance ) {
		$prefix                        = $this->prefix;
		$instance                      = $old_instance;
		$instance[ $prefix . 'title' ] = $new_instance[ $prefix . 'title' ];

		// author__not_in >> author__in
		$instance[ $prefix . 'author__in' ]     = esc_sql( $new_instance[ $prefix . 'author__in' ] );
		$instance[ $prefix . 'author__not_in' ] = esc_sql( $new_instance[ $prefix . 'author__not_in' ] );

		$instance[ $prefix . 'cache_results' ] = $new_instance[ $prefix . 'cache_results' ];

		// category__not_in >> category__in >> category__and >> cat
		$instance[ $prefix . 'cat' ]              = $new_instance[ $prefix . 'cat' ];
		$instance[ $prefix . 'category__and' ]    = $new_instance[ $prefix . 'category__and' ];
		$instance[ $prefix . 'category__in' ]     = $new_instance[ $prefix . 'category__in' ];
		$instance[ $prefix . 'category__not_in' ] = $new_instance[ $prefix . 'category__not_in' ];

		$instance[ $prefix . 'comment_count' ] = [
			'value'   => $new_instance[ $prefix . 'comment_count' ],
			'compare' => $new_instance[ $prefix . 'compare' ]
		];

		$instance[ $prefix . 'date_query' ] = esc_sql($new_instance[ $prefix . 'date_query' ]);

		$instance[ $prefix . 'author_inc' ]     = $new_instance[ $prefix . 'author' ];
		$instance[ $prefix . 'tag' ]            = $new_instance[ $prefix . 'tag' ];
		$instance[ $prefix . 'limit' ]          = $new_instance[ $prefix . 'limit' ];
		$instance[ $prefix . 'offset' ]         = $new_instance[ $prefix . 'offset' ];
		$instance[ $prefix . 'order_by' ]       = $new_instance[ $prefix . 'order_by' ];
		$instance[ $prefix . 'order' ]          = $new_instance[ $prefix . 'order' ];
		$instance[ $prefix . 'include' ]        = $new_instance[ $prefix . 'include' ];
		$instance[ $prefix . 'exclude' ]        = $new_instance[ $prefix . 'exclude' ];
		$instance[ $prefix . 'pagination' ]     = $new_instance[ $prefix . 'pagination' ];
		$instance[ $prefix . 'post_structure' ] = $new_instance[ $prefix . 'post_structure' ];

		return $instance;
	}

	public function form( $instance ) {
		$prefix  = $this->prefix;
		$authors = get_users( [
			'orderby' => 'name',
			'order'   => 'ASC',
			'number'  => '',
		] );

		$arr                   = [
			'cache_results'  => [
				'id'          => $this->get_field_id( $prefix . 'cache_results' ),
				'name'        => $this->get_field_name( $prefix . 'cache_results' ),
				'instance'    => $instance[ $prefix . 'cache_results' ],
				'label'       => 'Cache results',
				'description' => 'Whether to cache post information. (default = true)',
			],
			'title'          => [
				'id'       => $this->get_field_id( $prefix . 'title' ),
				'name'     => $this->get_field_name( $prefix . 'title' ),
				'instance' => $instance[ $prefix . 'title' ],
			],
			'category'       => [
				'id'       => $this->get_field_id( $prefix . 'category' ),
				'name'     => $this->get_field_name( $prefix . 'category[]' ),
				'instance' => $instance[ $prefix . 'category' ],
			],
			'tag'            => [
				'id'       => $this->get_field_id( $prefix . 'tag' ),
				'name'     => $this->get_field_name( $prefix . 'tag[]' ),
				'instance' => $instance[ $prefix . 'tag' ],
			],
			'limit'          => [
				'id'       => $this->get_field_id( $prefix . 'limit' ),
				'name'     => $this->get_field_name( $prefix . 'limit' ),
				'instance' => $instance[ $prefix . 'limit' ],
			],
			'offset'         => [
				'id'       => $this->get_field_id( $prefix . 'offset' ),
				'name'     => $this->get_field_name( $prefix . 'offset' ),
				'instance' => $instance[ $prefix . 'offset' ],
			],
			'order_by'       => [
				'id'       => $this->get_field_id( $prefix . 'order_by' ),
				'name'     => $this->get_field_name( $prefix . 'order_by' ),
				'instance' => $instance[ $prefix . 'order_by' ],
			],
			'order'          => [
				'id'       => $this->get_field_id( $prefix . 'order' ),
				'name'     => $this->get_field_name( $prefix . 'order' ),
				'instance' => $instance[ $prefix . 'order' ],
			],
			'include'        => [
				'id'          => $this->get_field_id( $prefix . 'include' ),
				'name'        => $this->get_field_name( $prefix . 'include' ),
				'instance'    => $instance[ $prefix . 'include' ],
				'description' => 'An array of post IDs to retrieve, sticky posts will be included.',
			],
			'exclude'        => [
				'id'          => $this->get_field_id( $prefix . 'exclude' ),
				'name'        => $this->get_field_name( $prefix . 'exclude' ),
				'instance'    => $instance[ $prefix . 'exclude' ],
				'description' => 'An array of post IDs not to retrieve.',
			],
			'post_structure' => [
				'id'       => $this->get_field_id( $prefix . 'post_structure' ),
				'name'     => $this->get_field_name( $prefix . 'post_structure' ),
				'instance' => $instance[ $prefix . 'post_structure' ],
			],
			'pagination'     => [
				'id'       => $this->get_field_id( $prefix . 'pagination' ),
				'name'     => $this->get_field_name( $prefix . 'pagination' ),
				'instance' => $instance[ $prefix . 'pagination' ],
			],
		];
		$order_by_allowed_keys = array(
			'author',
			'post_author',
			'date',
			'post_date',
			'title',
			'post_title',
			'name',
			'post_name',
			'modified',
			'post_modified',
			'modified_gmt',
			'post_modified_gmt',
			'menu_order',
			'parent',
			'post_parent',
			'ID',
			'rand',
			'comment_count',
		);
		$order_options         = [
			'ASC',
			'DESC'
		];
		$wpeditor              = [
			'textarea_name' => $arr['post_structure']['name']
		];


		?>
        <style>
            fieldset {
                display: block;
                margin-left: 2px;
                margin-right: 2px;
                padding-top: 0.35em;
                padding-bottom: 0.625em;
                padding-left: 0.75em;
                padding-right: 0.75em;
                border: 2px groove #dedede;
            }
        </style>
        <div>
            <fieldset>
                <legend hidden>Author</legend>
                <details>
                    <summary>Author</summary>
                    <p>
                        <small>You can choose to use either of these options, but not both at the same time. exclude will override include if it has been selected</small>
                    </p>
                    <p>
                        <label for="<?= $this->get_field_id( $prefix . 'author__in' ) ?>">
                            include<br/>
                            <small>Select the authors you want to include in this feed.</small>
                        </label>
                        <select class="widefat" name="<?= $this->get_field_name( $prefix . 'author__in[]' ) ?>"
                                id="<?= $this->get_field_id( $prefix . 'author__in' ) ?>" multiple="multiple">
							<?php foreach ( $authors as $author ) {
								$selected = ( in_array( $author->id, $instance[ $prefix . 'author__in' ] ) ) ? 'selected' : '';
								echo "<option value='{$author->id}' {$selected}>{$author->data->display_name}</option>";
							} ?>
                        </select>
                    </p>
                    <hr/>
                    <p>
                        <label for="<?= $this->get_field_id( $prefix . 'author__not_in' ) ?>">
                            exclude<br/>
                            <small>Select the authors you want to exclude in this feed.</small>
                        </label>
                        <select class="widefat" name="<?= $this->get_field_name( $prefix . 'author__not_in[]' ) ?>"
                                id="<?= $this->get_field_id( $prefix . 'author__not_in' ) ?>" multiple="multiple">
							<?php foreach ( $authors as $author ) {
								$selected = ( in_array( $author->id, $instance[ $prefix . 'author__not_in' ] ) ) ? 'selected' : '';
								echo "<option value='{$author->id}' {$selected}>{$author->data->display_name}</option>";
							} ?>
                        </select>
                    </p>
                </details>
            </fieldset>

            <p>
                <input type="hidden" name="<?= $this->get_field_name( $prefix . 'cache_results' ) ?>" value="0"/>
                <input type="checkbox" class="checkbox" id="<?= $this->get_field_id( $prefix . 'cache_results' ) ?>"
                       name="<?= $this->get_field_name( $prefix . 'cache_results' ) ?>" <?= ( $instance[ $prefix . 'cache_results' ] || empty( $instance[ $prefix . 'cache_results' ] ) ) ? 'checked' : '' ?>
                       value="1">
                <label for="<?= $this->get_field_id( $prefix . 'cache_results' ) ?>">
                    Cache results<br/>
                    <small>Whether to cache post information. (default = true)</small>
                </label>
            </p>

            <fieldset>
                <legend hidden>Category</legend>
                <details>
                    <summary>Category</summary>
                    <p>
                        <label for="<?= $this->get_field_id( $prefix . 'cat' ) ?>">
                            categories (first filter)<br/>
                            <small>Show a category and its children if this includes any of the selected categories, including the children of the selected category. (empty = all)</small>
                        </label>
                        <select class="widefat" name="<?= $this->get_field_name( $prefix . 'cat[]' ) ?>"
                                id="<?= $this->get_field_id( $prefix . 'cat' ) ?>" multiple="multiple">
							<?= $this->selectInputHierarchical( get_categories(), $instance[ $prefix . 'cat' ] ) ?>
                        </select>
                    </p>
                    <hr/>
                    <p>
                        <label for="<?= $this->get_field_id( $prefix . 'category__and' ) ?>">
                            categories and (second filter)<br/>
                            <small>only show posts that have all the categories defined here. (note that this filter will only apply to the posts that pass the first filter)</small>
                        </label>
                        <select class="widefat" name="<?= $this->get_field_name( $prefix . 'category__and[]' ) ?>"
                                id="<?= $this->get_field_id( $prefix . 'category__and' ) ?>" multiple="multiple">
							<?= $this->selectInputHierarchical( get_categories(), $instance[ $prefix . 'category__and' ] ) ?>
                        </select>
                    </p>
                    <hr/>
                    <p>
                        <label for="<?= $this->get_field_id( $prefix . 'category__in' ) ?>">
                            categories in (third filter)<br/>
                            <small>Only show posts that include the category selected, <b>not</b> including the children of the selected category. (note that this filter will only apply to the posts that pass the first and second filter)</small>
                        </label>
                        <select class="widefat" name="<?= $this->get_field_name( $prefix . 'category__in[]' ) ?>"
                                id="<?= $this->get_field_id( $prefix . 'category__in' ) ?>" multiple="multiple">
							<?= $this->selectInputHierarchical( get_categories(), $instance[ $prefix . 'category__in' ] ) ?>
                        </select>
                    </p>
                    <hr/>
                    <p>
                        <label for="<?= $this->get_field_id( $prefix . 'category__not_in' ) ?>">
                            categories not in<br/>
                            <small>Do not show posts that include the category selected, <b>not</b> including the children of the selected category. (note that this filter will only apply to the posts that pass the first, second and third filter)</small>
                        </label>
                        <select class="widefat" name="<?= $this->get_field_name( $prefix . 'category__not_in[]' ) ?> ?>"
                                id="<?= $this->get_field_id( $prefix . 'category__not_in' ) ?>" multiple="multiple">
							<?= $this->selectInputHierarchical( get_categories(), $instance[ $prefix . 'category__not_in' ] ) ?>
                        </select>
                    </p>
                </details>
            </fieldset>

            <p>
                <label for="<?= $this->get_field_id( $prefix . 'compare' ) ?>">
                    comment count<br/>
                    <small>Only show posts that fall under the criteria defined below. Leave empty to disable</small>
                </label>
            </p>
            <p style="display: inline">
                <select style="width: 60%" name="<?= $this->get_field_name( $prefix . 'compare' ) ?>"
                        id="<?= $this->get_field_id( $prefix . 'compare' ) ?>">
                    <option value="=" <?php selected( $instance[ $prefix . 'comment_count' ]['compare'], '=' ) ?>>equals</option>
                    <option value="!=" <?php selected( $instance[ $prefix . 'comment_count' ]['compare'], '!=' ) ?>>Does not equal</option>
                    <option value=">" <?php selected( $instance[ $prefix . 'comment_count' ]['compare'], '>' ) ?>>Greater then</option>
                    <option value=">=" <?php selected( $instance[ $prefix . 'comment_count' ]['compare'], '>=' ) ?>>Greater or equal to</option>
                    <option value="<" <?php selected( $instance[ $prefix . 'comment_count' ]['compare'], '<' ) ?>>Smaller then</option>
                    <option value="<=" <?php selected( $instance[ $prefix . 'comment_count' ]['compare'], '<=' ) ?>>Smaller or equal to</option>
                </select>
                <input style="width: 37%;vertical-align: middle;" id="<?= $this->get_field_id( $prefix . 'comment_count' ) ?>"
                       name="<?= $this->get_field_name( $prefix . 'comment_count' ) ?> ?>"
                       type="number" value="<?= $instance[ $prefix . 'comment_count' ]['value'] ?>"/>
            </p>

            <p>
                <label for="<?= $arr['title']['id'] ?>">Title</label>
                <input class="widefat" id="<?= $arr['title']['id'] ?>" name="<?= $arr['title']['name'] ?>" type="text" value="<?= esc_attr( $arr['title']['instance'] ) ?>"/>
            </p>


            <fieldset>
                <legend hidden>Date filters</legend>
                <details>
                    <summary>Date filters</summary>
                    <div>
						<?php
//						echo "<pre>";
//						var_dump( $instance );
//						echo "</pre>";
						?>
                        <p>
                            <b>Compare option: <=</b>
                            <input type="hidden" name="<?= $this->get_field_name( $prefix . 'date_query[0][compare]' ) ?>" value="<=">
                        </p>
                        <p>
                            <label for="<?= $this->get_field_id( $prefix . 'month' ) ?>">Month</label>
                            <select class="widefat" name="<?= $this->get_field_name( $prefix . 'date_query[0][month]' ) ?>" id="<?= $this->get_field_id( $prefix . 'month' ) ?>">
								<?php
								for ( $m = 1; $m <= 12; $m ++ ) {
									$month_label = date( 'F', mktime( 0, 0, 0, $m, 1 ) );
									echo "<option value='{$m}'>{$month_label}</option>";
								} ?>
                            </select>
                        </p>
                        <p>
                            <label for="<?= $this->get_field_id( $prefix . 'day' ) ?>">Day</label>
                            <select class="widefat" name="<?= $this->get_field_name( $prefix . 'date_query[0][day]' ) ?>" id="<?= $this->get_field_id( $prefix . 'day' ) ?>">
								<?php
								for ( $d = 1; $d <= 31; $d ++ ) {
									echo "<option value='{$d}'>{$d}</option>";
								} ?>
                            </select>
                        </p>
                        <p>
                            <label for="<?= $this->get_field_id( $prefix . 'date_query[0][year]' ) ?>">Year</label>
                            <select class="widefat" name="<?= $this->get_field_name( $prefix . 'year' ) ?>" id="<?= $this->get_field_id( $prefix . 'year' ) ?>">
								<?php
								$year = date( 'Y' );
								$min  = $year - 60;
								$max  = $year;
								for ( $y = $max; $y >= $min; $y -- ) {
									echo "<option value='{$y}'>{$y}</option>";
								} ?>
                            </select>
                        </p>

                        <p>
                            <label for="<?= $this->get_field_id( $prefix . 'dayofweek' ) ?>">Days of week</label>
                            <select class="widefat" name="<?= $this->get_field_name( $prefix . 'date_query[0][dayofweek][]' ) ?>" id="<?= $this->get_field_id( $prefix . 'dayofweek' ) ?>" multiple>
								<?php
								for ( $i = 0; $i < 7; $i ++ ) {
									$day = jddayofweek( $i, 1 );
									echo "<option value='{$i}'>{$day}</option>";
								} ?>
                            </select>
                        </p>

                        <p>
                            <label for="<?= $this->get_field_id( $prefix . 'dayofyear' ) ?>">Days of year</label>
                            <select class="widefat" name="<?= $this->get_field_name( $prefix . 'date_query[0][dayofyear][]' ) ?>" id="<?= $this->get_field_id( $prefix . 'dayofyear' ) ?>" multiple>
								<?php
								for ( $i = 0; $i < 366; $i ++ ) {
									echo "<option value='{$i}'>{$i}</option>";
								} ?>
                            </select>
                        </p>
                        <span id="delete-link">
                            <a class="delete" href>Remove filter</a>
                        </span>
                        <hr/>
                    </div>

                    <div>
                        <p>
                            <label for="compare">Compare</label>
                            <select class="widefat" name="compare" id="compare">
								<?php
								$array = [ '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' ];
								foreach ( $array as $compare ) {
									echo "<option value='{$compare}'>{$compare}</option>";
								} ?>
                            </select>
                        </p>
                        <input type="hidden" value="<?= $this->get_field_name( $prefix . 'date_query[0]' ) ?>">
                        <button type='button' class="button-secondary widefat add-date-filter"><span class="dashicons dashicons-plus-alt" style="vertical-align: text-top;"></span> Add date filter</button>
                    </div>
                </details>
            </fieldset>

            <div>
                <p>
                    <label for="<?= $arr['tag']['id'] ?>">Filter tags</label>
                    <select class="widefat" name="<?= $arr['tag']['name'] ?>" id="<?= $arr['tag']['id'] ?>" multiple>
						<?php foreach ( get_tags( [ 'hide_empty' => 0 ] ) as $tag ) {
							$selected = ( in_array( $tag->term_id, $arr['tag']['instance'] ) ) ? 'selected' : '';
							echo "<option value='{$tag->term_id}' {$selected}>{$tag->name}</option>";
						} ?>
                    </select>
                </p>
                <p>
                    <label for="<?= $arr['order']['id'] ?>">Order</label>
                    <select class="widefat" name="<?= $arr['order']['name'] ?>" id="<?= $arr['order']['id'] ?>">
						<?php foreach ( $order_by_allowed_keys as $order ) {
							$selected = ( $order == $arr['order']['instance'] ) ? 'selected' : '';
							echo "<option value='{$order}' {$selected}>" . str_replace( '_', ' ', $order ) . "</option>";
						} ?>
                    </select>
                </p>
                <p>
                    <label for="<?= $arr['order_by']['id'] ?>">Order By</label>
                    <select class="widefat" name="<?= $arr['order_by']['name'] ?>" id="<?= $arr['order_by']['id'] ?>">
						<?php foreach ( $order_options as $order ) {
							$selected = ( $order == $arr['order_by']['instance'] ) ? 'selected' : '';
							echo "<option value='{$order}' {$selected}>{$order}</option>";
						} ?>
                    </select>
                </p>
                <p>
                    <label for="<?= $arr['limit']['id'] ?>">Limit</label>
                    <input class="tiny-text" id="<?= $arr['limit']['id'] ?>" name="<?= $arr['limit']['name'] ?>" type="number" value="<?= esc_attr( $arr['limit']['instance'] ) ?>"/>
                </p>
                <p>
                    <label for="<?= $arr['offset']['id'] ?>">Offset</label>
                    <input class="tiny-text" id="<?= $arr['offset']['id'] ?>" name="<?= $arr['offset']['name'] ?>" type="number" value="<?= esc_attr( $arr['offset']['instance'] ) ?>"/>
                </p>
                <p>
                    <label for="<?= $arr['include']['id'] ?>">Include (comma separated)</label>
                    <input class="widefat" placeholder="145, 421" id="<?= $arr['include']['id'] ?>" name="<?= $arr['include']['name'] ?>" type="number" value="<?= esc_attr( $arr['include']['instance'] ) ?>"/>
                </p>
                <p>
                    <label for="<?= $arr['exclude']['id'] ?>">Exclude (comma separated)</label>
                    <input class="widefat" placeholder="145, 421" id="<?= $arr['exclude']['id'] ?>" name="<?= $arr['exclude']['name'] ?>" type="number" value="<?= esc_attr( $arr['exclude']['instance'] ) ?>"/>
                </p>
                <p>
                    <input type="checkbox" class="checkbox" id="<?= $arr['pagination']['id'] ?>" name="<?= $arr['pagination']['name'] ?>" value="<?= ( $arr['pagination']['instance'] == 'on' ) ? 'selected' : '' ?>">
                    <label for="<?= $arr['pagination']['id'] ?>">Pagination</label>
                </p>
                <p>
					<?php
					//                wp_editor(esc_attr($arr['post_structure']['instance']), 155, $wpeditor);
					?>
                    <label for="<?= $arr['post_structure']['id'] ?>">single post structure</label>
                    <textarea class="widefat" name="<?= $arr['post_structure']['name'] ?>" id="<?= $arr['post_structure']['id'] ?>" cols="30" rows="10">
                    <?= $arr['post_structure']['instance'] ?>
                </textarea>
                </p>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#date').datepicker({
                    dateFormat: "dd-mm-yy"
                });
            });
        </script>
		<?php

	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $post;
		$prefix = $this->prefix;
		/**
		 * urls:
		 * https://codex.wordpress.org/Class_Reference/WP_Query#Parameters
		 * https://codex.wordpress.org/Template_Tags/get_posts
		 * https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
		 */

		$postStructure = $instance['wp-ab-post_structure'];

//        $arr = [
//            'title' => [
//                'id' => $this->get_field_id($prefix . 'title'),
//                'name' => $this->get_field_name($prefix . 'title'),
//                'instance' => $instance[$prefix . 'title'],
//            ],
//            'category' => [
//                'id' => $this->get_field_id($prefix . 'category'),
//                'name' => $this->get_field_name($prefix . 'category[]'),
//                'instance' => $instance[$prefix . 'category'],
//            ],
//            'tag' => [
//                'id' => $this->get_field_id($prefix . 'tag'),
//                'name' => $this->get_field_name($prefix . 'tag[]'),
//                'instance' => $instance[$prefix . 'tag'],
//            ],
//            'limit' => [
//                'id' => $this->get_field_id($prefix . 'limit'),
//                'name' => $this->get_field_name($prefix . 'limit'),
//                'instance' => $instance[$prefix . 'limit'],
//            ],
//            'offset' => [
//                'id' => $this->get_field_id($prefix . 'offset'),
//                'name' => $this->get_field_name($prefix . 'offset'),
//                'instance' => $instance[$prefix . 'offset'],
//            ],
//            'order_by' => [
//                'id' => $this->get_field_id($prefix . 'order_by'),
//                'name' => $this->get_field_name($prefix . 'order_by'),
//                'instance' => $instance[$prefix . 'order_by'],
//            ],
//            'order' => [
//                'id' => $this->get_field_id($prefix . 'order'),
//                'name' => $this->get_field_name($prefix . 'order'),
//                'instance' => $instance[$prefix . 'order'],
//            ],
//            'include' => [
//                'id' => $this->get_field_id($prefix . 'include'),
//                'name' => $this->get_field_name($prefix . 'include'),
//                'instance' => $instance[$prefix . 'include'],
//                'description' => 'An array of post IDs to retrieve, sticky posts will be included.',
//            ],
//            'exclude' => [
//                'id' => $this->get_field_id($prefix . 'exclude'),
//                'name' => $this->get_field_name($prefix . 'exclude'),
//                'instance' => $instance[$prefix . 'exclude'],
//                'description' => 'An array of post IDs not to retrieve.',
//            ],
//            'post_structure' => [
//                'id' => $this->get_field_id($prefix . 'post_structure'),
//                'name' => $this->get_field_name($prefix . 'post_structure'),
//                'instance' => $instance[$prefix . 'post_structure'],
//            ],
//            'pagination' => [
//                'id' => $this->get_field_id($prefix . 'pagination'),
//                'name' => $this->get_field_name($prefix . 'pagination'),
//                'instance' => $instance[$prefix . 'pagination'],
//            ],
//        ];

		$postParameters = [
//            'author__in' => $instance[$prefix . 'author__in'],
//            'author__not_in' => $instance[$prefix . 'author__not_in'],
//            'cache_results' => (bool) $instance[$prefix . 'cache_results'],
//            'cat' => $instance[$prefix . 'cat'],
//            'category__and' => $instance[$prefix . 'category__and'],
//            'category__in' => $instance[$prefix . 'category__in'],
//            'category__not_in' => $instance[$prefix . 'category__not_in'],
//            'comment_count' => $instance[$prefix . 'comment_count']['value'], // TODO:: Test this value - https://developer.wordpress.org/reference/classes/wp_query/
//            TODO:: in the future I can add comments to the feed
//            'comments_per_page' => 1
//            'comment_statuses' => 'open'
			'date_query' => [
				[
//                        'post_date_gmt' ,'post_modified_gmt', 'comment_date_gmt'. Default 'post_date'.
					'column'  => 'post_date',
//                     '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'. Default '='.
					'compare' => '=',
//                    'OR' or 'AND'. Default 'OR'.
//					'relation' => 'AND',
					[
						'before' => [
							'year' => date( 'Y' ),
							'week' => date( 'W' ),
							'day'  => '1',
						],
						'after'  => [
							'year' => date( 'Y' ),
							'week' => date( 'W' ),
							'day'  => '1',
						]
					],

				]
			]
		];

//compare = '=', '!=', '>', '>=','<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'.
//
//inclusive = TRUE
//
//anything else then
//
//Add javascript add date filter. each date filter consists of:
//
//	<select><option>Compare</option>
//	All compare options should generate the appropiate form
//
//	=, !=, >, >=, <, <=:
//		// exacty only show posts with these values
//		select with all years
//		select with all months
//		select with all weeks of the year
//		select with all days of the month
//
//		select with all days of the week
//		select with all days of the year
//		input time - hour + min
//	IN, NOT IN:
//		// exacty only posts that are in these arrays
//		multi select with all years
//		multi select with all months
//		multi select with all weeks
//
//		multi select with all days of the week
//		multi select with all days of the year
//		input time - hour + min
//	BETWEEN, NOT BETWEEN:
//		// before
//		<input type='date'> // year, month, week
//		<input type='time'> // hour, minute
//		// after
//		<input type='date'> // year, month, week
//		<input type='time'> // hour, minute


		if ( ! empty( $instance[ $prefix . 'comment_count' ]['value'] ) ) {
//            $postParameters['comment_count'] = $instance[$prefix . 'comment_count'];
		}

		$posts = get_posts( $postParameters );

		$html = $args['before_widget'];

		$regex = get_shortcode_regex();
//        preg_match_all('/\[title(.*?)?\](?:(.+?)?\[\/title\])?/', $content, $matches);
		preg_match_all( '/' . $regex . '/s', $postStructure, $matches );

		$html .= '<div>';
		foreach ( $posts as $post ) : setup_postdata( $post );
			// Reset post structure
			$postStructure = $instance['wp-ab-post_structure'];
			// Fill in the post structure
			for ( $i = 0; $i < count( $matches[0] ); $i ++ ) {
				// "[title ... ]" -> "[title id=1 ... ]"
				$match         = str_replace( '[' . $matches[2][ $i ], '[' . $matches[2][ $i ] . ' id=' . get_the_ID(), $matches[0][ $i ] );
				$postStructure = str_replace( $matches[0][ $i ], do_shortcode( $match ), $postStructure );
			}

			$html .= $postStructure;
			$html .= '<hr />';
		endforeach;
		$html .= '</div>';

		wp_reset_postdata();

		$html .= $args['after_widget'];
		echo $html;
	}

	// region: Custom methods
	/*
	 * [
	 * id: [name, children: []
	 * id: [name, children: []
	 * id: [name, children: []
	 * ]
	 */
	private function selectInputHierarchical( $categories, $selectedArr ) {
		$html = '';
		foreach ( $categories as $key => $category ) {
			if ( $category->category_parent == 0 ) {
				$selected = ( in_array( $category->cat_ID, $selectedArr ) ) ? 'selected' : '';
				$html     .= "<option value='{$category->cat_ID}' {$selected}>{$category->name}</option>";
				$html     .= $this->childrenCategory( $categories, $category->cat_ID, 0, $selectedArr );
				unset( $categories[ $key ] );
			}
		}

		return $html;
	}

	private function childrenCategory( $categories, $parentId, $dept, $selectedArr ) {
		$html     = '';
		$deptHtml = '';
		for ( $i = $dept + 1; $i > 0; $i -- ) {
			$deptHtml .= '&nbsp;&nbsp;&nbsp;';
		}
		foreach ( $categories as $key => $category ) {
			if ( $category->category_parent == $parentId ) {
				$selected = ( in_array( $category->cat_ID, $selectedArr ) ) ? 'selected' : '';
				$html     .= "<option value='{$category->cat_ID}' {$selected}>{$deptHtml}{$category->name}</option>";
				$html     .= $this->childrenCategory( $categories, $category->cat_ID, $dept + 1, $selectedArr );
				unset( $categories[ $key ] );
			}
		}

		return $html;
	}
	// endregion
}
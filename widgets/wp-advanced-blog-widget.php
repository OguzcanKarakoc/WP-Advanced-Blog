<?php

class Wp_Advanced_Blog_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(// Base ID of your widget
            false, // Widget name will appear in UI
            __('WP advanced blog'), // Widget description
            ['description' => __('The most advanced blog plguin', 'wp-advanced-blog')]);
    }

    public function update($new_instance, $old_instance)
    {
        $prefix = 'wp-ab-';
        $instance = $old_instance;
        $instance[$prefix . 'title'] = $new_instance[$prefix . 'title'];
        $instance[$prefix . 'category'] = $new_instance[$prefix . 'category'];
        $instance[$prefix . 'author_inc'] = $new_instance[$prefix . 'author'];
        $instance[$prefix . 'tag'] = $new_instance[$prefix . 'tag'];
        $instance[$prefix . 'limit'] = $new_instance[$prefix . 'limit'];
        $instance[$prefix . 'offset'] = $new_instance[$prefix . 'offset'];
        $instance[$prefix . 'order_by'] = $new_instance[$prefix . 'order_by'];
        $instance[$prefix . 'order'] = $new_instance[$prefix . 'order'];
        $instance[$prefix . 'include'] = $new_instance[$prefix . 'include'];
        $instance[$prefix . 'exclude'] = $new_instance[$prefix . 'exclude'];
        $instance[$prefix . 'pagination'] = $new_instance[$prefix . 'pagination'];
        $instance[$prefix . 'post_structure'] = $new_instance[$prefix . 'post_structure'];

        return $instance;
    }

    public function form($instance)

    {
        $prefix = 'wp-ab-';
        $authors = get_users([
            'orderby' => 'name',
            'order' => 'ASC',
            'number' => '',
        ]);

        $arr = [
            'author__in' => [
                'id' => $this->get_field_id($prefix . 'author__in'),
                'name' => $this->get_field_name($prefix . 'author__in'),
                'instance' => $instance[$prefix . 'author__in'],
                'list' => $authors,
                'label' => 'Author include',
                'description' => 'Select the authors you want to include in this feed.',
            ],
            'author__not_in' => [
                'id' => $this->get_field_id($prefix . 'author__not_in'),
                'name' => $this->get_field_name($prefix . 'author__not_in'),
                'instance' => $instance[$prefix . 'author__not_in'],
                'list' => $authors,
                'label' => 'Author exclude',
                'description' => 'Select the authors you want to exclude in this feed.',
            ],
            'cache_results' => [
                'id' => $this->get_field_id($prefix . 'cache_results'),
                'name' => $this->get_field_name($prefix . 'cache_results'),
                'instance' => $instance[$prefix . 'cache_results'],
                'label' => 'Cache results',
                'description' => 'Whether to cache post information. (default = true)',
            ],
            'title' => [
                'id' => $this->get_field_id($prefix . 'title'),
                'name' => $this->get_field_name($prefix . 'title'),
                'instance' => $instance[$prefix . 'title'],
            ],
            'category' => [
                'id' => $this->get_field_id($prefix . 'category'),
                'name' => $this->get_field_name($prefix . 'category[]'),
                'instance' => $instance[$prefix . 'category'],
            ],
            'tag' => [
                'id' => $this->get_field_id($prefix . 'tag'),
                'name' => $this->get_field_name($prefix . 'tag[]'),
                'instance' => $instance[$prefix . 'tag'],
            ],
            'limit' => [
                'id' => $this->get_field_id($prefix . 'limit'),
                'name' => $this->get_field_name($prefix . 'limit'),
                'instance' => $instance[$prefix . 'limit'],
            ],
            'offset' => [
                'id' => $this->get_field_id($prefix . 'offset'),
                'name' => $this->get_field_name($prefix . 'offset'),
                'instance' => $instance[$prefix . 'offset'],
            ],
            'order_by' => [
                'id' => $this->get_field_id($prefix . 'order_by'),
                'name' => $this->get_field_name($prefix . 'order_by'),
                'instance' => $instance[$prefix . 'order_by'],
            ],
            'order' => [
                'id' => $this->get_field_id($prefix . 'order'),
                'name' => $this->get_field_name($prefix . 'order'),
                'instance' => $instance[$prefix . 'order'],
            ],
            'include' => [
                'id' => $this->get_field_id($prefix . 'include'),
                'name' => $this->get_field_name($prefix . 'include'),
                'instance' => $instance[$prefix . 'include'],
                'description' => 'An array of post IDs to retrieve, sticky posts will be included.',
            ],
            'exclude' => [
                'id' => $this->get_field_id($prefix . 'exclude'),
                'name' => $this->get_field_name($prefix . 'exclude'),
                'instance' => $instance[$prefix . 'exclude'],
                'description' => 'An array of post IDs not to retrieve.',
            ],
            'post_structure' => [
                'id' => $this->get_field_id($prefix . 'post_structure'),
                'name' => $this->get_field_name($prefix . 'post_structure'),
                'instance' => $instance[$prefix . 'post_structure'],
            ],
            'pagination' => [
                'id' => $this->get_field_id($prefix . 'pagination'),
                'name' => $this->get_field_name($prefix . 'pagination'),
                'instance' => $instance[$prefix . 'pagination'],
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
        $order_options = [
            'ASC', 'DESC'
        ];
        $wpeditor = [
            'textarea_name' => $arr['post_structure']['name']
        ];


        ?>
        <div>
            <p>
                <label for="<?= $arr['author__in']['id'] ?>">
                    <?= $arr['author__in']['label'] ?> <br/>
                    <small><?= $arr['author__in']['description'] ?></small>
                </label>
                <select class="widefat" name="<?= $arr['author__in']['name'] ?>" id="<?= $arr['author__in']['id'] ?>" multiple>
                    <?php foreach ($arr['author__in']['list'] as $author) {
                        $selected = (in_array($author->id, $arr['author__in']['instance'])) ? 'selected' : '';
                        echo "<option value='{$author->id}' {$selected}>{$author->data->display_name}</option>";
                    } ?>
                </select>
            </p>
            <p>
                <label for="<?= $arr['author__not_in']['id'] ?>">
                    <?= $arr['author__not_in']['label'] ?> <br/>
                    <small><?= $arr['author__not_in']['description'] ?></small>
                </label>
                <select class="widefat" name="<?= $arr['author__not_in']['name'] ?>" id="<?= $arr['author__not_in']['id'] ?>" multiple>
                    <?php foreach ($arr['author__not_in']['list'] as $author) {
                        $selected = (in_array($author->id, $arr['author__not_in']['instance'])) ? 'selected' : '';
                        echo "<option value='{$author->id}' {$selected}>{$author->data->display_name}</option>";
                    } ?>
                </select>
            </p>
            <p>
                <input type="checkbox" class="checkbox"
                       id="<?= $arr['cache_results']['id'] ?>"
                       name="<?= $arr['cache_results']['name'] ?>"
                    <?= ($arr['cache_results']['instance'] == 'on' || empty($arr['cache_results']['instance'])) ? 'checked' : '' ?>>
                <label for="<?= $arr['cache_results']['id'] ?>">
                    <?= $arr['cache_results']['label'] ?> <br/>
                    <small><?= $arr['cache_results']['description'] ?></small>
                </label>
            </p>
            <?php
            $args2 = array(
                'show_option_all' => '',
                'container' => false,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => 0,
                'use_desc_for_title' => 0,
                'child_of' => 0,
                'hierarchical' => 1,
                'number' => null,
                'echo' => 1,
                'depth' => -1,
                'taxonomy' => 'category'

            );
            $categories = get_categories($args2);
            // Recursive method get categories.
            $test = $this->selectInputHierarchical($categories);
            echo "<pre>";
            var_dump($test);
            echo "</pre>";
            ?>
            <p>
                <label for="<?= $arr['category']['id'] ?>">Filter categories</label>
                <select class="widefat" name="<?= $arr['category']['name'] ?>" id="<?= $arr['category']['id'] ?>" multiple>
                    <?= $this->selectInputHierarchical($categories) ?>
                    <!--                    --><?php //foreach (get_categories(['hide_empty' => 0]) as $category) {
                    //                        $selected = (in_array($category->cat_ID, $arr['category']['instance'])) ? 'selected' : '';
                    //                        echo "<option value='{$category->cat_ID}' {$selected}>{$category->cat_name}</option>";
                    //                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?= $arr['title']['id'] ?>">Title</label>
                <input class="widefat" id="<?= $arr['title']['id'] ?>" name="<?= $arr['title']['name'] ?>" type="text" value="<?= esc_attr($arr['title']['instance']) ?>"/>
            </p>

            <p>
                <label for="<?= $arr['tag']['id'] ?>">Filter tags</label>
                <select class="widefat" name="<?= $arr['tag']['name'] ?>" id="<?= $arr['tag']['id'] ?>" multiple>
                    <?php foreach (get_tags(['hide_empty' => 0]) as $tag) {
                        $selected = (in_array($tag->term_id, $arr['tag']['instance'])) ? 'selected' : '';
                        echo "<option value='{$tag->term_id}' {$selected}>{$tag->name}</option>";
                    } ?>
                </select>
            </p>
            <p>
                <label for="<?= $arr['order']['id'] ?>">Order</label>
                <select class="widefat" name="<?= $arr['order']['name'] ?>" id="<?= $arr['order']['id'] ?>">
                    <?php foreach ($order_by_allowed_keys as $order) {
                        $selected = ($order == $arr['order']['instance']) ? 'selected' : '';
                        echo "<option value='{$order}' {$selected}>" . str_replace('_', ' ', $order) . "</option>";
                    } ?>
                </select>
            </p>
            <p>
                <label for="<?= $arr['order_by']['id'] ?>">Order By</label>
                <select class="widefat" name="<?= $arr['order_by']['name'] ?>" id="<?= $arr['order_by']['id'] ?>">
                    <?php foreach ($order_options as $order) {
                        $selected = ($order == $arr['order_by']['instance']) ? 'selected' : '';
                        echo "<option value='{$order}' {$selected}>{$order}</option>";
                    } ?>
                </select>
            </p>
            <p>
                <label for="<?= $arr['limit']['id'] ?>">Limit</label>
                <input class="tiny-text" id="<?= $arr['limit']['id'] ?>" name="<?= $arr['limit']['name'] ?>" type="number" value="<?= esc_attr($arr['limit']['instance']) ?>"/>
            </p>
            <p>
                <label for="<?= $arr['offset']['id'] ?>">Offset</label>
                <input class="tiny-text" id="<?= $arr['offset']['id'] ?>" name="<?= $arr['offset']['name'] ?>" type="number" value="<?= esc_attr($arr['offset']['instance']) ?>"/>
            </p>
            <p>
                <label for="<?= $arr['include']['id'] ?>">Include (comma separated)</label>
                <input class="widefat" placeholder="145, 421" id="<?= $arr['include']['id'] ?>" name="<?= $arr['include']['name'] ?>" type="number" value="<?= esc_attr($arr['include']['instance']) ?>"/>
            </p>
            <p>
                <label for="<?= $arr['exclude']['id'] ?>">Exclude (comma separated)</label>
                <input class="widefat" placeholder="145, 421" id="<?= $arr['exclude']['id'] ?>" name="<?= $arr['exclude']['name'] ?>" type="number" value="<?= esc_attr($arr['exclude']['instance']) ?>"/>
            </p>
            <p>
                <input type="checkbox" class="checkbox" id="<?= $arr['pagination']['id'] ?>" name="<?= $arr['pagination']['name'] ?>" value="<?= ($arr['pagination']['instance'] == 'on') ? 'selected' : '' ?>">
                <label for="<?= $arr['pagination']['id'] ?>">Pagination</label>
            </p>
            <p>
                <?php
                //                wp_editor(esc_attr($arr['post_structure']['instance']), 155, $wpeditor);
                ?>
                <label for="">single post structure</label>
                <textarea class="widefat" name="<?= $arr['post_structure']['name'] ?>" id="<?= $arr['post_structure']['id'] ?>" cols="30" rows="10">
                    <?= $arr['post_structure']['instance'] ?>
                </textarea>
            </p>
        </div>
        <?php

    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        global $post;

        /**
         * urls:
         * https://codex.wordpress.org/Class_Reference/WP_Query#Parameters
         * https://codex.wordpress.org/Template_Tags/get_posts
         * https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
         */
        $prefix = 'wp-ab-';
//        $content = str_replace("wp-ab-postid", "wp-ab-postid=1", $instance['wp-ab-post_structure']);
        $postStructure = $instance['wp-ab-post_structure'];
//        echo "<pre>";
//        var_dump($instance);
//        echo "</pre>";
        $arr = [
            'title' => [
                'id' => $this->get_field_id($prefix . 'title'),
                'name' => $this->get_field_name($prefix . 'title'),
                'instance' => $instance[$prefix . 'title'],
            ],
            'category' => [
                'id' => $this->get_field_id($prefix . 'category'),
                'name' => $this->get_field_name($prefix . 'category[]'),
                'instance' => $instance[$prefix . 'category'],
            ],
            'tag' => [
                'id' => $this->get_field_id($prefix . 'tag'),
                'name' => $this->get_field_name($prefix . 'tag[]'),
                'instance' => $instance[$prefix . 'tag'],
            ],
            'limit' => [
                'id' => $this->get_field_id($prefix . 'limit'),
                'name' => $this->get_field_name($prefix . 'limit'),
                'instance' => $instance[$prefix . 'limit'],
            ],
            'offset' => [
                'id' => $this->get_field_id($prefix . 'offset'),
                'name' => $this->get_field_name($prefix . 'offset'),
                'instance' => $instance[$prefix . 'offset'],
            ],
            'order_by' => [
                'id' => $this->get_field_id($prefix . 'order_by'),
                'name' => $this->get_field_name($prefix . 'order_by'),
                'instance' => $instance[$prefix . 'order_by'],
            ],
            'order' => [
                'id' => $this->get_field_id($prefix . 'order'),
                'name' => $this->get_field_name($prefix . 'order'),
                'instance' => $instance[$prefix . 'order'],
            ],
            'include' => [
                'id' => $this->get_field_id($prefix . 'include'),
                'name' => $this->get_field_name($prefix . 'include'),
                'instance' => $instance[$prefix . 'include'],
                'description' => 'An array of post IDs to retrieve, sticky posts will be included.',
            ],
            'exclude' => [
                'id' => $this->get_field_id($prefix . 'exclude'),
                'name' => $this->get_field_name($prefix . 'exclude'),
                'instance' => $instance[$prefix . 'exclude'],
                'description' => 'An array of post IDs not to retrieve.',
            ],
            'post_structure' => [
                'id' => $this->get_field_id($prefix . 'post_structure'),
                'name' => $this->get_field_name($prefix . 'post_structure'),
                'instance' => $instance[$prefix . 'post_structure'],
            ],
            'pagination' => [
                'id' => $this->get_field_id($prefix . 'pagination'),
                'name' => $this->get_field_name($prefix . 'pagination'),
                'instance' => $instance[$prefix . 'pagination'],
            ],
        ];

        $postParameters = [
            'author_in' => $instance[$prefix . 'author__in'],
            'author_not_in' => $instance[$prefix . 'author_not_in'],
            'cache_results' => $instance[$prefix . 'cache_results'],
        ];


//        $args = array(
//            'posts_per_page'   => 5,
//            'offset'           => 0,
//            'cat'         => '',
//            'category_name'    => '',
//            'orderby'          => 'date',
//            'order'            => 'DESC',
//            'include'          => '',
//            'exclude'          => '',
//            'meta_key'         => '',
//            'meta_value'       => '',
//            'post_type'        => 'post',
//            'post_mime_type'   => '',
//            'post_parent'      => '',
//            'author'	   => '',
//            'author_name'	   => '',
//            'post_status'      => 'publish',
//            'suppress_filters' => true,
//            'fields'           => '',
//        );
//        $posts_array = get_posts( $args );
//        $query = new WP_Query($args);

        $myposts = get_posts($postParameters);

        $html = $args['before_widget'];

        $regex = get_shortcode_regex();
//        preg_match_all('/\[title(.*?)?\](?:(.+?)?\[\/title\])?/', $content, $matches);
        preg_match_all('/' . $regex . '/s', $postStructure, $matches);
        echo "<pre>";
        var_dump($matches);
        echo "</pre>";

        $html .= '<div>';
        foreach ($myposts as $post) : setup_postdata($post);
            // Reset post structure
            $postStructure = $instance['wp-ab-post_structure'];
            // Fill in the post structure
            for ($i = 0; $i < count($matches[0]); $i++) {
                // "[title ... ]" -> "[title id=1 ... ]"
                $match = str_replace('[' . $matches[2][$i], '[' . $matches[2][$i] . ' id=' . get_the_ID(), $matches[0][$i]);
                $postStructure = str_replace($matches[0][$i], do_shortcode($match), $postStructure);
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
    private function selectInputHierarchical($categories, $dept = 0)
    {
//        $cat_arr = [];
        $html = '';
        $deptHtml = '';
        for ($i = $dept; $i >= 0; $i--) {
            $deptHtml .= '&nbsp;';
        }
        foreach ($categories as $key => $category) {
            if ($category->category_parent == 0) {

                $html .= "<option value='{$category->catID}'>{$deptHtml}{$category->name}</option>";
                $html .= $this->childrenCategory($categories, $category->cat_ID, $dept);
//                $cat_arr[$category->cat_ID]['name'] = $category->name;
//                $cat_arr[$category->cat_ID]['children'] = $this->childrenCategory($categories, $category->cat_ID);
                unset($categories[$key]);
            }
        }
        return $html;
    }

    private function childrenCategory($categories, $parentId, $dept)
    {
        $html = '';
        $deptHtml = '';
        for ($i = $dept + 1; $i >= 0; $i--) {
            $deptHtml .= '&nbsp;';
        }
//        $children = [];
        foreach ($categories as $key => $category) {
            if ($category->category_parent == $parentId) {
                $html .= "<option value='{$category->catID}'>{$deptHtml}{$category->name}</option>";
                $html .= $this->childrenCategory($categories, $category->cat_ID, $dept + 1);
//                $children[$category->cat_ID]['name'] = $category->name;
//                $children[$category->cat_ID]['children'] = $this->childrenCategory($categories, $category->cat_ID, $dept + 1);
                unset($categories[$key]);
            }
        }
        return $html;
    }
    // endregion
}
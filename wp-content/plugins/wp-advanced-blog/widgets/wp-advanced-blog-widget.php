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
        echo "<pre>";
//        var_dump($arr['post_structure']['instance']);
        echo "</pre>";
        ?>
        <div>
            <p>
                <label for="<?= $arr['title']['id'] ?>">Title</label>
                <input class="widefat" id="<?= $arr['title']['id'] ?>" name="<?= $arr['title']['name'] ?>" type="text" value="<?= esc_attr($arr['title']['instance']) ?>"/>
            </p>
            <p>
                <label for="<?= $arr['category']['id'] ?>">Filter categories</label>
                <select class="widefat" name="<?= $arr['category']['name'] ?>" id="<?= $arr['category']['id'] ?>" multiple>
                    <?php foreach (get_categories(['hide_empty' => 0]) as $category) {
                        $selected = (in_array($category->cat_ID, $arr['category']['instance'])) ? 'selected' : '';
                        echo "<option value='{$category->cat_ID}' {$selected}>{$category->cat_name}</option>";
                    } ?>
                </select>
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
                    &lt;p&gt;1: [title link='true' link-target='_blank' wp-ab-postid]&lt;/p&gt;
                    &lt;p&gt;2: [Excerpt]&lt;/p&gt;
                    &lt;p&gt;3: [Tags]&lt;/p&gt;
                    &lt;p&gt;4: [Categories]&lt;/p&gt;
                    &lt;p&gt;5: [FeaturedImage]&lt;/p&gt;
                    &lt;p&gt;6: [FeaturedImageLink]&lt;/p&gt;
                    &lt;p&gt;7: [PostLink]&lt;/p&gt;
                    &lt;p&gt;8: [ViewCount]&lt;/p&gt;
                    &lt;p&gt;9: [CommentCount]&lt;/p&gt;
                    &lt;p&gt;10: [CustomField name='']&lt;/p&gt;
                    &lt;p&gt;11: [PublishedDate format='']&lt;/p&gt;
                    &lt;p&gt;12: [UpdatedDate format='']&lt;/p&gt;
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
        /**
         * urls:
         * https://codex.wordpress.org/Class_Reference/WP_Query#Parameters
         * https://codex.wordpress.org/Template_Tags/get_posts
         * https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
         */
        $prefix = 'wp-ab-';
        $content = str_replace("wp-ab-postid", "wp-ab-postid=1", $instance['wp-ab-post_structure']);

        $args = array(
            'category' => $instance['category']
        );
//        $query = new WP_Query($args);

//        get_posts($args);

        $html = $args['before_widget'];

        preg_match_all('/\[title(.*?)?\](?:(.+?)?\[\/title\])?/', $content, $matches);
        echo "<pre>";
        var_dump($content, $matches[0]);
        echo "</pre>";
        $html .= $args['after_widget'];
        echo $html;
    }
}
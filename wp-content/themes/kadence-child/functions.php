<?php
function kadence_child_enqueue_styles() {
    wp_enqueue_style('kadence-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('kadence-child-style', get_stylesheet_directory_uri() . '/style.css', array('kadence-style'));
    wp_enqueue_style('kadence-child-custom', get_stylesheet_directory_uri() . '/css/custom-styles.css');
    wp_enqueue_script('kadence-child-modal', get_stylesheet_directory_uri() . '/js/modal.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_styles');
  

function create_books_cpt() {
    $labels = array(
        'name' => _x('Books', 'Post Type General Name', 'textdomain'),
        'singular_name' => _x('Book', 'Post Type Singular Name', 'textdomain'),
        'menu_name' => __('Books', 'textdomain'),
        'name_admin_bar' => __('Book', 'textdomain'),
        'archives' => __('Book Archives', 'textdomain'),
        'attributes' => __('Book Attributes', 'textdomain'),
        'parent_item_colon' => __('Parent Book:', 'textdomain'),
        'all_items' => __('All Books', 'textdomain'),
        'add_new_item' => __('Add New Book', 'textdomain'),
        'add_new' => __('Add New', 'textdomain'),
        'new_item' => __('New Book', 'textdomain'),
        'edit_item' => __('Edit Book', 'textdomain'),
        'update_item' => __('Update Book', 'textdomain'),
        'view_item' => __('View Book', 'textdomain'),
        'view_items' => __('View Books', 'textdomain'),
        'search_items' => __('Search Book', 'textdomain'),
        'not_found' => __('Not found', 'textdomain'),
        'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
        'featured_image' => __('Featured Image', 'textdomain'),
        'set_featured_image' => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image' => __('Use as featured image', 'textdomain'),
        'insert_into_item' => __('Insert into book', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this book', 'textdomain'),
        'items_list' => __('Books list', 'textdomain'),
        'items_list_navigation' => __('Books list navigation', 'textdomain'),
        'filter_items_list' => __('Filter books list', 'textdomain'),
    );
    $args = array(
        'label' => __('Book', 'textdomain'),
        'description' => __('Post Type Description', 'textdomain'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('books', $args);
}
add_action('init', 'create_books_cpt', 0);

function books_custom_fields() {
    register_post_meta('books', 'short_description', array(
        'type' => 'string',
        'description' => 'Short Description',
        'single' => true,
        'show_in_rest' => true,
    ));
    register_post_meta('books', 'publication_year', array(
        'type' => 'number',
        'description' => 'Publication Year',
        'single' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'books_custom_fields');

function add_books_metaboxes() {
    add_meta_box(
        'books_publication_year',
        'Publication Year',
        'books_publication_year_callback',
        'books',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_books_metaboxes');

function books_publication_year_callback($post) {
    wp_nonce_field(basename(__FILE__), 'books_nonce');
    $stored_meta = get_post_meta($post->ID);
    ?>
    <label for="meta-publication-year" class="books-row-title"><?php _e('Publication Year', 'textdomain'); ?></label>
    <input type="number" name="meta-publication-year" id="meta-publication-year" value="<?php if (isset($stored_meta['meta-publication-year'])) echo $stored_meta['meta-publication-year'][0]; ?>" />
    <?php
}

function save_books_meta($post_id) {
    // Check nonce
    if (!isset($_POST['books_nonce']) || !wp_verify_nonce($_POST['books_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check permissions
    if ('books' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        } elseif (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    }

    // Save the publication year
    if (isset($_POST['meta-publication-year'])) {
        update_post_meta($post_id, 'meta-publication-year', sanitize_text_field($_POST['meta-publication-year']));
    }
}
add_action('save_post', 'save_books_meta');

function books_shortcode($atts) {
    $atts = shortcode_atts(array(
        'posts_per_page' => 3,
    ), $atts, 'books');

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'books',
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => $paged,
    );
    $query = new WP_Query($args);
    $output = '<div class="books">';
    while ($query->have_posts()) : $query->the_post();
        $output .= '<div class="book">';
        $output .= get_the_post_thumbnail(get_the_ID(), 'medium'); 
        $output .= '<h2>' . get_the_title() . '</h2>';
        $output .= '<p>' . get_post_meta(get_the_ID(), 'short_description', true) . '</p>';
        $output .= '<p class="publication-year">Publication Year: ' . get_post_meta(get_the_ID(), 'meta-publication-year', true) . '</p>';
        $output .= '</div>';
    endwhile;
    $output .= '</div>';
    $output .= '<div class="pagination">';
    $output .= paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ));
    $output .= '</div>';
    $output .= '
    <div class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 class="modal-title"></h2>
            <img class="modal-image" src="" alt="Book Image">
            <p class="modal-description"></p>
            <p class="modal-year"></p>
        </div>
    </div>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('books', 'books_shortcode');

// Register Sidebar
function kadence_child_register_sidebars() {
    register_sidebar(array(
        'name' => __('Bitcoin Price Widget', 'textdomain'),
        'id' => 'bitcoin_widget',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'kadence_child_register_sidebars');

// Bitcoin Price Widget

function bitcoin_price_widget() {
    $response = wp_remote_get('https://mempool.space/api/v1/prices');
    if (is_wp_error($response)) {
        echo '<div class="bitcoin-price">Error retrieving Bitcoin price.</div>';
        return;
    }
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (empty($data)) {
        echo '<div class="bitcoin-price">No data available.</div>';
        return;
    }

    $currencies = array('USD', 'EUR', 'GBP', 'CAD', 'CHF', 'AUD', 'JPY');
    echo '<div class="bitcoin-price-widget">';
    foreach ($currencies as $currency) {
        $price = isset($data[$currency]) ? number_format($data[$currency], 2) : 'N/A';
        echo '<p>Price in ' . esc_html($currency) . ': $' . $price . '</p>';
    }
    echo '</div>';
}

class Bitcoin_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('bitcoin_widget', 'Bitcoin Price', array('description' => 'Displays the current Bitcoin price.'));
    }
    public function widget($args, $instance) {
        if (is_front_page()) { 
            echo $args['before_widget'];
            if (!empty($instance['title'])) {
                echo '<div class="widget-title">' . apply_filters('widget_title', $instance['title']) . '</div>';
            }
            bitcoin_price_widget();
            echo $args['after_widget'];

            echo '<div class="fixed-bitcoin-widget">';
            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . '</div>';
            }
            bitcoin_price_widget();
            echo '</div>';
        }
    }
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Bitcoin Price';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

function register_bitcoin_widget() {
    register_widget('Bitcoin_Widget');
}
add_action('widgets_init', 'register_bitcoin_widget');

?>

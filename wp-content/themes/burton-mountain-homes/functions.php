<?php
/**
 * Burton Mountain Homes Theme Functions
 *
 * @package Burton_Mountain_Homes
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// =============================================
// THEME SETUP
// =============================================

add_action('after_setup_theme', 'bmh_theme_setup');
function bmh_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Custom image sizes for properties
    add_image_size('property-card', 800, 600, true);
    add_image_size('property-hero', 1920, 1080, true);
    add_image_size('property-gallery', 1200, 800, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'burton-mountain-homes'),
        'footer'  => __('Footer Menu', 'burton-mountain-homes'),
    ));
}

// =============================================
// ENQUEUE STYLES & SCRIPTS
// =============================================

add_action('wp_enqueue_scripts', 'bmh_enqueue_assets');
function bmh_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'bmh-google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap',
        array(),
        null
    );
    
    // Parent theme (GeneratePress)
    wp_enqueue_style(
        'generatepress-parent',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('generatepress')->get('Version')
    );
    
    // Child theme
    wp_enqueue_style(
        'bmh-theme-style',
        get_stylesheet_uri(),
        array('generatepress-parent', 'bmh-google-fonts'),
        wp_get_theme()->get('Version')
    );

    // Nav JS (hamburger + smooth scroll)
    wp_enqueue_script(
        'bmh-nav',
        get_stylesheet_directory_uri() . '/assets/js/nav.js',
        array(),
        wp_get_theme()->get('Version'),
        true // load in footer
    );
}

// =============================================
// PROPERTY CUSTOM POST TYPE
// =============================================

add_action('init', 'bmh_register_property_post_type');
function bmh_register_property_post_type() {
    
    $labels = array(
        'name'                  => _x('Properties', 'Post type general name', 'burton-mountain-homes'),
        'singular_name'         => _x('Property', 'Post type singular name', 'burton-mountain-homes'),
        'menu_name'             => _x('Properties', 'Admin Menu text', 'burton-mountain-homes'),
        'add_new'               => __('Add New', 'burton-mountain-homes'),
        'add_new_item'          => __('Add New Property', 'burton-mountain-homes'),
        'edit_item'             => __('Edit Property', 'burton-mountain-homes'),
        'new_item'              => __('New Property', 'burton-mountain-homes'),
        'view_item'             => __('View Property', 'burton-mountain-homes'),
        'view_items'            => __('View Properties', 'burton-mountain-homes'),
        'search_items'          => __('Search Properties', 'burton-mountain-homes'),
        'not_found'             => __('No properties found', 'burton-mountain-homes'),
        'not_found_in_trash'    => __('No properties found in Trash', 'burton-mountain-homes'),
        'all_items'             => __('All Properties', 'burton-mountain-homes'),
        'archives'              => __('Property Archives', 'burton-mountain-homes'),
        'attributes'            => __('Property Attributes', 'burton-mountain-homes'),
        'featured_image'        => __('Property Featured Image', 'burton-mountain-homes'),
        'set_featured_image'    => __('Set featured image', 'burton-mountain-homes'),
        'remove_featured_image' => __('Remove featured image', 'burton-mountain-homes'),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'properties', 'with_front' => false),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-building',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );
    
    register_post_type('property', $args);
}

// Property Status Taxonomy
add_action('init', 'bmh_register_property_taxonomies');
function bmh_register_property_taxonomies() {
    
    // Property Status (Active, Sold, Pending, etc.)
    register_taxonomy('property_status', 'property', array(
        'labels' => array(
            'name'          => __('Status', 'burton-mountain-homes'),
            'singular_name' => __('Status', 'burton-mountain-homes'),
            'search_items'  => __('Search Statuses', 'burton-mountain-homes'),
            'all_items'     => __('All Statuses', 'burton-mountain-homes'),
            'edit_item'     => __('Edit Status', 'burton-mountain-homes'),
            'add_new_item'  => __('Add New Status', 'burton-mountain-homes'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'status'),
    ));
    
    // Neighborhood/Area
    register_taxonomy('property_area', 'property', array(
        'labels' => array(
            'name'          => __('Areas', 'burton-mountain-homes'),
            'singular_name' => __('Area', 'burton-mountain-homes'),
            'search_items'  => __('Search Areas', 'burton-mountain-homes'),
            'all_items'     => __('All Areas', 'burton-mountain-homes'),
            'edit_item'     => __('Edit Area', 'burton-mountain-homes'),
            'add_new_item'  => __('Add New Area', 'burton-mountain-homes'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'area'),
    ));
}

// Flush rewrite rules on theme activation
add_action('after_switch_theme', 'bmh_flush_rewrite_rules');
function bmh_flush_rewrite_rules() {
    bmh_register_property_post_type();
    bmh_register_property_taxonomies();
    flush_rewrite_rules();
}

// =============================================
// ACF FIELD REGISTRATION (if ACF not installed, use native)
// =============================================

// Check if ACF is active, if not we'll add basic meta boxes
if (!class_exists('ACF')) {
    add_action('add_meta_boxes', 'bmh_add_property_meta_boxes');
    add_action('save_post_property', 'bmh_save_property_meta');
}

function bmh_add_property_meta_boxes() {
    add_meta_box(
        'bmh_property_details',
        __('Property Details', 'burton-mountain-homes'),
        'bmh_property_details_callback',
        'property',
        'normal',
        'high'
    );
}

function bmh_property_details_callback($post) {
    wp_nonce_field('bmh_property_details', 'bmh_property_details_nonce');
    
    $price = get_post_meta($post->ID, '_bmh_price', true);
    $address = get_post_meta($post->ID, '_bmh_address', true);
    $bedrooms = get_post_meta($post->ID, '_bmh_bedrooms', true);
    $bathrooms = get_post_meta($post->ID, '_bmh_bathrooms', true);
    $sqft = get_post_meta($post->ID, '_bmh_sqft', true);
    $year_built = get_post_meta($post->ID, '_bmh_year_built', true);
    $mls_number = get_post_meta($post->ID, '_bmh_mls_number', true);
    ?>
    <style>
        .bmh-meta-row { margin-bottom: 15px; }
        .bmh-meta-row label { display: block; font-weight: 600; margin-bottom: 5px; }
        .bmh-meta-row input { width: 100%; max-width: 400px; padding: 8px; }
        .bmh-meta-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    </style>
    <div class="bmh-meta-row">
        <label for="bmh_price"><?php _e('Price ($)', 'burton-mountain-homes'); ?></label>
        <input type="number" id="bmh_price" name="bmh_price" value="<?php echo esc_attr($price); ?>" placeholder="4500000">
    </div>
    <div class="bmh-meta-row">
        <label for="bmh_address"><?php _e('Address', 'burton-mountain-homes'); ?></label>
        <input type="text" id="bmh_address" name="bmh_address" value="<?php echo esc_attr($address); ?>" placeholder="2325 Colorow Road, Wildridge">
    </div>
    <div class="bmh-meta-grid">
        <div class="bmh-meta-row">
            <label for="bmh_bedrooms"><?php _e('Bedrooms', 'burton-mountain-homes'); ?></label>
            <input type="number" id="bmh_bedrooms" name="bmh_bedrooms" value="<?php echo esc_attr($bedrooms); ?>" placeholder="4">
        </div>
        <div class="bmh-meta-row">
            <label for="bmh_bathrooms"><?php _e('Bathrooms', 'burton-mountain-homes'); ?></label>
            <input type="text" id="bmh_bathrooms" name="bmh_bathrooms" value="<?php echo esc_attr($bathrooms); ?>" placeholder="3.5">
        </div>
        <div class="bmh-meta-row">
            <label for="bmh_sqft"><?php _e('Square Feet', 'burton-mountain-homes'); ?></label>
            <input type="number" id="bmh_sqft" name="bmh_sqft" value="<?php echo esc_attr($sqft); ?>" placeholder="4200">
        </div>
    </div>
    <div class="bmh-meta-grid">
        <div class="bmh-meta-row">
            <label for="bmh_year_built"><?php _e('Year Built', 'burton-mountain-homes'); ?></label>
            <input type="number" id="bmh_year_built" name="bmh_year_built" value="<?php echo esc_attr($year_built); ?>" placeholder="2018">
        </div>
        <div class="bmh-meta-row">
            <label for="bmh_mls_number"><?php _e('MLS Number', 'burton-mountain-homes'); ?></label>
            <input type="text" id="bmh_mls_number" name="bmh_mls_number" value="<?php echo esc_attr($mls_number); ?>" placeholder="S123456">
        </div>
    </div>
    <?php
}

function bmh_save_property_meta($post_id) {
    if (!isset($_POST['bmh_property_details_nonce']) || 
        !wp_verify_nonce($_POST['bmh_property_details_nonce'], 'bmh_property_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('price', 'address', 'bedrooms', 'bathrooms', 'sqft', 'year_built', 'mls_number');
    
    foreach ($fields as $field) {
        if (isset($_POST['bmh_' . $field])) {
            update_post_meta($post_id, '_bmh_' . $field, sanitize_text_field($_POST['bmh_' . $field]));
        }
    }
}

// =============================================
// HELPER FUNCTIONS FOR TEMPLATES
// =============================================

/**
 * Get formatted property price
 */
function bmh_get_price($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $price = get_post_meta($post_id, '_bmh_price', true);
    
    if (!$price) {
        // Try ACF field name
        $price = get_field('price', $post_id);
    }
    
    if ($price) {
        return '$' . number_format((float)$price);
    }
    
    return 'Price Upon Request';
}

/**
 * Get property address
 */
function bmh_get_address($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $address = get_post_meta($post_id, '_bmh_address', true);
    
    if (!$address) {
        $address = get_field('address', $post_id);
    }
    
    return $address ?: '';
}

/**
 * Get property specs as array
 */
function bmh_get_specs($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    
    return array(
        'bedrooms'  => get_post_meta($post_id, '_bmh_bedrooms', true) ?: get_field('bedrooms', $post_id),
        'bathrooms' => get_post_meta($post_id, '_bmh_bathrooms', true) ?: get_field('bathrooms', $post_id),
        'sqft'      => get_post_meta($post_id, '_bmh_sqft', true) ?: get_field('sqft', $post_id),
    );
}

/**
 * Get property status
 */
function bmh_get_status($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $terms = get_the_terms($post_id, 'property_status');
    
    if ($terms && !is_wp_error($terms)) {
        return $terms[0];
    }
    
    return null;
}

/**
 * Get property status badge HTML
 */
function bmh_get_status_badge($post_id = null) {
    $status = bmh_get_status($post_id);
    
    if (!$status) {
        return '';
    }
    
    $class = 'bmh-property-badge';
    $slug = strtolower($status->slug);
    
    if (in_array($slug, array('sold', 'pending', 'price-reduced'))) {
        $class .= ' ' . $slug;
    }
    
    return sprintf('<span class="%s">%s</span>', esc_attr($class), esc_html($status->name));
}

// =============================================
// ADMIN CLEANUP - REMOVE BLOAT
// =============================================

// Remove dashboard widgets
add_action('wp_dashboard_setup', 'bmh_remove_dashboard_widgets');
function bmh_remove_dashboard_widgets() {
    remove_action('welcome_panel', 'wp_welcome_panel');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
}

// Clean admin bar
add_action('admin_bar_menu', 'bmh_clean_admin_bar', 999);
function bmh_clean_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('comments');
}

// Hide non-essential notices for non-admins
add_action('admin_head', 'bmh_hide_nag_notices');
function bmh_hide_nag_notices() {
    if (!current_user_can('manage_options')) {
        echo '<style>.notice:not(.notice-error):not(.notice-warning) { display: none !important; }</style>';
    }
}

// Clean up admin menu for editors
add_action('admin_menu', 'bmh_clean_admin_menu', 999);
function bmh_clean_admin_menu() {
    if (!current_user_can('manage_options')) {
        remove_menu_page('tools.php');
        remove_menu_page('edit-comments.php');
    }
}

// Remove WordPress version from footer
add_filter('admin_footer_text', '__return_empty_string');
add_filter('update_footer', '__return_empty_string', 11);

// =============================================
// CUSTOM ADMIN COLUMNS FOR PROPERTIES
// =============================================

add_filter('manage_property_posts_columns', 'bmh_property_admin_columns');
function bmh_property_admin_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['price'] = __('Price', 'burton-mountain-homes');
            $new_columns['address'] = __('Address', 'burton-mountain-homes');
        } elseif ($key === 'date') {
            $new_columns['specs'] = __('Specs', 'burton-mountain-homes');
            $new_columns[$key] = $value;
        } else {
            $new_columns[$key] = $value;
        }
    }
    
    return $new_columns;
}

add_action('manage_property_posts_custom_column', 'bmh_property_admin_column_content', 10, 2);
function bmh_property_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'price':
            echo bmh_get_price($post_id);
            break;
        case 'address':
            echo esc_html(bmh_get_address($post_id));
            break;
        case 'specs':
            $specs = bmh_get_specs($post_id);
            $output = array();
            if ($specs['bedrooms']) $output[] = $specs['bedrooms'] . ' bed';
            if ($specs['bathrooms']) $output[] = $specs['bathrooms'] . ' bath';
            if ($specs['sqft']) $output[] = number_format((int)$specs['sqft']) . ' sqft';
            echo implode(' · ', $output);
            break;
    }
}

// Make price column sortable
add_filter('manage_edit-property_sortable_columns', 'bmh_property_sortable_columns');
function bmh_property_sortable_columns($columns) {
    $columns['price'] = 'price';
    return $columns;
}

add_action('pre_get_posts', 'bmh_property_orderby');
function bmh_property_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ($query->get('orderby') === 'price') {
        $query->set('meta_key', '_bmh_price');
        $query->set('orderby', 'meta_value_num');
    }
}

// =============================================
// ADD DEFAULT STATUSES ON THEME ACTIVATION
// =============================================

add_action('after_switch_theme', 'bmh_create_default_statuses');
function bmh_create_default_statuses() {
    $statuses = array(
        'active' => 'Active',
        'sold' => 'Sold',
        'pending' => 'Pending',
        'price-reduced' => 'Price Reduced',
        'coming-soon' => 'Coming Soon',
    );
    
    foreach ($statuses as $slug => $name) {
        if (!term_exists($slug, 'property_status')) {
            wp_insert_term($name, 'property_status', array('slug' => $slug));
        }
    }
    
    // Default areas
    $areas = array(
        'vail-village' => 'Vail Village',
        'beaver-creek' => 'Beaver Creek',
        'bachelor-gulch' => 'Bachelor Gulch',
        'arrowhead' => 'Arrowhead',
        'edwards' => 'Edwards',
        'eagle-vail' => 'Eagle-Vail',
        'avon' => 'Avon',
        'wildridge' => 'Wildridge',
        'cordillera' => 'Cordillera',
        'minturn' => 'Minturn',
    );
    
    foreach ($areas as $slug => $name) {
        if (!term_exists($slug, 'property_area')) {
            wp_insert_term($name, 'property_area', array('slug' => $slug));
        }
    }
}

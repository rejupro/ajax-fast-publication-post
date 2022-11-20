<?php

/**
 * Class Fast_Testimonial_Frontend
 */
class Fast_Publication_Post
{

    private static $instance = null;
    public static function get_instance() {
        if ( ! self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Initialize global hooks.
     */
    public function init() {

        // Load shortcode page.
        add_shortcode('fast-publication', array( $this, 'fast_testminial_load_shortcode_callback' ));

        // Load style and script. 
        add_action('admin_enqueue_scripts', array( $this, 'fast_publication_script_callback' ));

        // Register Custom Post Type
        add_action('init', array( $this, 'publication_post_type'), 0);
        
        // Register Taxonomy
        add_action('init', array( $this, 'publication_taxonomy' ));

        // Add Publisher Page
        add_action('admin_menu', array( $this, 'publication_publisher_callback' ));

        // Load Ajax. 
        add_action('wp_ajax_fast_publication_submit', array( $this, 'fast_publication_submit' ));
        add_action('wp_ajax_nopriv_fast_publication_submit', array( $this, 'fast_publication_submit' ));
        


        // Load Ajax. 
        // add_action('wp_ajax_fast_ajax_searchresult', array( $this, 'fast_ajax_searchresult' ));
    }



    // Register Custom Post Type
    function publication_post_type() {

        $labels = array(
            'name'                  => _x( 'Publication Posts', 'Post Type General Name', 'ajax-fast-publication-post' ),
            'singular_name'         => _x( 'Publication Post', 'Post Type Singular Name', 'ajax-fast-publication-post' ),
            'menu_name'             => __( 'Publication', 'ajax-fast-publication-post' ),
            'name_admin_bar'        => __( 'Post Type', 'ajax-fast-publication-post' ),
            'archives'              => __( 'Item Archives', 'ajax-fast-publication-post' ),
            'attributes'            => __( 'Item Attributes', 'ajax-fast-publication-post' ),
            'parent_item_colon'     => __( 'Parent Item:', 'ajax-fast-publication-post' ),
            'all_items'             => __( 'All Items', 'ajax-fast-publication-post' ),
            'add_new_item'          => __( 'Add New Publication', 'ajax-fast-publication-post' ),
            'add_new'               => __( 'Add New', 'ajax-fast-publication-post' ),
            'new_item'              => __( 'New Publication ', 'ajax-fast-publication-post' ),
            'edit_item'             => __( 'Edit Publication', 'ajax-fast-publication-post' ),
            'update_item'           => __( 'Update Publication', 'ajax-fast-publication-post' ),
            'view_item'             => __( 'View Publication', 'ajax-fast-publication-post' ),
            'view_items'            => __( 'View Publication', 'ajax-fast-publication-post' ),
            'search_items'          => __( 'Search Publication', 'ajax-fast-publication-post' ),
            'not_found'             => __( 'Not found', 'ajax-fast-publication-post' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'ajax-fast-publication-post' ),
            'featured_image'        => __( 'Publication Image', 'ajax-fast-publication-post' ),
            'set_featured_image'    => __( 'Set Publication Image', 'ajax-fast-publication-post' ),
            'remove_featured_image' => __( 'Remove Publication Image', 'ajax-fast-publication-post' ),
            'use_featured_image'    => __( 'Use as Publication Image', 'ajax-fast-publication-post' ),
            'insert_into_item'      => __( 'Insert into item', 'ajax-fast-publication-post' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'ajax-fast-publication-post' ),
            'items_list'            => __( 'Items list', 'ajax-fast-publication-post' ),
            'items_list_navigation' => __( 'Items list navigation', 'ajax-fast-publication-post' ),
            'filter_items_list'     => __( 'Filter items list', 'ajax-fast-publication-post' ),
        );
        $args = array(
            'label'                 => __( 'Publication Post', 'ajax-fast-publication-post' ),
            'description'           => __( 'Post Type Description', 'ajax-fast-publication-post' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-text-page',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type( 'publication_post', $args );

    }
    
    function publication_taxonomy() {
        register_taxonomy(
            'publication_categories',
            'publication_post',
            array(
                'hierarchical' => true,
                'label' => 'Categories',
                'query_var' => true,
                'rewrite' => array(
                    'slug' => 'themes',
                    'with_front' => false
                )
            )
        );
    }

    function publication_publisher_callback(){
        add_submenu_page(
            'edit.php?post_type=publication_post',
            __('Publishers', 'ajax-fast-publication-post'),
            __('Publishers', 'ajax-fast-publication-post'),
            'manage_options',
            'publication-publisher-page',
            array( $this, 'publication_publisherpage_callback' )
        );
    }

    /**
     * Display callback for the submenu page.
     */
    function publication_publisherpage_callback() {
        include_once(dirname(__FILE__) . '/Display_Publisher_Template.php');
    }

    // Submit Data
    function fast_publication_submit(){

        if(wp_verify_nonce($_POST['name_nonce'], 'name_nonce')) :

            $name = $_POST['name'];
            $email = $_POST['email'];
            
            global $wpdb;
            $table = $wpdb->prefix.'fastpublication_publisher';
            $data = array(
                'name' => $name,
                'email' => $email
            );
            $format = array('%s','%s');
            $wpdb->insert($table,$data,$format);
            $data = array(
                'message'  => 'Publisher Inserted Successfully',
            );
            wp_send_json($data);

        endif;
    }


    public function fast_testminial_load_shortcode_callback() {

        ob_start(); 
        
        ?>

        <h2>Hello World</h2>

        <?php $allcontents = ob_get_contents(); ?>
        <?php ob_get_clean();
        return $allcontents;
    }
    

    
    /**
     * Load frontend style and script.
     *
     * @return void
     */
    function fast_publication_script_callback() {
        // CSS
        wp_enqueue_style('post-admin', plugin_dir_url(__DIR__) . 'admin/css/ajax-fast-publication-post-admin.css', array(), AJAX_FAST_PUBLICATION_POST_VERSION);

        // JS
        wp_enqueue_script('jquery');
        wp_enqueue_script('publisher-submit', plugin_dir_url(__DIR__) . 'admin/js/ajax-fast-publication-post-admin.js', array( 'jquery' ), AJAX_FAST_PUBLICATION_POST_VERSION, true);
        wp_localize_script('publisher-submit', 'publisherSubmit', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}

Fast_Publication_Post::get_instance()->init();

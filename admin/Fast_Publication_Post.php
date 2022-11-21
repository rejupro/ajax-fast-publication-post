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

        // Data Insert. 
        add_action('wp_ajax_fast_publication_submit', array( $this, 'fast_publication_submit' ));
        add_action('wp_ajax_nopriv_fast_publication_submit', array( $this, 'fast_publication_submit' ));

        // Edit Data 
        add_action('wp_ajax_fast_publication_edit', array( $this, 'fast_publication_edit' ));
        add_action('wp_ajax_nopriv_fast_publication_edit', array( $this, 'fast_publication_edit' ));

        // Update Data 
        add_action('wp_ajax_fast_publication_update', array( $this, 'fast_publication_update' ));
        add_action('wp_ajax_nopriv_fast_publication_update', array( $this, 'fast_publication_update' ));

        // Load Post Meta
        add_action('add_meta_boxes', array( $this, 'add_meta_box' ));
        add_action('save_post',      array( $this, 'save' ));
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
            'supports'              => array( 'title', 'editor', 'thumbnail', 'author' ),
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
            
            $result = $wpdb->get_row("SELECT * FROM $table WHERE email = '$email'");
            if($result == null){
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
            }else{
                $data = array(
                    'message_error'  => 'This Email Already Exist',
                );
                wp_send_json($data);
            }
        endif;
    }

    // Edit Data
    function fast_publication_edit(){
        $id = $_POST['editid'];
        global $wpdb;
        $table = $wpdb->prefix.'fastpublication_publisher';
        $data = $wpdb->get_row ("SELECT * FROM $table WHERE `id` = $id ");
        wp_send_json($data);
    }

    // Update Data
    function fast_publication_update(){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        global $wpdb;
        $table = $wpdb->prefix.'fastpublication_publisher';
        
        $result = $wpdb->get_results("SELECT * FROM $table WHERE email = '$email'");
        $length = sizeof($result);
        
        
        if($length < 2){
            
            $wpdb->update( $table ,array( 
                'name' => $name,
                'email' => $email, 
            ), array( 'id' => $id ) );

            $data = array(
                'message'  => 'Publisher Updated Successfully',
            );
            wp_send_json($data);
        }else{
            $data = array(
                'message_error'  => 'This Email Already Exist',
            );
            wp_send_json($data);
        }
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'publication_post' );

        if ( in_array($post_type, $post_types) ) {
            add_meta_box(
                'some_meta_box_name',
                __('Publication URL & Publisher Option', 'ajax-fast-publication-post'),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

        /*
	         * We need to verify this came from the our screen and with proper authorization,
	         * because save_post can be triggered at other times.
	         */

        // Check if our nonce is set.
        if ( ! isset($_POST['fasttestimonial_inner_custom_box_nonce']) ) {
            return $post_id;
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['fasttestimonial_inner_custom_box_nonce'] ?? ''));

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($nonce, 'fasttestimonial_inner_custom_box') ) {
            return $post_id;
        }

        /*
	         * If this is an autosave, our form has not been submitted,
	         * so we don't want to do anything.
	         */
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $publication_url = sanitize_text_field(wp_unslash($_POST['publication_url'] ?? ''));
        // Sanitize the user input.
        $publication_publisher = sanitize_text_field(wp_unslash($_POST['publication_publisher'] ?? ''));

        // Update the meta field.
        update_post_meta($post_id, '_publication_url', $publication_url);
        update_post_meta($post_id, '_publication_publisher', $publication_publisher);
    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field('fasttestimonial_inner_custom_box', 'fasttestimonial_inner_custom_box_nonce');

        // Use get_post_meta to retrieve an existing value from the database.
        $publication_url = get_post_meta($post->ID, '_publication_url', true);
        $publication_publisher = get_post_meta($post->ID, '_publication_publisher', true);

        // Display the form, using the current value.
        ?>
        <label for="publication_url">
            <?php esc_html_e('Publication PDF url', 'fast-testimonial'); ?>
        </label>
        <input class="widefat" type="text" id="publication_url" name="publication_url" value="<?php echo esc_attr($publication_url); ?>" size="25" />

        <br><br>

        <label for="publication_publisher">
            <?php esc_html_e('Select Publisher', 'fast-testimonial'); ?>
        </label>

        <select class="widefat" name="publication_publisher">
            <option value="" selected="" disabled>Select Publisher</option>
            <?php
            global $wpdb;
            $table = $wpdb->prefix.'fastpublication_publisher';
            $datas = $wpdb->get_results ( "SELECT * FROM $table ORDER BY id DESC");
            foreach($datas as $single) :
            ?>
            <option <?php if ( $single->id == $publication_publisher ) echo "selected='selected'"; ?> value="<?php echo $single->id?>"><?php echo $single->name . ' - ' . $single->email ; ?></option>
            <?php endforeach; ?>
        </select>

        <?php
    }




    public function fast_testminial_load_shortcode_callback() {

        ob_start(); 

        $args = array(
            'taxonomy' => 'publication_categories',
            'orderby' => 'name',
            'order'   => 'ASC'
        );

        $cats = get_categories($args);
            echo "<pre>";
            // print_r($cats);
        ?>
        <?php
            $args = array(
                'post_type' => 'publication_post',
                'post_status' => 'publish',
                'posts_per_page' => 8,
                'order' => 'ASC',
                'meta_key' => '_publication_publisher',
                'meta_value' => 2
                
                // 'tax_query' => array(
                //     array(
                //       'taxonomy' => 'publication_categories',
                //       'field' => 'id',
                //       'terms' => 31 
                //     )
                // )
            );

            $loop = new WP_Query($args);

            while ( $loop->have_posts() ) : $loop->the_post(); ?>
            <h2><?php echo the_title(); ?></h2>
            <?php endwhile ; ?>
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
        wp_localize_script('publisher-submit', 'publisherTable', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('publisher-submit', 'editData', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('publisher-submit', 'updateData', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}

Fast_Publication_Post::get_instance()->init();

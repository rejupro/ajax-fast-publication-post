<?php

/**
 * Class Fast_Testimonial_Frontend
 */
class Fast_Publication_Show
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
        add_action('wp_enqueue_scripts', array( $this, 'fast_publication_script_callback' ));

        // Load More Publication 
        add_action('wp_ajax_fast_publication_loadmore', array( $this, 'fast_publication_loadmore' ));
        add_action('wp_ajax_nopriv_fast_publication_loadmore', array( $this, 'fast_publication_loadmore' ));
        
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

        <div class="fast_allpublication"><!-- 
            --><div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 6,
                    'order' => 'ASC',
                    'paged' => 1
                    // 'meta_key' => '_publication_publisher',
                    // 'meta_value' => 2
                    
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
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h2><?php echo the_title(); ?></h2><!-- 
                            --><span>By Admin</span><!--
                            --><p><?php echo the_content(); ?></p><!--
                    --></div>
                    </div><!--
                --></div>
                <?php endwhile ; ?>
                <?php wp_reset_postdata() ; ?>
            </div>
            
        </div>
        <div class="fast-loadmore-area">
            <p class="fast_load_more">Load More Publications</p>
        </div>
        <?php $allcontents = ob_get_contents(); ?>
        <?php ob_get_clean();
        return $allcontents;
    }
    
    // Load More Data Show
    function fast_publication_loadmore(){
        check_ajax_referer('load_more_posts', 'security');
        $args = array(
            'post_type' => 'publication_post',
            'post_status' => 'publish',
            'posts_per_page' => 6,
            'order' => 'ASC',
            'paged' => $_POST['page']
        );
        $loop = new WP_Query($args);
        if($loop->have_posts()) :
        while ( $loop->have_posts() ) : $loop->the_post(); ?>
        
        <div class="fast_publication_item"><!-- 
            --><div class="fast_publication_img"><!--
            --><?php the_post_thumbnail(); ?><!--
            --><div class="fast_publication_text"><!-- 
                --><h2><?php echo the_title(); ?></h2><!-- 
                    --><span>By Admin</span><!--
                    --><p><?php echo the_content(); ?></p><!--
            --></div>
            </div><!--
        --></div>
            
        <?php endwhile ; ?>
        <?php wp_reset_postdata() ; 
        endif; exit;
    }
    
    /**
     * Load frontend style and script.
     *
     * @return void
     */
    function fast_publication_script_callback() {
        // CSS
        wp_enqueue_style('fast-publication-frontend', plugin_dir_url(__DIR__) . 'frontend/css/frontend.css', array(), AJAX_FAST_PUBLICATION_POST_VERSION);

        // JS
        wp_enqueue_script('jquery');
        wp_enqueue_script('fast-publicationshow', plugin_dir_url(__DIR__) . 'frontend/js/frontend.js', array( 'jquery' ), AJAX_FAST_PUBLICATION_POST_VERSION, true);
        wp_localize_script('fast-publicationshow', 'publicationLoad', array('ajaxurl' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce('load_more_posts')));
    }
}

Fast_Publication_Show::get_instance()->init();

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

        // Publication Filter Shortcode.
        add_shortcode('fast-publication-shortcode', array( $this, 'fast_publication_filter_shortcode_callback' ));
        // Load shortcode page.
        add_shortcode('fast-publication', array( $this, 'fast_testminial_load_shortcode_callback' ));

        // Load style and script. 
        add_action('wp_enqueue_scripts', array( $this, 'fast_publication_script_callback' ));

        // Load More Publication 
        add_action('wp_ajax_fast_publication_loadmore', array( $this, 'fast_publication_loadmore' ));
        add_action('wp_ajax_nopriv_fast_publication_loadmore', array( $this, 'fast_publication_loadmore' ));

        // Search Publication 
        add_action('wp_ajax_fast_ajax_pubsearch', array( $this, 'fast_ajax_pubsearch' ));
        add_action('wp_ajax_nopriv_fast_ajax_pubsearch', array( $this, 'fast_ajax_pubsearch' ));

        // Publication by Category 
        add_action('wp_ajax_fast_ajax_pubcategory', array( $this, 'fast_ajax_pubcategory' ));
        add_action('wp_ajax_nopriv_fast_ajax_pubcategory', array( $this, 'fast_ajax_pubcategory' ));

        // Publication by Author 
        add_action('wp_ajax_fast_ajax_pubauthorin', array( $this, 'fast_ajax_pubauthorin' ));
        add_action('wp_ajax_nopriv_fast_ajax_pubauthorin', array( $this, 'fast_ajax_pubauthorin' ));

        // Publication by Publisher 
        add_action('wp_ajax_fast_ajax_publisherin', array( $this, 'fast_ajax_publisherin' ));
        add_action('wp_ajax_nopriv_fast_ajax_publisherin', array( $this, 'fast_ajax_publisherin' ));

        // Publication by Yearin 
        add_action('wp_ajax_fast_ajax_yearin', array( $this, 'fast_ajax_yearin' ));
        add_action('wp_ajax_nopriv_fast_ajax_yearin', array( $this, 'fast_ajax_yearin' ));

        // Publication by Monthin 
        add_action('wp_ajax_fast_ajax_monthin', array( $this, 'fast_ajax_monthin' ));
        add_action('wp_ajax_nopriv_fast_ajax_monthin', array( $this, 'fast_ajax_monthin' ));

    }


    function fast_publication_filter_shortcode_callback(){
        ob_start();
        ?>
            
            <div class="fastajax-publication-filter">
            <div class="fastajax-publication-searchbox">
                <form action="" id="fastajax-publicationform" autocomplete="off">
                    <input type="text" class="fastinput" data-nonce="<?php echo wp_create_nonce('fast_ajax_nonce'); ?>" id="fastpublicationinput" placeholder="Search Here..." >
                    <button>Search</button>
                </form>
                
            </div>
            <div class="fastajax-catpubfilter">

                <?php
                   function get_posts_years_array() {
                        global $wpdb;
                        $result = array();
                        $years = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT YEAR(post_date) FROM {$wpdb->posts} WHERE post_status = 'publish' GROUP BY YEAR(post_date) DESC"
                            ),
                            ARRAY_N
                        );
                        if ( is_array( $years ) && count( $years ) > 0 ) {
                            foreach ( $years as $year ) {
                                $result[] = $year[0];
                            }
                        }
                        return $result;
                    }
                    
                    // Echo the years out wherever you want
                    $years = get_posts_years_array();
                ?>

                <div class="single-pubfitler">
                    <label for="">Select Category</label>
                    <?php
                        $args = array(
                            'taxonomy' => 'publication_categories',
                            'orderby' => 'name',
                            'order'   => 'ASC'
                        );
                        $categories = get_categories($args);
                    ?>
                    <select class="fastpub-select" id="fast_pubcat">
                        <option value="" selected="" disabled>Select Category</option>
                        <?php foreach($categories as $single) : ?>
                        <option value="<?php echo $single->term_id; ?>"><?php echo $single->name; ?></option>
                        <?php endforeach ; ?>
                    </select>
                </div>
                <div class="single-pubfitler">
                    <label>Select Author</label>
                    <select class="fastpub-select" id="fast_pubauthor">
                        <option value="" selected="" disabled>Select Author</option>
                        <?php 
                            $users = get_users();
                        ?>
                        <?php foreach($users as $single) : ?>
                        <option value="<?php echo $single->id; ?>"><?php echo $single->display_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="single-pubfitler">
                    <label>Select Publisher</label>
                    <select class="fastpub-select" id="fast_pubpublisher">
                        <option value="" selected="" disabled>Select Publisher</option>
                        <?php
                        global $wpdb;
                        $table = $wpdb->prefix.'fastpublication_publisher';
                        $datas = $wpdb->get_results ( "SELECT * FROM $table ORDER BY id DESC");
                        foreach($datas as $single) :
                        ?>
                        <option value="<?php echo $single->id; ?>"><?php echo $single->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="single-pubfitler">
                    <label>Select Year</label>
                    <select class="fastpub-select" id="fast_pubyear">
                        <option value="" selected="" disabled>Select Year</option>
                        <?php foreach($years as $single) : ?>
                        <option value="<?php echo $single; ?>"><?php echo $single; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="single-pubfitler" id="fast_pubmonthfilter">
                    <label>Select Month</label>
                    <select class="fastpub-select" id="fast_pubmonth">
                    <option value="" selected="" disabled>Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March </option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September </option>
                        <option value="10">October </option>
                        <option value="11">November</option>
                        <option value="12">December </option>
                    </select>
                </div>
            </div>

            <div class="publicationOutputs" id="publicationOutputs">
                <div class="pubsearchOutput" id="pubsearchOutput">

                </div>
            </div>


        </div>

        <?php $publication_filter = ob_get_contents(); ?>
        <?php ob_get_clean();
        return $publication_filter;
    }
    




    public function fast_testminial_load_shortcode_callback() {

        ob_start(); 

        ?>

        <div class="fast_allpublication"><!-- 
            --><div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 6,
                    'order' => 'DSC',
                    'paged' => 1
                    
                );
                $loop = new WP_Query($args);
                while ( $loop->have_posts() ) : $loop->the_post(); 
                $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div>
                <?php endwhile ; wp_reset_postdata(); ?>
            </div><!--
        --></div><!--
        --><div class="fast-loadmore-area"><!--
            --><p class="fast_load_more">Load More Publications</p><!--
        --></div>
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
            'order' => 'DESC',
            'paged' => $_POST['page']
        );
        $loop = new WP_Query($args);
        if($loop->have_posts()) :
            while ( $loop->have_posts() ) : $loop->the_post(); 
            $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
            ?>
            <div class="fast_publication_item"><!-- 
            --><div class="fast_publication_img"><!--
            --><?php the_post_thumbnail(); ?><!--
            --><div class="fast_publication_text"><!-- 
                --><h3><?php echo the_title(); ?></h3><!-- 
                    --><span>By <?php the_author(); ?></span><!--
                    --><?php echo the_content(); ?><!--
                    --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
            --></div>
            </div><!--
        --></div>
        <?php endwhile ; wp_reset_postdata(); ?>
        <?php 
        endif; exit;
    }
    

    // Include Filter Data 
    function fast_ajax_pubsearch(){

            if(wp_verify_nonce($_POST['searchNonce'], 'fast_ajax_nonce')) :
            if(isset($_POST['search_data']) && !empty($_POST['search_data'])) :

        ?>
            <div class="ajax-searchloading"></div>
            <div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 12,
                    'order' => 'DSC',
                    's' => $_POST['search_data'],
                );
                $loop = new WP_Query($args);

                if($loop->have_posts()) :

                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div> 
                <?php endwhile ; wp_reset_postdata(); ?>
                
                <?php else : ?>
                    <p style="color: red">Sorry Not Found for the word <strong><?php echo esc_html($_POST['search_data']) ; ?></strong></p>
                <?php endif; ?>    
            </div>
        <?php endif; endif; exit;
    }
    
    // publication by category
    function fast_ajax_pubcategory(){
        if(isset($_POST['catin'])) :
        ?>    

            <div class="ajax-searchloading"></div>
            <div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 12,
                    'order' => 'DSC',
                    'tax_query' => array(
                        array(
                          'taxonomy' => 'publication_categories',
                          'field' => 'id',
                          'terms' => $_POST['catin'] 
                        )
                    )
                );
                $loop = new WP_Query($args);

                if($loop->have_posts()) :

                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div> 
                <?php endwhile ; wp_reset_postdata(); ?>
                
                <?php else : ?>
                    <p style="color: red">Sorry not found publication from this category</p>
                <?php endif; ?>    
            </div>

        <?php endif; exit;    
    }

    // fast_ajax_pubauthorin
    function fast_ajax_pubauthorin(){
        if(isset($_POST['authorin'])) :
        ?>    

            <div class="ajax-searchloading"></div>
            <div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 12,
                    'order' => 'DSC',
                    'author' => $_POST['authorin']
                );
                $loop = new WP_Query($args);

                if($loop->have_posts()) :

                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div> 
                <?php endwhile ; wp_reset_postdata(); ?>
                
                <?php else : ?>
                    <p style="color: red">Sorry not found publication from this Author</p>
                <?php endif; ?>    
            </div>

        <?php endif; exit;    
    }

    // Post by Publisher 
    function fast_ajax_publisherin(){
        if(isset($_POST['publisherin'])) :
        ?>    

            <div class="ajax-searchloading"></div>
            <div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 12,
                    'order' => 'DSC',
                    'meta_key' => '_publication_publisher',
                    'meta_value' => $_POST['publisherin']
                );
                $loop = new WP_Query($args);

                if($loop->have_posts()) :

                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div> 
                <?php endwhile ; wp_reset_postdata(); ?>
                
                <?php else : ?>
                    <p style="color: red">Sorry not found publication from this Publisher</p>
                <?php endif; ?>    
            </div>

        <?php endif; exit;    
    }

    // Publication by Year 
    function fast_ajax_yearin(){
        if(isset($_POST['publisheryearin'])) :
        ?>    

            <div class="ajax-searchloading"></div>
            <div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 12,
                    'order' => 'DSC',
                    'date_query' => array(
                        'year' => $_POST['publisheryearin'],
                    ),
                );
                $loop = new WP_Query($args);

                if($loop->have_posts()) :

                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div> 
                <?php endwhile ; wp_reset_postdata(); ?>
                
                <?php else : ?>
                    <p style="color: red">Sorry not found publication from this Year</p>
                <?php endif; ?>    
            </div>

        <?php endif; exit;    
    }

    // Publication by Month 
    function fast_ajax_monthin(){
        if(isset($_POST['publishermonthin'])) :
        ?>    

            <div class="ajax-searchloading"></div>
            <div class="fast-publication-gridpost">
                <?php
                $args = array(
                    'post_type' => 'publication_post',
                    'post_status' => 'publish',
                    'posts_per_page' => 12,
                    'order' => 'DSC',
                    'date_query' => array(
                        'year' => $_POST['publisheryearin'],
                        'month' => $_POST['publishermonthin']
                    ),
                );
                $loop = new WP_Query($args);

                if($loop->have_posts()) :

                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $publication_url = get_post_meta(get_the_ID(), '_publication_url', true);
                ?>
                    <div class="fast_publication_item"><!-- 
                    --><div class="fast_publication_img"><!--
                    --><?php the_post_thumbnail(); ?><!--
                    --><div class="fast_publication_text"><!-- 
                        --><h3><?php echo the_title(); ?></h3><!-- 
                            --><span>By <?php the_author(); ?></span><!--
                            --><?php echo the_content(); ?><!--
                            --><a href="<?php echo $publication_url; ?>" target="__blank">View</a><!--    
                    --></div>
                    </div><!--
                --></div> 
                <?php endwhile ; wp_reset_postdata(); ?>
                
                <?php else : ?>
                    <p style="color: red">Sorry not found publication from this Month</p>
                <?php endif; ?>    
            </div>

        <?php endif; exit;    
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
        wp_localize_script('fast-publicationshow', 'publicationSearch', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('fast-publicationshow', 'publicationCategory', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('fast-publicationshow', 'publicationPublisher', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('fast-publicationshow', 'publicationYear', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_localize_script('fast-publicationshow', 'publicationMonth', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}

Fast_Publication_Show::get_instance()->init();

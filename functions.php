<?php 

function pageBanner($args = null)
{
    if(!$args['title']) {
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']) {
        if(get_field('page_banner_background_image')) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('images/ocean.jpg');
        }
    }
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <?php echo $args['subtitle']; ?>
            </div>
        </div>  
    </div>
<?php }

function university_files()
{
    wp_enqueue_script( 'googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyC1WzWGRL1fjZAE7wV8lEX34svtQ4MTTSw', NULL, microtime(), true);
    wp_enqueue_script( 'main-university-js', get_theme_file_uri('js/scripts-bundled.js'), NULL, microtime(), true);
    wp_enqueue_style( 'custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
    //How to integrate Menu functionality into WP Theme
    // register_nav_menu( 'headerMenu', 'Header Menu Location' );
    // register_nav_menu( 'footerMenuOne', 'Footer Menu Location One' );
    // register_nav_menu( 'footerMenuTwo', 'Footer Menu Location Two' );
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query)
{
    // Query to use at pre-post state 
    if(!is_admin() && is_post_type_archive( 'program' ) && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api)
{
    $api['key'] = 'AIzaSyC1WzWGRL1fjZAE7wV8lEX34svtQ4MTTSw';
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey')
?>
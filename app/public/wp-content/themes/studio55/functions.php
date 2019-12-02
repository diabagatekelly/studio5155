<?php 
function important_files() {
    wp_enqueue_script('javaScript', get_theme_file_uri('/js/scripts.js'), 'NULL', microtime(), true);
    wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.3.1.slim.min.js');
    wp_enqueue_script('popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');
    wp_enqueue_script('bootstrap-js', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js');
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    
    wp_enqueue_style('main_styles', get_stylesheet_uri(), NULL, microtime()); //get style sheet style.css
}
add_action('wp_enqueue_scripts', 'important_files');

function features() {
    register_nav_menu('footerMenu', 'Footer Menu');
    register_nav_menu('headerMenu', 'Header Menu');
    register_nav_menu('legalMenu', 'Legal Menu');



    add_theme_support('title-tag');
    
	add_filter( 'get_the_archive_title', function ($title) {    
    if ( is_category() ) {    
            $title = single_cat_title( '', false );    
        } elseif ( is_tag() ) {    
            $title = single_tag_title( '', false );    
        } elseif ( is_author() ) {    
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;    
        } elseif ( is_tax() ) { //for custom post types
            $title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
        }    
    return $title;    
});
}
add_action('after_setup_theme', 'features');

function custom_sidebar() {
    register_sidebar(
        array(
            'name' => __( 'Custom'),
            'id' => 'custom-sidebar',
            'description' => __('Custom Sidebar for Blog Page'),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        )
        );
}
add_action( 'widgets_init', 'custom_sidebar');

function wpse74620_ignore_sticky($query)
{
    if (is_home() && $query->is_main_query())
    $query->set('post__not_in', get_option('sticky_posts'));

}
add_action('pre_get_posts', 'wpse74620_ignore_sticky');

function woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'woocommerce_support');

function my1234_get_total_sales() {
 
    global $wpdb;
     
    $order_totals = apply_filters( 'woocommerce_reports_sales_overview_order_totals', $wpdb -> get_row( "
     
    SELECT SUM(meta.meta_value) AS total_sales, COUNT(posts.ID) AS total_orders FROM {$wpdb -> posts} AS posts
    
    LEFT JOIN {$wpdb -> postmeta} AS meta ON posts.ID = meta.post_id
     
    WHERE meta.meta_key = '_order_total'
     
    AND posts.post_type = 'shop_order'
     
    AND posts.post_status IN ( '" . implode( "','", array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) . "' )
     
    " ) );
     
    return absint( $order_totals -> total_sales);
     
    }

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function my_header_add_to_cart_fragment( $fragments ) {
 
    ob_start();
    $count = WC()->cart->cart_contents_count;
    ?><a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
    if ( $count > 0 ) {
        ?>
        <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
        <?php            
    }
        ?></a><?php
 
    $fragments['a.cart-contents'] = ob_get_clean();
     
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );

/**
 * Exclude products from a particular category on the shop page
 */
function custom_pre_get_posts_query( $q ) {

    $tax_query = (array) $q->get( 'tax_query' );

    $tax_query[] = array(
           'taxonomy' => 'product_cat',
           'field' => 'slug',
           'terms' => array( 'active-campaigns', 'past-campaigns' ), // Don't display products in the clothing category on the shop page.
           'operator' => 'NOT IN'
    );


    $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );  
?>
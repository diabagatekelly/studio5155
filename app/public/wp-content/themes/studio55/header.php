<! DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
    <meta charset="<?php bloginfo('charset') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(  ); ?>
    </head>
    <body <?php body_class() ?>>
    <header class="site-header">
                      <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?php echo site_url()?>"><img class="img-fluid" src="<?php echo get_theme_file_uri('/images/logo.jpg') ?>"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <?php wp_nav_menu(array(
                           'theme_location' => 'headerMenu'
                           )) ?>
    </ul>
    <form class="form-inline my-2 my-lg-0">
    <span class="dashicons dashicons-search " data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></span>
    <input class="form-control mr-sm-2 collapse" id="collapseExample" type="search"  placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>"
            value="<?php echo get_search_query() ?>" name="s"
            title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0 search-submit collapse" id="collapseExample" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>">Search</button>
    <span class="separator">|</span>
    <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
 $count = WC()->cart->cart_contents_count;
 ?><a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php 
 if ( $count > 0 ) {
     ?>
     <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
     <?php
 }
     ?></a>

<?php } ?>

    </form>
  </div>
</nav>
                      </header>
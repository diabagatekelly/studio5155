<?php get_header();?>
<div class="jumbotron jumbotron-fluid" style="background-image: url(<?php echo get_theme_file_uri('/images/header.jpg') ?>); background-repeat: no-repeat; background-size: cover; height: 90vh">
  <div class="container header">
    <h1 class="display-4"><?php bloginfo('title')?></h1>
    <p class="lead"><?php bloginfo('description') ?></p>
  </div>
</div>
<div class="container-fluid">
    <div class="row d-flex align-items-center">
        <div class="col-12 justify-content-center text-center">
<h2>Active Campaigns</h2>
        </div>
        <?php 
$args = array( 
    'post_type' => 'product',
    'product_cat' => 'Active Campaigns',
    'posts_per_page' => '4'
);

$products = new WP_Query($args);


while($products -> have_posts()) {
    $products -> the_post(); ?>
    <?php $timer = get_field('timer'); ?>
    <?php $logo = get_field('logo'); ?>


<div class="col-xs-12 col-lg-3 text-center justify-content-center campaigns">
    <a href="<?php the_permalink();?>"><img class="img-fluid logo" src="<?php echo $logo ?>"></a>
    <?php echo $timer ?>
    <a href="<?php the_permalink(); ?>">View Campaign</a>
    </div>
<?php
}
?>

    </div>
    <?php
    $params = array( 
        'post_type' => 'product',
        'product_cat' => 'Past Campaigns',
        'posts_per_page' => '8'
    );
    
    $past = new WP_Query($params); 
    if($past -> have_posts()) {
        ?>

        <div class="row d-flex align-items-center">
        <div class="col-12 justify-content-center text-center">
            <h2>Past Campaigns</h2>
        </div>
        <?php 



while($past -> have_posts()) {
    $past -> the_post(); ?>
    <?php $logo = get_field('logo'); ?>


<div class="col-xs-12 col-md-4 col-lg-3 text-center justify-content-center campaigns">
    <img class="img-fluid logo" src="<?php echo $logo ?>">
    </div>
<?php
}
?>
<div class="col-12 text-center justify-content-center campaigns">
    <h3>
        Thank you for helping us raise over $<?php echo ($order_totals)*0.10 ?> in past campaigns!
    </h3>
</div>
    </div>
    <?php
    }
    ?>
</div>




<?php get_footer() ?>
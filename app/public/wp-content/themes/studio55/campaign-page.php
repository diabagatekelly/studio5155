<?php /*Template Name: CustomPageT2 */ ?>
<?php get_header();?>
<div class="jumbotron jumbotron-fluid text-center" style="background-image: url(<?php $pageBannerImage = get_field('banner_image'); echo $pageBannerImage['url'] ?>); background-repeat: no-repeat; background-size: cover; height: 30vh">
  <div class="container">
    <h1><?php the_title()?></h1>
  </div>
</div>
<div class="container-fluid">
    <div class="row d-flex align-items-center">

        <?php 
$args = array( 
    'post_type' => 'product',
    'product_cat' => 'Active Campaigns',
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
    </div>




<?php get_footer() ?>
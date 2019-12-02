<?php /*Template Name: CustomPageT1 */ ?>
<?php 
get_header();
?>

<div class="container-fluid">
    <div class="row d-flex align-items-center page-title" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(<?php $pageBannerImage = get_field('banner_image'); echo $pageBannerImage['url'] ?>); background-repeat: no-repeat; background-size: cover;">
        <div class="col text-center justify-content-center">
            <h1><?php the_title()?></h1>
        </div>
    </div>

<?php
while(have_posts(  )) {
the_post(  ); ?>
   
    <?php the_content()?>
<?php }
?>

    <div class="row d-flex align-items-center footer">
    <div class="col">
        <?php get_footer(); ?>
    </div>
</div>

</div>
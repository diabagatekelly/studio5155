<?php get_header(); ?>
<div class="jumbotron jumbotron-fluid text-center" style="background-image: url(<?php $pageBannerImage = get_field('banner_image'); echo $pageBannerImage['url'] ?>); background-repeat: no-repeat; background-size: cover; height: 30vh">
  <div class="container">
    <h1><?php the_title()?></h1>
  </div>
</div>
<div class="container-fluid pages">
<?php while(have_posts(  )) {
the_post(); ?>
<div >
<?php the_content() ?>
</div>
<?php
}
?>
</div>
<?php get_footer(); ?>
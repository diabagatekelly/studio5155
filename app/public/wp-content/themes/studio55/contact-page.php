<?php /*Template Name: CustomPageT3 */ ?>
<?php get_header(); ?>
<div class="jumbotron jumbotron-fluid text-center" style="background-image: url(<?php $pageBannerImage = get_field('banner_image'); echo $pageBannerImage['url'] ?>); background-repeat: no-repeat; background-size: cover; height: 30vh">
  <div class="container">
    <h1><?php the_title()?></h1>
  </div>
</div>
<div class="container-fluid">
    <div class="row d-flex align-items-center">
        <div class="col-12">
            <p>Fill out this form to get in touch with questions, leave comments, or submit a new campaign proposal. We will get back to you promptly. Thank you!</p>
        </div>
        <div class="col-12">
        <?php while ( have_posts() ) : the_post(); ?>
				
				
				<?php the_content(); ?>
				

			<?php endwhile; ?>
        </div>
    </div>
</div>
	

<?php get_footer(); ?>
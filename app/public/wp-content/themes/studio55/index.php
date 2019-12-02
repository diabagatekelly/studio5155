<?php get_header();?>

<div class="container-fluid events-page">
    <div class="row d-flex align-items-center events-title" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(<?php echo get_theme_file_uri('/images/blog.jpg') ?>); background-repeat: no-repeat; background-size: cover;">
        <div class="col text-center justify-content-center">
            <h1>Events</h1>
        </div>
    </div>

    <div class="row d-flex align-items-top blog-container">
        <div class="col-xs-12 col-sm-9 text-center justify-content-center">
            <div class="row d-flex align-items-center">
            
            <?php 
            $stickies = get_option( 'sticky_posts' );
if ( $stickies ) {
    $args = [
        'post_type'           => 'post',
        'post__in'            => $stickies,
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => 1
    ];
    $featured = new WP_Query($args);
            
            if($featured -> have_posts()) {
                ?>
                <div class="col-12">
                    <h2 class="text-left">Featured Event</h2>
                </div>
                <div class="col-12 featured-blog">

                <?php 
       
        while($featured -> have_posts()) {
            $featured -> the_post(); ?>
<div class="col">
            <h4><a href="<?php the_permalink();?>"><?php the_title()?></a></h4>
            <p>Posted by <?php the_author()?> on <?php the_time('n/j/y') ?> </p>
            <a href="<?php the_permalink();?>"><img class="img-fluid" src="<?php $pageBannerImage = get_field('banner'); echo $pageBannerImage['url'] ?>"></a>
            <p><?php echo wp_trim_words(get_the_content(), 50) ?></p>
            <p><?php wp_trim_words(the_content(), 50) ?></p>

            <a href="<?php the_permalink(); ?>">View Full Post</a>
            </div>

<?php
        }
        ?>
</div>

    <?php }
}
    ?>
<div class="col-12">
    <h2 class="text-left">What's New</h2>
</div>
<div class="row d-flex align-items-center regular-posts">



<?php 

if(have_posts()) {

while(have_posts(  )) {
the_post(); ?>
<div class="col-xs-12 col-sm-6 col-md-3 text-center justify-content-center blog-post" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(<?php $pageBannerImage = get_field('banner'); echo $pageBannerImage['url'] ?>); background-repeat: no-repeat; background-size: cover; color: white">
            <h4><a href="<?php the_permalink();?>"><?php the_title()?></a></h4>
            <div>
                <p>Posted by <?php the_author()?> on <?php the_time('n/j/y') ?></p>
            </div>
            <div>
            <p><?php echo wp_trim_words(get_the_content(), 2) ?></p>
            </div>
            <a href="<?php the_permalink(); ?>">Continue Reading</a>
            </div>
<?php
}
} else {
    ?>
    <div class="col">
    <h4>Coming Soon!</h4>
</div>
<?php
}
?>
    


<div class="col-12 text-left">
<?php 
        echo paginate_links();
    ?>
</div>
</div>

        </div>
        </div>
<div class="col-xs-12 col-sm-3 sidebar">
            <?php if (is_active_sidebar('custom-sidebar')) : ?>
            <?php dynamic_sidebar('custom-sidebar'); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row d-flex align-items-center footer">
    <div class="col">
        <?php get_footer(); ?>
    </div>
</div>


</div>
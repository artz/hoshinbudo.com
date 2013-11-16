<?php
/**
 * Template Name: Portal
 *
 * Our layout for the portal (home page).
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Hoshin
 * @since Hoshin 1.0
 */
// print_r( $wp_query );
$original_query = clone $wp_query;
get_header();
?>
<div class="content">
<div class="recent-articles">
<h1><a href="/articles/">Recent Articles</a></h1>
<?php
query_posts( "posts_per_page=4" );
while ( have_posts() ) : the_post();
?>
<h2><a href="<?=the_permalink()?>"><?=the_title()?></a></h2>
<i><?=the_date('m.d.y')?> <?=get_the_author()?></i>
<? echo limit_text( get_the_excerpt(), 17 ); ?>
<a class="more" href="<?=the_permalink()?>">Read More</a>
<? endwhile; ?>
</div>
<?php
    get_template_part('homepage_promo');
    dynamic_sidebar("homepage-video-bar");
?>
</div>
</div>
<div class="footer">
<div class="content">
<div class="welcome">
<h1>Welcome to Hoshin Budo</h1>
<?php
	$wp_query = clone $original_query;
/* Welcome Message (Home Page) */
	if (have_posts()) : ?>
   <?php while ( have_posts() ) : the_post(); ?>
<?=the_content()?>
   <?php endwhile; ?>
<?php endif; ?>
</div>
<div class="upcoming-events">
<h2>Upcoming Events</h2>
<?php
	em_events( array("limit" => 3) );
?>
</div>
</div>
<?php get_footer(); ?>

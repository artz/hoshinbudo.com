<?php
/**
 * Template Name: Articles
 *
 * Our layout for the articles page.
 *
 * @package WordPress
 * @subpackage Hoshin
 * @since Hoshin 1.0
 */

get_header();
?>
<div class="content">
<div class="aside">
<?php get_sidebar("articles"); ?>
</div>
<div class="recent-articles">
<?php

// The Query
$the_query = new WP_Query( "post_type=post&posts_per_page=10" );

// The Loop
while ( $the_query->have_posts() ) : $the_query->the_post();
?>
<h2><a href="<?=the_permalink()?>"><?=the_title()?></a></h2>
<i><?=the_date()?></i>
<?=the_excerpt()?><!-- <a class="more" href="<?=the_permalink()?>">Read More</a> -->
<?php
endwhile;

// Reset Post Data
wp_reset_postdata();

?>
</div>
</div>
<?php get_footer(); ?>
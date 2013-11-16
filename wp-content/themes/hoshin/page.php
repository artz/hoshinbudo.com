<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<div class="content">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<nav><?php

if ( $post->post_title == 'Events' ):
	get_sidebar("events");
else:
echo "<ul>";
    if ( $post->post_parent ) {
        $post_parent_id = $post->post_parent;
    } else {
        $post_parent_id = $post->ID;
    }

    $args = array(
        'depth'        => 1,
//		'show_date'    => '',
//		'date_format'  => get_option('date_format'),
        'child_of'     => $post_parent_id,
//		'exclude'      => '',
//		'include'      => '',
        'title_li'     => ''
//		'echo'         => 1,
//		'authors'      => '',
//		'sort_column'  => 'menu_order, post_title',
//		'link_before'  => '',
//		'link_after'   => '',
//		'walker' => ''
    );

    wp_list_pages( $args );
echo "</ul>";
endif;

?>
</nav>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<h1><b><?php the_title(); ?></b></h1>
<?php the_content(); ?>

<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>

<?php // comments_template( '', true ); ?>
<?php endwhile; ?>
</article>
<?php get_footer(); ?>
</div>

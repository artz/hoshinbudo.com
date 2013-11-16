<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<div class="content">
<div class="post">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php edit_post_link( __( '<b>Edit &raquo;</b>', 'hoshin' ), '<span class="edit-link">', '</span>' ); ?>
    <h1 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
    <div class="entry-meta">
        <?php hoshin_posted_on(); ?>
    </div><!-- .entry-meta -->

    <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'hoshin' ), 'after' => '</div>' ) ); ?>
    </div><!-- .entry-content -->

    <div class="entry-utility">
        <?php hoshin_posted_in(); ?>
    </div><!-- .entry-utility -->
</div><!-- #post-## -->

<div id="nav-below" class="navigation">
    <div class="nav-previous"><?php previous_post_link( '&larr; Previous Article: %link' ); ?></div>
    <div class="nav-next"><?php next_post_link( 'Next Article: %link &rarr;' ); ?></div>
</div><!-- #nav-below -->

<?php comments_template( '', true ); ?>
<?php endwhile; // end of the loop. ?>

</div>
<div class="aside">
<?php get_sidebar("articles"); ?>
</div>

<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
<div class="author-info">
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'hoshin_author_bio_avatar_size', 60 ) ); ?>
	</div><!-- #author-avatar -->
	<div class="author-description">
		<h2><?php printf( esc_attr__( 'About %s', 'hoshin' ), get_the_author() ); ?></h2>
		<?php the_author_meta( 'description' ); ?>
		<div class="author-link">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
				<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'hoshin' ), get_the_author() ); ?>
			</a>
		</div>
	</div>
</div>
<?php endif; ?>

</div>
<?php get_footer(); ?>

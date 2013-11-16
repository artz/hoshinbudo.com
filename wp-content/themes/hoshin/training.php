<?php
/**
 * Template Name: Training
 *
 * Our layout for the training page.
 *
 * @package WordPress
 * @subpackage Hoshin
 * @since Hoshin 1.0
 */

get_header(); ?>
<div class="content">
<nav>
    <h1><b>Training</b>
      <!--    <ul>
        <li class="active"><a href="#">Overview</a></li>
        <li><a href="#">Local</a>
			<ul><li><a href="#">Hoshin Hombu</a><i>Yorktown, VA</i></li>
				<li><a href="#">Valley of the Wind Hoshin Dojo</a><i>Charlottesville, VA</i></li>
				<li><a href="#">Wasatch Hoshin Dojo</a><i>Wasatch, UT</i></li>
			</ul>
		</li>
        <li><a href="#">Distance</a></li>
    </ul>
-->
</h1>
    <ul>
<li><h2>Hoshin Hombu</h2>
<i>Yorktown, VA</i>
<a href="mailto:hoshinsoke@gmail.com">Contact</a></li>
<li>
  <h2>Raleigh Dojo</h2>
  <i>Raleigh, NC<br />
  </i><a href="http://trianglehoshin.com/" target="_blank">www.trianglehoshin.com</a> <a href="mailto:Alexanderjfa402001@yahoo.com">Contact</a></li>
<li>
  <h2>Shihan Robinson</h2>
  <i>Williamsburg, VA</i> <a href="mailto:killyred1@yahoo.com">Contact</a></li>
<li>
  <h2>Seven Centers Dojo</h2>
  <i>Lake Charles, LA</i> <a href="mailto:cosmophage@yahoo.com">Contact</a></li>
<li>
<h2>Charlottesville Dojo</h2>
<i>Charlottesville, VA</i>
<a href="http://www.hoshincville.com">www.hoshincville.com</a>
<a href="mailto:joel@hoshincville.com">Contact</a></li>
<li>
  <h2>Wasatch Dojo</h2>
  <i>Point of the Mountain, UT</i> <a href="http://www.wasatchhoshindojo.com">www.wasatchhoshindojo.com</a> <a href="mailto:wasatchhoshindojo@gmail.com">Contact</a></li>
<li><h2>St. Louis Dojo</h2>
<i>St. Louis, MO</i>
<a href="mailto:stlhoshin@gmail.com">Contact</a></li>
<li>
  <h2>Lafayette Dojo</h2>
  <i>Lafayette, LA</i><br />
(337) 288-8090</li>
<li>
  <h2>Clarksville Dojo</h2>
  <i>Clarksville, TN</i> <a href="mailto:hoshin_ronin@hotmail.com">Contact</a></li>
<li>
  <h2>Dragon Path Dojo</h2>
  <i>Provo, UT</i> <a href="mailto:calebspears@yahoo.com">Contact</a></li>
<i><br />
If you have an active Hoshin dojo or training group that is not listed above email <a href="mailto:joel@hoshincville.com">here</a>.</i>
    </ul>
</nav>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>    
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php the_content(); ?>

<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'hoshin' ), 'after' => '</div>' ) ); ?>
<?php edit_post_link( __( 'Edit', 'hoshin' ), '<span class="edit-link">', '</span>' ); ?>

<?php // comments_template( '', true ); ?>
<?php endwhile; ?>
</article>
<?php get_footer(); ?>
</div>
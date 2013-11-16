<?php
/**
 * Template Name: Events
 *
 * Our layout for the events page.
 *
 * @package WordPress
 * @subpackage Hoshin
 * @since Hoshin 1.0
 */

get_header(); 

// http://wp-events-plugin.com/documentation/template-tags/
// em_events();
// em_calendar();
// em_locations();

?>
<div class="content">
<div class="aside">
<?php get_sidebar("events"); ?>
</div>
<ul class="recent-events">
<?php
	em_events();
?>
</ul>
</div>
<?php get_footer(); ?>
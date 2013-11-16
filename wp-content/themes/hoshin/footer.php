<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Hoshin
 * @since Hoshin 1.0
 */
?>
</div>
<footer class="copyright">&copy; <?=date('Y')?> HOSHIN BUDO</footer>
<?php if ( is_page_template('portal.php') ):
// We could try this, though I don't believe $page_id is set.
// print_r( get_post_custom($page_id) );
// echo get_post_meta( $page_id, '_wp_page_template', true );
?>
<?php endif; ?>
<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28057770-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>

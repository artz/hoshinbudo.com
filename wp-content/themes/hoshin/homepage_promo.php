<?php
	$homepage_promos = get_artz_promos( 127 );
	// $active_panel = ' class="active-panel"';
	// print_r( $homepage_promos );
?>
<ol class="promo">
<?php 
	$count = 0;
	foreach ( $homepage_promos as $promo ): ?>
<li<? // =$active_panel ?>>
<a href="<?=$promo->URL?>"><?=$promo->image_promo_src?>
<?=$promo->title?></a>
<p><?=$promo->subtitle?></p>
</li>
<?php 
		$active_panel = '';
		if ( ++$count > 2 ) {
			break;
		}
	endforeach; 
?>
</ol>
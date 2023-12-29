<?php

$loc = new localization ($_SESSION['language']);

?>

		<section class="hero bg-white border-bottom-1 border-grey-200">
			<div class="container">
				<div class="page-header">
					<div class="page-title"><?php echo $loc->label("Contact Us");?></div>
				</div>
			</div>
		</section>
	
		<section style="margin-bottom: 300px;" class="border-bottom-1 border-grey-400 padding-30">
			<div class="container text-center">
				<h2 class="font-size-22 font-weight-300"><?php echo $loc->label("ContactTextHead");?></h2>
				<p><?php echo $loc->label("ContactText");?></p>
			</div>
		</section>  



	
<!-- Javascript -->
	<script src="../Library/bootstrap-3.3.6//plugins/gmaps/prettify.js"></script>
	<script src="../Library/bootstrap-3.3.6/plugins/gmaps/gmaps.js"></script>
	<script>
	(function($) {
	"use strict";
		var map;
		$(document).ready(function(){
			prettyPrint();
			var map = new GMaps({
				div: '#map',
				scrollwheel: false,
				lat: -12.043333,
				lng: -77.028333
			});
			var marker = map.addMarker({
				lat: -12.042,
				lng: -77.028333
			});
		});
	})(jQuery);
	</script>

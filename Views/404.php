<?php

$loc = new localization($_SESSION['language']);

?>

		<section class="error-404" style="background-image: url(img/content/404.jpg); margin-bottom: 200px;">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<div class="title">
							<h4><i class="fa fa-warning"></i> <?php echo $loc->label("404Head");?></h4>
						</div>
						<p><?php echo $loc->label("404Text");?></p>
						<form>
							<div class="col-lg-8 pull-none display-inline-block">
							<div class="btn-inline">
	
							</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	

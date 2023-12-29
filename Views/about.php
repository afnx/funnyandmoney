<?php

$loc = new localization($_SESSION['language']);

?>	
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="post post-fl" style="margin-bottom: 500px;">
							<div class="post-header">
								<div class="post-title">
									<h2><?php echo $loc->label("AboutHead");?></h2>
								</div>
							</div>
							
							<?php echo $loc->label("AboutText");?>
							
						</div>
					</div>
				</div>		
			</div>
		</section>
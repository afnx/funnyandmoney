<?php

$loc = new localization ($_SESSION['language']); 

?>
		
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="post post-fl">
							<div class="post-header">
								<div class="post-title">
									<h1><?php echo $loc->label("TermsHead");?></h1>
								</div>
							</div>
							
							<?php echo $loc->label("TermsText");?>
							
						</div>
					</div>
				</div>		
			</div>
		</section>
	</div>


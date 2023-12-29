<?php


$loc = new localization($_SESSION['language']);


?>

<style>

.well:hover {
background-color: #2776dc !important;
}

</style>
	

	<section class="hero parallax" style="background-image: url(images/helpCover.jpg); height: 400px;">
	
		<div class="hero-bg"></div>
		
		<div class="container">
		
			<div class="page-header">
			
				<div class="page-title" style="font-size: 45px;"><?php echo $loc->label("Help Center");?></div>
				
				<ol class="breadcrumb" style="font-size: 30px;">
				
					<li><?php echo $loc->label("How can we help you?");?></li>
					
				</ol>	
				
			</div>
			
		</div>
		
	</section>
		
		
		
	<section>
	
		<div class="container">
		
	
			
			
				  
									
									
										<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="cursor: pointer;">
						<div class="well" style="text-align: center;" onclick="location.href='/ticket'"><a href="/ticket"><i style="font-size: 100px; color: orange;" class="glyphicon glyphicon-comment"></i><br/><h3><?php echo $loc->label("Sumbit a ticket");?><br/><span style="font-style: italic; color: orange;">(<?php echo $loc->label("FAST HELP");?>)</span></h3></a></div>
					</div>
								
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="cursor: pointer;">
						<div class="well" style="text-align: center;" onclick="location.href='ticket/knowledgebase.php'"><a href="/ticket/knowledgebase.php"><i style="font-size: 100px; color: orange;" class="glyphicon glyphicon-search"></i><br/><h3><?php echo $loc->label("View articles");?><br/><br/></h3></a></div>
					</div>
								
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="cursor: pointer;">
						<div class="well" style="text-align: center;" onclick="location.href='/contact'"><a href="/contact"><i style="font-size: 100px; color: orange;" class="glyphicon glyphicon-earphone"></i><br/><h3><?php echo $loc->label("HelpContact");?><br/><br/></h3></a></div>
					</div>
				</div>
				
				<h2 style="text-align: center; margin: 30px;"><?php echo $loc->label("FAQ");?><h2>


										<div class="panel-group" id="accordion">
										
											<div class="panel panel-default">
											
												<div class="panel-heading" id="headingOne">
												
													<h4 class="panel-title">
		
														<a href="#collapseOne" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq1");?>
															
														</a>
														
													</h4>			
													
												</div>
												
												<div id="collapseOne" class="panel-collapse collapse in">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText1");?>
														
													</div>
													
												</div>
												
											</div>
											
											<div class="panel panel-default">
											
												<div class="panel-heading" id="headingTwo">
												
													<h4 class="panel-title">
													
														<a href="#collapseTwo" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq2");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText2");?>
														
													</div>
													
												</div>
												
											</div>
											
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingThree">
												
													<h4 class="panel-title">
													
														<a href="#collapseThree" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq3");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseThree" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText3");?>
														
													</div>
													
												</div>
												
											</div>
											
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingFour">
												
													<h4 class="panel-title">
													
														<a href="#collapseFour" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq4");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseFour" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText4");?>
														
													</div>
													
												</div>
												
											</div>
											
<?php $a=5; if($a==4) { ?>
										
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingFive">
												
													<h4 class="panel-title">
													
														<a href="#collapseFive" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq5");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseFive" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText5");?>
														
													</div>
													
												</div>
												
											</div>
												
												<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingSix">
												
													<h4 class="panel-title">
													
														<a href="#collapseSix" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq6");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseSix" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText6");?>
														
													</div>
													
												</div>
												
											</div>
												
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingSeven">
												
													<h4 class="panel-title">
													
														<a href="#collapseSeven" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq7");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseSeven" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText7");?>
														
													</div>
													
												</div>
												
											</div>
											
											
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingEight">
												
													<h4 class="panel-title">
													
														<a href="#collapseEight" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq8");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseEight" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText8");?>
														
													</div>
													
												</div>
												
											</div>
											
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingNine">
												
													<h4 class="panel-title">
													
														<a href="#collapseNine" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq9");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseNine" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText9");?>
														
													</div>
													
												</div>
												
											</div>
											
											<div class="panel panel-default">
											
												<div class="panel-heading" role="tab" id="headingTen">
												
													<h4 class="panel-title">
													
														<a href="#collapseTen" class="collapsed" data-toggle="collapse" data-parent="#accordion" style="color: #2776dc; font-weight: bold; font-size: 20px;">
														
															<?php echo $loc->label("HelpFaq10");?>
															
														</a>
														
													</h4>
													
												</div>
												
												<div id="collapseTen" class="panel-collapse collapse">
												
													<div class="panel-body">
													
														<?php echo $loc->label("HelpFaqText10");?>
														
													</div>
													
												</div>
												
											</div>
												
<?php } ?>
											
										</div>

				
	

		</div>	
		
	</section>
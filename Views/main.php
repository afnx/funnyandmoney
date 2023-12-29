<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/platforms.php";
$loc = new localization ($_SESSION['language']);
?>
	<!-- wrapper -->   
	<div id="wrapper">	
		<div id="full-carousel" class="ken-burns carousel slide full-carousel carousel-fade" data-ride="carousel">
			<ol class="carousel-indicators">
			<a href="javascript: getStart();" class="btn btn-primary btn-lg btn-rounded"><?php echo $loc->label("HomeSlideButton");?></a>
			</ol>
			<div class="carousel-inner">
				<div class="item active inactiveUntilOnLoad">
					<img src="images/slideshow/1.jpg" alt="">
					<div class="container">
						<div class="carousel-caption">
							<h1 data-animation="animated animate1 bounceInDown"><?php echo $loc->label("HomeSlide1");?></h1>
							<p data-animation="animated animate7 fadeInUp"><?php echo $loc->label("HomeSlideDown1");?></p>
						</div>		
					</div>
				</div>
				
				<div class="item">
					<img src="images/slideshow/2.jpg" alt="">
					<div class="container">
						<div class="carousel-caption">
							<h1 data-animation="animated animate1 fadeInDown"><?php echo $loc->label("HomeSlide2");?></h1>
							<p data-animation="animated animate7 fadeIn"><?php echo $loc->label("HomeSlideDown2");?></p>
						</div>
					</div>
				</div>

			</div>	

		</div>
		
		<section id="getstarted" class="bg-grey-50 padding-top-60 padding-bottom-60 relative">	
		
			<div class="container">			
			
				<h4 class="page-header text-center no-padding no-border"><?php echo $loc->label("What is F&M?");?></h4>	
				
				<div class="well"><?php echo $loc->label("HomeWhatText");?></div>		
				
				<ul class="timeline margin-top-40">			
					
					<li>						
						
						<div class="timeline-badge primary"></div>
							
						<div class="timeline-panel">		
								
							<div class="timeline-heading">	
									
								<h4><?php echo $loc->label("HomeIntroHead1");?></h4>
										
								<img src="../Assets/images/user1.jpg" alt="" />  
										
							</div>						
									
							<div class="timeline-body">		
									
								<p><?php echo $loc->label("HomeIntroText1");?></p>
										
							</div>	
									
						</div>	
								
					</li>	
						
					<li>	
						
						<div class="timeline-badge primary"></div>		
							
						<div class="timeline-panel">		
							
							<div class="timeline-heading">	
								
								<h4><?php echo $loc->label("HomeIntroHead2");?></h4>
									
								<img src="../Assets/images/customer1.jpg" alt="" />   
									
							</div>					
		
							<div class="timeline-body">		
		
							<p><?php echo $loc->label("HomeIntroText2");?></p>
									
							</div>		
								
						</div>		
							
					</li>	
						
					<li>
					
						<div class="timeline-badge primary"></div>		
							
						<div class="timeline-panel">		
							
							<div class="timeline-heading">	
								
							<h4><?php echo $loc->label("HomeIntroHead3");?></h4>
									
								<img src="../Assets/images/user2.png" alt="" />  
									
							</div>	
								
							<div class="timeline-body">			
								
								<p><?php echo $loc->label("HomeIntroText3");?></p>
									
							</div>	
								
						</div>		
		
					</li>
		
					<li>	
		
						<div class="timeline-badge primary"></div>	
		
						<div class="timeline-panel">		
		
							<div class="timeline-heading">	
		
								<h4><?php echo $loc->label("HomeIntroHead4");?></h4>
		
								<img src="../Assets/images/customer2.png" alt="" />    
		
							</div>			
		
							<div class="timeline-body">		
		
								<p><?php echo $loc->label("HomeIntroText4");?></p> 
		
							</div>			
		
						</div>	
		
					</li>	
		
					<li class="clearfix" style="float: none;"></li>
		
				</ul>	
		
			</div>	
		
		</section>	
		
		
		<div class="row no-margin">	
			
			<div class="col-lg-12 no-padding">	
										
				<section class="bg-success subtitle-lg">	
				
				<h2><?php echo $loc->label("JoinText3");?></h2>
				<a href="bepublisher" class="btn btn-white btn-outline btn-icon-right"><?php echo $loc->label("Click Here");?><i class="glyphicon glyphicon-ok"></i></a>
										
				</section>	
										
			</div>	
										
		</div>	 
		

		<section class="padding-top-60 padding-bottom-40 bg-grey-50 border-bottom-1 border-grey-300">	 
		
			<div class="container">
			
			<div class="panel panel-default" style="margin-bottom: 60px;">
			<div class="panel-body">
				<div class="row">
	
					<div class="col-lg-2 col-md-2 col-sm-2 co-xs-2" style="text-align: center; margin-bottom: 15px;">
					
						<i style="font-size: 135px; color: #e0e0e0;" class="fa fa-question-circle"></i>  
					
					</div>
					
					<div class="col-lg-10 col-md-10 col-sm-10 co-xs-10">
					
					 <div class="row">
					<div class="col-lg-12">
						<h4 class="margin-bottom-15"><?php echo $loc->label("What can I buy with 100&?");?></h4>
						<p><?php echo $loc->label("WhatcanIbuyText");?></p> 
					</div>  
				</div>
					
					</div>
				
				</div>
			</div>  
		</div>
			
				<div class="row">
				
					<div class="col-md-8 col-md-offset-2">
					
						<div class="title outline">
						
							<h4><i class="fa fa-star"></i> <?php echo $loc->label("Recent Posts");?></h4>
							
							<p><?php echo $loc->label("HomeRecentPostText");?></p>
							
						</div>
						
					</div>
					
				</div>
				
				<div class="row slider">
				
					<div class="owl-carousel">
					<?php 
						$posts = new posts();
						$result = $posts->getPosts(0,10,1);
						while ($row=mysqli_fetch_array($result)) {
							$platform = new platforms($row["platformID"]);
							
					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}
					
					?>
						<div class="card card-list">
						
							<div class="card-img" style="height: 310px; background-color: #fafafa;">
							
								<img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo $row["title"];?>">
								
								
							</div>
							
							<div class="caption">
							
								<h4 class="card-title"><a href="login" style="color: #777; display:inline-block; width:150px; white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis;"><?php echo $row["title"];?></a></h4>	
								
								<p style=" display:inline-block; width:280px; white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis;"><?php echo $row["description"];?></p>
								
							</div>
							
						</div>
						<?php }?>
						
					</div>
					
					<a href="#" class="prev"><i class="fa fa-angle-left"></i></a>
					<a href="#" class="next"><i class="fa fa-angle-right"></i></a>
					
				</div>
	
			</div>
			
		</section>
		
		<div class="row no-margin">	
		
			<div class="col-lg-6 no-padding">		
			
				<section class="bg-danger subtitle">	
										
					<h2><?php echo $loc->label("JoinText1");?></h2>	
										
					<a href="signup" class="btn btn-inverse btn-icon-left"><i class="glyphicon glyphicon-ok"></i> <?php echo $loc->label("Click Here");?></a>
										
				</section>	
										
			</div>		
										
			<div class="col-lg-6 no-padding">		
										
				<section class="bg-primary subtitle" style="background-color: #2776dc !important;">	
										
					<h2><?php echo $loc->label("JoinText2");?></h2>		
										
					<a href="signup" class="btn btn-inverse btn-icon-left"><i class="glyphicon glyphicon-ok"></i> <?php echo $loc->label("Click Here");?></a>	
										
				</section>	
										
			</div>	

		</div>	
		
	</div>
	
	<!-- /#wrapper -->
	

	
	
	<script>
	(function($) {
	"use strict";
		var owl = $(".owl-carousel");
			 
		owl.owlCarousel({
			items : 4, //4 items above 1000px browser width
			itemsDesktop : [1000,3], //3 items between 1000px and 0
			itemsTablet: [600,1], //1 items between 600 and 0
			itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
		});
			 
		$(".next").click(function(){
			owl.trigger('owl.next');
			return false;
		})
		$(".prev").click(function(){
			owl.trigger('owl.prev');
			return false;
		})
	})(jQuery);

		function getStart() {
			var divPosition = $('#getstarted').offset();
			$('html, body').animate({scrollTop: divPosition.top}, "slow");

		}
	</script>
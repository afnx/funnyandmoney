<footer>
<div class="footer-bottom">
			<div class="container">	
			<?php 
			//Country Control
			if(isset($_SESSION["userID"])) {
			?>
			<?php $userCCont = new users($_SESSION["userID"]); ?>
			<?php if($userCCont->country == 306) {?>
			<ul class="list-inline">	
	
						<li><a href="javascript: changeLang('en');"><button type="button" class="btn btn-primary btn-outline"><?php echo $loc->label("LangEnglish");?></button></a></li>		
						<li><a href="javascript: changeLang('tr');"><button type="button" class="btn btn-primary btn-outline"><?php echo $loc->label("LangTurkish");?></button></a></li>	
	
			</ul>  
			<?php } ?>
			<?php } else { ?>
			<?php if($_SESSION["country"] == 'TR') {?>
			<ul class="list-inline">	
	
						<li><a href="javascript: changeLang('en');"><button type="button" class="btn btn-primary btn-outline"><?php echo $loc->label("LangEnglish");?></button></a></li>		
						<li><a href="javascript: changeLang('tr');"><button type="button" class="btn btn-primary btn-outline"><?php echo $loc->label("LangTurkish");?></button></a></li>	
	
			</ul>  
			<?php } ?>
			
			<?php } ?>
	
			<div class="container">

							<ul class="list-inline">
								<li><a href="about"><?php echo $loc->label("About");?></a></li>
								<li><a href="help"><?php echo $loc->label("Help");?></a></li>
								<li><a href="contact"><?php echo $loc->label("Contact");?></a></li>
								<li><a href="privacy"><?php echo $loc->label("Privacy");?></a></li>
								<li><a href="terms"><?php echo $loc->label("Terms");?></a></li>
								<li><a href="bepublisher"><?php echo $loc->label("Be Publisher");?></a></li>
							</ul>
				</div>
			
				<ul class="list-inline">
					<li><a href="http://twitter.com/funnynmoney" class="btn btn-circle btn-social-icon" data-toggle="tooltip" title="Follow us on Twitter"><i class="fa fa-twitter"></i></a></li>
					<li><a href="http://facebook.com/funnynmoney" class="btn btn-circle btn-social-icon" data-toggle="tooltip" title="Follow us on Facebook"><i class="fa fa-facebook"></i></a></li>
				</ul>
				
					<span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=Rp18Tyb5wZzBGhzVLdVVaFhdwCsq4goJt4TRfpljAxVBq4Wkvh2QijFGWV81"></script></span>
				<br/>
				<br/>
				<?php echo $loc->label("Copyright");?> Web v1.0.0 
			</div>
		</div>
</footer>
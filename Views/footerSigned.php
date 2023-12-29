<footer>
<div class="footer-bottom">
			<div class="container">	
	
			<div class="container">

							<ul class="list-inline">
							<?php 
							//Country Control
							if(isset($_SESSION["userID"])) {
							?>
							<?php $userCCont = new users($_SESSION["userID"]); ?>
							<?php if($userCCont->country == 306) {?>
								<li><a href="javascript: changeLang('en');"><?php echo $loc->label("English");?></a></li>		
								<li><a href="javascript: changeLang('tr');"><?php echo $loc->label("Turkish");?></a></li>
							<?php } ?>
							<?php } ?>
								<br/>
								<li><a href="about"><?php echo $loc->label("About");?></a></li>
								<li><a href="help"><?php echo $loc->label("Help");?></a></li>
								<li><a href="contact"><?php echo $loc->label("Contact");?></a></li>
								<li><a href="privacy"><?php echo $loc->label("Privacy");?></a></li>
								<li><a href="terms"><?php echo $loc->label("Terms");?></a></li>
								<li><a href="bepublisher"><?php echo $loc->label("Be Publisher");?></a></li>
							</ul>
			</div>
			
				<?php echo $loc->label("Copyright");?>  Web v1.0.0 
			</div>
		</div>
</footer>
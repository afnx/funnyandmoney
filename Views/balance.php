<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/currency.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/platforms.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}


$loc = new localization ($_SESSION['language']);
$currency = new currency(2);

$useridr = $_SESSION['userID'];

$balanceU = new balance();
$result = $balanceU->getBalanceHistory($useridr);


function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '&nbsp;...';
    echo $y;
  }
}


?>

		
		
<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300"><?php echo $loc->label("Balance");?></h2>

	</div>

</section>


		<section>
			<div class="container">
				<div class="row">
					<div class="col-md-12 leftside">
						<div class="post post-single">
							<div class="post-header post-author">


							<div class="panel panel-default panel-post">
							<div class="panel-body" style="background-color: #ddd;">
								<div class="post" style="text-align: center;"> 

								<h3><?php echo $loc->label("Now Your Balance");?><h3>
								<h2 style="color: orange;"><?php echo bcdiv($userBalance, 1, 2);?><?php echo $loc->label("Points");?></h2> 

								</div>
							</div>
							</div>



							</div>



							<div class="panel panel-default panel-post">
							<div class="panel-body">
								<div class="post">





		<section style="padding-top: 20px;">
	
				<h3><?php echo $loc->label("Buy Points");?></h3>
				<p><?php echo $loc->label("Get Likes,Followers,Subscribers... Now!");?></p>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
						<div class="widget">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("Buy Points");?></div>
								<div class="panel-body" style="padding: 20px;">
								<p><?php echo $loc->label("Buy points and keep your posts on the agenda!");?></p>
									<a href="buy"><button type="button" class="btn btn-success btn-icon-right"><?php echo $loc->label("Buy now");?><i class="fa fa-check-square-o"></i></button></a>
								</div>
							</div>
						</div>


					</div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;"> 


 
					<div class="widget">

					
					<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("Get offer for your business");?> <i
										class="fa fa-question-circle" data-toggle="tooltip"
										title="<?php echo $loc->label("Send a mail or open a ticket on Fast Help");?>"
										style="font-size: 18px; margin-left: 5px;"></i></div>
								<div class="panel-body" style="padding: 20px;">
								<p><?php echo $loc->label("Get a offer and keep your business on the agenda!");?></p>
									<a href="contact"><button type="button" class="btn btn-info btn-icon-right"><?php echo $loc->label("Contact us");?><i class="glyphicon glyphicon-envelope"></i></button></a>
								</div>
							</div>

						</div>



					</div>

				</div>



		</section>





			<div class="widget widget-list">
							<div class="panel panel-default">
								<div class="panel-heading bold"><?php echo $loc->label("History");?></div>
								<div class="panel-body" style="padding: 20px;">
									<ul id="balanceList" >

									<?php while ($row=mysqli_fetch_array($result)) {

									if($row["actionID"] == 1 || $row["actionID"] == 2 || $row["actionID"] == 3 || $row["actionID"] == 4) {
										
										$post = new posts($row["postID"]);
										$platform = new platforms($post->platformID);
									
										if($post->platformID == 4){
											$imgsrc= $post->imagePath;
										} else{
											$imgsrc= project::uploadPath.$post->imagePath;
										}
										
									


									?> 
										
			


										<li style="display: none;">
											<a href="<?php if($post->postType == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $post->ID;?>" class="thumb"><img src="<?php echo ($post->imagePath!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($post->title, 60);?>"></a>
											<div class="widget-list-meta">
												<h4 class="widget-list-title"><a href="<?php if($post->postType == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $post->ID;?>"><?php echo custom_echo($post->title, 60);?></a></h4>
												<p>
												<?php if($row["actionID"] == 1 || $row["actionID"] == 2 || $row["actionID"] == 3 || $row["actionID"] == 4) { echo $loc->label("Earned amount");?>: <?php echo bcdiv($row["point"], 1, 2); }?> &
												</p>
												<p><i class="fa fa-clock-o"></i> <?php echo $row["actiondate_"];?></p>
											</div>
										</li>
										
						
									
									<?php } else if($row["actionID"] == 6) {?>
									
										<li style="display: none;">
											<div class="thumb"><img src="../Assets/images/purchase.png" alt=""></div>
											<div class="widget-list-meta">
												<h4 class="widget-list-title" style="color: #27ae60;"><?php echo $loc->label("You purchased &!");?> </h4>
												<p><?php echo $loc->label("Purchased &");?>: <?php echo $row["point"];?> &  </p>
												<p><i class="fa fa-clock-o"></i> <?php echo $row["actiondate_"];?></p>    
											</div>
										</li>
									
									<?php } else if($row["actionID"] == 7) {?>
									
										<li style="display: none;">
											<div class="thumb"><img src="../Assets/images/gift.png" alt=""></div>
											<div class="widget-list-meta">
												<h4 class="widget-list-title" style="color: #e91e63;"><?php echo $loc->label("You received a gift!");?></h4>
												<p><?php echo $loc->label("You received &s as a gift");?>: <?php echo $row["point"];?> &  </p>
												<p><i class="fa fa-clock-o"></i> <?php echo $row["actiondate_"];?></p>
											</div>
										</li>
									
									<?php }?>

									<?php }?>

									<?php if (mysqli_num_rows( $result ) == 0) {?>

										<div style="text-align: center; padding: 100px;"> <h3> <?php echo $loc->label("NO ACTION HISTORY");?> </h3></div>

									<?php }?>


									</ul>

									<?php if (mysqli_num_rows( $result ) > 0) {?>

									<div class="text-center"><div class="btn btn-primary btn-lg btn-shadow btn-rounded btn-icon-right margin-top-10 margin-bottom-40" id="load"><?php echo $loc->label("Load More");?> </div></div>

									<?php }?>

								</div>
							</div>
						</div>




								</div>
							</div>
							</div>




						</div>


					</div>
				</div>
			</div>
		</section>
	</div>

	
	
				
	
	
	
	<script>

		function calculate() {
		var box1 = document.getElementById('cashPoint').value;
		var box2 = <?php echo $currency->monetaryValue;?>;
		var result = document.getElementById('moneyValue');
		var myResult = box1 * box2;
		result.value = myResult;

	}

	$(document).ready(function () {
	    size_li = $("#balanceList li").size();
	    x=5;
		if(size_li < 6) {
			$('#load').hide();
		}
	    $('#balanceList li:lt('+x+')').show();
	    $('#load').click(function () {
	        x= (x+5 <= size_li) ? x+5 : size_li;
	        $('#balanceList li:lt('+x+')').show();
	        if(x == size_li){
	            $('#load').hide();
	        }
	    })
	});
		


	</script>

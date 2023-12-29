<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/campaigns.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/campaignsHistory.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/IPlogs.php";

if (!isset($_SESSION['userID'])) {
	
	$url = trim($_SERVER["REQUEST_URI"], '/');
	
	$fn = new functions();
	$fn->redirect("login?pre=" . $url);
}

 

$loc = new localization ($_SESSION['language']); 
$obj = new objects ();
$userID = isset ( $_SESSION ["userID"] ) ? $_SESSION ["userID"] : 0;
$usTwitter = userSocials::getUserSocialFromID ( $userID, 2 );
if($usTwitter-> ID < 1){
	$twError='<a href="../Controllers/twitter.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;">'.$loc->label("Add a Twitter account").'</button></a>';
}





$campaign = new campaigns(1);
if(isset($campaign->ID)) {
$campaignh = new campaignsHistory();
$iplog = new IPlogs();
$ipresult = $iplog->getLastIPaddress($_SESSION["userID"]);
$rowip = mysqli_fetch_array($ipresult);
$address = $rowip[0];
$campaignCont = new campaignsHistory();
$campaignCont = campaignsHistory::checkUserCampaignWithIPaddress($campaign->ID,$campaign->startdate_,$campaign->duedate_,$address,$_SESSION["userID"]);

if($campaign->status == 1) {
	if($campaign->limit != NULL) {
		
		$result = $campaignh->countCampaign($campaign->ID,$campaign->startdate_,$campaign->duedate_);
		$rowcc = mysqli_fetch_array($result);
		$campCount = $rowcc[0];
		
		if($campCount == $campaign->limit or $campCount > $campaign->limit) {
			$campON = 0;
		} else {
			$campON = 1;
		}
	
	} else {
		
		if ((time() > $campaign->startdate_) && (time() < $campaign->duedate_)) {
			$campON = 1;
		} else {
			$campON = 0;  
		}
		
	}
	
} else {
	$campON = 0;
}

if($campaignCont->ID > 0) {
	
	$campON = 0;

}
} else {
	
	$campON = 0;
	
}

if($campON == 1) {

	if($campaign->lower != NULL) {
		$lower = $campaign->lower;
	} else {
		$lower = 0;
	}

}

?>

<head>


<link rel="stylesheet" href="../Library/bootstrap-3.3.6/css/chosen.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.min.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.css">
   
 

</head>

<style>
#loading {
    display: none;  
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
    width: 100vw;
    height: 100vh;
    background-image: url("../images/loader.gif");
    background-repeat: no-repeat;
    background-position: center;
}
</style>


<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300"><?php echo $loc->label("Add New Post");?></h2>

	</div>

</section>

<section style="display: block;" class="padding-top-50 padding-bottom-50 padding-top-sm-30">


	<div id="addpost-panel" class="container">

<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<center><h3><?php echo $loc->label("Choose somethingPCS");?></h3>  
						<p><?php echo $loc->label("ChoosePostGenreText");?></p></center>
						<ul class="nav nav-tabs" style="
    text-align: center;
">
							<li class="active" style="display: inline-block; float: none;"><a style="font-size: 18px;" href="#itab1" data-toggle="tab" aria-expanded="true"><i class="fa fa-globe" style="color: #2196f3;"></i><?php echo $loc->label("Massive Posts");?> </a></li>
							<li style="display: inline-block; float: none;"><a style="font-size: 18px;" href="addpublisherpost" aria-expanded="false"><i class="fa fa-group" style="color: #ffc107;"></i> <?php echo $loc->label("Specific Posts(Publisher)");?></a></li>
							
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane fade in active">



		<form id="formAddPost" name="formAddPost" onsubmit="submitAddPost(); return false;" method="post"
			action="/Controllers/formPosts.php?action=saveForm"> 
			<input type="hidden" name="tableName" value="posts" /> 
				<input type="hidden" name="postType" value=""/>
				<input id="socialID" type="hidden" name="socialID" value=""/>
				<input type="hidden" name="platformID" value=""/>
				
				
				
								
							
							
							
						
				
				

			<?php if($campON == 1) { ?>
			
			<?php if($campaign->locLabelT != NULL && $campaign->locLabel != NULL ) { ?>
			
				
				<div class="row margin-bottom-30">
				<div class="col-lg-12">
				<div class="alert alert-success alert-lg fade in margin-top-30">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
					<center><i class="fa fa-gift" style="font-size: 100px;color: #e91e63;"></i></center>
					</div>
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
					<h4 class="alert-title"><?php echo $loc->label($campaign->locLabelT); ?></h4>
					<p><?php echo $loc->label($campaign->locLabel); ?></p>
				<?php if($campaign->limit != NULL) { ?>
					<p><?php echo $loc->label("campaignLastCountText") . ": <strong>" . ($campaign->limit-$campCount) . "</strong>"; ?></p>
				<?php } ?>
					</div>
					</div>
				</div>  
				</div>
				</div>
				
			<?php } ?>
			
			<?php } ?>

		

				<div class="panel panel-default panel-post">

					<div class="panel-body">

						<div class="post">

							<div class="form-group" style="position: relative;">

								<h4><?php echo $loc->label("Platform");?><i
										class="fa fa-question-circle" data-toggle="tooltip"
										title="<?php echo $loc->label("Select your platform where your post");?>"
										style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
								</h4>  

							</div>

							<div class="form-group">

								<select id="modalSelectMain" name="platformIDr" class="chosen-select"
									onclick="craateUserJsObject.modalSelectMain();">

									<option id="select" value="select" selected disabled><?php echo $loc->label("Select Platform");?></option>

									<option id="facebookOpt" value="1"> <?php echo $loc->label("Facebook");?></option>

									<option id="twitterOpt" value="2"> <?php echo $loc->label("Twitter");?></option>

									<option id="youtubeOpt" value="4" > <?php echo $loc->label("Youtube");?></option>

								</select>

							</div>


							<div class="selectFacebook" style="display: none">

								<div class="form-group" style="position: relative;">

									<h4><?php echo $loc->label("Post Type");?><i class="fa fa-question-circle"
											data-toggle="tooltip"
											title="<?php echo $loc->label("Select post type in order to indicate whether your post is page/channel or post/tweet/video");?>"
											style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
									</h4>

								</div>

								<div class="form-group">

									<select id="modalSelectType1" name="postTypefacebook" class="chosen-select-no-single"
										onclick="craateUserJsObject.modalSelectType1();">

										<option id="select1" value="" selected disabled><?php echo $loc->label("Select Post Type");?></option>

										<option id="facepageOpt" value="1"> <?php echo $loc->label("Page");?></option>

										<option id="facepostOpt" value="2"> <?php echo $loc->label("Post");?></option>

									</select>

								</div>

							</div>

							<div class="selectTwitter" style="display: none">

								<div class="form-group" style="position: relative;">

									<h4><?php echo $loc->label("Post Type");?><i class="fa fa-question-circle"
											data-toggle="tooltip"
											title="<?php echo $loc->label("Select post type in order to indicate whether your post is page/channel or post/tweet/video");?>"
											style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
									</h4>

								</div>

								<div class="form-group">

									<select <?php if(isset($twError)){ echo 'disabled'; } ?> id="modalSelectType2" name="postTypetwitter" class="chosen-select"
										onclick="craateUserJsObject.modalSelectType2();">
  
										<option id="select2" value="" selected disabled><?php echo $loc->label("Select Post Type");?></option>

										<option id="tweetterpageOpt" value="1"> <?php echo $loc->label("Page");?></option>

										<option id="tweetOpt" value="2"> <?php echo $loc->label("Tweet");?></option>

									</select>

								</div>
								  
								<div id="urlPaget">

									<div class="form-group" style="position: relative;">

										<h4><?php echo $loc->label("URL");?>
										</h4>

										<p><?php echo $loc->label("AddPostUrlHelp");?>
									
									
										<p>
								
									</div>

									<div class="form-group">
										<?php if(isset($twError)){
											echo $twError;
										}else{
										?>
										<input id="postUrlTwitter" type="url" name="postUrlTwitter" class="form-control"
											value="http://www.twitter.com/<?=$usTwitter->screenName?>" disabled >
										<?php } ?>
									</div>
								
								</div>

							</div>


							<div class="selectYoutube" style="display: none">

								<div class="form-group" style="position: relative;">

									<h4><?php echo $loc->label("Post Type");?><i class="fa fa-question-circle"
											data-toggle="tooltip"
											title="<?php echo $loc->label("Select post type in order to indicate whether your post is page/channel or post/tweet/video");?>"
											style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
									</h4>

								</div>

								<div class="form-group">

									<select id="modalSelectType4" name="postTypeyoutube" class="chosen-select-no-single"
										onclick="craateUserJsObject.modalSelectType4();">

										<option id="select3" value="" selected disabled><?php echo $loc->label("Select Post Type");?></option>

										<option id="youtubechannelOpt" value="3"> <?php echo $loc->label("Channel");?></option>

										<option id="youtubevideoOpt" value="4"> <?php echo $loc->label("Video");?></option>

									</select>

								</div>

								<div class="form-group" style="position: relative;">

									<h4><?php echo $loc->label("URL");?>
									</h4>

									<p><?php echo $loc->label("AddPostUrlHelp");?>
									
									
									<p>
								
								</div>

								<div class="form-group">

									<input id="postUrlYoutube" type="url" name="postUrlYoutube" class="form-control"
										placeholder="www.youtube.com/example">

								</div>

							</div>

						</div>

					</div>
				</div>







				<div class="allAddPost" style="display: none">
				
				
				<div id="smartListL" class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">
								
								
					<div class="panel-heading"
									style="background-color: #F6F7F8; padding-bottom: 0px;">

									<h3><?php echo $loc->label("Select your post/tweet/video or page/channel");?> </h3>

									<p style="margin-bottom: 5px;"><?php echo $loc->label("You can select content that you want to publish from your social media account");?>
									
	
									<p>
								
								</div>
								
								<div class="panel-body">

									<div class="post">

										<div class="form-group" style="position: relative;">
										
										<button id="smartListButton" style="display: none; margin-bottom: 10px;" type="button" class="btn btn-info"><?php echo $loc->label("Back");?></button>
				
				<div id="smartList" class="comments" style="height: 300px; overflow: auto; border: 2px; margin-top: 0px;"> 
				

				</div>
				
				</div>
				</div>
				</div>
				
				</div>

						

							

							<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">


								<div class="panel-heading"
									style="background-color: #F6F7F8; padding-bottom: 0px;">

									<h3><?php echo $loc->label("Post Preferences");?> </h3>

									<p style="margin-bottom: 5px;"><?php echo $loc->label("AddPostBigHeadText1");?>
									
									
									<p>
								
								</div>


								<div class="panel-body">

									<div class="post">

										<div class="form-group" style="position: relative;">

											<h4>
												<?php echo $loc->label("Category");?><i class="fa fa-question-circle" data-toggle="tooltip"
													title="<?php echo $loc->label("Select a category that explain your post most appropriately");?>"
													style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
											</h4>

										</div>


										<div class="form-group">
									
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=12 and isDeleted<>1", "categoryID", 0);?>	
		
									
								</div>


									</div>
								</div>
							</div>


							<p></p>



							<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">


								<div class="panel-heading"
									style="background-color: #F6F7F8; padding-bottom: 0px;">

									<h3><?php echo $loc->label("Actions");?></h3>

									<p><?php echo $loc->label("Tick up actions that you want to implement for your post. You can tick up more than one.");?>
									
									
									<p>
								
								</div>

								<div class="panel-body">
									<div class="post">







										<div class="checkFacebookPage" style="display: none;">



											<div class="row">

												<div class="form-group">

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkfacebookpage1"> <label
															for="checkfacebookpage1"><?php echo $loc->label("Like");?></label>

													</div>

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkfacebookpage2"> <label
															for="checkfacebookpage2"><?php echo $loc->label("Share");?></label>

													</div>

												</div>


											</div>


										</div>


										<div class="checkFacebookPost" style="display: none;">

											<div class="form-group">


												<div class="row">

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkfacebookpost1"> <label
															for="checkfacebookpost1"><?php echo $loc->label("Like");?></label>

													</div>

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkfacebookpost2"> <label
															for="checkfacebookpost2"><?php echo $loc->label("Share");?></label>

													</div>

												</div>


											</div>


										</div>



										<div class="checkTwitterPage" style="display: none;">

											<div class="form-group">


												<div class="row">

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checktwitterpage1"> <label
															for="checktwitterpage1"><?php echo $loc->label("Follow");?></label>

													</div>

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checktwitterpage2"> <label
															for="checktwitterpage2"><?php echo $loc->label("Share");?></label>

													</div>

												</div>


											</div>


										</div>


										<div class="checkTwitterPost" style="display: none;">

											<div class="form-group">


												<div class="row">

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checktwitterpost1"> <label
															for="checktwitterpost1"><?php echo $loc->label("Like");?></label>

													</div>

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checktwitterpost2"> <label
															for="checktwitterpost2"><?php echo $loc->label("Share");?></label>

													</div>

												</div>


											</div>


										</div>

										<div class="checkYoutubeChannel" style="display: none;">

											<div class="form-group">


												<div class="row">

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkyoutubechannel1"> <label
															for="checkyoutubechannel1"><?php echo $loc->label("Subscribe");?></label>

													</div>

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkyoutubechannel2"> <label
															for="checkyoutubechannel2"><?php echo $loc->label("Share");?></label>

													</div>

												</div>


											</div>


										</div>


										<div class="checkYoutubeVideo" style="display: none;">

											<div class="form-group">


												<div class="row">

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkyoutubevideo1"> <label
															for="checkyoutubevideo1"><?php echo $loc->label("View");?></label>

													</div>
													
													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkyoutubevideo3"> <label
															for="checkyoutubevideo3"><?php echo $loc->label("Like");?></label>

													</div>

													<div
														class="checkbox checkbox-control checkbox-inline checkbox-success">

														<input type="checkbox" id="checkyoutubevideo2"> <label
															for="checkyoutubevideo2"><?php echo $loc->label("Share");?></label>

													</div>


												</div>


											</div>


										</div>


									</div>
								</div>
							</div>


							<p></p>


							<div class="getPanel" style="display: none;">

								<div id="style" class="panel panel-default panel-post">
									<div class="panel-body">
										<div class="post">


											<div class="likePageShow" style="display: none;">

												<div class="form-group">

													<h3><?php echo $loc->label("Like");?></h3>

												</div>

												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of likes");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Entry count the likes you want to reach");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>

												<div class="form-group">


													<input id="likePageCount" type="number" min="0" max="99999999999" class="form-control" name="numberFollowFacebook"
														placeholder="<?php echo $loc->label("Number of likes");?>">


												</div>

											</div>


											<div class="followShow" style="display: none;">

												<div class="form-group">

													<h3><?php echo $loc->label("Follow");?></h3>

												</div>

												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of followers");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Entry count the followers you want to reach");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>


												<div class="form-group">


													<input id="followerCount" type="number" min="0" max="99999999999" class="form-control" name="numberFollowTwitter"
														placeholder="<?php echo $loc->label("Number of followers");?>">


												</div>

											</div>




											<div class="subscribeShow" style="display: none;">

												<div class="form-group">

													<h3><?php echo $loc->label("Subscribe");?></h3>

												</div>


												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of subscribers");?><i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Entry count the subscribers you want to reach");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>


												<div class="form-group">


													<input id="subscribeCount" type="number" min="0" max="99999999999" class="form-control" name="numberFollowYoutube"
														placeholder="<?php echo $loc->label("Number of subscribers");?>">


												</div>


											</div>


											<div class="viewShow" style="display: none;">

												<div class="form-group">

													<h3><?php echo $loc->label("View");?></h3>

												</div>


												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of views");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Entry count the views you want to reach");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>


												<div class="form-group">


													<input id="viewCount" type="number" min="0" max="99999999999" class="form-control" name="numberView"
														placeholder="<?php echo $loc->label("Number of views for your video");?>">


												</div>


											</div>


											<div class="likeShow" style="display: none;">

												<div class="form-group">

													<h3><?php echo $loc->label("Like");?></h3>

												</div>

												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of likes");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Entry count the likes you want to reach");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>

												<div class="form-group">


													<input id="likeCount" type="number" min="0" max="99999999999" class="form-control" name="numberLike"
														placeholder="<?php echo $loc->label("Number of likes");?>">


												</div>

											</div>


											<div class="shareShow" style="display: none;">

												<div class="form-group">

													<h3><?php echo $loc->label("Share");?></h3>

												</div>

												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of people who share");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Entry count the shares you want to reach");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>

												<div class="form-group">


													<input id="shareCount" type="number" min="0" max="99999999999" class="form-control" name="numberShare"
														placeholder="<?php echo $loc->label("Number of people who share");?>">


												</div>
							

												<div class="form-group">

													<h4>
														<?php echo $loc->label("Number of followers owned by one person");?> <i
															class="fa fa-question-circle" data-toggle="tooltip"
															title="<?php echo $loc->label("Select a followers range in order to");?>"
															style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
													</h4>

												</div>

												<div class="form-group">
								<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=9 and isDeleted<>1", "shareSelectFollowers", 0);?>		
										
							</div>

												<!--
							<div class="form-group">
								
								<h4>Platforms for shares <i class="fa fa-question-circle" data-toggle="tooltip" title="" style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i></h4>
								
								</div>
								
								<div class="form-group">
										
								<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=8 and isDeleted<>1", "platform", 0, true);?>
										
							</div>	
							
							-->




											</div>

										</div>
									</div>
								</div>

							</div>

							<p></p>


 
							<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">


								<div class="panel-heading"
									style="background-color: #F6F7F8; padding-bottom: 0px;">

									<h3><?php echo $loc->label("Target Group");?></h3>

									<p><?php echo $loc->label("You can show your post to people who comply with your criterias");?>
									
									
									<p>
								
								</div>


								<div class="panel-body">
									<div class="post">




										<div class="form-group" style="position: relative;">

											<h4>
												<?php echo $loc->label("Country");?><i class="fa fa-question-circle" data-toggle="tooltip"
													title="<?php echo $loc->label("Peoples country who see your post");?>"
													style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
											</h4>

										</div>


										<div class="form-group">
									
																		
									<?php
									//Country Control
									$userCCont = new users($_SESSION["userID"]);
									if($userCCont->country == 306) {

										echo "<b>". $loc->label("Turkey") ."</b>" . " - <i class='fa fa-info-circle' style='margin-right: 3px; margin-left: 5px;'></i>Şuanda gönderiler sadece Türkiye için eklenebilir. Diğer ülkeler için gönderi açmak istiyorsanız
										<a href='help'>Yardım Merkezi</a>'den bize ulaşın.";  
									
									} else { 
										
										echo $obj->dropDownFill("select ID,definition from definitions where definitionID=13 and isDeleted<>1", "country[]", 0, true);
										
									}?>
		
									 
								</div>


										<div class="form-group" style="position: relative;">

											<h4>
												<?php echo $loc->label("Gender");?><i class="fa fa-question-circle" data-toggle="tooltip"
													title="<?php echo $loc->label("Peoples gender who see your post");?>"
													style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
											</h4>

										</div>


										<div class="form-group">
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=1 and isDeleted<>1", "gender[]", 0, true);?>
									
								</div>


										<div class="form-group" style="position: relative;">

											<h4>
												<?php echo $loc->label("Age");?><i class="fa fa-question-circle" data-toggle="tooltip"
													title="<?php echo $loc->label("Peoples age who see your post");?>"
													style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i>
											</h4>

										</div>


										<div class="form-group">
									
									
									 <?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=6 and isDeleted<>1", "age[]", 0, true);?>
		
									
								</div>

									</div>
								</div>
							</div>


							<p></p>


							<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">

								<div class="panel-heading"
									style="background-color: #F6F7F8; padding-bottom: 0px;">

									<h3><?php echo $loc->label("Position");?></h3>
									<span style="font-style: italic; color: #03a9f4;"><?php echo $loc->label("1 weeks for paid positions");?></span>  

									<p><?php echo $loc->label("Where you want posts appears?");?>
									
									
									<p>
								
								</div>

								<div class="panel-body">
									<div class="post">







										<div id="position-radio" class="form-group">

									<div class="row" style="margin: 20px;">
										<div class="radio radio-control radio-primary">
							<?php echo $obj->radioPositionFill("select ID,definition from definitions where definitionID=18 and isDeleted<>1", "radioPositionInline", 0);?> <img src="images/position/vipfield.jpg" />
							</div>
							</div>
						<div class="videoRadio" style="display: none;">
						<div class="row" style="margin: 20px;">
						<div class="radio radio-control radio-primary">
						
						<?php echo $obj->radioPositionFill("select ID,definition from definitions where definitionID=19 and isDeleted<>1", "radioPositionInline", 0);?> <img src="images/position/topvfield.jpg" />
						
							</div>
							</div>
						</div>
						<div class="row" style="margin: 20px;">
						<div class="radio radio-control radio-primary">
							
							<?php echo $obj->radioPositionFill("select ID,definition from definitions where definitionID=20 and isDeleted<>1", "radioPositionInline", 0);?> <img src="images/position/topfield.jpg" />
							</div>
							</div>
							<div class="row" style="margin: 20px;">
						<div class="radio radio-control radio-primary">  
						<?php echo $obj->radioPositionFill("select ID,definition from definitions where definitionID=21 and isDeleted<>1", "radioPositionInline", 0);?> <img src="images/position/stfield.jpg" />
						
						
						</div>
						</div>
					
							
										</div>



									</div>

								</div>
							</div>
							
							<div class="row">
							
							<div class="col-lg-6 col-md-6 col-sm-6 co-xs-12">
							
							<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">

								<div class="panel-heading"
									style="background-color: #F6F7F8;">

									<h3><?php echo $loc->label("Estimated Reach");?><i
										class="fa fa-question-circle" data-toggle="tooltip"
										title="<?php echo $loc->label("People who see your post");?>"
										style="font-size: 18px; margin-left: 5px; cursor: pointer;"></i></h3>
								
								</div>

								<div class="panel-body">
								
								<div class="post">
								
									<div class="form-group" style="text-align: center;">

									<h2 id="totalReach">
										0
										</h2>
										
									</div>
								</div>
								
								</div>
								
							</div>
							</div>
							
							<div class="col-lg-6 col-md-6 col-sm-6 co-xs-12">
							
							<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">

								<div class="panel-heading"
									style="background-color: #F6F7F8;">

									<h3><?php echo $loc->label("Total Point");?></h3>
								
								</div>

								<div class="panel-body">
								
								<div class="post">
								
									<div class="form-group" style="text-align: center;">

									<h2 id="totalPoint">
										0
										</h2>
										
									</div>
								</div>
								
								</div>
								
							</div>
							</div>
							
						
							</div>

							<a id="addPost" href="javascript: submitAddPost();"><button type="submit" class="btn btn-block btn-primary"><?php echo $loc->label("Add");?></button></a>
							
							
							<div id="modal" class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $loc->label("Error");?></h4>
							</div>
							<div class="modal-body">
								
								<p id="modalText" style="text-align: center;"></p>			
								
							</div>
							<div class="modal-footer">

								<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>

							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				
				
				<input id="modalButton" type="hidden" data-toggle="modal" data-target=".bs-modal">



					

				

				</div>
	
		</form>

	</div> 
	

						</div>
					</div>
				</div>
				
					</div>
					
						<div id="success-panel" style="display: none;">
	
	<div class="container">
	
	
	
	<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">
								

								
								<div class="panel-body">

									<div class="post">

										<div class="form-group" style="position: relative;">
				
										
											<h3><?php echo $loc->label("Your post added!");?> </h3>

									<p style="margin-bottom: 5px;"><?php echo $loc->label("You can check on My posts");?></p>
			
				
				</div>
				</div>
				</div>
				
				</div>
	

	</div>
	

	
<input type="hidden" id="realTotalPoint" value="0" />  
<input type="hidden" id="radioCheckValue" value="65" />
	


	</div>
 
				</section>
<div id="loading"></div>

<!-- Javascript -->

<script src="../Library/bootstrap-3.3.6/plugins/jquery/chosen.jquery.js"
	type="text/javascript"></script>
	
<script>
//puan hesaplama fonksiyonuna parametre gönder
$(document).ready(function () {
$("input[name=radioPositionInline]:radio,#likeCount,#followerCount,#shareCount,#shareSelectFollowers,#viewCount,#subscribeCount,#likePageCount,input[name='platformID']").change(function () {

document.getElementById("totalReach").innerHTML = "<?php echo $loc->label("calculating...");?>";
document.getElementById("totalPoint").innerHTML = "<?php echo $loc->label("calculating...");?>";
	
if($("input[name=radioPositionInline]:radio:checked").val() != "") {
	
	document.getElementById("radioCheckValue").value = $("input[name=radioPositionInline]:radio:checked").val();
	
} else {
	
	$("#radioCheckValue").val(65);
	
}

var item_follow;

if($("#likePageCount").val() != "") {
	
	item_follow = $("#likePageCount").val();
	
} else if($("#followerCount").val() != "") {
	
	item_follow = $("#followerCount").val();    
	
} else if($("#subscribeCount").val() != "") {
	
	item_follow = $("#subscribeCount").val();
	
} else {
	item_follow=0;
}

var item_like = $("#likeCount").val();
var item_share = $("#shareCount").val();
var item_shareFollower = $("#shareSelectFollowers").val();
var item_view = $("#viewCount").val();
var item_platform = $($("input[name='platformID']")).val();

var shareR = 0;
switch(Math.round(item_shareFollower)) {
	case 30:
		shareR = 200;
		break;
	case 31:
		shareR = 400;
		break;
	case 32:
		shareR = 750;
		break;
	case 33:
		shareR = 2000;
		break;
	case 34:
		shareR = 4000;
		break;
	case 35:
		shareR = 7500;
		break;
}

var shareTot = Math.round((item_share !== '' ? item_share : 0) * shareR);
var reach = 0;
reach = Math.round(item_like !== '' ? item_like : 0) + Math.round(item_follow !== '' ? item_follow : 0) + Math.round(item_view !== '' ? item_view : 0) + shareTot; 

document.getElementById("totalReach").innerHTML = kFormatter(reach);

jQuery.ajax({
    type: "POST",
    url: '../BL/functions.php',
    dataType: 'json',
    data: {functionname: 'calcAddPost', position: $("#radioCheckValue").val(), like: item_like, follow: item_follow, share: item_share, shareFollower: item_shareFollower, view: item_view, refunc: 0, platform: item_platform},

    success: function (e) {
	<?php if($campON == 1) { ?>
		
		var max = <?php echo $campaign->quantity; ?>;
		var lower = <?php echo $lower; ?>;
		
		if(lower > e) {
			
			document.getElementById("totalPoint").innerHTML = e;
			document.getElementById("realTotalPoint").value = e;
		
		} else {
			
			if(e > max) {
			
				document.getElementById("totalPoint").innerHTML = (e-max).toFixed(2) + " <b style='color: #4caf50;'> + " + max + " <?php echo $loc->label("FREE"); ?>" + "</b>";
				document.getElementById("realTotalPoint").value = (e-max).toFixed(2);
			
			} else {
			
				document.getElementById("totalPoint").innerHTML = " <b style='color: #4caf50;'> " + e + " <?php echo $loc->label("FREE"); ?>" + "</b>"; 
				document.getElementById("realTotalPoint").value = 0;
			
			}
			
		}
		
		
		
       
		
	<?php } else {?>
		
		document.getElementById("totalPoint").innerHTML = e;
		document.getElementById("realTotalPoint").value = e;
		
	<?php } ?>
            }
});
})
});

function kFormatter(num) {
    if(1000000 > num && num > 999) {
		return parseFloat((num/1000).toFixed(1)) + 'K';
	} else if(1000000000 > num && num > 999999) {
		return parseFloat((num/1000000).toFixed(1)) + 'M';
	} else if(1000000000000> num && num > 999999999) {
		return parseFloat((num/1000000000).toFixed(1)) + 'B'; 
	} else if(num > 999999999999) {
		return "-";
	} else {
		return num;
	}
}


$(document).ready(function () {
	

	
	
	$("#country\\[\\],#gender\\[\\],#age\\[\\]").change(function () {
		
	var selectsoptions = [
	
		"1",
		"17",
		"55"

	];
		if($(this).val() == null){
			var ptimes= 0;
		}else{
			var ptimes= $(this).val().length;
		}
		for (i = 0; i < selectsoptions.length; i++) { 
			for (p = 0; p < ptimes; p++) { 
				if($(this).val()[p] == selectsoptions[i])	{
					$(this).val(selectsoptions[i]);
				}
			}
		}
	
		// If all options selected, this val = all gender,age,country.
		console.log("Değer: " + ptimes);
		var atimes = $(this).children('option').length - 2;
		console.log("Toplam: " + atimes);
		if(atimes == ptimes) {
			var okay = 0;
			for (i = 0; i < selectsoptions.length; i++) {
				$(this).children('option').each(function(){
					if($(this).val() == selectsoptions[i])	{  
						okay = selectsoptions[i];
					}
				 });
			}
			$(this).val(okay);
		}
		
		$(this).trigger("chosen:updated");
	
		
		
	
	});
	
});
/*
var op = document.getElementById(this.id);
	
		for (i = 0; i < selectsoptions.length; i++) { 
		
			if($(this).val() == selectsoptions[i])	{
		
				for (x = 0; x < op.length; x++) { 
			
					if (op.options[i].value != selectsoptions[i]) {
					
						op.options[i].disabled = true;
					
					} else { 
				
						op.options[i].disabled = false; 
				
					}
				
				}
			}
			
		}
*/
	
	
	
	
$(document).keypress(function(e) {
		if(e.which == 13) {
				submitAddPost();
		}
	});

	function submitAddPost(){
		
		$('#addpost-panel').hide();
		$('#loading').show();
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=saveForm",
			data: $("#formAddPost").serialize(),
			success: function cevap(e){
				
				
				$('#loading').hide();
				$('#addpost-panel').show();
				if(e.match(/[a-z]/i) && !(e.indexOf("ok") > -1)){
					
					document.getElementById("modalText").innerHTML = e;
					document.getElementById("modalButton").click(); 
					
				}else if(e.indexOf("ok") > -1){
					if($("#realTotalPoint").val() != 0) {
						var now;
						getBalance(function(output){
							now = output;  
						});
						$("#newPoints").text('-'+$("#realTotalPoint").val()).css("display", "inline");
						$("#newPoints").removeClass("label-success").addClass("label-danger");
						$("#currentPoints").fadeOut(500, function(){$("#currentPoints").text(now)}).fadeIn(1500, function(){$("#newPoints").css("display", "none");});
					}
					$('#addpost-panel').hide();
					$('#success-panel').show();   
				}

			}
			}) 
		}
	
	
	$( "#smartListButton" ).click(function() {
		
		$('#addpost-panel').hide();
		$('#loading').show();
		
		$.post("/BL/socials.php?run=pagesAndMe",{},function(data){
			
			$('#loading').hide();
			$('#addpost-panel').show();
			$('#smartListButton').hide();
			$('#smartList').html(data);
		});
		
	});

</script>
	
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

<script>

var selects = [
	
	".allAddPost",
	".selectFacebook",
	".selectTwitter",
	".selectYoutube",
	".getPanel",
	".videoRadio",
	"#smartListL"

];  

function hideAll()

{
	
   for (i = 0; i < selects.length; i++) { 
			
			$(selects[i]).hide();
			
		}
		
		document.getElementById("formAddPost").reset();
	 
}

var modalSelectMain = jQuery('#modalSelectMain');

var select = this.value;

var i;

modalSelectMain.change(function () {
	
	$($("input[name='postType']")).val('');
	
    if ($(this).val() == '1') {
    	$("#facebookOpt").attr('selected','selected');
		
		hideAll();
		hideAll2();
		hideAll3();
		
        $('.selectFacebook').show();
		$($("input[name='platformID']")).val('1');
		
    }
	
    else if ($(this).val() == '2') {
    	$("#twitterOpt").attr('selected','selected');
		
		hideAll();
		hideAll2();
		hideAll3();
		  
        $('.selectTwitter').show();
		$($("input[name='platformID']")).val('2');
		
    }
	
	else if ($(this).val() == '4') {
		$("#youtubeOpt").attr('selected','selected');
		hideAll();
		hideAll2();
		hideAll3();
		
		$('.selectYoutube').show();
		$($("input[name='platformID']")).val('4');
		
		
    }
    
    else {
		
		hideAll();
		hideAll2();
		hideAll3();
	 
    }
    
});

</script>

<script>

var checks = [

			".allAddPost",
			".checkFacebookPage",
			".checkFacebookPost",
			".checkTwitterPage",
			".checkTwitterPost",
			".checkYoutubeChannel",
			".checkYoutubeVideo",
			".getPanel",
			".videoRadio"

];



function hideAll2()

{
	
   for (i = 0; i < checks.length; i++) { 
			
			$(checks[i]).hide();
			
		}
		
		document.getElementById("formAddPost").reset();
	 
}



var modalSelectType1 = jQuery('#modalSelectType1');

var select = this.value;

modalSelectType1.change(function () {

    if ($(this).val() == '1') {
		
		$('#addpost-panel').hide();
		$('#loading').show();
		$('#smartListL').show(); 
		
		$.post("/BL/socials.php?run=pages",{},function(data){
			
			$('#loading').hide();
			$('#addpost-panel').show();
			$('#smartListButton').hide();
			$('#smartList').html(data);
		});
    	
    	
		hideAll2();
		hideAll3();
		
		$('.allAddPost').show();
		$('.checkFacebookPage').show();
		$($("input[name='postType']")).val('1');
    }
	
    else if ($(this).val() == '2') {
		
		$('#addpost-panel').hide();
		$('#loading').show();
		$('#smartListL').show(); 
		
		
		
		$.post("/BL/socials.php?run=pagesAndMe",{},function(data){
			
			$('#loading').hide();
			$('#addpost-panel').show();
			$('#smartList').html(data);
		});
		
		
		
		hideAll2();
		hideAll3();
		$('.allAddPost').show();
		$('.checkFacebookPost').show();
		$($("input[name='postType']")).val('2');
    }
    
    else {
		
		hideAll2();
		hideAll3();
		
    }
    
});


var modalSelectType2 = jQuery('#modalSelectType2');

var select = this.value;

modalSelectType2.change(function () {
	
    if ($(this).val() == '1') {
		
		$('#smartListL').hide();  
		
		hideAll2();
		hideAll3();
		
		$('.allAddPost').show();
		$("#urlPaget").show();
		$('.checkTwitterPage').show();
		$($("input[name='postType']")).val('1');
		
    }
	
    else if ($(this).val() == '2') {
		
		
		$('#addpost-panel').hide();
		$('#loading').show();
		$('#smartListL').show(); 
		
		$.post("/BL/socials.php?run=tweets",{},function(data){
			
			$('#loading').hide();
			$('#addpost-panel').show();
			$('#smartListButton').hide();
			$('#smartList').html(data);
		});
		hideAll2();
		hideAll3();
		
		$('.allAddPost').show();
		$("#urlPaget").hide();
		$('.checkTwitterPost').show();
		$($("input[name='postType']")).val('2');
		
    }
    
    else {
		
		hideAll2();
		hideAll3();
		
    }
    
});


var modalSelectType4 = jQuery('#modalSelectType4');

var select = this.value;

modalSelectType4.change(function () {
	
    if ($(this).val() == '3') {
		
		hideAll2();
		hideAll3();
		
		$('#smartListL').hide(); 
		$('.allAddPost').show();
		$('.checkYoutubeChannel').show();
		$($("input[name='postType']")).val('3');
    }
	
    else if ($(this).val() == '4') {
		
		hideAll2();
		hideAll3();
		
		$('#smartListL').hide(); 
		$('.allAddPost').show();
		$('.videoRadio').show();
		$('.checkYoutubeVideo').show();
		$($("input[name='postType']")).val('4');
    }
    
    else {
		
		hideAll2();
		hideAll3();
		
    }
    
});


var chboxs = [

			"#checkfacebookpage1",
			"#checkfacebookpage2",
			"#checkfacebookpost1",
			"#checkfacebookpost2",
			"#checktwitterpage1",
			"#checktwitterpage2",
			"#checktwitterpost1",
			"#checktwitterpost2",
			"#checkyoutubechannel1",
			"#checkyoutubechannel2",
			"#checkyoutubevideo1",
			"#checkyoutubevideo2",
			"#checkyoutubevideo3"

];


var chShowHide = [

			".likeShow",
			".likePageShow",
			".followShow",
			".shareShow",
			".subscribeShow",
			".viewShow"


];


function hideAll3()

{
	
   for (i = 0; i < chShowHide.length; i++) { 
			
			$(chShowHide[i]).hide();
			
		}
	 
}


function checkCheckBox() {
     for (var i = 0; i < chboxs.length; i++) {
		 
			if ($(chboxs[i]).is(":checked")) {
				
				$('.getPanel').show();
			
				break;
				
            } else {
				
				$('.getPanel').hide();
				
			}
        }
    }



$(function () {
        $("#checkfacebookpage1").click(function () {
            if ($(this).is(":checked")) {
                $('.likePageShow').show();
				checkCheckBox();
            } else {
                $('.likePageShow').hide();
				checkCheckBox();
				$('#likePageCount').val("");
            }
        });
    });
	

$(function () {
        $("#checkfacebookpage2").click(function () {
            if ($(this).is(":checked")) {
                $('.shareShow').show();
				checkCheckBox();
            } else {
                $('.shareShow').hide();
				checkCheckBox();
				$('#shareCount').val("");
            }
        });
    });
	

$(function () {
        $("#checkfacebookpost1").click(function () {
            if ($(this).is(":checked")) {
                $('.likeShow').show();
				checkCheckBox();
            } else {
                $('.likeShow').hide();
				checkCheckBox();
				$('#likeCount').val("");
            }
        });
    });
	
	
$(function () {
        $("#checkfacebookpost2").click(function () {
            if ($(this).is(":checked")) {
                $('.shareShow').show();
				checkCheckBox();
            } else {
                $('.shareShow').hide();
				checkCheckBox();
				$('#shareCount').val("");
            }
        });
    });

$(function () {
        $("#checktwitterpage1").click(function () {
            if ($(this).is(":checked")) {
                $('.followShow').show();
				checkCheckBox();
            } else {
                $('.followShow').hide();
				checkCheckBox();
				$('#followerCount').val("");
            }
        });
    });
	
$(function () {
        $("#checktwitterpage2").click(function () {
            if ($(this).is(":checked")) {
                $('.shareShow').show();
				checkCheckBox();
            } else {
                $('.shareShow').hide();
				checkCheckBox();
				$('#shareCount').val("");
            }
        });
    });

	
$(function () {
        $("#checktwitterpost1").click(function () {
            if ($(this).is(":checked")) {
                $('.likeShow').show();
				checkCheckBox();
            } else {
                $('.likeShow').hide();
				checkCheckBox();
				$('#likeCount').val("");
            }
        });
    });
	
	
$(function () {
        $("#checktwitterpost2").click(function () {
            if ($(this).is(":checked")) {
               $('.shareShow').show();
				checkCheckBox();
            } else {
                $('.shareShow').hide();
				checkCheckBox();
				$('#shareCount').val("");
            }
        });
    });
	
	
	$(function () {
        $("#checkyoutubechannel1").click(function () {
            if ($(this).is(":checked")) {
               $('.subscribeShow').show();
				checkCheckBox();
            } else {
                $('.subscribeShow').hide();
				checkCheckBox();
				$('#subscribeCount').val("");
            }
        });
    });
	
	
	
	$(function () {
        $("#checkyoutubechannel2").click(function () {
            if ($(this).is(":checked")) {
               $('.shareShow').show();
				checkCheckBox();
            } else {
                $('.shareShow').hide();
				checkCheckBox();
				$('#shareCount').val("");
            }
        });
    });
	
	
	$(function () {
        $("#checkyoutubevideo1").click(function () {
            if ($(this).is(":checked")) {
               $('.viewShow').show();
				checkCheckBox();
            } else {
                $('.viewShow').hide();
				checkCheckBox();
				$('#viewCount').val("");
            }
        });
    });
	
	
	
	$(function () {
        $("#checkyoutubevideo2").click(function () {
            if ($(this).is(":checked")) {
               $('.shareShow').show();
				checkCheckBox();
            } else {
                $('.shareShow').hide();
				checkCheckBox();
				$('#shareCount').val("");
            }
        });
    });
	
	
		$(function () {
        $("#checkyoutubevideo3").click(function () {
            if ($(this).is(":checked")) {
               $('.likeShow').show();
				checkCheckBox();
            } else {
                $('.likeShow').hide();
				checkCheckBox();
				$('#likeCount').val("");
            }
        });
    });


	function smartList(e,progress){
		
		$('#addpost-panel').hide();
		$('#loading').show();  
		
		$('.sourceList').click(function() {
			if ($(this).is(":checked")) {
				var group = "input:checkbox[name='" + $(this).attr("name") + "']";
				$(group).prop("checked", false);
				$(this).prop("checked", true);
			} else {
				$(this).prop("checked", false);
			}
		});
		
		if(progress == 'pagesAndMe'){ 
		$.post("/BL/socials.php?run=posts",{sourceid:e},function(data){
			$('#smartList').html(data);
			
			$('#loading').hide();
			$('#addpost-panel').show();
			$('#smartListButton').show();
		});
		}else if(progress == 'pages' || progress == 'myPosts' || progress == 'pagePosts'){
			
			$('#loading').hide();
			$('#addpost-panel').show();  
			
			document.getElementById("socialID").value = e;
		}
	}
	
	
		$(document).ready(function(){
			
			$('[data-toggle="tooltip"]').tooltip();   
			
		});
		
		
	</script>
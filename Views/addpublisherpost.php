<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publishers.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";

if (!isset($_SESSION['userID'])) {
	
	$url = trim($_SERVER["REQUEST_URI"], '/');
	
	$fn = new functions();
	$fn->redirect("login?pre=" . $url);
}

if (isset($_POST['publisher'])) {
	
	$publisherCont = new publishers($_POST['publisher']);
	
	if($publisherCont->ID > 0) {
		$publisherID = $_POST['publisher'];
		
		$publisher = new publishers($publisherID);
		$userP = new users($publisher->userID);
		
		$socialT = new userSocials($publisher->twitter);  
		$socialY = new userSocials($publisher->youtube); 
		
		$pageN = 0;
		
	} else {
		$publisherID = 0;
	}
	
} else {

	$publisherID = 0;
	
}

if($publisherID == 0) {
	
$_SESSION['pagenumP']=1;
$_SESSION['randomP']=rand(1,50);

}

$loc = new localization ($_SESSION['language']); 
$obj = new objects ();
$userID = isset ( $_SESSION ["userID"] ) ? $_SESSION ["userID"] : 0;

function kFormatter($num) {
    if(1000000 > $num && $num > 999) {
		return (number_format(($num/1000), 1, '.', '') + 0) . 'K';
	} else if(1000000000 > $num && $num > 999999) {
		return (number_format(($num/1000000), 1, '.', '') + 0) . 'M';
	} else if(1000000000000> $num && $num > 999999999) {
		return (number_format(($num/1000000000), 1, '.', '') + 0) . 'B'; 
	} else if($num > 999999999999) {
		return "-";
	} else {
		return $num;
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
							<li style="display: inline-block; float: none;"><a style="font-size: 18px;" href="addpost" aria-expanded="false"><i class="fa fa-globe" style="color: #2196f3;"></i><?php echo $loc->label("Massive Posts");?> </a></li>
							<li class="active" style="display: inline-block; float: none;"><a style="font-size: 18px;" href="#itab2" data-toggle="tab" aria-expanded="true"><i class="fa fa-group" style="color: #ffc107;"></i> <?php echo $loc->label("Specific Posts(Publisher)");?></a></li>
							
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane fade in active">
							
								<?php if(0==1) { ?>


		<?php if($publisherID == 0) { ?>
				

				<div id="allPublisherPost">
				
				<div id="wrapper">	
				
				
		
		
		<section style="padding-top: 0px;">
	
				<div class="row">
					<div class="col-md-3 leftside">
					
			
						
						<div class="widget widget-box margin-bottom-40">
							<div class="title">Filter</div>
							<form>
								<div class="form-group">
									<label for="title">Search</label>
									<input type="text" class="form-control" id="search" placeholder="Search">
								</div>  
								<div class="form-group">
									<label for="description">Platform</label>
									<select data-placeholder="<?php echo $loc->label("Platform"); ?>" class="chosen-select" name="platform" id="platform">
										<option value="0" selected disabled><?php echo $loc->label("Select"); ?></option>
										<option value="1">Facebook</option>
										<option value="2">Twitter</option>
										<option value="3">Youtube</option>
									</select>
								</div>
								<div class="form-group">
									<label for="description">Category</label>
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=12 and isDeleted<>1", "category", 0);?>	
								</div>
								<div class="form-group">
									<label for="description">Language</label>
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=56 and isDeleted<>1", "language", 0);?>	
								</div>
								<div class="form-group">
									<label for="description">Country</label>
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=13 and isDeleted<>1", "country", 0);?>
								</div>
								<div class="form-group">
									<label for="description">Age</label>
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=6 and isDeleted<>1", "age", 0);?>
								</div>
								<div class="form-group">
									<label for="description">Gender</label>
									<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=1 and isDeleted<>1", "gender", 0);?>
								</div>
								<div class="form-group">
									<label for="description">Sort</label>
									<select data-placeholder="<?php echo $loc->label("Sort"); ?>" class="chosen-select" name="sort" id="sort">
										<option value="0" selected disabled><?php echo $loc->label("Select"); ?></option>
										<option value="1">En iyi skor</option>  
										<option value="3">En yüksek & </option>
										<option value="2">En düşük & </option>
									</select>
								</div>
								<a href="javascript: sortPublishers(0,0);" class="btn btn-block btn-primary margin-top-20">Filter</a>
								<a href="javascript: clear();" class="btn btn-block btn-default margin-top-20">Sıfırla</a>
							</form>
						</div>
				
		
					</div>
					
					<div class="col-md-9 rightside">
					
					<div class="panel panel-default panel-post" style="margin-bottom: 20px !important;">

					<div class="panel-body">

						<div class="post text-center">	

							<p style="margin: 0px; font-weight: bold;"><?php echo $loc->label("selectPublisherText");?></p>
						

						</div>

					</div>
				</div>
						
						<div id="publisher-panel">
						

						
						</div>
						
						<div id="loadMoreArea" class="text-center"><a id ="loadMore" href="javascript: sortPublishers(1,0)" class="btn btn-primary btn-lg btn-shadow btn-rounded btn-icon-right margin-top-10 margin-bottom-40"><?php echo $loc->label("Load More");?></a><div id="loader" style="display: none;"><img src="images/loader.gif" /></div></div>
					
			
					
					</div>
					
					
				</div>
		
		</section>
	</div>

				</div>
				
		<?php } ?>
		
		
		<?php if($publisherID != 0) { ?>
		
			<div class="panel panel-default panel-post" style="margin-bottom: 10px !important;">

					<div class="panel-body">

						<div class="post">	

							<h4>
							
							<?php echo $loc->label("Add post for");?> <a href="profile?id=<?php echo $userP->ID; ?>" class="author"><img style="margin-right: 5px; margin-left: 3px;" class="img-circle" width="42" height="42" src="<?php echo ($userP->picture!="") ? project::uploadPath."/userImg/".$userP->picture : "../Assets/images/profile.jpg";?>" alt=""><?php echo $userP->fullName;?></a>
							<span id="whichPt" style="display: none;"></span>
							<div style="float: right; font-size: 30px; color: #777;">
											<a href="addpublisherpost"><i class="fa fa-remove"></i></a>
										</div>
							
							</h4>  
						

						</div>

					</div>
				</div>
				
				<div id="selectPage" class="form-group">

				<div class="row margin-top-30 text-center">
						
				<?php if($publisher->facebook!="" && $publisher->facebook!=0) { $pageN+=1;?>	  
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 margin-bottom-sm-30">
						<div class="panel panel-default text-center" style="margin-bottom: 5px;">
							<div class="panel-body">

								<table class="table text-center">
									<thead>
										<tr>
											<td style="text-align: center;padding: 15px;"><h3><i data-toggle="tooltip" title="Facebook" class="fa fa-facebook-square" style="color: #3b5998; margin-right: 5px;"></i><?php echo $publisher->facebookPageName; ?></h3></td> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<td style="text-align: center;padding: 15px;"><a style="color: #2776dc; font-weight: bold;cursor: pointer;" target="_blank" href="<?php echo $publisher->facebookPageLink; ?>"><?php echo $loc->label("Go to the page");?><i class="fa fa-external-link" style="margin-left: 5px;"></i></a></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo kFormatter($publisher->facebookPageLikes); ?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("One share");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Lower bound");?> <b style="font-size: 18px;"><?php echo $publisher->priceF; ?>& </b></td>
										</tr>  
										<tr>
											<td style="text-align: center;padding: 15px;"><a href="javascript: selectP(1);" id="button" value="f" class="btn btn-success btn-shadow btn-icon-left"><i class="fa fa-check"></i><?php echo $loc->label("Add Post");?></a></td>
										</tr>
									</tbody>  
								</table>
							
							</div>

							
							
						</div>
					</div>

				<?php } ?>
					
				<?php if($publisher->twitter!="" && $publisher->twitter!=0) { $pageN+=1;?>	  
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 margin-bottom-sm-30">
						<div class="panel panel-default text-center" style="margin-bottom: 5px;">
							<div class="panel-body">

								<table class="table text-center">
									<thead>
										<tr>
											<td style="text-align: center;padding: 15px;"><h3><i data-toggle="tooltip" title="Twitter" class="fa fa-twitter-square" style="color: #1da1f2; margin-right: 5px;"></i><?php echo $socialT->screenName; ?></h3></td> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<td style="text-align: center;padding: 15px;"><a style="color: #2776dc; font-weight: bold;cursor: pointer;" target="_blank" href="http://www.twitter.com/<?php echo $socialT->screenName; ?>"><?php echo $loc->label("Go to the page");?><i class="fa fa-external-link" style="margin-left: 5px;"></i></a></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo kFormatter($socialT->followerCount); ?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("One share");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Lower Bound");?> <b style="font-size: 18px;"><?php echo $publisher->priceT; ?>& </b></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><a href="javascript: selectP(2);" id="button" value="f" class="btn btn-success btn-shadow btn-icon-left"><i class="fa fa-check"></i><?php echo $loc->label("Add Post");?></a></td>
										</tr>
									</tbody>  
								</table>
							
							</div>

							
							
						</div>
					</div>
				<?php } ?>

				<?php if($publisher->youtube!="" && $publisher->youtube!=0) { $pageN+=1;?>	
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 margin-bottom-sm-30">
						<div class="panel panel-default text-center" style="margin-bottom: 5px;">
							<div class="panel-body">

								<table class="table text-center">
									<thead>
										<tr>
											<td style="text-align: center;padding: 15px;"><h3><i data-toggle="tooltip" title="Youtube" class="fa fa-youtube-square" style="color: #cd201f; margin-right: 5px;"></i><?php echo $socialY->screenName; ?></h3></td> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<td style="text-align: center;padding: 15px;"><a style="color: #2776dc; font-weight: bold;cursor: pointer;" target="_blank" href="<?php echo $publisher->youtubeChannelLink; ?>"><?php echo $loc->label("Go to the page");?><i class="fa fa-external-link" style="margin-left: 5px;"></i></a></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo kFormatter($publisher->youtubeSubscriber); ?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("One video");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Lower Bound");?> <b style="font-size: 18px;"><?php echo $publisher->priceY; ?>& </b></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><a href="javascript: selectP(4);" id="button" value="f" class="btn btn-success btn-shadow btn-icon-left"><i class="fa fa-check"></i><?php echo $loc->label("Add Post");?></a></td>
										</tr>  
									</tbody>  
								</table>
							
							</div> 
							
							</div>
					</div>
				<?php } ?>
				
				<?php if($pageN == 2) { ?>	
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 margin-bottom-sm-30">
						<div class="panel panel-default text-center" style="margin-bottom: 5px; border-color: #e74c3c;">
							<div class="panel-body">

								<table class="table text-center">
									<thead>
										<tr>
											<td style="text-align: center;padding: 15px;"><h3> 
											<?php if($publisher->facebook!="" && $publisher->facebook!=0) { $pageF=1;?><i data-toggle="tooltip" title="Facebook" class="fa fa-facebook-square" style="color: #3b5998;"></i><?php } ?>
											<?php if($publisher->twitter!="" && $publisher->twitter!=0) { $pageT=1;?><? if(isset($pageF)){echo" + ";}?><i data-toggle="tooltip" title="Twitter" class="fa fa-twitter-square" style="color: #1da1f2;"></i><?php } ?>
											<?php if($publisher->youtube!="" && $publisher->youtube!=0) {?><? if(isset($pageT)){echo" + ";}?><i data-toggle="tooltip" title="Youtube" class="fa fa-youtube-square" style="color: #cd201f;"></i><?php } ?>
											</h3></td> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("DOUBLE");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><h4><?php echo kFormatter($publisher->facebookPageLikes + $socialT->followerCount + $publisher->youtubeSubscriber); ?><h4></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Two share");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Lower Bound");?> <b style="font-size: 18px;"><?php echo ($publisher->priceF+$publisher->priceT+$publisher->priceY); ?>& </b></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><a href="javascript: selectP(99);" id="button" value="f" class="btn btn-success btn-shadow btn-icon-left"><i class="fa fa-check"></i><?php echo $loc->label("Add Post");?></a></td>
										</tr>  
									</tbody>  
								</table>
							
							</div> 
							
							</div>
					</div>
				<?php } else if($pageN == 3) { ?>	
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-sm-30 margin-top-10">
						<div class="panel panel-default text-center" style="margin-bottom: 5px; border-color: #27ae60;">
							<div class="panel-body">

								<table class="table text-center">
									<thead>
										<tr>
											<td style="text-align: center;padding: 15px;"><h3 id="comboTitle"> 
											<i data-toggle="tooltip" title="Facebook" class="fa fa-facebook-square" style="color: #3b5998;"></i>
											+ <i data-toggle="tooltip" title="Twitter" class="fa fa-twitter-square" style="color: #1da1f2;"></i>
											+ <i data-toggle="tooltip" title="Youtube" class="fa fa-youtube-square" style="color: #cd201f;"></i>
											</h3></td> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("COMBO");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><h4><?php echo kFormatter($publisher->facebookPageLikes + $socialT->followerCount + $publisher->youtubeSubscriber); ?></h4></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Three share");?></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><?php echo $loc->label("Lower Bound");?> <b style="font-size: 18px;"><?php echo ($publisher->priceF+$publisher->priceT+$publisher->priceY); ?>& </b></td>
										</tr>
										<tr>
											<td style="text-align: center;padding: 15px;"><a href="javascript: selectP(999);" id="button" value="f" class="btn btn-success btn-shadow btn-icon-left"><i class="fa fa-check"></i><?php echo $loc->label("Add Post");?></a></td>
										</tr>  
									</tbody>  
								</table>
							
							</div> 
							
							</div>
					</div>
				<?php } ?>
							
							
						
					
				</div>


							</div>
							
			<form enctype='multipart/form-data'>
							
			<div id="newPost" style="display: none;">
			
				<div class="panel panel-default panel-post margin-top-30">

					<div class="panel-body">

						<div class="post">	
						
						

						<div class="text-center margin-bottom-20"><p><?php echo $loc->label("newPublisherPostText");?></p></div>
									
									 <div class="row">
                <!-- title input-->
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Post Title");?> <i class="fa fa-question-circle-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Post title should explain what you want to be shared by the publisher shortly.");?>"
															style="font-size: 15px; margin-left: 5px; cursor: pointer;"></i></label>
                    <div class="controls">
                        <input maxlength="100" class="form-control" name="title" type="text" placeholder="<?php echo $loc->label("Post Title");?>" />
                        <p class="help-block"></p>
                    </div>
                </div>
				</div>
			</div>
		
				 <div class="row">
                <!-- details input-->
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Post Details");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("Write a description for your post in detail. You can mention the content for sharing. If you are a company, write something regarding your products and company.");?>"  
															style="font-size: 15px; margin-left: 5px; cursor: pointer;"></i></label>
                    <div class="controls">
                        <textarea maxlength="500" class="form-control" style="height: 150px;" name="details" placeholder="<?php echo $loc->label("Post Details");?>" type="text"></textarea>
                        <p class="help-block"></p>
                    </div>
                </div>  
				</div>
			</div>
		


									
									 <div class="row">
                <!-- link input-->
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Link");?> <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("If you want to be shared a url, you should fill that.");?>"
															style="font-size: 15px; margin-left: 5px; cursor: pointer;"></i></label>
                    <div class="controls">
                        <input class="form-control" name="link" type="url" placeholder="http://example.com" />
                        <p class="help-block"></p>
                    </div>
                </div>  
				</div>
			</div>
	
								
									
									 <div class="row">
                <!-- document input-->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Document");?><i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("If you want to be shared a video, image etc, upload it.");?>"
															style="font-size: 15px; margin-left: 5px; cursor: pointer;"></i></label>
                    <div class="controls">
						<div id="form1" runat="server">  

							 <input type='file' name="document" id="document" />  

								</div>
                        <p class="help-block"><?php echo $loc->label("Video, image etc.");?></p>
                    </div>
                </div>  
				</div>
			</div>
			
			 <div class="row">
                <!-- offer input-->
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Bid");?>(&) <i class="fa fa-question-circle"
															data-toggle="tooltip"
															title="<?php echo $loc->label("You must enter a bid above the lower bound. The bid that you will enter should be applicable for rating of the publisher depending on your post. After you added the post, we will withdraw the bid you entered from your account for security. If the post cannot completed, we retransfer it into your account.");?>"
															style="font-size: 15px; margin-left: 5px; cursor: pointer;"></i></label>
                    <div class="controls">
                        <input max="9999999999" class="form-control" id="bid" name="bid" type="number" placeholder="Bid(&)" />
                        <p class="help-block"><?php echo $loc->label("Min");?>: <b id="lowerb"></b><b>& </b></p> 
                    </div>
                </div>  
				</div>
			</div>
			
			<p class="text-center"><a href="javascript: submitAddPost();" class="btn btn-rounded btn-primary"><?php echo $loc->label("Add");?></a></p>

						</div>

					</div>
				</div>
			
			
			</div>
							
			
			<input type="hidden" id="publisherID" name="publisherID" value="<?php echo $publisherID; ?>" />
			<input type="hidden" id="which_package" name="which_package" value="" />
			
			</form>
		
		<?php } ?>
		
<?php } else { ?> 
	
	<center class="margin-bottom-40 margin-top-40">
	<h2 style="color: #9E9E9E;"><?php echo $loc->label("adppostComingSheader"); ?></h2>
	<p><?php echo $loc->label("adppostComingSText"); ?></p>
	<a href="bepublisher" class="btn btn-default btn-lg btn-icon-right" style="color: #9E9E9E; border: 1px solid #9E9E9E !important;"><?php echo $loc->label("Click Here"); ?> <i class="fa fa-check"></i></a>
	</center>
	<?php } ?>
				
				
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
	

						</div>
					</div>

	
				</div>
				
					</div>

<?php if(1 == 0) { ?>				
<?php if($publisherID != 0) { ?>
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

	


	</div>
	
		<?php } ?>
		<?php } ?>
 
				</section>
<div id="loading"></div>

<!-- Javascript -->

<script src="../Library/bootstrap-3.3.6/plugins/jquery/chosen.jquery.js"
	type="text/javascript"></script>
	
<?php if(0==1) { ?>
<script>

<?php if($publisherID == 0) { ?>
$( document ).ready(function() {
		sortPublishers(0,1);
});

	$(window).scroll(function() {  
       if($(window).scrollTop() + $(window).height() == $(document).height()) {
           sortPublishers(1,0);
       }
    });

	function sortPublishers(next,first) { 
		
		if(next == 0) {
			
			$("#publisher-panel").hide();
			
		}
		
		first = typeof first !== 'undefined' ? first : 0;
		
		var search =  $("#search").val() !== '' ? $("#search").val() : ''; 
		var category =  $("#category").val() !== '' ? $("#category").val() : 0; 
		var language =  $("#language").val() !== '' ? $("#language").val() : 0; 
		var platform =  $("#platform").val() !== '' ? $("#platform").val() : 0;  
		var country =  $("#country").val() !== '' ? $("#country").val() : 55;  
		var age =  $("#age").val() !== '' ? $("#age").val() : 0;  
		var gender =  $("#gender").val() !== '' ? $("#gender").val() : 0;  
		var sort =  $("#sort").val() !== '' ? $("#sort").val() : 0;  
		
		$('#loadMore').show();
		
		if(first == 0) {
			
			$('#loadMore').hide();
			$('#loader').show();
		
		}

	
					$.ajax({
						type: 'POST',
						url: "../BL/showPublishers.php",
						data: {nextpage: next, search: search, category: category, language: language, platform: platform, country: country, age: age, gender: gender, sort: sort}, 
						success: function cevap(e){

							if(next == 0){
								$("#publisher-panel").html(e.replace("NOPUBLISHERS", ""));
								
							}else{
								$("#publisher-panel").append(e.replace("NOPUBLISHERS", ""));
								
							}


							if(e.indexOf("NOPUBLISHERS") > -1){  
								
								$('#loadMore').hide();
								$('#loader').hide();
								
								$("#loadMoreArea").html("<?php echo $loc->label("The End Publishers");?>");
								
							} else {
								
								$('#loader').hide();  
								$('#loadMore').show();

								
							}
							
							$("#publisher-panel").show();

						
						}
						});
						
						
					
				}
	
	function clear() { 
		
		$("select").each(function() { this.selectedIndex = 0 });
		$(':input').val('');
		sortPublishers(0,0);
		
	}
	

$("#search").change(function() {
	sortPublishers(0,0);
});

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});

	var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$("#search").keyup(function() {
    delay(function(){
		
	sortPublishers(0,0);
  
    }, 400 );
});
	
<?php } ?>

<?php if($publisherID != 0) { ?>

	function selectP(e){
		
		if(<? echo $publisher->userID; ?> == <? echo $_SESSION["userID"]; ?>) {
		
			document.getElementById("modalText").innerHTML = "<?php echo $loc->label('You cannot add a post for you.'); ?>";
			document.getElementById("modalButton").click(); 
			
		} else {
			
			switch(e) {
				
			<?php if($publisher->facebook!="" && $publisher->facebook!=0) { ?>	
			
				case 1 :
					
					$("#whichPt").html('-> <i data-toggle="tooltip" title="Facebook" class="fa fa-facebook-square" style="color: #3b5998; margin-right: 5px;"></i><?php echo $publisher->facebookPageName; ?>');
					$("#whichPt").show();
					$("#lowerb").html("<?php echo $publisher->priceF; ?>");
					
					break;
					
			<?php } ?>
				
			<?php if($publisher->twitter!="" && $publisher->twitter!=0) { ?>	  
					
				case 2 :
					
					$("#whichPt").html('-> <i data-toggle="tooltip" title="Twitter" class="fa fa-twitter-square" style="color: #1da1f2; margin-right: 5px;"></i><?php echo $socialT->screenName; ?>');
					$("#whichPt").show();
					$("#lowerb").html("<?php echo $publisher->priceT; ?>");
					
					break;
					
			<?php } ?>	
					
			<?php if($publisher->youtube!="" && $publisher->youtube!=0) { ?>	
					
				case 3 :
					
					$("#whichPt").html('-> <i data-toggle="tooltip" title="Youtube" class="fa fa-youtube-square" style="color: #cd201f; margin-right: 5px;"></i><?php echo $socialY->screenName; ?>');
					$("#whichPt").show();
					$("#lowerb").html("<?php echo $publisher->priceY; ?>");
					
					break;
					
			<?php } ?>	
					
			<?php if($pageN == 2) { ?>	
				
				case 99 :
					
					$("#whichPt").html('-> <?php if($publisher->facebook!="" && $publisher->facebook!=0) { $pageF=1;?><i data-toggle="tooltip" title="Facebook" class="fa fa-facebook-square" style="color: #3b5998;"></i><?php } ?><?php if($publisher->twitter!="" && $publisher->twitter!=0) { $pageT=1;?><? if(isset($pageF)){echo" + ";}?><i data-toggle="tooltip" title="Twitter" class="fa fa-twitter-square" style="color: #1da1f2;"></i><?php } ?><?php if($publisher->youtube!="" && $publisher->youtube!=0) {?><? if(isset($pageT)){echo" + ";}?><i data-toggle="tooltip" title="Youtube" class="fa fa-youtube-square" style="color: #cd201f;"></i><?php } ?>(DOUBLE)');
					$("#whichPt").show();
					$("#lowerb").html("<?php echo ($publisher->priceF+$publisher->priceT+$publisher->priceY); ?>");
					
					break;
					
			<?php } else if($pageN == 3) { ?>	
				
				case 999 :
					
					$("#whichPt").html('-> <i data-toggle="tooltip" title="Facebook" class="fa fa-facebook-square" style="color: #3b5998;"></i> + <i data-toggle="tooltip" title="Twitter" class="fa fa-twitter-square" style="color: #1da1f2;"></i> + <i data-toggle="tooltip" title="Youtube" class="fa fa-youtube-square" style="color: #cd201f;"></i>(COMBO)');
					$("#whichPt").show();
					$("#lowerb").html("<?php echo ($publisher->priceF+$publisher->priceT+$publisher->priceY); ?>");
					
					break;   
				
			<?php } ?>	
			}
			
			$("#which_package").val(e);
			$('#selectPage').hide();
			$('#newPost').show();
			$("html, body").animate({ scrollTop: 0 }, "slow");
			
		}

		
	} 
	
	
	function submitAddPost(){
		
		$('#addpost-panel').hide();
		$('#loading').show();
		
		var formDataFile = new FormData(); 
	
		var formData = $("form").serializeArray();
	
		for (var i=0; i<formData.length; i++)  
			formDataFile.append(formData[i].name, formData[i].value);

		formDataFile.append('document', $('#document')[0].files[0]); 
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=publisherPost",
			data: formDataFile,
			processData: false,  // tell jQuery not to process the data
			contentType: false,  // tell jQuery not to set contentType
			success: function cevap(e){
				
				
				$('#loading').hide();
				$('#addpost-panel').show();
				if(e.match(/[a-z]/i) && !(e.indexOf("ok") > -1)){
					
					document.getElementById("modalText").innerHTML = e;
					document.getElementById("modalButton").click(); 
					
				}else if(e.indexOf("ok") > -1){
					if($("#bid").val() != 0) {
						$("#newPoints").text('-'+$("#bid").val()).css("display", "inline");
						$("#newPoints").removeClass("label-success").addClass("label-danger");
						$("#currentPoints").fadeOut(500, function(){$("#currentPoints").text(Math.round((+$("#currentPoints").text()- +$("#bid").val())*100)/100)}).fadeIn(1500, function(){$("#newPoints").css("display", "none");});
					}
					$('#addpost-panel').hide();
					$('#success-panel').show();   
				}

			}
			})   
		}
	
	

<?php } ?>
 
	
	$(document).ready(function(){
			
			$('[data-toggle="tooltip"]').tooltip();   
			
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

<?php } ?>

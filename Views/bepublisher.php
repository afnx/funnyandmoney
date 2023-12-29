<?php
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/publisherApplications.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/userSocials.php';

$loc = new localization($_SESSION['language']);

$form = "";
$formComplete = 0;
if(isset($_SESSION["userID"])) {
	
$userid = $_SESSION["userID"];
	
$user = new users($userid);
	
if(isset($_GET["form"])) {
	
if ($user->publisher == 1) {
	$fn = new functions();
	$fn->redirect("/");
}
	
if($_GET["form"] == "on") {
	$form = "on";
	
	$obj = new objects ();

}
$publisherC = new publisherApplications();
$publisherC = publisherApplications::checkUserPublishForm($userid);

if($publisherC->ID > 0) {
	
	$form = "on";
	$formComplete=1;
	
	$publisher = new publisherApplications();
	$result = $publisher->getPublishForm($userid);

	while($row=mysqli_fetch_array($result)) {
	
		$rowID = $row["ID"];
		$firstName = $row["firstName"];
		$lastName = $row["lastName"];
		$birthDate = $row["birthDate"];
		$nationality = $row["nationality"];
		$identityID = $row["identityID"];
		$identityDocument = $row["identityDocument"];
		$proofAddress = $row["proofAddress"];
		$phone = $row["phone"];
		$address = $row["address"];
		$region = $row["region"];
		$postalCode = $row["postalCode"];
		$city = $row["city"];
		$country = $row["country"];
		$socialName = $row["socialName"];
		$description = $row["description"];
		$facebook = $row["facebook"];
		$twitter = $row["twitter"];
		$youtube = $row["youtube"];
		$category = $row["category"];
		$gender = $row["genderGroups"];
		$language = $row["language"];
		$ageGroups = $row["ageGroups"];
		$countryGroups = $row["countryGroups"];
		$status = $row["status"];
	
	}
	
	$time=strtotime($birthDate);
	$month=date("m",$time);
	$year=date("Y",$time);
	$day=date("d",$time);
	
	
	
} else {
	
	$formComplete=0;

		$rowID = "";
		$firstName = "";
		$lastName = "";
		$birthDate = "";
		$nationality = "";
		$identityID = "";
		$identityDocument = "";
		$proofAddress = "";
		$phone = "";
		$address = "";
		$region = "";
		$postalCode = "";
		$city = "";
		$country = "";
		$socialName = "";
		$description = "";
		$facebook = "";
		$twitter = "";
		$youtube = "";
		$category = "";
		$gender = "";
		$language = "";
		$ageGroups = "";
		$countryGroups = "";
		$status = "";
	
	$month=0;
	$year=0;
	$day=0;
	
}

}

$usFacebook = userSocials::getUserSocialFromID ( $userid, 1 );
if($usFacebook-> ID < 1){
	$fbError='<a href="../Controllers/facebook.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;">'.$loc->label("Add a Facebook account").'</button></a>';
}

$usTwitter = userSocials::getUserSocialFromID ( $userid, 2 );
if($usTwitter-> ID < 1){
	$twError='<a href="../Controllers/twitter.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;">'.$loc->label("Add a Twitter account").'</button></a>';
}

$usGoogle = userSocials::getUserSocialFromID ( $userid, 4 );
if($usGoogle-> ID < 1){
	$goError='<a href="../Controllers/google.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;">'.$loc->label("Add a Youtube account").'</button></a>';
}

	
} else{
	$formComplete=0;
}



?>	
	
	<head>


<link rel="stylesheet" href="../Library/bootstrap-3.3.6/css/chosen.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.min.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.css">

	

<style>

.icon {
  overflow: hidden;
  display: inline-block;
  font-size: 120px; 
  color: #ffeb3b;

  text-decoration: none;
}

/**
 * The "shine" element
 */

.icon:after {
  
    animation: shine 5s cubic-bezier(0.72, 0.27, 0.27, 0.58) infinite;
    animation-fill-mode: forwards;
    content: "";
    position: absolute;
    top: -100%;
    left: -200%;
    width: 110%;
    height: 60%;
  opacity: 0;
  transform: rotate(30deg);
  
  background: rgba(255, 255, 255, 0.13);
  background: linear-gradient(
    to right, 
    rgba(255, 255, 255, 0.13) 0%,
    rgba(255, 255, 255, 0.13) 77%,
    rgba(255, 255, 255, 0.5) 92%,
    rgba(255, 255, 255, 0.0) 100%
  );
}

/* Hover state - trigger effect */


/* Active state */

.icon:active:after {
  opacity: 0;
}

@keyframes shine{
  10% {
    opacity: 1;
    top: -30%;
    left: -30%;
    transition-property: left, top, opacity;
    transition-duration: 0.7s, 0.7s, 0.15s;
    transition-timing-function: ease;
  }
  100% {
    opacity: 0;
    top: -30%;
    left: -30%;
    transition-property: left, top, opacity;
  }
}
 
</style>



</head>
		<section style="margin-bottom: 100px;">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="post post-fl">
							<div class="post-header">
								<center style="margin-bottom: 15px;"><h1><?php echo $loc->label("F&M Publisher");?></h1></center>
			<center>	<p><?php echo $loc->label("bePublisherFirstText");?></p></center>
							</div>  
							

<?php if($form != "on") { ?>

					
					<div class="panel panel-default margin-top-30">
					<div class="panel-body">
					<center><?php echo $loc->label("bePublisherExplainText");?></center>
					</div> 
				</div>

					
				
					
					<div class="row" style="margin-top: 40px;">
					<div class="col-lg-4 margin-bottom-30">
						<div class="panel panel-default">
							<div class="panel-body">
							<center>
							<i class="fa fa-star icon"></i> 
							<br/>
								<?php echo $loc->label("bePublisherExplainText1");?>
							</center>
							</div>
						</div>
					</div>

					<div class="col-lg-4 margin-bottom-30">
						<div class="panel panel-default">
							<div class="panel-body">
								 <center>
							<i class="fa fa-star icon"></i>
							<br/>
								<?php echo $loc->label("bePublisherExplainText2");?>
							</center>
							</div>
						</div>
					</div>
					
					<div class="col-lg-4 margin-bottom-30">
						<div class="panel panel-default">
							<div class="panel-body">
								 <center>
							<i class="fa fa-star icon"></i>  
							<br/>
								<?php echo $loc->label("bePublisherExplainText3");?>
							</center>
							</div>
						</div>
					</div>

			</div>
							
<? } else { ?>

<?php if($formComplete == 1) { ?>


<div id="summary">

<div class="panel panel-default margin-top-30">
					<div class="panel-body">
					<center>
					<?php echo $loc->label("Hello") . " " . $user->fullName . ",";?>
					<br/>
				<?php 
				if($status == 1) {
					echo $loc->label("bePublisherStatusTextVerified");
				} else if($status == 2) { 
					echo $loc->label("bePublisherStatusTextPending");
				} else if($status == 0) {
					echo $loc->label("bePublisherStatusTextCancelled");
				} 
				?>
					</center>
				
					</div> 
</div>


<div class="form-group margin-top-30">
												
<center><?php if($status == 2 or $status == 0) { ?><button type="button" data-toggle="modal" data-target=".bs-modal-sm" class="btn btn-danger btn-lg"><?php echo $loc->label("Cancel Application");?></button><? } ?> <?php if($status == 2) { ?><a href="javascript: change(0);"><button type="button" class="btn btn-primary btn-lg"><?php echo $loc->label("Update");?></button></a> <? } ?></center>  
		
<?php if($status == 2 or $status == 0) { ?>

<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title"><?php echo $loc->label("Cancel Application");?></h4>
							</div>
							<div class="modal-body">
								<?php echo $loc->label("Are you sure cancel application?");?>
							</div>
							<div class="modal-footer">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("No");?></button>
								<a href="javascript: cancel(<?php echo $rowID; ?>);"><button style="margin-right: 15px;" class="btn btn-danger"><?php echo $loc->label("Yes");?></button></a>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				
<? } ?>

</div>

</div> 


<? } ?>


<form id="form" <?php if($formComplete == 1) { ?>style="display: none;"<? } ?> enctype='multipart/form-data'>

<div class="alert alert-danger alert-lg fade in margin-top-30" id="alert" style="display: none;">
						<h4 class="alert-title"><?php echo $loc->label("publisherAlertFormbelow");?></h4>
						<p id="alert-text"></p>
			</div>

<span class="label label-primary text-left margin-bottom-30 label-icon-left margin-top-30" style="width: 100%; background-color: #9E9E9E; font-size: 18px;"><i>1</i><?php echo $loc->label("Personal Information");?></span>

 <div class="row">
                <!-- full-name input-->
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("First Name");?></label>
                    <div class="controls">
                        <input maxlength="250" class="form-control" value="<?php echo $firstName; ?>" name="firstName" type="text" placeholder="<?php echo $loc->label("First Name");?>"
                       >
                        <p class="help-block"></p>
                    </div>
                </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Last Name");?></label>
                    <div class="controls">
                        <input maxlength="250" class="form-control" value="<?php echo $lastName; ?>" name="lastName" type="text" placeholder="<?php echo $loc->label("Last Name");?>"
                       >
                        <p class="help-block"></p>
                    </div>
                </div>
				</div>
			</div>
			
 <div class="row">
 
 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			
<div class="row">
												
												<div class="col-lg-12">

  <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Birth Date");?></label>
                    <div class="controls">
												<div class="row">
												
										<?php if($_SESSION['language'] == "tr") { ?>
										
										
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												

													<div class="form-group">
														
												
												
												<select id="dayS" name="dayS" class="form-control input-md">

													
														<option disabled><?php echo $loc->label("Day");?></option>

														<?php for($i=1;$i<32;$i++){
															echo '<option value="'. $i .'" ' . ($i == $day ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
														
											

															
												</select>

												
												
										</div>
										
										</div>
											
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												
										<div class="form-group">
													
											<select id="monthS" name="monthS" class="form-control input-md">

										
														<option disabled><?php echo $loc->label("Month");?></option>
													
													
														<?php for($i=1;$i<13;$i++){
															echo '<option value="'. $i .'" ' . ($i == $month ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
															
												

															
											</select>

													 
										</div>
										
										</div>
										
										<?php } else { ?>
										
										
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												
										<div class="form-group">
													
											<select id="monthS" name="monthS" class="form-control input-md">

										
														<option disabled><?php echo $loc->label("Month");?></option>
													
													
														<?php for($i=1;$i<13;$i++){
															echo '<option value="'. $i .'" ' . ($i == $month ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
															
												

															
											</select>

													 
										</div>
										
										</div>
										
										
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												

													<div class="form-group">
														
												
												
												<select id="dayS" name="dayS" class="form-control input-md">

													<option disabled><?php echo $loc->label("Day");?></option>
														

														<?php for($i=1;$i<32;$i++){
															echo '<option value="'. $i .'" ' . ($i == $day ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
														
											

															
												</select>

												
												
										</div>
										
										</div>
										
										
										<?php } ?> 
										
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<div class="form-group">
						
											<select id="yearS" name="yearS" class="form-control input-md">

											
													<option disabled><?php echo $loc->label("Year");?></option>
										
													
														<?php for($i=date("Y")-13;$i>date("Y")-80;$i--){
															echo '<option value="'. $i .'" ' . ($i == $year ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
												
											</select>	
													
										</div>
										</div>
										
										</div>
										
										
				  </div>
                </div>
				</div>
          
				</div>
				
				</div>
				
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										
										<div class="row">
                <!-- nationality select -->
				<div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Nationality");?></label>
                    <div class="controls">
					
					<?php if($_SESSION['language'] == "tr") {
					
						echo $obj->dropDownFill("select ID,definition from definitions where definitionID=13 and isDeleted<>1 and ID<>55 order by (ID = 306) desc, ID", "nationality", 0);
					
					} else {
						
						echo $obj->dropDownFill("select ID,definition from definitions where definitionID=13 and isDeleted<>1 and ID<>55", "nationality", 0);
						
					} ?>
																

                    </div>
                </div>
				</div>
          
				</div>
				
				</div>
				
				</div>
				
				
				 <div class="row">
                <!-- full-name input-->
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Identify ID");?></label>
                    <div class="controls">
                        <input maxlength="250" class="form-control" value="<?php echo $identityID; ?>" name="identityID" type="text" placeholder="<?php echo $loc->label("Identify ID");?>"
                       >
                        <p class="help-block"></p>
                    </div>
                </div>
				</div>
			</div>

<span class="label label-primary text-left margin-bottom-30 label-icon-left margin-top-30" style="width: 100%; background-color: #9E9E9E; font-size: 18px;"><i>2</i><?php echo $loc->label("Contact Information");?></span>

 <div class="row">
                <!-- email input-->
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Email");?></label>
                    <div class="controls">
                        <input maxlength="250" value="<?php echo $user->email; ?>" class="form-control" name="email" type="email" placeholder="<?php echo $loc->label("Email");?>"
                       disabled>
                        <p class="help-block"></p>
                    </div>
                </div>
				</div>
			</div>

				<!-- phone input-->
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Phone");?></label>
					
					<div class="row">
					
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
					
					<select name="countryCode" id="countryCode" class="form-control input">
	<optgroup>
	<option value="" selected disabled>Code</option>
		<option data-countryCode="DZ" value="213">Algeria (+213)</option>
		<option data-countryCode="AD" value="376">Andorra (+376)</option>
		<option data-countryCode="AO" value="244">Angola (+244)</option>
		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
		<option data-countryCode="AR" value="54">Argentina (+54)</option>
		<option data-countryCode="AM" value="374">Armenia (+374)</option>
		<option data-countryCode="AW" value="297">Aruba (+297)</option>
		<option data-countryCode="AU" value="61">Australia (+61)</option>
		<option data-countryCode="AT" value="43">Austria (+43)</option>
		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
		<option data-countryCode="BY" value="375">Belarus (+375)</option>
		<option data-countryCode="BE" value="32">Belgium (+32)</option>
		<option data-countryCode="BZ" value="501">Belize (+501)</option>
		<option data-countryCode="BJ" value="229">Benin (+229)</option>
		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
		<option data-countryCode="BW" value="267">Botswana (+267)</option>
		<option data-countryCode="BR" value="55">Brazil (+55)</option>
		<option data-countryCode="BN" value="673">Brunei (+673)</option>
		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
		<option data-countryCode="BI" value="257">Burundi (+257)</option>
		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
		<option data-countryCode="CA" value="1">Canada (+1)</option>
		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
		<option data-countryCode="CF" value="236">Central African Re(+236)</option>
		<option data-countryCode="CL" value="56">Chile (+56)</option>
		<option data-countryCode="CN" value="86">China (+86)</option>
		<option data-countryCode="CO" value="57">Colombia (+57)</option>
		<option data-countryCode="KM" value="269">Comoros (+269)</option>
		<option data-countryCode="CG" value="242">Congo (+242)</option>
		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
		<option data-countryCode="HR" value="385">Croatia (+385)</option>
		<option data-countryCode="CU" value="53">Cuba (+53)</option>
		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
		<option data-countryCode="CZ" value="42">Czech Re(+42)</option>
		<option data-countryCode="DK" value="45">Denmark (+45)</option>
		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
		<option data-countryCode="DO" value="1809">Dominican Re(+1809)</option>
		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
		<option data-countryCode="EG" value="20">Egypt (+20)</option>
		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
		<option data-countryCode="EE" value="372">Estonia (+372)</option>
		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
		<option data-countryCode="FI" value="358">Finland (+358)</option>
		<option data-countryCode="FR" value="33">France (+33)</option>
		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
		<option data-countryCode="GA" value="241">Gabon (+241)</option>
		<option data-countryCode="GM" value="220">Gambia (+220)</option>
		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
		<option data-countryCode="DE" value="49">Germany (+49)</option>
		<option data-countryCode="GH" value="233">Ghana (+233)</option>
		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
		<option data-countryCode="GR" value="30">Greece (+30)</option>
		<option data-countryCode="GL" value="299">Greenland (+299)</option>
		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
		<option data-countryCode="GU" value="671">Guam (+671)</option>
		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
		<option data-countryCode="GN" value="224">Guinea (+224)</option>
		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
		<option data-countryCode="GY" value="592">Guyana (+592)</option>
		<option data-countryCode="HT" value="509">Haiti (+509)</option>
		<option data-countryCode="HN" value="504">Honduras (+504)</option>
		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
		<option data-countryCode="HU" value="36">Hungary (+36)</option>
		<option data-countryCode="IS" value="354">Iceland (+354)</option>
		<option data-countryCode="IN" value="91">India (+91)</option>
		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
		<option data-countryCode="IR" value="98">Iran (+98)</option>
		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
		<option data-countryCode="IE" value="353">Ireland (+353)</option>
		<option data-countryCode="IL" value="972">Israel (+972)</option>
		<option data-countryCode="IT" value="39">Italy (+39)</option>
		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
		<option data-countryCode="JP" value="81">Japan (+81)</option>
		<option data-countryCode="JO" value="962">Jordan (+962)</option>
		<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
		<option data-countryCode="KE" value="254">Kenya (+254)</option>
		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
		<option data-countryCode="KP" value="850">Korea North (+850)</option>
		<option data-countryCode="KR" value="82">Korea South (+82)</option>
		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
		<option data-countryCode="LA" value="856">Laos (+856)</option>
		<option data-countryCode="LV" value="371">Latvia (+371)</option>
		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
		<option data-countryCode="LR" value="231">Liberia (+231)</option>
		<option data-countryCode="LY" value="218">Libya (+218)</option>
		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
		<option data-countryCode="MO" value="853">Macao (+853)</option>
		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
		<option data-countryCode="MW" value="265">Malawi (+265)</option>
		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
		<option data-countryCode="MV" value="960">Maldives (+960)</option>
		<option data-countryCode="ML" value="223">Mali (+223)</option>
		<option data-countryCode="MT" value="356">Malta (+356)</option>
		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
		<option data-countryCode="MX" value="52">Mexico (+52)</option>
		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
		<option data-countryCode="MD" value="373">Moldova (+373)</option>
		<option data-countryCode="MC" value="377">Monaco (+377)</option>
		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
		<option data-countryCode="MA" value="212">Morocco (+212)</option>
		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
		<option data-countryCode="NA" value="264">Namibia (+264)</option>
		<option data-countryCode="NR" value="674">Nauru (+674)</option>
		<option data-countryCode="NP" value="977">Nepal (+977)</option>
		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
		<option data-countryCode="NE" value="227">Niger (+227)</option>
		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
		<option data-countryCode="NU" value="683">Niue (+683)</option>
		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
		<option data-countryCode="NO" value="47">Norway (+47)</option>
		<option data-countryCode="OM" value="968">Oman (+968)</option>
		<option data-countryCode="PW" value="680">Palau (+680)</option>
		<option data-countryCode="PA" value="507">Panama (+507)</option>
		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
		<option data-countryCode="PE" value="51">Peru (+51)</option>
		<option data-countryCode="PH" value="63">Philippines (+63)</option>
		<option data-countryCode="PL" value="48">Poland (+48)</option>
		<option data-countryCode="PT" value="351">Portugal (+351)</option>
		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
		<option data-countryCode="QA" value="974">Qatar (+974)</option>
		<option data-countryCode="RE" value="262">Reunion (+262)</option>
		<option data-countryCode="RO" value="40">Romania (+40)</option>
		<option data-countryCode="RU" value="7">Russia (+7)</option>
		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
		<option data-countryCode="SM" value="378">San Marino (+378)</option>
		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
		<option data-countryCode="SN" value="221">Senegal (+221)</option>
		<option data-countryCode="CS" value="381">Serbia (+381)</option>
		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
		<option data-countryCode="SG" value="65">Singapore (+65)</option>
		<option data-countryCode="SK" value="421">Slovak Re(+421)</option>
		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
		<option data-countryCode="SO" value="252">Somalia (+252)</option>
		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
		<option data-countryCode="ES" value="34">Spain (+34)</option>
		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
		<option data-countryCode="SD" value="249">Sudan (+249)</option>
		<option data-countryCode="SR" value="597">Suriname (+597)</option>
		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
		<option data-countryCode="SE" value="46">Sweden (+46)</option>
		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
		<option data-countryCode="SI" value="963">Syria (+963)</option>
		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
		<option data-countryCode="TH" value="66">Thailand (+66)</option>
		<option data-countryCode="TG" value="228">Togo (+228)</option>
		<option data-countryCode="TO" value="676">Tonga (+676)</option>
		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
		<option data-countryCode="TR" value="90">Turkey (+90)</option>
		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
		<option data-countryCode="UG" value="256">Uganda (+256)</option>
		<option data-countryCode="GB" value="44">UK (+44)</option>
		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
		<option data-countryCode="US" value="1">USA (+1)</option>
		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
	</optgroup>
</select>
					
		</div>			
		
		<div style="margin-left: -20px;" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					
                    <div class="form-group">
                       <input id="phone" value="<?php echo $phone; ?>" class="form-control" name="phone" type="text"
                       >
                        <p class="help-block"></p>
                    </div>
					
				</div>
					
					</div>
					
                </div>
				<div class="row">
                <!-- address input-->
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Address");?></label>
                    <div class="controls">
                        <textarea id="address" name="address" maxlength="250" class="form-control" type="text" placeholder="<?php echo $loc->label("Address");?>"
                       ><?php echo $address; ?></textarea>
                        <p class="help-block"><?php echo $loc->label("Street address, P.O. box, company name, c/o");?>&<?php echo $loc->label("Apartment, suite , unit, building, floor, etc.");?> </p>
                    </div>
                </div>
				</div>
			</div>
<div class="row">
                <!-- region input-->
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("State / Province / Region");?></label>
                    <div class="controls">
                        <input maxlength="100" id="region" value="<?php echo $region; ?>" name="region" type="text" class="form-control" placeholder="<?php echo $loc->label("State / Province / Region");?>"
                        >
                    </div>
                </div>
				</div>
				<!-- city input-->
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("City / Town");?></label>
                    <div class="controls">
                        <input id="city" name="city" value="<?php echo $city; ?>" maxlength="250" type="text" class="form-control" placeholder="<?php echo $loc->label("City");?>" >
                        <p class="help-block"></p>
                    </div>
                </div>
				</div>
			</div>
			<div class="row">
                <!-- postalcode input-->
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Postal Code");?></label>
                    <div class="controls">
                        <input id="postalCode" maxlength="50" value="<?php echo $postalCode; ?>" class="form-control" name="postalCode" type="text" placeholder="<?php echo $loc->label("Postal Code");?>" />
                    </div>
                </div>
				</div>  
			</div>
			<div class="row">
                <!-- country select -->
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Country");?></label>
                    <div class="controls">
					
					<?php if($_SESSION['language'] == "tr") {
					
						echo $obj->dropDownFill("select ID,definition from definitions where definitionID=13 and isDeleted<>1 and ID<>55 order by (ID = 306) desc, ID", "countryAddress", 0);
					
					} else {
						
						echo $obj->dropDownFill("select ID,definition from definitions where definitionID=13 and isDeleted<>1 and ID<>55", "countryAddress", 0);
						
					} ?>
																

                    </div>
                </div>
				</div>
          
				</div>
				
				


<span class="label label-primary text-left margin-bottom-30 label-icon-left margin-top-30" style="width: 100%; background-color: #9E9E9E; font-size: 18px;"><i>3</i><?php echo $loc->label("Social Media Account Information");?></span>

<div class="panel panel-default margin-bottom-30">
					<div class="panel-body">
					
					<?php echo $loc->label("publisherSocialText"); ?>
					
					</div> 
				</div>
				
				
				 <div class="row">
                <!-- socialName input-->
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Username");?></label>
                    <div class="controls">
                        <input maxlength="30" class="form-control" value="<?php if($user->username!="") { echo $user->username; } ?>" name="username" type="text" placeholder="<?php echo $loc->label("Username");?>"
                      <?php if($user->username!="") { echo "disabled"; } ?> >
                        <p class="help-block"><?php echo $loc->label("Social media accounts displaying name");?></p>
                    </div>
                </div>
				</div>
			</div>
			
			<div class="row">
                <!-- description input-->
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Description");?></label>
                    <div class="controls">
                        <textarea id="description" name="description" maxlength="100" class="form-control" type="text" placeholder="<?php echo $loc->label("Description");?>"
                       ><?php echo $description; ?></textarea>
                        <p class="help-block"><?php echo $loc->label("Write a brief description of your social accounts");?> </p>
                    </div>
                </div>
				</div>
			</div>

			
	<div class="row">
                <!-- facebook select -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Facebook");?></label>
                    <div class="controls">

					<?php if(isset($fbError)){
											echo $fbError;
										}else{
										?>
											
				<div id="smartList" class="comments" style="height: 300px; overflow: auto; border: 2px; margin-top: 0px;">
				
				
				</div>  
											
					<?php } ?>
					
                    </div>
                </div>
				</div>
          
				</div>
				
		
		<div class="row">
               
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Twitter");?></label>
                    <div class="controls">

					<?php if(isset($twError)){
											echo $twError;
										}else{

										?>
											
					<?php echo $usTwitter->screenName; ?>
					
					<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input type="checkbox" id="twCheck" name="twCheck" value="<?php echo $usTwitter->ID; ?>" <? if($twitter!="") { echo"checked"; } ?>> 
										<label for="twCheck"></label>
						
									</div>
								</div>
											
					<?php } ?>
					
                    </div>
                </div>
				</div>
          
				</div>
				
				
		<div class="row">
               
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Youtube");?></label>
                    <div class="controls">

					<?php if(isset($goError)){
											echo $goError;
										}else{

										?>
											
					<?php echo ($usGoogle->screenName=="" ? $loc->label("Your Youtube Account") : $usGoogle->screenName); ?> 
					
					<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input type="checkbox" id="goCheck" name="goCheck" value="<?php echo $usGoogle->ID; ?>" <? if($facebook!="") { echo"checked"; } ?>> 
										<label for="goCheck"></label>
						
									</div>
								</div>
											
					<?php } ?>
					
                    </div>
                </div>
				</div>
          
				</div>   
				<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
                <!-- category select -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Category");?></label>
					<p><?php echo $loc->label("publisherSocialText2"); ?></p>
                    <div class="controls">
					
					<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=12 and isDeleted<>1", "category", 0);?>	
																

                    </div>
                </div>
				</div>
          
				</div>
				</div>
				
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
                <!-- language select -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label"><?php echo $loc->label("Language");?></label>
					<p><?php echo $loc->label("publisherSocialText3"); ?></p>
                    <div class="controls">
					
					<?php echo $obj->dropDownFill("select ID,definition from definitions where definitionID=56 and isDeleted<>1", "language", 0);?>	
																

                    </div> 
                </div>
				</div>
          
				</div>
				</div>
				</div>
				

<span class="label label-primary text-left margin-bottom-30 label-icon-left margin-top-30" style="width: 100%; background-color: #9E9E9E; font-size: 18px;"><i>4</i><?php echo $loc->label("Documents");?></span>

<div class="panel panel-default margin-bottom-30">
					<div class="panel-body">
					
					<?php echo $loc->label("publisherSocialText7"); ?> 
					
					
					</div> 
				</div>

<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 margin-bottom-sm-30">
						<div class="thumbnail">
							<h4 class="thumbnail-title" style="text-align: center;"><?php echo $loc->label("ID of publisher");?></h4>
							<div id="imgInpArea" style="text-align: center;"><i id="imgInpAreaIco" class="fa fa-file-text-o" style="font-size: 120px;"></i><img id="imgInpAreaPic" style="max-height: 260px; max-width: 200;" style="display:none"/></div>
							<div class="caption padding-20" style="text-align: center;">
								<p style="text-align: center;"><?php echo $loc->label("Documentpublish1"); ?></p>
								<div id="form1" runat="server" class="form-group">  

								<p id="imgInpName" style="text-align: center; font-weight: bold;"><?php echo(($identityDocument != "") ? "<span style='color: green;'><i class='fa fa-check-circle' style='font-size: 20px; margin-right: 5px;'></i>". $loc->label("Uploaded") ."</span>" : "");?></p>
								<button type="button" onClick="$('#imgInp').click();" class="btn btn-block btn-primary btn-rounded"><?php echo $loc->label("Upload"); ?></button>
								 <input style="display:none" type='file' name="identityDocument" class="btn btn-block btn-primary btn-rounded" id="imgInp" />

								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 margin-bottom-sm-30">
						<div class="thumbnail">
							<h4 class="thumbnail-title" style="text-align: center;"><?php echo $loc->label("Proof of address");?></h4>
							<div id="imgInpArea2" style="text-align: center;"><i id="imgInpAreaIco2" class="fa fa-street-view" style="font-size: 120px;"></i><img id="imgInpAreaPic2" style="max-height: 260px; max-width: 200;" style="display:none"/></div>
							<div class="caption padding-20" style="text-align: center;">
								<p style="text-align: center;"><?php echo $loc->label("Documentpublish2"); ?></p>
								<div id="form2" runat="server" class="form-group">

								<p id="imgInpName2" style="text-align: center; font-weight: bold;"><?php echo(($proofAddress != "") ? "<span style='color: green;'><i class='fa fa-check-circle' style='font-size: 20px; margin-right: 5px;'></i>". $loc->label("Uploaded") ."</span>" : "");?></p>
								<button type="button" onClick="$('#imgInp2').click();" class="btn btn-block btn-primary btn-rounded"><?php echo $loc->label("Upload"); ?></button>
								<input style="display:none"  type='file' name="proofAddress" class="btn btn-block btn-primary btn-rounded" id="imgInp2" />

								</div>
							</div>
						</div>
					</div>
					
</div>


<div class="form-group margin-top-30"> 
												
											<center><div id="buttons"><?php if($formComplete == 1) {?><a href="javascript: change(1);"><button type="button" class="btn btn-warning btn-lg"><?php echo $loc->label("Close");?></button></a><? } ?>	<a href="javascript: submitForm();"><button id="submit" type="button" class="btn btn-primary btn-lg"><?php echo (($formComplete == 1) ? $loc->label("Save") : $loc->label("Submit"));?></button></a></div> <div id="loader" style="display: none;"><img src="images/loader.gif" /></div></center>  
												
											</div>   

											
											
											
<input type="hidden" id="day" name="day" value="<?php echo $day; ?>" />
<input type="hidden" id="month" name="month" value="<?php echo $month; ?>" />
<input type="hidden" id="year" name="year" value="<?php echo $year; ?>" />
<input id="fbsocialID" type="hidden" name="fbsocialID" value="<?php echo $facebook; ?>"/>
<input id="twsocialID" type="hidden" name="twsocialID" value="<?php echo $twitter; ?>"/>
<input id="gosocialID" type="hidden" name="gosocialID" value="<?php echo $youtube; ?>"/>
<input id="newID" type="hidden" name="newID" value="<?php if($formComplete == 1) { echo $rowID; } else { echo 0; } ?>"/>


</form>


<script src="../Library/bootstrap-3.3.6/plugins/jquery.maskedinput.js" type="text/javascript"></script>

<script>
<?php if($formComplete == 1) { ?>
$(document).ready(function () {
	
	$("#nationality").val(<?php echo $nationality; ?>); 
	$("#nationality").trigger("chosen:updated");
	$("#countryAddress").val(<?php echo $country; ?>);
	$("#countryAddress").trigger("chosen:updated");
	$("#category").val(<?php echo $category; ?>);
	$("#category").trigger("chosen:updated");
	$("#language").val(<?php echo $language; ?>);
	$("#language").trigger("chosen:updated");

	
<?php echo(($twitter != "" && $twitter != 0) ? "document.getElementById('twCheck').checked = true;" : "document.getElementById('twCheck').checked = false;"); ?>
<?php echo(($youtube != "" && $youtube != 0) ? "document.getElementById('goCheck').checked = true;" : "document.getElementById('goCheck').checked = false;"); ?>


	
});


<? } ?>
function submitForm(){
	
	$("#buttons").hide();
	$("#loader").show();
	
	var formDataFile = new FormData(); 
	
	var formData = $("form").serializeArray();
	
	for (var i=0; i<formData.length; i++)  
		formDataFile.append(formData[i].name, formData[i].value);

	formDataFile.append('identityDocument', $('#imgInp')[0].files[0]); 
	formDataFile.append('proofAddress', $('#imgInp2')[0].files[0]); 
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=bePublisher",
			data: formDataFile,
			processData: false,  // tell jQuery not to process the data
			contentType: false,  // tell jQuery not to set contentType
			success: function cevap(e){
				if(!(e.indexOf("XXxpublisher!_%+%+XXxzzxoksssX") > -1)){  
					
					$("#loader").hide();
					$("#buttons").show();
					
					$("#alert-text").html(e);
					$("#alert").show();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					
					
					
			   }else{
				   window.location.href = "/bepublisher?form=on";
			   }
			}
			})
}

jQuery(function($){

$("#phone").mask("(999) 999 99 99");

/*$("#phone").change(function() {

var str = $("#phone").val();

str = str.replace(")", "");
str = str.replace("(", "");
str = str.replace(/ /g,'');
str = "+90" + str;
document.getElementById("phone").defaultValue = str;
alert(str);


});*/
});

<?php if($formComplete == 1) { ?>
	
<?php if($status == 2 or $status == 0) { ?>
	
function cancel(id){
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=cancelPublisherApp",
			data: {appID: id, userID: <?php echo $userid; ?>},
			success: function cevap(e){
				
				if(e.indexOf("ok") > -1){ 

				   window.location.href = "/bepublisher";
			 
				} else {
					
					alert("ERROR");
					
				}
			}
			})
}

<? } ?>
	
<?php if($status == 2) { ?>

function change(w){
	
	if(w == 1) {
		
		$("#form").hide();
		$("#summary").show();

	} else if(w == 0) {
		
		$("#summary").hide();
		$("#form").show();
		
	}
	
	
	
}

<? } ?>



<? } ?>

$.post("/BL/socials.php?run=pages",{},function(data){
			
			$('#loading').hide();
			$('#addpost-panel').show();
			$('#smartListButton').hide();
			$('#smartList').html(data);
			
			<?php if($facebook != "" && $facebook != 0) { ?>
    $('#smartList input').each(function() {
        if ($(this).val() == <?php echo $facebook; ?>) {
          $(this).prop('checked', true); 
        }
       
    });
	<? } ?>
		
		});
		
		
function smartList(e,progress){
		
		$('#addpost-panel').hide();
		$('#loading').show();  
		
		$('.sourceList').click(function() {
			if ($(this).is(":checked")) {
				var group = "input:checkbox[name='" + $(this).attr("name") + "']";
				$(group).prop("checked", false);
				$(this).prop("checked", true);
				document.getElementById("fbsocialID").value = e;
			} else {
				$(this).prop("checked", false);
				document.getElementById("fbsocialID").value = "";  
			}
		});
		
	}



$("#twCheck").click(function(){
	if(document.getElementById("twCheck").checked == true) {
		$("#twsocialID").val(<?php echo $usTwitter->ID; ?>);
	} else {
		$("#twsocialID").val("");
	}
});

$("#goCheck").click(function(){
	if(document.getElementById("goCheck").checked == true) {
		$("#gosocialID").val(<?php echo $usGoogle->ID; ?>);
	} else {
		$("#gosocialID").val("");
	}
});

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
		if(input == document.getElementById("imgInp")) {
			$('#imgInpAreaIco').hide();
			$('#imgInpAreaPic').attr('src', e.target.result);
			$('#imgInpAreaPic').show();
		} else if(input == document.getElementById("imgInp2")) {
			$('#imgInpAreaIco2').hide();
			$('#imgInpAreaPic2').attr('src', e.target.result);
			$('#imgInpAreaPic2').show();
		}
           
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function () {

$("#imgInp").change(function(){
var path = document.getElementById("imgInp").value;
var fileName = path.match(/[^\/\\]+$/);
$("#imgInpName").html(fileName);
readURL(this);
});

$("#imgInp2").change(function(){
var path = document.getElementById("imgInp2").value;
var fileName = path.match(/[^\/\\]+$/);
$("#imgInpName2").html(fileName);
readURL(this);
});

});

$("#dayS").change(function(){
	document.getElementById("day").value = $("#dayS").val();
});

$("#monthS").change(function(){
	document.getElementById("month").value = $("#monthS").val();
});

$("#yearS").change(function(){
	document.getElementById("year").value = $("#yearS").val(); 
});
</script>



<? } ?>
							
						</div>

<?php if($form != "on") { ?>
						<section class="bg subtitle-lg">
			<div class="container">
				<h2 style="color: #9E9E9E;"><?php echo $loc->label("bePublisherApplyText");?></h2>  
				<a <?php if(isset($_SESSION["userID"])) { ?>href="bepublisher?form=on"<? } else { ?>data-toggle="modal" data-target=".bs-modal"<? } ?> id="buttonWhich" class="btn btn-default btn-lg btn-icon-right" style="color: #9E9E9E; border: 1px solid #9E9E9E !important;"><?php echo(($formComplete != 1) ? $loc->label("Apply") . "<i class='fa fa-user-plus'></i>" : $loc->label("View your application")); ?></a>
			
			<?php if(!isset($_SESSION["userID"])) { ?>
			
			<div class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $loc->label("AlertPusblishFormTitle");?></h4>
							</div>
							<div class="modal-body">
								
								<p style="text-align: center;"><?php echo $loc->label("AlertPusblishForm");?></p>   
																
							</div>
							<div class="modal-footer">

								
									<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>
	
									<a href="login?pre=bepublisher?form=on" class="btn btn-primary"><?php echo $loc->label("Login");?></a>

								
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
<? } ?>
			</div>  
		</section>
		
		

          

<? } ?>
						
					</div>
					
				
					
				</div>		
			</div>
		</section>

		

		<script src="../Library/bootstrap-3.3.6/plugins/jquery/chosen.jquery.js"
	type="text/javascript"></script>
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
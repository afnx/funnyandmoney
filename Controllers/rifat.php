<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");

$errorDetected= 0;
		$errorMsg= '';
		$user = new users($userID);
		$post = new posts($_POST['postID']);
		$actionID= isset($_POST['actionID']) ? $_POST['actionID'] : '';
		$_POST['shareOn']= isset($_POST['shareOn']) ? $_POST['shareOn'] : '';
		$platformID = $_POST['platformID'];
		$runsql = new \data\DALProsess ();
		
		$actionID = $runsql->checkInjection($actionID);
		$platformID = $runsql->checkInjection($platformID);
		$_POST['shareOn'] = $runsql->checkInjection($_POST['shareOn']);
		
		//run
		if($userID == $post->userID){
			$errorDetected= 1;
			$errorMsg.= $loc->label("You cannot earn points from your own post") . "<br/>";
		}else{
			switch( $platformID ){
				case 1:
					$us = userSocials::getUserSocialFromID ( $userID, 1 );
					if($us->ID < 1){
						$errorDetected= 1;
						$errorMsg.= $loc->label("Link your facebook account first") . "<br/>"; 
					}else{
						$sql = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID='$actionID'";
						$runsql->executenonquery ( $sql, NULL, false );
						if ($runsql->recordCount == 1) {
							$errorDetected= 1;
							$errorMsg.= $loc->label("You have already earned point for this post") . "<br/>"; 
						}else{
							$social= new zuckerberg($userID,$_POST['postID']);
							switch($actionID){
								case 1:
									$action= $social->like();
									if(isset($action[0]) AND $action[0] == 'error'){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowLike == NULL){
											$post->nowLike=1;
										}else{
										$post->nowLike+=1;
										}
									}
									break;
								case 2:
								if(
								($post->oneSharerFollowerCount == 30 AND ($us->friendsCount < 100 AND $us->followerCount < 100)) OR 
								($post->oneSharerFollowerCount == 31 AND ($us->friendsCount < 300 AND $us->followerCount < 300)) OR 
								($post->oneSharerFollowerCount == 32 AND ($us->friendsCount < 500 AND $us->followerCount < 500)) OR 
								($post->oneSharerFollowerCount == 33 AND ($us->friendsCount < 1000 AND $us->followerCount < 1000)) OR 
								($post->oneSharerFollowerCount == 34 AND ($us->friendsCount < 3000 AND $us->followerCount < 3000)) OR 
								($post->oneSharerFollowerCount == 35 AND ($us->friendsCount < 5000 AND $us->followerCount < 5000)) OR  
								($post->oneSharerFollowerCount == 36 AND ($us->friendsCount < 10000 AND $us->followerCount < 10000)) OR 
								($post->oneSharerFollowerCount == 37 AND ($us->friendsCount < 30000 AND $us->followerCount < 30000)) OR 
								($post->oneSharerFollowerCount == 38 AND ($us->friendsCount < 50000 AND $us->followerCount < 50000)) OR 
								($post->oneSharerFollowerCount == 39 AND ($us->friendsCount < 100000 AND $us->followerCount < 100000)) OR 
								($post->oneSharerFollowerCount == 40 AND ($us->friendsCount < 300000 AND $us->followerCount < 300000)) OR
								($post->oneSharerFollowerCount == 333 AND ($us->friendsCount < 500000 AND $us->followerCount < 500000)) OR 
								($post->oneSharerFollowerCount == 334 AND ($us->friendsCount < 1000000 AND $us->followerCount < 1000000)) OR 
								($post->oneSharerFollowerCount == 335 AND ($us->friendsCount < 3000000 AND $us->followerCount < 3000000)) OR 
								($post->oneSharerFollowerCount == 336 AND ($us->friendsCount < 5000000 AND $us->followerCount < 5000000)) OR  
								($post->oneSharerFollowerCount == 337 AND ($us->friendsCount < 7000000 AND $us->followerCount < 7000000)) OR 
								($post->oneSharerFollowerCount == 338 AND ($us->friendsCount < 10000000 AND $us->followerCount < 10000000))
								){
									$errorDetected= 1;
									$errorMsg.= $loc->label("You do not have enough friends or followers to be able to share this post.") . "<br/>";
								}else{
									$action= $social->share();
									if(isset($action[0]) AND $action[0] == 'error'){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowShare==NULL){
											$post->nowShare=1;
										}else{
											$post->nowShare+=1;
										}
									}
								}
									break;
								case 3:
									if($post->nowFollow==NULL){
											$post->nowFollow=1;
										}else{
											$post->nowFollow+=1;
										}
									break;
							}
						}
					}
					break;
				case 2:
					$us = userSocials::getUserSocialFromID ( $userID, 2 );
					if($us->ID < 1){
						$errorDetected= 1;
						$errorMsg.= $loc->label("Link your twitter account first") . "<br/>";
					}else{
						$sql = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID='$actionID'";
						$runsql->executenonquery ( $sql, NULL, false );
						if ($runsql->recordCount == 1) {
							$errorDetected= 1;
							$errorMsg.= $loc->label("You have already earned point for this post") . "<br/>"; 
						}else{
							$social= new twitter($userID,$_POST['postID']);
							switch($actionID){
								case 1:
									if(isset($social->like()->errors)){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowLike==NULL){
											$post->nowLike=1;
										}else{
											$post->nowLike+=1;
										}
									}
									break;
								case 2:
									if(
								($post->oneSharerFollowerCount == 30 AND ($us->friendsCount < 100 AND $us->followerCount < 100)) OR 
								($post->oneSharerFollowerCount == 31 AND ($us->friendsCount < 300 AND $us->followerCount < 300)) OR 
								($post->oneSharerFollowerCount == 32 AND ($us->friendsCount < 500 AND $us->followerCount < 500)) OR 
								($post->oneSharerFollowerCount == 33 AND ($us->friendsCount < 1000 AND $us->followerCount < 1000)) OR 
								($post->oneSharerFollowerCount == 34 AND ($us->friendsCount < 3000 AND $us->followerCount < 3000)) OR 
								($post->oneSharerFollowerCount == 35 AND ($us->friendsCount < 5000 AND $us->followerCount < 5000)) OR  
								($post->oneSharerFollowerCount == 36 AND ($us->friendsCount < 10000 AND $us->followerCount < 10000)) OR 
								($post->oneSharerFollowerCount == 37 AND ($us->friendsCount < 30000 AND $us->followerCount < 30000)) OR 
								($post->oneSharerFollowerCount == 38 AND ($us->friendsCount < 50000 AND $us->followerCount < 50000)) OR 
								($post->oneSharerFollowerCount == 39 AND ($us->friendsCount < 100000 AND $us->followerCount < 100000)) OR 
								($post->oneSharerFollowerCount == 40 AND ($us->friendsCount < 300000 AND $us->followerCount < 300000)) OR
								($post->oneSharerFollowerCount == 333 AND ($us->friendsCount < 500000 AND $us->followerCount < 500000)) OR 
								($post->oneSharerFollowerCount == 334 AND ($us->friendsCount < 1000000 AND $us->followerCount < 1000000)) OR 
								($post->oneSharerFollowerCount == 335 AND ($us->friendsCount < 3000000 AND $us->followerCount < 3000000)) OR 
								($post->oneSharerFollowerCount == 336 AND ($us->friendsCount < 5000000 AND $us->followerCount < 5000000)) OR  
								($post->oneSharerFollowerCount == 337 AND ($us->friendsCount < 7000000 AND $us->followerCount < 7000000)) OR 
								($post->oneSharerFollowerCount == 338 AND ($us->friendsCount < 10000000 AND $us->followerCount < 10000000))
								){
									$errorDetected= 1;
									$errorMsg.= $loc->label("You do not have enough friends or followers to be able to share this post.") . "<br/>";
								}else{
									if($post->postType == 2){
										if(isset($social->retweet()->errors)){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowShare == NULL){
											$post->nowShare=1;
										}else{
											$post->nowShare+=1;
										}
									}
									}elseif($post->postType == 1){
										if(count($social->tweet($post->postUrl))==0){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowShare==NULL){
											$post->nowShare=1;
										}else{
											$post->nowShare+=1;
										}
									}
									}
								}
									break;
								case 3:
									$social->follow();
									if($post->nowFollow==NULL){
											$post->nowFollow=1;
									}else{
											$post->nowFollow+=1;
									}
									break;
							}
						}
					}
					break;
				case 4:
					$usFacebook = userSocials::getUserSocialFromID ( $userID, 1 );
					$usTwitter = userSocials::getUserSocialFromID ( $userID, 2 );
					$usYoutube = userSocials::getUserSocialFromID ( $userID, 4 );
					if(($actionID == 1 OR $actionID == 3) AND $usYoutube->ID < 1){
						$errorDetected= 1;
						$errorMsg.= $loc->label("Link your youtube account first") . "<br/>"; 
					}elseif($actionID == 2 AND $_POST['shareOn'] == 1 AND $usFacebook->ID < 1){
						$errorDetected= 1;
						$errorMsg.= $loc->label("Link your facebook account first") . "<br/>"; 
					}elseif($actionID == 2 AND $_POST['shareOn'] == 2 AND $usTwitter->ID < 1){
						$errorDetected= 1;
						$errorMsg.= $loc->label("Link your twitter account first") . "<br/>";
					}else{
						$sql = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID='$actionID'";
						$runsql->executenonquery ( $sql, NULL, false );
						if ($runsql->recordCount == 1) {
							$errorDetected= 1;
							$errorMsg.= $loc->label("You have already earned point for this post") . "<br/>"; 
						}else{
							
							switch($actionID){
								case 1:
									$social= new youtube($userID,$_POST['postID']);
									if($social->like()==0){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowLike==NULL){
											$post->nowLike=1;
										}else{
											$post->nowLike+=1;
										}
									}
									break;
								case 2:
									if($_POST['shareOn']== 1){
										if(
								($post->oneSharerFollowerCount == 30 AND ($usFacebook->friendsCount < 100 AND $usFacebook->followerCount < 100)) OR 
								($post->oneSharerFollowerCount == 31 AND ($usFacebook->friendsCount < 300 AND $usFacebook->followerCount < 300)) OR 
								($post->oneSharerFollowerCount == 32 AND ($usFacebook->friendsCount < 500 AND $usFacebook->followerCount < 500)) OR 
								($post->oneSharerFollowerCount == 33 AND ($usFacebook->friendsCount < 1000 AND $usFacebook->followerCount < 1000)) OR 
								($post->oneSharerFollowerCount == 34 AND ($usFacebook->friendsCount < 3000 AND $usFacebook->followerCount < 3000)) OR 
								($post->oneSharerFollowerCount == 35 AND ($usFacebook->friendsCount < 5000 AND $usFacebook->followerCount < 5000)) OR  
								($post->oneSharerFollowerCount == 36 AND ($usFacebook->friendsCount < 10000 AND $usFacebook->followerCount < 10000)) OR 
								($post->oneSharerFollowerCount == 37 AND ($usFacebook->friendsCount < 30000 AND $usFacebook->followerCount < 30000)) OR 
								($post->oneSharerFollowerCount == 38 AND ($usFacebook->friendsCount < 50000 AND $usFacebook->followerCount < 50000)) OR 
								($post->oneSharerFollowerCount == 39 AND ($usFacebook->friendsCount < 100000 AND $usFacebook->followerCount < 100000)) OR 
								($post->oneSharerFollowerCount == 40 AND ($usFacebook->friendsCount < 300000 AND $usFacebook->followerCount < 300000)) OR
								($post->oneSharerFollowerCount == 333 AND ($usFacebook->friendsCount < 500000 AND $usFacebook->followerCount < 500000)) OR 
								($post->oneSharerFollowerCount == 334 AND ($usFacebook->friendsCount < 1000000 AND $usFacebook->followerCount < 1000000)) OR 
								($post->oneSharerFollowerCount == 335 AND ($usFacebook->friendsCount < 3000000 AND $usFacebook->followerCount < 3000000)) OR 
								($post->oneSharerFollowerCount == 336 AND ($usFacebook->friendsCount < 5000000 AND $usFacebook->followerCount < 5000000)) OR  
								($post->oneSharerFollowerCount == 337 AND ($usFacebook->friendsCount < 7000000 AND $usFacebook->followerCount < 7000000)) OR 
								($post->oneSharerFollowerCount == 338 AND ($usFacebook->friendsCount < 10000000 AND $usFacebook->followerCount < 10000000))
								){
									$errorDetected= 1;
									$errorMsg.= $loc->label("You do not have enough friends or followers to be able to share this post.") . "<br/>";
								}else{
										$social= new zuckerberg($userID,$_POST['postID']);
										$social->share();
										if($post->nowShare==NULL){
											$post->nowShare=1;
										}else{
											$post->nowShare+=1;
										}
								}
										
									}elseif($_POST['shareOn'] == 2){
										if(
								($post->oneSharerFollowerCount == 30 AND ($usTwitter->friendsCount < 100 AND $usTwitter->followerCount < 100)) OR 
								($post->oneSharerFollowerCount == 31 AND ($usTwitter->friendsCount < 300 AND $usTwitter->followerCount < 300)) OR 
								($post->oneSharerFollowerCount == 32 AND ($usTwitter->friendsCount < 500 AND $usTwitter->followerCount < 500)) OR 
								($post->oneSharerFollowerCount == 33 AND ($usTwitter->friendsCount < 1000 AND $usTwitter->followerCount < 1000)) OR 
								($post->oneSharerFollowerCount == 34 AND ($usTwitter->friendsCount < 3000 AND $usTwitter->followerCount < 3000)) OR 
								($post->oneSharerFollowerCount == 35 AND ($usTwitter->friendsCount < 5000 AND $usTwitter->followerCount < 5000)) OR  
								($post->oneSharerFollowerCount == 36 AND ($usTwitter->friendsCount < 10000 AND $usTwitter->followerCount < 10000)) OR 
								($post->oneSharerFollowerCount == 37 AND ($usTwitter->friendsCount < 30000 AND $usTwitter->followerCount < 30000)) OR 
								($post->oneSharerFollowerCount == 38 AND ($usTwitter->friendsCount < 50000 AND $usTwitter->followerCount < 50000)) OR 
								($post->oneSharerFollowerCount == 39 AND ($usTwitter->friendsCount < 100000 AND $usTwitter->followerCount < 100000)) OR 
								($post->oneSharerFollowerCount == 40 AND ($usTwitter->friendsCount < 300000 AND $usTwitter->followerCount < 300000)) OR
								($post->oneSharerFollowerCount == 333 AND ($usTwitter->friendsCount < 500000 AND $usTwitter->followerCount < 500000)) OR 
								($post->oneSharerFollowerCount == 334 AND ($usTwitter->friendsCount < 1000000 AND $usTwitter->followerCount < 1000000)) OR 
								($post->oneSharerFollowerCount == 335 AND ($usTwitter->friendsCount < 3000000 AND $usTwitter->followerCount < 3000000)) OR 
								($post->oneSharerFollowerCount == 336 AND ($usTwitter->friendsCount < 5000000 AND $usTwitter->followerCount < 5000000)) OR  
								($post->oneSharerFollowerCount == 337 AND ($usTwitter->friendsCount < 7000000 AND $usTwitter->followerCount < 7000000)) OR 
								($post->oneSharerFollowerCount == 338 AND ($usTwitter->friendsCount < 10000000 AND $usTwitter->followerCount < 10000000))
								){
									$errorDetected= 1;
									$errorMsg.= $loc->label("You do not have enough friends or followers to be able to share this post.") . "<br/>";
								}else{
										$social= new twitter($userID,$_POST['postID']);
										$social->tweet();
										if($post->nowShare==NULL){
											$post->nowShare=1;
										}else{
											$post->nowShare+=1;
										}
										
									}
									}
									break;
								case 4:
									if(isset($_SESSION['youtubePost']) AND time()-$_SESSION['youtubePost']['time']>=19 AND $_SESSION['youtubePost']['ID'] == $_POST['postID']){
										if($post->nowView==NULL){  
											$post->nowView=1;
										}else{
											$post->nowView+=1;
										}
									}else{
										$errorDetected= 1;
										$errorMsg.= $loc->label("Please watch one video at a time") . "<br/>";
									}
									unset($_SESSION['youtubePost']);
									break;
								case 3:
									$social= new youtube($userID,$_POST['postID']);
									if($social->subscribe()==0){
										$errorDetected= 1;
										$errorMsg.= $loc->label("This action was unsuccessful") . "<br/>";
									}else{
										if($post->nowFollow==NULL){
											$post->nowFollow=1;
										}else{
											$post->nowFollow+=1;
										}
									}
									break;
							}
						}
					}
					break;
			}
		}
		if($errorDetected == 0){
			
		if($actionID == 2) {
			
			$cost = new cost();
			
		} else {
			
			$cost = cost::getCostAll ( $actionID, $platformID );
			
		}
			
		//$cost = new cost ($actionID); 
		$usCost = userSocials::getUserSocialFromID ( $userID, ($actionID==2?($platformID!=4?$platformID:$_POST['shareOn']):$platformID) );
		$followerfriend=($usCost->followerCount >= $usCost-> friendsCount?$usCost->followerCount:$usCost-> friendsCount);
		$rate= currency::getCurrency ('total', 'usersShare')->monetaryValue;
		//balance
		$balance = new balance();
		$balance->userID = $userID;
		$balance->actionID = $actionID;
		$balance->postID = $_POST['postID'];
		$balance->point = str_replace(",",".",($actionID!=2?$cost->point*$rate:$cost->getCost('Share',$followerfriend)*$rate));
		$balance->socialID = $post->socialID;
		$status= $balance->save();
		//reference
		$refExists= 0;
		$status2= 1;
		if($user->referrerID != NULL AND $user->referrerID > 0){
			$ref= new users($user->referrerID);
			if($ref->referrerON == 1){
				$refExists=1;
				$refRate= currency::getCurrency ('usersShare', 'referrerShare')->monetaryValue;
				$refBalance = new balance();
				$refBalance->userID = $user->referrerID;
				$refBalance->actionID = 5;
				$refBalance->postID = $_POST['postID'];
				$refBalance->point = str_replace(",",".",($actionID!=2?$cost->point*$rate:$cost->getCost('Share',$followerfriend)*$rate)*$refRate);
				$refBalance->socialID = $post->socialID;
				$status2= $refBalance->save();
				
				$ref->balance= str_replace(",",".",($ref->balance+$refBalance->point));
			}
		}
		//user->balance
		$user->balance= str_replace(",",".",($user->balance+$balance->point));

		if($status>0 AND $status2>0){
			$post->save();
			$user->save();
			if($refExists==1){
				$ref->save();
			}
			echo '{"errorDetected": false, "errorMsg":null, "earnedPoints":'.str_replace(",",".",($actionID!=2?$cost->point*$rate:$cost->getCost('Share',$followerfriend)*$rate)).', "newBalance": '.bcdiv($user->balance, 1, 2).'}';
		}else{
			echo '{"errorDetected": true, "errorMsg":"Database record could not be updated", "earnedPoints":null}';  
		}		
		}else{
			echo '{"errorDetected": true, "errorMsg":"'.$errorMsg.'", "earnedPoints":null}';
		}
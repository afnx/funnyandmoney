<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
require_once "Tables/posts.php";
require_once "Tables/platforms.php";
require_once "Tables/balance.php";
require_once "Tables/users.php";
require_once "Tables/gifts.php";
require_once "Tables/giftRequests.php";
require_once "Tables/localization.php";
require_once "Tables/definitions.php";
require_once "Tables/admins.php";
require_once "functions.php";


if(isset($_SERVER['HTTP_REFERER'])) {
$pos = strpos($_SERVER['HTTP_REFERER'],getenv('HTTP_HOST'));
} else {
	$pos=false;
}
if($pos===false) {
	
  die('Restricted access');
  exit;
	
} else if($_SESSION['adminID']) {

$adminA = admins::checkRank ( $_SESSION['userID'], "A");
$adminSM = admins::checkRank ( $_SESSION['userID'], "SM");
$adminM = admins::checkRank ( $_SESSION['userID'], "M");

if(isset($_POST['tableN'])) {
	
	$table = $_POST['tableN'];
} 


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
	
<?php switch($table) {   
	
case "posts" :  

if(isset($_POST['userID']) or !empty($_POST['userID'])) {
	$userID=$_POST['userID'];
} else {
	$userID=0;
}
if(isset($_POST['limit']) or !empty($_POST['limit'])) {
	$limit=$_POST['limit'];
} else {
	$limit=0;
}
if(isset($_POST['search'])) {  
	$search=$_POST['search'];
} else {
	$search="";
}
if(isset($_POST['platformID']) or !empty($_POST['platformID'])) {
	$platformID=$_POST['platformID'];
} else {
	$platformID=0;
} 
if(isset($_POST['status']) or !empty($_POST['status'])) {
	$status=$_POST['status'];
} else {
	$status=1;
} 
$post = new posts();
$result = $post->getAlPosts($userID,$limit,$search,$platformID,$status);

while ($row=mysqli_fetch_array($result)) {
						
											$platform = new platforms($row["platformID"]);
											$userPostS = new users($row["userID"]);  
											if($row['platformID'] == 4){
												$imgsrc= $row["imagePath"];
											}else{
												$imgsrc= project::uploadPath.$row["imagePath"]; 
											}
				?>
									
										<tr style="cursor:pointer;" class='clickable-row' data-href='admin?tab=editpost&postID=<?php echo $row['ID']; ?>'>
											<td><?php echo $row["ID"];?></td>
											<td><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" style="height: 60px;" alt="<?php echo $row["title"];?>"></td>
											<td><?php echo $row["title"];?></td>
											<td><?php custom_echo($row['description'], 50);?></td>    
											<td><?php echo $platform->platform;?></td>
											<td><?php echo $row['createddate_'];?></td>  
											<td><i class="fa fa-user"></i> <?php echo $userPostS->fullName;?></td>
									                    
										</tr> 

										<?php } ?>
										
									
<?php if (mysqli_num_rows( $result ) == 0) {

	echo "<p>Gönderi bulunamadı.</p>";
  
}	
	
break;

case "users" : 

if(isset($_POST['limit']) or !empty($_POST['limit'])) {
	$limit=$_POST['limit'];
} else {
	$limit=0;
}
if(isset($_POST['search'])) {  
	$search=$_POST['search'];
} else {
	$search="";
}
if(isset($_POST['country']) or !empty($_POST['country'])) {
	$country=$_POST['country'];
} else {
	$country=0;
}
if(isset($_POST['age']) or !empty($_POST['age'])) {
	$age=$_POST['age'];
} else {
	$age=0;
} 
if(isset($_POST['gender']) or !empty($_POST['gender'])) {
	$gender=$_POST['gender'];
} else {
	$gender=0;
} 
if(isset($_POST['status']) or !empty($_POST['status'])) {
	$status=$_POST['status'];
} else {
	$status=1;
} 
if(isset($_POST['sort']) or !empty($_POST['sort'])) {
	$sort=$_POST['sort'];
} else {
	$sort=1;
} 
$user = new users();
$result = $user->getAllUsersFilter($limit,$search,$country,$age,$gender,$status,$sort);

while ($row=mysqli_fetch_array($result)) { 
	
	$gender = new definitions( $row['gender'] );
	
	?>
									
										<tr style="cursor:pointer;" class='clickable-row' data-href='admin?tab=edituser&userID=<?php echo $row['ID']; ?>'>
											<td><?php echo $row["ID"];?></td>
											<td><img src="<?php echo ($row['picture']!="") ? project::uploadPath."/userImg/".$row['picture'] : "../Assets/images/profile.jpg";?>" style="height: 60px;" alt="<?php echo $row["fullName"];?>"></td>
											<td><?php echo $row["fullName"];?></td> 
											<td><?php echo $row['email'];?></td>    
											<td><?php echo $row['username'];?></td> 
											<td><?php echo $row['birthDate'];?></td> 
											<td><?php echo evalLoc($gender->definition);?></td> 
											<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>	
											<td><?php echo number_format((float)$row['balance'], 2, '.', '');?>& </td>   
											<?php } ?>
											<td><?php echo $row['registerdate_'];?></td>    
										
									                    
										</tr> 

										<?php } ?>
									
<?php if (mysqli_num_rows( $result ) == 0) {

	echo "<p>Üye bulunamadı.</p>";
  
}

break;


case "gifts" :  

if(isset($_POST['provider']) or !empty($_POST['provider'])) {
	$provider=$_POST['provider'];
} else {
	$provider=0;
}
if(isset($_POST['limit']) or !empty($_POST['limit'])) {
	$limit=$_POST['limit'];
} else {
	$limit=0;
}
if(isset($_POST['search'])) {  
	$search=$_POST['search'];
} else {
	$search="";
}
if(isset($_POST['category']) or !empty($_POST['category'])) {
	$category=$_POST['category'];
} else {
	$category=0;
} 
if(isset($_POST['digital']) or !empty($_POST['digital'])) {
	$digital=$_POST['digital'];
} else {
	$digital=3;
} 
if(isset($_POST['zone']) or !empty($_POST['zone'])) {
	$zone=$_POST['zone'];
} else {
	$zone=3;
} 
if(isset($_POST['sort']) or !empty($_POST['sort'])) {
	$sort=$_POST['sort'];
} else {
	$sort=0;
} 
$gift = new gifts();
$result = $gift->getAdminGifts($provider,$limit,$search,$category,$digital,$zone,$sort); 

while ($row=mysqli_fetch_array($result)) {
						
											$category = new definitions($row["category"]);
										
				?>
									
										<tr style="cursor:pointer;" class='clickable-row' data-href='admin?tab=editgift&giftID=<?php echo $row['ID']; ?>'>
											<td><?php echo $row["ID"];?></td>
											<td><img src="<?php echo ($row["picture"]!="") ? project::uploadPath."/giftImg/".$row["picture"] : project::assetImages. "giftimage.jpg";?>" alt="<?php echo $row["name"];?>"></td>
											<td><?php echo $row["name"];?></td>
											<td><?php custom_echo($row['description'], 50);?></td>    
											<td><?php echo $row['price'];?></td>  
											<td><?php echo evalLoc($category->definition);?></td>   
											<td><?php echo $row['quantity'];?></td>  
											<td><?php echo $row['numberOfSales'];?></td>  
											<td><?php echo $row['provider'];?></td>  
											<td><?php echo (($row['isDigital'] == 1) ? "Evet" : "Hayır");?></td>  
											<td><?php if($row['availableZone']==0){echo "Uluslararası";} else if($row['availableZone']==1){echo "Türkiye";} else if($row['availableZone']==2){echo "Yurtdışı";} ?></td>  
											<td><?php echo $row['date_'];?></td>  
									                    
										</tr> 

										<?php } ?>
										
									
<?php if (mysqli_num_rows( $result ) == 0) {

	echo "<p>Ürün bulunamadı.</p>";
  
}	
	
break;


case "giftsRe" :  

if(isset($_POST['user']) or !empty($_POST['user'])) {
	$user=$_POST['user'];
} else {
	$user=0;
}
if(isset($_POST['limit']) or !empty($_POST['limit'])) {
	$limit=$_POST['limit'];
} else {
	$limit=0;
}
if(isset($_POST['search'])) {  
	$search=$_POST['search'];
} else {
	$search="";
}
if(isset($_POST['searchCargoNo'])) {  
	$searchCargoNo=$_POST['searchCargoNo'];
} else {
	$searchCargoNo="";
}
if(isset($_POST['product']) or !empty($_POST['product'])) {
	$product=$_POST['product'];
} else {
	$product=0;
} 
if(isset($_POST['delivery']) or !empty($_POST['delivery'])) {
	$delivery=$_POST['delivery'];
} else {
	$delivery=4;
} 

$giftRequest = new giftRequests();
$result = $giftRequest->getAdminGiftsReq($user,$limit,$search,$searchCargoNo,$product,$delivery); 

while ($row=mysqli_fetch_array($result)) {  
						
											$gift = new gifts($row["giftID"]);
											$userG = new users($row['userID']);
										
				?>
								
										<tr style="cursor:pointer; height: 60px; <?php if($row['deliveryStatus']==2) {?>background-color: #FDEDEA; border-color: #E6CECA; color: #986F68;<?php } ?>" class='clickable-row' data-href='admin?tab=editorder&orderID=<?php echo $row['ID']; ?>'>
											<td><?php echo $row["orderNo"];?></td>
											<td><?php echo $gift->ID;?></td>
											<td><?php echo $gift->name;?></td>  
											<td><?php echo (($gift->isDigital == 1) ? "Evet" : "Hayır");?></td> 
											<td><?php echo $row['price'];?></td>  
											<td><?php echo $row['userID'];?></td>  
											<td><?php echo $userG->fullName;?></td>  
											<td><?php echo $row['date_'];?></td>  
											<td><?php if($row['deliveryStatus']==0){echo '<b style="color: green;">Teslim Edildi</b>';} else if($row['deliveryStatus']==1){echo '<b style="color: grey;">Kargoya Verildi</b>';} else if($row['deliveryStatus']==2){echo '<b style="color: orange;">Bekliyor</b>';}else if($row['deliveryStatus']==3){echo '<b style="color: red;">İptal Edildi</b>';}?><?php if($row['preturn']==1){echo '<b style="color: red;">(İADE EDİLDİ)</b>';}?></td>  
											
									                    
										</tr>   
										
							

										<?php } ?>
										
									
<?php if (mysqli_num_rows( $result ) == 0) {

	echo "<p>Sipariş bulunamadı.</p>";
  
}	
	
break;

} ?>




<script>

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
});

</script>

<?php } ?>
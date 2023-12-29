<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/PHPMailer/class.phpmailer.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/PHPMailer/class.smtp.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/mailTemplates.php";


class Mail {  
	private $recipients = '';
	private $recipientsName = '';
	private $from = '';
	private $fromName;
	private $subject = '';
	private $body = '';
	private $attachment = '';
	private $recipientList =NULL;
	private $templateID;
	private $userID;
	private $addText;
	private $addID;
	

	public function __construct($recipients, $recipientsName, $from, $fromName, $subject, $body, $attachment, $recipientList = NULL,$templateID=1, $userID = NULL, $addText = NULL, $addID = NULL) {
		$this->recipients = $recipients;
		$this->recipientsName = $recipientsName;
		$this->from = $from;
		$this->fromName = $fromName;
		$this->subject = $subject;
		$this->body = $body;
		$this->attachment = $attachment;
		$this->recipientList = $recipientList;
		$this->templateID=$templateID;
		$this->userID = $userID;
		$this->addText = $addText;
		$this->addID = $addID;
	}

	public function sendMail() {
		$mail = new PHPMailer ( true ); // the true param means it will throw
		$mail->isSMTP(); // telling the class to use SendMail transport

		try {
			if ( is_null ( $this->recipientList )) {
				$mail->AddAddress ( $this->recipients, $this->recipientsName );
			} else {
				foreach($this->recipientList as $key=>$value)
				{
					$mail->AddAddress ( $value, $key );
				}
			} 
			
			if (!is_null($this->templateID)) {
				$template = new mailTemplates($this->templateID);
				$tl = str_replace("@subject", $this->subject, $template->body);
				$this->body=str_replace("@body", $this->body, $tl);
				
				//Add str_replace to change content of mailTemplate
				if($this->templateID == 1 && !is_null($this->userID)) {
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
				
					$user = new users( $this->userID );
					$loc = new localization ($user->language); 
				
					$this->body=str_replace("{@fullName}", $user->fullName, $this->body);
					$this->body=str_replace("{@email_code}", $user->email_code, $this->body);
					$this->body=str_replace("{@Hello}", $loc->label("Hello"), $this->body);
					$this->body=str_replace("{@SignUpEmailContent}", $loc->label("SignUpEmailContent"), $this->body);
					$this->body=str_replace("{@SignUpFastConfirm}", $loc->label("SignUpFastConfirm"), $this->body);
					$this->body=str_replace("{@SignUpClickHere}", $loc->label("SignUpClickHere"), $this->body);
					$this->body=str_replace("{@SignUpError}", $loc->label("SignUpError"), $this->body);
					$this->body=str_replace("{@About}", $loc->label("About"), $this->body);
					$this->body=str_replace("{@Contact}", $loc->label("Contact"), $this->body);
					$this->body=str_replace("{@Help}", $loc->label("Help"), $this->body);
				
				} else if($this->templateID == 2 && !is_null($this->userID)) {
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
				
					$user = new users( $this->userID );
					$loc = new localization ($user->language); 
					
					$this->body=str_replace("{@fullName}", $user->fullName, $this->body);
					$this->body=str_replace("{@email_code}", $user->email_code, $this->body);
					$this->body=str_replace("{@Hello}", $loc->label("Hello"), $this->body);
					$this->body=str_replace("{@ConfirmEmailContent}", $loc->label("ConfirmEmailContent"), $this->body);
					$this->body=str_replace("{@ConfirmFastConfirm}", $loc->label("ConfirmFastConfirm"), $this->body);
					$this->body=str_replace("{@ConfirmClickHere}", $loc->label("ConfirmClickHere"), $this->body);
					$this->body=str_replace("{@About}", $loc->label("About"), $this->body);
					$this->body=str_replace("{@Contact}", $loc->label("Contact"), $this->body);
					$this->body=str_replace("{@Help}", $loc->label("Help"), $this->body);
					
				}else if($this->templateID == 3 && !is_null($this->userID)) {
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
				
					$user = new users( $this->userID );
					$loc = new localization ($user->language); 
					
					$this->body=str_replace("{@fullName}", $user->fullName, $this->body);
					$this->body=str_replace("{@email_code}", $user->email_code, $this->body);
					$this->body=str_replace("{@Hello}", $loc->label("Hello"), $this->body);
					$this->body=str_replace("{@PasswordRecoveryContent}", $loc->label("PasswordRecoveryContent"), $this->body);
					$this->body=str_replace("{@user_email}", $user->email, $this->body);
					$this->body=str_replace("{@About}", $loc->label("About"), $this->body);
					$this->body=str_replace("{@Contact}", $loc->label("Contact"), $this->body);
					$this->body=str_replace("{@Help}", $loc->label("Help"), $this->body);
					
				}else if($this->templateID == 4 && !is_null($this->userID)) {
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/giftRequests.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/address.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
				
					
					$user = new users( $this->userID );
					$loc = new localization ($user->language); 
					$giftR = new giftRequests($this->addID);
					$gift = new gifts($giftR->giftID);
					$address = new address($giftR->addressID);
					$definition = new definitions($address->country);
					
					$this->body=str_replace("{@fullName}", $user->fullName, $this->body); 
					
					$giftString = "

						<tableab class='tableab' style='width: 100%;font-size: 16px;'> 
									<thead>
										<tr>
											<th><h3>".$loc->label("giftInfo")."</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>".$loc->label("Order Number").":</th>
											<td>".$giftR->orderNo."</td>
										</tr>
										<tr>
											<th>".$loc->label("Order Date").":</th>
											<td>".date((($user->language=="tr") ? "d/m/Y H:i" : "m/d/Y H:i"), strtotime( $giftR->date_ ))."</td>
										</tr>
										<tr>
											<th>".$loc->label("Product Name").":</th>
											<td>".$gift->name."</td>
										</tr>
									</tbody>    
							</tableab>

					
					";
						
					$this->body=str_replace("{@gift}", $giftString, $this->body);
					
					if($gift->isDigital != 1) {
						
						$addressString = "
						
							<tableab class='tableab' style='width: 100%;font-size: 16px;'> 
									<thead>
										<tr>
											<th><h3>".$loc->label("deliveryAddressInfo")."</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>".$loc->label("Address").":</th>
											<td>".$address->addressLine1."<br>".$address->addressLine2."</td>
										</tr>
										<tr>
											<th>".$loc->label("Region").":</th>
											<td>".$address->region."</td>
										</tr>
										<tr>
											<th>".$loc->label("Postal Code").":</th>
											<td>".$address->postalCode."</td>
										</tr>
										<tr>
											<th>".$loc->label("City").":</th>
											<td>".$address->city."</td>
										</tr>
										<tr>
											<th>".$loc->label("Country").":</th>
											<td>".evalLoc($definition->definition)."</td>
										</tr>
										<tr>
											<th>".$loc->label("Recipient Name").":</th>
											<td>".$address->recipientName."</td>
										</tr>
										<tr>
											<th>".$loc->label("Phone").":</th>
											<td>+".$address->phone."</td>
										</tr>
										

									</tbody>    
							</tableab>
						
						";
						
						$this->body=str_replace("{@address}", $addressString, $this->body);
						
					} else {
						
						$this->body=str_replace("{@address}", "", $this->body);
						
					}
					
					$this->body=str_replace("{@Hello}", $loc->label("Hello"), $this->body);
					$this->body=str_replace("{@message}", $this->addText, $this->body);
					$this->body=str_replace("{@bye}", $loc->label("goodByeMail"), $this->body);
					$this->body=str_replace("{@About}", $loc->label("About"), $this->body);
					$this->body=str_replace("{@Contact}", $loc->label("Contact"), $this->body); 
					$this->body=str_replace("{@Help}", $loc->label("Help"), $this->body);
					
				} else if($this->templateID == 5 && !is_null($this->userID)) {
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/giftRequests.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/digitalGiftCodes.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/address.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
				
					
					$user = new users( $this->userID );
					$loc = new localization ($user->language); 
					$giftR = new giftRequests($this->addID);
					$gift = new gifts($giftR->giftID);
					$address = new address($giftR->addressID);
					$definition = new definitions($address->country);
					$digitalCode = new digitalGiftCodes();
					$digital = mysqli_fetch_array ( $digitalCode->findGiftCode($this->addID) );
					
					$this->body=str_replace("{@fullName}", $user->fullName, $this->body); 
					
					$giftString = "

						<tableab class='tableab' style='width: 100%;font-size: 16px;'> 
									<thead>
										<tr>
											<th><h3>".$loc->label("giftInfo")."</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>".$loc->label("Order Number").":</th>
											<td>".$giftR->orderNo."</td>
										</tr>
										<tr>
											<th>".$loc->label("Order Date").":</th>
											<td>".date((($user->language=="tr") ? "d/m/Y" : "m/d/Y"), strtotime( $giftR->date_ ))."</td>
										</tr>
										<tr>
											<th>".$loc->label("Product Name").":</th>
											<td>".$gift->name."</td>
										</tr>
									</tbody>    
							</tableab>

					
					";
						
					$this->body=str_replace("{@gift}", $giftString, $this->body);			
					$this->body=str_replace("{@Hello}", $loc->label("Hello"), $this->body);
					$this->body=str_replace("{@message}", $this->addText, $this->body);
					$this->body=str_replace("{@code}", "<b style='font-size: 30px;'>" . $digital["giftCode"] . "</b><br/><br/><b>" . $loc->label("howtouse") . "</b><br/><br/>" . $digital["descriptionText"] . "<br/><br/><b>" . $loc->label("Expiration Date") . "</b><br/><br/>"  . date((($user->language=="tr") ? "d/m/Y H:i" : "m/d/Y H:i"), strtotime( $digital["expirationDate_"] )) , $this->body);
					$this->body=str_replace("{@bye}", $loc->label("goodByeMail"), $this->body);
					$this->body=str_replace("{@About}", $loc->label("About"), $this->body);
					$this->body=str_replace("{@Contact}", $loc->label("Contact"), $this->body);     
					$this->body=str_replace("{@Help}", $loc->label("Help"), $this->body);
					
				}
			}
			$mail->SetFrom ( $this->from, $this->fromName );
			$mail->AddReplyTo ( $this->from, $this->fromName );
			$mail->Subject = $this->subject;
			$mail->MsgHTML ($this->body);
			if ($this->attachment != "") {
				$mail->AddAttachment ( $this->attachment );
			}
			$mail->SMTPSecure = "ssl";
			$mail->SMTPAuth = true;
			$mail->Port = mailServer::port;
			$mail->Host = mailServer::host;
			$mail->Username = mailServer::fromEmail;
			$mail->Password = mailServer::password;
			$mail->CharSet = "utf-8";
			$mail->Encoding = "base64";
			//$mail->SMTPDebug=2;
			$mail->isHTML(true);
			if(!$mail->Send ())
			{
				echo "Message could not be sent. <p>";
				echo "Mailer Error: " . $mail->ErrorInfo;
				exit;
			};
			return true;
		} catch ( phpmailerException $e ) {
			echo $e->errorMessage ();
			return false; // Pretty error messages from PHPMailer
		} catch ( Exception $e ) {
			echo $e->errorMessage ();
			return false; // Pretty error messages from PHPMailer
		}
	}
}
?>

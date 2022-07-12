<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'PHPMailer-master/src/Exception.php';
// require 'PHPMailer-master/src/PHPMailer.php';
// require 'PHPMailer-master/src/SMTP.php';


// $mail = new PHPMailer(true);
// $mail->IsSMTP();
// $mail->Mailer = "smtp";
 
// $mail->SMTPDebug  = 1;  
// $mail->SMTPAuth   = TRUE;
// $mail->SMTPSecure = "tls";
// $mail->Port       = 587;
// $mail->Host       = "smtp.gmail.com";
// $mail->Username   = "zahradh57@gmail.com";
// $mail->Password   = "qzacatvilzajeztj";

// $mail->IsHTML(true);
// $mail->AddAddress("dekzahra@outlook.fr", "Dupont");
// $mail->SetFrom("zahradh57@gmail.com", "MYB");
// //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
// //$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
// $mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
// $content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";

// $mail->MsgHTML($content); 
// if(!$mail->Send()) {
//   echo "Error while sending Email.";
//   var_dump($mail);
// } else {
//   echo "Email sent successfully";
// }

// $to = "dekzahra@outlook.fr";
// $subject = "sujet du message";
// $message = "Vivamus suscipit tortor eget felis porttitor volutpat. Cras ultricies ligula sed magna dictum porta. Nulla quis lorem ut libero malesuada feugiat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Nulla porttitor accumsan tincidunt.";

// mail($to, $subject, $message);

// dd($this->getMail());
 //namespace App\Classe;

// use Mailjet\Client;
// use Mailjet\Resources;

 //class Mail
 //{
//     private $api_key = 'd622305c9987be98167555daddcf6555';
//     private $api_key_secret = '16de3c04e87885f68e961ea650d6bf3d';

 //    public function send($to_email, $to_name, $subject, $content)
 //   {
//         $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
//         //$mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);
//         $body = [
//             'Messages' => [
//                 [
//                     'From' => [
//                         'Email' => "dekzahra@outlook.fr",
//                         'Name' => "Move Your Body"
//                     ],
//                     'To' => [
//                         [
//                             'Email' => $to_email,
//                             'Name' => $to_name
//                         ]
//                     ],
//                     'TemplateID' => 3934360,
//                     'TemplateLanguage' => true,
//                     'Subject' => $subject,
//                     'Variables' => [
//                         'content'=> $content,

//                     ]
//                 ]
            
//             ]
//         ];
//         $response = $mj->post(Resources::$Email, ['body' => $body]);
//         $response->success();
  //   }
 //}


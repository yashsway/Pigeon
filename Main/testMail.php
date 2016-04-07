<?php
/**
 * This example shows sending a message using a local sendmail binary.
 */
require __DIR__ . '/vendor/autoload.php';
function mailScaffold($data){
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set PHPMailer to use the sendmail transport
    $mail->isSendmail();
    //Set who the message is to be sent from
    $mail->setFrom('pigeon@mcmaster.ca', 'HCS Help Desk');
    //Set an alternative reply-to address
    $mail->addReplyTo('housit1@mcmaster.ca', 'IT Assistant');
    //Set who the message is to be sent to
    $mail->addAddress($data["email"], 'HCS Staff');
    //Set the subject line
    $mail->Subject = $data["sub"];
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $content = $mail->msgHTML(file_get_contents($data["contentPath"]), dirname(__FILE__));
    $mail->Body = $content;
    //Replace the plain text body with one created manually (is this really needed?)
    //$mail->AltBody = '';
    //Attach a file
    //$mail->addAttachment('mailTemplates/addtocalendar.png');
    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "success";
    }
}
function ticketAuto(){
    session_start();
    $email = $_SESSION['userMail'];
    $sub = 'Ticket Confirmation';
    $ticket = $_POST['ticket'];
    $contentPath = 'mailTemplates/ticketauto.html.php';

    $out = array(
        "email" => $email,
        "sub" => $sub,
        "ticket" => $ticket,
        "contentPath" => $contentPath,
    );
    mailScaffold($out);
}

if((isset($_REQUEST['reqType']))==1){
    if($_REQUEST['reqType']==0){
        ticketAuto();
    }
}
?>

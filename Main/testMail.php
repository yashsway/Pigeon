<?php
/**
 * This example shows sending a message using a local sendmail binary.
 */
//Autoload PHP dependencies using Composer's autoload.php
require __DIR__ . '/vendor/autoload.php';
require_once 'simple_html_dom.php';
//Error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function getInnerHtml(DOMNode $node) {
    $innerHTML= '';
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveHTML( $child );
    }
    return $innerHTML;
}
function mailScaffold($data,$html){
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
    //file_get_contents($data["contentPath"])
    $content = $mail->msgHTML($html, dirname(__FILE__));
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
function modifyTemplate($data,$callback){
    //Load email template from file.
    $html = file_get_html($data["contentPath"]);
    $html->find('span[id=ticket]',0)->innertext = $data["ticket"];
    if(is_callable($callback)){
        call_user_func($callback,$data,$html);
    }
}
function ticketAuto(){
    session_start();
    $email = $_SESSION['userMail'];
    $sub = 'Ticket Confirmation';
    $ticket = $_REQUEST['ticket'];
    $contentPath = 'mailTemplates/ticketauto.html';

    $out = array(
        "email" => $email,
        "sub" => $sub,
        "ticket" => $ticket,
        "contentPath" => $contentPath,
    );
    modifyTemplate($out,'mailScaffold');
}

if((isset($_REQUEST['reqType']))==1){
    if($_REQUEST['reqType']==0){
        ticketAuto();
    }
}
?>

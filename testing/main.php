<?php

/* 
 * Script to test the AWS emailing functionality
 * Make sure to fill in any empty variables appropriately.
 */

require_once(__DIR__ . '/../vendor/autoload.php');

$config = array(
    'access_key_id', 
    'secret_access_key'
);

$aws_key        = '';
$aws_secret     = '';
$from_email     = '';
$reply_to_name  = '';
$reply_to_email = '';
$aws_region     = \iRAP\Emailers\SesRegion::createEuRegion();

$to_name = '';
$to_email = '';
$subject = "SES test";
$plaintextBody = "This is a plaintext body." . PHP_EOL . "This should be on a new line.";
$htmlBody = "This is a html body.<br /> This should be on a new line.";



$emailer = new iRAP\Emailers\AwsEmailer(
    $aws_key, 
    $aws_secret, 
    $aws_region, 
    $from_email, 
    $reply_to_name, 
    $reply_to_email
);



$emailer->send($to_name, $to_email, $subject, $plaintextBody, $htmlFormat=false);
$emailer->send($to_name, $to_email, $subject, $htmlBody, $htmlFormat=true);



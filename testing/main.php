<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/Settings.php');

# Test that phpmailer works 
# (you will need to log into your email account and check that you actually get the email)
$mailer = new iRAP\Emailers\PhpMailerEmailer(
    SMTP_HOST, 
    SMTP_USER, 
    SMTP_PASSWORD, 
    SMTP_TLS_OR_SSL, 
    SMTP_FROM_EMAIL, 
    $fromName = 'PhpMailerEmailer TestUser', 
    $smtpPort = 587,
    $replyToEmail = 'noreply@noaddress.com',
    $replyToName = 'noreply'
);

$mailer->send(
    'toName', 
    TO_EMAIL, 
    $subject='PhpMailerEmailer Test', 
    $body = '<h2>Test</h2><p>Did you get this?</p>'
);
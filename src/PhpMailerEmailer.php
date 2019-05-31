<?php

/* 
 * This class wraps around PHPMailer to send an email using SMTP. You could just use PHPMailer 
 * directly, but if you go with this, you stick to an interface that allows you to swap emailer
 * super easily in future.
 */

namespace iRAP\Emailers;

class PhpMailerEmailer implements EmailerInterface
{
    private $m_smtpHost;
    private $m_smtpUser;
    private $m_smtpPassword;
    private $m_tlsOrSsl;
    private $m_fromEmail;
    private $m_fromName;
    private $m_smtpPort;
    private $m_replyToEmail;
    private $m_replyToName;
    
    
    /**
     * Create an emailer object that wraps around PhpMailer to send an email through SMTP.
     * @param string $smtpHost - the host of the SMTP provider.
     * @param string $smtpUser - username or email address for authenticating with SMTP provider.
     * @param type $smtpPassword - password for authenticating with SMTP provider.
     * @param type $tlsOrSsl - should be 'tls' or 'ssl' for how you send emails
     * @param string $fromEmail - who the email should appear as if its from.
     * @param string $fromName - the name that should appears as the email is from.
     * @param int $smtpPort - the port to connect to the SMTP provider with (defaults to 587)
     * @param type $replyToEmail - optionally specify where the user should reply to with. If not
     *                             provided then this will default to $fromEmail
     * @param type $replyToName - the name of the person to reply to. If $replyToEmail was not
     *                          - provided then this will default to $fromName
     */
    public function __construct($smtpHost, $smtpUser, $smtpPassword, $tlsOrSsl, $fromEmail, $fromName, $smtpPort=587, $replyToEmail=null, $replyToName=null)
    {
        $tlsOrSsl = strtolower($tlsOrSsl);
        
        if (!in_array($tlsOrSsl, array('tls', 'ssl')))
        {
            throw new Exception("PhpMailerEmailer tlsOrSsl needs to be set to 'tls' or 'ssl'");
        }
        
        if ($replyToEmail == null)
        {
            $replyToEmail = $fromEmail;
            $replyToName = $fromName;
        }
        
        $this->m_smtpHost = $smtpHost;
        $this->m_smtpUser = $smtpUser;
        $this->m_smtpPassword = $smtpPassword;
        $this->m_tlsOrSsl = $tlsOrSsl;
        $this->m_fromEmail = $fromEmail;
        $this->m_fromName = $fromName;
        $this->m_smtpPort = $smtpPort;
        $this->m_replyToEmail = $replyToEmail;
    }
    
    
    /**
     * Send an email.
     * @param string $to_name - name of the person to send email to
     * @param string $to_email - email address to send the email to
     * @param string $subject - subject of the email
     * @param string $message - plaintext or html body of the email.
     * @param bool $html_format - whether the email is html or not (defaults to true)
     * @throws \Exception - if failed to send the email for whatever reason.
     */
    public function send($to_name, $to_email, $subject, $message, $html_format = true) 
    {
        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        $mailer->isSMTP();
        $mailer->Host = $this->m_smtpHost;
        $mailer->SMTPAuth = true;
        $mailer->Username = $this->m_smtpUser;
        $mailer->Password = $this->m_smtpPassword;
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = $this->m_smtpPort;
        
        $mailer->setFrom($this->m_fromEmail, $this->m_fromName);
        $mailer->addAddress($to_email, $to_name);
        $mailer->addReplyTo($this->m_replyToEmail, $this->m_replyToName);
        $mailer->isHTML($html_format);
        
        $mailer->Subject = $subject;
        $mailer->Body    = $message;
        $mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
        if (!$mailer->send()) 
        {
            throw new \Exception("Failed to send email: " . $mailer->ErrorInfo);
        } 
    }
}




<?php

namespace iRAP\Emailers;

/*
 * This is an emailer that sends an email through a third-party smtp provider 
 * This requires the pear mail service to be installed
 * To do this, you will need pear mail set up.
 * http://programster.blogspot.co.uk/2013/08/installing-pear-mail.html
 */

class SmtpEmailer implements EmailerInterface
{
    private $m_from;
    private $m_smtp_host; # e.g. ssl://smtp.gmail.com
    private $m_smtp_port; # 465 for gmail
    private $m_smtp_username; # e.g. sdpagent@gmail.com
    private $m_smtp_password; # gmail req this to be an app-specific password
    
    
    /**
     * Constructs this object in preperation for sending emails
     * 
     * @param String $from_name - name the email should appear from e.g. 'John Doe'
     * @param String $from_email - email should appear from (same as SMTP provider account)
     * @param String $smtp_host - the smtp provider host e.g. ssl://smtp.gmail.com
     * @param int $smtp_port - the port on the host e.g. 465 for gmail.
     * @param String $smtp_username - the username (eg. email address for gmail)
     * @param String $smtp_password - the password (app-specific password for gmail)
     */
    public function __construct($from_name, 
                                $from_email, 
                                $smtp_host, 
                                $smtp_port, 
                                $smtp_username, 
                                $smtp_password)
    {
        $this->m_fromName = $from_name;
        $this->m_fromEmail = $from_email;
        $this->m_from = $from_name . " <" . $from_email . ">";
        $this->m_smtp_host = $smtp_host;
        $this->m_smtp_port = $smtp_port;
        $this->m_smtp_password = $smtp_password;
        $this->m_smtp_username = $smtp_username;
    }
    
    
    /**
     * 
     * @param string $to_name - name of the person sending email to e.g. 'John Doe'
     * @param string $to_email - email address of recipient
     * @param string $subject - the subject of the email
     * @param string $message - the body of the email
     * @param bool $html_format - optional - set to false if you must send plaintext, html assumed
     */
    public function send($to_name, $to_email, $subject, $message, $html_format=true)
    {
        # To do this, you will need pear mail set up.
        # http://programster.blogspot.co.uk/2013/08/installing-pear-mail.html
        require_once "Mail.php"; # Server include

        $to = $to_name . " <" . $to_email . ">";


        $headers = array(
            'From'    => $this->m_from,
            'To'      => $to,
            'Subject' => $subject
        );

        $mail_params =  array(
            'host'     => $this->m_smtp_host,
            'port'     => $this->m_smtp_port,
            'auth'     => true,
            'username' => $this->m_smtp_username, 
            'password' => $this->m_smtp_password
        );

        # Pear mail...
        $smtp = Mail::factory('smtp', $mail_params);

        $mail = $smtp->send($to, $headers, $message);

        if (PEAR::isError($mail)) 
        {
            throw new ExceptionFailedToSendEmail('Error sending email: ' . $mail->getMessage());
        } 
    }    
}

<?php

namespace iRAP\Emailers;

/*
 * This is an emailer that makes use of amazons SES (simple email service).
 * Note that this class requires that the 1.6 sdk has already been set up with the autoloader
 */

class AwsEmailer implements EmailerInterface
{
    private $m_replyToEmail; 
    private $m_replyToName; 
    private $m_fromEmail; 
    private $m_bounceEmail;
    private $m_ses_client;
    
    /**
     * Construct this emailer in preperation for sending emails.
     * 
     * @param string $from_email - A registered email address with SES e.g.
     *                            https://console.aws.amazon.com/ses/home?#verified-senders-email
     * @param string $reply_to_name - the name of who to reply to. e.g. "noreply"
     * @param string $reply_to_email - $email address where responses should be sent to.
     * @param string $bounce_email - optionally set an email of where failed outgoing messages 
     *                              should be bounced to. By default these are ignored.
     * 
     * @return AwsEmailer - (constructor)
     */
    public function __construct($aws_key,
                                $aws_secret,
                                SesRegion $region,
                                $from_email, 
                                $reply_to_name, 
                                $reply_to_email, 
                                $bounce_email = null)
    {
        $this->m_fromEmail    = $from_email;
        $this->m_replyToName  = $reply_to_name;
        $this->m_replyToEmail = $reply_to_email;
        
        # @TODO use $ses->list_identities to validate that fromEmail has been registered.
        $this->m_bounceEmail = $bounce_email;
        
        $sesConfig = array(
            'credentials' => array('key' => $aws_key, 'secret'  => $aws_secret),
            'region'      => (string) $region,
            'version'     => 'latest'
        );
        
        $this->m_ses_client = \Aws\Ses\SesClient::factory($sesConfig);
    }
    
    
    /**
     * Sends an email. Note that this will result in the attachments being 'reset' (but not deleted)
     * so if you want to send the attachments again, you need to re-add them.
     * @param string $to_name - name of person sending email to
     * @param string $to_email - email address where email should go
     * @param string $subject - subject of the email
     * @param string $body_message - body of the email
     * @param bool $html_format - whether should be in html format. (has no affect)
     * 
     * @return void - throws exception on error.
     */
    public function send($to_name, $to_email, $subject, $body_message, $html_format = true) 
    {
        $to_email = $to_name . "<" . $to_email . ">";
        
        $destination = array(
            'ToAddresses' => array($to_email)
        );
        
        $body = array();
        
        if ($html_format)
        {
            $body['Html'] = array(
                'Data' => $body_message
            );
        }
        else
        {
            $body['Text'] = array(
                'Data' => $body_message
            );
        }
        
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body'    => $body
        );
        
        
        $options = array(
            'Source'            => $this->m_fromEmail,
            'Destination'       => $destination,
            'Message'           => $message,
            'ReplyToAddresses'  => array($this->m_replyToName . '<' . $this->m_replyToEmail . '>'),
        );
        
        if ($this->m_bounceEmail != null)
        {
            $options['ReturnPath'] = $this->m_bounceEmail;
        }
        
        $this->m_ses_client->sendEmail($options);
    }
}

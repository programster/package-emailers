<?php

namespace iRAP\Emailers;

/*
 * This is a wrapper around the third party php_mailer libarary, but this class will only be sending
 * mail directly rather than through an smtp provider. If you want to use an smtp provider, please
 * use the smtp_emailer class instead.
 * 
 * The main advantage of this is:
 *  - less likely to appear in spam folder than base_emailer, yet is still sent 'direct'
 *  - can add attachments!
 */

class PhpMailerWrapper implements EmailerInterface
{
    private $m_attachments = array(); # Array of filepaths for attachments to be sent.
    private $m_reply_to_email; 
    private $m_reply_to_name; 
    private $m_from_email;
    private $m_from_name;  
    
    /**
     * Construct this emailer in preperation for sending emails.
     * 
     * @param string $from_name - name of person sending email.
     * @param string $from_email - email of person sending mail.
     * @param string $reply_to_name - name of person to reply to
     * @param string $reply_to_email - email of person sending mail.
     * 
     * @return PhpMailerWrapper - (constructor)
     */
    public function __construct($from_name, $from_email, $reply_to_name, $reply_to_email)
    {
        $this->m_from_name = $from_name;
        $this->m_from_email = $from_email;
        $this->m_reply_to_name = $reply_to_name;
        $this->m_reply_to_email = $reply_to_email;
    }
    
    
    /**
     * 
     * @param string $filePath - path to the file we wish to send via email
     */
    public function add_attachment($filePath)
    {
        $this->m_attachments[] = $filePath;
    }
    
    
    /**
     * Sends an email. Note that this will result in the attachments being 'reset' (but not deleted)
     * so if you want to send the attachments again, you need to re-add them.
     * @param type $to_name - name of person sending email to
     * @param type $to_email - email address where email should go
     * @param type $subject - subject of the email
     * @param type $body - body of the email
     * @param type $html_format - whether should be in html format. (has no affect)
     * 
     * @return void - throws exception on error.
     */
    public function send($to_name, $to_email, $subject, $body, $html_format = true) 
    {
        # Send the email
        $mail = new \PHPMailer(true); // specifying true results in throwing exceptions
        $mail->IsSendmail(); // telling the class to use SendMail transport

        try 
        {
            $mail->AddAddress($to_email, $to_name);
            $mail->SetFrom($this->m_from_email, $this->m_from_name);
            $mail->AddReplyTo($this->m_reply_to_email, $this->m_reply_to_name);
            $mail->Subject = $subject;
            
            $mail->IsHTML($html_format);
            
            if (!$html_format)
            {
                $mail->ContentType = 'text/plain'; 
                $mail->Body = $body;
            }
            else
            {
                 $mail->MsgHTML($body);
                 $mail->AltBody = StringLib::br2nl($body); 
            }
            
            if (count($this->m_attachments) > 0)
            {
                foreach ($this->m_attachments as $filepath)
                {
                    $mail->AddAttachment($filepath);
                }
            }

            $mail->Send();

            # Clear the attachments array for the next usage (may not want to send attachments)
            $this->m_attachments = array();
        } 
        catch (phpmailerException $e) 
        {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } 
        catch (Exception $e) 
        {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }
}



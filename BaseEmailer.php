<?php

namespace iRAP\Emailers;

/*
 * This is the 'base' emailer which sends an email directly from the server and does not need any
 * extensions such as PEAR mail or third party libraries. 
 * It is adviseable that you do NOT use this as it is the most likely to appear in the spam folder
 * or just not get through. Better to use an SMTP provider with credentials.
 * It does however require port 25 to be open for sending mail.
 */

class BaseEmailer implements EmailerInterface
{
    private $m_from_name;
    private $m_from_email;
    
    
    public function __construct($from_name, $from_email) 
    {
        $this->m_from_name = $from_name;
        $this->m_from_email = $from_email;
    }
    
    
    public function send($to_name, $to_email, $subject, $message, $html_format=true)
    {
        $to     = '"' . mb_encode_mimeheader($to_name) . '" <' . $to_email . '>';
        $from   = '"' . mb_encode_mimeheader($this->m_from_name) . '" <' . $this->m_from_email . '>';
        
        if ($html_format == true)
        {
            $format = 'text/html';
        }
        else
        {
            $format = 'text/plain';
        }
            
        
       
        if (\iRAP\CoreLibs\Core::is_cli())
        {
            $origin_ip = \iRAP\CoreLibs\Core::get_public_ip();
        }
        else
        {
            $origin_ip = $_SERVER['SERVER_ADDR'];
        }
        
        
        $headers = array(
            'MIME-Version'              => '1.0',
            'Content-type'              => $format . '; charset="UTF-8";',
            'Content-Transfer-Encoding' => '8bit', # Needed for utf8
            'Date'                      => date('r', $_SERVER['REQUEST_TIME']),
            'From'                      => $from,
            'Reply-To'                  => $from,
            'Return-Path'               => $from,
            'X-Mailer'                  =>'PHP v' . phpversion(),
            'X-Originating-IP'          => $origin_ip,
        );
        
        $header_string = '';
        
        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        
        foreach ($headers as $name => $value)
        {
            $header_string .= $name . ': ' . $value . PHP_EOL;
        }
            

        $result = mail($to, $subject, $message, $header_string);
        return $result;
    }    
}

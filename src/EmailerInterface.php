<?php

namespace iRAP\Emailers;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Interface EmailerInterface
{
    public function send($to_name, $to_email, $subject, $message, $html_format=true);
}


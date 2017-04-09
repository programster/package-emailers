<?php

namespace iRAP\Emailers;

Interface EmailerInterface
{
    public function send($to_name, $to_email, $subject, $message, $html_format=true);
}


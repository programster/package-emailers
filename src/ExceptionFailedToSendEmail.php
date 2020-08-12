<?php

/*
 * A custom exception to raise if email sending fails.
 * Using a custom exception, allows the developer to gracefully handle different erroneous situations at a higher level.
 */

namespace Programster\Emailers;

class ExceptionFailedToSendEmail extends \Exception
{

}



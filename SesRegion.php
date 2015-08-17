<?php

/* 
 * Class to prevent the developer making a mistake when creating a region for the our AWS emailer.
 * The dev doesn't have to look up the regions but just use one of this classes factory methods.
 */

namespace iRAP\Emailers;


class SesRegion
{
    private $m_name;
    
    private function __construct($name)
    {
        $this->m_name = $name;
    }
    
    
    /**
     * Create a European region to send emails from America.
     * @return \SesRegion
     */
    public static function createEuRegion()
    {
        return new SesRegion('eu-west-1');
    }
    
    
    /**
     * Create an American region to send emails from America.
     * @return \SesRegion
     */
    public static function createAmericaRegion()
    {
        return new SesRegion('us-east-1');
    }
    
    
    /**
     * Get the string representation of this class.
     * @return string
     */
    public function __toString()
    {
        return $this->m_name;
    }
}

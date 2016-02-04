<?php
/*
*
* BillingFuse
*
* @copyright 2016 BillingFuse International Limited.
*
* @license Apache V2.0
*
* THIS SOURCE CODE FORM IS SUBJECT TO THE TERMS OF THE PUBLIC
* APACHE LICENSE V2.0. A COMPLETE COPY OF THE LICENSE TEXT IS
* INCLUDED IN THE LICENSE FILE. 
*
*/

class Payment_Exception extends Exception
{
    /**
     * Creates a new translated exception.
     *
     * @param   string   error message
     * @param   array    translation variables
     */
    public function __construct($message, array $variables = NULL, $code = 0)
    {
        // Set the message
        $message = __($message, $variables);

        // Pass the message to the parent
        parent::__construct($message, $code);
    }
}

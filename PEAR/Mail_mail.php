<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Chuck Hagenbuch <chuck@horde.org>                            |
// +----------------------------------------------------------------------+
//
// $Id: mail.php,v 1.20 2007/10/06 17:00:00 chagenbu Exp $

/**
 * internal PHP-mail() implementation of the PEAR Mail:: interface.
 * @package Mail
 * @version $Revision: 1.20 $
 */
class Mail_mail extends Mail {

    /**
     * Any arguments to pass to the mail() function.
     * @var string
     */
    var $_params = '';

    /**
     * Constructor.
     *
     * Instantiates a new Mail_mail:: object based on the parameters
     * passed in.
     *
     * @param array $params Extra arguments for the mail() function.
     */
    function __construct($params = null)
    {
        // The other mail implementations accept parameters as arrays.
        // In the interest of being consistent, explode an array into
        // a string of parameter arguments.
        if (is_array($params)) {
            $this->_params = join(' ', $params);
        } else {
            $this->_params = $params;
        }

        /* Because the mail() function may pass headers as command
         * line arguments, we can't guarantee the use of the standard
         * "\r\n" separator.  Instead, we use the system's native line
         * separator. */
        if (defined('PHP_EOL')) {
            $this->sep = PHP_EOL;
        } else {
            $this->sep = (strpos(PHP_OS, 'WIN') === false) ? "\n" : "\r\n";
        }
    }

    function Mail_mail($params = null)
    {
      self::__construct($params);
    }
    /**
     * Implements Mail_mail::send() function using php's built-in mail()
     * command.
     *
     * @param mixed $recipients Either a comma-seperated list of recipients
     *              (RFC822 compliant), or an array of recipients,
     *              each RFC822 valid. This may contain recipients not
     *              specified in the headers, for Bcc:, resending
     *              messages, etc.
     *
     * @param array $headers The array of headers to send with the mail, in an
     *              associative array, where the array key is the
     *              header name (ie, 'Subject'), and the array value
     *              is the header value (ie, 'test'). The header
     *              produced from those values would be 'Subject:
     *              test'.
     *
     * @param string $body The full text of the message body, including any
     *               Mime parts, etc.
     *
     * @return mixed Returns true on success, or a PEAR_Error
     *               containing a descriptive error message on
     *               failure.
     *
     * @access public
     */
    function send($recipients, $headers, $body)
    {
        global $php_errormsg;
        if (!is_array($headers)) {
            return PEARraiseError('$headers must be an array');
        }

        $result = $this->_sanitizeHeaders($headers);
        if (is_a($result, 'PEAR_Error')) {
            return $result;
        }

        // If we're passed an array of recipients, implode it.
        if (is_array($recipients)) {
            $recipients = implode(', ', $recipients);
        }

        // Get the Subject out of the headers array so that we can
        // pass it as a seperate argument to mail().
        $subject = '';
        if (isset($headers['Subject'])) {
            $subject = $headers['Subject'];
            unset($headers['Subject']);
        }

        // Also remove the To: header.  The mail() function will add its own
        // To: header based on the contents of $recipients.
        unset($headers['To']);

        // Return-Path
        if(empty($this->_params) && !empty($headers['Return-Path'])) {
          $rp = $headers['Return-Path'];
          $rp = str_replace("<", "", $rp);
          $rp = str_replace(">", "", $rp);
          $this->_params = "-f".$rp;
          unset($headers['Return-Path']);
        }else if(empty($this->_params)){
          if(!empty($headers['Return-Path'])){
            $from = $headers['Return-Path'];
            unset($headers['Return-Path']);
            }
            else
            $from = $headers['From'];
          $p = strpos($from, "<");
          if($p !== false){
            $from = substr($from, $p + 1);
            $from = substr($from, 0, strpos($from, ">"));
            $this->_params = "-f" . $from;
          }
        }

        // Flatten the headers out.
        $headerElements = $this->prepareHeaders($headers);
        if (is_a($headerElements, 'PEAR_Error')) {
            return $headerElements;
        }
        list(, $text_headers) = $headerElements;

        // We only use mail()'s optional fifth parameter if the additional
        // parameters have been provided and we're not running in safe mode.
        $old_track_errors = @ini_set('track_errors', 1);
        if (empty($this->_params) || ini_get('safe_mode')) {
            $result = @mail($recipients, $subject, $body, $text_headers);
        } else {
            $result = @mail($recipients, $subject, $body, $text_headers,
                           $this->_params);
        }

        // If the mail() function returned failure, we need to create a
        // PEAR_Error object and return it instead of the boolean result.
        if ($result === false) {
            $errorText = "";
            if(function_exists("error_get_last"))
              $errorText = " ".join(" ", error_get_last());
            else
              if(isset($php_errormsg))
                $errorText = " ".$php_errormsg;
            @ini_set('track_errors', $old_track_errors);
            $result = PEARraiseError('mail() returned failure' . $errorText);
        }
        @ini_set('track_errors', $old_track_errors);

        return $result;
    }

}


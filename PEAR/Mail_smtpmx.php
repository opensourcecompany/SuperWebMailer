<?PHP
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * SMTP MX
 *
 * SMTP MX implementation of the PEAR Mail interface. Requires the Net_SMTP class.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Mail
 * @package    Mail_smtpmx
 * @author     gERD Schaufelberger <gerd@php-tools.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: smtpmx.php,v 1.2 2007/10/06 17:00:00 chagenbu Exp $
 * @see        Mail
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Net_SMTP.php';

/**
 * SMTP MX implementation of the PEAR Mail interface. Requires the Net_SMTP class.
 *
 *
 * @access public
 * @author  gERD Schaufelberger <gerd@php-tools.net>
 * @package Mail
 * @version $Revision: 1.2 $
 */
class Mail_smtpmx extends Mail {

    /**
     * SMTP connection object.
     *
     * @var object
     * @access private
     */
    var $_smtp = null;

    /**
     * The port the SMTP server is on.
     * @var integer
     * @see getservicebyname()
     */
    var $port = 25;

    /**
     * Hostname or domain that will be sent to the remote SMTP server in the
     * HELO / EHLO message.
     *
     * @var string
     * @see posix_uname()
     */
    var $mailname = 'localhost';

    /**
     * SMTP connection timeout value.  NULL indicates no timeout.
     *
     * @var integer
     */
    var $timeout = 10;

    /**
     * use either PEAR:Net_DNS or getmxrr
     *
     * @var boolean
     */
    var $withNetDns = true;

    /**
     * PEAR:Net_DNS_Resolver
     *
     * @var object
     */
    var $resolver;

    /**
     * Whether to use VERP or not. If not a boolean, the string value
     * will be used as the VERP separators.
     *
     * @var mixed boolean or string
     */
    var $verp = false;

    /**
     * Whether to use VRFY or not.
     *
     * @var boolean $vrfy
     */
    var $vrfy = false;

    /**
     * Switch to test mode - don't send emails for real
     *
     * @var boolean $debug
     */
    var $test = false;

    /**
     * Turn on Net_SMTP debugging?
     *
     * @var boolean $peardebug
     */
    var $debug = false;
    var $debugfilename = "";

    var $crlf = "\r\n";

    var $SSLConnection = false; // dummy for Mail_smtp.php
    /**
     * internal error codes
     *
     * translate internal error identifier to PEAR-Error codes and human
     * readable messages.
     *
     * @var boolean $debug
     * @todo as I need unique error-codes to identify what exactly went wrond
     *       I did not use intergers as it should be. Instead I added a "namespace"
     *       for each code. This avoids conflicts with error codes from different
     *       classes. How can I use unique error codes and stay conform with PEAR?
     */
    var $errorCode = array(
        'not_connected' => array(
            'code'  => 1,
            'msg'   => 'Could not connect to any mail server ({HOST}) at port {PORT} to send mail to {RCPT}.'
        ),
        'failed_vrfy_rcpt' => array(
            'code'  => 2,
            'msg'   => 'Recipient "{RCPT}" could not be veryfied.'
        ),
        'failed_set_from' => array(
            'code'  => 3,
            'msg'   => 'Failed to set sender: {FROM}.'
        ),
        'failed_set_rcpt' => array(
            'code'  => 4,
            'msg'   => 'Failed to set recipient: {RCPT}.'
        ),
        'failed_send_data' => array(
            'code'  => 5,
            'msg'   => 'Failed to send mail to: {RCPT}.'
        ),
        'no_from' => array(
            'code'  => 5,
            'msg'   => 'No from address has be provided.'
        ),
        'send_data' => array(
            'code'  => 7,
            'msg'   => 'Failed to create Net_SMTP object.'
        ),
        'no_mx' => array(
            'code'  => 8,
            'msg'   => 'No MX-record for {RCPT} found.'
        ),
        'no_resolver' => array(
            'code'  => 9,
            'msg'   => 'Could not start resolver! Install PEAR:Net_DNS or switch off "netdns"'
        ),
        'failed_rset' => array(
            'code'  => 10,
            'msg'   => 'RSET command failed, SMTP-connection corrupt.'
        ),
    );

    /**
     * Constructor.
     *
     * Instantiates a new Mail_smtp:: object based on the parameters
     * passed in. It looks for the following parameters:
     *     mailname    The name of the local mail system (a valid hostname which matches the reverse lookup)
     *     port        smtp-port - the default comes from getservicebyname() and should work fine
     *     timeout     The SMTP connection timeout. Defaults to 30 seconds.
     *     vrfy        Whether to use VRFY or not. Defaults to false.
     *     verp        Whether to use VERP or not. Defaults to false.
     *     test        Activate test mode? Defaults to false.
     *     debug       Activate SMTP and Net_DNS debug mode? Defaults to false.
     *     netdns      whether to use PEAR:Net_DNS or the PHP build in function getmxrr, default is true
     *
     * If a parameter is present in the $params array, it replaces the
     * default.
     *
     * @access public
     * @param array Hash containing any parameters different from the
     *              defaults.
     * @see _Mail_smtpmx()
     */
    function _INTERNALconstruct($params)
    {
        if (isset($params['mailname'])) {
            $this->mailname = $params['mailname'];
        } else {
            // try to find a valid mailname
            if (function_exists('posix_uname')) {
                $uname = posix_uname();
                $this->mailname = $uname['nodename'];
            }
        }

        // port number
        if (isset($params['port'])) {
            $this->_port = $params['port'];
        } else {
            $this->_port = getservbyname('smtp', 'tcp');
        }

        if (isset($params['timeout'])) $this->timeout = $params['timeout'];
        if (isset($params['verp'])) $this->verp = $params['verp'];
        if (isset($params['test'])) $this->test = $params['test'];
        if (isset($params['peardebug'])) $this->test = $params['peardebug'];
        if (isset($params['netdns'])) $this->withNetDns = $params['netdns'];
    }

    /**
     * Constructor wrapper for PHP4
     *
     * @access public
     * @param array Hash containing any parameters different from the defaults
     * @see __construct()
     */
    function __construct($params)
    {
        $this->_INTERNALconstruct($params);
    }

    function Mail_smtpmx($params)
    {
      self::__construct($params);
      register_shutdown_function(array($this, '__destruct'));
    }

    /**
     * Destructor implementation to ensure that we disconnect from any
     * potentially-alive persistent SMTP connections.
     */
    function __destruct()
    {
        if (is_object($this->_smtp)) {
            $this->_smtp->disconnect();
            $this->_smtp = null;
        }
    }

    /**
     * Disconnect and destroy the current SMTP connection.
     *
     * @return boolean True if the SMTP connection no longer exists.
     *
     * @access public
     */
    function disconnect()
    {
        /* If we have an SMTP object, disconnect and destroy it. */
        if (is_object($this->_smtp)) {
            $this->_smtp->disconnect();
            $this->_smtp = null;
        }

        /* We are disconnected if we no longer have an SMTP object. */
        return ($this->_smtp === null);
    }

    /**
     * Implements Mail::send() function using SMTP direct delivery
     *
     * @access public
     * @param mixed $recipients in RFC822 style or array
     * @param array $headers The array of headers to send with the mail.
     * @param string $body The full text of the message body,
     * @return mixed Returns true on success, or a PEAR_Error
     */
    function send($recipients, $headers, $body)
    {
        if (!is_array($headers)) {
            return PEARraiseError('$headers must be an array');
        }

        $result = $this->_sanitizeHeaders($headers);
        if (is_a($result, 'PEAR_Error')) {
            return $result;
        }

        // Prepare headers
        $headerElements = $this->prepareHeaders($headers);
        if (is_a($headerElements, 'PEAR_Error')) {
            return $headerElements;
        }
        list($from, $textHeaders) = $headerElements;

        // use 'Return-Path' if possible
        if (!empty($headers['Return-Path'])) {
            $from = $headers['Return-Path'];
            // M.B.
            $from = str_replace("<", "", $from);
            $from = str_replace(">", "", $from);
            // M.B.
            unset($headers['Return-Path']);
        }
        if (!isset($from)) {
            return $this->_raiseError('no_from');
        }

        // Prepare recipients
        $recipients = $this->parseRecipients($recipients);
        if (is_a($recipients, 'PEAR_Error')) {
            return $recipients;
        }

        foreach ($recipients as $rcpt) {
            list($user, $host) = explode('@', $rcpt);

            $mx = $this->_getMx($host);
            if (is_a($mx, 'PEAR_Error')) {
                return $mx;
            }

            if (empty($mx)) {
                $info = array('rcpt' => $rcpt);
                return $this->_raiseError('no_mx', $info);
            }

            $connected = false;
            foreach ($mx as $mserver => $mpriority) {
                // Don't send anything in test mode
                if ($this->test) {
                  $this->_smtp = null;  
                  return true;
                }  

                $this->_smtp = new Net_SMTP($mserver, $this->port, $this->mailname);

                // configure the SMTP connection.
                if ($this->debug) {
                    $this->_smtp->setDebug(true);
                }

                if($this->debugfilename){
                    $this->_smtp->setDebugFileName($this->debugfilename);
                }

                // attempt to connect to the configured SMTP server.
                $res = $this->_smtp->connect($this->timeout);
                if (is_a($res, 'PEAR_Error')) {
                    $this->_smtp = null;
                    continue;
                }

                // connection established
                if ($res) {
                    $connected = true;
                    break;
                }
            }

            if (!$connected) {
                $info = array(
                    'host' => implode(', ', array_keys($mx)),
                    'port' => $this->port,
                    'rcpt' => $rcpt,
                );
                return $this->_raiseError('not_connected', $info);
            }

            // Verify recipient
            if ($this->vrfy) {
                $res = $this->_smtp->vrfy($rcpt);
                if (is_a($res, 'PEAR_Error')) {
                    $info = array('rcpt' => $rcpt);
                    return $this->_raiseError('failed_vrfy_rcpt', $info);
                }
            }

            // mail from:
            $args['verp'] = $this->verp;
            $res = $this->_smtp->mailFrom($from, $args);
            if (is_a($res, 'PEAR_Error')) {
                $info = array('from' => $from);
                return $this->_raiseError('failed_set_from', $info);
            }

            // rcpt to:
            $res = $this->_smtp->rcptTo($rcpt);
            if (is_a($res, 'PEAR_Error')) {
                $info = array('rcpt' => $rcpt);
                return $this->_raiseError('failed_set_rcpt', $info);
            }

            // Don't send anything in test mode
            if ($this->test) {
                $result = $this->_smtp->rset();
                $res = $this->_smtp->rset();
                if (is_a($res, 'PEAR_Error')) {
                    return $this->_raiseError('failed_rset');
                }

                $this->_smtp->disconnect();
                $this->_smtp = null;
                return true;
            }

            // Send data
            $res = $this->_smtp->data( rtrim($textHeaders).$this->crlf.$this->crlf.$body);
            if (is_a($res, 'PEAR_Error')) {
                $info = array('rcpt' => $rcpt);
                return $this->_raiseError('failed_send_data', $info);
            }

            $this->_smtp->disconnect();
            $this->_smtp = null;
        }

        return true;
    }

    /**
     * Recieve mx rexords for a spciefied host
     *
     * The MX records
     *
     * @access private
     * @param string $host mail host
     * @return mixed sorted
     */
    function _getMx($host)
    {
        $mx = array();

        $NetDNSFailed = false;
        if ($this->withNetDns) {
            $res = $this->_loadNetDns();
            if (is_a($res, 'PEAR_Error')) {
                return $res;
            }

            $response = $this->resolver->query($host, 'MX');
            if (!$response) {
                $NetDNSFailed = true;
            }

            if(!$NetDNSFailed)
               foreach ($response->answer as $rr) {
                   if ($rr->type == 'MX') {
                       $mx[$rr->exchange] = $rr->preference;
                   }
               }
        }

        if (!$this->withNetDns || $NetDNSFailed) {
            $mxHost = array();
            $mxWeight = array();

            if (!getmxrr($host, $mxHost, $mxWeight)) {
                return false;
            }
            // M.B.
            reset($mxHost);
            foreach($mxHost as $key => $value) {
            //for ($i = 0; $i < count($mxHost); ++$i) {
            // M.B.
                $mx[$value] = $mxWeight[$key];
            }
        }

        asort($mx);
        return $mx;
    }

    /**
     * initialize PEAR:Net_DNS_Resolver
     *
     * @access private
     * @return boolean true on success
     */
    function _loadNetDns()
    {
        if (is_object($this->resolver)) {
            return true;
        }

        if (!include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DNS.php') {
            return $this->_raiseError('no_resolver');
        }

        $this->resolver = new Net_DNS_Resolver();
        if ($this->debug) {
            $this->resolver->test = 1;
        }

        return true;
    }

    /**
     * raise standardized error
     *
     * include additional information in error message
     *
     * @access private
     * @param string $id maps error ids to codes and message
     * @param array $info optional information in associative array
     * @see _errorCode
     */
    function _raiseError($id, $info = array())
    {
        $code = $this->errorCode[$id]['code'];
        $msg = $this->errorCode[$id]['msg'];

        // include info to messages
        if (!empty($info)) {
            $search = array();
            $replace = array();

            foreach ($info as $key => $value) {
                array_push($search, '{' . strtoupper($key) . '}');
                array_push($replace, $value);
            }

            $msg = str_replace($search, $replace, $msg);
        }

        return PEARraiseError($msg, $code);
    }
}

// support windows platforms
if (!function_exists ('getmxrr') ) {
  function getmxrr($hostname, &$mxhosts, &$mxweight) {
   if (!is_array ($mxhosts) ) {
     $mxhosts = array ();
   }

   if (!empty ($hostname) ) {
     $output = "";
     @exec ("nslookup.exe -type=MX $hostname.", $output);
     $imx=-1;

     foreach ($output as $line) {
       $imx++;
       $parts = "";
       if (preg_match ("/^$hostname\tMX preference = ([0-9]+), mail exchanger = (.*)$/", $line, $parts) ) {
         $mxweight[$imx] = $parts[1];
         $mxhosts[$imx] = $parts[2];
       }
     }
     return ($imx!=-1);
   }
   return false;
  }
}


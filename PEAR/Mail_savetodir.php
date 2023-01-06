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
// | Authors: Chuck Hagenbuch <chuck@horde.org>                           |
// |          Jon Parise <jon@php.net>                                    |
// +----------------------------------------------------------------------+

if(!defined("PHP_VERSION"))
  define("PHP_VERSION", phpversion());

class Mail_savetodir extends Mail {

    //@public
    var $filepathandname = "";
    var $save_serialized = false;
    var $save_asjson = false;



    /**
     * Constructor.
     *
     *
     * @param array Hash containing any parameters different from the
     *              defaults.
     * @access public
     */
    function __construct($params = null)
    {
        $this->save_serialized = isset($params['save_serialized']) ?  $params['save_serialized'] : false;
        $this->save_asjson = isset($params['save_asjson']) ?  $params['save_asjson'] : false;

    }

    function Mail_savetodir($params)
    {
      self::__construct($params);
    }

    function __destruct(){
    }

    function _Mail_savetodir()
    {
      self::__destruct();
    }

    /**
     * Implements Mail::send() function and save as file.
     *
     * @param mixed $recipients Either a comma-seperated list of recipients
     *              (RFC822 compliant), or an array of recipients,
     *              each RFC822 valid. This may contain recipients not
     *              specified in the headers, for Bcc:, resending
     *              messages, etc.
     *
     * @param array $headers The array of headers to send with the mail, in an
     *              associative array, where the array key is the
     *              header name (e.g., 'Subject'), and the array value
     *              is the header value (e.g., 'test'). The header
     *              produced from those values would be 'Subject:
     *              test'.
     *
     * @param string $body The full text of the message body, including any
     *               Mime parts, etc.
     *
     * @return mixed Returns true on success, or a PEAR_Error
     *               containing a descriptive error message on
     *               failure.
     * @access public
     */
    function send($recipients, $headers, $body)
    {

        if (!is_array($headers)) {
            return PEARraiseError('$headers must be an array');
        }

        if(empty($this->filepathandname))
            return PEARraiseError('You must specify filepathandname.');

        if($this->save_asjson || $this->save_serialized){
          $f = fopen($this->filepathandname, "wb");
          if($f === false)
            PEARraiseError("Can't open file " . $this->filepathandname);
          if($this->save_asjson){
            $text = $this->_json_encode(array("recipients" => $recipients, "headers" => $headers, "body" => $body));
            if($text === false) // on error it must be UTF-8
              $text = $this->_json_encode(array("utf8" => true, "recipients" => $recipients, "headers" => _utf8_string_array_encode($headers), "body" => utf8_encode($body)));
          }

          if($this->save_serialized){
            $text = serialize(array("recipients" => $recipients, "headers" => $headers, "body" => $body));
          }

          $res = fwrite($f, $text);
          fclose($f);
          if($res === false || $res <strlen($text))
            PEARraiseError("Can't write to file " . $this->filepathandname);
          return true;
        }

        $result = $this->_sanitizeHeaders($headers);
        if (is_a($result, 'PEAR_Error')) {
            return $result;
        }

        $headerElements = $this->prepareHeaders($headers);
        if (is_a($headerElements, 'PEAR_Error')) {
            return $headerElements;
        }

        list(, $text_headers) = $headerElements;

        $text = $text_headers . $this->sep . $this->sep . $body;

        $f = fopen($this->filepathandname, "wb");
        if($f === false)
          PEARraiseError("Can't open file " . $this->filepathandname);
        $res = fwrite($f, $text);
        fclose($f);
        if($res === false || $res <strlen($text))
          PEARraiseError("Can't write to file " . $this->filepathandname);

        return true;
    }

    function _json_encode($val, $options = 0){
      if(version_compare(PHP_VERSION, "5.3") >= 0){
       return json_encode($val, $options);
      }

      if (is_numeric($val))
          if($options == 32 /*JSON_NUMERIC_CHECK*/)
             return $val;
             else
             return '"'.$val.'"';

        if (is_string($val)) return '"'.addslashes($val).'"';

        if ($val === null) return 'null';
        if ($val === true) return 'true';
        if ($val === false) return 'false';

        $assoc = false;
        $i = 0;
        foreach ($val as $k=>$v){
            if ($k !== $i++){
                $assoc = true;
                break;
            }
        }
        $res = array();
        foreach ($val as $k=>$v){
            $v = $this->_json_encode($v, $options);
            if ($assoc){
                $k = '"'.addslashes($k).'"';
                $v = $k.':'.$v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);
        return ($assoc)? '{'.$res.'}' : '['.$res.']';

    }

    function _utf8_string_array_encode(&$array){
        array_walk($array, array($this, '_utf8_string_array_encode_helper'));
        return $array;
    }

    function _utf8_string_array_encode_helper(&$value,&$key){
            if(is_string($value)){
                $value = utf8_encode($value);
            }
            if(is_string($key)){
                $key = utf8_encode($key);
            }
            if(is_array($value)){
                utf8_string_array_encode($value);
            }
    }

}

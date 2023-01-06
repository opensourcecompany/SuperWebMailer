<?php
/**
 * The Mail_mimePart class is used to create MIME E-mail messages
 *
 * This class enables you to manipulate and build a mime email
 * from the ground up. The Mail_Mime class is a userfriendly api
 * to this class for people who aren't interested in the internals
 * of mime mail.
 * This class however allows full control over the email.
 *
 * Compatible with PHP versions 4 and 5
 *
 * LICENSE: This LICENSE is in the BSD license style.
 * Copyright (c) 2002-2003, Richard Heyes <richard@phpguru.org>
 * Copyright (c) 2003-2006, PEAR <pear-group@php.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or
 * without modification, are permitted provided that the following
 * conditions are met:
 *
 * - Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.
 * - Neither the name of the authors, nor the names of its contributors
 *   may be used to endorse or promote products derived from this
 *   software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Mail
 * @package    Mail_Mime
 * @author     Richard Heyes  <richard@phpguru.org>
 * @author     Cipriano Groenendal <cipri@php.net>
 * @author     Sean Coates <sean@php.net>
 * @copyright  2003-2006 PEAR <pear-group@php.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    CVS: $Id: mimePart.php,v 1.26 2007/10/06 12:56:01 cipri Exp $
 * @link       http://pear.php.net/package/Mail_mime
 */


/**
 * The Mail_mimePart class is used to create MIME E-mail messages
 *
 * This class enables you to manipulate and build a mime email
 * from the ground up. The Mail_Mime class is a userfriendly api
 * to this class for people who aren't interested in the internals
 * of mime mail.
 * This class however allows full control over the email.
 *
 * @category   Mail
 * @package    Mail_Mime
 * @author     Richard Heyes  <richard@phpguru.org>
 * @author     Cipriano Groenendal <cipri@php.net>
 * @author     Sean Coates <sean@php.net>
 * @copyright  2003-2006 PEAR <pear-group@php.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Mail_mime
 */
class Mail_mimePart {

   /**
    * The encoding type of this part
    *
    * @var string
    * @access private
    */
    var $_encoding;

   /**
    * An array of subparts
    *
    * @var array
    * @access private
    */
    var $_subparts;

   /**
    * The output of this part after being built
    *
    * @var string
    * @access private
    */
    var $_encoded;

   /**
    * Headers for this part
    *
    * @var array
    * @access private
    */
    var $_headers;

   /**
    * The body of this part (not encoded)
    *
    * @var string
    * @access private
    */
    var $_body;


   /**
     M.B. Cache encoded images/attachments it is quicker than do this every same email again
    */

    var $cache_body_encoded = false;
    var $cache_sourceArray;

    /**
     * Constructor.
     *
     * Sets up the object.
     *
     * @param $body   - The body of the mime part if any.
     * @param $params - An associative array of parameters:
     *                  content_type - The content type for this part eg multipart/mixed
     *                  encoding     - The encoding to use, 7bit, 8bit, base64, or quoted-printable
     *                  cid          - Content ID to apply
     *                  disposition  - Content disposition, inline or attachment
     *                  dfilename    - Optional filename parameter for content disposition
     *                  description  - Content description
     *                  charset      - Character set to use
     * @access public
     */
    function __construct($body = '', $params = array())
    {
        if (!defined('MAIL_MIMEPART_CRLF')) {
            define('MAIL_MIMEPART_CRLF', defined('MAIL_MIME_CRLF') ? MAIL_MIME_CRLF : "\r\n");
        }

        $contentType = array();
        $contentDisp = array();
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'content_type':
                    $contentType['type'] = $value;
                    //$headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
                    break;

                case 'encoding':
                    $this->_encoding = $value;
                    $headers['Content-Transfer-Encoding'] = $value;
                    break;

                case 'cid':
                    $headers['Content-ID'] = '<' . $value . '>';
                    break;

                case 'disposition':
                    $contentDisp['disp'] = $value;
                    break;

                case 'dfilename':
                    $contentDisp['filename'] = $value;
                    $contentType['name'] = $value;
                    break;

                case 'description':
                    $headers['Content-Description'] = $value;
                    break;

                case 'charset':
                    $contentType['charset'] = $value;
                    $contentDisp['charset'] = $value;
                    break;

                case 'language':
                    $contentType['language'] = $value;
                    $contentDisp['language'] = $value;
                    break;

                case 'location':
                    $headers['Content-Location'] = $value;
                    break;
            }
        }
        if (isset($contentType['type'])) {
            $headers['Content-Type'] = $contentType['type'];
            if (isset($contentType['name'])) {
                $headers['Content-Type'] .= ';' . MAIL_MIMEPART_CRLF;
                $headers['Content-Type'] .= $this->_buildHeaderParam('name', $contentType['name'],
                                                isset($contentType['charset']) ? $contentType['charset'] : 'US-ASCII',
                                                isset($contentType['language']) ? $contentType['language'] : NULL);
            } elseif (isset($contentType['charset'])) {
                $headers['Content-Type'] .= "; charset=\"{$contentType['charset']}\"";
                if(isset($params["format"]))
                  $headers['Content-Type'] .= "; format=".$params["format"];
            }
        }


        if (isset($contentDisp['disp'])) {
            $headers['Content-Disposition'] = $contentDisp['disp'];
            if (isset($contentDisp['filename'])) {
                $headers['Content-Disposition'] .= ';' . MAIL_MIMEPART_CRLF;
                $headers['Content-Disposition'] .= $this->_buildHeaderParam('filename', $contentDisp['filename'],
                                                isset($contentDisp['charset']) ? $contentDisp['charset'] : 'US-ASCII',
                                                isset($contentDisp['language']) ? $contentDisp['language'] : NULL);
            }
        }




        // Default content-type
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'text/plain';
        }

        //Default encoding
        if (!isset($this->_encoding)) {
            $this->_encoding = '7bit';
        }

        // Assign stuff to member variables
        $this->_encoded  = array();
        $this->_headers  = $headers;
        $this->_body     = $body;
    }

    function Mail_mimePart($body = '', $params = array())
    {
      self::__construct($body, $params);
    }

    /**
     * encode()
     *
     * Encodes and returns the email. Also stores
     * it in the encoded member variable
     *
     * @return An associative array containing two elements,
     *         body and headers. The headers element is itself
     *         an indexed array.
     * @access public
     */
    function encode($part = 0, $boundaryString = "")
    {
        $encoded =& $this->_encoded;

        if ( isset($this->_subparts) && count($this->_subparts)) {
            //$boundary = '=_' . md5(rand() . microtime());
            //srand((double)microtime()*1000000);
            // M.B. Outlook style parts
            if($part == 0) {
              srand(time());
              $s = md5(rand() . microtime());
              $boundaryString = substr($s, 0, 4);
              $boundaryString .= "_".substr($s, 4, 8);
              $boundaryString .= ".".substr($s, 9, 8);
              $boundaryString = strtoupper($boundaryString);
              $boundary = '----=_NextPart_' ."000"."_".$boundaryString;
              $part++;
            } else {
              $partStr = $part;
              while(strlen($partStr) < 3)
                $partStr = "0".$partStr;
              $boundary = '----=_NextPart_' .$partStr."_".$boundaryString;
              $part++;
            }
            // M.B.
            $this->_headers['Content-Type'] .= ';' . MAIL_MIMEPART_CRLF . "\t" . 'boundary="' . $boundary . '"';

            // Add body parts to $subparts
            for ($i = 0; $i < count($this->_subparts); $i++) {
                $headers = array();
                $tmp = $this->_subparts[$i]->encode($part, $boundaryString);
                foreach ($tmp['headers'] as $key => $value) {
                    $headers[] = $key . ': ' . $value;
                }
                $subparts[] = implode(MAIL_MIMEPART_CRLF, $headers) . MAIL_MIMEPART_CRLF . MAIL_MIMEPART_CRLF . $tmp['body'] . MAIL_MIMEPART_CRLF;
            }

            $encoded['body'] = '--' . $boundary . MAIL_MIMEPART_CRLF .
                               rtrim(implode('--' . $boundary . MAIL_MIMEPART_CRLF , $subparts), MAIL_MIMEPART_CRLF) . MAIL_MIMEPART_CRLF .
                               '--' . $boundary.'--' . MAIL_MIMEPART_CRLF;

        } else {
            $ValueSet = false;
            if( isset($this->_headers["Content-Disposition"]) && isset($this->cache_sourceArray) && $this->cache_body_encoded ) {
               $encoded['body'] = $this->cache_sourceArray["body"];
               $ValueSet = true;
            }
            if(!$ValueSet) {
              $encoded['body'] = $this->_getEncodedData($this->_body, $this->_encoding);

              if( isset($this->_headers["Content-Disposition"]) && isset($this->cache_sourceArray) && (strpos($this->_headers["Content-Disposition"], "inline") !== false || strpos($this->_headers["Content-Disposition"], "attachment") !== false ) ) {
                $this->_body = ""; // clear save memory
                $this->cache_sourceArray["body"] = $encoded['body'];
                $this->cache_sourceArray["cache_body_encoded"] = true;
                $this->cache_body_encoded = true;
              }

            }
        }

        // Add headers to $encoded
        $encoded['headers'] =& $this->_headers;

        return $encoded;
    }

    /**
     * &addSubPart()
     *
     * Adds a subpart to current mime part and returns
     * a reference to it
     *
     * @param $body   The body of the subpart, if any.
     * @param $params The parameters for the subpart, same
     *                as the $params argument for constructor.
     * @return A reference to the part you just added. It is
     *         crucial if using multipart/* in your subparts that
     *         you use =& in your script when calling this function,
     *         otherwise you will not be able to add further subparts.
     * @access public
     */
    function &addSubPart($body, $params)
    {
        $this->_subparts[] = new Mail_mimePart($body, $params);
        return $this->_subparts[count($this->_subparts) - 1];
    }

    /**
     * _getEncodedData()
     *
     * Returns encoded data based upon encoding passed to it
     *
     * @param $data     The data to encode.
     * @param $encoding The encoding type to use, 7bit, base64,
     *                  or quoted-printable.
     * @access private
     */
    function _getEncodedData($data, $encoding)
    {
        switch ($encoding) {
            case '8bit':
            case '7bit':
                return $data;
                break;

            case 'quoted-printable':
                return $this->_quotedPrintableEncode($data);
                break;

            case 'base64':
                return rtrim(chunk_split(base64_encode($data), 76, MAIL_MIMEPART_CRLF));
                break;

            default:
                return $data;
        }
    }

    /**
     * quotedPrintableEncode()
     *
     * Encodes data to quoted-printable standard.
     *
     * @param $input    The data to encode
     * @param $line_max Optional max line length. Should
     *                  not be more than 76 chars
     *
     * @access private
     */
    function _quotedPrintableEncode($input , $line_max = 76)
    {
        $lines  = preg_split("/\r?\n/", $input);
        $eol    = MAIL_MIMEPART_CRLF;
        $escape = '=';
        $output = '';

        foreach($lines as $key => $line) {
        #while (list(, $line) = each($lines)) {

            $line    = preg_split('||', $line, -1, PREG_SPLIT_NO_EMPTY);
            $linlen     = count($line);
            $newline = '';

            for ($i = 0; $i < $linlen; $i++) {
                $char = $line[$i];
                $dec  = ord($char);

                if (($dec == 32) AND ($i == ($linlen - 1))) {    // convert space at eol only
                    $char = '=20';

                } elseif (($dec == 9) AND ($i == ($linlen - 1))) {  // convert tab at eol only
                    $char = '=09';
                } elseif ($dec == 9) {
                    ; // Do nothing if a tab.
                } elseif (($dec == 61) OR ($dec < 32 ) OR ($dec > 126)) {
                    $char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
                } elseif (($dec == 46) AND (($newline == '') || ((strlen($newline) + strlen("=2E")) >= $line_max))) {
                    //Bug #9722: convert full-stop at bol,
                    //some Windows servers need this, won't break anything (cipri)
                    //Bug #11731: full-stop at bol also needs to be encoded
                    //if this line would push us over the line_max limit.
                    $char = '=2E';
                }

                //Note, when changing this line, also change the ($dec == 46)
                //check line, as it mimics this line due to Bug #11731
                if ((strlen($newline) + strlen($char)) >= $line_max) {        // MAIL_MIMEPART_CRLF is not counted
                    $output  .= $newline . $escape . $eol;                    // soft line break; " =\r\n" is okay
                    $newline  = '';
                }
                $newline .= $char;
            } // end of for
            $output .= $newline . $eol;
        }
        $output = substr($output, 0, -1 * strlen($eol)); // Don't want last crlf
        return $output;
    }

    /**
     * _buildHeaderParam()
     *
     * Encodes the paramater of a header.
     *
     * @param $name         The name of the header-parameter
     * @param $value        The value of the paramter
     * @param $charset      The characterset of $value
     * @param $language     The language used in $value
     * @param $maxLength    The maximum length of a line. Defauls to 75
     *
     * @access private
     */

    function _buildHeaderParam($name, $value, $charset=NULL, $language=NULL, $maxLength=75)
    {
        if($charset == "utf-8") {
           $s = utf8_encode($value);
           if($s != "")
             $value = $s;
         }
        $shouldEncode = 0;
        $secondAsterisk = '';
        if (preg_match('#([\x80-\xFF]){1}#', $value)) {
            $shouldEncode = 1;
        }

        if ($shouldEncode) {
          $hdr_value_suffix = "";
          $value = $this->_encodeHeaderValue($name, $value, $hdr_value_suffix, $charset);
        }
        $header = "\t{$name}{$secondAsterisk}=\"{$value}\""; // // Outlook Express doesn't use Space it use TAB
        if (strlen($header) <= $maxLength || $shouldEncode) { // if $shouldEncode than line breaks ever OK
            return $header;
        }

        // name*0*= and filename*0*= ... not compatible with Windows mail / Outlook express, the attachments has no name
        // therefore we leave the filename the user must use short names

        return $header;

        // ignore this

        $header .= "; ";

        $preLength = strlen(" {$name}*0{$secondAsterisk}=\"");
        $sufLength = strlen("\";");
        $maxLength = MAX(16, $maxLength - $preLength - $sufLength - 2);
        $maxLengthReg = "|(.{0,$maxLength}[^\%][^\%])|";

        $headers = array();
        $headCount = 0;
        while ($value) {
            $matches = array();
            $found = preg_match($maxLengthReg, $value, $matches);
            if ($found) {
                $headers[] = " {$name}*{$headCount}{$secondAsterisk}=\"{$matches[0]}\"";
                $value = substr($value, strlen($matches[0]));
            } else {
                $headers[] = " {$name}*{$headCount}{$secondAsterisk}=\"{$value}\"";
                $value = "";
            }
            $headCount++;
        }
        $headers = implode(MAIL_MIMEPART_CRLF, $headers); // . ';';
        return $headers;
    }


    // M.B. moved from mime.php to ever use Q encoding
    function _encodeHeaderValue($hdr_name, $hdr_value, $hdr_value_suffix, $charset)
    {
       $suffixlen = strlen($hdr_value_suffix);

                    // M.B.
                    if($hdr_value_suffix != "")
                      $hdr_value = str_replace('"', '', $hdr_value);
                    // M.B.

                    //quoted-printable encoding has been selected

                    //Fix for Bug #10298, Ota Mares <om@viazenetti.de>
                    //Check if there is a double quote at beginning or end of
                    //the string to prevent that an open or closing quote gets
                    //ignored because it is encapsuled by an encoding pre/suffix.
                    //Remove the double quote and set the specific prefix or
                    //suffix variable so that we can concat the encoded string and
                    //the double quotes back together to get the intended string.
                    $quotePrefix = $quoteSuffix = '';
                    if ($hdr_value[0] == '"') {
                        $hdr_value = substr($hdr_value, 1);
                        $quotePrefix = '"';
                    }
                    if ($hdr_value[strlen($hdr_value)-1] == '"') {
                        $hdr_value = substr($hdr_value, 0, -1);
                        $quoteSuffix = '"';
                    }

                    //Generate the header using the specified params and dynamicly
                    //determine the maximum length of such strings.
                    //75 is the value specified in the RFC. The -2 is there so
                    //the later regexp doesn't break any of the translated chars.
                    //The -2 on the first line-regexp is to compensate for the ": "
                    //between the header-name and the header value
                    $prefix = '=?' . $charset . '?Q?';
                    $suffix = '?=';
                    $maxLength = (75 - $suffixlen) - strlen($prefix . $suffix) - 2 - 1;
                    $maxLength1stLine = $maxLength - strlen($hdr_name) - 2;
                    $maxLength = $maxLength - 1;

                    //Replace all special characters used by the encoder.
                    $search  = array('=',   '_',   '?',   ' ');
                    $replace = array('=3D', '=5F', '=3F', '_');
                    $hdr_value = str_replace($search, $replace, $hdr_value);

                    //Replace all extended characters (\x80-xFF) with their
                    //ASCII values.
                    /*$hdr_value = preg_replace('#([\x80-\xFF])#e',
                        '"=" . strtoupper(dechex(ord("\1")))',
                        $hdr_value);*/
                    $hdr_value = preg_replace_callback('#([\x80-\xFF])#',
                        array(get_class($this), "_encodeHeaderValue_preg_replace_callback1")
                        ,
                        $hdr_value);

                    //This regexp will break QP-encoded text at every $maxLength
                    //but will not break any encoded letters.
                    $reg1st = "|(.{0,$maxLength1stLine}[^\=][^\=])|";
                    $reg2nd = "|(.{0,$maxLength}[^\=][^\=])|";
                    //Fix for Bug #10298, Ota Mares <om@viazenetti.de>
                    //Concat the double quotes and encoded string together
                    $hdr_value = $quotePrefix . $hdr_value . $quoteSuffix;


                    $hdr_value_out = $hdr_value;
                    $realMax = $maxLength1stLine + strlen($prefix . $suffix);
                    if (strlen($hdr_value_out) >= $realMax) {
                        //Begin with the regexp for the first line.
                        $reg = $reg1st;
                        $output = "";
                        while ($hdr_value_out) {
                            //Split translated string at every $maxLength
                            //But make sure not to break any translated chars.
                            $found = preg_match($reg, $hdr_value_out, $matches);

                            //After this first line, we need to use a different
                            //regexp for the first line.
                            $reg = $reg2nd;

                            //Save the found part and encapsulate it in the
                            //prefix & suffix. Then remove the part from the
                            //$hdr_value_out variable.
                            if ($found) {
                                $part = $matches[0];
                                $len = strlen($matches[0]);
                                $hdr_value_out = substr($hdr_value_out, $len);
                            } else {
                                $part = $hdr_value_out;
                                $hdr_value_out = "";
                            }

                            //RFC 2047 specifies that any split header should
                            //be seperated by a CRLF SPACE
                            if ($output) {
                                $output .=  "\r\n\t"; // Outlook Express doesn't use Space it use TAB
                            }
                            $output .= $prefix . $part . $suffix;
                        }
                        $hdr_value_out = $output;
                    } else {
                        $hdr_value_out = $prefix . $hdr_value_out . $suffix;
                    }
                    $hdr_value = $hdr_value_out;

       return $hdr_value;
    }

    function _encodeHeaderValue_preg_replace_callback1 ($matches){
      return "=" . strtoupper(dechex(ord($matches[1])));
    }

    // M.B.


/*


    function _buildHeaderParam($name, $value, $charset=NULL, $language=NULL, $maxLength=75)
    {
        //If we find chars to encode, or if charset or language
        //is not any of the defaults, we need to encode the value.
        $shouldEncode = 0;
        $secondAsterisk = '';
        if (preg_match('#([\x80-\xFF]){1}#', $value)) {
            $shouldEncode = 1;
        } elseif ($charset && (strtolower($charset) != 'us-ascii')) {
            $shouldEncode = 1;
        } elseif ($language && ($language != 'en' && $language != 'en-us')) {
            $shouldEncode = 1;
        }
        if ($shouldEncode) {
            $search  = array('%',   ' ',   "\t");
            $replace = array('%25', '%20', '%09');
            $encValue = str_replace($search, $replace, $value);
            $encValue = preg_replace('#([\x80-\xFF])#e', '"%" . strtoupper(dechex(ord("\1")))', $encValue);
            $value = "$charset'$language'$encValue";
            $secondAsterisk = '*';
        }
        $header = " {$name}{$secondAsterisk}=\"{$value}\"";
        if (strlen($header) <= $maxLength) {
            return $header;
        }
        $header .= "; ";

        $preLength = strlen(" {$name}*0{$secondAsterisk}=\"");
        $sufLength = strlen("\";");
        $maxLength = MAX(16, $maxLength - $preLength - $sufLength - 2);
        $maxLengthReg = "|(.{0,$maxLength}[^\%][^\%])|";

        $headers = array();
        $headCount = 0;
        while ($value) {
            $matches = array();
            $found = preg_match($maxLengthReg, $value, $matches);
            if ($found) {
                $headers[] = " {$name}*{$headCount}{$secondAsterisk}=\"{$matches[0]}\"";
                $value = substr($value, strlen($matches[0]));
            } else {
                $headers[] = " {$name}*{$headCount}{$secondAsterisk}=\"{$value}\"";
                $value = "";
            }
            $headCount++;
        }
        $headers = implode(MAIL_MIMEPART_CRLF, $headers); // . ';';
        return $headers;
    }

*/
} // End of class

<?php
/**
 *      Filemanager extension
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
define("UploadGetContentsCalled", 1);
require_once('filemanager.config.php');

if(!function_exists("IsUtf8String")) {
 function check_utf8_helper($str) {
     $len = strlen($str);
     for($i = 0; $i < $len; $i++){
         $c = ord($str[$i]);
         if ($c > 128) {
             if (($c > 247)) return false;
             elseif ($c > 239) $bytes = 4;
             elseif ($c > 223) $bytes = 3;
             elseif ($c > 191) $bytes = 2;
             else return false;
             if (($i + $bytes) > $len) return false;
             while ($bytes > 1) {
                 $i++;
                 $b = ord($str[$i]);
                 if ($b < 128 || $b > 191) return false;
                 $bytes--;
             }
         }
     }
     return true;
 } // end of check_utf8

 function IsUtf8String( $s ) {
     return check_utf8_helper($s);

     # problem segmentation fault one some apache webserver because of bug in libs for text > 10KB
     $ptrASCII  = '[\x00-\x7F]';
     $ptr2Octet = '[\xC2-\xDF][\x80-\xBF]';
     $ptr3Octet = '[\xE0-\xEF][\x80-\xBF]{2}';
     $ptr4Octet = '[\xF0-\xF4][\x80-\xBF]{3}';
     $ptr5Octet = '[\xF8-\xFB][\x80-\xBF]{4}';
     $ptr6Octet = '[\xFC-\xFD][\x80-\xBF]{5}';
     $result = preg_match("/^($ptrASCII|$ptr2Octet|$ptr3Octet|$ptr4Octet|$ptr5Octet|$ptr6Octet)*$/s", $s);

     if($result)
       $result = (utf8_decode($s) != "");

     return $result;
 }
}

if(!function_exists("UTF8ToEntities")) {
// from http://php.net/manual/de/function.utf8-decode.php
function UTF8ToEntities ($string) {
     /* note: apply htmlspecialchars if desired /before/ applying this function
     /* Only do the slow convert if there are 8-bit characters */
     /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
     if (! preg_match("/[\200-\237]/", $string) and ! preg_match("/[\241-\377]/", $string))
         return $string;

    // reject too-short sequences
     $string = preg_replace("/[\302-\375]([\001-\177])/", "&#65533;\\1", $string);
    $string = preg_replace("/[\340-\375].([\001-\177])/", "&#65533;\\1", $string);
    $string = preg_replace("/[\360-\375]..([\001-\177])/", "&#65533;\\1", $string);
    $string = preg_replace("/[\370-\375]...([\001-\177])/", "&#65533;\\1", $string);
    $string = preg_replace("/[\374-\375]....([\001-\177])/", "&#65533;\\1", $string);

    // reject illegal bytes & sequences
         // 2-byte characters in ASCII range
     $string = preg_replace("/[\300-\301]./", "&#65533;", $string);
         // 4-byte illegal codepoints (RFC 3629)
     $string = preg_replace("/\364[\220-\277]../", "&#65533;", $string);
         // 4-byte illegal codepoints (RFC 3629)
     $string = preg_replace("/[\365-\367].../", "&#65533;", $string);
         // 5-byte illegal codepoints (RFC 3629)
     $string = preg_replace("/[\370-\373]..../", "&#65533;", $string);
         // 6-byte illegal codepoints (RFC 3629)
     $string = preg_replace("/[\374-\375]...../", "&#65533;", $string);
         // undefined bytes
     $string = preg_replace("/[\376-\377]/", "&#65533;", $string);

    // reject consecutive start-bytes
     $string = preg_replace("/[\302-\364]{2,}/", "&#65533;", $string);

    // decode four byte unicode characters
/*     $string = preg_replace(
         "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/e",
         "'&#'.((ord('\\1')&7)<<18 | (ord('\\2')&63)<<12 |" .
         " (ord('\\3')&63)<<6 | (ord('\\4')&63)).';'",
     $string); */

     $string = preg_replace_callback(
         "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback1',
     $string);

    // decode three byte unicode characters
    /* $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
     "'&#'.((ord('\\1')&15)<<12 | (ord('\\2')&63)<<6 | (ord('\\3')&63)).';'",
     $string); */

     $string = preg_replace_callback("/([\340-\357])([\200-\277])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback2',
     $string);

    // decode two byte unicode characters
     /* $string = preg_replace("/([\300-\337])([\200-\277])/e",
     "'&#'.((ord('\\1')&31)<<6 | (ord('\\2')&63)).';'",
     $string); */

     $string = preg_replace_callback("/([\300-\337])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback3',
     $string);

    // reject leftover continuation bytes
     $string = preg_replace("/[\200-\277]/", "&#65533;", $string);

    return $string;
 }

 function UTF8ToEntities_preg_replace_callback1($matches){
  return '&#'.((ord($matches[1])&7)<<18 | (ord($matches[2])&63)<<12 |
          (ord($matches[3])&63)<<6 | (ord($matches[4])&63)).';';
 }

 function UTF8ToEntities_preg_replace_callback2($matches){
  return '&#'.((ord($matches[1])&15)<<12 | (ord($matches[2])&63)<<6 | (ord($matches[3])&63)).';';
 }

 function UTF8ToEntities_preg_replace_callback3($matches){
  return '&#'.((ord($matches[1])&31)<<6 | (ord($matches[2])&63)).';';
 }

}

function SendError( $number, $text )
{
  SendUploadResults( $number, '', '', $text ) ;
}

if(!auth()){
 SendUploadResults( 1, '', '', "unauthorized access" ) ;
 exit;
}

FileUploadAndGetContents();


// This is the function that sends the results of the uploading process.
// This is the function that sends the results of the uploading process.
function SendUploadResults( $errorNumber, $fileUrl = '', $fileName = '', $customMsg = '' )
{
        echo <<<EOF
<script type="text/javascript">
(function()
{
        var d = document.domain ;

        while ( true )
        {
                // Test if we can access a parent property.
                try
                {
                        var test = window.top.opener.document.domain ;
                        break ;
                }
                catch( e ) {}

                // Remove a domain part: www.mytest.example.com => mytest.example.com => example.com ...
                d = d.replace( /.*?(?:\.|$)/, '' ) ;

                if ( d.length == 0 )
                        break ;         // It was not able to detect the domain.

                try
                {
                        document.domain = d ;
                }
                catch (e)
                {
                        break ;
                }
        }
})() ;

EOF;
        $rpl = array( '\\' => '\\\\', '"' => '\\"' ) ;
        echo 'window.parent.OnUploadCompleted(' . $errorNumber . ',"' . strtr( $fileUrl, $rpl ) . '","' . strtr( $fileName, $rpl ) . '", "' . strtr( $customMsg, $rpl ) . '") ;' ;
        echo '</script>' ;
        exit ;
}


function FileUploadAndGetContents()
{
        global $config, $Config;

        if (!isset($_FILES)) {
            global $_FILES;
        }
        $sErrorNumber = '0';
        $sFileName = '' ;
        $contents = "";
        $customMsg = "";
        $IsFromInternet = false;

        if( !empty($_POST["InternetFile"]) ) {
                $IsFromInternet = true;
                $sFileName = $_POST["InternetFile"];
                // Get the extension.
                $sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
                $sExtension = strtolower( $sExtension ) ;

                if ( isset( $config['HtmlExtensions'] ) ) {

                  if ( !IsHtmlExtension( $sExtension, $config['HtmlExtensions'] ) ) {
                    $sErrorNumber = '202';
                  }
                  else
                  if(!$config['ZIPSupport'] &&  ($sExtension == "zip")) {
                    $sErrorNumber = '310' ;
                  }
                  if( $sExtension != "zip" && (( $detectHtml = DetectHtml( join("", file($sFileName)) ) ) === false) ) {
                    $sErrorNumber = '202';
                  }
                  else {

                           if($sExtension == "zip" && $config['ZIPSupport']){
                             $errorCode = 0;
                             $dest = fopen($Config['UserFilesAbsolutePath']."/import/"."HtMlZiPImPoRt.zip", "wb");
                             if(!$dest)
                               $sErrorNumber = '203';
                             if(!$sErrorNumber){
                               $src = fopen($sFileName, "r");
                               if(!$src)
                                 $sErrorNumber = '320';
                             }

                             if(!$sErrorNumber){
                               while(!feof($src)){
                                 $buffer = fread($src, 1024);
                                 if($buffer)
                                   fwrite($dest, $buffer);
                               }
                               fclose($src);
                               fclose($dest);

                               $contents = LoadZIPFile($Config['UserFilesAbsolutePath']."/import/"."HtMlZiPImPoRt.zip", $errorCode);
                               if(!$contents) {
                                 if($errorCode == 0) {
                                     $sErrorNumber = '202';
                                     $customMsg = "ZIP file problems";
                                   }
                                   else
                                   $sErrorNumber = $errorCode;
                               }
                               @unlink($Config['UserFilesAbsolutePath']."/import/"."HtMlZiPImPoRt.zip");
                             }
                           } else {

                             // Load file
                             $contents = join("", file($sFileName));
                             # replace only absolute paths
                             $InlineFiles = array();
                             GetInlineFiles($contents, $InlineFiles);
                             $aPath = substr($sFileName, 0, strrpos($sFileName, '/') + 1);
                             for($i=0; $i<count($InlineFiles); $i++) {
                                $contents = str_replace('"'.$InlineFiles[$i].'"', '"'.$aPath.$InlineFiles[$i].'"', $contents);
                                $contents = str_replace('&quot;'.$InlineFiles[$i].'&quot;', '&quot;'.$aPath.$InlineFiles[$i].'&quot;', $contents);
                                $contents = str_replace('url('.$InlineFiles[$i].')', 'url('.$aPath.$InlineFiles[$i].')', $contents);
                                $contents = str_replace("'".$InlineFiles[$i]."'", "'".$aPath.$InlineFiles[$i]."'", $contents);
                             }

                             // change links
                             $Links = array();
                             $xLinks = array();

                             // href
                             preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $contents, $out, PREG_SET_ORDER);
                             for($i=0;$i<count($out);$i++) {
                               if( $out[$i][2] == "" ) continue;
                               if( !preg_match("/^http:\/\//i", $out[$i][2]) && !preg_match("/^https:\/\//i", $out[$i][2]) && !preg_match("/^javascript\:/i", $out[$i][2]) && !preg_match("/^mailto\:/i", $out[$i][2]) )
                                    $xLinks[] = $out[$i][2];
                             }

                             // only unique links
                             $x = array_unique($xLinks);
                             foreach ($x as $key => $value)
                               $Links[] = $value;

                             for($i=0; $i<count($Links); $i++) {
                               if($Links[$i] == "/") continue; // ignore this links
                               $contents = str_replace('href="'.$Links[$i], 'href="'.$aPath.$Links[$i], $contents);
                             }

                             if($contents == "")
                              $sErrorNumber = '202';
                          }
                 }
            }
             else
               $sErrorNumber = '208';
   }
    else
      if ( isset( $_FILES['NewFile'] ) && !is_null( $_FILES['NewFile']['tmp_name'] ) )
      {

              $oFile = $_FILES['NewFile'] ;
              $sFileName = $oFile['name'] ;

              // $sFileName = SanitizeFileName( $sFileName ) ;
              // Get the extension.
              $sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
              $sExtension = strtolower( $sExtension ) ;

              if ( isset( $config['HtmlExtensions'] ) )
              {
                      if ( !IsHtmlExtension( $sExtension, $config['HtmlExtensions'] ) )
                      {
                              $sErrorNumber = '202' ;
                      }

                      if(!$config['ZIPSupport'] &&  ($sExtension == "zip"))
                        $sErrorNumber = '310' ;

                      if ( $sErrorNumber == 0  && ($sExtension != "zip") &&
                              ( $detectHtml = DetectHtml( $oFile['tmp_name'] ) ) === false )
                      {
                              $sErrorNumber = '202' ;

                      }

              } else {
                 $sErrorNumber = '208' ;
                 $customMsg = "No HTML Extensions set";
              }

              if(!$sErrorNumber && $sExtension == "zip" && $config['ZIPSupport']){
                $errorCode = 0;
                $contents = LoadZIPFile($oFile['tmp_name'], $errorCode);
                if(!$contents) {
                  if($errorCode == 0) {
                      $sErrorNumber = '202';
                      $customMsg = "ZIP file problems";
                    }
                    else
                    $sErrorNumber = $errorCode;
                }
                @unlink($oFile['tmp_name']);
              }
              else
              if ( !$sErrorNumber  )
              {
                $contents = join("", file($oFile['tmp_name']));
                if($contents == "")
                     $sErrorNumber = '202';
                @unlink($oFile['tmp_name']);
              }
              else
                $sErrorNumber = '202' ;
      }
      else {
        #if( !empty($_POST["InternetFile"]) )
        $sErrorNumber = '1' ;
        $customMsg = 'Nothing to do.';
     }


 if($sErrorNumber == 0) {
   $contents = CleanUpHTML($contents);
   $charset = GetHTMLCharSet($contents);
   if(! ($charset == "utf-8" || $charset == "") ) {
     $contents = SetHTMLCharSet($contents, "utf-8", true);
     if(strpos($contents, "<!DOCTYPE") === false)
        $contents = '<!DOCTYPE html>'.$contents;
     $contents = @htmlentities($contents, ENT_NOQUOTES, $charset);
   } else {
     $savecontents = $contents;
     if($charset == "utf-8" || IsUtf8String($contents))
       $contents = UTF8ToEntities($contents);
       else
       $contents = @htmlentities($contents, ENT_NOQUOTES, $charset); // IE has problems with utf-8 when there are not encoded
     if(!$contents)
       $contents = $savecontents;

     if(stripos($contents, "<!DOCTYPE") === false) {
        $contents = '<!DOCTYPE html>'.$contents;
     }
   }

   if($contents == "") {
     $contents = "contents empty or not parseable";
   }

   $contents = str_replace("<", "&lt;", $contents);
   $contents = str_replace(">", "&gt;", $contents);
   if($charset != "")
     $charset = '<--filecharset=' . strtolower($charset) . '!-->';
   print '<body><span id="UploadedFileContent">'.$charset.$contents."</span></body>";
 }

 SendUploadResults( $sErrorNumber, $customMsg, $sFileName ) ;
 exit ;
}

/**
 * Check whether given extension is in html etensions list
 *
 * @param string $ext
 * @param array $htmlExtensions
 * @return boolean
 */
function IsHtmlExtension( $ext, $htmlExtensions )
{
        if ( !$htmlExtensions || !is_array( $htmlExtensions ) )
        {
                return false ;
        }
        $lcaseHtmlExtensions = array() ;
        foreach ( $htmlExtensions as $key => $val )
        {
                $lcaseHtmlExtensions[$key] = strtolower( $val ) ;
        }
        return in_array( $ext, $lcaseHtmlExtensions ) ;
}

/**
 * Detect HTML in the first KB to prevent against potential security issue with
 * IE/Safari/Opera file type auto detection bug.
 * Returns true if file contain insecure HTML code at the beginning.
 *
 * $filePath != false file is loaded and checked
 * $filePath == false AND $contentsToCheck != false $contentsToCheck is checked
 *
 * @param string $filePath absolute path to file
 * @return boolean
 */
function DetectHtml($filePath, $contentsToCheck = false)
{
        if($filePath !== false) {
          $fp = @fopen( $filePath, 'rb' ) ;

          //open_basedir restriction, see #1906
          if ( $fp === false || !flock( $fp, LOCK_SH ) )
          {
                  return -1 ;
          }

          $chunk = fread( $fp, 1024 ) ;
          flock( $fp, LOCK_UN ) ;
          fclose( $fp ) ;
        } elseif($contentsToCheck)
           $chunk = $contentsToCheck;
           else
           return false;

        $chunk = strtolower( $chunk ) ;

        if (!$chunk)
        {
                return false ;
        }

        $chunk = trim( $chunk ) ;

        if ( preg_match( "/<!DOCTYPE\W*X?HTML/sim", $chunk ) )
        {
                return true;
        }

        $tags = array( '<body', '<head', '<html', '<img', '<pre', '<script', '<table', '<title' ) ;

        foreach( $tags as $tag )
        {
                if( false !== strpos( $chunk, $tag ) )
                {
                        return true ;
                }
        }

        //type = javascript
        if ( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim', $chunk ) )
        {
                return true ;
        }

        //href = javascript
        //src = javascript
        //data = javascript
        if ( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
        {
                return true ;
        }

        //url(javascript
        if ( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
        {
                return true ;
        }

        return false ;
}



function LoadZIPFile($ZIPFileName, &$errorCode){
 global $Config, $config;

 if( empty($Config['UserFilesAbsolutePath']) || empty($Config['UserFilesPath']) || empty($ZIPFileName) )
   return false;

 $contents = false;
 $errorCode = 0;
 $zip = new ZipArchive;
 if($zip){
   $res = $zip->open($ZIPFileName);
   if(!$res) {
     $errorCode = 301;
     return false;
   }

   for($i = 0; $i < $zip->numFiles; $i++) {
      $sFileName = $zip->getNameIndex($i);
      if(strpos($sFileName, "\\") !== false || strpos($sFileName, "/") !== false) continue;
      $sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
      $sExtension = strtolower( $sExtension ) ;
      if( !IsHtmlExtension($sExtension, $config['HtmlExtensions']) )
         continue;

      $fp = $zip->getStream($sFileName);
      if(!$fp) return false;
      $contents = "";
      while (!feof($fp)) {
         $contents .= fread($fp, 1024);
      }
      fclose($fp);

      if(!DetectHtml(false, $contents))
        $contents = false;
     }
   if(!$contents){
     $errorCode = 302;
     return false;
   }

   $images = array();
   for($i = 0; $i < $zip->numFiles; $i++) {
      $sFileName = $zip->getNameIndex($i);
      if(strpos($sFileName, "\\") !== false /*|| strpos($sFileName, "/") !== false*/) continue;
      $sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
      $sExtension = strtolower( $sExtension ) ;
      if( !IsHtmlExtension($sExtension, $config['images']) )
         continue;
      $images[] = $sFileName;
     }

   if(!$zip->extractTo($Config['UserFilesAbsolutePath'].'image/', $images)){
     $errorCode = 303;
     return false;
   }

   $zip->close();

   if(count($images)) {
     $InlineFiles = array();
     GetInlineFiles($contents, $InlineFiles);

     reset($InlineFiles);
     foreach ($InlineFiles as $key => $value) {
         if(!in_array($value, $images)) continue;
         $regex   = array();
         $regex[] = '#(\s)((?i)src|background|href(?-i))\s*=\s*(["\']?)' .
                     preg_quote($value, '#') . '\3#';
         #$regex[] = '#(?i)url(?-i)\(\s*(["\']?)' .
         #            preg_quote($value, '#') . '\1\s*\)#';

         $rep   = array();
         $rep[] = '\1\2=\3' . $Config['UserFilesPath'] . "image/" . $value .'\3';
         $rep[] = 'url(\1' . $Config['UserFilesPath'] . "image/" . $value . '\1)';

         $contents = preg_replace($regex, $rep, $contents);

         $contents = preg_replace("/(url\(\'" . preg_quote($value, '/') . "\'\))/", "url('" . $Config['UserFilesPath'] . "image/" . $value . "')", $contents);
         $contents = preg_replace("/(url\(" . preg_quote('"' . $value . '"', '/') . "\))/", "url('" . $Config['UserFilesPath'] . "image/" . $value . "')", $contents);
     }

   }

   return $contents;

 } else {
   $errorCode = 300;
   return false;
 }
}



?>

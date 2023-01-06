<?php
/**
 * PHP version 5
 *
 * @package    UASparser
 * @author     Jaroslav Mallat (http://mallat.cz/)
 * @copyright  Copyright (c) 2008 Jaroslav Mallat
 * @version    0.4 beta
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @link       http://user-agent-string.info/download/UASparser
 */

 /*
    changed to PHP4
    $autoupdate = false; ==> deactivated
*/

// view source this file and exit
#if ($_GET['source'] == "y") {   show_source(__FILE__);  exit; }

class UASparser
{
        var $IniUrl                  = 'http://user-agent-string.info/rpc/get_data.php?key=free&format=ini';
        var $VerUrl                  = 'http://user-agent-string.info/rpc/get_data.php?key=free&format=ini&ver=y';
        var $InfoUrl                 = 'http://user-agent-string.info';
        var $cache_dir       = null;
        var $updateInterval  = 86400; // 1 day
        var $autoupdate = false;

        var $_data                  = array();
        var $_ret                   = array();
        var $test                   = null;
        var $id_browser             = null;
        var $os_id                  = null;

        /*public function __construct() {
        } */


        function Parse($useragent = null) {
                $_ret['typ']                    = "unknown";
                $_ret['ua_family']              = "unknown";
                $_ret['ua_name']                = "unknown";
                $_ret['ua_url']                 = "unknown";
                $_ret['ua_company']             = "unknown";
                $_ret['ua_company_url'] = "unknown";
                $_ret['ua_icon']                = "unknown.png";
                $_ret["ua_info_url"]    = "unknown";
                $_ret["os_family"]              = "unknown";
                $_ret["os_name"]                = "unknown";
                $_ret["os_url"]                 = "unknown";
                $_ret["os_company"]             = "unknown";
                $_ret["os_company_url"] = "unknown";
                $_ret["os_icon"]                = "unknown.png";
                
                if (!isset($useragent)) {
                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                }
                $_data = $this->_loadData();
                if($_data) {

                        // crawler
                        foreach ($_data['robots'] as $test) {
                                if ($test[0] == $useragent) {
                                        $_ret['typ']                                                                                            = "Robot";
                                        if ($test[1]) $_ret['ua_family']                                                        = $test[1];
                                        if ($test[2]) $_ret['ua_name']                                                          = $test[2];
                                        if ($test[3]) $_ret['ua_url']                                                           = $test[3];
                                        if ($test[4]) $_ret['ua_company']                                                       = $test[4];
                                        if ($test[5]) $_ret['ua_company_url']                                           = $test[5];
                                        if ($test[6]) $_ret['ua_icon']                                                          = $test[6];
                                        if ($test[7]) { // OS set
                                                if ($_data['os'][$test[7]][0]) $_ret["os_family"]               = $_data['os'][$test[7]][0];
                                                if ($_data['os'][$test[7]][1]) $_ret["os_name"]                 = $_data['os'][$test[7]][1];
                                                if ($_data['os'][$test[7]][2]) $_ret["os_url"]                  = $_data['os'][$test[7]][2];
                                                if ($_data['os'][$test[7]][3]) $_ret["os_company"]              = $_data['os'][$test[7]][3];
                                                if ($_data['os'][$test[7]][4]) $_ret["os_company_url"]  = $_data['os'][$test[7]][4];
                                                if ($_data['os'][$test[7]][5]) $_ret["os_icon"]                 = $_data['os'][$test[7]][5];
                                        }
                                        if ($test[8]) $_ret['ua_info_url']                                                      = $this->InfoUrl.$test[8];
                                        return $_ret;
                                }
                        }

                        // browser
                        foreach ($_data['browser_reg'] as $test) {
                                if (@preg_match($test[0],$useragent,$info)) { // $info contains version
                                        $id_browser = $test[1];
                                        break;
                                }
                        }
                        if ($id_browser) { // browser detail
                                if ($_data['browser_type'][$_data['browser'][$id_browser][0]][0]) $_ret['typ']  = $_data['browser_type'][$_data['browser'][$id_browser][0]][0];
                                if ($_data['browser'][$id_browser][1]) $_ret['ua_family']                                               = $_data['browser'][$id_browser][1];
//                              if ($info[2]) { //it's inside
//                                      $_ret["ua_name"] = $_data['browser'][$id_browser][1]." ".$info[3]." (".$info[1]." ".$info[2]." inside)";
//                              }
//                              else {
                                       if(isset($info[1]))
                                        $_ret["ua_name"] = $_data['browser'][$id_browser][1]." ".$info[1];
                                        else
                                        $_ret["ua_name"] = $_data['browser'][$id_browser][1];
//                              }
                                if ($_data['browser'][$id_browser][2]) $_ret['ua_url']                                                  = $_data['browser'][$id_browser][2];
                                if ($_data['browser'][$id_browser][3]) $_ret['ua_company']                                              = $_data['browser'][$id_browser][3];
                                if ($_data['browser'][$id_browser][4]) $_ret['ua_company_url']                                  = $_data['browser'][$id_browser][4];
                                if ($_data['browser'][$id_browser][5]) $_ret['ua_icon']                                                 = $_data['browser'][$id_browser][5];
                                if ($_data['browser'][$id_browser][6]) $_ret['ua_info_url']                                             = $this->InfoUrl.$_data['browser'][$id_browser][6];
                        }

                        // browser OS
                        if (isset($_data['browser_os'][$id_browser])) { // os detail
                                $os_id = $_data['browser_os'][$id_browser][1];
                                if ($_data['os'][$os_id][0]) $_ret["os_family"]                 = $_data['os'][$os_id][0];
                                if ($_data['os'][$os_id][1]) $_ret["os_name"]                   = $_data['os'][$os_id][1];
                                if ($_data['os'][$os_id][2]) $_ret["os_url"]                    = $_data['os'][$os_id][2];
                                if ($_data['os'][$os_id][3]) $_ret["os_company"]                = $_data['os'][$os_id][3];
                                if ($_data['os'][$os_id][4]) $_ret["os_company_url"]    = $_data['os'][$os_id][4];
                                if ($_data['os'][$os_id][5]) $_ret["os_icon"]                   = $_data['os'][$os_id][5];
                                return $_ret;
                        }
                        foreach ($_data['os_reg'] as $test) {
                                if (@preg_match($test[0],$useragent)) {
                                        $os_id = $test[1];
                                        break;
                                }
                        }
                        if ($os_id) { // os detail
                                if ($_data['os'][$os_id][0]) $_ret["os_family"]                 = $_data['os'][$os_id][0];
                                if ($_data['os'][$os_id][1]) $_ret["os_name"]                   = $_data['os'][$os_id][1];
                                if ($_data['os'][$os_id][2]) $_ret["os_url"]                    = $_data['os'][$os_id][2];
                                if ($_data['os'][$os_id][3]) $_ret["os_company"]                = $_data['os'][$os_id][3];
                                if ($_data['os'][$os_id][4]) $_ret["os_company_url"]    = $_data['os'][$os_id][4];
                                if ($_data['os'][$os_id][5]) $_ret["os_icon"]                   = $_data['os'][$os_id][5];
                        }
                        return $_ret;
                }
                return $_ret;
        }

        function _loadData() {
                if(!$this->autoupdate){
                  if (file_exists($this->cacheDir."/uasdata.ini")) {
                          return _parse_ini_file($this->cacheDir."/uasdata.ini", true);
                  }
                  else {
                          //die('ERROR: No datafile (uasdata.ini in Cache Dir), maybe update the file manually.');
                          return false;
                  }
                }
                if (file_exists($this->cacheDir."/cache.ini")) {
                        $cacheIni = _parse_ini_file($this->cacheDir."/cache.ini");
                }
                else {
                        $this->_downloadData();
                }
                if ($cacheIni['lastupdate'] < time() - $this->updateInterval || $cacheIni['lastupdatestatus'] != "0") {
                        $this->_downloadData();
                }
                if (file_exists($this->cacheDir."/uasdata.ini")) {
                        return @_parse_ini_file($this->cacheDir."/uasdata.ini", true);
                }
                else {
                        die('ERROR: No datafile (uasdata.ini in Cache Dir), maybe update the file manually.');
                }
        }
        function _downloadData() {
                if(ini_get('allow_url_fopen')) {
                        if (file_exists($this->cacheDir."/cache.ini")) {
                                $cacheIni = _parse_ini_file($this->cacheDir."/cache.ini");
                        }
                        $ctx = stream_context_create(array('http' => array('timeout' => 5)));
                        !$ver = @file_get_contents($this->VerUrl, 0, $ctx);
                        if($ini = @file_get_contents($this->IniUrl, 0, $ctx)) {
                                @file_put_contents($this->cacheDir."/uasdata.ini", $ini);
                                $staus = 0;
                        }
                        else {
                                if($cacheIni['localversion']) {
                                        $ver = $cacheIni['localversion'];
                                }
                                else {
                                        $ver = "none";
                                }
                                $staus = 1;
                        }
                        $cacheIni = "; cache info for class UASparser - http://user-agent-string.info/download/UASparser\n";
                        $cacheIni .= "[main]\n";
                        $cacheIni .= "localversion = \"".$ver."\"\n";
                        $cacheIni .= "lastupdate = \"".time()."\"\n";
                        $cacheIni .= "lastupdatestatus = \"".$staus."\"\n";
                        @file_put_contents($this->cacheDir."/cache.ini", $cacheIni);
                }
                else {
                        die('ERROR: function file_get_contents not allowed URL open. Update the datafile (uasdata.ini in Cache Dir) manually.');
                }
        }
        function SetCacheDir($cache_dir) {
                if ($this->autoupdate && !is_writable($cache_dir)) {
                        die('ERROR: Cache dir('.$cache_dir.') is not writable');
                }
                $cache_dir = realpath($cache_dir);
                $this->cacheDir = $cache_dir;
        }
}

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

if (!function_exists('file_get_contents')) {
    function file_get_contents($filename) {
      $fhandle = @fopen($filename, "r");
      if(!$fhandle) return false;
      $fcontents = fread($fhandle, filesize($filename));
      fclose($fhandle);
      return $fcontents;
    }
}

if(version_compare(phpversion(), "5.0.0", "<")) {  # PHP versions < 5.0 can't parse arrays [] in ini files
  function _parse_ini_file($file, $process_sections = false) {
    $process_sections = ($process_sections !== true) ? false : true;

    $ini = @file($file);
    if (count($ini) == 0) {return array();}

    $sections = array();
    $values = array();
    $result = array();
    $globals = array();
    $i = 0;
    foreach ($ini as $line) {
      $line = trim($line);
      $line = str_replace("\t", " ", $line);

      // Comments
      if (!preg_match('/^[a-zA-Z0-9[]/', $line)) {continue;}

      // Sections
      if ($line[0] == '[') {
        $tmp = explode(']', $line);
        $sections[] = trim(substr($tmp[0], 1));
        $i++;
        continue;
      }

      // Key-value pair
      list($key, $value) = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value);
      if (strstr($value, ";")) {
        $tmp = explode(';', $value);
        if (count($tmp) == 2) {
          if ((($value[0] != '"') && ($value[0] != "'")) ||
              preg_match('/^".*"\s*;/', $value) || preg_match('/^".*;[^"]*$/', $value) ||
              preg_match("/^'.*'\s*;/", $value) || preg_match("/^'.*;[^']*$/", $value) ){
            $value = $tmp[0];
          }
        } else {
          if ($value[0] == '"') {
            $value = preg_replace('/^"(.*)".*/', '$1', $value);
          } elseif ($value[0] == "'") {
            $value = preg_replace("/^'(.*)'.*/", '$1', $value);
          } else {
            $value = $tmp[0];
          }
        }
      }
      $value = trim($value);
      $value = trim($value, "'\"");

      if ($i == 0) {
        if (substr($key, -2, 2) == '[]') {
          $globals[str_replace("[]", "", $key)][] = $value;
        } else {
          $globals[$key] = $value;
        }
      } else {
        if (substr($key, -2, 2) == '[]') {
          $values[$i-1][str_replace("[]", "", $key)][] = $value;
        } else {
          $values[$i-1][$key] = $value;
        }
      }
    }

    for($j = 0; $j < $i; $j++) {
      if ($process_sections === true) {
        $result[$sections[$j]] = $values[$j];
      } else {
        $result[] = $values[$j];
      }
    }

    return $result + $globals;
  }
} else {
    function _parse_ini_file($file, $process_sections = false) {
       return @parse_ini_file($file, $process_sections);
    }
}

?>

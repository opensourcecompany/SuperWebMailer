<?php
/**
 *      Filemanager PHP class
 *
 *      filemanager.class.php
 *      class for the filemanager.php connector
 *
 *      @license        MIT License
 *      @author         Riaan Los <mail (at) riaanlos (dot) nl>
 *      @author         Simon Georget <simon (at) linea21 (dot) com>
 *      @copyright      Authors
 */

if ( !function_exists('IsUTF8string') ) {
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

 function IsUTF8String( $s ) {
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

class Filemanager {

  // @protected
  var $config = array();
  var $language = array();
  var $get = array();
  var $post = array();
  var $properties = array();
  var $item = array();
  var $languages = array();
  var $root = '';
  var $doc_root = '';

  function Filemanager($config) {
     global $Config;
     $this->config = $config;
     $this->root = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR;
     $this->properties = array(
             'Date Created'=>null,
             'Date Modified'=>null,
             'Height'=>null,
             'Width'=>null,
             'Size'=>null
     );
     if (isset($this->config['doc_root'])) {
             $this->doc_root = $this->config['doc_root'];
     } else {
             $this->doc_root = $_SERVER['DOCUMENT_ROOT'];
     }

     $this->setParams();
     $this->availableLanguages();
     $this->loadLanguageFile();

  }

#  function __construct($config) {
#    $this->Filemanager($config);
#  }

  // @public
  function error($string,$textarea=false) {
    $array = array(
                        'Error'=>$string,
                        'Code'=>'-1',
                        'Properties'=>$this->properties
    );
    if($textarea) {
      echo '<textarea>' . json_encode($array) . '</textarea>';
    } else {
      echo json_encode($array);
    }
    die();
  }

  // @public
  function lang($string) {
    if(isset($this->language[$string]) && $this->language[$string]!='') {
      return $this->language[$string];
    } else {
      return 'Language string error on ' . $string;
    }
  }

  // @public
  function getvar($var) {
    global $Config;
    if(!isset($_GET[$var]) || $_GET[$var]=='') {
      $this->error(sprintf($this->lang('INVALID_VAR'),$var));
    } else {
      $this->get[$var] = $this->sanitize($_GET[$var]);
      if($var == "path" && strpos($this->get[$var], $Config['UserFilesAbsolutePath']) === false ) {
        $this->error(sprintf($this->lang('INVALID_DIRECTORY_OR_FILE'),$var));
        return false;
      }
      return true;
    }
  }

  // @public
  function postvar($var) {
    if(!isset($_POST[$var]) || $_POST[$var]=='') {
      $this->error(sprintf($this->lang('INVALID_VAR'),$var));
    } else {
      $this->post[$var] = $_POST[$var];
      return true;
    }
  }

  // @public
  function getinfo() {
    $this->item = array();
    $this->item['properties'] = $this->properties;
    $this->get_file_info();
    $full_path = $this->doc_root .$this->get['path'];

    $array = array(
                        'Path'=> $this->get['path'],
                        'Filename'=>$this->item['filename'],
                        'File Type'=>$this->item['filetype'],
                        'Preview'=>$this->item['preview'],
                        'Properties'=>$this->item['properties'],
                        'Error'=>"",
                        'Code'=>0
    );
    return $array;
  }

  // @public
  function getfolder() {
    $array = array();
    $current_path = $this->doc_root . $this->get['path'];
    if(!is_dir($current_path)) {
      $this->error(sprintf($this->lang('DIRECTORY_NOT_EXIST'),$current_path));
    }
    if(!$handle = opendir($current_path)) {
      $this->error(sprintf($this->lang('UNABLE_TO_OPEN_DIRECTORY'),$current_path));
    } else {

      // M.B.
      $files = array();
      while (false !== ($file = readdir($handle))) {
       if($file == "index.php") continue;
       if($file != "." && $file != ".." && is_dir($current_path . $file)) {
         $files[] = "DIR->".$file;
       } else
         $files[] = "FILE->".$file;
      }
      closedir($handle);
      sort($files);
      // M.B.

      #while (false !== ($file = readdir($handle))) {
      reset($files);
      foreach($files as $filesKey => $file) {
        $file = substr($file, strpos($file, "->") + 2); // remove DIR-> or FILE->
        if($file != "." && $file != ".." && is_dir($current_path . $file)) {
          if(!in_array($file, $this->config['unallowed_dirs'])) {
            $array[$this->get['path'] . $file .'/'] = array(
                                                'Path'=> $this->get['path'] . $file .'/',
                                                'Filename'=>$file,
                                                'File Type'=>'dir',
                                                'Preview'=> $this->config['icons']['path'] . $this->config['icons']['directory'],
                                                'Properties'=>array(
                                                        'Date Created'=>null,
                                                        'Date Modified'=>null,
                                                        'Height'=>null,
                                                        'Width'=>null,
                                                        'Size'=>null
            ),
                                                'Error'=>"",
                                                'Code'=>0
            );
          }
        } else if ($file != "." && $file != ".."  && !in_array($file, $this->config['unallowed_files'])) {
          $this->item = array();
          $this->item['properties'] = $this->properties;
          $this->get_file_info($this->get['path'] . $file);

          if(!isset($this->params['type']) || (isset($this->params['type']) && strtolower($this->params['type'])=='images' && in_array(strtolower($this->item['filetype']),$this->config['images']))) {
            if($this->config['upload']['imagesonly']== false || ($this->config['upload']['imagesonly']== true && in_array(strtolower($this->item['filetype']),$this->config['images']))) {
              $array[$this->get['path'] . $file] = array(
                                                        'Path'=> $this->get['path'] . $file,
                                                        'Filename'=>$this->item['filename'],
                                                        'File Type'=>$this->item['filetype'],
                                                        'Preview'=>$this->item['preview'],
                                                        'Properties'=>$this->item['properties'],
                                                        'Error'=>"",
                                                        'Code'=>0
              );
            }
          }
        }
      }
      #closedir($handle);
    }
    return $array;
  }

  // @public
  function resample() {

    $filename = $this->get['filename'];
    $current_width =  $this->get['current_width'];
    $current_height = $this->get['current_height'];
    $newwidth = $this->get['newwidth'];
    $newheight = $this->get['newheight'];

    $result = $this->image_resize($filename, $filename, $newwidth, $newheight, 0);

    if($result == 1) {
      $array = array(
                          'Error'=>"",
                          'Code'=>0
      );
      return $array;
    } else {
      $this->error(sprintf($this->lang('ERROR_WHILECHANGINGIMGSIZE'), $result));
    }
  }

  // @public
  function image_resize($src, $dst, $width, $height, $crop=0){

    if(!list($w, $h) = getimagesize($src)) return $this->lang("ERROR_UNSUPPORTEDEXT");

    $type = strtolower(substr(strrchr($src,"."),1));
    if($type == 'jpeg') $type = 'jpg';
    switch($type){
      case 'bmp': $img = imagecreatefromwbmp($src); break;
      case 'gif': $img = imagecreatefromgif($src); break;
      case 'jpg': $img = imagecreatefromjpeg($src); break;
      case 'jpeg': $img = imagecreatefromjpeg($src); break;
      case 'png': $img = imagecreatefrompng($src); break;
      default : return "Unsupported picture type!";
    }

    if(!$img)
       return sprintf($this->lang('ERROR_CANTOPENSRCFILE'), $src);

    $new = imagecreatetruecolor($width, $height);

    // preserve transparency
    if($type == "gif" or $type == "png"){
      imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
      imagealphablending($new, false);
      imagesavealpha($new, true);
    }

    imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $w, $h);

    switch($type){
      case 'bmp': $ret = imagewbmp($new, $dst); break;
      case 'gif': $ret = imagegif($new, $dst); break;
      case 'jpg': $ret = imagejpeg($new, $dst); break;
      case 'jpeg': $ret = imagejpeg($new, $dst); break;
      case 'png': $ret = imagepng($new, $dst); break;
    }

    if(!$ret)
      return sprintf($this->lang('ERROR_CANTSAVEDSTFILE'), $dst);
      else
      return 1;
  }


  // @public
  function rename() {

    $suffix='';


    if(substr($this->get['old'],-1,1)=='/') {
      $this->get['old'] = substr($this->get['old'],0,(strlen($this->get['old'])-1));
      $suffix='/';
    }
    $tmp = explode('/',$this->get['old']);
    $filename = $tmp[(sizeof($tmp)-1)];
    $path = str_replace('/' . $filename,'',$this->get['old']);

    if(file_exists ($this->doc_root . $path . '/' . $this->get['new'])) {
      if($suffix=='/' && is_dir($this->doc_root . $path . '/' . $this->get['new'])) {
        $this->error(sprintf($this->lang('DIRECTORY_ALREADY_EXISTS'),$this->get['new']));
      }
      if($suffix=='' && is_file($this->doc_root . $path . '/' . $this->get['new'])) {
        $this->error(sprintf($this->lang('FILE_ALREADY_EXISTS'),$this->get['new']));
      }
    }

    if(strpos($this->get['new'], ".php") !== false || strpos($this->get['new'], ".phtml") !== false)
        $this->error(sprintf($this->lang('ERROR_RENAMING_FILE'),$filename,$this->get['new']));

    if(!rename($this->doc_root . $this->get['old'],$this->doc_root . $path . '/' . $this->get['new'])) {
      if(is_dir($this->get['old'])) {
        $this->error(sprintf($this->lang('ERROR_RENAMING_DIRECTORY'),$filename,$this->get['new']));
      } else {
        $this->error(sprintf($this->lang('ERROR_RENAMING_FILE'),$filename,$this->get['new']));
      }
    }
    $array = array(
                        'Error'=>"",
                        'Code'=>0,
                        'Old Path'=>$this->get['old'],
                        'Old Name'=>$filename,
                        'New Path'=>$path . '/' . $this->get['new'].$suffix,
                        'New Name'=>$this->get['new']
    );
    return $array;
  }

  // @public
  function delete($textarea=true, $quickupload = false) { // M.B. $textarea=true, $quickupload = false {
    if(is_dir($this->doc_root . $this->get['path'])) {
      $this->unlinkRecursive($this->doc_root . $this->get['path']);
      $array = array(
                                'Error'=>"",
                                'Code'=>0,
                                'Path'=>$this->get['path']
      );
      return $array;
    } else if(file_exists($this->doc_root . $this->get['path'])) {
      if(unlink($this->doc_root . $this->get['path'])) {
          $array = array(
                                    'Error'=>"",
                                    'Code'=>0,
                                    'Path'=>$this->get['path']
          );
      } else {
          $array = array(
                                    'Error'=>"Can't delete file ".$this->get['path'],
                                    'Code'=>1,
                                    'Path'=>$this->get['path']
          );
      }

      return $array;
    } else {
      if(!$quickupload)
         $this->error(sprintf($this->lang('INVALID_DIRECTORY_OR_FILE')));
         else
         return array('Error'=>"Can't delete file ".$this->get['path'], 'Code'=>1001, 'Path'=>$this->get['path']);
    }
  }

  // @public
  function add($textarea=true, $quickupload = false) { // M.B. $textarea=true, $quickupload = false
    $this->setParams();

    $files = $_FILES["newfile"];
    if(!is_array($files['name'])) {
      $files = array();
      $f=0;
      $files['name'][$f] = $_FILES["newfile"]["name"];
      $files['tmp_name'][$f] = $_FILES["newfile"]["tmp_name"];
      $files['size'][$f] = $_FILES["newfile"]["size"];
      $files['type'][$f] = $_FILES["newfile"]["type"];
      $files['error'][$f] = $_FILES["newfile"]["error"];
    }

#return array('Error'=> print_r($files, false) , 'Code'=>202);

    $newfile = array();
    $response = array();
    for($f=0;$f<count($files['name']);$f++){

      $newfile['name']=$files['name'][$f];
      $newfile['tmp_name']=$files['tmp_name'][$f];
      $newfile['size']=$files['size'][$f];
      $newfile['type']=$files['type'][$f];
      $newfile['error']=$files['error'][$f];

      if(empty($newfile['name'])) continue;

      if(!isset($newfile) || !is_uploaded_file($newfile['tmp_name'])) {
        if(!$quickupload)
          $this->error(sprintf($this->lang('INVALID_FILE_UPLOAD')),$textarea);
          else
          return array('Error'=>sprintf($this->lang('INVALID_FILE_UPLOAD')), 'Code'=>202);
      }
      if(($this->config['upload']['size']!=false && is_numeric($this->config['upload']['size'])) && ($newfile['size'] > ($this->config['upload']['size'] * 1024 * 1024))) {
        if(!$quickupload)
          $this->error(sprintf($this->lang('UPLOAD_FILES_SMALLER_THAN'),$this->config['upload']['size'] . 'Mb'),$textarea);
          else
          return array('Error'=>sprintf($this->lang('UPLOAD_FILES_SMALLER_THAN'),$this->config['upload']['size'] . 'Mb'), 'Code'=>1000);
      }
      if($this->config['upload']['imagesonly'] || (isset($this->params['type']) && strtolower($this->params['type'])=='images')) {
        if(!($size = @getimagesize($newfile['tmp_name']))){
          if(!$quickupload)
            $this->error(sprintf($this->lang('UPLOAD_IMAGES_ONLY')),$textarea);
            else
            return array('Error'=>sprintf($this->lang('UPLOAD_IMAGES_ONLY')), 'Code'=>202);
        }
        if(!in_array($size[2], array(1, 2, 3, 7, 8))) {
          if(!$quickupload)
            $this->error(sprintf($this->lang('UPLOAD_IMAGES_TYPE_JPEG_GIF_PNG')),$textarea);
            else
            return array('Error'=>sprintf($this->lang('UPLOAD_IMAGES_TYPE_JPEG_GIF_PNG')), 'Code'=>202);
        }
      }

      // M.B.
      if($this->config['upload']['filesonly']  || (isset($this->params['type']) && strtolower($this->params['type'])=='files')) {
         // Get the extension.
         $sExtension = substr( $newfile['name'], ( strrpos($newfile['name'], '.')  ) ) ;
         $sExtension = strtolower( $sExtension ) ;
         if(!preg_match("/".$this->config['upload']['LinkUploadAllowedExtensions']."/", $sExtension))
           if(!$quickupload)
            $this->error(sprintf($this->lang('INVALID_FILE_UPLOAD')),$textarea);
            else
            return array('Error'=>sprintf($this->lang('INVALID_FILE_UPLOAD')), 'Code'=>202);

      }
      //

      $newfile['name'] = $this->cleanString($newfile['name'],array('.','-'));
      if(!$this->config['upload']['overwrite']) {
        $newfile['name'] = $this->checkFilename($this->doc_root . $this->post['currentpath'],$newfile['name']);
      }

      if(strpos($newfile['name'], ".php") !== false || strpos($newfile['name'], ".phtml") !== false || strpos($newfile['name'], ".js") !== false)
         $response = array('Error'=>sprintf("UPLOAD ERROR Scripts not allowed."), 'Code'=>1000);
         else
          if(!move_uploaded_file($newfile['tmp_name'], $this->doc_root . $this->post['currentpath'] . $newfile['name'])){
             $response[] = array('Error'=>sprintf("UPLOAD ERROR e.g. access denied or no space left on device."), 'Code'=>1000);
          } else {
             chmod($this->doc_root . $this->post['currentpath'] . $newfile['name'], 0755);
             $response[] = array(
                            'Path'=>$this->post['currentpath'],
                            'Name'=>$newfile['name'],
                            'Error'=>"",
                            'Code'=>0
             );
          }

    } # for f

    if($textarea) { // M.B.
      echo '<textarea>' . json_encode($response) . '</textarea>';
      die();
    } else // M.B.
      return $response; // M.B.
  }


  // @public
  function addfolder() {
    if(is_dir($this->doc_root . $this->get['path'] . $this->get['name'])) {
      $this->error(sprintf($this->lang('DIRECTORY_ALREADY_EXISTS'),$this->get['name']));

    }
    $newdir = $this->cleanString($this->get['name']);
    if(!mkdir($this->doc_root . $this->get['path'] . $newdir, 0755)) {
      $this->error(sprintf($this->lang('UNABLE_TO_CREATE_DIRECTORY'),$newdir));
    } else {
      @chmod($this->doc_root . $this->get['path'] . $newdir, 0755);
    }
    $array = array(
                        'Parent'=>$this->get['path'],
                        'Name'=>$this->get['name'],
                        'File Type'=>'dir', // M.B.
                        'Error'=>"",
                        'Code'=>0
    );
    return $array;
  }

  // @public
  function download() {

    if(isset($this->get['path']) && file_exists($this->doc_root .$this->get['path'])) {
      header("Content-type: application/force-download");
      header('Content-Disposition: inline; filename="' . basename($this->get['path']) . '"');
      header("Content-Transfer-Encoding: Binary");
      header("Content-length: ".filesize($this->doc_root . $this->get['path']));
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($this->get['path']) . '"');
      header('Expires: 0');
      header('Pragma: no-cache');
      readfile($this->doc_root . $this->get['path']);
    } else {
      $this->error(sprintf($this->lang('FILE_DOES_NOT_EXIST'),$this->get['path']));
    }
  }

  // @public
  function preview() {

    if(isset($this->get['path']) && file_exists($this->doc_root . $this->get['path'])) {
      header("Content-type: image/" .$ext = pathinfo($this->get['path'], PATHINFO_EXTENSION));
      header("Content-Transfer-Encoding: Binary");
      header("Content-length: ".filesize($this->doc_root . $this->get['path']));
      header('Content-Disposition: inline; filename="' . basename($this->get['path']) . '"');
      header('Expires: 0');
      header('Pragma: no-cache');
      readfile($this->doc_root . $this->get['path']);
    } else {
      $this->error(sprintf($this->lang('FILE_DOES_NOT_EXIST'),$this->get['path']));
    }
  }

  // @private
  function setParams() {
    $tmp = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/');
    $tmp = explode('?',$tmp);
    $params = array();
    if(isset($tmp[1]) && $tmp[1]!='') {
      $params_tmp = explode('&',$tmp[1]);
      if(is_array($params_tmp)) {
        foreach($params_tmp as $value) {
          $tmp = explode('=',$value);
          if(isset($tmp[0]) && $tmp[0]!='' && isset($tmp[1]) && $tmp[1]!='') {
            $params[$tmp[0]] = $tmp[1];
          }
        }
      }
    }
    // M.B.
    if(empty($params["type"]))
      if($this->config['upload']['filesonly'])
        $params["type"] = "Files";
      else
        $params["type"] = "Images";
    // M.B.

    $this->params = $params;
  }


  // @private
  function get_file_info($path='',$return=array()) {
    if($path=='') {
      $path = $this->get['path'];
    }
    $tmp = explode('/',$path);
    $this->item['filename'] = $tmp[(sizeof($tmp)-1)];

    $tmp = explode('.',$this->item['filename']);
    $this->item['filetype'] = $tmp[(sizeof($tmp)-1)];
    $this->item['filemtime'] = filemtime($this->doc_root . $path);
    $this->item['filectime'] = filectime($this->doc_root . $path);

    $this->item['preview'] = $this->config['icons']['path'] . $this->config['icons']['default'];

    if(is_dir($this->doc_root . $path)) {

      $this->item['preview'] = $this->config['icons']['path'] . $this->config['icons']['directory'];
      $this->item['filetype'] = "dir"; // M.B.

    } else if(in_array(strtolower($this->item['filetype']),$this->config['images'])) {

      $this->item['preview'] = 'connectors/php/filemanager.php?mode=preview&path=' . $path;
      //if(isset($get['getsize']) && $get['getsize']=='true') {
      list($width, $height, $type, $attr) = @getimagesize($this->doc_root . $path);
      $this->item['properties']['Height'] = $height;
      $this->item['properties']['Width'] = $width;
      $this->item['properties']['Size'] = filesize($this->doc_root . $path);
      //}

    } else if(file_exists($this->root . $this->config['icons']['path'] . strtolower($this->item['filetype']) . '.png')) {

      $this->item['preview'] = $this->config['icons']['path'] . strtolower($this->item['filetype']) . '.png';
      $this->item['properties']['Size'] = filesize($this->doc_root . $path);

    }

    $this->item['properties']['Date Modified'] = date($this->config['date'], $this->item['filemtime']);
    //$return['properties']['Date Created'] = date($config['date'], $return['filectime']); // PHP cannot get create timestamp
  }


  // @private
  function unlinkRecursive($dir,$deleteRootToo=true) {
    if(!$dh = @opendir($dir)) {
      return;
    }
    while (false !== ($obj = readdir($dh))) {
      if($obj == '.' || $obj == '..') {
        continue;
      }

      if (!@unlink($dir . '/' . $obj)) {
        $this->unlinkRecursive($dir.'/'.$obj, true);
      }
    }

    closedir($dh);

    if ($deleteRootToo) {
      @rmdir($dir);
    }
    return;
  }

  // @public // M.B.
  function cleanString($string, $allowed = array()) {
    $allow = null;

    if (!empty($allowed)) {
      foreach ($allowed as $value) {
        $allow .= "\\$value";
      }
    }


    // M.B.
    $sFileName = $string;
    if(IsUTF8string($sFileName))
      $sFileName =  utf8_decode($sFileName);
    $s = "";
    $sFileName = str_replace(" ", "_", $sFileName);
    $sFileName = str_replace("-", "_", $sFileName);
    for($i=0; $i<strlen($sFileName); $i++) {
       if (
           (ord($sFileName{$i}) >= 0x30 && ord($sFileName{$i}) <= 0x39) ||
           (ord($sFileName{$i}) >= 0x41 && ord($sFileName{$i}) <= 0x5A) ||
           (ord($sFileName{$i}) >= 0x61 && ord($sFileName{$i}) <= 0x7A) ||
           (ord($sFileName{$i}) == 0x5F) ||
           ($sFileName{$i} == '.')
          ) {
            $s = $s . $sFileName{$i};
          } else {
            switch(ord($sFileName{$i})) {
              case 0xC4: $s = $s . "Ae";
                    break;
              case 0xDC: $s = $s . "Ue";
                    break;
              case 0xD6: $s = $s . "Oe";
                    break;
              case 0xE4: $s = $s . "ae";
                    break;
              case 0xFC: $s = $s . "ue";
                    break;
              case 0xF6: $s = $s . "oe";
                    break;
              case 0xDF: $s = $s . "ss";
                    break;
            }
          }
    }
    $string = $s;
    // M.B.

    $mapping = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', ' '=>'_', "'"=>'_', '/'=>''
        );

        if (is_array($string)) {

          $cleaned = array();

          foreach ($string as $key => $clean) {
            $clean = strtr($clean, $mapping);
            $clean = preg_replace("/[^{$allow}_a-zA-Z0-9]/", '', $clean);
            $cleaned[$key] = preg_replace('/[_]+/', '_', $clean); // remove double underscore
          }
        } else {
          $string = strtr($string, $mapping);
          $string = preg_replace("/[^{$allow}_a-zA-Z0-9]/", '', $string);
          $cleaned = preg_replace('/[_]+/', '_', $string); // remove double underscore
        }
        return $cleaned;
  }

  // @private
  function sanitize($var) {
    $sanitized = strip_tags($var);
    $sanitized = str_replace('http://', '', $sanitized);
    $sanitized = str_replace('https://', '', $sanitized);
    $sanitized = str_replace('../', '', $sanitized);
    return $sanitized;
  }

  // @private
  function checkFilename($path,$filename,$i='') {
    if(!file_exists($path . $filename)) {
      return $filename;
    } else {
      $_i = $i;
      $tmp = explode(/*$this->config['upload']['suffix'] . */$i . '.',$filename);
      if($i=='') {
        $i=1;
      } else {
        $i++;
      }
      $filename = str_replace($_i . '.' . $tmp[(sizeof($tmp)-1)],$i . '.' . $tmp[(sizeof($tmp)-1)],$filename);
      return $this->checkFilename($path,$filename,$i);
    }
  }

  // @private
  function loadLanguageFile() {

    // we load langCode var passed into URL if present and if exists
    // else, we use default configuration var
    $lang = $this->config['culture'];
    if(isset($this->params['langCode']) && in_array($this->params['langCode'], $this->languages)) $lang = $this->params['langCode'];

    if(file_exists($this->root. 'scripts/languages/'.$lang.'.js')) {
      $stream =file_get_contents($this->root. 'scripts/languages/'.$lang.'.js');
      $this->language = json_decode($stream, true);
    } else {
      $stream =file_get_contents($this->root. 'scripts/languages/'.$lang.'.js');
      $this->language = json_decode($stream, true);
    }
  }

  // @private
  function availableLanguages() {

    if ($handle = opendir($this->root.'/scripts/languages/')) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
          if(defined("PATHINFO_FILENAME")) // PHP < 5.2
            array_push($this->languages, pathinfo($file, PATHINFO_FILENAME));
            else {
              if (strrpos($file, '.') !== false)
                array_push($this->languages, substr($file, 0, strrpos($file,'.')));
                else
                array_push($this->languages, $file);
            }
        }
      }
      closedir($handle);
    }
  }
}
?>

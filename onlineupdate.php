<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("onlineupdate.inc.php");

  define("OnlineUpdate", 1);
  
  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeOnlineUpdate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!isset($_POST["step"]) || intval($_POST["step"]) == 0) {
    $_POST["step"] = "1";
  }

  if( (defined("DEMO") && $UserType !== "SuperAdmin") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()) ){
    if(isset($_POST["NextBtn"]))
      unset($_POST["NextBtn"]);
  }    
  
  $_Itfj8 = "";
  $_foL0Q = false;
  $_J0COJ = "";
  $_foLOl = "";
  
  if(isset($_POST["NextBtn"])) {

      switch($_POST["step"]) {
      case 1:
        $_foL0Q = !_J088O($_J1t6J . "current_update.zip", $_J0COJ, $_foLOl);
        break;
      case 2:
        $_foL0Q = !_J0PQJ($_J1t6J . "current_update.zip", $_POST["XID"], $_J0COJ);
        break;
      default:  
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", "Online update error");
        print $_QLJfI;
        exit;
      }  

  }  

  if(isset($_POST["NextBtn"])){
    $_POST["step"]++;
    if((defined("DEMO") && $UserType !== "SuperAdmin") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()) )
     $_POST["step"] = 1;
  }      
    
    
  switch($_POST["step"]) {
    case 1:
      $_QLJfI = GetMainTemplate(False, $UserType, $Username, False, $resourcestrings[$INTERFACE_LANGUAGE]["090300"], $_Itfj8, 'DISABLED', 'ou1_snipped.htm');
      
      _JJCCF($_QLJfI);
      
      if((defined("DEMO") && $UserType !== "SuperAdmin") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()) ){
        $_QLJfI = _L80DF($_QLJfI, "<ISNOT:DEMO>");
        $_QLJfI = _L80DF($_QLJfI, "<IS:UPDATE_AVAIL>");
        $_QLJfI = _L80DF($_QLJfI, "<NEXTBTN>");
        $_QLJfI = _L80DF($_QLJfI, "<IS:NOTUPDATE_AVAIL>");
        $_QLJfI = str_replace("./onlineupdate.php", "./dashboard.php", $_QLJfI);
        print $_QLJfI;
        exit;
      }
      
      $_QLJfI = _L80DF($_QLJfI, "<IS:DEMO>", "</IS:DEMO>");
      $_QLJfI = _L8OF8($_QLJfI, "<ISNOT:DEMO>");

      $_JoiQi = "";
      $_JoiJL = 0;
      $id = -1;
      if(!_J0RCP($_JoiQi, $_JoiJL, $id, $OwnerUserId == 0, true)){
        $_JoiQi = $_Ij6Lj;
      }

      $_QLJfI = _L81BJ($_QLJfI, "<PRODUCTVERSION>", "</PRODUCTVERSION>", $_Ij6Lj);
      $_QLJfI = _L81BJ($_QLJfI, "<SHOW:NEWVERSION>", "</SHOW:NEWVERSION>", $_JoiQi);

      if ( $_JoiQi != "" && $_JoiJL != 0 ) {
        if ( version_compare($_Ij6Lj, $_JoiQi, "<") ) {
         
          $_foL0Q = array();
          if(!_J0PEE($_foL0Q)){
            $_QLJfI = _LRFCO("</IS:AUTO_UPDATE_NOT_POSSIBLE>",  "</IS:AUTO_UPDATE_NOT_POSSIBLE>" . '<textarea style="width: 90%; height: 200px;">' . join($_QLl1Q, $_foL0Q) . "</textarea>", $_QLJfI);
            $_QLJfI = _L8OF8($_QLJfI, "<IS:AUTO_UPDATE_NOT_POSSIBLE>");
            $_QLJfI = str_replace("./onlineupdate.php", "./dashboard.php", $_QLJfI);
            $_QLJfI = _L80DF($_QLJfI, "<NEXTBTN>");
          }
          $_QLJfI = _L80DF($_QLJfI, "<IS:NOTUPDATE_AVAIL>");
          
        }else{
          $_QLJfI = _L80DF($_QLJfI, "<IS:UPDATE_AVAIL>");
          $_QLJfI = str_replace("./onlineupdate.php", "./dashboard.php", $_QLJfI);
          $_QLJfI = _L80DF($_QLJfI, "<NEXTBTN>");
        }
        $_QLJfI = _L81BJ($_QLJfI, "<SHOW:VERSIONDATE>", "</SHOW:VERSIONDATE>", date($ShortDateFormat, $_JoiJL));
      }else{
        $_QLJfI = _L80DF($_QLJfI, "<IS:UPDATE_AVAIL>");
        $_QLJfI = str_replace("./onlineupdate.php", "./dashboard.php", $_QLJfI);
        $_QLJfI = _L80DF($_QLJfI, "<NEXTBTN>");
      }  
      
      $_QLJfI = _L80DF($_QLJfI, "<IS:AUTO_UPDATE_NOT_POSSIBLE>");

      break;
    case 2:
      $_QLJfI = GetMainTemplate(False, $UserType, $Username, False, $resourcestrings[$INTERFACE_LANGUAGE]["090301"], $_Itfj8, 'DISABLED', 'ou2_snipped.htm');
      
      _JJCCF($_QLJfI);
      
      if(!$_foL0Q){
        $_QLJfI = _L80DF($_QLJfI, "<DOWNLOAD:FAILURE>");
        $_QLJfI = str_replace('name="XID"', 'name="XID" value="' . $_foLOl . '"', $_QLJfI);
        }
        else{
          $_QLJfI = _L80DF($_QLJfI, "<DOWNLOAD:SUCCESS>");
          $_QLJfI = str_replace("<!--DOWNLOADERRORTEXT-->", $_J0COJ, $_QLJfI);
        }
      
      break;
    case 3:
      $_QLJfI = GetMainTemplate(False, $UserType, $Username, False, $resourcestrings[$INTERFACE_LANGUAGE]["090302"], $_Itfj8, 'DISABLED', 'ou3_snipped.htm');
      
      _JJCCF($_QLJfI);
      
      if(!$_foL0Q){

        if(function_exists("opcache_reset"))
          opcache_reset();
        clearstatcache();
        if(function_exists("opcache_invalidate")){
          opcache_invalidate("config.inc.php", true);
          opcache_invalidate(InstallPath . "config.inc.php", true);
        }

        $_QLJfI = _L80DF($_QLJfI, "<UPDATE:FAILURE>");
        include_once("logout.php");
        }
        else{
          $_QLJfI = _L80DF($_QLJfI, "<UPDATE:SUCCESS>");
          $_QLJfI = str_replace("<!--INSTALLERRORTEXT-->", $_J0COJ, $_QLJfI);
          unlink($_J1t6J . "current_update.zip");
        }
      
      break;
    default:
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", "Online update error");
        print $_QLJfI;
        exit;
  }  
  

  print $_QLJfI;

  function _J0PEE(&$_foL0Q){
    global $_J1t6J;
    
    if(version_compare(PHP_VERSION, "7.0.0", "<")){
      $_foL0Q[] = "PHP_VERSION < 7.0.0";
      return false;
    }  

    if(!class_exists("ZipArchive")){
      $_foL0Q[] = "class ZipArchive not found";
      return false;
    }  
      
    if(!function_exists("DoHTTPPOSTRequest")){  
      $_foL0Q[] = "DoHTTPPOSTRequest not found";
      return false;
    }  
      
    $_QLJfI = ini_get("disable_functions");  
    if(stripos($_QLJfI, "chmod") !== false || stripos($_QLJfI, "unlink") !== false || stripos($_QLJfI, "mkdir") !== false || stripos($_QLJfI, "rmdir") !== false){
      $_foL0Q[] = "file system functions chmod, unlink, mkdir and/or rmdir disabled";
      return false;
    }   
       
    // write check   
    $_foLL6 = false;
    $_I0lji = @fopen($_J1t6J."writecheck.txt", "w");
    if($_I0lji != false) {
      fclose($_I0lji);
      $_foLL6 = true;
      if(!unlink($_J1t6J."writecheck.txt"))
        $_foLL6 = false;
    } else {
      $_foLL6 = false;
    }
    if(!$_foLL6){
      $_foL0Q[] = "Can't write to $_J1t6J";
      return false;
    }  

    // chmod test
    $_foLL6 = chmod(InstallPath."config.inc.php", 0777);
    if(!$_foLL6){
      $_foL0Q[] = "Can't chmod 0777 " . InstallPath."config.inc.php";
      return false;
    }  

    // write test
    $_foLL6 = false;  
    $_I0lji = @fopen(InstallPath."config.inc.php", "a");
    if($_I0lji != false) {
      fclose($_I0lji);
      $_foLL6 = true;
    }
    if(!$_foLL6){
      $_foL0Q[] = "Can't write to " . InstallPath."config.inc.php";
      return false;
    }  

    $_foLL6 = chmod(InstallPath."config.inc.php", 0444);   
    if(!$_foLL6){
      $_foL0Q[] = "Can't chmod 0444 " . InstallPath."config.inc.php";
      return false;
    }  

    // mkdir test
    $_foLL6 = mkdir(InstallPath . 'ouTest', 0755);
    if(!$_foLL6){
      $_foL0Q[] = "Can't mkdir 0755 " . InstallPath . 'ouTest';
      return false;
    }  
    $_foLL6 = rmdir(InstallPath . 'ouTest');
    if(!$_foLL6){
      $_foL0Q[] = "Can't rmdir " . InstallPath . 'ouTest';
      return false;
    }  
       
    $_j60Q0 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator( _LPBCC(InstallPath) ), RecursiveDirectoryIterator::SKIP_DOTS || RecursiveDirectoryIterator::UNIX_PATHS);   
    foreach ($_j60Q0 as $_QlCtl){
        $_I0lji = basename($_QlCtl->getPathname());
        if($_I0lji[0] == ".") continue;
        if (!$_QlCtl->isDir()){
          
          if( $_I0lji == "config.inc.php" ||
              $_I0lji == "config_db.inc.php" ||
              $_I0lji == "config_paths.inc.php"
            )
            {
              
            }else
              if( !$_QlCtl->isReadable() || !$_QlCtl->isWritable() ){
                $_foL0Q[] = "permissions: " . $_QlCtl->getPathname(); 
                $_foLL6 = false;
              }  
        }else{
          if( !$_QlCtl->isReadable() || !$_QlCtl->isWritable() || !$_QlCtl->isExecutable() ){
            $_foL0Q[] = "permissions: " . $_QlCtl->getPathname(); 
            $_foLL6 = false;
          }  
        }    
            
    }
    
    return $_foLL6;     
  }
  
?>

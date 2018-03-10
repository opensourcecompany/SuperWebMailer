<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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
  include_once("templates.inc.php");
  include_once("mailinglistq.inc.php");
  include_once("defaulttexts.inc.php");

  // MySql 4.0 http://dev.mysql.com/doc/refman/4.1/en/index.html

  if(!defined("E_DEPRECATED"))
    define("E_DEPRECATED", 0);
  if(!defined("E_STRICT"))
    define("E_STRICT", 0);
  error_reporting( E_ALL & ~ ( E_NOTICE | E_WARNING  | E_DEPRECATED | E_STRICT ) );

  define('Setup', 1); # we install
  $_Jtf68 = "http://";

  if(!empty($_SERVER["REQUEST_SCHEME"]))
     $_Jtf68 = $_SERVER["REQUEST_SCHEME"]."://";

  $Language = $INTERFACE_LANGUAGE;
  if(isset($_POST["Language"]))
     $Language = $_POST["Language"];
     else
     $_POST["Language"] = $INTERFACE_LANGUAGE;
  if($Language == "")
    $Language = $INTERFACE_LANGUAGE;
  $INTERFACE_LANGUAGE = $Language;

  _LQLRQ($INTERFACE_LANGUAGE);

  $_I0600 = "";
  $_JtffC = false;
  $errors = array();

  if(!isset($_POST["step"]) || $_POST["step"] == "") {
    $_POST["step"] = "1";
  }

  if(isset($_POST["NextBtn"])) {
    switch($_POST["step"]) {
      case 999999:
       if(!isset($_POST["ver_code"]) || trim($_POST["ver_code"]) == "") {
         $_POST["step"] = 4;
         $_POST["ManualBtn"] = 1;
         $errors[] = "ver_code";
       }
       if(!isset($_POST["RegName"]) || trim($_POST["RegName"]) == "" || !isset($_POST["RegNumber"]) || trim($_POST["RegNumber"]) == "") {
         $_POST["step"] = 4;
         $errors[] = "ver_code";
       }

       if (count($errors) == 0) {
        if( !_OFRED($_POST["ver_code"], $_POST["RegNumber"]) ) {
          $_POST["step"] = 4;
          $_POST["ManualBtn"] = 1;
          $errors[] = "ver_code";
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090206"];
        } else {
          $_POST["step"] = 5;
          $_POST["RegNumber"] = strtoupper($_POST["RegNumber"]);
          $_JtO0o = "";
          if(!_OFR1B($_JtO0o)) {
            $_POST["step"] = 4;
            $_POST["ManualBtn"] = 1;
            $errors[] = "ver_code";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090206"].$_JtO0o;
          }
        }
       }
       break;
      case 1:
        break;
      case 2:
        if(!isset($_POST["WebsiteURL"]) || trim($_POST["WebsiteURL"]) == "" || strpos($_POST["WebsiteURL"], "\\") !== false )
           $errors[] = "WebsiteURL";
        if(!isset($_POST["InstallPath"]) || trim($_POST["InstallPath"]) == "" || strpos($_POST["InstallPath"], "\\") !== false )
           $errors[] = "InstallPath";
        if(!isset($_POST["ScriptBaseURL"]) || trim($_POST["ScriptBaseURL"]) == "" || strpos($_POST["ScriptBaseURL"], "\\") !== false )
           $errors[] = "ScriptBaseURL";
        if(count($errors) == 0) {
          if(!_OE0EB()) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090210"];
            $errors[] = "ScriptBaseURL";
          }
        }
        break;
      case 3:
        if(!isset($_POST["DatabaseHostname"]) || trim($_POST["DatabaseHostname"]) == "")
           $errors[] = "DatabaseHostname";
        if(!isset($_POST["DatabaseName"]) || trim($_POST["DatabaseName"]) == "")
           $errors[] = "DatabaseName";
        if(!isset($_POST["DatabaseUsername"]) || trim($_POST["DatabaseUsername"]) == "")
           $errors[] = "DatabaseUsername";

        $_I8i66 = "";
        for ($_Q6llo=0; $_Q6llo<strlen($_POST["DatabaseTablePrefix"]); $_Q6llo++)
          if (preg_match('/[A-Za-z0-9_]/', $_POST["DatabaseTablePrefix"]{$_Q6llo}))
            $_I8i66 .= $_POST["DatabaseTablePrefix"]{$_Q6llo};
        $_POST["DatabaseTablePrefix"] = $_I8i66;
        if($_I8i66 != "" && $_I8i66{strlen($_I8i66) - 1} != "_")
          $_POST["DatabaseTablePrefix"] = $_POST["DatabaseTablePrefix"]."_";

        if(count($errors) == 0) {
          if(!_OEQJB($_JtiIJ)) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090211"].$_JtiIJ;
            $errors[] = "DatabaseHostname";
            $errors[] = "DatabaseName";
            $errors[] = "DatabaseUsername";
          } else {
            if(!_OE1BP()) {
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090212"];
              $errors[] = "DatabaseHostname";
            }
          }
        }

        break;
      case 4:
        if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
         break;
        }

        if(isset($_POST["ManualBtn"])) {
          break;
        }
        if( !isset($_POST["RegName"]) || $_POST["RegName"] == "" )
           $errors[] = "RegName";
        if( !isset($_POST["RegNumber"]) || $_POST["RegNumber"] == "" )
           $errors[] = "RegNumber";
        if(count($errors) == 0) {
          $_POST["RegNumber"] = strtoupper($_POST["RegNumber"]);
          if ( !_OFR0F(urlencode($_POST["RegName"]), $_POST["RegNumber"], $_I0600, $_JtffC) ) {
            $errors[] = "RegName";
            $errors[] = "RegNumber";
          } else {
            $_JtO0o = "";
            if( !_OFR1B($_JtO0o) ) {
              $errors[] = "RegName";
              $errors[] = "RegNumber";
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090202"].$_JtO0o;
            }
          }
        }
        break;
      default:
        if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded())
         $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_I0600, 'DISABLED', 'inst1_evaluation_snipped.htm');
         else
         $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_I0600, 'DISABLED', 'inst1_snipped.htm');
      case 5:
        break;
      case 6:
        include_once("securitycheck.inc.php");
        if(_LO0BO()) {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["ConfigFilesWriteable"];
          $errors[] = "";
        }
        if(!_LO0BL(true)) {
          $_I0600 .= "<br />".$resourcestrings[$INTERFACE_LANGUAGE]["UserPathsNotWriteable"];
          $errors[] = "";
        }
        break;
      case 7:
        if(!isset($_POST["Password"]) || trim($_POST["Password"]) == "")
          $errors[] = "Password";
        if(!isset($_POST["PasswordAgain"]) || trim($_POST["PasswordAgain"]) == "")
          $errors[] = "PasswordAgain";
        if(count($errors) == 0) {
          if( trim($_POST["Password"]) != trim($_POST["PasswordAgain"]) || trim($_POST["Password"]) == "*PASSWORDSET*" ) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
            $errors[] = "Password";
            $errors[] = "PasswordAgain";
          }
        }
        if(count($errors) == 0) {
         $Username = "superadmin";
         include_once("superadmin.inc.php");
         $_Qt6oI = _LLEQD($Username, "SuperAdmin", $Language);

         $_QlLOL = _OC1CF();
         $_QJlJ0 = "UPDATE $_Q8f1L SET Password=CONCAT("._OPQLR($_QlLOL).", PASSWORD("._OPQLR($_QlLOL.trim($_POST["Password"])).") ) WHERE id=".$_Qt6oI;
         mysql_query($_QJlJ0, $_Q61I1);
         // unset it because admin user can have an own password
         unset($_POST["Password"]);
         unset($_POST["PasswordAgain"]);
        }
        break;
      case 8:
        if(!isset($_POST["Username"]) || trim($_POST["Username"]) == "")
          $errors[] = "Username";
          else
          if( !preg_match("/^[a-zA-Z0-9_]{3,}$/", $_POST["Username"]) ) {
            $errors[] = "Username";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
          }
        if(!isset($_POST["Password"]) || trim($_POST["Password"]) == "")
          $errors[] = "Password";
        if(!isset($_POST["PasswordAgain"]) || trim($_POST["PasswordAgain"]) == "")
          $errors[] = "PasswordAgain";
        if(count($errors) == 0) {
          if(trim($_POST["Password"]) != trim($_POST["PasswordAgain"]) || trim($_POST["Password"]) == "*PASSWORDSET*" ) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
            $errors[] = "Password";
            $errors[] = "PasswordAgain";
          }
        }
        if ( (!isset($_POST['EMail'])) || (trim($_POST['EMail']) == "") || !_OPAOJ($_POST['EMail']) )
          $errors[] = 'EMail';
        if(count($errors) == 0) {

          include_once("superadmin.inc.php");
          // it creates admin and tables
          $_QlLfl = _LLEQD(trim($_POST["Username"]), "Admin", $Language);

          if(!isset($_POST["FirstName"]))
            $_POST["FirstName"] = "";
          if(!isset($_POST["LastName"]))
            $_POST["LastName"] = "";

          $_QlLOL = _OC1CF();
          $_QJlJ0 = "UPDATE $_Q8f1L SET Password=CONCAT("._OPQLR($_QlLOL).", PASSWORD("._OPQLR($_QlLOL.trim($_POST["Password"])).") ), Language="._OPQLR($Language).", EMail="._OPQLR($_POST["EMail"]).", FirstName="._OPQLR($_POST["FirstName"]).", LastName="._OPQLR($_POST["LastName"])." WHERE id=".$_QlLfl;
          mysql_query($_QJlJ0, $_Q61I1);

          $_QJlJ0 = "UPDATE $_Q8f1L SET EMail="._OPQLR($_POST["EMail"])." WHERE Username='superadmin'";
          mysql_query($_QJlJ0, $_Q61I1);

          $_QJlJ0 = "SHOW COLUMNS FROM $_Q8f1L";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

          $_QJojf = array();
          while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             // Fieldname
             foreach ($_Q6Q1C as $key => $_Q6ClO) {
                if ($key == "Field")  {
                  if(strpos($_Q6ClO, "Privilege") !== false) {
                    $_QJojf[] = $_Q6ClO;
                    break;
                  }
                }
             }
          }
          mysql_free_result($_Q60l1);

          $_QJlJ0 = "";
          for($_Q6llo=0; $_Q6llo<count($_QJojf); $_Q6llo++)
            if($_QJlJ0 == "")
            $_QJlJ0 = $_QJojf[$_Q6llo]."=1";
            else
            $_QJlJ0 .= ", ".$_QJojf[$_Q6llo]."=1";
          $_QJlJ0 = "UPDATE $_Q8f1L SET ".$_QJlJ0." WHERE id=".$_QlLfl;
          mysql_query($_QJlJ0, $_Q61I1);
        }

        break;
      case 9:
        if(@file_exists(InstallPath."install.php")) {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090213"];
          $errors[] = "dummy";
        }
        break;
    }
  }


  if ( count($errors) == 0 ) {

    if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
      if(isset($_POST["NextBtn"])){
        $_POST["step"]++;
        if($_POST["step"] == 4) {
          include_once("./_evaluation.inc.php");
          $_JtO0o="";
          if(!_OFR1B($_JtO0o)){
           print $_JtO0o;
           exit;
          }
          $_POST["step"]++;
        }
      }
      if(isset($_POST["BackBtn"])){
        $_POST["step"]--;
        if($_POST["step"] == 4)
          $_POST["step"]--;
      }
    } else {
      if(isset($_POST["NextBtn"]))
        $_POST["step"]++;
      if(isset($_POST["BackBtn"]))
        $_POST["step"]--;
    }

  } else {
    if($_I0600 == "")
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  switch($_POST["step"]) {
    case 1:
      if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded())
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_I0600, 'DISABLED', 'inst1_evaluation_snipped.htm');
        else
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_I0600, 'DISABLED', 'inst1_snipped.htm');
      break;
    case 2:
      _OE01O($_JtOOJ, $_Jto6o, $_JtOjf, $_JtLjO);
      if(!isset($_POST["WebsiteURL"]) || $_POST["WebsiteURL"] == "")
         $_POST["WebsiteURL"] = $_JtOjf;
      if(!isset($_POST["InstallPath"]) || $_POST["InstallPath"] == "")
         $_POST["InstallPath"] = $_JtOOJ;
      if(!isset($_POST["ScriptBaseURL"]) || $_POST["ScriptBaseURL"] == "")
         $_POST["ScriptBaseURL"] = $_Jto6o;
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090102"], $_I0600, 'DISABLED', 'inst2_snipped.htm');
      if(_OEO10()) {
        $_QJCJi = _OP6PQ($_QJCJi, "<RIGHTS>", "</RIGHTS>");
      } else {
        $_QJCJi = str_replace("<RIGHTS>", "", $_QJCJi);
        $_QJCJi = str_replace("</RIGHTS>", "", $_QJCJi);
      }
      break;
    case 3:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090103"], $_I0600, 'DISABLED', 'inst3_snipped.htm');
      break;
    case 4:
      if(isset($_POST["ManualBtn"])) {
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090101"], $_I0600, 'DISABLED', 'instman_snipped.htm');
        $_JttQ1 = urlencode( $_POST["RegName"] );
        $_Jttii = urlencode( $_POST["RegNumber"] );
        if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
           $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

        $_Qf1i1 = "Program=$AppName&Name1=$_JttQ1&Code=$_Jttii&AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE";

        $_QJCJi = str_replace( _OBLDR($_Jtf68.$_JftOi ), _OBLDR($_Jtf68.$_JftOi).$_JfOii."/swm_cc.php?".$_Qf1i1, $_QJCJi);
        break;
      }
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090101"], $_I0600, 'DISABLED', 'inst4_snipped.htm');
      if(!$_JtffC) {
        $_QJCJi = _OP6PQ($_QJCJi, "<MANUAL>", "</MANUAL>");
      }
      break;
    case 5:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090104"], $_I0600, 'DISABLED', 'inst5_snipped.htm');
      $_QJCJi = str_replace("<crons_php>", ScriptBaseURL.'crons.php', $_QJCJi);
      break;
    case 6:
      if(!isset($_POST["JumpToNextBtn"]) ) {
        @chmod (_OBLCO($_jjC06), 0777);
        @chmod (InstallPath."config.inc.php", 0444);
        @chmod (InstallPath."config_paths.inc.php", 0444);
        @chmod (InstallPath."config_db.inc.php", 0444);

        include_once("securitycheck.inc.php");
        if(_LO0BO() || !_LO0BL(true) ) {
          $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090105"], $_I0600, 'DISABLED', 'inst6_snipped.htm');
          break;
        } else {
          $_POST["step"] = 7; // jump to next
        }
      } else
        $_POST["step"] = 7; // jump to next

    case 7:
      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q8f1L WHERE UserType='SuperAdmin'";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      if($_Q6Q1C[0] == 0) {
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090107"], $_I0600, 'DISABLED', 'inst7_snipped.htm');
        break;
      }
      $_POST["step"] = 8; // jump to next
    case 8:
      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q8f1L WHERE UserType='Admin'";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      if($_Q6Q1C[0] == 0) {
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090108"], $_I0600, 'DISABLED', 'inst8_snipped.htm');
        break;
      }
      $_POST["step"] = 9; // goto end
    case 9:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090109"], $_I0600, 'DISABLED', 'inst9_snipped.htm');
      break;
    case 10:
      // install_done.php
      break;
    default:
      if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded())
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_I0600, 'DISABLED', 'inst1_evaluation_snipped.htm');
        else
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_I0600, 'DISABLED', 'inst1_snipped.htm');
  }

  unset($_POST["step"]);


  _LJ81E($_QJCJi);

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;

  function _ODFC8($_QJCJi) {
    $_IflL6 = 0;
    while(strpos($_QJCJi, ".") !== false) {
      $_IflL6++;
      $_QJCJi = substr($_QJCJi, strpos($_QJCJi, ".") + 1);
    }
    return $_IflL6;
  }

  function _OE01O(&$_JtOOJ, &$_Jto6o, &$_JtOjf, &$_JtLjO) {
    global $_Jtf68;
    if (!isset($_SERVER)) {
        global $_SERVER;
    }

    $_QCoLj = realpath(dirname(__FILE__));
    $_QCoLj = str_replace( '\\', '/', $_QCoLj);

    if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
       $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );

    if(substr($_QCoLj, strlen($_QCoLj), 1) <> '/')
       $_QCoLj .= "/";

    $_JtLjO = substr($_QCoLj, strpos_reverse($_QCoLj, "/", strlen($_QCoLj) - 1 ));

    $_JtOOJ = $_QCoLj;

    if(isset($_SERVER["SERVER_NAME"]))
       $_Jto6o = $_SERVER["SERVER_NAME"];
       else
       $_Jto6o = "";

    if(strpos($_Jto6o, "www.") === false && _ODFC8($_Jto6o) < 2 && strpos($_Jto6o, "localhost") === false ){
       if(!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "www.") !== false)
          $_Jto6o = "www.".$_Jto6o;
          else
          if(empty($_SERVER['HTTP_REFERER']))
             $_Jto6o = "www.".$_Jto6o;
    }
    if(strpos($_Jto6o, $_Jtf68) === false)
       $_Jto6o = $_Jtf68.$_Jto6o;

    if(isset($_SERVER['PHP_SELF']) && $_SERVER['PHP_SELF'] != "")
        $_Jto6o .= $_SERVER['PHP_SELF'];
        else
        if(isset($_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"] != "")
           $_Jto6o .= $_SERVER["REQUEST_URI"];
    $_Jto6o = substr($_Jto6o, 0, strpos_reverse($_Jto6o, "/", strlen($_Jto6o)) + 1 );
    $_JtOjf = _OBLCO($_Jto6o);
    if ( substr($_JtOjf, 0, strpos_reverse($_JtOjf, "/", strlen($_JtOjf)) + 1 ) != $_Jtf68 )
       $_JtOjf = substr($_JtOjf, 0, strpos_reverse($_JtOjf, "/", strlen($_JtOjf)) + 1 );

    $_JtOOJ = _OBLDR($_JtOOJ);
    $_Jto6o = _OBLDR($_Jto6o);
    $_JtOjf = _OBLDR($_JtOjf);
    $_JtLjO = _OBLDR($_JtLjO);
  }

 function _OE0EB() {
  global $_Q6JJJ;

  $_POST["WebsiteURL"] = _OBLDR(trim($_POST["WebsiteURL"]));
  $_POST["InstallPath"] = _OBLDR(trim($_POST["InstallPath"]));
  $_POST["ScriptBaseURL"] = _OBLDR(trim($_POST["ScriptBaseURL"]));

  $_QllO8 = explode("/", $_POST["ScriptBaseURL"]);
  unset($_QllO8[0]);
  unset($_QllO8[1]);
  unset($_QllO8[2]);
  $_JtLjO = "/".join("/", $_QllO8);

  $_POST["BasePath"] = $_JtLjO;

  @chmod ($_POST["InstallPath"]."config_paths.inc.php", 0777);
  $_QCioi = fopen($_POST["InstallPath"]."config_paths.inc.php", "w");
  if(!$_QCioi) {
    return false;
  }

  $_QJCJi = "<?php
   /*
    Paths Configuration
   */

  # all Paths WITH slash /
   define('BasePath', '$_POST[BasePath]'); # e.g. /swm/
   define('WebsiteURL', '$_POST[WebsiteURL]'); # e.g. http://superwebmailer.de/
   define('ScriptBaseURL', '$_POST[ScriptBaseURL]'); # e.g. http://superwebmailer.de/swm/
   define('InstallPath', '$_POST[InstallPath]'); # e.g. /root/www/vhosts/superwebmailer.de/htdocs/swm/

  # DefaultPath for template files and sql folder if they are in another path with /
   define('DefaultPath', '');

   // Templates path name without / the subdirs must include the languages
   define('TemplatesPath', 'templates');";

  $_QJCJi .= $_Q6JJJ."?>";


  $_JI6f0 = fwrite($_QCioi, $_QJCJi);
  fflush($_QCioi);
  fclose($_QCioi);
  clearstatcache();
  if(function_exists("opcache_invalidate")){
    opcache_invalidate("config_paths.inc.php", true);
    opcache_invalidate($_POST["InstallPath"]."config_paths.inc.php", true);
  }
  return $_JI6f0 == strlen($_QJCJi);
 }

 function _OE1BP() {
  global $_Q6JJJ;

  @chmod ($_POST["InstallPath"]."config_db.inc.php", 0777);
  $_QCioi = fopen($_POST["InstallPath"]."config_db.inc.php", "w");
  if(!$_QCioi) {
    return false;
  }

  $_QJCJi = "<?php
   /*
    MySQL-Server Configuration
   */

   define('MySQLServername', '".trim($_POST["DatabaseHostname"])."');
   define('MySQLUsername', '".trim($_POST["DatabaseUsername"])."');
   define('MySQLPassword', '".trim($_POST["DatabasePassword"])."');
   define('MySQLDBName', '".trim($_POST["DatabaseName"])."');

   define('TablePrefix', '".trim($_POST["DatabaseTablePrefix"])."');

  ?>";

  $_JI6f0 = fwrite($_QCioi, $_QJCJi);
  fflush($_QCioi);
  fclose($_QCioi);
  clearstatcache();
  if(function_exists("opcache_invalidate")){
    opcache_invalidate("config_db.inc.php", true);
    opcache_invalidate($_POST["InstallPath"]."config_db.inc.php", true);
  }
  return $_JI6f0 == strlen($_QJCJi);
 }

 function _OEQJB(&$_JtiIJ) {
   global $_Q61I1;
   $_Q61I1 = mysql_connect ($_POST["DatabaseHostname"], $_POST["DatabaseUsername"], $_POST["DatabasePassword"], true);
   if ($_Q61I1 == 0) {
      $_QJCJi = mysql_error($_Q61I1);
      if($_QJCJi == "")
         $_QJCJi = "Hostname, username or password incorrect.";
      $_JtiIJ = mysql_errno($_Q61I1).": ".$_QJCJi;
      return false;
   }

   // UTF-8 connection
   @mysql_query("SET NAMES 'utf8'", $_Q61I1);
   @mysql_query("SET CHARACTER SET 'utf8'", $_Q61I1);
   // not STRICT mode
   @mysql_query('SET SQL_MODE=""', $_Q61I1);

   if (!mysql_select_db ($_POST["DatabaseName"], $_Q61I1)) {
      $_QJCJi = mysql_error($_Q61I1);
      if($_QJCJi == "")
         $_QJCJi = "Database name incorrect.";
     $_JtiIJ = mysql_errno($_Q61I1).": ".$_QJCJi;
     mysql_close ($_Q61I1);
     return false;
   }

   // set to utf8_general_ci when possible
   @mysql_query("ALTER DATABASE ".$_POST["DatabaseName"]." CHARACTER SET utf8;", $_Q61I1);

   $_Ij6Io = join("", file(_O68A8()."install.sql"));
   $_Q66jQ = $_Ij6Io;
   $_I1fiC = array();
   while(strpos($_Q66jQ, "CREATE TABLE IF NOT EXISTS ") !== false) {
     $_Q66jQ = substr($_Q66jQ, strpos($_Q66jQ, "CREATE TABLE IF NOT EXISTS ") + strlen("CREATE TABLE IF NOT EXISTS ") );
     $_I1fiC[] = substr($_Q66jQ, 0, strpos($_Q66jQ, "` (") + 1);
     $_Q66jQ = substr($_Q66jQ, strpos($_Q66jQ, "` (") + strlen("` ("));
   }

   for($_Q6llo=0; $_Q6llo<count($_I1fiC); $_Q6llo++) {
     $_Ij6Io = str_replace($_I1fiC[$_Q6llo], str_replace("sml_", $_POST["DatabaseTablePrefix"], $_I1fiC[$_Q6llo]), $_Ij6Io);
   }

   $_Ij6il = explode(");", $_Ij6Io); // split on ); NOT on ; because of &auml;

   for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
     if(trim($_Ij6il[$_Q6llo]) == "") continue;
     if(strpos($_Ij6il[$_Q6llo], "CREATE TABLE ") !== false) {
       $_Q60l1 = mysql_query($_Ij6il[$_Q6llo].") CHARSET=utf8", $_Q61I1);
       if(!$_Q60l1)
          $_Q60l1 = mysql_query($_Ij6il[$_Q6llo].")", $_Q61I1);
     } else
       $_Q60l1 = mysql_query($_Ij6il[$_Q6llo].")", $_Q61I1);
     if(!$_Q60l1) {
       $_QJCJi = mysql_error($_Q61I1);
       if($_QJCJi == "")
          $_QJCJi = "Table create error: ";
       $_JtiIJ = $_QJCJi." ".$_Ij6il[$_Q6llo].")";
       if( stripos($_Ij6il[$_Q6llo], "INSERT INTO") !== false && (stripos($_QJCJi, "DUPLICATE") !== false  || stripos($_QJCJi, "DOPPELTER") !== false) )
       $_JtiIJ = "";
       else
       return false;
     }
   }

   return true;
 }

 function _OEO10() {
  $_QfC8t = fopen(InstallPath."config_paths.inc.php", "a");
  if($_QfC8t) {
    fclose($_QfC8t);
  } else
    return false;

  $_QfC8t = fopen(InstallPath."config_db.inc.php", "a");
  if($_QfC8t) {
    fclose($_QfC8t);
  } else
    return false;

  return true;
 }

?>

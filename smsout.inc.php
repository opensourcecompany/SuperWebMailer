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
 include_once("mailinglistq.inc.php");

 $_6oLOt = array('0049', '0043', '0041');
 $_6ol8Q = array ( "0151",
                          "0152",
                          "0157",
                          "0159",
                          "0160",
                          "0161",
                          "0162",
                          "0163",
                          "0164",
                          "0170",
                          "0171",
                          "0172",
                          "0173",
                          "0174",
                          "0175",
                          "0176",
                          "0177",
                          "0178",
                          "0179"
                        );
 $_6C0Ji = array (
                          "0650",
                          "0660",
                          "0663",
                          "0664",
                          "0676",
                          "0680",
                          "0681",
                          "0688",
                          "0699"
                         );

 $_6C1JJ = array ( "076",
                          "077",
                          "078",
                          "079",
                         );

 function _LOC1E(&$_j808f, &$_II1Ot) {
    global $_6oLOt, $_6ol8Q, $_6C1JJ, $_6C0Ji;
    global $INTERFACE_LANGUAGE, $resourcestrings;

    // kein +!
    $_j808f = _OPLPC(' ', '', $_j808f);
    $_j808f = _OPLPC('-', '', $_j808f);
    $_j808f = _OPLPC('/', '', $_j808f);
    $_j808f = _OPLPC("\\", '', $_j808f);
    $_j808f = _OPLPC('*', '', $_j808f);
    $_j808f = _OPLPC('?', '', $_j808f);
    $_j808f = _OPLPC('"', '', $_j808f);
    $_j808f = _OPLPC("'", '', $_j808f);
    $_j808f = _OPLPC('´', '', $_j808f);
    $_j808f = _OPLPC('`', '', $_j808f);
    $_j808f = _OPLPC('(', '', $_j808f);
    $_j808f = _OPLPC(')', '', $_j808f);
    $_j808f = _OPLPC('[', '', $_j808f);
    $_j808f = _OPLPC(']', '', $_j808f);

    if (substr($_j808f, 0, 2) != "00") { //0049

      if ( # 179

            !(array_search("0".substr($_j808f, 0, 3), $_6ol8Q) === false)

         )
            $_j808f = "0049".$_j808f;

      if ( # 0179

            !(array_search(substr($_j808f, 0, 4), $_6ol8Q) === false)

         )
            $_j808f = "0049".substr($_j808f, 1);

      if (substr($_j808f, 0, 1) == "+") { //+49
         $_j808f = _OPLPC('+', '00', $_j808f);
      }

      if (substr($_j808f, 0, 2) == "04") { //04915...
         $_j808f = "0".$_j808f;
      }

      if ( (substr($_j808f, 0, 1) != "0") && (substr($_j808f, 0, 1) == "1") ) { //179...
        $_j808f = "0049".$_j808f;
      }

      if ( (substr($_j808f, 0, 2) != "00") && (substr($_j808f, 0, 2) == "01") ) { //0179...
        $_j808f = "0049".substr($_j808f, 1);
      }
    }

    if (substr($_j808f, 0, 2) != "00") { //0049

       if ( # 650

             !(array_search("0".substr($_j808f, 0, 3), $_6C0Ji) === false)

          )
             $_j808f = "0043".$_j808f;


       if ( # 0650

             !(array_search(substr($_j808f, 0, 4), $_6C0Ji) === false)

          )
             $_j808f = "0043".substr($_j808f, 1);


       if ( # 76

             !(array_search("0".substr($_j808f, 0, 2), $_6C1JJ) === false)

          )
             $_j808f = "0041".$_j808f;

       if ( # 076

             !(array_search(substr($_j808f, 0, 3), $_6C1JJ) === false)

          )
             $_j808f = "0041".substr($_j808f, 1);

    }

    if (substr($_j808f, 0, 4) == "0049") {
       if ( (!preg_match ('/^[0-9]{14}$/', $_j808f, $_6CQ1I)) && (!preg_match ('/^[0-9]{15}$/', $_j808f, $_6CQ1I)) ) {  //00491791317529
         $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{1}';
         return false;
       } else {
         if(count($_6CQ1I) > 1) {
          $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{'.count($_6CQ1I).'}';
          return false;
         }
       }

       if (substr($_j808f, 0, 5) == "00490") {
           $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{2}';;
           return false;
       }

    } else
       if ( (substr($_j808f, 0, 4) == "0041") || (substr($_j808f, 0, 4) == "0043") )
       {
         if (substr($_j808f, 0, 4) == "0041")
            $_JI6Ij=13;
            else
            $_JI6Ij=14; // AT


         if (substr($_j808f, 0, 4) == "0043") {
           if ( (!preg_match ('/^[0-9]{14}$/', $_j808f, $_6CQ1I)) && (!preg_match ('/^[0-9]{15}$/', $_j808f, $_6CQ1I)) && (!preg_match ('/^[0-9]{16}$/', $_j808f, $_6CQ1I)) ) {  //00491791317529
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{1}';
             return false;
           }  else {
             if(count($_6CQ1I) > 1) {
              $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{'.count($_6CQ1I).'}';
              return false;
             }
           }
         } else {
           if (!preg_match('/^[0-9]{'.$_JI6Ij.'}$/', $_j808f, $_6CQ1I)) {  //0049 179 1317529
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{1}';  // $_JI6Ij
             return false;
           }  else {
             if(count($_6CQ1I) > 1) {
              $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{'.count($_6CQ1I).'}'; // $_JI6Ij
              return false;
             }
           }
         }

         if (substr($_j808f, 0, 5) == "00410") {
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{1}';
             return false;
         }

         if (substr($_j808f, 0, 5) == "00430") {
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{1}';
             return false;
         }


         $key = array_search(substr($_j808f, 0, 4), $_6oLOt);
         if($key === false) {
           $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000581"], $_j808f);
           return false;
         }

       } else {


           if (!preg_match('/^[0-9]{1,}$/', $_j808f, $_6CQ1I)) {  //0049 179 1317529
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{1}';
             return false;
           }
            else {
            if(count($_6CQ1I) > 1) {
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000580"], $_j808f).'{'.count($_6CQ1I).'}';
             return false;
            }
          }

          if (substr($_j808f, 0, 2) != '00') {
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000582"], $_j808f);
             return false;
           }

          if (strlen($_j808f) < 7) { // 00490179
             $_II1Ot[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000583"], $_j808f);
             return false;
          }

       }

   return true;
 }

 function _LOD0R($_6CI1Q, $_6CI66, $SMSText, $SMSoutUsername, $SMSoutPassword, &$errors) {
   // $SMSText iso-8859-1
   $_jOI0f = new _LODEB();

   $_jOI0f->SMSoutUsername = $SMSoutUsername;
   $_jOI0f->SMSoutPassword = $SMSoutPassword;

   if(!$_jOI0f->Login()) {
     $errors[] = $_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString;
     return false;
   }

   if(!$_jOI0f->SendSingleSMS($_6CI66, $_6CI1Q, "", $SMSText)){
     $errors[] = $_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString;
     return false;
   }

   $errors[] = $_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString;
   return true;
 }


 class _LODEB{

  // @public
  var $SMSoutUsername = "";
  var $SMSoutPassword = "";

  var $SMSoutLastErrorNo, $SMSoutLastErrorString;

  // @private
  var $_6Cjfl = false;
  var $_6CjL6 = 0x110;
  var $_6CJJC = "www.smsout.de";
  var $_6C61o = "/client/swm.php";
  var $_6C6to = "";

  // constructor
  function __construct() {
  }

  function _LODEB() {
    self::__construct();
  }

  // destructor
  function __destruct() {
  }


  // @public
  function IsLoggedIn(){
    return $this->_6Cjfl && $this->_6C6to != "";
  }

  // @private
  function _LOEFJ($Action){
    global $_QoJ8j, $AppName;

    return sprintf("AppName=%s&AppVersion=%s&ClientVersion=%s&Action=%s", urlencode( $AppName ), urlencode( $_QoJ8j ), urlencode($this->_6CjL6), urlencode($Action));
  }

  // @private
  function _LOFQA($_j8O8t,$_QfJI8,$_QCoLj,$_Qf1i1,$_ILOjO=0, $_6Cfji="", $_j8O60=80, $_JJfCl=false, $_JJfL8="", $_JJ8jj="", &$_j88of, &$_j8t8L) {
      global $_jJtt0;
      $_JJt0j = false;

      while (true){
        $_JJtoO = "";
        if (empty($_QfJI8))
            $_QfJI8 = 'GET';
        $_QfJI8 = strtoupper($_QfJI8);
        $_QCioi = fsockopen($_j8O8t, $_j8O60, $_j88of, $_j8t8L, 20);
        if(!$_QCioi) {
          $_j88of = 600;
          $_j8t8L = "Can't connect to server.";
          return false;
        }

        if ($_QfJI8 == 'GET')
            $_QCoLj .= '?' . $_Qf1i1;
        fputs($_QCioi, "$_QfJI8 $_QCoLj HTTP/1.0\r\n");
        fputs($_QCioi, "Host: $_j8O8t\r\n");

        if(!empty($_6Cfji))
          fputs($_QCioi, "Cookie: $_6Cfji\r\n");

        if($_JJfCl && $_JJt0j){
          fputs($_QCioi, "Authorization: Basic ".base64_encode($_JJfL8 . ":" . $_JJ8jj)."\r\n");
        }

        if ($_QfJI8 == 'POST'){
          fputs($_QCioi, "Content-type: application/x-www-form-urlencoded\r\n");
          fputs($_QCioi, "Content-length: " . strlen($_Qf1i1) . "\r\n");
        }
        if ($_ILOjO)
            fputs($_QCioi, "User-Agent: MSIE\r\n");
            else
            fputs($_QCioi, "User-Agent: $_jJtt0\r\n");
        fputs($_QCioi, "Connection: close\r\n\r\n");
        if ($_QfJI8 == 'POST')
           fputs($_QCioi, $_Qf1i1);

        if(function_exists("stream_set_timeout") && function_exists("stream_set_blocking") && function_exists("stream_get_meta_data") )  {
           stream_set_blocking($_QCioi, TRUE);
           stream_set_timeout($_QCioi, 20);
           $_JJo0J = stream_get_meta_data($_QCioi);
           while ((!feof($_QCioi)) && (!$_JJo0J['timed_out'])) {
            $_JJtoO .= fgets($_QCioi, 128);
            $_JJo0J = stream_get_meta_data($_QCioi);
           }
         } else {
           sleep(2);
           while (!feof($_QCioi)) {
            $_JJtoO .= fgets($_QCioi, 128);
           }
         }
        fclose($_QCioi);
        $_JJolf = trim(substr($_JJtoO, 0, strpos($_JJtoO, " ")));
        $_JJCQ0 = trim(substr($_JJtoO, strpos($_JJtoO, " ")));
        $_JJCQ0 = trim(substr($_JJCQ0, 0, strpos($_JJCQ0, " ")));

        if($_JJCQ0 == 401 && $_JJfCl && !$_JJt0j){
          $_JJt0j = true;
          continue;
        } else
          return $_JJtoO;
      }
  }


  // @private
  function _LOF8A($_JJtoO){

    if(!is_array($_JJtoO))
      $_JJtoO = explode("\r\n", $_JJtoO);

    # find body
    $_Q6i6i=0;
    for($_Q6llo=0; $_Q6llo<count($_JJtoO); $_Q6llo++){
      if($_JJtoO[$_Q6llo] == "") {
        $_Q6i6i = $_Q6llo + 1;
        break;
      }
    }

    $_Q60l1 = array();
    for($_Q6llo=$_Q6i6i; $_Q6llo<count($_JJtoO); $_Q6llo++){

      $_Q6ClO = explode(": ", $_JJtoO[$_Q6llo]);
      if(count($_Q6ClO) < 2) continue;
      $_Q60l1[$_Q6ClO[0]] = $_Q6ClO[1];

    }

    return $_Q60l1;

  }

  // @public
  function Login(){
    global $_jJtt0;
    if($this->_6Cjfl) $this->Logout();

    $_Qf1i1 = $this->_LOEFJ("login");
    $_Qf1i1 .= sprintf("&Username=%s&Password=%s", urlencode($this->SMSoutUsername), urlencode($this->SMSoutPassword));

    $_JJtoO = $this->_LOFQA($this->_6CJJC, "POST", $this->_6C61o, $_Qf1i1, 1, $this->_6C6to, 80, false, "", "", $this->SMSoutLastErrorNo, $this->SMSoutLastErrorString);
    if($_JJtoO == false){
      return false;
    }

    $_JJtoO = explode("\r\n", $_JJtoO);

    for($_Q6llo=0; $_Q6llo<count($_JJtoO); $_Q6llo++){
      if($_Q6i6i = stripos($_JJtoO[$_Q6llo], "Set-Cookie:") !== false){
          $_Qi8If = explode("Set-Cookie: ", $_JJtoO[$_Q6llo]);
          $this->_6C6to = $_Qi8If[1];
          if(strpos($this->_6C6to, ";") !== false)
             $this->_6C6to = substr($this->_6C6to, 0, strpos($this->_6C6to, ";"));
          break;
      }
    }

    $_Q60l1 = $this->_LOF8A($_JJtoO);

    $_6C80t = false;
    $this->SMSoutLastErrorNo = "";
    $this->SMSoutLastErrorString = "";

    if(isset($_Q60l1["Return"]) && $_Q60l1["Return"] == "OK")
      $_6C80t = true;
    if(isset($_Q60l1["ErrorCode"]))
      $this->SMSoutLastErrorNo = intval($_Q60l1["ErrorCode"]);
    if(isset($_Q60l1["ErrorText"]))
      $this->SMSoutLastErrorString = utf8_encode( $_Q60l1["ErrorText"] );

    if($_6C80t && empty($this->_6C6to)) {
      $this->SMSoutLastErrorNo = 999999;
      $this->SMSoutLastErrorString = "No session cookie received.";
      return false;
    }

    $this->_6Cjfl = $_6C80t;

    return $_6C80t;
  }

  // @public
  function Logout(){
    if(!$this->_6Cjfl || empty($this->_6C6to)) return true;
    $this->_6C6to = "";
    $this->_6Cjfl = false;
  }

  // @public
  function SendSingleSMS($SMSType, $SMSTo, $SMSCampaignName, $SMSText){
    global $_jJtt0;

    // $SMSText iso-8859-1
    $_Qf1i1 = $this->_LOEFJ("sendsinglesms");
    $_Qf1i1 .= sprintf("&SMSTo=%s&SMSType=V%s&SMSText=%s&SMSCampaignName=%s", urlencode($SMSTo), $SMSType, urlencode($SMSText), urlencode($SMSCampaignName));

    $_JJtoO = $this->_LOFQA($this->_6CJJC, "POST", $this->_6C61o, $_Qf1i1, 1, $this->_6C6to, 80, false, "", "", $this->SMSoutLastErrorNo, $this->SMSoutLastErrorString);
    if($_JJtoO == false){
      return false;
    }

    $_Q60l1 = $this->_LOF8A($_JJtoO);

    $_6C80t = false;
    $this->SMSoutLastErrorNo = "";
    $this->SMSoutLastErrorString = "";

    if(isset($_Q60l1["Return"]) && $_Q60l1["Return"] == "OK")
      $_6C80t = true;
    if(isset($_Q60l1["ErrorCode"]))
      $this->SMSoutLastErrorNo = intval($_Q60l1["ErrorCode"]);
    if(isset($_Q60l1["ErrorText"]))
      $this->SMSoutLastErrorString = utf8_encode( $_Q60l1["ErrorText"] );

    if($_6C80t && isset($_Q60l1["SMSCosts"])){
      $this->SMSoutLastErrorString = $_Q60l1["SMSCosts"];
    }

#    if(!$_6C80t || $this->SMSoutLastErrorNo != 0) {
#      print $_JJtoO;
#      print "<br /><br /><br /><br />";
#      print_r($_Q60l1);
#      exit;
#    }

    return $_6C80t;

  }

 }

  // @public
  function SendMassSMS($SMSType, $_6Ct6f, $SMSCampaignName){
    global $_jJtt0;

    // $SMSText iso-8859-1
    $_Qf1i1 = $this->_LOEFJ("sendmasssms");

    for($_Q6llo=0; $_Q6llo<count($_6Ct6f); $_Q6llo++){
      if(isset($_6Ct6f[$_Q6llo]["Errors"]) && $_6Ct6f[$_Q6llo]["Errors"]) continue;
      $_Qf1i1 .= sprintf("&SMSTo[]=%s&SMSType[]=V%s&SMSText[]=%s&SMSCampaignName[]=%s", urlencode($_6Ct6f[$_Q6llo]["u_CellNumber"]), $SMSType, urlencode($_6Ct6f[$_Q6llo]["smstextiso"]), urlencode($SMSCampaignName));
    }

    $_JJtoO = $this->_LOFQA($this->_6CJJC, "POST", $this->_6C61o, $_Qf1i1, 1, $this->_6C6to, 80, false, "", "", $this->SMSoutLastErrorNo, $this->SMSoutLastErrorString);
    if($_JJtoO == false){
      return false;
    }

    $_Q60l1 = $this->_LOF8A($_JJtoO);

    $_6C80t = false;
    $this->SMSoutLastErrorNo = "";
    $this->SMSoutLastErrorString = "";

    if(isset($_Q60l1["Return"]) && $_Q60l1["Return"] == "OK")
      $_6C80t = true;
    if(isset($_Q60l1["ErrorCode"]))
      $this->SMSoutLastErrorNo = intval($_Q60l1["ErrorCode"]);
    if(isset($_Q60l1["ErrorText"]))
      $this->SMSoutLastErrorString = utf8_encode( $_Q60l1["ErrorText"] );

    if($_6C80t && isset($_Q60l1["SMSCosts"])){
      $this->SMSoutLastErrorString = $_Q60l1["SMSCosts"];
    }

#    if(!$_6C80t || $this->SMSoutLastErrorNo != 0) {
#      print $_JJtoO;
#      print "<br /><br /><br /><br />";
#      print_r($_Q60l1);
#      exit;
#    }

    return $_6C80t;

  }


?>

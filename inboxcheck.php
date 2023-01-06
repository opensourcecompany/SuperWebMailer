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
  include_once("mail.php");
  include_once(PEAR_PATH . "IMAP.php");
  include_once(PEAR_PATH . "POP3.php");
  include_once(PEAR_PATH . "mimeDecode.php");
  include_once(PEAR_PATH . "RFC822.php");
  include_once("bouncer.php");

  class _LCAO8 {
   // @access public
   var $Name;
   var $InboxType='pop3'; // 'pop3', 'imap'
   var $EMailAddress;
   var $Servername;
   var $Serverport=110;
   var $Username;
   var $Password;
   var $SSLConnection=false;
   var $NumberOfEMailsToProcess=50;
   var $UIDL;
   var $LeaveMessagesInInbox = false;
   var $RemoveUnknownMailsAndSoftbounces = false;
   var $timeout=15;
   var $_6ootf = false; // imap only

   // @access private
   var $_6oCfO;
   var $_6oi1L;
   var $_6oi6i;
   var $_6oiCL = 1048576;

   // constructor
   function __construct($_6oLfI) {
     $this->_6oCfO = $_6oLfI;
     $this->_6oi1L = new _L10QP( _LPC1C($this->_6oCfO) );
   }

   function _LCAO8($_6oLfI) {
     self::__construct($_6oLfI);
   }

   function _LCAR0(&$_J0COJ, &$_6oL8t) {

     if($this->InboxType == "pop3"){
       $this->_6oi6i = new Net_POP3($this->SSLConnection, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_6oi6i = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     $_Ijj6Q = $this->_6oi6i->login($this->Username, $this->Password);
     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $_6oL8t = $this->_6oi6i->numMsg();

     if($this->InboxType == "imap")
       $_6oL8t = $this->_6oi6i->getNumberOfMessages();

     $this->_6oi6i->disconnect(false);
     $this->_6oi6i = null;

     return true;
   }

   function _LCACA(&$_J0COJ, &$_ILJIO, &$_JjLJ1, $_6olI6 = false, $_6olli = null) {

     # only for testing purposes
     if ( $_6olI6 && isset($_6olli) ) {
       $_JjLJ1 = count($_6olli);
       for($_Qli6J=0; $_Qli6J<count($_6olli); $_Qli6J++) {
         $_ILlOQ = join("", file($_6olli[$_Qli6J]));

         $_ILjOj = "";
         $_ILJIO = array();
         if($this->_6oi1L->_L10FC($_ILlOQ, $this->EMailAddress, $_ILjOj, $_ILJIO) ) {
           print_r( $_ILJIO );
         }
       }
       exit;
     }

     $_JjLJ1 = 0;

     if($this->InboxType == "pop3"){
       $this->_6oi6i = new Net_POP3($this->SSLConnection, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_6oi6i = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     _LRCOC();

     $_Ijj6Q = $this->_6oi6i->login($this->Username, $this->Password);
     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $this->_LCBBO($_J0COJ, $_ILJIO, $_JjLJ1);

     if($this->InboxType == "imap")
       $this->_LCBBJ($_J0COJ, $_ILJIO, $_JjLJ1);

     _LRCOC();
     $this->_6oi6i->disconnect(true);
     $this->_6oi6i = null;

     return true;
   }

   function _L1LE6($_ILlOQ, &$rid, &$ListId){
      global $_Il06C, $_QLl1Q;

      $rid = "";
      $ListId = "";
      
      $_ILlOQ = substr($_ILlOQ, strpos($_ILlOQ, $_QLl1Q . $_QLl1Q));
      
      $_QlOjt = strpos($_ILlOQ, $_Il06C . ": ");
      if($_QlOjt !== false) {
         $rid = substr($_ILlOQ, $_QlOjt + strlen($_Il06C . ": "));
         $rid = trim(substr($rid, 0, strpos($rid, "\n") - 1));
         
         $_QlOjt = strpos($_ILlOQ, "List-Id" . ": ");
         if($_QlOjt !== false) {
           $ListId = substr($_ILlOQ, $_QlOjt - 1);
           if($ListId != "" && $ListId[0] == "\n" || $ListId[0] == "\r"){
             $ListId = substr($ListId, 9);
             $ListId = trim(substr($ListId, 0, strpos($ListId, "\n") - 1));
             $ListId = str_replace('<', '', $ListId);
             $ListId = str_replace('>', '', $ListId);
             $_QlOjt = strpos($ListId, ".");
             if($_QlOjt !== false)
               $ListId = substr($ListId, 0, $_QlOjt);
               else
               $ListId = "";
           }else
             $ListId = "";
         }
      }
   }
   
   function _LCBBO(&$_J0COJ, &$_ILJIO, &$_JjLJ1) {
     $_J6fij = array();
     $_6C0oI = array();
     $_6oL8t = $this->_6oi6i->numMsg();

     if($_6oL8t > 0) {
       $_6C1t1 = 0;
       for($_Qli6J=1; $_Qli6J<=$_6oL8t; $_Qli6J++) {
         _LRCOC();
         $_IC1C6 = $this->_6oi6i->getListing($_Qli6J);
         if (IsPEARError($_IC1C6) || $_IC1C6 === false) {
           continue;
         }
         if(!isset($_IC1C6["uidl"]) || $_IC1C6["uidl"] == "")
           $_IC1C6["uidl"] = $_Qli6J;
         $_6C0oI[] = $_IC1C6["uidl"]; # save it for later checkings
         if( !in_array($_IC1C6["uidl"], $this->UIDL) && $_IC1C6["size"] < $this->_6oiCL ) {
           $_ILlOQ = $this->_6oi6i->getMsg($_IC1C6["msg_id"]);

           if($_ILlOQ === false) {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
             continue;
           }

           $_JjLJ1++;
           $_6C1t1++;
           $_ILjOj = "";
           if($this->_6oi1L->_L10FC($_ILlOQ, $this->EMailAddress, $_ILjOj, $_ILJIO) ) {

              if($this->LeaveMessagesInInbox)
                 $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
                 else
                 $_J6fij[] = $_IC1C6["msg_id"];

              $this->_L1LE6($_ILlOQ, $rid, $ListId);
              if($rid != "")
                _LPJ1D($rid, true, $ListId);

           } else {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again

             if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
               $_J6fij[] = $_IC1C6["msg_id"];
             }

             $this->_L1LE6($_ILlOQ, $rid, $ListId);
             if($rid != "")
               _LPJ1D($rid, false, $ListId);

           }

           if($_6C1t1 >= $this->NumberOfEMailsToProcess) break; // too much mails?

         } else {
           if( !in_array($_IC1C6["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
           if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
             $_J6fij[] = $_IC1C6["msg_id"];
           }
          }
       } // for

       _LRCOC();
       // delete mails
       for ($_Qli6J=count($_J6fij) - 1; $_Qli6J>=0; $_Qli6J--) {
          $_6C1oJ = $this->_6oi6i->deleteMsg($_J6fij[$_Qli6J]);
          if( IsPEARError($_6C1oJ) ) {
             $_J0COJ .= "<br />Error deleting email $_J6fij[$_Qli6J]; ".$_6C1oJ->code. ": ".$_6C1oJ->message;
          }
       }

       _LRCOC();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_QltJO) {
        $_QLCt1 = in_array($_QltJO, $_6C0oI);
        if(!$_QLCt1)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_QltJO)

     } else { # if($_6oL8t > 0)
       if($_6oL8t !== false && !IsPEARError($_6oL8t)){
         reset($this->UIDL);
         foreach($this->UIDL as $key => $_QltJO)  // remove all stored UIDL
            unset($this->UIDL[$key]);
       }   
     }
   }

   function _LCBBJ(&$_J0COJ, &$_ILJIO, &$_JjLJ1) {
     $_J6fij = array();
     $_6oL8t = $this->_6oi6i->getNumberOfMessages();
     if (IsPEARError($_6oL8t)) {
       $_J0COJ .= $_6oL8t->code. ": ".$_6oL8t->message;
       return false;
     }
     $_6C0oI = array();

     if($_6oL8t > 0) {
       $_6C1t1 = 0;
       for($_Qli6J=1; $_Qli6J<=$_6oL8t; $_Qli6J++) {
         _LRCOC();
         if(!$this->_6ootf)
           $_IC1C6 = $this->_6oi6i->getMessagesList($_Qli6J);
           else
           $_IC1C6 = $this->_6oi6i->getSummary($_Qli6J);
         if (IsPEARError($_IC1C6)) {
           continue;
         }
         if(is_array($_IC1C6))
            $_IC1C6 = $_IC1C6[0];

         if($this->_6ootf){
          $_IC1C6["uidl"] = $_IC1C6["UID"];
          $_IC1C6["msg_id"] = $_IC1C6["MSG_NUM"];
          $_IC1C6["size"] = $_IC1C6["SIZE"];
          if(isset($_IC1C6["FLAGS"]) && stripos(join(",", $_IC1C6["FLAGS"]), "seen") === true){
            continue;  
          }
         }   

         if(!isset($_IC1C6["uidl"]) || $_IC1C6["uidl"] == "")
           $_IC1C6["uidl"] = $_Qli6J;
         $_6C0oI[] = $_IC1C6["uidl"]; # save it for later checkings

         if( !in_array($_IC1C6["uidl"], $this->UIDL) && $_IC1C6["size"] < $this->_6oiCL ) {
           $_ILlOQ = $this->_6oi6i->getMessages($_IC1C6["uidl"], false);

           if($_ILlOQ === false) {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
             continue;
           }

           if (IsPEARError($_ILlOQ)) {
             $_J0COJ .= $_ILlOQ->code. ": ".$_ILlOQ->message;
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
             continue;
           }

           if(is_array($_ILlOQ))
             $_ILlOQ = $_ILlOQ[0]; // not an array

           $_JjLJ1++;
           $_6C1t1++;
           $_ILjOj = "";
           if($this->_6oi1L->_L10FC($_ILlOQ, $this->EMailAddress, $_ILjOj, $_ILJIO) ) {

              if($this->LeaveMessagesInInbox)
                 $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
                 else
                 $_J6fij[] = $_IC1C6["msg_id"];


              $this->_L1LE6($_ILlOQ, $rid, $ListId);
              if($rid != "")
                _LPJ1D($rid, true, $ListId);

           } else {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again

             if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
               $_J6fij[] = $_IC1C6["msg_id"];
             }

             $this->_L1LE6($_ILlOQ, $rid, $ListId);
             if($rid != "")
               _LPJ1D($rid, false, $ListId);

           }

           if($_6C1t1 >= $this->NumberOfEMailsToProcess) break; // too much mails?


         } else {
           if( !in_array($_IC1C6["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
           if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
             $_J6fij[] = $_IC1C6["msg_id"];
           }
          }
       } // for

       _LRCOC();
       // delete mails
       for ($_Qli6J=count($_J6fij) - 1; $_Qli6J>=0; $_Qli6J--) {
          $_6C1oJ = $this->_6oi6i->deleteMessages($_J6fij[$_Qli6J]);
          if( IsPEARError($_6C1oJ) ) {
             $_J0COJ .= "<br />Error deleting email $_J6fij[$_Qli6J]; ".$_6C1oJ->code. ": ".$_6C1oJ->message;
          }
       }

       _LRCOC();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_QltJO) {
        $_QLCt1 = in_array($_QltJO, $_6C0oI);
        if(!$_QLCt1)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_QltJO)

     } else { # if($_6oL8t > 0)
       if($_6oL8t !== false && !IsPEARError($_6oL8t)){
         reset($this->UIDL);
         foreach($this->UIDL as $key => $_QltJO)  // remove all stored UIDL
            unset($this->UIDL[$key]);
       }   
     }

   }

  } # class


  class _LCC0O {
   // @access public
   var $Name;
   var $InboxType = 'pop3'; // 'pop3', 'imap'
   var $EMailAddress;
   var $Servername;
   var $Serverport = 110;
   var $Username;
   var $Password;
   var $SSLConnection = false;
   var $NumberOfEMailsToProcess = 0;
   var $UIDL;
   var $LeaveMessagesInInbox = true;
   var $timeout = 15;
   var $EntireMessage = true;
   var $_6ootf = false; // imap only

   var $AttachmentsPath="";
   var $InlineImagesPath="";

   // @access private
   var $_6oi6i;
   var $_6oiCL = 10485760;

   // constructor

   function __construct(){
   }

   function _LCC0O() {
     self::__construct();
   }

   function _LCCOQ($_6CIjC){
     if($_6CIjC > 0)
       $this->_6oiCL = $_6CIjC;
       else
       $this->_6oiCL = 10485760;
   }

   function _LCAR0(&$_J0COJ, &$_6oL8t) {

     if($this->InboxType == "pop3"){
       $this->_6oi6i = new Net_POP3($this->SSLConnection, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_6oi6i = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     $_Ijj6Q = $this->_6oi6i->login($this->Username, $this->Password);
     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $_6oL8t = $this->_6oi6i->numMsg();

     if($this->InboxType == "imap"){
       $_6oL8t = $this->_6oi6i->getNumberOfMessages();
     }

     $this->_6oi6i->disconnect(false);
     $this->_6oi6i = null;

     return true;
   }

   function _LCACA(&$_J0COJ, &$_ILJIO, &$_JjLJ1, $_6olI6 = false, $_6olli = null) {

     # only for testing purposes
     if ( $_6olI6 && isset($_6olli) ) {
       $_JjLJ1 = count($_6olli);
       for($_Qli6J=0; $_Qli6J<count($_6olli); $_Qli6J++) {
         $_ILlOQ = join("", file($_6olli[$_Qli6J]));

         if($_I6C0o = $this->_LCDRB($_ILlOQ))
            $_ILJIO[] = $_I6C0o;

       }
       return true;
     }

     $_JjLJ1 = 0;

     if($this->InboxType == "pop3"){
       $this->_6oi6i = new Net_POP3($this->SSLConnection, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_6oi6i = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     _LRCOC();

     $_Ijj6Q = $this->_6oi6i->login($this->Username, $this->Password);
     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $this->_LCBBO($_J0COJ, $_ILJIO, $_JjLJ1);

     if($this->InboxType == "imap")
       $this->_LCBBJ($_J0COJ, $_ILJIO, $_JjLJ1);

     _LRCOC();
     $this->_6oi6i->disconnect(true);
     $this->_6oi6i = null;

     return true;
   }

   function _LCBBO(&$_J0COJ, &$_ILJIO, &$_JjLJ1) {
     $_J6fij = array();
     $_6C0oI = array();
     $_6oL8t = $this->_6oi6i->numMsg();

     if($_6oL8t > 0) {
       $_6C1t1 = 0;
       for($_Qli6J=1; $_Qli6J<=$_6oL8t; $_Qli6J++) {
         _LRCOC();
         $_IC1C6 = $this->_6oi6i->getListing($_Qli6J);
         if (IsPEARError($_IC1C6) || $_IC1C6 === false) {
           continue;
         }
         if(!isset($_IC1C6["uidl"]) || $_IC1C6["uidl"] == "")
           $_IC1C6["uidl"] = $_Qli6J;
         $_6C0oI[] = $_IC1C6["uidl"]; # save it for later checkings
         if( !in_array($_IC1C6["uidl"], $this->UIDL) ) {
           $_6CIL0 = $_IC1C6["size"] > $this->_6oiCL;
           if($this->EntireMessage && !$_6CIL0)
              $_ILlOQ = $this->_6oi6i->getMsg($_IC1C6["msg_id"]);
              else
              $_ILlOQ = $this->_6oi6i->getRawHeaders($_IC1C6["msg_id"]);

           if($_ILlOQ === false) {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
             continue;
           }

           $_JjLJ1++;
           $_6C1t1++;
           if($_I6C0o = $this->_LCDRB($_ILlOQ) ) {

              if($this->LeaveMessagesInInbox || $_6CIL0)
                 $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
                 else
                 $_J6fij[] = $_IC1C6["msg_id"];

              if($this->LeaveMessagesInInbox || $_6CIL0) {
                $_I6C0o["Server_msg_id"] = $_IC1C6["msg_id"];
                $_I6C0o["Server_uidl"] = $_IC1C6["uidl"];
                if($_6CIL0) $_I6C0o["Server_MaxEMaiSizeReached"] = $_IC1C6["size"];
              }
              $_ILJIO[] = $_I6C0o;

           } else {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
           }

           if($_6C1t1 >= $this->NumberOfEMailsToProcess) break; // too much mails?


         } else {
           if( !in_array($_IC1C6["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
          }
       } // for

       _LRCOC();
       // delete mails
       for ($_Qli6J=0; $_Qli6J<count($_J6fij); $_Qli6J++) {
         $this->_6oi6i->deleteMsg($_J6fij[$_Qli6J]);
       }

       _LRCOC();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_QltJO) {
        $_QLCt1 = in_array($_QltJO, $_6C0oI);
        if(!$_QLCt1)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_QltJO)

     } else { # if($_6oL8t > 0)
       if($_6oL8t !== false && !IsPEARError($_6oL8t)){
         reset($this->UIDL);
         foreach($this->UIDL as $key => $_QltJO)  // remove all stored UIDL
            unset($this->UIDL[$key]);
       }   
     }
   }

   function _LCBBJ(&$_J0COJ, &$_ILJIO, &$_JjLJ1) {
     $_J6fij = array();
     $_6oL8t = $this->_6oi6i->getNumberOfMessages();
     $_6C0oI = array();

     if($_6oL8t > 0) {
       $_6C1t1 = 0;
       for($_Qli6J=1; $_Qli6J<=$_6oL8t; $_Qli6J++) {
         _LRCOC();
         if(!$this->_6ootf)
           $_IC1C6 = $this->_6oi6i->getMessagesList($_Qli6J);
           else
           $_IC1C6 = $this->_6oi6i->getSummary($_Qli6J);
         if (IsPEARError($_IC1C6)) {
           continue;
         }
        
         if(is_array($_IC1C6))
            $_IC1C6 = $_IC1C6[0];

         if($this->_6ootf){
          $_IC1C6["uidl"] = $_IC1C6["UID"];
          $_IC1C6["msg_id"] = $_IC1C6["MSG_NUM"];
          $_IC1C6["size"] = $_IC1C6["SIZE"];
          if(isset($_IC1C6["FLAGS"]) && stripos(join(",", $_IC1C6["FLAGS"]), "seen") === true){
            continue;  
          }
         }   
            
         if(!isset($_IC1C6["uidl"]) || $_IC1C6["uidl"] == "")
           $_IC1C6["uidl"] = $_Qli6J;
         $_6C0oI[] = $_IC1C6["uidl"]; # save it for later checkings
         $_6CIL0 = $_IC1C6["size"] > $this->_6oiCL;
         if( !in_array($_IC1C6["uidl"], $this->UIDL) ) {

           if($this->EntireMessage && !$_6CIL0)
              $_ILlOQ = $this->_6oi6i->getMessages($_IC1C6["uidl"], false);
              else
              $_ILlOQ = $this->_6oi6i->getRawHeaders($_IC1C6["msg_id"]);

           if($_ILlOQ === false) {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
             continue;
           }

           if (IsPEARError($_ILlOQ)) {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
             $_J0COJ .= $_ILlOQ->code. ": ".$_ILlOQ->message;
             continue;
           }

           if(is_array($_ILlOQ))
             $_ILlOQ = $_ILlOQ[0]; // not an array

           $_6C1t1++;
           $_JjLJ1++;
           if($_I6C0o = $this->_LCDRB($_ILlOQ) ) {

              if($this->LeaveMessagesInInbox || $_6CIL0)
                 $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
                 else
                 $_J6fij[] = $_IC1C6["msg_id"];

              if($this->LeaveMessagesInInbox || $_6CIL0) {
                $_I6C0o["Server_msg_id"] = $_IC1C6["msg_id"];
                $_I6C0o["Server_uidl"] = $_IC1C6["uidl"];
                if($_6CIL0) $_I6C0o["Server_MaxEMaiSizeReached"] = $_IC1C6["size"];
              }

              $_ILJIO[] = $_I6C0o;

           } else {
             $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
           }

           if($_6C1t1 >= $this->NumberOfEMailsToProcess) break; // too much mails?

         } else {
           if( !in_array($_IC1C6["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IC1C6["uidl"]; // don't load it again
          }
       } // for

       _LRCOC();
       // delete mails
       for ($_Qli6J=0; $_Qli6J<count($_J6fij); $_Qli6J++) {
         $this->_6oi6i->deleteMessages($_J6fij[$_Qli6J]);
       }

       _LRCOC();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_QltJO) {
        $_QLCt1 = in_array($_QltJO, $_6C0oI);
        if(!$_QLCt1)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_QltJO)

     } else { # if($_6oL8t > 0)
       if($_6oL8t !== false && !IsPEARError($_6oL8t)){
         reset($this->UIDL);
         foreach($this->UIDL as $key => $_QltJO)  // remove all stored UIDL
            unset($this->UIDL[$key]);
       }   
     }

   }

   // @access public
   // $_J6fij array("msg_id"=>id, "uidl" => uidl)
   function deleteMessagesFromServer($_J6fij, &$_J0COJ){

     if($this->InboxType == "pop3"){
       $this->_6oi6i = new Net_POP3($this->SSLConnection, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_6oi6i = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_Ijj6Q = $this->_6oi6i->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     _LRCOC();

     $_Ijj6Q = $this->_6oi6i->login($this->Username, $this->Password);
     if (IsPEARError($_Ijj6Q)) {
       $_J0COJ = $_Ijj6Q->code. ": ".$_Ijj6Q->message;
       return false;
     }

     // delete mails
     for ($_Qli6J=0; $_Qli6J<count($_J6fij); $_Qli6J++) {
        if($this->InboxType == "pop3")
         $this->_6oi6i->deleteMsg($_J6fij[$_Qli6J]["msg_id"]);
        else
         $this->_6oi6i->deleteMessages($_J6fij[$_Qli6J]["msg_id"]);

        $_6CjjJ = $_J6fij[$_Qli6J]["uidl"];

        reset($this->UIDL);
        foreach($this->UIDL as $key => $_QltJO) {
         $_QLCt1 = $_6CjjJ == $_QltJO;
         if($_QLCt1){
           unset($this->UIDL[$key]);
           break;
         }
        } # foreach($this->UIDL as $key => $_QltJO)
     }

     _LRCOC();
     $this->_6oi6i->disconnect(true);
     $this->_6oi6i = null;
     return true;
   }

   // @access private
   function _LCDRB($_ILItl) {
     global $_QLl1Q;
     if (! preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $_ILItl, $_6CjtQ) )
       $_ILItl .= $_QLl1Q.$_QLl1Q." ";
     $_ILJt8 = new Mail_mimeDecode($_ILItl);

     // only header decode?
     if(!$this->EntireMessage)
       $_I08CQ["decode_headers"] = true;
       else {
        // decode all
        $_I08CQ["decode_headers"] = true;
        $_I08CQ["include_bodies"] = true;
        $_I08CQ["decode_bodies"] = true;
       }

     $_IL6J6 = $_ILJt8->decode($_I08CQ);
     if(IsPEARError($_IL6J6)) {
       unset($_ILJt8);
       return false;
     }

     /*
      do not decode because kyrillic chars are converted to iso-8859-1
     foreach($_IL6J6->headers as $key => $_QltJO) {
       if(is_array($_QltJO)) continue; // no arrays
       if (IsUtf8String($_QltJO)) {
          $x = utf8_decode($_QltJO);
          if($x != "")
            $_QltJO = $x;
          $_IL6J6->headers[$key] = $_QltJO;
       }
     } */

     unset($_ILJt8);

     if(!$this->EntireMessage)
        return $_IL6J6->headers;
        else {
           $_j10IJ = array();
           $_j10IJ["headers"] = $_IL6J6->headers;
           $_6QCli = $_IL6J6->ctype_primary . '/'. $_IL6J6->ctype_secondary;
           $_j10IJ["content_type"] = strtolower($_6QCli);

 			     $_6CJ6J = "";
					 if(isset($_IL6J6->ctype_parameters) && isset($_IL6J6->ctype_parameters["charset"]))
      			 $_6CJ6J = $_IL6J6->ctype_parameters["charset"];
           $_j10IJ["charset"] = $_6CJ6J;

           $_j10IJ["multipart"] = false;
           $_I0iti = array();

           if  ( (isset($_IL6J6->parts)) && (is_array($_IL6J6->parts)) ){ // multipart?

             for($_QlOjt=0; $_QlOjt<count($_IL6J6->parts); $_QlOjt++){

               if(is_object($_IL6J6->parts[$_QlOjt])){

                  if( !isset($_IL6J6->parts[$_QlOjt]->disposition) && strtolower($_IL6J6->parts[$_QlOjt]->ctype_primary) == "multipart")
                  {
                    $_j10IJ["multipart"] = true;
                    
                    for($_IOLil=0; $_IOLil<count($_IL6J6->parts[$_QlOjt]->parts); $_IOLil++) {
                      $_6C6I6 = $_IL6J6->parts[$_QlOjt]->parts[$_IOLil];
                      $this->_LCE68($_6C6I6, $_I0iti);
                      $_6C6Lj = $_6C6I6;
                      if(is_object($_6C6Lj) && isset($_6C6Lj->parts) && is_array($_6C6Lj->parts)) {
                        for($_68tQ1=0; $_68tQ1<count($_6C6Lj->parts); $_68tQ1++) {
                          $_6CfIQ = $_6C6Lj->parts[$_68tQ1];
                          
                          if(is_object($_6CfIQ) && isset($_6CfIQ->parts) && is_array($_6CfIQ->parts) && isset($_6CfIQ->ctype_primary) && isset($_6CfIQ->ctype_secondary) ){
                            for($_j60Q0=0; $_j60Q0<count($_6CfIQ->parts); $_j60Q0++){  //multipart/signed emails, multipart/mixed and a part multipart/alternative
                              $_6Cffi = $_6CfIQ->parts[$_j60Q0];
                              $this->_LCE68($_6Cffi, $_I0iti);
                            }
                          }
                          else
                            $this->_LCE68($_6CfIQ, $_I0iti);
                        }
                      }
                    }
                  } else {
                    $this->_LCE68($_IL6J6->parts[$_QlOjt], $_I0iti);
                  }

               }

             }
           }

           $_j10IJ["body"] = "";
           if( isset($_IL6J6->body) )
             $_j10IJ["body"] = $_IL6J6->body;

           $_j10IJ["parts"] = $_I0iti;

           return $_j10IJ;

        }
   }

   function _LCE68($_6fjiJ, &$_I0iti){

      $_6CJ6J = "";
      if(isset($_6fjiJ->ctype_parameters) && isset($_6fjiJ->ctype_parameters["charset"]))
        $_6CJ6J = $_6fjiJ->ctype_parameters["charset"];

      $_6CfCl = "";

      // plain text part
      if( !isset($_6fjiJ->disposition) && strtolower($_6fjiJ->ctype_primary) == "text" && strtolower($_6fjiJ->ctype_secondary) != "html")
      {
        // Apple mail adds a 2nd plain text we add this to first part
        $_6C86f = false;
        for($_Qli6J=0; $_Qli6J<count($_I0iti) && !$_6C86f; $_Qli6J++){
          $_6C86f = isset($_I0iti[$_Qli6J]['plaintext']) && $_I0iti[$_Qli6J]['plaintext'];
          if($_6C86f){ // we add plaintext part
            $_I0iti[$_Qli6J]['body'] .= $_6fjiJ->body; 
            return true;
            break;
          }
        }
        if(!$_6C86f){
          $_I0iti[] = array('plaintext' => true, 'html' => false,  'charset' => $_6CJ6J, 'body' => $_6fjiJ->body);
          return true;
        }
      }

      // plain html part
      if( (!isset($_6fjiJ->disposition) || $_6fjiJ->disposition == 'inline' /* for multipart/mixed */ ) && strtolower($_6fjiJ->ctype_primary) == "text" && strtolower($_6fjiJ->ctype_secondary) == "html")
      {
        // Apple mail adds a 2nd html part we add this as attachment when it's not empty
        $_6C86f = false;
        for($_Qli6J=0; $_Qli6J<count($_I0iti) && !$_6C86f; $_Qli6J++){
          $_6C86f = isset($_I0iti[$_Qli6J]['html']) && $_I0iti[$_Qli6J]['html'];
          if($_6C86f && !isset($_6fjiJ->disposition) ){ // is attachment?
            
            if(trim(_LBDA8($_6fjiJ->body)) != ""){
              $_6fjiJ->disposition = "attachment";
              $_6fjiJ->d_parameters["filename"] = "nonamedattachmentfile.html";
            }
            
            break;
          }
        }

        if(!$_6C86f){
          $_I0iti[] = array('plaintext' => false, 'html' => true, 'charset' => $_6CJ6J, 'body' => $_6fjiJ->body);
          return true;
        }
      }


      // web.de signed as attachment but is inline
      if(isset($_6fjiJ->disposition) && strtolower($_6fjiJ->disposition) == "attachment" && isset($_6fjiJ->d_parameters))
      {

         $_Jf0Cl = "";
         if(isset($_6fjiJ->d_parameters["contentid"]))
           $_Jf0Cl = $_6fjiJ->d_parameters["contentid"]["value"];
         if($_Jf0Cl != "")
           $_6fjiJ->disposition = "inline";
      }

      // AppleMail part is marked as inline, but no Content-Id, than it must be an attachment
      if(isset($_6fjiJ->disposition) && isset($_6fjiJ->d_parameters) && strtolower($_6fjiJ->disposition) == "inline"){
        if(!isset($_6fjiJ->d_parameters["contentid"]) || empty($_6fjiJ->d_parameters["contentid"]["value"]))
          $_6fjiJ->disposition = "attachment";
      }
      
      if(isset($_6fjiJ->disposition) && isset($_6fjiJ->d_parameters) && strtolower($_6fjiJ->disposition) == "attachment")
      {
              if(isset($_6fjiJ->d_parameters["filename"]))
                $_JfIIf = $_6fjiJ->d_parameters["filename"];
                else
                $_JfIIf = "nonamedattachmentfile";

              if(isset($_6fjiJ->d_parameters["filename-charset"]) && mbfunctionsExists){
                $_I0lji = mb_convert_encoding($_JfIIf, "ISO-8859-1", $_6fjiJ->d_parameters["filename-charset"]);
                if($_I0lji != "")
                  $_JfIIf = $_I0lji;
              }  
                
              if(IsUtf8String($_JfIIf))
                $_JfIIf = utf8_decode($_JfIIf);

              if($_JfIIf == "smime.p7s")
                return false; // we don't support this

              $_JfIIf = GetUniqueFileNameInPath($this->AttachmentsPath, $_JfIIf);

              if($this->AttachmentsPath != "") {
                $_I60fo = @fopen (_LPC1C($this->AttachmentsPath) . $_JfIIf, 'wb');
                if($_I60fo){
                  @fwrite ($_I60fo, $_6fjiJ->body);
                  @fclose($_I60fo);
                  $_I0iti[] = array("attachment" => $_JfIIf);
                } else{
                  // can't save it, error handling?
                  return false;
                }
              }
              return true;
      }

      if(isset($_6fjiJ->disposition) && isset($_6fjiJ->d_parameters) && strtolower($_6fjiJ->disposition) == "inline")
      {

              if(isset($_6fjiJ->d_parameters["filename"]))
                $_JfIIf = $_6fjiJ->d_parameters["filename"];
                else
                $_JfIIf = "nonamedimage";
              if(IsUtf8String($_JfIIf) || (isset( $_6fjiJ->d_parameters["filename-charset"] ) && strtolower($_6fjiJ->d_parameters["filename-charset"]) == "utf-8") )
                $_JfIIf = utf8_decode($_JfIIf);

              $_Jf0Cl = "";
              if(isset($_6fjiJ->d_parameters["contentid"]))
                $_Jf0Cl = $_6fjiJ->d_parameters["contentid"]["value"];

              $_JfIIf = GetUniqueFileNameInPath($this->InlineImagesPath, $_JfIIf);

              if($this->InlineImagesPath != "") {
                $_I60fo = @fopen (_LPC1C($this->InlineImagesPath) . $_JfIIf, 'wb');
                if($_I60fo){
                  @fwrite ($_I60fo, $_6fjiJ->body);
                  @fclose($_I60fo);
                  $_I0iti[] = array("inlineimage" => $_JfIIf, "cid" => $_Jf0Cl);
                } else{
                  // can't save it, error handling?
                  return false;
                }
              }
              return true;
      }
      
   }

  } # class

?>

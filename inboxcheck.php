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
  include_once("mail.php");
  $_J66tC = "./PEAR/";
  if(!@include_once($_J66tC."IMAP.php")) {
    $_J66tC = InstallPath."PEAR/";
    include_once($_J66tC."IMAP.php");
  }
  include_once($_J66tC."POP3.php");
  include_once($_J66tC."mimeDecode.php");
  include_once($_J66tC."RFC822.php");
  include_once("bouncer.php");

  class _ODPCC {
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

   // @access private
   var $_J8t10;
   var $_J8O0t;
   var $_J8Olf;
   var $_J8otf = 1048576;

   // constructor
   function __construct($_J8Cf6) {
     $this->_J8t10 = $_J8Cf6;
     $this->_J8O0t = new _OL0A1( _OBLDR($this->_J8t10) );
   }

   function _ODPCC($_J8Cf6) {
     self::__construct($_J8Cf6);
   }

   function _ODA1F(&$_jj0JO, &$_J8C8O) {

     if($this->InboxType == "pop3"){
       $this->_J8Olf = new Net_POP3($this->SSLConnection, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_J8Olf = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     $_QoQOL = $this->_J8Olf->login($this->Username, $this->Password);
     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $_J8C8O = $this->_J8Olf->numMsg();

     if($this->InboxType == "imap")
       $_J8C8O = $this->_J8Olf->getNumberOfMessages();

     $this->_J8Olf->disconnect(false);

     return true;
   }

   function _ODA80(&$_jj0JO, &$_IJ6Cf, &$_jft86, $_J8i6t = false, $_J8iLL = null) {

     # only for testing purposes
     if ( $_J8i6t && isset($_J8iLL) ) {
       $_jft86 = count($_J8iLL);
       for($_Q6llo=0; $_Q6llo<count($_J8iLL); $_Q6llo++) {
         $_J601L = join("", file($_J8iLL[$_Q6llo]));

         $_IJJLJ = "";
         $_IJ6Cf = array();
         if($this->_J8O0t->_OL1LO($_J601L, $this->EMailAddress, $_IJJLJ, $_IJ6Cf) ) {
           print_r( $_IJ6Cf );
         }
       }
       exit;
     }

     $_jft86 = 0;

     if($this->InboxType == "pop3"){
       $this->_J8Olf = new Net_POP3($this->SSLConnection, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_J8Olf = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     _OPQ6J();

     $_QoQOL = $this->_J8Olf->login($this->Username, $this->Password);
     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $this->_ODBL0($_jj0JO, $_IJ6Cf, $_jft86);

     if($this->InboxType == "imap")
       $this->_ODB6F($_jj0JO, $_IJ6Cf, $_jft86);

     _OPQ6J();
     $this->_J8Olf->disconnect(true);

     return true;
   }

   function _ODBL0(&$_jj0JO, &$_IJ6Cf, &$_jft86) {
     global $_jJtJt;
     $_jt1CC = array();
     $_J8ilI = array();
     $_J8C8O = $this->_J8Olf->numMsg();

     if($_J8C8O > 0) {
       $_J8LQj = 0;
       for($_Q6llo=1; $_Q6llo<=$_J8C8O; $_Q6llo++) {
         _OPQ6J();
         $_IIJi1 = $this->_J8Olf->getListing($_Q6llo);
         if (IsPEARError($_IIJi1) || $_IIJi1 === false) {
           continue;
         }
         if(!isset($_IIJi1["uidl"]) || $_IIJi1["uidl"] == "")
           $_IIJi1["uidl"] = $_Q6llo;
         $_J8ilI[] = $_IIJi1["uidl"]; # save it for later checkings
         if( !in_array($_IIJi1["uidl"], $this->UIDL) && $_IIJi1["size"] < $this->_J8otf ) {
           $_J601L = $this->_J8Olf->getMsg($_IIJi1["msg_id"]);

           if($_J601L === false) {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
             continue;
           }

           $_jft86++;
           $_J8LQj++;
           $_IJJLJ = "";
           if($this->_J8O0t->_OL1LO($_J601L, $this->EMailAddress, $_IJJLJ, $_IJ6Cf) ) {

              if($this->LeaveMessagesInInbox)
                 $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
                 else
                 $_jt1CC[] = $_IIJi1["msg_id"];

              $_Q6i6i = strpos($_J601L, $_jJtJt.": ");
              if($_Q6i6i !== false) {
                 $_J601L = substr($_J601L, $_Q6i6i + strlen($_jJtJt.": "));
                 $_J601L = trim(substr($_J601L, 0, strpos($_J601L, "\n") - 1));
                 _OAC1D($_J601L, true);
              }

           } else {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again

             if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
               $_jt1CC[] = $_IIJi1["msg_id"];
             }

             $_Q6i6i = strpos($_J601L, $_jJtJt.": ");
             if( $_Q6i6i !== false) {
                $_J601L = substr($_J601L, $_Q6i6i + strlen($_jJtJt.": "));
                $_J601L = trim(substr($_J601L, 0, strpos($_J601L, "\n") - 1));
                _OAC1D($_J601L, false);
             }

           }

           if($_J8LQj >= $this->NumberOfEMailsToProcess) break; // too much mails?

         } else {
           if( !in_array($_IIJi1["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
           if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
             $_jt1CC[] = $_IIJi1["msg_id"];
           }
          }
       } // for

       _OPQ6J();
       // delete mails
       for ($_Q6llo=count($_jt1CC) - 1; $_Q6llo>=0; $_Q6llo--) {
          $_J8L8Q = $this->_J8Olf->deleteMsg($_jt1CC[$_Q6llo]);
          if( IsPEARError($_J8L8Q) ) {
             $_jj0JO .= "<br />Error deleting email $_jt1CC[$_Q6llo]; ".$_J8L8Q->code. ": ".$_J8L8Q->message;
          }
       }

       _OPQ6J();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO) {
        $_Qo1oC = in_array($_Q6ClO, $_J8ilI);
        if(!$_Qo1oC)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_Q6ClO)

     } else { # if($_J8C8O > 0)
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO)  // remove all stored UIDL
          unset($this->UIDL[$key]);
     }
   }

   function _ODB6F(&$_jj0JO, &$_IJ6Cf, &$_jft86) {
     global $_jJtJt;
     $_jt1CC = array();
     $_J8C8O = $this->_J8Olf->getNumberOfMessages();
     if (IsPEARError($_J8C8O)) {
       $_jj0JO .= $_J8C8O->code. ": ".$_J8C8O->message;
       return false;
     }
     $_J8ilI = array();

     if($_J8C8O > 0) {
       $_J8LQj = 0;
       for($_Q6llo=1; $_Q6llo<=$_J8C8O; $_Q6llo++) {
         _OPQ6J();
         $_IIJi1 = $this->_J8Olf->getMessagesList($_Q6llo);
         if (IsPEARError($_IIJi1)) {
           continue;
         }
         if(is_array($_IIJi1))
            $_IIJi1 = $_IIJi1[0];

         if(!isset($_IIJi1["uidl"]) || $_IIJi1["uidl"] == "")
           $_IIJi1["uidl"] = $_Q6llo;
         $_J8ilI[] = $_IIJi1["uidl"]; # save it for later checkings

         if( !in_array($_IIJi1["uidl"], $this->UIDL) && $_IIJi1["size"] < $this->_J8otf ) {
           $_J601L = $this->_J8Olf->getMessages($_IIJi1["uidl"], false);

           if($_J601L === false) {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
             continue;
           }

           if (IsPEARError($_J601L)) {
             $_jj0JO .= $_J601L->code. ": ".$_J601L->message;
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
             continue;
           }

           if(is_array($_J601L))
             $_J601L = $_J601L[0]; // not an array

           $_jft86++;
           $_J8LQj++;
           $_IJJLJ = "";
           if($this->_J8O0t->_OL1LO($_J601L, $this->EMailAddress, $_IJJLJ, $_IJ6Cf) ) {

              if($this->LeaveMessagesInInbox)
                 $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
                 else
                 $_jt1CC[] = $_IIJi1["msg_id"];


              $_Q6i6i = strpos($_J601L, $_jJtJt.": ");
              if( $_Q6i6i !== false) {
                 $_J601L = substr($_J601L, $_Q6i6i + strlen($_jJtJt.": "));
                 $_J601L = trim(substr($_J601L, 0, strpos($_J601L, "\n") - 1));
                 _OAC1D($_J601L, true);
              }

           } else {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again

             if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
               $_jt1CC[] = $_IIJi1["msg_id"];
             }

             $_Q6i6i = strpos($_J601L, $_jJtJt.": ");
             if( $_Q6i6i !== false) {
                $_J601L = substr($_J601L, $_Q6i6i + strlen($_jJtJt.": "));
                $_J601L = trim(substr($_J601L, 0, strpos($_J601L, "\n") - 1));
                _OAC1D($_J601L, false);
             }

           }

           if($_J8LQj >= $this->NumberOfEMailsToProcess) break; // too much mails?


         } else {
           if( !in_array($_IIJi1["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
           if(!$this->LeaveMessagesInInbox && $this->RemoveUnknownMailsAndSoftbounces) {
             $_jt1CC[] = $_IIJi1["msg_id"];
           }
          }
       } // for

       _OPQ6J();
       // delete mails
       for ($_Q6llo=count($_jt1CC) - 1; $_Q6llo>=0; $_Q6llo--) {
          $_J8L8Q = $this->_J8Olf->deleteMessages($_jt1CC[$_Q6llo]);
          if( IsPEARError($_J8L8Q) ) {
             $_jj0JO .= "<br />Error deleting email $_jt1CC[$_Q6llo]; ".$_J8L8Q->code. ": ".$_J8L8Q->message;
          }
       }

       _OPQ6J();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO) {
        $_Qo1oC = in_array($_Q6ClO, $_J8ilI);
        if(!$_Qo1oC)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_Q6ClO)

     } else { # if($_J8C8O > 0)
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO)  // remove all stored UIDL
          unset($this->UIDL[$key]);
     }

   }

  } # class


  class _ODCLL {
   // @access public
   var $Name;
   var $InboxType='pop3'; // 'pop3', 'imap'
   var $EMailAddress;
   var $Servername;
   var $Serverport=110;
   var $Username;
   var $Password;
   var $SSLConnection=false;
   var $NumberOfEMailsToProcess=0;
   var $UIDL;
   var $LeaveMessagesInInbox = true;
   var $timeout=15;
   var $EntireMessage=true;

   var $AttachmentsPath="";
   var $InlineImagesPath="";

   // @access private
   var $_J8Olf;
   var $_J8otf = 10485760;

   // constructor

   function __construct(){
   }

   function _ODCLL() {
     self::__construct();
   }

   function _ODA1F(&$_jj0JO, &$_J8C8O) {

     if($this->InboxType == "pop3"){
       $this->_J8Olf = new Net_POP3($this->SSLConnection, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_J8Olf = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     $_QoQOL = $this->_J8Olf->login($this->Username, $this->Password);
     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $_J8C8O = $this->_J8Olf->numMsg();

     if($this->InboxType == "imap")
       $_J8C8O = $this->_J8Olf->getNumberOfMessages();

     $this->_J8Olf->disconnect(false);

     return true;
   }

   function _ODA80(&$_jj0JO, &$_IJ6Cf, &$_jft86, $_J8i6t = false, $_J8iLL = null) {

     # only for testing purposes
     if ( $_J8i6t && isset($_J8iLL) ) {
       $_jft86 = count($_J8iLL);
       for($_Q6llo=0; $_Q6llo<count($_J8iLL); $_Q6llo++) {
         $_J601L = join("", file($_J8iLL[$_Q6llo]));

         if($_QiOo1 = $this->_ODDRD($_J601L))
            $_IJ6Cf[] = $_QiOo1;

       }
       return true;
     }

     $_jft86 = 0;

     if($this->InboxType == "pop3"){
       $this->_J8Olf = new Net_POP3($this->SSLConnection, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_J8Olf = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     _OPQ6J();

     $_QoQOL = $this->_J8Olf->login($this->Username, $this->Password);
     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     if($this->InboxType == "pop3")
       $this->_ODBL0($_jj0JO, $_IJ6Cf, $_jft86);

     if($this->InboxType == "imap")
       $this->_ODB6F($_jj0JO, $_IJ6Cf, $_jft86);

     _OPQ6J();
     $this->_J8Olf->disconnect(true);

     return true;
   }

   function _ODBL0(&$_jj0JO, &$_IJ6Cf, &$_jft86) {
     $_jt1CC = array();
     $_J8ilI = array();
     $_J8C8O = $this->_J8Olf->numMsg();

     if($_J8C8O > 0) {
       $_J8LQj = 0;
       for($_Q6llo=1; $_Q6llo<=$_J8C8O; $_Q6llo++) {
         _OPQ6J();
         $_IIJi1 = $this->_J8Olf->getListing($_Q6llo);
         if (IsPEARError($_IIJi1) || $_IIJi1 === false) {
           continue;
         }
         if(!isset($_IIJi1["uidl"]) || $_IIJi1["uidl"] == "")
           $_IIJi1["uidl"] = $_Q6llo;
         $_J8ilI[] = $_IIJi1["uidl"]; # save it for later checkings
         if( !in_array($_IIJi1["uidl"], $this->UIDL) && $_IIJi1["size"] < $this->_J8otf ) {
           if($this->EntireMessage)
              $_J601L = $this->_J8Olf->getMsg($_IIJi1["msg_id"]);
              else
              $_J601L = $this->_J8Olf->getRawHeaders($_IIJi1["msg_id"]);

           if($_J601L === false) {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
             continue;
           }

           $_jft86++;
           $_J8LQj++;
           if($_QiOo1 = $this->_ODDRD($_J601L) ) {

              if($this->LeaveMessagesInInbox)
                 $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
                 else
                 $_jt1CC[] = $_IIJi1["msg_id"];

              if($this->LeaveMessagesInInbox) {
                $_QiOo1["Server_msg_id"] = $_IIJi1["msg_id"];
                $_QiOo1["Server_uidl"] = $_IIJi1["uidl"];
              }
              $_IJ6Cf[] = $_QiOo1;

           } else {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
           }

           if($_J8LQj >= $this->NumberOfEMailsToProcess) break; // too much mails?


         } else {
           if( !in_array($_IIJi1["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
          }
       } // for

       _OPQ6J();
       // delete mails
       for ($_Q6llo=0; $_Q6llo<count($_jt1CC); $_Q6llo++) {
         $this->_J8Olf->deleteMsg($_jt1CC[$_Q6llo]);
       }

       _OPQ6J();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO) {
        $_Qo1oC = in_array($_Q6ClO, $_J8ilI);
        if(!$_Qo1oC)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_Q6ClO)

     } else { # if($_J8C8O > 0)
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO)  // remove all stored UIDL
          unset($this->UIDL[$key]);
     }
   }

   function _ODB6F(&$_jj0JO, &$_IJ6Cf, &$_jft86) {
     $_jt1CC = array();
     $_J8C8O = $this->_J8Olf->getNumberOfMessages();
     $_J8ilI = array();

     if($_J8C8O > 0) {
       $_J8LQj = 0;
       for($_Q6llo=1; $_Q6llo<=$_J8C8O; $_Q6llo++) {
         _OPQ6J();
         $_IIJi1 = $this->_J8Olf->getMessagesList($_Q6llo);
         if (IsPEARError($_IIJi1)) {
           continue;
         }
         if(is_array($_IIJi1))
            $_IIJi1 = $_IIJi1[0];

         if(!isset($_IIJi1["uidl"]) || $_IIJi1["uidl"] == "")
           $_IIJi1["uidl"] = $_Q6llo;
         $_J8ilI[] = $_IIJi1["uidl"]; # save it for later checkings
         if( !in_array($_IIJi1["uidl"], $this->UIDL) && $_IIJi1["size"] < $this->_J8otf ) {

           if($this->EntireMessage)
              $_J601L = $this->_J8Olf->getMessages($_IIJi1["uidl"], false);
              else
              $_J601L = $this->_J8Olf->getRawHeaders($_IIJi1["msg_id"]);

           if($_J601L === false) {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
             continue;
           }

           if (IsPEARError($_J601L)) {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
             $_jj0JO .= $_J601L->code. ": ".$_J601L->message;
             continue;
           }

           if(is_array($_J601L))
             $_J601L = $_J601L[0]; // not an array

           $_J8LQj++;
           $_jft86++;
           if($_QiOo1 = $this->_ODDRD($_J601L) ) {

              if($this->LeaveMessagesInInbox)
                 $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
                 else
                 $_jt1CC[] = $_IIJi1["msg_id"];

              if($this->LeaveMessagesInInbox) {
                $_QiOo1["Server_msg_id"] = $_IIJi1["msg_id"];
                $_QiOo1["Server_uidl"] = $_IIJi1["uidl"];
              }

              $_IJ6Cf[] = $_QiOo1;

           } else {
             $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
           }

           if($_J8LQj >= $this->NumberOfEMailsToProcess) break; // too much mails?

         } else {
           if( !in_array($_IIJi1["uidl"], $this->UIDL) )
              $this->UIDL[] = $_IIJi1["uidl"]; // don't load it again
          }
       } // for

       _OPQ6J();
       // delete mails
       for ($_Q6llo=0; $_Q6llo<count($_jt1CC); $_Q6llo++) {
         $this->_J8Olf->deleteMessages($_jt1CC[$_Q6llo]);
       }

       _OPQ6J();
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO) {
        $_Qo1oC = in_array($_Q6ClO, $_J8ilI);
        if(!$_Qo1oC)
          unset($this->UIDL[$key]);
       } # foreach($this->UIDL as $key => $_Q6ClO)

     } else { # if($_J8C8O > 0)
       reset($this->UIDL);
       foreach($this->UIDL as $key => $_Q6ClO)  // remove all stored UIDL
          unset($this->UIDL[$key]);
     }

   }

   // @access public
   // $_jt1CC array("msg_id"=>id, "uidl" => uidl)
   function deleteMessagesFromServer($_jt1CC, &$_jj0JO){

     if($this->InboxType == "pop3"){
       $this->_J8Olf = new Net_POP3($this->SSLConnection, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport);
     }
     if($this->InboxType == "imap") {
       $this->_J8Olf = new Net_IMAP($this->Servername, $this->Serverport, false, $this->timeout);
       $_QoQOL = $this->_J8Olf->connect($this->Servername, $this->Serverport, false, $this->SSLConnection);
     }

     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     _OPQ6J();

     $_QoQOL = $this->_J8Olf->login($this->Username, $this->Password);
     if (IsPEARError($_QoQOL)) {
       $_jj0JO = $_QoQOL->code. ": ".$_QoQOL->message;
       return false;
     }

     // delete mails
     for ($_Q6llo=0; $_Q6llo<count($_jt1CC); $_Q6llo++) {
        if($this->InboxType == "pop3")
         $this->_J8Olf->deleteMsg($_jt1CC[$_Q6llo]["msg_id"]);
        else
         $this->_J8Olf->deleteMessages($_jt1CC[$_Q6llo]["msg_id"]);

        $_Jt068 = $_jt1CC[$_Q6llo]["uidl"];

        reset($this->UIDL);
        foreach($this->UIDL as $key => $_Q6ClO) {
         $_Qo1oC = $_Jt068 == $_Q6ClO;
         if($_Qo1oC){
           unset($this->UIDL[$key]);
           break;
         }
        } # foreach($this->UIDL as $key => $_Q6ClO)
     }

     _OPQ6J();
     $this->_J8Olf->disconnect(true);
     return true;
   }

   // @access private
   function _ODDRD($_IJJfQ) {
     global $_Q6JJJ;
     if (! preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $_IJJfQ, $_Jt0lt) )
       $_IJJfQ .= $_Q6JJJ.$_Q6JJJ." ";
     $_IJ80Q = new Mail_mimeDecode($_IJJfQ);

     // only header decode?
     if(!$this->EntireMessage)
       $_QffOf["decode_headers"] = true;
       else {
        // decode all
        $_QffOf["decode_headers"] = true;
        $_QffOf["include_bodies"] = true;
        $_QffOf["decode_bodies"] = true;
       }

     $_IJ8tf = $_IJ80Q->decode($_QffOf);
     if(IsPEARError($_IJ8tf)) {
       unset($_IJ80Q);
       return false;
     }

     /*
      do not decode because kyrillic chars are converted to iso-8859-1
     foreach($_IJ8tf->headers as $key => $_Q6ClO) {
       if(is_array($_Q6ClO)) continue; // no arrays
       if (IsUtf8String($_Q6ClO)) {
          $x = utf8_decode($_Q6ClO);
          if($x != "")
            $_Q6ClO = $x;
          $_IJ8tf->headers[$key] = $_Q6ClO;
       }
     } */

     unset($_IJ80Q);

     if(!$this->EntireMessage)
        return $_IJ8tf->headers;
        else {
           $_IiJit = array();
           $_IiJit["headers"] = $_IJ8tf->headers;
           $_Jt1fL = $_IJ8tf->ctype_primary . '/'. $_IJ8tf->ctype_secondary;
           $_IiJit["content_type"] = strtolower($_Jt1fL);

							    $_JtQ6o = "";
						     if(isset($_IJ8tf->ctype_parameters) && isset($_IJ8tf->ctype_parameters["charset"]))
      							$_JtQ6o = $_IJ8tf->ctype_parameters["charset"];
           $_IiJit["charset"] = $_JtQ6o;

           $_IiJit["multipart"] = false;
           $_Qfo8t = array();

           if  ( (isset($_IJ8tf->parts)) && (is_array($_IJ8tf->parts)) ){ // multipart?
             for($_Q6i6i=0; $_Q6i6i<count($_IJ8tf->parts); $_Q6i6i++){

               if(is_object($_IJ8tf->parts[$_Q6i6i])){

                  if( !isset($_IJ8tf->parts[$_Q6i6i]->disposition) && strtolower($_IJ8tf->parts[$_Q6i6i]->ctype_primary) == "multipart")
                  {
                    $_IiJit["multipart"] = true;
                    for($_I1i8O=0; $_I1i8O<count($_IJ8tf->parts[$_Q6i6i]->parts); $_I1i8O++) {
                      $this->_ODEOE($_IJ8tf->parts[$_Q6i6i]->parts[$_I1i8O], $_Qfo8t);
                      $_JtIIj = $_IJ8tf->parts[$_Q6i6i]->parts[$_I1i8O];
                      if(is_object($_JtIIj) && isset($_JtIIj->parts) && is_array($_JtIIj->parts)) {
                        for($_JffQ8=0; $_JffQ8<count($_JtIIj->parts); $_JffQ8++) {
                          $this->_ODEOE($_JtIIj->parts[$_JffQ8], $_Qfo8t);
                        }
                      }
                    }
                  } else {
                    $this->_ODEOE($_IJ8tf->parts[$_Q6i6i], $_Qfo8t);
                  }

               }

             }
           }

           $_IiJit["body"] = "";
           if( isset($_IJ8tf->body) )
             $_IiJit["body"] = $_IJ8tf->body;

           $_IiJit["parts"] = $_Qfo8t;

           return $_IiJit;

        }
   }

   function _ODEOE($_JtI8j, &$_Qfo8t){

      $_JtQ6o = "";
      if(isset($_JtI8j->ctype_parameters) && isset($_JtI8j->ctype_parameters["charset"]))
        $_JtQ6o = $_JtI8j->ctype_parameters["charset"];

      $_JtI88 = "";

      // plain text part
      if( !isset($_JtI8j->disposition) && strtolower($_JtI8j->ctype_primary) == "text" && strtolower($_JtI8j->ctype_secondary) != "html")
      {
        // Apple mail adds a HTML part this is a attachment but not marked as attachment
        // when we have a plaintext part the other must be an attachment
        $_JtjQl = false;
        for($_Q6llo=0; $_Q6llo<count($_Qfo8t) && !$_JtjQl; $_Q6llo++){
          $_JtjQl = isset($_Qfo8t[$_Q6llo]['plaintext']) && $_Qfo8t[$_Q6llo]['plaintext'];
          if($_JtjQl){
            $_JtI8j->disposition = "attachment";
            $_JtI8j->d_parameters["filename"] = "nonamedfile.txt";
            break;
          }
        }
        if(!$_JtjQl){
          $_Qfo8t[] = array('plaintext' => true, 'html' => false,  'charset' => $_JtQ6o, 'body' => $_JtI8j->body);
          return true;
        }
      }

      // plain html part
      if( (!isset($_JtI8j->disposition) || $_JtI8j->disposition == 'inline' /* for multipart/mixed */ ) && strtolower($_JtI8j->ctype_primary) == "text" && strtolower($_JtI8j->ctype_secondary) == "html")
      {
        // Apple mail adds a HTML part this is a attachment but not marked as attachment
        // when we have a HTML part the other must be an attachment
        $_JtjQl = false;
        for($_Q6llo=0; $_Q6llo<count($_Qfo8t) && !$_JtjQl; $_Q6llo++){
          $_JtjQl = isset($_Qfo8t[$_Q6llo]['html']) && $_Qfo8t[$_Q6llo]['html'];
          if($_JtjQl && !isset($_JtI8j->disposition) ){
            $_JtI8j->disposition = "attachment";
            $_JtI8j->d_parameters["filename"] = "nonamedfile.html";
            break;
          }
        }

        if(!$_JtjQl){
          $_Qfo8t[] = array('plaintext' => false, 'html' => true, 'charset' => $_JtQ6o, 'body' => $_JtI8j->body);
          return true;
        }
      }


      // web.de signed as attachment but is inline
      if(isset($_JtI8j->disposition) && strtolower($_JtI8j->disposition) == "attachment" && isset($_JtI8j->d_parameters))
      {

         $_jtfJI = "";
         if(isset($_JtI8j->d_parameters["contentid"]))
           $_jtfJI = $_JtI8j->d_parameters["contentid"]["value"];
         if($_jtfJI != "")
           $_JtI8j->disposition = "inline";
      }

      if(isset($_JtI8j->disposition) && isset($_JtI8j->d_parameters) && strtolower($_JtI8j->disposition) == "attachment")
      {
              if(isset($_JtI8j->d_parameters["filename"]))
                $_jt8LJ = $_JtI8j->d_parameters["filename"];
                else
                $_jt8LJ = "nonamedfile";

              if(IsUtf8String($_jt8LJ))
                $_jt8LJ = utf8_decode($_jt8LJ);

              $_jt8LJ = _OBRJD($this->AttachmentsPath, $_jt8LJ);

              if($this->AttachmentsPath != "") {
                $_QCioi = @fopen (_OBLDR($this->AttachmentsPath) . $_jt8LJ, 'wb');
                if($_QCioi){
                  @fwrite ($_QCioi, $_JtI8j->body);
                  @fclose($_QCioi);
                  $_Qfo8t[] = array("attachment" => $_jt8LJ);
                } else{
                  // can't save it, error handling?
                  return false;
                }
              }
              return true;
      }

      if(isset($_JtI8j->disposition) && isset($_JtI8j->d_parameters) && strtolower($_JtI8j->disposition) == "inline")
      {

              if(isset($_JtI8j->d_parameters["filename"]))
                $_jt8LJ = $_JtI8j->d_parameters["filename"];
                else
                $_jt8LJ = "nonamedimage";
              if(IsUtf8String($_jt8LJ) || (isset( $_JtI8j->d_parameters["filename-charset"] ) && strtolower($_JtI8j->d_parameters["filename-charset"]) == "utf-8") )
                $_jt8LJ = utf8_decode($_jt8LJ);

              $_jtfJI = "";
              if(isset($_JtI8j->d_parameters["contentid"]))
                $_jtfJI = $_JtI8j->d_parameters["contentid"]["value"];

              $_jt8LJ = _OBRJD($this->InlineImagesPath, $_jt8LJ);

              if($this->InlineImagesPath != "") {
                $_QCioi = @fopen (_OBLDR($this->InlineImagesPath) . $_jt8LJ, 'wb');
                if($_QCioi){
                  @fwrite ($_QCioi, $_JtI8j->body);
                  @fclose($_QCioi);
                  $_Qfo8t[] = array("inlineimage" => $_jt8LJ, "cid" => $_jtfJI);
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

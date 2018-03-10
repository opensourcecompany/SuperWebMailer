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

  class _OL0A1 {

   // @access public

   // @access private
   var $_IJj0C = array();
   var $_IJj6i = array();
   var $_IJjoL = array();
   var $_IJjCL = array();


   // constructor
   function __construct($_IJjll) {
     $this->_OL1AL($_IJjll."bouncer.php");
   }

   function _OL0A1($_IJjll) {
     self::__construct($_IJjll);
   }

   function _OL1LO($_IJJfQ, $_IJJLI, &$_IJJLJ, &$_IJ6Cf) {

     $_IJfC0 = false;
     $_IJ80Q = new Mail_mimeDecode($_IJJfQ);

     // only header decode
     $_QffOf["decode_headers"] = true;
     $_IJ8tf = $_IJ80Q->decode($_QffOf);
     if(IsPEARError($_IJ8tf)) {
       unset($_IJ80Q);
       return false;
     }

     $_IJJLJ = "";
     if(isset($_IJ8tf->headers["subject"])) {
        $_IJJLJ = $_IJ8tf->headers["subject"];
        if(!is_string($_IJJLJ))
          $_IJJLJ = "";
        if (IsUtf8String($_IJJLJ))
          $_IJJLJ = utf8_decode($_IJJLJ); // convert to iso-8859-1, looses unicode chars
     }

     if(!isset($_IJ8tf->headers["from"])) {
       unset($_IJ80Q);
       return false;
     }
     $_IJ8oI = new Mail_RFC822();
     $From = $_IJ8tf->headers["from"];
     if (IsUtf8String($From))
        $From = utf8_decode($From);  // convert to iso-8859-1, looses unicode chars
     $_IJO8j = $_IJ8oI->parseAddressList($From, null, null, false); // no ASCII check

     if ( !(IsPEARError($_IJO8j)) ) {
       if(isset($_IJO8j[0]->personal))
         $_IJo16 = $_IJO8j[0]->personal;
         else
         $_IJo16 = "";
       if(isset($_IJO8j[0]->mailbox) && isset($_IJO8j[0]->host))
          $_IJoII = $_IJO8j[0]->mailbox."@".$_IJO8j[0]->host;
          else
          $_IJoII = "";
       $_IJo16 = str_replace('"', '', $_IJo16);
     } else {
       $_IJo16 = "";
       $_IJoII = "";
     }

     if ($this->_OLQO0($_IJo16, $_IJoII) && $this->_OLO1A($_IJJLJ) ) {

       // decode all
       $_QffOf["decode_headers"] = true;
       $_QffOf["include_bodies"] = true;
       $_QffOf["decode_bodies"] = true;
       unset($_IJ8tf);

       $_IJ8tf = $_IJ80Q->decode($_QffOf);
       if(IsPEARError($_IJ8tf)) {
         unset($_IJ80Q);
         return false;
       }

       $_QJCJi = "";
       if( isset($_IJ8tf->parts) ) {
         for($_Qf0Ct=0; $_Qf0Ct<min(count($_IJ8tf->parts), 10); $_Qf0Ct++) {

           if(isset($_IJojl))
            unset($_IJojl);
           if(isset($_IJ8tf->parts[$_Qf0Ct]->disposition))
             $_IJojl=$_IJ8tf->parts[$_Qf0Ct]->disposition;

           if(isset($_IJC0L))
             unset($_IJC0L);
           if(isset($_IJ8tf->parts[$_Qf0Ct]->headers["content-type"]))
             $_IJC0L = $_IJ8tf->parts[$_Qf0Ct]->headers["content-type"];

           if(isset($_IJCt0))
             unset($_IJCt0);
           if(isset($_IJ8tf->parts[$_Qf0Ct]->headers['content-description']))
              $_IJCt0 = $_IJ8tf->parts[$_Qf0Ct]->headers['content-description'];

           //Mail_mimeDecode doesn't convert it as attachment, we do it here
           if(isset($_IJC0L) && stripos($_IJC0L, 'delivery-status') !== false)
             $_IJojl="attachment";
           if ( isset($_IJCt0) && (stripos($_IJCt0, 'Delivery error report' ) !== false || stripos($_IJCt0, 'Delivery report.txt' ) !== false  || stripos($_IJCt0, 'Delivery report' ) !== false ) )
             $_IJojl="attachment";

           if ( !isset($_IJojl) || $_IJojl != "attachment" ) continue; // check only attachments here

           if ( isset($_IJC0L) && stripos($_IJC0L, 'delivery-status') !== false && isset($_IJ8tf->parts[$_Qf0Ct]->body) )
             $_QJCJi = $_IJ8tf->parts[$_Qf0Ct]->body;
             else
             if ( isset($_IJCt0) && (stripos($_IJCt0, 'Delivery error report' ) !== false || stripos($_IJCt0, 'Delivery report.txt' ) !== false  || stripos($_IJCt0, 'Delivery report' ) !== false ) && isset($_IJ8tf->parts[$_Qf0Ct]->body) )
               $_QJCJi = $_IJ8tf->parts[$_Qf0Ct]->body;
               else
               continue;

             if( $_QJCJi != "" && isset($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters) && isset($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters["charset"]) && strtolower($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters["charset"]) == "utf-8" ) {
               $_QllO8 = utf8_decode($_QJCJi);
               if($_QllO8 != "")
                 $_QJCJi = $_QllO8;
             }
         } # for j

         // text check
         if ($_QJCJi == "" )
           for($_Qf0Ct=0; $_Qf0Ct<min(count($_IJ8tf->parts), 10); $_Qf0Ct++) {

              if ( isset($_IJ8tf->parts[$_Qf0Ct]->disposition) ) continue; // check only text here no attachments
              if ( $_IJ8tf->parts[$_Qf0Ct]->ctype_primary != "text" && $_IJ8tf->parts[$_Qf0Ct]->ctype_primary != "multipart" ) continue; // check only text here no attachments

              $charset = "";
              if ($_IJ8tf->parts[$_Qf0Ct]->ctype_primary == "multipart" && is_array($_IJ8tf->parts[$_Qf0Ct]->parts)) {
                for($_I1i8O=0; $_I1i8O<min($_IJ8tf->parts[$_Qf0Ct]->parts, 10); $_I1i8O++) {
                 if(!isset($_IJ8tf->parts[$_Qf0Ct]->parts[$_I1i8O])) break;
                 if($_IJ8tf->parts[$_Qf0Ct]->parts[$_I1i8O]->ctype_primary == "text") {
                   $_QJCJi = trim($_IJ8tf->parts[$_Qf0Ct]->parts[$_I1i8O]->body);
                   if(isset($_IJ8tf->parts[$_Qf0Ct]->parts[$_I1i8O]->ctype_parameters))
                     $charset = $_IJ8tf->parts[$_Qf0Ct]->parts[$_I1i8O]->ctype_parameters["charset"];
                   break;
                 }
                }
              } else {
                $_QJCJi = trim($_IJ8tf->parts[$_Qf0Ct]->body);
                if( isset($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters) && isset($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters["charset"]) )
                  $charset = $_IJ8tf->parts[$_Qf0Ct]->ctype_parameters["charset"];
              }


              if( strtolower($charset) == "utf-8" ) {
                $_QllO8 = utf8_decode($_QJCJi);
                if($_QllO8 != "")
                  $_QJCJi = $_QllO8;
              }

              if($_QJCJi != "")
                break;
           }

         if($_QJCJi == "")
          for($_Qf0Ct=0; $_Qf0Ct<min(count($_IJ8tf->parts), 10); $_Qf0Ct++) {

            if ( !isset($_IJ8tf->parts[$_Qf0Ct]->disposition) || $_IJ8tf->parts[$_Qf0Ct]->disposition != "attachment" ) continue; // check only attachments here

            if ( isset($_IJ8tf->parts[$_Qf0Ct]->headers["content-type"]) && stripos($_IJ8tf->parts[$_Qf0Ct]->headers["content-type"], 'text/plain') !== false )
              $_QJCJi = $_IJ8tf->parts[$_Qf0Ct]->body;
              else
                continue;

              if( $_QJCJi != "" && isset($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters) && isset($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters["charset"]) && strtolower($_IJ8tf->parts[$_Qf0Ct]->ctype_parameters["charset"]) == "utf-8" ) {
                $_QllO8 = utf8_decode($_QJCJi);
                if($_QllO8 != "")
                  $_QJCJi = $_QllO8;
              }
          } # for j

       } # if( isset($_IJ8tf->part) )

       if( (!isset($_IJ8tf->parts) || $_QJCJi == "") && isset($_IJ8tf->body) ) {
          $_QJCJi = $_IJ8tf->body;

          if( isset($_IJ8tf->headers) && isset($_IJ8tf->headers["content-type"]) && stripos($_IJ8tf->headers["content-type"], "utf-8") !== false ) {
            $_QllO8 = utf8_decode($_QJCJi);
            if($_QllO8 != "")
              $_QJCJi = $_QllO8;
          }

       } else {
          if ( $_QJCJi != "" && (isset($_IJ8tf->body)) && ($_IJ8tf->body != 'This is a multi-part message in MIME format.'."\r\n") )
             $_QJCJi = $_IJ8tf->body.$_QJCJi;
       }


       if ($_QJCJi != "" && $this->_OLOJB($_QJCJi) )
         {
          if (!$this->_OLL1E($_QJCJi))
          {
            $_IJfC0 = true;
            if(strpos($_QJCJi, "\r\n") !== false)
              $_IJi8C = explode("\r\n", $_QJCJi);
              else
              if(strpos($_QJCJi, "\n") !== false)
                 $_IJi8C = explode("\n", $_QJCJi);
              else
              if(strpos($_QJCJi, "\r") !== false)
                 $_IJi8C = explode("\r", $_QJCJi);
                 else
                 $_IJi8C[] = $_QJCJi;
            $this->_OLLPB($_IJJLI, $_IJi8C, $_IJfC0, $_IJ6Cf);
          }
         }


     } # if ($this->_OLQO0($_IJo16, $_IJoII) && $this->_OLO1A($_IJJLJ) )
     unset($_IJ80Q);
     return $_IJfC0;
   }


   // @access private
   function _OL1AL($_QCttf){
     $_QfC8t = file($_QCttf);
     for($_Q6llo=0; $_Q6llo<count($_QfC8t); $_Q6llo++) {
       $_QJCJi = $_QfC8t[$_Q6llo];
       if(substr($_QJCJi, 0, 1) == ";") continue;

       if(strpos($_QJCJi, "[") !== false && strpos($_QJCJi, "]") !== false ) {
         $_IJLIl = substr($_QJCJi, 1, strpos($_QJCJi, "]") - 1);
         for($_Qf0Ct=$_Q6llo + 1; $_Qf0Ct < count($_QfC8t); $_Qf0Ct++) {
           $_QJCJi = $_QfC8t[$_Qf0Ct];
           if(strpos($_QJCJi, "[") !== false && strpos($_QJCJi, "]") !== false) {
             break;
           }
           if(substr($_QJCJi, 0, 1) == ";") continue;
           if($_IJLIl == "FromAddress")
             $this->_IJj0C[] = trim(substr($_QJCJi, strpos($_QJCJi, "=") + 1));
           if($_IJLIl == "Subject")
             $this->_IJj6i[] = trim(substr($_QJCJi, strpos($_QJCJi, "=") + 1));
           if($_IJLIl == "MailBody")
             $this->_IJjoL[] = trim(substr($_QJCJi, strpos($_QJCJi, "=") + 1));
           if($_IJLIl == "TextNotInMailBody")
             $this->_IJjCL[] = trim(substr($_QJCJi, strpos($_QJCJi, "=") + 1));
         }
         $_Q6llo = $_Qf0Ct - 1;
       }
     }
   }

   // @access private
   function _OLQO0($_IJLt1, $_IJlJ6){
     for ($_Q6llo=0; $_Q6llo<count($this->_IJj0C); $_Q6llo++) {
        if( stripos($_IJLt1, $this->_IJj0C[$_Q6llo] ) !== false ) {
           return true;
        }

        if( stripos($_IJlJ6, $this->_IJj0C[$_Q6llo] ) !== false ) {
           return true;
        }
     }
     return false;
   }

   // @access private
   function _OLO1A($_I6016){
     for ($_Q6llo=0; $_Q6llo<count($this->_IJj6i); $_Q6llo++) {
        if( stripos($_I6016, $this->_IJj6i[$_Q6llo] ) !== false  ) {
           return true;
        }
     }
     return false;
   }

   // @access private
   function _OLOJB($_I606j) {
     for ($_Q6llo=0; $_Q6llo<count($this->_IJjoL); $_Q6llo++) {
        $_QJCJi = $this->_IJjoL[$_Q6llo];

        if (stripos($_QJCJi, "*") === false)
          if (stripos($_I606j, $_QJCJi) !== false)
            return true;

        if (stripos($_QJCJi, "*") === false) continue;

        $_IJQJ8 = substr($_QJCJi, stripos($_QJCJi, "*") + 1); // dahinter
        $_QJCJi = substr($_QJCJi, 0, stripos($_QJCJi, "*") - 1); // davor
        $_Q66jQ = $_I606j;

        if (stripos($_Q66jQ, $_QJCJi) !== false) {
           $_QJCJi = substr($_Q66jQ, stripos($_Q66jQ, $_QJCJi));
           $_Q6i6i = stripos($_QJCJi, $_IJQJ8);
           if ( ($_IJQJ8 == '') || ($_Q6i6i !== false && $_Q6i6i > 0) )  // nicht 0!!
              return true;
         }
     }
     return false;
   }

   // @access private
   function _OLL1E($_I606j) {
     for ($_Q6llo=0; $_Q6llo<count($this->_IJjCL); $_Q6llo++) {
        $_QJCJi = $this->_IJjCL[$_Q6llo];

        if (stripos($_QJCJi, "*") === false)
          if (stripos($_I606j, $_QJCJi) !== false)
            return true;

        if (stripos($_QJCJi, "*") === false) continue;

        $_IJQJ8 = substr($_QJCJi, stripos($_QJCJi, "*") + 1); // dahinter
        $_QJCJi = substr($_QJCJi, 0, stripos($_QJCJi, "*") - 1); // davor
        $_Q66jQ = $_I606j;

        if (stripos($_Q66jQ, $_QJCJi) !== false) {
           $_QJCJi = substr($_Q66jQ, stripos($_Q66jQ, $_QJCJi));
           $_Q6i6i = stripos($_QJCJi, $_IJQJ8);
           if ( ($_IJQJ8 == '') || ($_Q6i6i !== false && $_Q6i6i > 0) )  // nicht 0!!
              return true;
         }
     }
     return false;
   }

   function _OLLPB($_I611i, $_IJi8C, $_IJfC0, &$_IJ6Cf) {

     $_I61QL = false;

     // Suche nach To:
     for ($_Q6llo = 0; $_Q6llo< count($_IJi8C); $_Q6llo++)
     {

       if ( (stripos($_IJi8C[$_Q6llo], 'To:') !== false) && (stripos($_IJi8C[$_Q6llo], 'Reply-To:') === false) )
       {
         $_QJCJi = trim($_IJi8C[$_Q6llo]);
         $_QJCJi = trim(substr($_QJCJi, stripos($_QJCJi, 'To:') + 3));
         if (strpos($_QJCJi, ' ') !== false)
           {
            $_Q66jQ = substr($_QJCJi, 0, strpos($_QJCJi, ' '));
            if (strpos($_Q66jQ, '@') !== false)
               $_QJCJi = $_Q66jQ;
               else
               {
                 $_QJCJi = substr($_QJCJi, strpos($_QJCJi, ' ') + 1);
                 while ( (strpos($_QJCJi, ' ') > 0) && (strlen($_QJCJi) > 1) )
                    $_QJCJi = substr($_QJCJi, strpos($_QJCJi, ' ') + 1);
               }
           }
         if (strpos($_QJCJi, '@') !== false)
           {

            if (strpos($_QJCJi, '<') !== false)
            {
              $_QJCJi = substr($_QJCJi, strpos($_QJCJi, '<') + 1);
              $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, '>'));
              if (strpos($_QJCJi, ' ') !== false)
                 $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ' '));
              $_QJCJi = trim($_QJCJi);
            }

            $_QJCJi = str_replace('<', '', $_QJCJi);
            $_QJCJi = str_replace('>', '', $_QJCJi);

            if ($_QJCJi != '')
            {
              if ( $_QJCJi{strlen($_QJCJi) - 1} == ':' || $_QJCJi{strlen($_QJCJi) - 1} == ';' )
                 $_QJCJi = trim(substr($_QJCJi, 0, strlen($_QJCJi) - 1));

              if ( ($_I611i != '') && (stripos($_QJCJi, $_I611i) !== false) ) continue; // nichts gefunden
              if (! _OPAOJ($_QJCJi) ) continue;
              $_QJCJi = strtolower($_QJCJi);

              if (! in_array($_QJCJi, $_IJ6Cf) )
                 $_IJ6Cf[] = $_QJCJi;

              $_I61QL = true;
    //      mehr davon suchen    break; // koennen wir aufhoeren ist gefunden
            }
           }
       }
     } # for ($_Q6llo = 0; $_Q6llo< count($_IJi8C); $_Q6llo++)

     if (!$_I61QL)
       for ($_Q6llo = 0; $_Q6llo < count($_IJi8C); $_Q6llo++)
       {
         if (stripos($_IJi8C[$_Q6llo], 'From:') !== false) continue;
         if (stripos($_IJi8C[$_Q6llo], 'Reply-To:') !== false) continue;
         if (stripos($_IJi8C[$_Q6llo], 'Return-path:') !== false) continue;

         if ( stripos($_IJi8C[$_Q6llo], '@') !== false )
          {
            $_QJCJi = $_IJi8C[$_Q6llo];
            if ( (strpos($_QJCJi, '<') !== false) && (strpos($_QJCJi, '>') > strpos($_QJCJi, '<')) )
            {
              $_QJCJi = substr($_QJCJi, strpos($_QJCJi, '<') + 1);
              $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, '>'));
              if (strpos($_QJCJi, ' ') !== false)
                 $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ' '));
              $_QJCJi = trim($_QJCJi);
              if ($_QJCJi != '')
              {
                if ( ($_I611i != '') && (stripos($_QJCJi, $_I611i) !== false) ) continue; // nichts gefunden
                if (! _OPAOJ($_QJCJi) ) continue;
                $_QJCJi = strtolower($_QJCJi);

                if (! in_array($_QJCJi, $_IJ6Cf) )
                   $_IJ6Cf[] = $_QJCJi;
                $_I61QL = true;
    //            break; // koennen wir aufhoeren ist gefunden
              }
              else
               $_QJCJi = $_IJi8C[$_Q6llo];
            }

            $_QJCJi = trim($_QJCJi);
            if ($_QJCJi{strlen($_QJCJi) - 1} == ':' || $_QJCJi{strlen($_QJCJi) - 1} == ';' )
               $_QJCJi = substr($_QJCJi, 0, strlen($_QJCJi) - 1);
            $_QJCJi = trim($_QJCJi);
            if (strpos($_QJCJi, ';') !== false)
              {
                $_IJQJ8 = substr($_QJCJi, 0, strpos($_QJCJi, ';'));
                if (strpos($_IJQJ8, '@') !== false)
                   $_QJCJi = trim(substr($_QJCJi, 0, strpos($_QJCJi, ';')));
                   else
                   $_QJCJi = trim(substr($_QJCJi, strpos($_QJCJi, ';') + 1));
              }
            $_Q66jQ = substr($_QJCJi, strpos($_QJCJi, '@') + 1);
            $_Q66jQ = substr($_Q66jQ, strpos($_Q66jQ, '.') + 1);
            if (strlen($_Q66jQ) >= 2)
              {

                if (strpos($_QJCJi, '<') !== false)
                {
                  $_QJCJi = substr($_QJCJi, strpos($_QJCJi, '<') + 1);
                  $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, '>'));
                  if (strpos($_QJCJi, ' ') !== false)
                     $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ' ') );
                  $_QJCJi = trim($_QJCJi);
                }

                if (strpos($_QJCJi, ' ') !== false)
                   $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ' ') );

                if ( ($_QJCJi != '') && (strpos($_QJCJi, '@') !== false) )
                {
                  if ( ($_I611i != '') && (stripos($_QJCJi, $_I611i) !== false) ) continue; // nichts gefunden
                  if (! _OPAOJ($_QJCJi) ) continue;
                  $_QJCJi = strtolower($_QJCJi);

                  if (! in_array($_QJCJi, $_IJ6Cf) )
                     $_IJ6Cf[] = $_QJCJi;
                  $_I61QL = true;
    //              break; // koennen wir aufhoeren ist gefunden
                }
              }
          }
       }

   } # for ($_Q6llo = 0; $_Q6llo < count($_IJi8C); $_Q6llo++)

  } # class

?>

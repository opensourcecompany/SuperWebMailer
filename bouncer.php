<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

  class _L10QP {

   // @access public

   // @access private
   var $_IL1ff = array();
   var $_ILQ01 = array();
   var $_ILQjj = array();
   var $_ILQtO = array();


   // constructor
   function __construct($_ILI1C) {
     $this->_L11PD($_ILI1C."bouncer.php");
   }

   function _L10QP($_ILI1C) {
     self::__construct($_ILI1C);
   }

   function _L10FC($_ILItl, $_ILjJ0, &$_ILjOj, &$_ILJIO) {

     $_ILJjL = false;
     $_ILJt8 = new Mail_mimeDecode($_ILItl);

     // only header decode
     $_I08CQ["decode_headers"] = true;
     $_IL6J6 = $_ILJt8->decode($_I08CQ);
     if(IsPEARError($_IL6J6)) {
       unset($_ILJt8);
       return false;
     }

     $_ILjOj = "";
     if(isset($_IL6J6->headers["subject"])) {
        $_ILjOj = $_IL6J6->headers["subject"];
        if(!is_string($_ILjOj))
          $_ILjOj = "";
        if (IsUtf8String($_ILjOj))
          $_ILjOj = utf8_decode($_ILjOj); // convert to iso-8859-1, looses unicode chars
     }

     if(!isset($_IL6J6->headers["from"])) {
       unset($_ILJt8);
       return false;
     }
     $_IL6Jt = new Mail_RFC822();
     $From = $_IL6J6->headers["from"];
     if (IsUtf8String($From))
        $From = utf8_decode($From);  // convert to iso-8859-1, looses unicode chars
     $_ILf66 = $_IL6Jt->parseAddressList($From, null, null, false); // no ASCII check

     if ( !(IsPEARError($_ILf66)) ) {
       if(isset($_ILf66[0]->personal))
         $_ILfoj = $_ILf66[0]->personal;
         else
         $_ILfoj = "";
       if(isset($_ILf66[0]->mailbox) && isset($_ILf66[0]->host))
          $_IL8oI = $_ILf66[0]->mailbox."@".$_ILf66[0]->host;
          else
          $_IL8oI = "";
       $_ILfoj = str_replace('"', '', $_ILfoj);
     } else {
       $_ILfoj = "";
       $_IL8oI = "";
     }

     if ($this->_L1QQ0($_ILfoj, $_IL8oI) && $this->_L1QP0($_ILjOj) ) {

       // decode all
       $_I08CQ["decode_headers"] = true;
       $_I08CQ["include_bodies"] = true;
       $_I08CQ["decode_bodies"] = true;
       unset($_IL6J6);

       $_IL6J6 = $_ILJt8->decode($_I08CQ);
       if(IsPEARError($_IL6J6)) {
         unset($_ILJt8);
         return false;
       }

       $_QLJfI = "";
       if( isset($_IL6J6->parts) ) {
         for($_QliOt=0; $_QliOt<min(count($_IL6J6->parts), 10); $_QliOt++) {

           if(isset($_ILt60))
            unset($_ILt60);
           if(isset($_IL6J6->parts[$_QliOt]->disposition))
             $_ILt60=$_IL6J6->parts[$_QliOt]->disposition;

           if(isset($_ILOjj))
             unset($_ILOjj);
           if(isset($_IL6J6->parts[$_QliOt]->headers["content-type"]))
             $_ILOjj = $_IL6J6->parts[$_QliOt]->headers["content-type"];

           if(isset($_ILoIo))
             unset($_ILoIo);
           if(isset($_IL6J6->parts[$_QliOt]->headers['content-description']))
              $_ILoIo = $_IL6J6->parts[$_QliOt]->headers['content-description'];

           //Mail_mimeDecode doesn't convert it as attachment, we do it here
           if(isset($_ILOjj) && stripos($_ILOjj, 'delivery-status') !== false)
             $_ILt60="attachment";
           if ( isset($_ILoIo) && (stripos($_ILoIo, 'Delivery error report' ) !== false || stripos($_ILoIo, 'Delivery report.txt' ) !== false  || stripos($_ILoIo, 'Delivery report' ) !== false ) )
             $_ILt60="attachment";

           if ( !isset($_ILt60) || $_ILt60 != "attachment" ) continue; // check only attachments here

           if ( isset($_ILOjj) && stripos($_ILOjj, 'delivery-status') !== false && isset($_IL6J6->parts[$_QliOt]->body) )
             $_QLJfI = $_IL6J6->parts[$_QliOt]->body;
             else
             if ( isset($_ILoIo) && (stripos($_ILoIo, 'Delivery error report' ) !== false || stripos($_ILoIo, 'Delivery report.txt' ) !== false  || stripos($_ILoIo, 'Delivery report' ) !== false ) && isset($_IL6J6->parts[$_QliOt]->body) )
               $_QLJfI = $_IL6J6->parts[$_QliOt]->body;
               else
               continue;

             if( $_QLJfI != "" && isset($_IL6J6->parts[$_QliOt]->ctype_parameters) && isset($_IL6J6->parts[$_QliOt]->ctype_parameters["charset"]) && strtolower($_IL6J6->parts[$_QliOt]->ctype_parameters["charset"]) == "utf-8" ) {
               $_I016j = utf8_decode($_QLJfI);
               if($_I016j != "")
                 $_QLJfI = $_I016j;
             }
         } # for j

         // text check
         if ($_QLJfI == "" )
           for($_QliOt=0; $_QliOt<min(count($_IL6J6->parts), 10); $_QliOt++) {

              if ( isset($_IL6J6->parts[$_QliOt]->disposition) ) continue; // check only text here no attachments
              if ( $_IL6J6->parts[$_QliOt]->ctype_primary != "text" && $_IL6J6->parts[$_QliOt]->ctype_primary != "multipart" ) continue; // check only text here no attachments

              $charset = "";
              if ($_IL6J6->parts[$_QliOt]->ctype_primary == "multipart" && is_array($_IL6J6->parts[$_QliOt]->parts)) {
                for($_IOLil=0; $_IOLil<min($_IL6J6->parts[$_QliOt]->parts, 10); $_IOLil++) {
                 if(!isset($_IL6J6->parts[$_QliOt]->parts[$_IOLil])) break;
                 if($_IL6J6->parts[$_QliOt]->parts[$_IOLil]->ctype_primary == "text") {
                   $_QLJfI = trim($_IL6J6->parts[$_QliOt]->parts[$_IOLil]->body);
                   if(isset($_IL6J6->parts[$_QliOt]->parts[$_IOLil]->ctype_parameters))
                     $charset = $_IL6J6->parts[$_QliOt]->parts[$_IOLil]->ctype_parameters["charset"];
                   break;
                 }
                }
              } else {
                $_QLJfI = trim($_IL6J6->parts[$_QliOt]->body);
                if( isset($_IL6J6->parts[$_QliOt]->ctype_parameters) && isset($_IL6J6->parts[$_QliOt]->ctype_parameters["charset"]) )
                  $charset = $_IL6J6->parts[$_QliOt]->ctype_parameters["charset"];
              }


              if( strtolower($charset) == "utf-8" ) {
                $_I016j = utf8_decode($_QLJfI);
                if($_I016j != "")
                  $_QLJfI = $_I016j;
              }

              if($_QLJfI != "")
                break;
           }

         if($_QLJfI == "")
          for($_QliOt=0; $_QliOt<min(count($_IL6J6->parts), 10); $_QliOt++) {

            if ( !isset($_IL6J6->parts[$_QliOt]->disposition) || $_IL6J6->parts[$_QliOt]->disposition != "attachment" ) continue; // check only attachments here

            if ( isset($_IL6J6->parts[$_QliOt]->headers["content-type"]) && stripos($_IL6J6->parts[$_QliOt]->headers["content-type"], 'text/plain') !== false )
              $_QLJfI = $_IL6J6->parts[$_QliOt]->body;
              else
                continue;

              if( $_QLJfI != "" && isset($_IL6J6->parts[$_QliOt]->ctype_parameters) && isset($_IL6J6->parts[$_QliOt]->ctype_parameters["charset"]) && strtolower($_IL6J6->parts[$_QliOt]->ctype_parameters["charset"]) == "utf-8" ) {
                $_I016j = utf8_decode($_QLJfI);
                if($_I016j != "")
                  $_QLJfI = $_I016j;
              }
          } # for j

       } # if( isset($_IL6J6->part) )

       if( (!isset($_IL6J6->parts) || $_QLJfI == "") && isset($_IL6J6->body) ) {
          $_QLJfI = $_IL6J6->body;

          if( isset($_IL6J6->headers) && isset($_IL6J6->headers["content-type"]) && stripos($_IL6J6->headers["content-type"], "utf-8") !== false ) {
            $_I016j = utf8_decode($_QLJfI);
            if($_I016j != "")
              $_QLJfI = $_I016j;
          }

       } else {
          if ( $_QLJfI != "" && (isset($_IL6J6->body)) && ($_IL6J6->body != 'This is a multi-part message in MIME format.'."\r\n") )
             $_QLJfI = $_IL6J6->body.$_QLJfI;
       }


       if ($_QLJfI != "" && $this->_L1OJA($_QLJfI) )
         {
          if (!$this->_L1OD8($_QLJfI))
          {
            $_ILJjL = true;
            
            // find by ListId
            $this->_L1LE6($_ILItl, $rid, $ListId);
            if(!empty($rid) && !empty($ListId)){
              $_ILotJ = $this->_L1JJC($ListId);
              if(!empty($_ILotJ)){
                $_ILJIO[] = $_ILotJ;
                return $_ILJjL;
              }  
            }
            
            // parse email
            
            if(strpos($_QLJfI, "\r\n") !== false)
              $_ILC8O = explode("\r\n", $_QLJfI);
              else
              if(strpos($_QLJfI, "\n") !== false)
                 $_ILC8O = explode("\n", $_QLJfI);
              else
              if(strpos($_QLJfI, "\r") !== false)
                 $_ILC8O = explode("\r", $_QLJfI);
                 else
                 $_ILC8O[] = $_QLJfI;
            $this->_L1L8E($_ILjJ0, $_ILC8O, $_ILJjL, $_ILJIO);
          }
         }


     } # if ($this->_L1QQ0($_ILfoj, $_IL8oI) && $this->_L1QP0($_ILjOj) )
     unset($_ILJt8);
     return $_ILJjL;
   }


   // @access private
   function _L11PD($_IJOfj){
     $_I0lji = file($_IJOfj);
     for($_Qli6J=0; $_Qli6J<count($_I0lji); $_Qli6J++) {
       $_QLJfI = $_I0lji[$_Qli6J];
       if(substr($_QLJfI, 0, 1) == ";") continue;

       if(strpos($_QLJfI, "[") !== false && strpos($_QLJfI, "]") !== false ) {
         $_ILi1t = substr($_QLJfI, 1, strpos($_QLJfI, "]") - 1);
         for($_QliOt=$_Qli6J + 1; $_QliOt < count($_I0lji); $_QliOt++) {
           $_QLJfI = $_I0lji[$_QliOt];
           if(strpos($_QLJfI, "[") !== false && strpos($_QLJfI, "]") !== false) {
             break;
           }
           if(substr($_QLJfI, 0, 1) == ";") continue;
           if($_ILi1t == "FromAddress")
             $this->_IL1ff[] = trim(substr($_QLJfI, strpos($_QLJfI, "=") + 1));
           if($_ILi1t == "Subject")
             $this->_ILQ01[] = trim(substr($_QLJfI, strpos($_QLJfI, "=") + 1));
           if($_ILi1t == "MailBody")
             $this->_ILQjj[] = trim(substr($_QLJfI, strpos($_QLJfI, "=") + 1));
           if($_ILi1t == "TextNotInMailBody")
             $this->_ILQtO[] = trim(substr($_QLJfI, strpos($_QLJfI, "=") + 1));
         }
         $_Qli6J = $_QliOt - 1;
       }
     }
   }

   // @access private
   function _L1QQ0($_I6C8f, $_ILotJ){
     for ($_Qli6J=0; $_Qli6J<count($this->_IL1ff); $_Qli6J++) {
        if( stripos($_I6C8f, $this->_IL1ff[$_Qli6J] ) !== false ) {
           return true;
        }

        if( stripos($_ILotJ, $this->_IL1ff[$_Qli6J] ) !== false ) {
           return true;
        }
     }
     return false;
   }

   // @access private
   function _L1QP0($_ILi8o){
     for ($_Qli6J=0; $_Qli6J<count($this->_ILQ01); $_Qli6J++) {
        if( stripos($_ILi8o, $this->_ILQ01[$_Qli6J] ) !== false  ) {
           return true;
        }
     }
     return false;
   }

   // @access private
   function _L1OJA($_ILL61) {
     for ($_Qli6J=0; $_Qli6J<count($this->_ILQjj); $_Qli6J++) {
        $_QLJfI = $this->_ILQjj[$_Qli6J];

        if (stripos($_QLJfI, "*") === false)
          if (stripos($_ILL61, $_QLJfI) !== false)
            return true;

        if (stripos($_QLJfI, "*") === false) continue;

        $_IilfC = substr($_QLJfI, stripos($_QLJfI, "*") + 1); // dahinter
        $_QLJfI = substr($_QLJfI, 0, stripos($_QLJfI, "*") - 1); // davor
        $_Ql0fO = $_ILL61;

        if (stripos($_Ql0fO, $_QLJfI) !== false) {
           $_QLJfI = substr($_Ql0fO, stripos($_Ql0fO, $_QLJfI));
           $_QlOjt = stripos($_QLJfI, $_IilfC);
           if ( ($_IilfC == '') || ($_QlOjt !== false && $_QlOjt > 0) )  // nicht 0!!
              return true;
         }
     }
     return false;
   }

   // @access private
   function _L1OD8($_ILL61) {
     for ($_Qli6J=0; $_Qli6J<count($this->_ILQtO); $_Qli6J++) {
        $_QLJfI = $this->_ILQtO[$_Qli6J];

        if (stripos($_QLJfI, "*") === false)
          if (stripos($_ILL61, $_QLJfI) !== false)
            return true;

        if (stripos($_QLJfI, "*") === false) continue;

        $_IilfC = substr($_QLJfI, stripos($_QLJfI, "*") + 1); // dahinter
        $_QLJfI = substr($_QLJfI, 0, stripos($_QLJfI, "*") - 1); // davor
        $_Ql0fO = $_ILL61;

        if (stripos($_Ql0fO, $_QLJfI) !== false) {
           $_QLJfI = substr($_Ql0fO, stripos($_Ql0fO, $_QLJfI));
           $_QlOjt = stripos($_QLJfI, $_IilfC);
           if ( ($_IilfC == '') || ($_QlOjt !== false && $_QlOjt > 0) )  // nicht 0!!
              return true;
         }
     }
     return false;
   }

   // @access private
   function _L1L8E($_ILLiQ, $_ILC8O, $_ILJjL, &$_ILJIO) {

     $_ILLl6 = false;

     // Suche nach To:
     for ($_Qli6J = 0; $_Qli6J< count($_ILC8O); $_Qli6J++)
     {

       if ( (stripos($_ILC8O[$_Qli6J], 'To:') !== false) && (stripos($_ILC8O[$_Qli6J], 'Reply-To:') === false) )
       {
         $_QLJfI = trim($_ILC8O[$_Qli6J]);
         $_QLJfI = trim(substr($_QLJfI, stripos($_QLJfI, 'To:') + 3));
         if (strpos($_QLJfI, ' ') !== false)
           {
            $_Ql0fO = substr($_QLJfI, 0, strpos($_QLJfI, ' '));
            if (strpos($_Ql0fO, '@') !== false)
               $_QLJfI = $_Ql0fO;
               else
               {
                 $_QLJfI = substr($_QLJfI, strpos($_QLJfI, ' ') + 1);
                 while ( (strpos($_QLJfI, ' ') > 0) && (strlen($_QLJfI) > 1) )
                    $_QLJfI = substr($_QLJfI, strpos($_QLJfI, ' ') + 1);
               }
           }
         if (strpos($_QLJfI, '@') !== false)
           {

            if (strpos($_QLJfI, '<') !== false)
            {
              $_QLJfI = substr($_QLJfI, strpos($_QLJfI, '<') + 1);
              $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, '>'));
              if (strpos($_QLJfI, ' ') !== false)
                 $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ' '));
              $_QLJfI = trim($_QLJfI);
            }

            $_QLJfI = str_replace('<', '', $_QLJfI);
            $_QLJfI = str_replace('>', '', $_QLJfI);

            if ($_QLJfI != '')
            {
              if ( $_QLJfI[strlen($_QLJfI) - 1] == ':' || $_QLJfI[strlen($_QLJfI) - 1] == ';' )
                 $_QLJfI = trim(substr($_QLJfI, 0, strlen($_QLJfI) - 1));

              if ( ($_ILLiQ != '') && (stripos($_QLJfI, $_ILLiQ) !== false) ) continue; // nichts gefunden
              if (! _L8JLR($_QLJfI) ) continue;
              $_QLJfI = strtolower($_QLJfI);

              if (! in_array($_QLJfI, $_ILJIO) )
                 $_ILJIO[] = $_QLJfI;

              $_ILLl6 = true;
    //      mehr davon suchen    break; // koennen wir aufhoeren ist gefunden
            }
           }
       }
     } # for ($_Qli6J = 0; $_Qli6J< count($_ILC8O); $_Qli6J++)

     if (!$_ILLl6)
       for ($_Qli6J = 0; $_Qli6J < count($_ILC8O); $_Qli6J++)
       {
         if (stripos($_ILC8O[$_Qli6J], 'From:') !== false) continue;
         if (stripos($_ILC8O[$_Qli6J], 'Reply-To:') !== false) continue;
         if (stripos($_ILC8O[$_Qli6J], 'Return-path:') !== false) continue;

         if ( stripos($_ILC8O[$_Qli6J], '@') !== false )
          {
            $_QLJfI = $_ILC8O[$_Qli6J];
            if ( (strpos($_QLJfI, '<') !== false) && (strpos($_QLJfI, '>') > strpos($_QLJfI, '<')) )
            {
              $_QLJfI = substr($_QLJfI, strpos($_QLJfI, '<') + 1);
              $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, '>'));
              if (strpos($_QLJfI, ' ') !== false)
                 $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ' '));
              $_QLJfI = trim($_QLJfI);
              if ($_QLJfI != '')
              {
                if ( ($_ILLiQ != '') && (stripos($_QLJfI, $_ILLiQ) !== false) ) continue; // nichts gefunden
                if (! _L8JLR($_QLJfI) ) continue;
                $_QLJfI = strtolower($_QLJfI);

                if (! in_array($_QLJfI, $_ILJIO) )
                   $_ILJIO[] = $_QLJfI;
                $_ILLl6 = true;
    //            break; // koennen wir aufhoeren ist gefunden
              }
              else
               $_QLJfI = $_ILC8O[$_Qli6J];
            }

            $_QLJfI = trim($_QLJfI);
            if ($_QLJfI[strlen($_QLJfI) - 1] == ':' || $_QLJfI[strlen($_QLJfI) - 1] == ';' )
               $_QLJfI = substr($_QLJfI, 0, strlen($_QLJfI) - 1);
            $_QLJfI = trim($_QLJfI);
            if (strpos($_QLJfI, ';') !== false)
              {
                $_IilfC = substr($_QLJfI, 0, strpos($_QLJfI, ';'));
                if (strpos($_IilfC, '@') !== false)
                   $_QLJfI = trim(substr($_QLJfI, 0, strpos($_QLJfI, ';')));
                   else
                   $_QLJfI = trim(substr($_QLJfI, strpos($_QLJfI, ';') + 1));
              }
            $_Ql0fO = substr($_QLJfI, strpos($_QLJfI, '@') + 1);
            $_Ql0fO = substr($_Ql0fO, strpos($_Ql0fO, '.') + 1);
            if (strlen($_Ql0fO) >= 2)
              {

                if (strpos($_QLJfI, '<') !== false)
                {
                  $_QLJfI = substr($_QLJfI, strpos($_QLJfI, '<') + 1);
                  $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, '>'));
                  if (strpos($_QLJfI, ' ') !== false)
                     $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ' ') );
                  $_QLJfI = trim($_QLJfI);
                }

                if (strpos($_QLJfI, ' ') !== false)
                   $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ' ') );

                if ( ($_QLJfI != '') && (strpos($_QLJfI, '@') !== false) )
                {
                  if ( ($_ILLiQ != '') && (stripos($_QLJfI, $_ILLiQ) !== false) ) continue; // nichts gefunden
                  if (! _L8JLR($_QLJfI) ) continue;
                  $_QLJfI = strtolower($_QLJfI);

                  if (! in_array($_QLJfI, $_ILJIO) )
                     $_ILJIO[] = $_QLJfI;
                  $_ILLl6 = true;
    //              break; // koennen wir aufhoeren ist gefunden
                }
              }
          }
       }

   } # for ($_Qli6J = 0; $_Qli6J < count($_ILC8O); $_Qli6J++)


   // @access private
   function _L1LE6($_ILlOQ, &$rid, &$ListId){
      global $_Il06C, $_QLl1Q;

      $_ILlOQ = substr($_ILlOQ, strpos($_ILlOQ, $_QLl1Q . $_QLl1Q));
      
      $rid = "";
      $ListId = "";
      
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
  
   // @access private
   function _L1JJC($ListId){
     global $_QL88I, $_QLttI;
     
     $ListId = explode("-", $ListId);
     if(count($ListId) == 2){ //MailingListId-RecipientsId
       $ListId[0] = intval($ListId[0]);
       $ListId[1] = intval($ListId[1]);
       
       if($ListId[0] && $ListId[1]){
         
         $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE `id`=" . $ListId[0];
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if(!$_QL8i1)
           return "";
         if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
           mysql_free_result($_QL8i1);
           
           $_QLfol = "SELECT `u_EMail` FROM `$_QLO0f[MaillistTableName]` WHERE `id`=" . $ListId[1];
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if(!$_QL8i1)
             return "";
           if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
             mysql_free_result($_QL8i1);
             return $_QLO0f["u_EMail"];
           }  
         }
         mysql_free_result($_QL8i1);
         
         return "";
         
       }else
         return "";
       
     }else
       return "";
     
   }
  
  } # class

?>

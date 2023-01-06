<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

  // supported HTML Tags

  $_8CI1I = array("area", "base", "basefont", "br", "col", "frame", "hr", "img", "input", "isindex", "link", "meta", "param");
  $_8CIt8 = array("a", "abbr", "acronym", "address", "applet", "b", "bdo", "big", "blockquote", "body", "button", "caption", "center", "cite", "code",
                         "colgroup", "dd", "del", "dfn", "dir", "div", "dl", "dt", "em", "fieldset", "font", "form", "frameset", "h1", "h2", "h3", "h4", "h5", "h6",
                         "head", "html", "i", "iframe", "ins", "kbd", "label", "legend", "li", "map", "menu", "noframes", "noscript", "object", "ol", "optgroup", "option",
                         "p", "pre", "q", "s", "samp", "script", "select", "small", "span", "strike", "strong", "style", "sub", "sup", "table", "tbody", "td", "textarea",
                         "tfoot", "th", "thead", "title", "tr", "tt", "u", "ul", "var", "section", "article", "main", "aside", "header", "footer", "nav", "figure", "figcaption",
                         "template", "video", "audio", "track", "embed", "mark", "progress", "meter", "time", "ruby", "rt", "rp", "bdi", "wbr", "canvas", "datalist", "keygen", "output");
  $_8Cjjo = array();
  $_8CJIL = array();

  function _JJ6FC($_81fCl){
    global $_8CIt8, $_8CJIL;
    $_81fCl = strtolower($_81fCl);
    if(count($_8CJIL) == 0) $_8CJIL = array_flip($_8CIt8); // search is faster
    return isset($_8CJIL[$_81fCl]);
  }

  function _JJR1P($_81fCl){
    global $_8CI1I, $_8Cjjo;
    $_81fCl = strtolower($_81fCl);
    if(count($_8Cjjo) == 0) $_8Cjjo = array_flip($_8CI1I); // search is faster
    return isset($_8Cjjo[$_81fCl]);
  }

  function _JJREP($_6IoCL, &$_jLtli, $_8C6It = 0){
    global $_JQjQ6;
    $_jLtli = array();
    if(stripos($_6IoCL, $_JQjQ6 . '=') === false) return; // stripos is quicker than preg_match*
    preg_match_all('/(?<!_)'.preg_quote($_JQjQ6, '/').'=([\'"])?(.*?)\\1/is', $_6IoCL, $_66tJo, PREG_SET_ORDER);
    for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;

     $_I1OoI = explode(" ", $_66tJo[$_Qli6J][2]);
     for($_QliOt=0; $_QliOt<count($_I1OoI); $_QliOt++){
       if($_I1OoI[$_QliOt] == "") continue;
       if(!in_array($_I1OoI[$_QliOt], $_jLtli)){
         array_push($_jLtli, $_I1OoI[$_QliOt]);
         if($_8C6It > 0 && count($_jLtli) >= $_8C6It) break;
       }
     }
    }
    sort($_jLtli);
  }

  function _JJ8DO($_6IoCL){
    $_8C6l1 = 0;
    $_8Cfi0 = array();
    _JJPA1($_6IoCL, $_8Cfi0, true, $_8C6l1);
    return $_8C6l1 > 0;
  }


  function _JJPO0($_QLJfI, $_8CfiO, $_8C6It = -1){
    global $_JQjQ6;

    $_QL8i1 = $_QLJfI;

    $_j1881 = 0;
    $_8C81t = preg_quote(' ' . $_JQjQ6 . '=' . $_8CfiO);
    $_QL8i1 = preg_replace("/".$_8C81t."/is", "", $_QL8i1, $_8C6It, $_j1881);

    if($_j1881 == 0){
      $_8C81t = preg_quote($_JQjQ6 . '=' . $_8CfiO);
      $_QL8i1 = preg_replace("/".$_8C81t."/is", "", $_QL8i1, $_8C6It);
    }

    return $_QL8i1;
  }

  function _JJPA1($_6IoCL, $_8Cfi0, $_8C8tC, &$_8C8oJ){
    global $_JQjQ6;
    $_8C8oJ = 0;
    $_QL8i1 = $_6IoCL;
    if(stripos($_6IoCL, $_JQjQ6 . '=') === false) return $_QL8i1; // stripos is quicker than preg_match*
    $_8Ctf1 = array();

    if(count($_8Cfi0)){
      preg_match_all('/(?<!_)'.preg_quote($_JQjQ6, '/').'=([\'"])?(.*?)\\1/is', $_6IoCL, $_66tJo, PREG_SET_ORDER);
      for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
       if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;

       $_I1OoI = explode(" ", $_66tJo[$_Qli6J][2]);
       $_QLCt1 = false;
       for($_QliOt=0; $_QliOt<count($_I1OoI); $_QliOt++){
         if($_I1OoI[$_QliOt] == "") continue;
       $_QLCt1 = in_arrayi($_I1OoI[$_QliOt], $_8Cfi0);
       if($_QLCt1) break;
       }

       if($_QLCt1)
         $_8Ctf1[] = $_66tJo[$_Qli6J][1] . $_66tJo[$_Qli6J][2] . $_66tJo[$_Qli6J][1];
      }

      // remove all DefaultTargetGroupsHTMLAttribute= attribs when recipient is INCLUDED
      for($_Qli6J=0; $_Qli6J<count($_8Ctf1); $_Qli6J++){
         $_QL8i1 = _JJPO0($_QL8i1, $_8Ctf1[$_Qli6J]);
      }
    }

    // remove all other DefaultTargetGroupsHTMLAttribute= attribs and HTML tags
    $_QLJfI = $_QL8i1; // DEBUG, REMOVE STRReplace
    // empty attribs
    $_QLJfI = _JJPO0($_QLJfI, '""');
    $_8C81t = '/(?<!_)'.preg_quote($_JQjQ6, '/').'=([\'"])?(.*?)\\1/is';
    while (true) {
          if(preg_match($_8C81t, $_QLJfI, $_66tJo, PREG_OFFSET_CAPTURE)){
            $_QlOjt = $_66tJo[0][1];
            $_Ql0fO = substr($_QLJfI, 0, $_QlOjt);

            $_QlOjt = strpos_reverse($_Ql0fO, '<');

            $_8Cti0 = substr($_Ql0fO, $_QlOjt + 1);
            $_I016j = strpos($_8Cti0, ' ');
            if($_I016j !== false)
              $_8Cti0 = strtolower(trim(substr($_8Cti0, 0, $_I016j)));
              else
              $_8Cti0 = strtolower($_8Cti0);

            if (_JJR1P($_8Cti0)){ // e.g. <img src="">
              $_8CtlJ = strpos($_QLJfI, '>', $_QlOjt);
              if ($_8C8tC){
                $_8CO16 = substr($_QLJfI, $_QlOjt, $_8CtlJ - $_QlOjt + 1);
                if (stripos($_8CO16, 'file://') !== false)
                  $_8C8oJ++;
              };
              $_QLJfI = substr_replace($_QLJfI, "", $_QlOjt, $_8CtlJ - $_QlOjt + 1);
            }
            else
             if (_JJ6FC($_8Cti0)){
                $_8COfo = false;
                do{
                  //P gibt Pos von Start-Tag an
                  $_Ql0fO = substr($_QLJfI, $_QlOjt + 1);
                  $_8CtlJ = strpos($_Ql0fO, '</' . $_8Cti0);
                  if ($_8CtlJ === false){
                    // hier fehlt der EndTag!!
                    // rausloeschen, sonst gibt es endlessloop!
                    $_Ql0fO = $_66tJo[1][0] . $_66tJo[2][0] . $_66tJo[1][0];
                    $_QLJfI = _JJPO0($_QLJfI, $_Ql0fO, 1);
                    //$_8COfo = true;
                    break;
                  };
                  $_6joLQ = substr($_Ql0fO, 0, $_8CtlJ);
                  if (stripos($_6joLQ, '<' . $_8Cti0) === false){
                    // keine weiteren Tags dieser Art enthalten, ganzen Block löschen
                    $_8CtlJ += strlen('</' . $_8Cti0 . '>');
                    $_8CtlJ += $_QlOjt;
                    if ($_8C8tC){
                      $_8CO16 = substr($_QLJfI, $_QlOjt, $_8CtlJ - $_QlOjt + 1);
                      if (stripos($_8CO16, 'file://') !== false)
                        $_8C8oJ++;
                    }
                    $_QLJfI = substr_replace($_QLJfI, "", $_QlOjt, $_8CtlJ - $_QlOjt + 1);
                    break;
                  }
                  else
                  {
                    // innerTag löschen
                    $_8CoIo = stripos($_Ql0fO, '<' . $_8Cti0);
                    if (stripos($_Ql0fO, '</' . $_8Cti0) === false){
                      // hier fehlt der EndTag!!
                      // rausloeschen, sonst gibt es endlessloop!
                      $_Ql0fO = $_66tJo[1][0] . $_66tJo[2][0] . $_66tJo[1][0];
                      $_QLJfI = _JJPO0($_QLJfI, $_Ql0fO, 1);
                      //EndTagError := True;
                      break;
                    }
                    $_8Coto = stripos($_Ql0fO, '</' . $_8Cti0) + strlen('</' . $_8Cti0 . '>');
                    //$_Ql0fO = substr_replace($_Ql0fO, "", $_8CoIo, $_8Coto - $_8CoIo); // debug
                    if ($_8C8tC){
                      $_8CO16 = substr($_QLJfI, $_8CoIo + $_QlOjt, $_8Coto - $_8CoIo);
                      if (stripos($_8CO16, 'file://') !== false)
                        $_8C8oJ++;
                    }
                    $_QLJfI = substr_replace($_QLJfI, "", $_8CoIo + $_QlOjt, $_8Coto - $_8CoIo);

                  }
                } while (!$_8COfo);

              }
              else
              {
                // Unknown Tag
                $_Ql0fO = $_66tJo[1][0] . $_66tJo[2][0] . $_66tJo[1][0];
                $_QLJfI = _JJPO0($_QLJfI, $_Ql0fO, 1);
              }

          }
          else
           break;

       if ($_8C8tC && $_8C8oJ) break; // we count
    } // while (true)

    $_QL8i1 = $_QLJfI;

    return $_QL8i1;
  }

?>

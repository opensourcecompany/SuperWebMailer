<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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

  $_f1liI = array("area", "base", "basefont", "br", "col", "frame", "hr", "img", "input", "isindex", "link", "meta", "param");
  $_fQ01Q = array("a", "abbr", "acronym", "address", "applet", "b", "bdo", "big", "blockquote", "body", "button", "caption", "center", "cite", "code",
                         "colgroup", "dd", "del", "dfn", "dir", "div", "dl", "dt", "em", "fieldset", "font", "form", "frameset", "h1", "h2", "h3", "h4", "h5", "h6",
                         "head", "html", "i", "iframe", "ins", "kbd", "label", "legend", "li", "map", "menu", "noframes", "noscript", "object", "ol", "optgroup", "option",
                         "p", "pre", "q", "s", "samp", "script", "select", "small", "span", "strike", "strong", "style", "sub", "sup", "table", "tbody", "td", "textarea",
                         "tfoot", "th", "thead", "title", "tr", "tt", "u", "ul", "var", "section", "article", "main", "aside", "header", "footer", "nav", "figure", "figcaption",
                         "template", "video", "audio", "track", "embed", "mark", "progress", "meter", "time", "ruby", "rt", "rp", "bdi", "wbr", "canvas", "datalist", "keygen", "output");
  $_fQ0L1 = array();
  $_fQ1j6 = array();

  function _LJ1AB($_6tjI6){
    global $_fQ01Q, $_fQ1j6;
    $_6tjI6 = strtolower($_6tjI6);
    if(count($_fQ1j6) == 0) $_fQ1j6 = array_flip($_fQ01Q); // search is faster
    return isset($_fQ1j6[$_6tjI6]);
  }

  function _LJQPB($_6tjI6){
    global $_f1liI, $_fQ0L1;
    $_6tjI6 = strtolower($_6tjI6);
    if(count($_fQ0L1) == 0) $_fQ0L1 = array_flip($_f1liI); // search is faster
    return isset($_fQ0L1[$_6tjI6]);
  }

  function _LJO8O($_JfJLt, &$_j1L1C, $_fQ1i6 = 0){
    global $_jJfoI;
    $_j1L1C = array();
    if(stripos($_JfJLt, $_jJfoI . '=') === false) return; // stripos is quicker than preg_match*
    preg_match_all('/(?<!_)'.preg_quote($_jJfoI, '/').'=([\'"])?(.*?)\\1/is', $_JfJLt, $_JjOII, PREG_SET_ORDER);
    for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;

     $_Q8otJ = explode(" ", $_JjOII[$_Q6llo][2]);
     for($_Qf0Ct=0; $_Qf0Ct<count($_Q8otJ); $_Qf0Ct++){
       if($_Q8otJ[$_Qf0Ct] == "") continue;
       if(!in_array($_Q8otJ[$_Qf0Ct], $_j1L1C)){
         array_push($_j1L1C, $_Q8otJ[$_Qf0Ct]);
         if($_fQ1i6 > 0 && count($_j1L1C) >= $_fQ1i6) break;
       }
     }
    }
    sort($_j1L1C);
  }

  function _LJOAD($_JfJLt){
    $_fQ1lQ = 0;
    $_fQQtL = array();
    _LJLCA($_JfJLt, $_fQQtL, true, $_fQ1lQ);
    return $_fQ1lQ > 0;
  }


  function _LJLQD($_QJCJi, $_fQI6I, $_fQ1i6 = -1){
    global $_jJfoI;

    $_Q60l1 = $_QJCJi;

    $_IflL6 = 0;
    $_fQIL1 = preg_quote(' ' . $_jJfoI . '=' . $_fQI6I);
    $_Q60l1 = preg_replace("/".$_fQIL1."/is", "", $_Q60l1, $_fQ1i6, $_IflL6);

    if($_IflL6 == 0){
      $_fQIL1 = preg_quote($_jJfoI . '=' . $_fQI6I);
      $_Q60l1 = preg_replace("/".$_fQIL1."/is", "", $_Q60l1, $_fQ1i6);
    }

    return $_Q60l1;
  }

  function _LJLCA($_JfJLt, $_fQQtL, $_fQj1O, &$_fQJ0J){
    global $_jJfoI;
    $_fQJ0J = 0;
    $_Q60l1 = $_JfJLt;
    if(stripos($_JfJLt, $_jJfoI . '=') === false) return $_Q60l1; // stripos is quicker than preg_match*
    $_fQJ66 = array();

    if(count($_fQQtL)){
      preg_match_all('/(?<!_)'.preg_quote($_jJfoI, '/').'=([\'"])?(.*?)\\1/is', $_JfJLt, $_JjOII, PREG_SET_ORDER);
      for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
       if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;

       $_Q8otJ = explode(" ", $_JjOII[$_Q6llo][2]);
       $_Qo1oC = false;
       for($_Qf0Ct=0; $_Qf0Ct<count($_Q8otJ); $_Qf0Ct++){
         if($_Q8otJ[$_Qf0Ct] == "") continue;
       $_Qo1oC = in_arrayi($_Q8otJ[$_Qf0Ct], $_fQQtL);
       if($_Qo1oC) break;
       }

       if($_Qo1oC)
         $_fQJ66[] = $_JjOII[$_Q6llo][1] . $_JjOII[$_Q6llo][2] . $_JjOII[$_Q6llo][1];
      }

      // remove all DefaultTargetGroupsHTMLAttribute= attribs when recipient is INCLUDED
      for($_Q6llo=0; $_Q6llo<count($_fQJ66); $_Q6llo++){
         $_Q60l1 = _LJLQD($_Q60l1, $_fQJ66[$_Q6llo]);
      }
    }

    // remove all other DefaultTargetGroupsHTMLAttribute= attribs and HTML tags
    $_QJCJi = $_Q60l1; // DEBUG, REMOVE STRReplace
    // empty attribs
    $_QJCJi = _LJLQD($_QJCJi, '""');
    $_fQIL1 = '/(?<!_)'.preg_quote($_jJfoI, '/').'=([\'"])?(.*?)\\1/is';
    while (true) {
          if(preg_match($_fQIL1, $_QJCJi, $_JjOII, PREG_OFFSET_CAPTURE)){
            $_Q6i6i = $_JjOII[0][1];
            $_Q66jQ = substr($_QJCJi, 0, $_Q6i6i);

            $_Q6i6i = strpos_reverse($_Q66jQ, '<');

            $_fQ6jQ = substr($_Q66jQ, $_Q6i6i + 1);
            $_QllO8 = strpos($_fQ6jQ, ' ');
            if($_QllO8 !== false)
              $_fQ6jQ = strtolower(trim(substr($_fQ6jQ, 0, $_QllO8)));
              else
              $_fQ6jQ = strtolower($_fQ6jQ);

            if (_LJQPB($_fQ6jQ)){ // e.g. <img src="">
              $_fQ6of = strpos($_QJCJi, '>', $_Q6i6i);
              if ($_fQj1O){
                $_fQffl = substr($_QJCJi, $_Q6i6i, $_fQ6of - $_Q6i6i + 1);
                if (stripos($_fQffl, 'file://') !== false)
                  $_fQJ0J++;
              };
              $_QJCJi = substr_replace($_QJCJi, "", $_Q6i6i, $_fQ6of - $_Q6i6i + 1);
            }
            else
             if (_LJ1AB($_fQ6jQ)){
                $_fQfL1 = false;
                do{
                  //P gibt Pos von Start-Tag an
                  $_Q66jQ = substr($_QJCJi, $_Q6i6i + 1);
                  $_fQ6of = strpos($_Q66jQ, '</' . $_fQ6jQ);
                  if ($_fQ6of === false){
                    // hier fehlt der EndTag!!
                    // rausloeschen, sonst gibt es endlessloop!
                    $_Q66jQ = $_JjOII[1][0] . $_JjOII[2][0] . $_JjOII[1][0];
                    $_QJCJi = _LJLQD($_QJCJi, $_Q66jQ, 1);
                    //$_fQfL1 = true;
                    break;
                  };
                  $_JQCIj = substr($_Q66jQ, 0, $_fQ6of);
                  if (stripos($_JQCIj, '<' . $_fQ6jQ) === false){
                    // keine weiteren Tags dieser Art enthalten, ganzen Block löschen
                    $_fQ6of += strlen('</' . $_fQ6jQ . '>');
                    $_fQ6of += $_Q6i6i;
                    if ($_fQj1O){
                      $_fQffl = substr($_QJCJi, $_Q6i6i, $_fQ6of - $_Q6i6i + 1);
                      if (stripos($_fQffl, 'file://') !== false)
                        $_fQJ0J++;
                    }
                    $_QJCJi = substr_replace($_QJCJi, "", $_Q6i6i, $_fQ6of - $_Q6i6i + 1);
                    break;
                  }
                  else
                  {
                    // innerTag löschen
                    $_fQ86j = stripos($_Q66jQ, '<' . $_fQ6jQ);
                    if (stripos($_Q66jQ, '</' . $_fQ6jQ) === false){
                      // hier fehlt der EndTag!!
                      // rausloeschen, sonst gibt es endlessloop!
                      $_Q66jQ = $_JjOII[1][0] . $_JjOII[2][0] . $_JjOII[1][0];
                      $_QJCJi = _LJLQD($_QJCJi, $_Q66jQ, 1);
                      //EndTagError := True;
                      break;
                    }
                    $_fQtIC = stripos($_Q66jQ, '</' . $_fQ6jQ) + strlen('</' . $_fQ6jQ . '>');
                    //$_Q66jQ = substr_replace($_Q66jQ, "", $_fQ86j, $_fQtIC - $_fQ86j); // debug
                    if ($_fQj1O){
                      $_fQffl = substr($_QJCJi, $_fQ86j + $_Q6i6i, $_fQtIC - $_fQ86j);
                      if (stripos($_fQffl, 'file://') !== false)
                        $_fQJ0J++;
                    }
                    $_QJCJi = substr_replace($_QJCJi, "", $_fQ86j + $_Q6i6i, $_fQtIC - $_fQ86j);

                  }
                } while (!$_fQfL1);

              }
              else
              {
                // Unknown Tag
                $_Q66jQ = $_JjOII[1][0] . $_JjOII[2][0] . $_JjOII[1][0];
                $_QJCJi = _LJLQD($_QJCJi, $_Q66jQ, 1);
              }

          }
          else
           break;

       if ($_fQj1O && $_fQJ0J) break; // we count
    } // while (true)

    $_Q60l1 = $_QJCJi;

    return $_Q60l1;
  }

  if(!function_exists("in_arrayi")){
    function in_arrayi($_ILooj, $_ILo0C) {
      return in_array(strtolower($_ILooj), array_map('strtolower', $_ILo0C));
    }
  }

?>

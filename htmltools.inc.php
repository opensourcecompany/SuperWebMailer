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

define("PHP56ANDNEWER", version_compare(PHP_VERSION, "5.5") > 0);

function strip_tags_keep_links($_6O11j)  // not used SWM 8.10
{
  //return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/href=[^\"\']*/i', '/\b((?![hH][rR][eE][fF]\b)\w+)[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", @strip_tags($_6O11j,'<a>'));
  return preg_replace_callback('/<(.*?)>/i', 'strip_tags_keep_links_preg_replace_callback'
         ,
         @strip_tags($_6O11j, '<a>')
         );
}

function strip_tags_keep_links_preg_replace_callback ($_6OQ0j){  // not used SWM 8.10
         return '<' . preg_replace(array('/href=[^\"\']*/i', '/\b((?![hH][rR][eE][fF]\b)\w+)[ \t\n]*=[ \t\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes($_6OQ0j[1])) . '>';
         }

// from https://www.php.net/manual/de/function.strip-tags.php         
function _LBDLO( $_6I1QQ, $_6OQi8 = '', $_6OI8C = false, $_6OIlJ = false, callable $_6OjfC = null ) {
  $_6OQi8 = array_map( 'strtolower', array_filter( // lowercase
      preg_split( '/(?:>|^)\\s*(?:<|$)/', $_6OQi8, -1, PREG_SPLIT_NO_EMPTY ), // get tag names
      function( $_6OjC0 ) { return preg_match( '/^[a-z][a-z0-9_]*$/i', $_6OjC0 ); } // filter broken
  ) );
  $_6OJQL = preg_split( '/(<!--.*?(?:-->|$))/', $_6I1QQ, -1, PREG_SPLIT_DELIM_CAPTURE );
  foreach ( $_6OJQL as $_Qli6J => $_6OJ6j ) {
    if ( $_Qli6J % 2 ) { // html comment
      if ( !( $_6OIlJ && preg_match( '/<!--.*?-->/', $_6OJ6j ) ) ) {
        $_6OJQL[$_Qli6J] = '';
      }
    } else { // stuff between comments
      $_6OJLl = preg_split( "/(<(?:[^>\"']++|\"[^\"]*+(?:\"|$)|'[^']*+(?:'|$))*(?:>|$))/", $_6OJ6j, -1, PREG_SPLIT_DELIM_CAPTURE );
      foreach ( $_6OJLl as $_QliOt => $_6O6LQ ) {
        $_6O6li = false;
        $_6OfIo = true;
        $_QL8i1 = $_6O6LQ;
        if ( $_QliOt % 2 ) { // tag
          if ( preg_match( "%^(</?)([a-z][a-z0-9_]*)\\b(?:[^>\"'/]++|/+?|\"[^\"]*\"|'[^']*')*?(/?>)%i", $_6O6LQ, $_6OQ0j ) ) {
            $_6OjC0 = strtolower( $_6OQ0j[2] );
            if ( in_array( $_6OjC0, $_6OQi8 ) ) {
              if ( $_6OI8C ) {
                $_6OfC1 = $_6OQ0j[1];
                $_6O86Q = ( $_6OfC1 === '</' ) ? '>' : $_6O86Q;
                $_QL8i1 = $_6OfC1 . $_6OjC0 . $_6O86Q;
              }
            } else {
              $_6OfIo = false;
              $_QL8i1 = '';
            }
          } else {
            $_6O6li = true;
            $_QL8i1 = 'WARNING: BROKEN TAG "' . $_6O6LQ . '"';
          }
        } else { // text
          $_6OjC0 = false;
        }
        if ( !$_6O6li && isset( $_6OjfC ) ) {
          // allow result modification
          call_user_func_array( $_6OjfC, array( &$_QL8i1, $_6O6LQ, $_6OjC0, $_6OfIo ) );
        }
        $_6OJLl[$_QliOt] = $_QL8i1;
      }
      $_6OJQL[$_Qli6J] = implode( '', $_6OJLl );
    }
  }
  $_6I1QQ = implode( '', $_6OJQL );
  return $_6I1QQ;
}
         
function _LBDA8($_6IoCL, $_6j0O1 = 'utf-8', $_QloQi = false, $_6O88o = false) {
  global $_QLl1Q;

  $_6IoCL = _LA1Q8($_6IoCL);
    
  #$_6IoCL = preg_replace('/' . preg_quote('<!--[if', '/') . '(.*?)' .  preg_quote('<![endif]-->', '/') . '/is', "", $_6IoCL);
  #$_6IoCL = preg_replace('/' . preg_quote('<!--', '/') . '(.*?)' .  preg_quote('-->', '/') . '/is', "", $_6IoCL);

  $_6IoCL = preg_replace('/(<style.*?[^>]*>)([^>]*)(<\/style>)/is', '', $_6IoCL);

  $_6IoCL = str_replace("\t", " ", $_6IoCL);

  if($_QloQi){
    $_6IoCL = preg_replace("/\r\n|\r|\n/", "", $_6IoCL);
    while(strpos($_6IoCL, '  ') !== false)
       $_6IoCL = str_replace('  ', '', $_6IoCL);

    for($_Qli6J=0; $_Qli6J<2; $_Qli6J++)
      $_6IoCL = preg_replace("/\r\n\r\n|\r\r|\n\n/is", "", $_6IoCL);

    // ckeditor emulation for formating HTML tags
    $_6O8C1 = array('div', 'p', 'table', 'td', 'tr', 'ol', 'ul', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
    for($_Qli6J=0; $_Qli6J<count($_6O8C1); $_Qli6J++){
      $_6IoCL = str_replace("<$_6O8C1[$_Qli6J]", "\r\n<$_6O8C1[$_Qli6J]", $_6IoCL);
      $_6IoCL = str_replace("</$_6O8C1[$_Qli6J]>", "</$_6O8C1[$_Qli6J]>\r\n", $_6IoCL);
    }
  }

  $_QlOjt = stripos($_6IoCL, "</body>");
  if($_QlOjt !== false){
    $_6IoCL = substr($_6IoCL, 0, $_QlOjt);
    $_QlOjt = stripos($_6IoCL, "<body");
    if($_QlOjt !== false){
      $_6IoCL = substr($_6IoCL, $_QlOjt + 1);
      $_QlOjt = strpos($_6IoCL, ">");
      if($_QlOjt !== false)
        $_6IoCL = substr($_6IoCL, $_QlOjt + 1);
        else
        $_6IoCL = "";
    }
  }
  #$_6IoCL = preg_replace('/(<style.*?[^>]*>)([^>]*)(<\/style>)/is', '', $_6IoCL);
  $_6IoCL = str_replace(chr(194).chr(167), "&sect;", $_6IoCL); // problem § char, mysql cuts plain text
  $_6IoCL = str_replace(chr(167), "&sect;", $_6IoCL); // problem § char, mysql cuts plain text
  $_6IoCL = str_replace("&nbsp;", " ", $_6IoCL);
  #$_6IoCL = unhtmlentities($_6IoCL, $_6j0O1);

  $_6IoCL = preg_replace('/[ \r\n]style\=[\"].*?[\"]/is', "", $_6IoCL);

  $_6IoCL = str_replace("<p></p>", "", $_6IoCL);
  $_6IoCL = str_replace("<div></div>", "", $_6IoCL);
  $_6IoCL = str_replace("<p> </p>", "<p></p>", $_6IoCL);
  $_6IoCL = str_replace("<div> </div>", "<div></div>", $_6IoCL);
  $_6IoCL = str_replace("</div>", "</div>", $_6IoCL);
  $_6IoCL = str_replace("<td> </td>", "<td></td>", $_6IoCL);

  $_6IoCL = str_replace("< /br>", "< /br>**b**", $_6IoCL);
  $_6IoCL = str_replace("</br>", "</br>**b**", $_6IoCL);
  $_6IoCL = str_replace("<br>", "<br>**b**", $_6IoCL);
  $_6IoCL = str_replace("<br />", "<br />**b**", $_6IoCL);
  for($_Qli6J=1;$_Qli6J<=6;$_Qli6J++)
     $_6IoCL = str_replace("</h$_Qli6J>", "</h$_Qli6J>**b**", $_6IoCL);
  $_6IoCL = str_replace("<li>", "<li>* ", $_6IoCL);
  $_6IoCL = str_replace("</p>", "</p>", $_6IoCL);
  $_6IoCL = str_replace("</td>", "</td>**b**", $_6IoCL);
  $_6IoCL = str_replace("</li>", "</li>**b**", $_6IoCL);
  $_6IoCL = str_replace("</ul>", "</ul>", $_6IoCL);
  $_6IoCL = str_replace("</ol>", "</ol>", $_6IoCL);

  $_IjQI8 = array();
  $_IjQCO = array();
  _LAL0C($_6IoCL, $_IjQI8, $_IjQCO, true, true);
  for($_Qli6J=0;$_Qli6J<count($_IjQI8);$_Qli6J++) {
    $_68tQ1 = $_IjQI8[$_Qli6J];
    $_QlOjt = strpos($_68tQ1, "#");
    if($_QlOjt !== false && $_QlOjt == 0) $_68tQ1="";
    if(stripos($_68tQ1, "mailto:") !== false)
      $_68tQ1 = substr($_68tQ1, 7);
    if( strtolower($_68tQ1) == strtolower($_IjQCO[$_Qli6J]) )
      $_IO08l = $_68tQ1;
       else
         if(stripos($_68tQ1, $_IjQCO[$_Qli6J]) === false && stripos(_LPC1C($_68tQ1), $_IjQCO[$_Qli6J]) === false)
           $_IO08l = $_IjQCO[$_Qli6J]. ' ' . $_68tQ1;
           else
            if( !_L8JLR('info@'. _LPBCC($_IjQCO[$_Qli6J])) && !_L8JLR('info@'. _LPB6Q(_LPBCC($_IjQCO[$_Qli6J]))) )
              $_IO08l = $_IjQCO[$_Qli6J]. ' ' . $_68tQ1;
              else
               $_IO08l = $_68tQ1;

    for($_Ift08=0; $_Ift08<2; $_Ift08++){
      $_6Otj6 = $_IjQI8[$_Qli6J];
      if($_Ift08 == 1)
        $_6Otj6 = _LPC1C($_6Otj6);
      $_6Otit = '/(<a.*?href\=[\"\']' . preg_quote($_6Otj6, '/') . '[\"\'].*?[\/>].*?<\/a>)/i';
      if(preg_match_all($_6Otit, $_6IoCL, $_66tJo, PREG_PATTERN_ORDER) && isset($_66tJo[0][0])){
        $_6IoCL = preg_replace("/".preg_quote($_66tJo[0][0], '/')."/", $_IO08l, $_6IoCL, 1);
      }
    }
  }

  // href, dup link text
  preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $_6IoCL, $_66tJo, PREG_SET_ORDER);
  for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     $_6IoCL = str_replace('">' .  _LPC1C($_66tJo[$_Qli6J][2]) . '</a>', '"></a>', $_6IoCL);
     $_6IoCL = str_replace('">' .  _LPBCC($_66tJo[$_Qli6J][2]) . '</a>', '"></a>', $_6IoCL);
  }

  #$_6IoCL = strip_tags_keep_links( $_6IoCL );
  
  if($_QloQi){
      $_6IoCL = $_6IoCL;
  }  
  
  $_6IoCL = _LBDLO($_6IoCL, '<a>');

  $_6IoCL = unhtmlentities($_6IoCL, $_6j0O1);

  $_6IoCL = str_replace("**b**", $_QLl1Q, $_6IoCL);
  $_6IoCL = str_replace($_QLl1Q.$_QLl1Q, $_QLl1Q, $_6IoCL);
  $_6IoCL = str_replace("</a>", " ", $_6IoCL);
  $_6IoCL = str_replace("<a \"", "", $_6IoCL);
  $_6IoCL = str_replace("<a >", "", $_6IoCL); // no "?
  $_6IoCL = str_replace("<a", "", $_6IoCL); // no "?
  $_6IoCL = str_replace('">', " ", $_6IoCL);
  $_6IoCL = str_replace('" >', " ", $_6IoCL); // FF bug

  $_6IoCL = str_replace("&nbsp;", " ", $_6IoCL); // &nbsp; in space

  while(strpos($_6IoCL, "  ") !== false)
     $_6IoCL = str_replace("  ", " ", $_6IoCL);

  if($_QloQi){
    $_6IoCL = str_replace("\n\n\n", "\n", $_6IoCL);
    while(strpos($_6IoCL, "\r\n\r\n\r\n") !== false)
      $_6IoCL = str_replace("\r\n\r\n\r\n", "\r\n", $_6IoCL);
    while(strpos($_6IoCL, "\r\n \r\n") !== false)
       $_6IoCL = str_replace("\r\n \r\n", "\r\n", $_6IoCL);
    $_6IoCL = preg_replace("/\r\n\r\n|\r\r|\n\n/is", "\r\n", $_6IoCL);
  }

  #2b
  $_6IoCL = str_replace("**2b**", $_QLl1Q.$_QLl1Q, $_6IoCL);
  #3b
  $_6IoCL = str_replace("**3b**", $_QLl1Q.$_QLl1Q.$_QLl1Q, $_6IoCL);
  if(!$_6O88o || strtolower($_6j0O1) != "utf-8")
    return trim( $_6IoCL );
    else
    return _LC6CP( trim( $_6IoCL ) );
}

if(!function_exists('html_entity_decode')) {
  function html_entity_decode($_6IQC6, $_6OtlQ, $charset)
  {
     return html_entity_decode_internal($_6IQC6, $_6OtlQ, $charset);
  }
}

$_6OOCJ = "www.superscripte.de";

function html_entity_decode_internal($_6IQC6, $_6OtlQ, $charset)
{
    static $_6Oot1;  
    // replace numeric entities
    //$_6IQC6 = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $_6IQC6);
    //$_6IQC6 = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $_6IQC6);

    if(strpos($_6IQC6, "&#") !== false){
      if(strpos($_6IQC6, "&#x") !== false){
         $_6IQC6 = preg_replace_callback('~&#x([0-9a-f]+);~i',
               "html_entity_decode_internal_preg_replace_callback1", $_6IQC6);
      }

      $_6IQC6 = preg_replace_callback('~&#([0-9]+);~',
             'html_entity_decode_internal_preg_replace_callback2', $_6IQC6);
    }         

    if(PHP56ANDNEWER){
      return str_replace("&nbsp;", " ", html_entity_decode($_6IQC6, ENT_COMPAT, "ISO-8859-1"));
    }

    // older PHP versions
    // replace literal entities
    if (!isset($_6Oot1))
    {
      $_6Oot1 = get_html_translation_table(HTML_ENTITIES, ENT_COMPAT, "ISO-8859-1");
      $_6Oot1[" "] = '&nbsp;';
      $_6Oot1 = array_flip($_6Oot1);
    }
    return strtr($_6IQC6, $_6Oot1);
}

function html_entity_decode_internal_preg_replace_callback1($_6OQ0j){
  return chr(hexdec($_6OQ0j[1]));
}

function html_entity_decode_internal_preg_replace_callback2($_6OQ0j){
  return chr($_6OQ0j[1]);
}

function unhtmlentities($_6IQC6, $charset = "UTF-8", $_6OCj6 = true) {
  return html_entity_decode_full($_6IQC6, ENT_COMPAT, strtoupper($charset), $_6OCj6);
}


function html_entity_decode_utf8($_6IQC6, $_6OCj6 = True)
{
    static $_6OC60;

    // replace numeric entities
    //$_6IQC6 = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $_6IQC6);
    //$_6IQC6 = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $_6IQC6);

    if(strpos($_6IQC6, "&#") !== false){  // e.g. for emojis
      if($_6OCj6 && !PHP56ANDNEWER){ // PHP 5.6 can decode &#value;
        $_6IQC6 = preg_replace_callback('~&#([0-9]+);~',
                'html_entity_decode_utf8_preg_replace_callback2', $_6IQC6);
        if(strpos($_6IQC6, "&#x") !== false){
          $_6IQC6 = preg_replace_callback('~&#x([0-9a-f]+);~i',
                  'html_entity_decode_utf8_preg_replace_callback1', $_6IQC6);
        }          
      }else{
        if(!$_6OCj6 && PHP56ANDNEWER)
           $_6IQC6 = preg_replace('~&#([0-9]+);~', '&emoji\1;', $_6IQC6);
      }        
    }          

    if(PHP56ANDNEWER){
      if($_6OCj6)
        return html_entity_decode($_6IQC6, ENT_COMPAT, "UTF-8");
        else{
          $_6IQC6 = html_entity_decode($_6IQC6, ENT_COMPAT, "UTF-8");

          $_6IQC6 = preg_replace('~&emoji([0-9]+);~', '&#\1;', $_6IQC6);

          return $_6IQC6;      
        }
    }
    
    // older PHP versions
    // replace literal entities
    if (!isset($_6OC60))
    {
        $_6OC60 = array();

        foreach (get_html_translation_table(HTML_ENTITIES, ENT_COMPAT, "ISO-8859-1") as $_I1Q0I=>$key)
            $_6OC60[$key] = utf8_encode($_I1Q0I);
    }

    return strtr($_6IQC6, $_6OC60);
}

function html_entity_decode_utf8_preg_replace_callback1($_6OQ0j){
 return code2utf(hexdec($_6OQ0j[1]));
}

function html_entity_decode_utf8_preg_replace_callback2($_6OQ0j){
 return code2utf($_6OQ0j[1]);;
}

$_6OiII = "codecheck";

// Returns the utf string corresponding to the unicode value (from php.net, courtesy - romans@void.lv)
function code2utf($_6OL01)
{
    if ($_6OL01 < 128) return chr($_6OL01);
    if ($_6OL01 < 2048) return chr(($_6OL01 >> 6) + 192) . chr(($_6OL01 & 63) + 128);
    if ($_6OL01 < 65536) return chr(($_6OL01 >> 12) + 224) . chr((($_6OL01 >> 6) & 63) + 128) . chr(($_6OL01 & 63) + 128);
    if ($_6OL01 < 2097152) return chr(($_6OL01 >> 18) + 240) . chr((($_6OL01 >> 12) & 63) + 128) . chr((($_6OL01 >> 6) & 63) + 128) . chr(($_6OL01 & 63) + 128);
    return '';
}

/* Bafflingly, html_entity_decode() only converts the 100 most common named
 * entities, whereas the HTML 4.01 Recommendation lists over 250. This
 * wrapper function converts all known named entities to numeric ones
 * before handing over to the original html_entity_decode, and hopefully
 * isn't too insufferably slow (am I right in thinking that making the
 * conversion table static will prevent it being reinitialised on each
 * call?)
 *
 * Update: 2008-03-26
 * Apologies to those who weren't sure if this was free to use: it is! See
 * the MIT License below, which should help (if in fact it prevents you
 * using it, please contact me and we'll arrange something). The sole
 * intentions are:
 *   1. to make sure you're satisfied that you're legally allowed to use
 *      this and I can't later complain that you can't, and
 *   2. to absolve myself of legal liability if you or I later turn out to
 *      be arseholes.
 */


function html_entity_decode_full($_6IQC6, $_6OLCI = ENT_COMPAT, $charset = 'ISO-8859-1', $_6OCj6 = True) {
  /* Regular Expression:
   * Named HTML entities start with an ampersand, have a single alpha character,
   * then 1-7 alphanumeric characters and finally a semicolon. (e.g. &gt;
   * &there4; &Upsilon;) They are case-sensitive (&egrave; is not &Egrave;)
   */
  if(!isset($_6IQC6)) return "";
  if( strtoupper($charset) == "UTF-8" )
  return html_entity_decode_utf8(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $_6IQC6), $_6OCj6);
  else
  return html_entity_decode_internal(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $_6IQC6), $_6OLCI, $charset);
}

/* Swap HTML named entities with numeric entities
 * This contains the full HTML 4 Recommendation listing of entities, so the default to discard
 * entities not in the table is generally good. Pass false to the second argument to return
 * the faulty entity unmodified, if you're ill or something.
 */
function convert_entity($_6OQ0j, $_6OLLL = true) {
  static $_6Ol6i = array('quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;','Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;','lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;','rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;','fnof' => '&#402;','Alpha' => '&#913;',
  'Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;','Theta' => '&#920;','Iota' => '&#921;',
  'Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;','Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;',
  'Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;','Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;',
  'epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;','theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;',
  'omicron' => '&#959;','pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;',
  'psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;','Prime' => '&#8243;',
  'oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;','larr' => '&#8592;','uarr' => '&#8593;',
  'rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;','rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;',
  'forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;','isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;',
  'sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;','prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;',
  'cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;','there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;',
  'ge' => '&#8805;','sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;',
  'sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;','spades' => '&#9824;',
  'clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;','curren' => '&#164;','yen' => '&#165;',
  'brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;','not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;',
  'deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;','acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;',
  'sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;','frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;',
  'Acirc' => '&#194;','Atilde' => '&#195;','Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;',
  'Euml' => '&#203;','Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;',
  'Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;','Uuml' => '&#220;',
  'Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;','auml' => '&#228;','aring' => '&#229;',
  'aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;','igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;',
  'iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;','ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;',
  'oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;','uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;'
                        );
  if (isset($_6Ol6i[$_6OQ0j[1]])) return $_6Ol6i[$_6OQ0j[1]];
  // else
  return $_6OLLL ? '' : $_6OQ0j[0];
}



/* html_entity_decode_full()
 * Copyright (c) 2007 Matt Robinson <http://mattrobinson.info/>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


if(!function_exists("UTF8ToEntities")) {
// from http://php.net/manual/de/function.utf8-decode.php
function UTF8ToEntities ($_6IQC6) {
     $_6IQC6 = _LC6CP($_6IQC6);
  
     /* note: apply htmlspecialchars if desired /before/ applying this function
     /* Only do the slow convert if there are 8-bit characters */
     /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
     if (! preg_match("/[\200-\237]/", $_6IQC6) and ! preg_match("/[\241-\377]/", $_6IQC6))
         return $_6IQC6;

    // reject too-short sequences
     $_6IQC6 = preg_replace("/[\302-\375]([\001-\177])/", "&#65533;\\1", $_6IQC6);
    $_6IQC6 = preg_replace("/[\340-\375].([\001-\177])/", "&#65533;\\1", $_6IQC6);
    $_6IQC6 = preg_replace("/[\360-\375]..([\001-\177])/", "&#65533;\\1", $_6IQC6);
    $_6IQC6 = preg_replace("/[\370-\375]...([\001-\177])/", "&#65533;\\1", $_6IQC6);
    $_6IQC6 = preg_replace("/[\374-\375]....([\001-\177])/", "&#65533;\\1", $_6IQC6);

    // reject illegal bytes & sequences
         // 2-byte characters in ASCII range
     $_6IQC6 = preg_replace("/[\300-\301]./", "&#65533;", $_6IQC6);
         // 4-byte illegal codepoints (RFC 3629)
     $_6IQC6 = preg_replace("/\364[\220-\277]../", "&#65533;", $_6IQC6);
         // 4-byte illegal codepoints (RFC 3629)
     $_6IQC6 = preg_replace("/[\365-\367].../", "&#65533;", $_6IQC6);
         // 5-byte illegal codepoints (RFC 3629)
     $_6IQC6 = preg_replace("/[\370-\373]..../", "&#65533;", $_6IQC6);
         // 6-byte illegal codepoints (RFC 3629)
     $_6IQC6 = preg_replace("/[\374-\375]...../", "&#65533;", $_6IQC6);
         // undefined bytes
     $_6IQC6 = preg_replace("/[\376-\377]/", "&#65533;", $_6IQC6);

    // reject consecutive start-bytes
     $_6IQC6 = preg_replace("/[\302-\364]{2,}/", "&#65533;", $_6IQC6);

    // decode four byte unicode characters
/*     $_6IQC6 = preg_replace(
         "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/e",
         "'&#'.((ord('\\1')&7)<<18 | (ord('\\2')&63)<<12 |" .
         " (ord('\\3')&63)<<6 | (ord('\\4')&63)).';'",
     $_6IQC6); */

     $_6IQC6 = preg_replace_callback(
         "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback1',
     $_6IQC6);

    // decode three byte unicode characters
    /* $_6IQC6 = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
     "'&#'.((ord('\\1')&15)<<12 | (ord('\\2')&63)<<6 | (ord('\\3')&63)).';'",
     $_6IQC6); */

     $_6IQC6 = preg_replace_callback("/([\340-\357])([\200-\277])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback2',
     $_6IQC6);

    // decode two byte unicode characters
     /* $_6IQC6 = preg_replace("/([\300-\337])([\200-\277])/e",
     "'&#'.((ord('\\1')&31)<<6 | (ord('\\2')&63)).';'",
     $_6IQC6); */

     $_6IQC6 = preg_replace_callback("/([\300-\337])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback3',
     $_6IQC6);

    // reject leftover continuation bytes
     $_6IQC6 = preg_replace("/[\200-\277]/", "&#65533;", $_6IQC6);

    return $_6IQC6;
 }

 function UTF8ToEntities_preg_replace_callback1($_6OQ0j){
  return '&#'.((ord($_6OQ0j[1])&7)<<18 | (ord($_6OQ0j[2])&63)<<12 |
          (ord($_6OQ0j[3])&63)<<6 | (ord($_6OQ0j[4])&63)).';';
 }

 function UTF8ToEntities_preg_replace_callback2($_6OQ0j){
  return '&#'.((ord($_6OQ0j[1])&15)<<12 | (ord($_6OQ0j[2])&63)<<6 | (ord($_6OQ0j[3])&63)).';';
 }

 function UTF8ToEntities_preg_replace_callback3($_6OQ0j){
  return '&#'.((ord($_6OQ0j[1])&31)<<6 | (ord($_6OQ0j[2])&63)).';';
 }

}

// https://stackoverflow.com/questions/9462104/remove-on-js-event-attributes-from-html-tags

$_6OliC = '(?(DEFINE)
    (?<tagname> [a-z][^\s>/]*+    )
    (?<attname> [^\s>/][^\s=>/]*+    )  # first char can be pretty much anything, including =
    (?<attval>  (?>
                    "[^"]*+" |
                    \'[^\']*+\' |
                    [^\s>]*+            # unquoted values can contain quotes, = and /
                )
    )
    (?<attrib>  (?&attname)
                (?: \s*+
                    = \s*+
                    (?&attval)
                )?+
    )
    (?<crap>    [^\s>]    )             # most crap inside tag is ignored, will eat the last / in self closing tags
    (?<tag>     <(?&tagname)
                (?: \s*+                # spaces between attributes not required: <b/foo=">"style=color:red>bold red text</b>
                    (?>
                        (?&attrib) |    # order matters
                        (?&crap)        # if not an attribute, eat the crap
                    )
                )*+
                \s*+ /?+
                \s*+ >
    )
)';


// removes onanything attributes from all matched HTML tags
function _LCLCP($_QLoli){
    global $_6OliC;
    $_6Ollo = '(?&tag)' . $_6OliC;
    //return preg_replace("~$_6Ollo~xie", 'remove_event_attributes_from_tag("$0")', $_QLoli);
    return preg_replace_callback("~$_6Ollo~xi", 'remove_event_attributes_callback', $_QLoli);
}

function remove_event_attributes_callback($_6OQ0j){
 return remove_event_attributes_from_tag($_6OQ0j[0]);
}

// removes onanything attributes from a single opening tag
function remove_event_attributes_from_tag($_6OjC0){
    global $_6OliC;
    $_6Ollo = '( ^ <(?&tagname) ) | \G \s*+ (?> ((?&attrib)) | ((?&crap)) )' . $_6OliC;
    //return preg_replace("~$_6Ollo~xie", '"$1$3"? "$0": (preg_match("/^on/i", "$2")? " ": "$0")', $_6OjC0);
    return preg_replace_callback("~$_6Ollo~xi", 'remove_event_attributes_from_tag_callback', $_6OjC0);
}
function remove_event_attributes_from_tag_callback($_6OQ0j){
  if(isset($_6OQ0j[3]))
    return ($_6OQ0j[1].$_6OQ0j[3]) ? $_6OQ0j[0]: (preg_match("/^on/i", $_6OQ0j[2]) ? " ": $_6OQ0j[0]);
    else
    return ($_6OQ0j[1]) ? $_6OQ0j[0]: (preg_match("/^on/i", $_6OQ0j[2]) ? " ": $_6OQ0j[0]);
}


function _LC6RR($_6o06o){ // requires UTF-8 string, returns false when no emojis inside
 // https://stackoverflow.com/questions/41580483/detect-emoticons-in-string 
 //https://unicode.org/emoji/charts/full-emoji-list.html
 /*
 $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u'; 
 $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
 $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
 $regex_misc = '/[\x{2600}-\x{26FF}]/u';
 $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
 $regex_symbols_picto = '/[\x{1F910}-\x{1F93F}]/u'; 
 1F100 - 1F1FF Flags
 */

  if(DefaultMySQLEncoding == "utf8mb4") # we don't need it 
     return false;
 
  $_6o0CJ = '/[\x{1F100}-\x{1FFFF}]|[\x{2600}-\x{27BF}]/u'; 
  
  return preg_match($_6o0CJ, $_6o06o);
}

function _LC6CP($_6o06o){ // requires UTF-8 string, returns html encoded emojis
  
  
 if(!mbfunctionsExists)
   return $_6o06o; 

 if(!_LC6RR($_6o06o))
   return $_6o06o; 
 
 $_I1OoI = mb_str_split($_6o06o, 1, "UTF-8");

 $_6o0C8 = "";
 foreach($_I1OoI as $key => $_QltJO){

    if(strlen($_QltJO) < 2){ // checks in bytes!!
      $_6o0C8 .= $_QltJO;
      continue;
    }   
    
    if( ($_jjOlo = mb_ord($_QltJO, "UTF-8")) > 255 ){
     $_6o0C8 .= sprintf("&#%d;", $_jjOlo); 
    }else
     $_6o0C8 .= $_QltJO;
 
 }
 
 return $_6o0C8;
}

function _LCRC8($_6IQC6){ // returns UTF-8

    if(strpos($_6IQC6, "&#") !== false){
      if(strpos($_6IQC6, "&#x") !== false){
        $_6IQC6 = preg_replace_callback('~&#x([0-9a-f]+);~i',
                'html_entity_decode_utf8_preg_replace_callback1', $_6IQC6);
      }          
      $_6IQC6 = preg_replace_callback('~&#([0-9]+);~',
              'html_entity_decode_utf8_preg_replace_callback2', $_6IQC6);
    } 
    
    return $_6IQC6;         
}

function _LC806($_jlJoJ){ // returns UTF-8
    $_IjO6t = new emoji_helper_Class(array());
    $_IjO6t->decodeEmojisInArray($_jlJoJ);
    $_IjO6t = null;
    return $_jlJoJ;
}

?>

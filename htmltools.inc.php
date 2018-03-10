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


function strip_tags_keep_links($_JfJ1o)
{
  //return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/href=[^\"\']*/i', '/\b((?![hH][rR][eE][fF]\b)\w+)[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", @strip_tags($_JfJ1o,'<a>'));
  return preg_replace_callback('/<(.*?)>/i', 'strip_tags_keep_links_preg_replace_callback'
         ,
         @strip_tags($_JfJ1o,'<a>')
         );
}

function strip_tags_keep_links_preg_replace_callback ($_JItfQ){
         return '<' . preg_replace(array('/href=[^\"\']*/i', '/\b((?![hH][rR][eE][fF]\b)\w+)[ \t\n]*=[ \t\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes($_JItfQ[1])) . '>';
         }

function _ODQAB($_JfJLt, $_Jf6jI = 'utf-8') {
  global $_Q6JJJ;

  $_Q6i6i = stripos($_JfJLt, "</body>");
  if($_Q6i6i !== false){
    $_JfJLt = substr($_JfJLt, 0, $_Q6i6i - 1);
    $_Q6i6i = stripos($_JfJLt, "<body");
    if($_Q6i6i !== false){
      $_JfJLt = substr($_JfJLt, $_Q6i6i + 1);
      $_Q6i6i = strpos($_JfJLt, ">");
      if($_Q6i6i !== false)
        $_JfJLt = substr($_JfJLt, $_Q6i6i + 1);
        else
        $_JfJLt = "";
    }
  }
  $_JfJLt = preg_replace('/(<style.*?[^>]*>)([^>]*)(<\/style>)/is', '', $_JfJLt);
  $_JfJLt = str_replace(chr(167), "&sect;", $_JfJLt); // problem § char, mysql cuts plain text
  $_JfJLt = unhtmlentities($_JfJLt, $_Jf6jI);

  $_JfJLt = str_replace("\t", " ", $_JfJLt);
  $_JfJLt = str_replace("\t ", " ", $_JfJLt);
  $_JfJLt = str_replace(" \t", " ", $_JfJLt);

  $_JfJLt = str_replace("<p></p>", "", $_JfJLt);
  $_JfJLt = str_replace("<div></div>", "", $_JfJLt);
  $_JfJLt = str_replace("<p> </p>", "<p></p>", $_JfJLt);
  $_JfJLt = str_replace("<div> </div>", "<div></div>", $_JfJLt);

  /*
  $_JfJLt = preg_replace("/\r\n|\r|\n/", "", $_JfJLt);
  while(strpos($_JfJLt, '  ') !== false)
     $_JfJLt = str_replace('  ', '', $_JfJLt);

  for($_Q6llo=0; $_Q6llo<2; $_Q6llo++)
    $_JfJLt = preg_replace("/\r\n\r\n|\r\r|\n\n/", "", $_JfJLt);

  // ckeditor emulation for formating HTML tags
  $BreakTags = array('div', 'p', 'table', 'td', 'tr', 'ol', 'ul', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
  for($_Q6llo=0; $_Q6llo<count($BreakTags); $_Q6llo++){
    $_JfJLt = str_replace("<$BreakTags[$_Q6llo]", "\r\n<$BreakTags[$_Q6llo]", $_JfJLt);
    $_JfJLt = str_replace("</$BreakTags[$_Q6llo]>", "</$BreakTags[$_Q6llo]>\r\n", $_JfJLt);
  }
  */


  $_JfJLt = str_replace("</div>", "</div>", $_JfJLt);
  $_JfJLt = str_replace("< /br>", "< /br>**b**", $_JfJLt);
  $_JfJLt = str_replace("</br>", "</br>**b**", $_JfJLt);
  $_JfJLt = str_replace("<br>", "<br>**b**", $_JfJLt);
  $_JfJLt = str_replace("<br />", "<br />**b**", $_JfJLt);
  for($_Q6llo=1;$_Q6llo<=6;$_Q6llo++)
     $_JfJLt = str_replace("</h$_Q6llo>", "</h$_Q6llo>**b**", $_JfJLt);
  $_JfJLt = str_replace("<li>", "<li>* ", $_JfJLt);
  $_JfJLt = str_replace("</p>", "</p>", $_JfJLt);
  $_JfJLt = str_replace("</td>", "</td>**b**", $_JfJLt);
  $_JfJLt = str_replace("</li>", "</li>**b**", $_JfJLt);
  $_JfJLt = str_replace("</ul>", "</ul>", $_JfJLt);
  $_JfJLt = str_replace("</ol>", "</ol>", $_JfJLt);

  $_QOLIl = array();
  $_QOLCo = array();
  _OBBPD($_JfJLt, $_QOLIl, $_QOLCo, true, true);
  for($_Q6llo=0;$_Q6llo<count($_QOLIl);$_Q6llo++) {
    $_JffQ8 = $_QOLIl[$_Q6llo];
    $_Q6i6i = strpos($_JffQ8, "#");
    if($_Q6i6i !== false && $_Q6i6i == 0) $_JffQ8="";
    if(stripos($_JffQ8, "mailto:") !== false)
      $_JffQ8 = substr($_JffQ8, 7);
    if( strtolower($_JffQ8) == strtolower($_QOLCo[$_Q6llo]) )
      $_I11oJ = $_JffQ8;
       else
         if(stripos($_JffQ8, $_QOLCo[$_Q6llo]) === false && stripos(_OBLDR($_JffQ8), $_QOLCo[$_Q6llo]) === false)
           $_I11oJ = $_QOLCo[$_Q6llo]. ' ' . $_JffQ8;
           else
            if( !_OPAOJ('info@'. _OBLCO($_QOLCo[$_Q6llo])) && !_OPAOJ('info@'. _OBLAR(_OBLCO($_QOLCo[$_Q6llo]))) )
              $_I11oJ = $_QOLCo[$_Q6llo]. ' ' . $_JffQ8;
              else
               $_I11oJ = $_JffQ8;

    for($_QL8Q8=0; $_QL8Q8<2; $_QL8Q8++){
      $_Jffo0 = $_QOLIl[$_Q6llo];
      if($_QL8Q8 == 1)
        $_Jffo0 = _OBLDR($_Jffo0);
      $_Jf8O1 = '/(<a.*?href\=[\"\']' . preg_quote($_Jffo0, '/') . '[\"\'][\/>].*?<\/a>)/i';
      if(preg_match_all($_Jf8O1, $_JfJLt, $_JjOII, PREG_PATTERN_ORDER) && isset($_JjOII[0][0])){
        $_JfJLt = preg_replace("/".preg_quote($_JjOII[0][0], '/')."/", $_I11oJ, $_JfJLt, 1);
      }
    }
  }

  // href, dup link text
  preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $_JfJLt, $_JjOII, PREG_SET_ORDER);
  for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     $_JfJLt = str_replace('">' .  _OBLDR($_JjOII[$_Q6llo][2]) . '</a>', '"></a>', $_JfJLt);
     $_JfJLt = str_replace('">' .  _OBLCO($_JjOII[$_Q6llo][2]) . '</a>', '"></a>', $_JfJLt);
  }

  $_JfJLt = strip_tags_keep_links( $_JfJLt );

  $_JfJLt = str_replace("**b**", $_Q6JJJ, $_JfJLt);
  $_JfJLt = str_replace($_Q6JJJ.$_Q6JJJ, $_Q6JJJ, $_JfJLt);
  $_JfJLt = str_replace("</a>", " ", $_JfJLt);
  $_JfJLt = str_replace("<a \"", "", $_JfJLt);
  $_JfJLt = str_replace("<a >", "", $_JfJLt); // no "?
  $_JfJLt = str_replace("<a", "", $_JfJLt); // no "?
  $_JfJLt = str_replace('">', " ", $_JfJLt);
  $_JfJLt = str_replace('" >', " ", $_JfJLt); // FF bug

  $_JfJLt = str_replace("&nbsp;", " ", $_JfJLt); // &nbsp; in space
  $_JfJLt = str_replace(chr(194), " ", $_JfJLt); // &nbsp; in space
  $_JfJLt = str_replace(chr(160), " ", $_JfJLt); // &nbsp; in space

  while(strpos($_JfJLt, "  ") !== false)
     $_JfJLt = str_replace("  ", " ", $_JfJLt);

  #2b
  $_JfJLt = str_replace("**2b**", $_Q6JJJ.$_Q6JJJ, $_JfJLt);
  #3b
  $_JfJLt = str_replace("**3b**", $_Q6JJJ.$_Q6JJJ.$_Q6JJJ, $_JfJLt);
  return trim( $_JfJLt );
}

if(!function_exists('html_entity_decode')) {
  function html_entity_decode($_J1lio, $_Jftj0, $charset)
  {
     html_entity_decode_internal($_J1lio, $_Jftj0, $charset);
  }
}

$_JftOi = "www.superscripte.de";

function html_entity_decode_internal($_J1lio, $_Jftj0, $charset)
{
    // replace numeric entities
    //$_J1lio = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $_J1lio);
    //$_J1lio = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $_J1lio);

    $_J1lio = preg_replace_callback('~&#x([0-9a-f]+);~i',
           "html_entity_decode_internal_preg_replace_callback1", $_J1lio);

    $_J1lio = preg_replace_callback('~&#([0-9]+);~',
           'html_entity_decode_internal_preg_replace_callback2', $_J1lio);


    // replace literal entities
    $_JfO8j = get_html_translation_table(HTML_ENTITIES);
    $_JfO8j[" "] = '&nbsp;';
    $_JfO8j = array_flip($_JfO8j);
    return strtr($_J1lio, $_JfO8j);
}

function html_entity_decode_internal_preg_replace_callback1($_JItfQ){
  return chr(hexdec($_JItfQ[1]));
}

function html_entity_decode_internal_preg_replace_callback2($_JItfQ){
  return chr($_JItfQ[1]);
}

function unhtmlentities($_J1lio, $charset) {
  return html_entity_decode_full($_J1lio, ENT_COMPAT, strtoupper($charset));
}


function html_entity_decode_utf8($_J1lio)
{
    static $_JfO8j;

    // replace numeric entities
    //$_J1lio = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $_J1lio);
    //$_J1lio = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $_J1lio);

    $_J1lio = preg_replace_callback('~&#x([0-9a-f]+);~i',
            'html_entity_decode_utf8_preg_replace_callback1', $_J1lio);
    $_J1lio = preg_replace_callback('~&#([0-9]+);~',
            'html_entity_decode_utf8_preg_replace_callback2', $_J1lio);

    // replace literal entities
    if (!isset($_JfO8j))
    {
        $_JfO8j = array();

        foreach (get_html_translation_table(HTML_ENTITIES) as $_Q80Qf=>$key)
            $_JfO8j[$key] = utf8_encode($_Q80Qf);
    }

    return strtr($_J1lio, $_JfO8j);
}

function html_entity_decode_utf8_preg_replace_callback1($_JItfQ){
 return code2utf(hexdec($_JItfQ[1]));
}

function html_entity_decode_utf8_preg_replace_callback2($_JItfQ){
 return code2utf($_JItfQ[1]);;
}

$_JfOii = "codecheck";

// Returns the utf string corresponding to the unicode value (from php.net, courtesy - romans@void.lv)
function code2utf($_JfoO6)
{
    if ($_JfoO6 < 128) return chr($_JfoO6);
    if ($_JfoO6 < 2048) return chr(($_JfoO6 >> 6) + 192) . chr(($_JfoO6 & 63) + 128);
    if ($_JfoO6 < 65536) return chr(($_JfoO6 >> 12) + 224) . chr((($_JfoO6 >> 6) & 63) + 128) . chr(($_JfoO6 & 63) + 128);
    if ($_JfoO6 < 2097152) return chr(($_JfoO6 >> 18) + 240) . chr((($_JfoO6 >> 12) & 63) + 128) . chr((($_JfoO6 >> 6) & 63) + 128) . chr(($_JfoO6 & 63) + 128);
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


function html_entity_decode_full($_J1lio, $_Jfoii = ENT_COMPAT, $charset = 'ISO-8859-1') {
  /* Regular Expression:
   * Named HTML entities start with an ampersand, have a single alpha character,
   * then 1-7 alphanumeric characters and finally a semicolon. (e.g. &gt;
   * &there4; &Upsilon;) They are case-sensitive (&egrave; is not &Egrave;)
   */
  if($charset == "UTF-8" || $charset == "utf-8" )
  return html_entity_decode_utf8(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $_J1lio));
  else
  return html_entity_decode_internal(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $_J1lio), $_Jfoii, $charset);
}

/* Swap HTML named entities with numeric entities
 * This contains the full HTML 4 Recommendation listing of entities, so the default to discard
 * entities not in the table is generally good. Pass false to the second argument to return
 * the faulty entity unmodified, if you're ill or something.
 */
function convert_entity($_JItfQ, $_JfCtO = true) {
  static $_Jfi0i = array('quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;','Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;','lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;','rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;','fnof' => '&#402;','Alpha' => '&#913;','Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;','Theta' => '&#920;','Iota' => '&#921;','Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;','Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;','Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;','Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;','epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;','theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;','omicron' => '&#959;','pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;','psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;','Prime' => '&#8243;','oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;','larr' => '&#8592;','uarr' => '&#8593;','rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;','rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;','forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;','isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;','sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;','prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;','cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;','there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;','ge' => '&#8805;','sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;','sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;','spades' => '&#9824;','clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;','curren' => '&#164;','yen' => '&#165;','brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;','not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;','deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;','acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;','sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;','frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;','Acirc' => '&#194;','Atilde' => '&#195;','Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;','Euml' => '&#203;','Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;','Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;','Uuml' => '&#220;','Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;','auml' => '&#228;','aring' => '&#229;','aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;','igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;','iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;','ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;','oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;','uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;'
                        );
  if (isset($_Jfi0i[$_JItfQ[1]])) return $_Jfi0i[$_JItfQ[1]];
  // else
  return $_JfCtO ? '' : $_JItfQ[0];
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
function UTF8ToEntities ($_J1lio) {
     /* note: apply htmlspecialchars if desired /before/ applying this function
     /* Only do the slow convert if there are 8-bit characters */
     /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
     if (! preg_match("/[\200-\237]/", $_J1lio) and ! preg_match("/[\241-\377]/", $_J1lio))
         return $_J1lio;

    // reject too-short sequences
     $_J1lio = preg_replace("/[\302-\375]([\001-\177])/", "&#65533;\\1", $_J1lio);
    $_J1lio = preg_replace("/[\340-\375].([\001-\177])/", "&#65533;\\1", $_J1lio);
    $_J1lio = preg_replace("/[\360-\375]..([\001-\177])/", "&#65533;\\1", $_J1lio);
    $_J1lio = preg_replace("/[\370-\375]...([\001-\177])/", "&#65533;\\1", $_J1lio);
    $_J1lio = preg_replace("/[\374-\375]....([\001-\177])/", "&#65533;\\1", $_J1lio);

    // reject illegal bytes & sequences
         // 2-byte characters in ASCII range
     $_J1lio = preg_replace("/[\300-\301]./", "&#65533;", $_J1lio);
         // 4-byte illegal codepoints (RFC 3629)
     $_J1lio = preg_replace("/\364[\220-\277]../", "&#65533;", $_J1lio);
         // 4-byte illegal codepoints (RFC 3629)
     $_J1lio = preg_replace("/[\365-\367].../", "&#65533;", $_J1lio);
         // 5-byte illegal codepoints (RFC 3629)
     $_J1lio = preg_replace("/[\370-\373]..../", "&#65533;", $_J1lio);
         // 6-byte illegal codepoints (RFC 3629)
     $_J1lio = preg_replace("/[\374-\375]...../", "&#65533;", $_J1lio);
         // undefined bytes
     $_J1lio = preg_replace("/[\376-\377]/", "&#65533;", $_J1lio);

    // reject consecutive start-bytes
     $_J1lio = preg_replace("/[\302-\364]{2,}/", "&#65533;", $_J1lio);

    // decode four byte unicode characters
/*     $_J1lio = preg_replace(
         "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/e",
         "'&#'.((ord('\\1')&7)<<18 | (ord('\\2')&63)<<12 |" .
         " (ord('\\3')&63)<<6 | (ord('\\4')&63)).';'",
     $_J1lio); */

     $_J1lio = preg_replace_callback(
         "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback1',
     $_J1lio);

    // decode three byte unicode characters
    /* $_J1lio = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
     "'&#'.((ord('\\1')&15)<<12 | (ord('\\2')&63)<<6 | (ord('\\3')&63)).';'",
     $_J1lio); */

     $_J1lio = preg_replace_callback("/([\340-\357])([\200-\277])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback2',
     $_J1lio);

    // decode two byte unicode characters
     /* $_J1lio = preg_replace("/([\300-\337])([\200-\277])/e",
     "'&#'.((ord('\\1')&31)<<6 | (ord('\\2')&63)).';'",
     $_J1lio); */

     $_J1lio = preg_replace_callback("/([\300-\337])([\200-\277])/",
         'UTF8ToEntities_preg_replace_callback3',
     $_J1lio);

    // reject leftover continuation bytes
     $_J1lio = preg_replace("/[\200-\277]/", "&#65533;", $_J1lio);

    return $_J1lio;
 }

 function UTF8ToEntities_preg_replace_callback1($_JItfQ){
  return '&#'.((ord($_JItfQ[1])&7)<<18 | (ord($_JItfQ[2])&63)<<12 |
          (ord($_JItfQ[3])&63)<<6 | (ord($_JItfQ[4])&63)).';';
 }

 function UTF8ToEntities_preg_replace_callback2($_JItfQ){
  return '&#'.((ord($_JItfQ[1])&15)<<12 | (ord($_JItfQ[2])&63)<<6 | (ord($_JItfQ[3])&63)).';';
 }

 function UTF8ToEntities_preg_replace_callback3($_JItfQ){
  return '&#'.((ord($_JItfQ[1])&31)<<6 | (ord($_JItfQ[2])&63)).';';
 }

}

?>

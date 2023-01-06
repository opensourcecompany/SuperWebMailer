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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];


  function _L1PCA($_j1CoJ){
    global $INTERFACE_LANGUAGE, $_QLo06;
    if($INTERFACE_LANGUAGE != "de")
      return htmlspecialchars($_j1CoJ, ENT_COMPAT, $_QLo06);
    $_IoLOO = explode(";", 'Smileys & Emotion=Smileys & Emotionen;Food & Drink=Essen & Trinken;People & Body=Personen & Körper;Animals & Nature=Natur & Tiere;Food=Essen & Getränke;Travel & Places=Reisen & Orte;Activities=Aktivitäten;Objects=Objekte;Symbols=Symbole;Flags=Flaggen');
    for($_Qli6J=0; $_Qli6J<$_IoLOO; $_Qli6J++)
      if(stripos($_IoLOO[$_Qli6J], $_j1CoJ) === 0){
        $_I016j = utf8_encode( substr($_IoLOO[$_Qli6J], strpos($_IoLOO[$_Qli6J], '=') + 1) );
        return htmlspecialchars($_I016j, ENT_COMPAT, $_QLo06);
      }
    return htmlspecialchars($_j1CoJ, ENT_COMPAT, $_QLo06);  
  }
    
  $_Itfj8 = "";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "Emojis", $_Itfj8, 'campaignedit6', 'browse_emojis.htm');

    
  if(isset($_POST["_FORMNAME"]) && $_POST["_FORMNAME"] != "null"){
    $_QLJfI = str_replace("'_FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QLJfI);
    $_QLJfI = str_replace("'FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QLJfI);
  }  

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null") {
      $_QLJfI = str_replace('.FORMFIELD', ".".$_POST["_FORMFIELD"], $_QLJfI);
      $_QLJfI = str_replace('._FORMFIELD', ".".$_POST["_FORMFIELD"], $_QLJfI);
      $_QLJfI = str_replace("'_FORMFIELD'", "'".$_POST["_FORMFIELD"]."'", $_QLJfI);
    }

  
   $_QlCtl = InstallPath . "js/emoji_131.json";
   $_j1iQi = json_decode(file_get_contents($_QlCtl), true);

   /*
   
     Array ( 
     
     [0] => 
     Array ( [emoji] =>  [description] => grinning face [category] => Smileys & Emotion 
     [aliases] => Array ( [0] => grinning ) 
     [tags] => Array ( [0] => smile [1] => happy ) 
     [unicode_version] => 6.1 
     [ios_version] => 6.0 ) 
     
     [1] => Array ( [emoji] =>  [description] => grinning face with big eyes [category] => Smileys & Emotion [aliases] => Array ( [0] => smiley ) [tags] => Array ( [0] => happy [1] => joy [2] => haha ) [unicode_version] => 6.0 [ios_version] => 6.0 )   
   
   */
   
   $_j1itI = array();
   for($_Qli6J=0; $_Qli6J<count($_j1iQi); $_Qli6J++){
     $_j1iLI = $_j1iQi[$_Qli6J];
     if(version_compare($_j1iLI["unicode_version"], "12.99") > -1) continue;
     
     if(!isset($_j1itI[$_j1iLI["category"]]))
       $_j1itI[$_j1iLI["category"]] = array();
     $_j1iLI["keywords"] = join(", ", $_j1iLI["tags"]);
     if(count($_j1iLI["aliases"])){
       $_j1iLI["keywords"] .= (!empty($_j1iLI["keywords"]) ? ", " : "") . join(", ", $_j1iLI["aliases"]);
     }  
     unset($_j1iLI["aliases"]);  
     unset($_j1iLI["tags"]);  
     unset($_j1iLI["unicode_version"]);  
     unset($_j1iLI["ios_version"]);  
     $_j1itI[$_j1iLI["category"]][] = $_j1iLI;
   }

   $_j1Lil = _L81DB($_QLJfI, "<EMOJI_GROUP>");
   $_j1lCo = _L81DB($_j1Lil, "<EMOJI_ITEM>");
   
   $_QLoli = "";
   
   foreach($_j1itI as $_j1liQ => $_jQ08L){
     $id = $_j1liQ;
     $id = str_replace(" ", "_", $id);
     $id = str_replace("&", "", $id);
     $_Ql0fO = $_j1Lil; 
     $_Ql0fO = str_replace("GROUPID", $id, $_Ql0fO);
     $_Ql0fO = _L81BJ($_Ql0fO, "<GROUPNAME>", "", _L1PCA($_j1liQ));
     $_jQ0OL = "";
     for($_Qli6J=0; $_Qli6J<count($_jQ08L); $_Qli6J++){
       $_jQ0OL .= $_j1lCo;
       $_jQ0OL = _L81BJ($_jQ0OL, "<emoji>", "", $_jQ08L[$_Qli6J]["emoji"]);
       $_jQ0OL = str_replace("[keywords]", $_jQ08L[$_Qli6J]["keywords"], $_jQ0OL);
     }
     $_Ql0fO = _L81BJ($_Ql0fO, "<EMOJI_ITEM>", "", $_jQ0OL);
     $_QLoli .= $_Ql0fO;
   }
   
   $_QLJfI = _L81BJ($_QLJfI, "<EMOJI_GROUP>", "", $_QLoli);
   
   SetHTMLHeaders($_QLo06);
   print $_QLJfI;

?>

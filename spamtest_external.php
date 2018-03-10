<?php
  #############################################################################
  #                    SUPERMAILER SPAMTEST SCRIPT                            #
  #     Copyright © 2004-2013 Mirko Boeer Softwareentwicklungen Leipzig       #
  #                 http://www.supermailer.de/                                #
  #                                                                           #
  #                 Dieses Script URHEBERRECHTLICH GESCHÜTZT!                 #
  # Es ist NICHT gestattet dieses Script ohne Einverstaendnis des Autors      #
  # weiterzugeben oder in anderen Anwendungen einzusetzen.                    #
  #                                                                           #
  # Systemvoraussetzungen: PHP 4 und Windows/Unix                             #
  #############################################################################

error_reporting(E_ALL);

# upload verzeichnis ab root mit /, rechte auf 777 setzen
# specify upload from root directory with /, chmod 777!!
$UPLOADDIR = "/srv/www/vhosts/ADOMAIN.DE/httpdocs/SpamTest/temp/";

####################### Ab hier nichts mehr aendern ###########################
if (move_uploaded_file($_FILES ['SpamTestFile']['tmp_name'], $UPLOADDIR.$_FILES['SpamTestFile']['name'])){
    $_jt8LJ = $UPLOADDIR.$_FILES ['SpamTestFile']['name'];
    chmod($_jt8LJ, 0777);
    $_I00tC = $_FILES ['SpamTestFile']['size'];
    
    $_6f6oo = ('spamassassin -t -L <'.$_jt8LJ.' >'.$_jt8LJ.'.out');
    system ($_6f6oo);
    
    $_QCioi = fopen("$_jt8LJ.out", "r");
    if ($_QCioi == FALSE) {
      print "SPAMCHECKERROR "."Can't open result file.";
      exit;
    }
    fseek($_QCioi, $_I00tC, SEEK_SET);

    $_QJCJi = fread($_QCioi, filesize("$_jt8LJ.out"));
    
    $_I1t0l=strpos($_QJCJi, "Content analysis details:");
    if (is_string ($_I1t0l) && !$_I1t0l) 
      $_QJCJi = trim($_QJCJi);
    else
      $_QJCJi = trim(substr($_QJCJi, $_I1t0l, strlen($_QJCJi)));
   
    print $_QJCJi;
    fclose($_QCioi);
    unlink($_jt8LJ);
    unlink($_jt8LJ.".out");
  }
  else
  print "SPAMCHECKERROR ".$_FILES ['SpamTestFile']['tmp_name']." -> ".$UPLOADDIR.$_FILES ['SpamTestFile']['name']."\n";
?>

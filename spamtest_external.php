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
    $_JfIIf = $UPLOADDIR.$_FILES ['SpamTestFile']['name'];
    chmod($_JfIIf, 0777);
    $_It18j = $_FILES ['SpamTestFile']['size'];
    
    $_fl801 = ('spamassassin -t -L <'.$_JfIIf.' >'.$_JfIIf.'.out');
    system ($_fl801);
    
    $_I60fo = fopen("$_JfIIf.out", "r");
    if ($_I60fo == FALSE) {
      print "SPAMCHECKERROR "."Can't open result file.";
      exit;
    }
    fseek($_I60fo, $_It18j, SEEK_SET);

    $_QLJfI = fread($_I60fo, filesize("$_JfIIf.out"));
    
    $_IOO6C=strpos($_QLJfI, "Content analysis details:");
    if (is_string ($_IOO6C) && !$_IOO6C) 
      $_QLJfI = trim($_QLJfI);
    else
      $_QLJfI = trim(substr($_QLJfI, $_IOO6C, strlen($_QLJfI)));
   
    print $_QLJfI;
    fclose($_I60fo);
    unlink($_JfIIf);
    unlink($_JfIIf.".out");
  }
  else
  print "SPAMCHECKERROR ".$_FILES ['SpamTestFile']['tmp_name']." -> ".$UPLOADDIR.$_FILES ['SpamTestFile']['name']."\n";
?>

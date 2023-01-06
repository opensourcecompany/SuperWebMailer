<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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

# requires PHP > 5.4

   require_once 'html2pdf/vendor/autoload.php';

   use Spipu\Html2Pdf\Html2Pdf;
   use Spipu\Html2Pdf\Exception\Html2PdfException;
   use Spipu\Html2Pdf\Exception\ExceptionFormatter;

   // https://github.com/spipu/html2pdf/blob/master/doc/page.md

   function _J0BCL($_QLoli, $_fCQiO, $lang, $_jt6tQ, $_ILi8o, $_fCQil, $_fCI1o){
     $_fCIfi = new Html2Pdf('P', 'A4', $lang, true, "UTF-8", array(12.7, 12.7, 12.7, 12.7));
     $_fCIfi->pdf->SetTitle($_jt6tQ);
     $_fCIfi->pdf->SetSubject($_ILi8o);
     $_fCIfi->pdf->SetAuthor($_fCQil);
     $_fCIfi->pdf->SetCreator($_fCI1o);
     $_fCIfi->setDefaultFont('arial');

     $_fCIfi->writeHTML($_QLoli);
     $_fCIfi->output($_fCQiO, "D");
   }

   function _J0C1A($_QLoli, $_fCQiO, $lang, $_jt6tQ, $_ILi8o, $_fCQil, $_fCI1o){
     $_fCIfi = new Html2Pdf('P', 'A4', $lang, true, "UTF-8", array(12.7, 12.7, 12.7, 12.7));
     $_fCIfi->pdf->SetTitle($_jt6tQ);
     $_fCIfi->pdf->SetSubject($_ILi8o);
     $_fCIfi->pdf->SetAuthor($_fCQil);
     $_fCIfi->pdf->SetCreator($_fCI1o);

     $_fCIfi->writeHTML($_QLoli);
     $_fCIfi->output($_fCQiO);
   }

   function _J0C8O($_QLoli, $_fCjJ0, $lang, $_jt6tQ, $_ILi8o, $_fCQil, $_fCI1o){
     $_fCIfi = new Html2Pdf('P', 'A4', $lang, true, "UTF-8", array(12.7, 12.7, 12.7, 12.7));
     $_fCIfi->pdf->SetTitle($_jt6tQ);
     $_fCIfi->pdf->SetSubject($_ILi8o);
     $_fCIfi->pdf->SetAuthor($_fCQil);
     $_fCIfi->pdf->SetCreator($_fCI1o);

     $_fCIfi->writeHTML($_QLoli);
     return $_fCIfi->output($_fCjJ0, "S");
   }

   function _J0DQL($_QLoli, $_fCQiO, $lang, $_jt6tQ, $_ILi8o, $_fCQil, $_fCI1o){
     $_fCIfi = new Html2Pdf('P', 'A4', $lang, true, "UTF-8", array(12.7, 12.7, 12.7, 12.7));
     $_fCIfi->pdf->SetTitle($_jt6tQ);
     $_fCIfi->pdf->SetSubject($_ILi8o);
     $_fCIfi->pdf->SetAuthor($_fCQil);
     $_fCIfi->pdf->SetCreator($_fCI1o);

     $_fCIfi->writeHTML($_QLoli);
     return $_fCIfi->output($_fCQiO, "E");
   }

?>

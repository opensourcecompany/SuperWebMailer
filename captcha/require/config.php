<?php // ------------------------------------------------------------------  //
//  Projekt:   eBiene CaptchaImage Maker 0.3                                 //
//  Download:  http://www.eBiene.de                                          //
//  Autor:     Sergej M�ller                                                 //
//  Kontakt:   smueller AT eBiene DOT de                                     //
//                                                                           //
//  Dateiname: require/config.php                                            //
//  AEnderung: 28. Februar 2007                                              //
//                                                                           //
//  Dieses Programm ist freie Software. Sie k�nnen es unter den Bedingungen  //
//  der GNU General Public License, wie von der Free Software Foundation     //
//  ver�ffentlicht, weitergeben und/oder modifizieren, entweder gem��        //
//  Version 2 der Lizenz oder (nach Ihrer Option) jeder sp�teren Version.    //
//  GNU General Public License ist unter http://www.gnu.de zu finden.        //
//  -----------------------------------------------------------------------  //


###############################################################################
#     ACHTUNG: Folgende Werte sollten einmalig richtig eingestellt werden!    #
#     WICHTIG: Mehr Informationen zu den Einstellungen in Install.html!       #
###############################################################################

/* Breite der Captcha-Grafik */
define('CAPTCHA_IMAGE_WIDTH', 180);

/* H�he der Captcha-Grafik */
define('CAPTCHA_IMAGE_HEIGHT', 50);

/* Typ der Captcha-Grafik */
define('CAPTCHA_IMAGE_TYPE', 'jpeg');


/* Quelle des Strings */
define('CAPTCHA_STRING_SOURCE', 'list');

/* L�nge des Strings */
define('CAPTCHA_STRING_LENGTH', 15);

/* Gr��e des Strings */
define('CAPTCHA_STRING_SIZE', 10);


/* Intensit�t des Hintergrunds */
define('CAPTCHA_BACKGROUND_INTENSITY', 'rand');

/* Farbe des Hintergrunds */
define('CAPTCHA_BACKGROUND_COLOR', 0);


###############################################################################
#     ACHTUNG: Folgende Werte duerfen NICHT geaendert werden!                 #
###############################################################################

/* eBiene CaptchaImage Maker-Version */
define('CAPTCHA_IMAGE_VERSION', '0.3');

/* Existenz der GDLIB-Bibliothek */
define('PHP_GDLIB_EXTENSION', extension_loaded('gd'));

/* Alle Fehler zeigen */
error_reporting(0);
ini_set("display_errors", 0);
?>

<?php // ------------------------------------------------------------------  //
//  Projekt:   eBiene CaptchaImage Maker 0.3                                 //
//  Download:  http://www.eBiene.de                                          //
//  Autor:     Sergej Müller                                                 //
//  Kontakt:   smueller AT eBiene DOT de                                     //
//                                                                           //
//  Dateiname: require/captcha_image.php                                     //
//  AEnderung: 28. Februar 2007                                              //
//                                                                           //
//  Dieses Programm ist freie Software. Sie können es unter den Bedingungen  //
//  der GNU General Public License, wie von der Free Software Foundation     //
//  veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß        //
//  Version 2 der Lizenz oder (nach Ihrer Option) jeder späteren Version.    //
//  GNU General Public License ist unter http://www.gnu.de zu finden.        //
//  -----------------------------------------------------------------------  //


/* Konfiguration einbinden */
require_once('config.php');

/* Array mit Einstellungen */
$config = array(
                'image'      => array(
                                      'width'  => (isset($_GET['width']) === true && empty($_GET['width']) === false) ? $_GET['width'] : CAPTCHA_IMAGE_WIDTH,
                                      'height' => (isset($_GET['height']) === true && empty($_GET['height']) === false) ? $_GET['height'] : CAPTCHA_IMAGE_HEIGHT,
                                      'type'   => (isset($_GET['type']) === true && empty($_GET['type']) === false) ? $_GET['type'] : CAPTCHA_IMAGE_TYPE
                                     ),
                'string'     => array(
                                      'source' => (isset($_GET['source']) === true && empty($_GET['source']) === false) ? $_GET['source'] : CAPTCHA_STRING_SOURCE,
                                      'length' => (isset($_GET['length']) === true && empty($_GET['length']) === false) ? $_GET['length'] : CAPTCHA_STRING_LENGTH,
                                      'size' => (isset($_GET['size']) === true && empty($_GET['size']) === false) ? $_GET['size'] : CAPTCHA_STRING_SIZE
                                     ),
                'background' => array(
                                      'intensity' => (isset($_GET['intensity']) === true && empty($_GET['intensity']) === false) ? $_GET['intensity'] : CAPTCHA_BACKGROUND_INTENSITY,
                                      'color' => (isset($_GET['color']) === true && empty($_GET['color']) === false) ? $_GET['color'] : CAPTCHA_BACKGROUND_COLOR
                                     )
               );

/* Captcha-Words einbinden */
if ($config['string']['source'] == 'list') {
  require 'words.php';
}

/* IE Hack? */
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

/* Session starten */
@session_start();

/* Klassen einbinden */
require 'crypt.class.php';
require 'captcha.class.php';

/* Crypt-Klasse initialisieren */
$GLOBALS['crypt_class'] = new crypt_class();

/* CaptchaImage-Klasse initialisieren */
$GLOBALS['captcha_image_class'] = new captcha_image_class(
                                                          $config['image'],
                                                          $config['string'],
                                                          $config['background']
                                                         );

/* Captcha-Image generieren */
$GLOBALS['captcha_image_class']->create_captcha_image();

/* Captcha-String speichern */
$_SESSION['captcha_string'] = $GLOBALS['crypt_class']->base64_encode_advanced($GLOBALS['captcha_image_class']->captcha_string);
?>

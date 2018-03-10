<?php // ------------------------------------------------------------------  //
//  Projekt:   eBiene CaptchaImage Maker 0.3                                 //
//  Download:  http://www.eBiene.de                                          //
//  Autor:     Sergej Müller                                                 //
//  Kontakt:   smueller AT eBiene DOT de                                     //
//                                                                           //
//  Dateiname: index.php                                                     //
//  AEnderung: 28. Februar 2007                                              //
//                                                                           //
//  Dieses Programm ist freie Software. Sie können es unter den Bedingungen  //
//  der GNU General Public License, wie von der Free Software Foundation     //
//  veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß        //
//  Version 2 der Lizenz oder (nach Ihrer Option) jeder späteren Version.    //
//  GNU General Public License ist unter http://www.gnu.de zu finden.        //
//  -----------------------------------------------------------------------  //


/* Session starten */
session_start();

/* Klassen einbinden */
require 'require/config.php';
require 'require/crypt.class.php';

/* Crypt-Klasse initialisieren */
$GLOBALS['crypt_class'] = new crypt_class();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  	<title>eBiene Captcha-Image Maker | Version <?php echo CAPTCHA_IMAGE_VERSION ?></title>
  </head>
  
  <body>
    <?php if (isset($_POST['user_captcha_string']) === true) { ?>
      
      <p>Eingegebener String: <?php echo $_POST['user_captcha_string'] ?></p>
      <p>Richtiger Captcha-String: <?php echo $GLOBALS['crypt_class']->base64_decode_advanced($_SESSION['captcha_string']) ?></p>
      
    <?php } else { ?>
      
      <form method="post">
        <p><img src="require/image.php?<?php echo md5(uniqid(rand(), true)) ?>"></p>
        <p><input type="text" name="user_captcha_string"><input type="submit" value="Go!"></p>
      </form>
      
    <?php } ?> 
  </body>
</html>

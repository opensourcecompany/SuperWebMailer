<?php // ------------------------------------------------------------------  //
//  Projekt:   eBiene CaptchaImage Maker 0.3                                 //
//  Download:  http://www.eBiene.de                                          //
//  Autor:     Sergej Müller                                                 //
//  Kontakt:   smueller AT eBiene DOT de                                     //
//                                                                           //
//  Dateiname: require/captcha_image.class.php                               //
//  AEnderung: 28. Februar 2007                                              //
//                                                                           //
//  Dieses Programm ist freie Software. Sie können es unter den Bedingungen  //
//  der GNU General Public License, wie von der Free Software Foundation     //
//  veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß        //
//  Version 2 der Lizenz oder (nach Ihrer Option) jeder späteren Version.    //
//  GNU General Public License ist unter http://www.gnu.de zu finden.        //
//  -----------------------------------------------------------------------  //

class captcha_image_class {


  /* Captcha-String */
  var $captcha_string;

  /* Laenge des Strings */
  var $captcha_string_length;

  /* Typ der Schrift */
  var $captcha_string_size;

  /* Breite der Grafik */
  var $captcha_image_width;

  /* Hoehe der Grafik */
  var $captcha_image_height;

  /* Typ der Grafik */
  var $captcha_image_type;

  /* Captcha-Image */
  var $captcha_image;

  /* Breite des Zeichens */
  var $image_font_width;

  /* Hoehe des Zeichens */
  var $image_font_height;

  /* Intensitaet des Hintergrunds */
  var $captcha_background_intensity;

  /* Farbe des Hintergrunds */
  var $captcha_background_color;


  /*
  * captcha_image_class
  *
  * Legt Eigenschaften des Captchas fest
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    11.11.2006
  * @change   24.02.2007
  * @access		privat
  * @param    array  $image_settings       Einstellungen der Grafik
  * @param    array  $string_settings      Einstellungen des Strings
  * @param    array  $background_settings  Einstellungen des Hintergrunds
  */

  function captcha_image_class($image_settings, $string_settings, $background_settings) {
    /* Breite der Grafik */
    $this->captcha_image_width = $image_settings['width'];

    /* Höhe der Grafik */
    $this->captcha_image_height = $image_settings['height'];

    /* Typ der Grafik */
    $this->captcha_image_type = $image_settings['type'];


    /* String oder Quelle des Strings */
    $this->captcha_string = $string_settings['source'];

    /* Länge des Strings */
    $this->captcha_string_length = $string_settings['length'];

    /* Größe der Schrift */
    $this->captcha_string_size = $string_settings['size'];


    /* Intensität des Hintergrunds */
    $this->captcha_background_intensity = $background_settings['intensity'];

    /* Farbe des Hintergrunds */
    $this->captcha_background_color = $background_settings['color'];
  }


  /*
  * create_captcha_image
  *
  * Erzeugt eine Captcha-Grafik
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    15.06.2004
  * @change   24.02.2007
  * @access		privat
  */

  function create_captcha_image() {
    /* Parameter pruefen */
    $this->check_captcha_params();

    /* Grafik erzeugen */
    $this->open_captcha_image();

    /* Hintergrund generieren */
    $this->create_captcha_background();

    /* Vordergrund generieren */
    $this->create_captcha_foreground();

    /* Header senden */
    $this->send_image_header();

    /* Grafik ausgeben */
    $this->close_captcha_image();
  }


  /*
  * check_captcha_params
  *
  * Prueft Parameter der Funktion auf Richtigkeit
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    15.06.2004
  * @change   25.02.2007
  * @access		privat
  */

  function check_captcha_params() {
    /* String-Quelle festlegen */
    if (empty($this->captcha_string) === true || $this->captcha_string == 'rand') {
      $this->captcha_string = md5(uniqid(rand(), true));
    } else {
      $this->captcha_string = $GLOBALS['captcha_words_array'][rand(0, count($GLOBALS['captcha_words_array']) - 1)];
    }

    /* Laenge des Strings */
    if ($this->captcha_string_length > 0) {
      $this->captcha_string = substr($this->captcha_string, 0, $this->captcha_string_length);
    } else if (empty($this->captcha_string_length) === true) {
      $this->captcha_string_length = strlen($this->captcha_string);
    }

    /* Typo des Strings */
    if (empty($this->captcha_string_size) === true) {
      $this->captcha_string_size = 5;
    }

    /* Hintergrund-Intensität festlegen */
    if (empty($this->captcha_background_intensity) === true || $this->captcha_background_intensity == 'default') {
      $this->captcha_background_intensity = 125;
    } else if ($this->captcha_background_intensity == 'rand') {
      $this->captcha_background_intensity = rand(1, 250);
    }

    /* Breite eines Zeichens */
    $this->image_font_width = imagefontwidth($this->captcha_string_size) + 2;

    /* Hoehe eines Zeichens */
    $this->image_font_height = imagefontheight($this->captcha_string_size) + 2;

    /* Breite der Grafik */
    if (empty($this->captcha_image_width) === true) {
      $this->captcha_image_width = $this->image_font_width * $this->captcha_string_length + 40;
    }

    /* Hoehe der Grafik */
    if (empty($this->captcha_image_height) === true) {
      $this->captcha_image_height = $this->image_font_height + 40;
    }

    /* JPG in JPEG umwandeln */
    if ($this->captcha_image_type == 'jpg') {
      $this->captcha_image_type = 'jpeg';
    }

    /* Typ klein schreiben */
    $this->captcha_image_type = strtolower($this->captcha_image_type);

    /* Formatunterstützung prüfen */
    if (empty($this->captcha_image_type) === true || ($this->captcha_image_type == 'jpeg' && $this->check_type_support() === false)) {
      $this->captcha_image_type = 'png';
    }
    if ($this->captcha_image_type == 'png' && $this->check_type_support() === false) {
      die('Format "' .$this->captcha_image_type. '" wird von GDlib nicht unterst&uuml;tzt!');
    }

    /* Dateiendung prüfen */
    if (in_array($this->captcha_image_type, array('jpeg', 'png')) === false) {
      die('Format "' .$this->captcha_image_type. '" wird von GDlib nicht unterst&uuml;tzt!');
    }

    /* RGB prüfen */
    if (empty($this->captcha_background_color) === false && preg_match('/(#*)([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/', strtolower($this->captcha_background_color), $matches)) {
      $this->captcha_background_color = array(
                                              'r' => hexdec($matches[2]),
                                              'g' => hexdec($matches[3]),
                                              'b' => hexdec($matches[4])
                                             );
    } else {
      $this->captcha_background_color = array(
                                              'r' => intval(rand(225, 255)),
                                              'g' => intval(rand(225, 255)),
                                              'b' => intval(rand(225, 255))
                                             );
    }
  }


  /*
  * check_type_support
  *
  * Prueft, ob Ausgabeformate unterstützt werden
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    15.06.2004
  * @change   11.11.2006
  * @access		privat
  * @return   boolean  true/false  TRUE bei Erfolg
  */

  function check_type_support() {
    /* GDlib installiert? */
    if (!PHP_GDLIB_EXTENSION) {
      die('GDlib ist nicht auf dem Server installiert!');
    }

    /* Support-Array */
    $image_types_array = array('jpeg' => 'JPG Support', 'png' => 'PNG Support');

    /* GD-Infos */
    $gd_info_array = gd_info();

    /* Format supported? */
    if (!$gd_info_array[$image_types_array[$this->captcha_image_type]]) {
      return false;
    }

    return true;
  }


  /*
  * open_captcha_image
  *
  * Startet das Erzeugen der Captcha-Grafik
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    15.06.2004
  * @change   11.11.2006
  * @access		privat
  */

  function open_captcha_image() {
    $this->captcha_image = imagecreatetruecolor($this->captcha_image_width, $this->captcha_image_height);
  }


  /*
  * create_captcha_foreground
  *
  * Erzeugt den Hintergrund der Captcha-Grafik
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    15.06.2004
  * @change   24.02.2007
  * @access		privat
  */

  function create_captcha_background() {
    /* Hintergrund-Farbe */
    $captcha_background_color = imagecolorallocate($this->captcha_image, $this->captcha_background_color['r'], $this->captcha_background_color['g'], $this->captcha_background_color['b']);

    /* Fläche streichen */
    imagefilledrectangle($this->captcha_image, 0, 0, $this->captcha_image_width, $this->captcha_image_height, $captcha_background_color);

    /* Zufallsstrings loopen */
    for ($x = 0; $x < $this->captcha_background_intensity; $x ++) {
      /* Farbe ermitteln */
      $random_string_color = imagecolorallocate($this->captcha_image, intval(rand(164, 254)), intval(rand(164, 254)), intval(rand(164, 254)));

      /* String ermitteln */
      $random_string = chr(intval(rand(65, 122)));

      /* X-Position */
      $x_position = intval(rand(0, $this->captcha_image_width - $this->image_font_width * strlen($random_string)));

      /* Y-Position */
      $y_position = intval(rand(0, $this->captcha_image_height - $this->image_font_height));

      /* String generieren */
      imagestring($this->captcha_image, $this->captcha_string_size, $x_position, $y_position, $random_string, $random_string_color);
    }
  }


  /*
  * create_captcha_foreground
  *
  * Erzeugt den Vordergrund der Captcha-Grafik
  *
  * @package  captcha_image.class.php
  * @author   Sergej Mueller
  * @since    15.06.2004
  * @change   11.11.2006
  * @access		privat
  */

  function create_captcha_foreground() {
    /* X-Position */
    $x_position = intval(rand(0, $this->captcha_image_width - $this->image_font_width * $this->captcha_string_length));

    /* Y-Position */
    $y_position = intval(rand(0, $this->captcha_image_height - $this->image_font_height));

    /* Zeichen loopen */
    for ($x = 0; $x < $this->captcha_string_length; $x ++) {
      /* RGB-Werte */
      $r_value = intval(rand(0, 128));
      $g_value = intval(rand(0, 128));
      $b_value = intval(rand(0, 128));

      /* String- und Schatten-Farbe */
      $captcha_string_color = imagecolorallocate($this->captcha_image, $r_value, $g_value, $b_value);
      $captcha_shadow_color = imagecolorallocate($this->captcha_image, $r_value + 128, $g_value + 128, $b_value + 128);

      /* Schatten-String */
      imagestring($this->captcha_image, $this->captcha_string_size, $x_position + 2, $y_position + 2, $this->captcha_string{$x}, $captcha_shadow_color);

      /* Captcha-String */
      imagestring($this->captcha_image, $this->captcha_string_size, $x_position, $y_position, $this->captcha_string{$x}, $captcha_string_color);

      /* X-Position bewegen */
      $x_position += $this->image_font_width;
    }
  }


  /*
  * send_image_header
  *
  * Sendet den passenden Header an den Browser
  *
  * @package  captcha_image.class.php
  * @author   Sergej Müller
  * @since    15.06.2004
  * @change   07.11.2006
  * @access		privat
  */

  function send_image_header() {
    /* Header senden */
    header('Expires: Mon, 01 Jul 1990 00:00:00 GMT');
    header('Last-Modified: ' .date('D, d M Y H:i:s'). ' GMT');
    header('Expires: 0');
    header('Pragma: no-cache');
    header('Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0');
    header('Content-Type: image/' .$this->captcha_image_type);
  }


  /*
  * close_captcha_image
  *
  * Beendet das Erzeugen der Captcha-Grafik
  *
  * @package  captcha_image.class.php
  * @author   Sergej Müller
  * @since    15.06.2004
  * @change   11.11.2006
  * @access		privat
  */

  function close_captcha_image() {
    /* Grafik generieren */
    switch ($this->captcha_image_type) {
      case 'jpeg':
        imagejpeg($this->captcha_image);
      break;

      case 'png':
        imagepng($this->captcha_image);
      break;
    }

    /* Grafik loeschen */
    imagedestroy($this->captcha_image);
  }
}
?>
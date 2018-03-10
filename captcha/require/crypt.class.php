<?php // ------------------------------------------------------------------  //
//  Projekt:   eBiene CaptchaImage Maker 0.3                                 //
//  Download:  http://www.eBiene.de                                          //
//  Autor:     Sergej Müller                                                 //
//  Kontakt:   smueller AT eBiene DOT de                                     //
//                                                                           //
//  Dateiname: require/crypt.class.php                                       //
//  AEnderung: 28. Februar 2007                                              //
//                                                                           //
//  Dieses Programm ist freie Software. Sie können es unter den Bedingungen  //
//  der GNU General Public License, wie von der Free Software Foundation     //
//  veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß        //
//  Version 2 der Lizenz oder (nach Ihrer Option) jeder späteren Version.    //
//  GNU General Public License ist unter http://www.gnu.de zu finden.        //
//  -----------------------------------------------------------------------  //

class crypt_class {
  
  
  /*
  * base64_encode_advanced
  *
  * Kodiert einen String
  *
  * @package  eBiene CaptchaImage Maker 0.2
  * @author   Sergej Müller
  * @since    28.05.2004
  * @change   11.11.2006
  * @access		privat
  * @param    mixed   $input_data    Der zu kodierende String (Möglicher Typ: integer | string | double)
  * @return   string  $encoded_data  Kodierter String
  */
  
  function base64_encode_advanced($input_data) {
    /* Wert initialisieren */
    $encoded_data = '';
    
    /* Leere Daten */
    if (empty($input_data) === true) {
      trigger_error('Der zu kodierende Wert ist leer!');
      return false;
    }
    
    /* Falscher Typ */
    if (in_array(gettype($input_data), array('integer', 'string', 'double')) === false) {
      trigger_error('Der zu kodierende Wert darf nur vom Typ "integer", "string", "double" sein!');
      return false;
    }
    
    /* Typ festlegen */
    if (is_string($input_data) === false) {
      settype($input_data, 'string');
    }
    
    /* CRC32 festlegen */
    $data_sign = sprintf('%u', crc32($input_data));
    
    /* Länge speichern */
    $data_length = strlen($input_data);
    $sign_length = strlen($data_sign);
    
    /* Zeichen drehen */
    $input_data = strrev($input_data);
    
    /* ASCII verwenden */
    for ($a = 0; $a < max($data_length, $sign_length); $a ++) {
      $encoded_data .= base64_encode(
                                     sprintf(
                                             '%03d.%d',
                                             ($a < $data_length ? ord($input_data{$a}) : 0),
                                             ($a < $sign_length ? $data_sign{$a} : 0)
                                            )
                                    );
    }
    
    /* Formatierung anwenden */
    $encoded_data = str_replace('=', '', $encoded_data);
    
    return $encoded_data;
  }
  
  
  /*
  * base64_decode_advanced
  *
  * Dekodiert einen String
  *
  * @package  eBiene CaptchaImage Maker 0.2
  * @author   Sergej Müller
  * @since    28.05.2004
  * @change   11.11.2006
  * @access		privat
  * @param    string  $encoded_data  Kodierter String
  * @param    string  $desired_type  Typ des Rückgabewertes nach dem Dekodieren [optional] (Möglicher Wert: integer | double | string | array | object)
  * @return   mixed   $decoded_data  Dekodierter String je nach Typ
  */
  
  function base64_decode_advanced($encoded_data, $desired_type = '') {
    /* Werte initialisieren */
    $decoded_data = '';
    $decoded_sign = '';
    
    /* Leere Daten */
    if (empty($encoded_data) === true) {
      trigger_error('Der zu dekodierende Wert ist leer!');
      return false;
    }
    
    /* Typ festlegen */
    if (is_string($encoded_data) === false) {
      settype($encoded_data, 'string');
    }
    
    /* Paare splitten */
    $encoded_array = explode(
                             ':',
                             wordwrap(
                                      $encoded_data,
                                      7,
                                      ':',
                                      7
                                     )
                            );
    
    /* Werte loopen */
    foreach ($encoded_array as $decoded_pair) {
      /* ASCII splitten */
      $ascii_array = explode(
                             '.',
                             base64_decode(
                                           $decoded_pair
                                          )
                            );
      
      /* Werte zwischenspeichern */
      $data_digit = intval($ascii_array[0]);
      $sign_digit = $ascii_array[1];
      
      /* Werte zuweisen */
      $decoded_data .= empty($data_digit) === false ? chr($data_digit) : '';
      $decoded_sign .= $sign_digit;
    }
    
    /* Zeichen drehen */
    $decoded_data = strrev($decoded_data);
    
    /* CRC32 festlegen */
    $data_sign = sprintf('%u', crc32($decoded_data));
    
    /* Länge speichern */
    $data_length = strlen($decoded_data);
    
    /* Signatur vergleichen */
    if (strcmp(str_pad($data_sign, $data_length, '0'), $decoded_sign) !== 0) {
      trigger_error('Der kodierte Wert wurde manipuliert!');
      return false;
    }
    
    /* Typ festlegen */
    if (empty($desired_type) === false && in_array($desired_type, array('integer', 'double', 'string', 'array', 'object')) === true) {
      settype($decoded_data, $desired_type);
    }
    
    return $decoded_data;
  }
}
?>
<?php

// PHP 5 >= 5.2.0, PECL json >= 1.2.0 -> http://php.net/manual/de/function.json-encode.php

if(!defined("JSON_NUMERIC_CHECK"))
  define("JSON_NUMERIC_CHECK", 32);

if(!function_exists("json_encode")) {

 function json_encode($val, $options = 0){
   return internal_json_encode($val, $options);
 }

}


function internal_json_encode($val, $options = 0)
{
    if (is_numeric($val))
      if($options == JSON_NUMERIC_CHECK)
         return $val;
         else
         return '"'.$val.'"';

    if (is_string($val)) return '"'.addslashes($val).'"';

    if ($val === null) return 'null';
    if ($val === true) return 'true';
    if ($val === false) return 'false';

    $assoc = false;
    $i = 0;
    foreach ($val as $k=>$v){
        if ($k !== $i++){
            $assoc = true;
            break;
        }
    }
    $res = array();
    foreach ($val as $k=>$v){
        $v = internal_json_encode($v, $options);
        if ($assoc){
            $k = '"'.addslashes($k).'"';
            $v = $k.':'.$v;
        }
        $res[] = $v;
    }
    $res = implode(',', $res);
    return ($assoc)? '{'.$res.'}' : '['.$res.']';
}


?>
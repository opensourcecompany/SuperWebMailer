<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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


$_I0j0i = version_compare(PHP_VERSION, "8.0.0") >= 0;

// copied from PEAR Webservices and modified
class GetFunctionsList{
    var $classname;

    /**
     * @var    array
     * @access private
     */
    var $simpleTypes = array(
        'string', 'int', 'float', 'bool', 'double', 'integer', 'boolean', 'array', 'Array', 'datetime',
        'varstring', 'varint', 'varfloat', 'varbool', 'vardouble',
        'varinteger', 'varboolean', 'vararray', 'vardatetime');

    /**
     * @var    array
     * @access public
     */
    var $preventMethods = array();

    /**
     * @var    array
     * @access public
     */
    var $wsdlStruct;

    /**
     * @access public
     */
    function classMethodsIntoStruct()
    {
        global $_I0j0i;
        
        $_Ql6LC = new ReflectionClass($this->classname);
        $_I060f = $_Ql6LC->getMethods();
        // params
        foreach ($_I060f AS $_I06t6) {
            if ($_I06t6->isPublic()
                && !in_array($_I06t6->getName(), $this->preventMethods)) {
                $_I06Oj = $_I06t6->getDocComment();

                $_I0fjl = $this->parseComment ($_I06Oj);
                $_I0fCt = str_replace("\n", "<br />", trim($_I0fjl["description"]));
                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['description'] = $_I0fCt;

                preg_match_all('~@param\s(\S+)~', $_I06Oj, $_I0flt);
                preg_match_all('~@return\s(\S+)~', $_I06t6->getDocComment(), $_I08f8);
                $_I08CQ = $_I06t6->getParameters();
                for ($_Qli6J = 0; $_Qli6J < count($_I08CQ); ++$_Qli6J) {

                    if(!$_I0j0i){ // here we have now classes as API param
                       $_I0tfO = $_I08CQ[$_Qli6J]->getClass();  //deprecated PHP 8
                       $_I0t8j  = ($_I0tfO) ? $_I0tfO->getName() : $_I0flt[1][$_Qli6J];
                    }else
                      $_I0t8j  = $_I0flt[1][$_Qli6J];

                    $_I0tiJ = str_replace('[]', '', $_I0t8j, $_I0tiO);
                    $_I0OoQ    = str_repeat('ArrayOf', $_I0tiO);

                    if($_I0tiJ == "array")
                       $_I0tiJ = "Array"; #nusoap supports Array with uppercase 'A' only

                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['name'] =
                            $_I08CQ[$_Qli6J]->getName();
                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['wsdltype'] =
                            $_I0OoQ . "xsd:".$_I0tiJ;
                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['type'] =
                            $_I0tiJ;
                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['length'] =
                            $_I0tiO;
                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['array'] =
                            ($_I0tiO > 0 && in_array($_I0tiJ, $this->simpleTypes))
                            ? true : false;
                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['class'] =
                            (!in_array($_I0tiJ, $this->simpleTypes) && new ReflectionClass($_I0tiJ))
                            ? true : false;
                    $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['param'] = true;
                }
                // return
                if (isset($_I08f8[1][0])) {
                    $_I0tiJ = str_replace('[]', '', $_I08f8[1][0], $_I0tiO);
                } else {
                    $_I0tiJ = 'void';
                    $_I0tiO = 0;
                }
                $_I0OoQ = str_repeat('ArrayOf', $_I0tiO);

                if($_I0tiJ == "array")
                  $_I0tiJ = "Array"; #nusoap supports Array with uppercase 'A' only

                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['wsdltype'] =
                        $_I0OoQ."xsd:".$_I0tiJ;
                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['type'] = $_I0tiJ;
                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['length'] = $_I0tiO;
                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['array'] =
                        ($_I0tiO > 0 && $_I0tiJ != 'void' && in_array($_I0tiJ, $this->simpleTypes)) ? true : false;
                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['class'] =
                        ($_I0tiJ != 'void' && !in_array($_I0tiJ, $this->simpleTypes) && new ReflectionClass($_I0tiJ))
                        ? true : false;
                $this->wsdlStruct[$this->classname]['method'][$_I06t6->getName()]['var'][$_Qli6J]['return'] = true;
            }
        }
    }


    // copied from PHP WSDL Generator - Version 1.3.1 Copyright (c) 2009 Dragos Protung
    /**
     * @access private
     */
     function parseComment ($_I0OlC) {
                $_I0OlC = trim($_I0OlC);
                if ($_I0OlC == "") return "";

                if (strpos($_I0OlC, "/*") === 0 && strripos($_I0OlC, "*/") === strlen($_I0OlC)-2) {
                        $_I0o0O = preg_split("(\\n\\r|\\r\\n\\|\\r|\\n)", $_I0OlC);
                        $_I0oit = "";
                        $_I0CQl = "";
                        $_I08CQ = array();
                        while (next($_I0o0O)) {
                                $_I0Clj = trim(current($_I0o0O));
                                $_I0Clj = trim(substr($_I0Clj, strpos($_I0Clj, "* ")+2));
                                if (isset($_I0Clj[0]) && $_I0Clj[0] == "@") {
                                        $_I0iti = explode(" ", $_I0Clj);
                                        if ($_I0iti[0] == "@return") {
                                                $_I0CQl = $_I0iti[1];
                                        } elseif ($_I0iti[0] == "@param") {
                                                $_I08CQ[$_I0iti[2]] = $_I0iti[1];
                                        } elseif ($_I0iti[0] == "@var") {
                                                $_I08CQ['type'] = $_I0iti[1];
                                        }
                                } else {
                                        $_I0oit .= "\n".trim($_I0Clj);
                                }
                        }

                        $_I0OlC = array("description"=>$_I0oit, "params"=>$_I08CQ, "return"=>$_I0CQl);
                        return $_I0OlC;
                } else {
                        return "";
                }

     }

}
?>

<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
        $_Q6o1o = new ReflectionClass($this->classname);
        $_QfjtQ = $_Q6o1o->getMethods();
        // params
        foreach ($_QfjtQ AS $_QfJI8) {
            if ($_QfJI8->isPublic()
                && !in_array($_QfJI8->getName(), $this->preventMethods)) {
                $_QfJti = $_QfJI8->getDocComment();

                $_QfJo0 = $this->_QF06D ($_QfJti);
                $_QfJL8 = str_replace("\n", "<br />", trim($_QfJo0["description"]));
                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['description'] = $_QfJL8;

                #$_docComments_Description = trim(str_replace('/**', '', substr($_QfJti, 0, strpos($_QfJti, '@'))));
                #$_QfJL8 = trim(substr($_docComments_Description, strpos($_docComments_Description, '*') + 1, strpos($_docComments_Description, '*', 1) - 1));
                preg_match_all('~@param\s(\S+)~', $_QfJti, $_Qf6OO);
                preg_match_all('~@return\s(\S+)~', $_QfJI8->getDocComment(), $_Qf6C6);
                $_QffOf = $_QfJI8->getParameters();
                for ($_Q6llo = 0; $_Q6llo < count($_QffOf); ++$_Q6llo) {
                    $_Qf8Ji = $_QffOf[$_Q6llo]->getClass();
                    $_Qft06  = ($_Qf8Ji) ? $_Qf8Ji->getName() : $_Qf6OO[1][$_Q6llo];

                    $_QftJf = str_replace('[]', '', $_Qft06, $_Qft8f);
                    $_Qftt8    = str_repeat('ArrayOf', $_Qft8f);

                    if($_QftJf == "array")
                       $_QftJf = "Array"; #nusoap supports Array with uppercase 'A' only

                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['name'] =
                            $_QffOf[$_Q6llo]->getName();
                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['wsdltype'] =
                            $_Qftt8 . "xsd:".$_QftJf;
                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['type'] =
                            $_QftJf;
                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['length'] =
                            $_Qft8f;
                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['array'] =
                            ($_Qft8f > 0 && in_array($_QftJf, $this->simpleTypes))
                            ? true : false;
                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['class'] =
                            (!in_array($_QftJf, $this->simpleTypes) && new ReflectionClass($_QftJf))
                            ? true : false;
                    $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['param'] = true;
                }
                // return
                if (isset($_Qf6C6[1][0])) {
                    $_QftJf = str_replace('[]', '', $_Qf6C6[1][0], $_Qft8f);
                } else {
                    $_QftJf = 'void';
                    $_Qft8f = 0;
                }
                $_Qftt8 = str_repeat('ArrayOf', $_Qft8f);

                if($_QftJf == "array")
                  $_QftJf = "Array"; #nusoap supports Array with uppercase 'A' only

                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['wsdltype'] =
                        $_Qftt8."xsd:".$_QftJf;
                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['type'] = $_QftJf;
                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['length'] = $_Qft8f;
                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['array'] =
                        ($_Qft8f > 0 && $_QftJf != 'void' && in_array($_QftJf, $this->simpleTypes)) ? true : false;
                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['class'] =
                        ($_QftJf != 'void' && !in_array($_QftJf, $this->simpleTypes) && new ReflectionClass($_QftJf))
                        ? true : false;
                $this->wsdlStruct[$this->classname]['method'][$_QfJI8->getName()]['var'][$_Q6llo]['return'] = true;
            }
        }
    }


    // copied from PHP WSDL Generator - Version 1.3.1 Copyright (c) 2009 Dragos Protung
    /**
     * @access private
     */
     function _QF06D ($_Qfti6) {
                $_Qfti6 = trim($_Qfti6);
                if ($_Qfti6 == "") return "";

                if (strpos($_Qfti6, "/*") === 0 && strripos($_Qfti6, "*/") === strlen($_Qfti6)-2) {
                        $_QfOij = preg_split("(\\n\\r|\\r\\n\\|\\r|\\n)", $_Qfti6);
                        $_Qfo0C = "";
                        $_QfoQ8 = "";
                        $_QffOf = array();
                        while (next($_QfOij)) {
                                $_QfoQo = trim(current($_QfOij));
                                $_QfoQo = trim(substr($_QfoQo, strpos($_QfoQo, "* ")+2));
                                if (isset($_QfoQo[0]) && $_QfoQo[0] == "@") {
                                        $_Qfo8t = explode(" ", $_QfoQo);
                                        if ($_Qfo8t[0] == "@return") {
                                                $_QfoQ8 = $_Qfo8t[1];
                                        } elseif ($_Qfo8t[0] == "@param") {
                                                $_QffOf[$_Qfo8t[2]] = $_Qfo8t[1];
                                        } elseif ($_Qfo8t[0] == "@var") {
                                                $_QffOf['type'] = $_Qfo8t[1];
                                        }
                                } else {
                                        $_Qfo0C .= "\n".trim($_QfoQo);
                                }
                        }

                        $_Qfti6 = array("description"=>$_Qfo0C, "params"=>$_QffOf, "return"=>$_QfoQ8);
                        return $_Qfti6;
                } else {
                        return "";
                }

     }

}
?>

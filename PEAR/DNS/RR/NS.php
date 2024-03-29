<?php
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_NS definition {{{ */
/**
 * A representation of a resource record of type <b>NS</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_NS extends Net_DNS_RR
{
    /* class variable defintiions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $nsdname;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function __construct($rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;


        if ($offset) {
            if ($this->rdlength > 0) {
                $Net_DNS_Packet = new Net_DNS_Packet();
                list($nsdname, $offset) = $Net_DNS_Packet->dn_expand($data, $offset);
                $this->nsdname = $nsdname;
            }
        } else {
            $this->nsdname = preg_replace("/[ \t]+(.+)[ \t]*$", '\\1/', $data);
        }
    }

    function Net_DNS_RR_NS($rro, $data, $offset = '')
    {
      self::__construct($rro, $data, $offset);
    }

    /* }}} */
    /* Net_DNS_RR_NS::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->nsdname)) {
            return $this->nsdname . '.';
        }
        return '; no data';
    }

    /* }}} */
    /* Net_DNS_RR_NS::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if (strlen($this->nsdname)) {
            return $packet->dn_comp($this->nsdname, $offset);
        }
        return null;
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
?>
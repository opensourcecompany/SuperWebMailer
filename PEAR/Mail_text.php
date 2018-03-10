<?php
class Mail_text extends Mail {

    function __construct($params = null)
    {
        if (is_array($params)) {
            $this->_params = join(' ', $params);
        } else {
            $this->_params = $params;
        }

        $this->sep = "\r\n";
    }

    function Mail_text($params = null)
    {
      self::__construct($params);
    }

    function send($recipients, $headers, $body)
    {
        if (!is_array($headers)) {
            return PEARraiseError('$headers must be an array');
        }

        $result = $this->_sanitizeHeaders($headers);
        if (is_a($result, 'PEAR_Error')) {
            return $result;
        }

        // Flatten the headers out.
        $headerElements = $this->prepareHeaders($headers);
        if (is_a($headerElements, 'PEAR_Error')) {
            return $headerElements;
        }
        list(, $text_headers) = $headerElements;

        return $text_headers.$this->sep.$this->sep.$body;
    }
}

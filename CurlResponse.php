<?php
/**
 * @author Pan Wenbin <panwenbin@gmail.com>
 */

namespace panwenbin\helper;


class CurlResponse
{
    public $code;
    public $header;
    protected $headers;
    public $body;

    /**
     * @return string
     */
    public function rawHeader()
    {
        return $this->header;
    }

    /**
     * @param null $key
     * @return array|string|null
     */
    public function headers($key = null)
    {
        if (empty($this->headers)) {
            $_headerLines = explode("\n", strtr($this->header, "\r", ''));
            if (isset($_headerLines[0])) {
                unset($_headerLines[0]);
            }
            foreach ($_headerLines as $headerLine) {
                if (!trim($headerLine)) {
                    continue;
                }
                $exp = explode(':', $headerLine, 2);
                list($headerKey, $headerValue) = $exp;
                $headerKey = trim($headerKey);
                $headerValue = trim($headerValue);
                $this->headers[$headerKey] = $headerValue;
            }
        }
        if ($key) {
            return isset($this->headers[$key]) ? $this->headers[$key] : null;
        }

        return $this->headers;
    }

    /**
     * @return string
     */
    public function rawBody()
    {
        return $this->body;
    }

    /**
     * @return array|null
     */
    public function jsonBodyArray()
    {
        return json_decode($this->body, true);
    }
}
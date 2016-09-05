<?php
/**
 * @author Pan Wenbin panwenbin@gmail.com
 */

namespace panwenbin\helper;


class Curl
{
    private $responseHeader = '';
    private $_responseHeaders = [];

    public static function to($url)
    {
        $curl = new Curl;
        $curl->toUrl($url);
        return $curl;
    }

    private $options = [
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_TIMECONDITION => 10,
        CURLOPT_TIMEOUT => 30,
    ];
    private $data = [];

    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    public function toUrl($url)
    {
        $this->setOption(CURLOPT_URL, $url);
        return $this;
    }

    public function withCookie($cookie)
    {
        $this->setOption(CURLOPT_COOKIE, $cookie);
        return $this;
    }

    public function withCookieFile($file)
    {
        $this->setOption(CURLOPT_COOKIEFILE, $file);
        return $this;
    }

    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function withHeader()
    {
        $this->setOption(CURLOPT_HEADER, true);
        return $this;
    }

    public function withoutBody()
    {
        $this->setOption(CURLOPT_NOBODY, false);
        return $this;
    }

    public function rawResponseHeader()
    {
        return $this->responseHeader;
    }

    public function responseHeaders($key = null)
    {
        if (empty($this->_responseHeaders)) {
            $_headerLines = explode("\n", strtr($this->responseHeader, "\r", ""));
            if (isset($_headerLines[0])) unset($_headerLines[0]);
            foreach ($_headerLines as $headerLine) {
                if (!trim($headerLine)) continue;
                $exp = explode(':', $headerLine, 2);
                list($headerKey, $headerValue) = $exp;
                $headerKey = trim($headerKey);
                $headerValue = trim($headerValue);
                $this->_responseHeaders[$headerKey] = $headerValue;
            }
        }
        if ($key) {
            return isset($this->_responseHeaders[$key]) ? $this->_responseHeaders[$key] : null;
        }
        return $this->_responseHeaders;
    }

    public function get()
    {
        $parameterString = '';
        if (is_array($this->data) && count($this->data)) {
            $parameterString .= strstr($this->options[CURLOPT_URL], '?') ? '&' : '?';
            $parameterString .= http_build_query($this->data);
        }
        $this->options[CURLOPT_URL] .= $parameterString;
        return $this->send();
    }

    public function post()
    {
        $this->setOption(CURLOPT_POST, 1);
        $this->setOption(CURLOPT_POSTFIELDS, $this->data);
        return $this->send();
    }

    public function patch()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->setOption(CURLOPT_POSTFIELDS, http_build_query($this->data));
        return $this->send();
    }

    public function send()
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        $html = curl_exec($ch);
        if (isset($this->options[CURLOPT_HEADER]) && $this->options[CURLOPT_HEADER]) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $this->responseHeader = substr($html, 0, $header_size);
            $html = substr($html, $header_size);
        }
        curl_close($ch);
        return $html;
    }
}
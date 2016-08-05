<?php
/**
 * @author Pan Wenbin panwenbin@gmail.com
 */

namespace panwenbin\helper;


class Curl
{
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

    public function send()
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }
}
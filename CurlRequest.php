<?php
/**
 * @author Pan Wenbin <panwenbin@gmail.com>
 */

namespace panwenbin\helper;


class CurlRequest
{
    private $options = [
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
    ];
    private $data = [];

    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    public function withOption($option, $value)
    {
        $this->setOption($option, $value);

        return $this;
    }

    public function noSslVerifyPeer()
    {
        $this->setOption(CURLOPT_SSL_VERIFYPEER, false);

        return $this;
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

    public function returnHeader()
    {
        $this->setOption(CURLOPT_HEADER, true);

        return $this;
    }

    public function notReturnBody()
    {
        $this->setOption(CURLOPT_NOBODY, false);

        return $this;
    }

    protected function prepareQueryString()
    {
        $parameterString = '';
        if (is_array($this->data) && count($this->data)) {
            $parameterString .= strstr($this->options[CURLOPT_URL], '?') ? '&' : '?';
            $parameterString .= http_build_query($this->data);
        }
        $this->options[CURLOPT_URL] .= $parameterString;
    }

    /**
     * @return CurlResponse
     */
    public function get()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'GET');
        $this->prepareQueryString();

        return $this->send();
    }

    /**
     * @return CurlResponse
     */
    public function post()
    {
        $this->setOption(CURLOPT_POST, 1);
        $this->setOption(CURLOPT_POSTFIELDS, $this->data);

        return $this->send();
    }

    /**
     * @return CurlResponse
     */
    public function patch()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->setOption(CURLOPT_POSTFIELDS, http_build_query($this->data));

        return $this->send();
    }

    /**
     * @return CurlResponse
     */
    public function head()
    {
        $this->notReturnBody();
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->prepareQueryString();

        return $this->send();
    }

    /**
     * @return CurlResponse
     */
    public function options()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        $this->prepareQueryString();

        return $this->send();
    }

    /**
     * @return CurlResponse
     */
    public function delete()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->prepareQueryString();

        return $this->send();
    }

    /**
     * @return CurlResponse
     */
    public function send()
    {
        $this->beforeSend();
        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        $res = new CurlResponse();
        $res->body = curl_exec($ch);
        $res->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!empty($this->options[CURLOPT_HEADER])) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $res->header = substr($res->body, 0, $header_size);
            $res->body = substr($res->body, $header_size);
        }
        curl_close($ch);
        $this->afterSend();

        return $res;
    }

    protected function beforeSend()
    {
    }

    protected function afterSend()
    {
    }
}
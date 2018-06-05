<?php
/**
 * @author Pan Wenbin panwenbin@gmail.com
 */

namespace panwenbin\helper;

class Curl
{
    public static function to($url)
    {
        $curl = new CurlRequest();
        $curl->toUrl($url);

        return $curl;
    }
}

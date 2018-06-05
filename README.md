# curl
> curl简单封装，暂时主要为了方便的GET/POST，代替file_get_contents

# 简单使用示例
```
use panwenbin\helper\Curl;

$res = Curl::to('https://github.com/panwenbin')->get();
$res = Curl::to('http://www.example.com/')->withData(['username' => 'panwenbin', 'password' => 'password'])->post();
$res = Curl::to('http://www.example.com/source/1')->withData(['minPriceYuan' => 123])->patch();
$res = Curl::to('http://www.example.com/')->withData(['product_id' => '123'])->get();
$res = Curl::to('http://www.example.com/profile')->withCookieFile('cookiejar.txt')->get();
if ($res->code == 200) {
    $html = $res->body;
}
```

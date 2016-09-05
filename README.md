# curl
> curl简单封装，暂时主要为了方便的GET/POST，代替file_get_contents

# 简单使用示例
```
use panwenbin\helper\Curl;

$html = Curl::to('https://github.com/panwenbin')->get();
$html = Curl::to('http://www.example.com/')->withData(['username' => 'panwenbin', 'password' => 'password'])->post();
$html = Curl::to('http://www.example.com/source/1')->withData(['minPriceYuan' => 123])->patch();
$html = Curl::to('http://www.example.com/')->withData(['product_id' => '123'])->get();
$html = Curl::to('http://www.example.com/profile')->withCookieFile('cookiejar.txt')->get();
```

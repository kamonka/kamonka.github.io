<?php
// 简单的远程视频代理，转发响应头并支持 Range 请求
// 用法: proxy.php?url=https%3A%2F%2Fexample.com%2Fvideo.mp4
if (!isset($_GET['url'])) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Missing url parameter';
    exit;
}

$url = $_GET['url'];
// 基本安全检查：仅允许 http(s)
if (!preg_match('#^https?://#i', $url)) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid url';
    exit;
}

// 初始化 cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024*8);
// 传递客户端的 Range 请求（如有），以支持断点/流式播放
if (isset($_SERVER['HTTP_RANGE'])) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Range: ' . $_SERVER['HTTP_RANGE']));
}

// 设置一个回调函数，把远端响应头转发给客户端
curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) {
    $len = strlen($header);
    $parts = explode(':', $header, 2);
    if (count($parts) == 2) {
        $name = trim($parts[0]);
        $value = trim($parts[1]);
        // 过滤掉可能不安全或 PHP 已经发送的头
        $forbidden = array('Transfer-Encoding', 'Connection');
        if (!in_array($name, $forbidden)) {
            header($name . ': ' . $value);
        }
    } else {
        // 处理 HTTP 状态 行，例如 HTTP/1.1 206 Partial Content
        if (preg_match('#HTTP/\d\.\d\s+(\d+)#', $header, $m)) {
            header(trim($header));
        }
    }
    return $len;
});

// 跨域允许
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Range, Accept-Encoding');
// 强制支持字节范围
header('Accept-Ranges: bytes');

// 执行并直接输出
curl_exec($ch);
$err = curl_error($ch);
if ($err) {
    error_log('proxy curl error: ' . $err);
}
curl_close($ch);
exit;

?>

<?php
$url = $_GET['url'];
$type = $_GET['type'];
if ($type == "S") {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36");
    curl_setopt($ch, CURLOPT_REFERER, "http://azureedge.xyz/");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = str_replace('#EXT-X-KEY:METHOD=AES-128,URI="', '#EXT-X-KEY:METHOD=AES-128,URI="/AES.php?url=http://' . explode("/", $url)[2], $result);
    header('Content-Disposition: attachment; filename="playlist.m3u8"');
    echo $result;
} else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36");
    curl_setopt($ch, CURLOPT_REFERER, "http://live94today.com/");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = str_replace('l_', '/ts.php?url=' . explode("chunks.m3u8", $url)[0] . 'l_', $result);
    header('Content-Disposition: attachment; filename="playlist.m3u8"');
    echo $result;
}
?>
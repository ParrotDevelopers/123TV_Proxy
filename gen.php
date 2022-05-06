<?php
function simplegrab($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = str_replace("\n", "", $result);
    $result = explode('<iframe id="123tv-frame" style="background-color: #000" src="', $result);
    $result = explode('"', $result[1]);
    $result = $result[0];
    $result = explode('?', $result)[0];
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $result);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_REFERER, "http://live94today.com/");
    curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36");
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
    $result2 = curl_exec($ch2);
    curl_close($ch2);
    return $result2;
};

function fullgrab($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);;
    $result0 = str_replace("\n", "", $result);
    $result0 = explode("['", $result0)[1];
    $result0 = explode("']", $result0)[0];
    $jsoncipher = str_replace("','", "", $result0);
    $pattern = '/=\[(.*?)\];/';
    preg_match_all($pattern, $result, $matches);
    $allmightystring = "";
    foreach (range(2, 5) as $i) {
        if ($i == 5) {
            $allmightystring .= $matches[1][$i]; 
        } else {
            $allmightystring .= $matches[1][$i] . "|";        
        }
    }
    $allmightystring = base64_encode($allmightystring);
    $settings = json_decode(file_get_contents("settings.json"), true);
    $decryption_server = $settings["decryption_server"];
    if (substr($decryption_server, -1) != "/") {
        $decryption_server .= "/";
    }
    $url2 = "$decryption_server?json=" . $jsoncipher . "&all=" . $allmightystring;
    $jsonauth = "?1&json=" . explode("';}", explode("+'?1&json=", $result)[1])[0];
    $ch4 = curl_init();
    curl_setopt($ch4, CURLOPT_URL, $url2);
    curl_setopt($ch4, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch4, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36");
    curl_setopt($ch4, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch4, CURLOPT_SSL_VERIFYHOST, false);
    $resultm3u8 = curl_exec($ch4);
    curl_close($ch4);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $resultm3u8);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36");
    curl_setopt($ch, CURLOPT_REFERER, "http://live94today.com/");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $jsonresult = curl_exec($ch);
    curl_close($ch);
    $jsonresult = str_replace("playlist.m3u8", "chunks.m3u8", $jsonresult);
    return $jsonresult;
};

$url = "http://live94today.com/watch/" . $_GET['c'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36");
curl_setopt($ch, CURLOPT_REFERER, "http://live94today.com/");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch);
curl_close($ch);
$hlsurl = "";
$type = "";
if (strpos($result, ".m3u8") !== false) {
    $hlsurl = simplegrab($url);
    $type = "S";
} else {
    $hlsurl = fullgrab($url);
    $type = "F";
}
header("Location: /playlist.php?url=$hlsurl&type=$type");
?>
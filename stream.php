<?php
    ini_set('max_execution_time', 0);
    set_time_limit(0);
    header("Content-Type: video/mp4");
    header('Accept-Ranges: bytes');
    header("Content-length: ".$length);
    
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'PWaanA*()!#EGyKaaZ';
    $secret_iv = 'PWAsrqWUN*()!#RETyAAga';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    
    $domain = base64_decode($_GET['domain']);
    $id = $_GET['id'];
    $itag = $_GET['itag'];
    $source = $_GET['source'];
    $requiressl = $_GET['requiressl'];
    $ttl = $_GET['ttl'];
    $susci = $_GET['susci'];
    $mm = $_GET['mm'];
    $mn = $_GET['mn'];
    $ms = $_GET['ms'];
    $mv = $_GET['mv'];
    $pl = $_GET['pl'];
    $ei = $_GET['ei'];
    $susc = $_GET['susc'];
    $driveid = openssl_decrypt(base64_decode($_GET['driveid']), $encrypt_method, $key, 0, $iv);
    $mime = $_GET['mime'];
    $cnr = $_GET['cnr'];
    $lmt = $_GET['lmt'];
    $mt = $_GET['mt'];
    $ip = $_GET['ip'];
    $ipbits = $_GET['ipbits'];
    $expire = $_GET['expire'];
    $cp = $_GET['cp'];
    $sparams = $_GET['sparams'];
    $signature = $_GET['signature'];
    $key = $_GET['key'];
    $app = $_GET['app'];
    $cookie = $_GET['token'];
    
    $googledrive = "".$domain."videoplayback?id=$id&itag=$itag&source=$source&requiressl=$requiressl&ttl=$ttl&mm=$mm&mn=$mn&ms=$ms&mv=$mv&pl=$pl&ei=$ei&susc=$susc&driveid=$driveid&mime=$mime&cnr=$cnr&lmt=$lmt&mt=$mt&ip=$ip&ipbits=$ipbits&susci=$susci&expire=$expire&cp=$cp&sparams=$sparams&signature=$signature&key=$key&app=$app";

    $ch = curl_init();
    $useragent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";
    $payload = array(
        CURLOPT_URL => $googledrive,
        CURLOPT_HEADER => false,
        CURLOPT_USERAGENT => $useragent,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 1000,
        CURLOPT_FRESH_CONNECT => true,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_NOBODY => false,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIE => 'DRIVE_STREAM='.$cookie
        );
    $length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_setopt_array($ch, $payload);
    $streamproxy = curl_exec($ch);
    return $streamproxy;

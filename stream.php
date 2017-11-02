<?php

    function inbtwn($input, $startcut, $finishcut){
        $a1 = explode($startcut, $input);
        $a2 = explode($finishcut, $a1[1]);
        $output = $a2[0];
        return $output;
    }
    
    ini_set('max_execution_time', 0); 
	$encrypt_method = "AES-256-CBC";
	$secret_key = 'PWaanA*()!#EGyKaaZ';
	$secret_iv = 'PWAsrqWUN*()!#RETyAAga';
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

    $domain = base64_decode($_GET['dom']);
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
    $driveid = openssl_decrypt(base64_decode($_GET['driveid']), $encrypt_method, $key, 0, $iv);
    $mime = $_GET['mime'];
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
    $ck = $_GET['ck'];

    $v = "".$domain."videoplayback?id=$id&itag=$itag&source=$source&requiressl=$requiressl&ttl=$ttl&mm=$mm&mn=$mn&ms=$ms&mv=$mv&pl=$pl&ei=$ei&driveid=$driveid&mime=$mime&lmt=$lmt&mt=$mt&ip=$ip&ipbits=$ipbits&susci=$susci&expire=$expire&cp=$cp&sparams=$sparams&signature=$signature&key=$key&app=$app";

    $e = $_GET['expire'];
    set_time_limit(0);
    $driverget = openssl_decrypt(base64_decode($driver), $encrypt_method, $key, 0, $iv);
 
    $id = inbtwn($driverget , "<div>" , "</div>");
    $ex = inbtwn($driverget , "<t>" , "</t>");
 
    $md5 = md5($ck);
    $md52 = md5($driveid);
  
 
    $current = time(); 
    
    if($_COOKIE[$md52] ==$md5) {
    } else {
     
    }

    function size($address, $cookiz1) { 
        $ch = curl_init();
        $useragent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: DRIVE_STREAM=".$cookiz1));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
         
        curl_setopt($ch, CURLOPT_URL, $address);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         
        $info = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        return $size;
    }

    function stream($address, $cookiz1) { 
        $ch = curl_init();
        $useragent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: DRIVE_STREAM=".$cookiz1));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
        curl_setopt($ch, CURLOPT_URL, $address);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
        $info = curl_exec($ch);
        $size2 = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    
        header("Content-Type: video/mp4"); 
        $filesize = $size2;
    
        $offset = 0;
        $length = $filesize;
        
        if ( isset($_SERVER['HTTP_RANGE']) ) {
            $partialContent = "true";
            preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
            $offset = intval($matches[1]);
            $length = $size2 - $offset -1;
        } else {
            $partialContent = "false";
        }
    
        if ( $partialContent =="true") {
            header('HTTP/1.1 206 Partial Content');
            header('Accept-Ranges: bytes'); 
            header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);
        } else {
            header('Accept-Ranges: bytes'); 
        }
        
        header("Content-length: ".$size2);
    }
 

    function StartsWith($Haystack, $Needle){
        return strpos($Haystack, $Needle);
    }
    
    echo stream($v,$ck);
    $fsize = size($v,$ck);
    $ch = curl_init();
    $useragent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";
    
    if ( isset($_SERVER['HTTP_RANGE']) ) {
        $partialContent = true;
    
        preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    
        $offset = intval($matches[1]);
        $length = $fsize - $offset -1;
    
        $headers = array(
            'Cookie: DRIVE_STREAM='.$ck,
            'Range: bytes=' . $offset . '-' . ($offset + $length) . '',
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        } else {
         curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: DRIVE_STREAM=".$ck));
    
    }

    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
    curl_setopt($ch, CURLOPT_TCP_FASTOPEN, 1);
    curl_setopt($ch, CURLOPT_URL, $v);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_exec($ch);

?>

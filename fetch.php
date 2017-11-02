<?php
//Modified

    function curl($url) {
    	$ch = @curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
    	curl_close($ch);
    	return $result;
    }
    
    function driveproxy($id) {
        $gdata = [];
        $gurl = 'https://drive.google.com/get_video_info?docid='.$id.'';
        $gparse = curl($gurl);
    
        parse_str($gparse, $gstring);
        $data = explode(",", $gstring["fmt_stream_map"]);
    
        foreach($data as $d) {
            switch ((int)substr($d, 0, 2)) {
                case 18:
                    $r = "360P";
                    break;
                case 22:
                    $r = "720P";
                    break;
                case 37:
                    $r = "1080P";
                    break;
                case 59:
                    $r = "480P";
                    break;
                default:
                    break;
            }
            
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $gparse, $matches);
            $cookies = array();
            foreach($matches[1] as $item) {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }
            $gck = str_replace("DRIVE_STREAM=" ,"" , $matches[1]);
            
    		$encrypt_method = "AES-256-CBC";
    		$secret_key = 'PWaanA*()!#EGyKaaZ';
    		$secret_iv = 'PWAsrqWUN*()!#RETyAAga';
    		$key = hash('sha256', $secret_key);
    		$iv = substr(hash('sha256', $secret_iv), 0, 16);
            $hashdrive = base64_encode(openssl_encrypt($id,$encrypt_method, $key, 0, $iv));
            
            $var = explode('&',$d);
            $domain = $var[0];
            $redirector = preg_replace("@(.*)videoplayback(.*)@si","$1", $domain);
            $hiddomain = base64_encode(str_replace(array("18|https", "22|https", "37|https", "59|https","c.drive.google.com"),array("https", "https", "https", "https", "googlevideo.com"), $redirector));

            $modiapi = 'japnime';
            $streamdrtr = 'stream.php';

            $o[$r] = substr(preg_replace(array("@&driveid=(.+?)&@si","/\/[^\/]+\.google\.com\/videoplayback/","@&ip=(.+?)&@si"),array("&driveid=$hashdrive&api=$modiapi&","/$streamdrtr","&ip=$1&ck=$gck[0]&dom=$hiddomain&"), $d), 3);
            $expire = substr(preg_replace("/expire=([\d]+)/",$o[$r],$expire, $d)?$expire[1]:false, 3);
        }
        ksort($o);
        return $o;
    }

  if($_GET['id'] != ""){

      $gid = $_GET['id'];
      $gdata = driveproxy($gid);
      header("Content-Type: application/json");

      foreach ($gdata as $quality => $file) {
          $file = urldecode($file);
          $filedata = array("file"=> $file.'&apps=japnimeserver.com', "type"=> "video/mp4", "label"=> $quality);
          $output = json_encode($filedata, JSON_PRETTY_PRINT);
          echo $output;
      }

  }

?>

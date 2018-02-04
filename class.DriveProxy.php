<?php

    class DriveProxy
    {
        private $proxylink = '';
        private $cachedirectory = __DIR__ .'/cache/';
        
        public function driveid($googletoken)
        {
            $cache = $this->cachedirectory . md5($googletoken) . '.cache';
            $googleproxy = $this->grab_link($googletoken);
            
            if (file_exists($cache))
            {
                $data = file_get_contents($cache);
                $data = explode('@@', $data);
                if (is_array($data) && isset($data[1]) && (time() - $data[0]) <= 900)
                {
				$videolinks = trim($data[1]);
                }
            }
            
            if (empty($videolinks))
            {
                $videolinks = $googleproxy;
                $this->cache($cache, $googleproxy);
            }
            
            $this->proxylink = $videolinks;
        }
        
        private function grab_content($link)
        {
            $curl = curl_init();
            $curlopt = array(
                CURLOPT_URL => $link,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HEADER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_FRESH_CONNECT=> true,
                CURLOPT_SSL_VERIFYPEER => 0
                );
            curl_setopt_array($curl, $curlopt);
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        }
        
        private function rnd($var)
        {
            $randomvar = array_rand($var);
            $random = $var[$randomvar];
            return $random;
        }
        
        private function grab_link($token)
        {
            $gdata = [];
            $gurl = 'https://drive.google.com/get_video_info?docid='.$token.'';
            $gparse = $this->grab_content($gurl);
        
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
                
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $gparse, $drivestream);
            $cookies = array();
            foreach($drivestream[1] as $item)
            {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }
            $gck = str_replace("DRIVE_STREAM=" ,"" , $drivestream[1]);
                
        	$encrypt_method = "AES-256-CBC";
        	$secret_key = 'PWaanA*()!#EGyKaaZ';
        	$secret_iv = 'PWAsrqWUN*()!#RETyAAga';
        	$key = hash('sha256', $secret_key);
        	$iv = substr(hash('sha256', $secret_iv), 0, 16);
        		
            $hashdrive = base64_encode(openssl_encrypt($token,$encrypt_method, $key, 0, $iv));

            $var = explode('&',$d);
            $domain = $var[0];
            $redirector = preg_replace("@(.*)videoplayback(.*)@si","$1", $domain);
            $hiddomain = base64_encode(str_replace(array("18|https", "22|https", "37|https", "59|https","c.drive.google.com"),array("https", "https", "https", "https", "googlevideo.com"), $redirector));
        
            $modiapi = 'japnime';
            $rndserver = [
                "stream.php"
                //"http://yourotherwebsite.com/stream.php",
                //"/directory/index.php",
                ];
            $streamdrtr = $this->rnd($rndserver);
                
            $o[$r] = substr(preg_replace(array("@&driveid=(.+?)&@si","/https:\/\/+[^\/]+\.google\.com\/videoplayback/","@&ip=(.+?)&@si"),array("&driveid=$hashdrive&driveapi=$modiapi&","$streamdrtr","&ip=$1&token=$gck[0]&domain=$hiddomain&"), $d), 3);
            }
            
            asort($o);
            
            foreach ($o as $quality => $file)
            {
                $urls = urldecode($file);
                if(empty($urls))
                {
                    die();
                } else {
                    $sources .= '{"type": "video/mp4", "label": "'.$quality.'", "file": "'.$urls.'&server=japnimeserver.com"},';
                }
            }
            
             return '['.rtrim($sources, ',').']';
        }
        
        private function cache($dir, $data)
        {
            if (!file_exists($this->cachedirectory)) {
                mkdir($this->cachedirectory, 0777, true);
            }
            
            $content = time().'@@'.$data;
            file_put_contents($dir, $content);
        }
        
        public function proxy_link()
        {
            echo $this->proxylink;
        }
        
    }
    
    class DriveStream
    {
        private $drive = '';
	private $bytes = '';
        private $useragent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36';
        
        public function ignition($proxylink, $cookie)
        {
            $this->drive = $this->grab_video_content($proxylink, $cookie);
            return $this->drive;
        }

        public function rangebyte($proxylink, $cookie)
        {
            $this->bytes = $this->grab_video_length($proxylink, $cookie);
            return $this->bytes;
        }
        
        private function grab_video_content($link, $cookie)
        {
            $curl = curl_init();
            $payload = array(
                CURLOPT_URL => $link,
                CURLOPT_HEADER => true,
                CURLOPT_USERAGENT => $this->useragent,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT => 1000,
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_NOBODY => true,
                CURLOPT_VERBOSE => 1,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_COOKIE => 'DRIVE_STREAM='.$cookie
            );
            curl_setopt_array($curl, $payload);
            curl_exec($curl);
            $solution = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    
            header("Content-Type: video/mp4");
            header("Content-length: ".$solution);
            
            if (isset($_SERVER['HTTP_RANGE']))
            {
                $http = 1;
                preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $range);
                $initial = intval($range[1]);
                $final = $solution - $initial -1;
            }
            
            if ($http == 1)
            {
                header('HTTP/1.1 206 Partial Content');
                header('Accept-Ranges: bytes'); 
                header('Content-Range: bytes ' . $initial . '-' . ($initial + $final) . '/' . $solution);
            } else {
                header('Accept-Ranges: bytes'); 
            }
        }
        
        private function grab_video_length($link, $cookie)
        {
            $curl = curl_init();
            $payload = array(
                CURLOPT_URL => $link,
                CURLOPT_HEADER => true,
                CURLOPT_USERAGENT => $this->useragent,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT => 1000,
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_NOBODY => true,
                CURLOPT_VERBOSE => 1,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_COOKIE => 'DRIVE_STREAM='.$cookie
            );
            curl_setopt_array($curl, $payload);
            curl_exec($curl);
            $solution = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            return $solution;
        }

        public function stream()
        {
            echo $this->drive;
        }
        
        public function result()
        {
            return $this->bytes;
        }
    }

?>

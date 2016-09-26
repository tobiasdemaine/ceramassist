<?php

/*
    (2014) Main source -> http://lancenewman.me/posting-a-photo-to-instagram-without-a-phone/
    
    I just managed to sniff Instagram traffic and fixed the code

    -- Have fun - batuhan.org - Batuhan Katırcı
    --- for your questions, comment @ http://batuhan.org/instagram-photo-upload-with-php/
    
    
*/


function SendRequest($url, $post, $post_data, $user_agent, $cookies) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://instagram.com/api/v1/'.$url);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);

    if($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }

    if($cookies) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');            
    } else {
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    }

    $response = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);

    
    return array($http, $response);
}

function GenerateGuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', 
            mt_rand(0, 65535), 
            mt_rand(0, 65535), 
            mt_rand(0, 65535), 
            mt_rand(16384, 20479), 
            mt_rand(32768, 49151), 
            mt_rand(0, 65535), 
            mt_rand(0, 65535), 
            mt_rand(0, 65535));
}


function GenerateSignature($data) {
    return hash_hmac('sha256', $data, '25eace5393646842f0d0c3fb2ac7d3cfa15c052436ee86b5406a8433f54d24a5');
}

function GetPostData($filename) {
    if(!$filename) {
        echo "The image doesn't exist ".$filename;
    } else {
        $post_data = array('device_timestamp' => time(), 
                            'photo' => '@'.$filename);
        return $post_data;
    }
}

// Set the username and password of the account that you wish to post a photo to
$username = '';
$password = '';

// Set the path to the file that you wish to post.
// This must be jpeg format and it must be a perfect square
$filename = '400x400.jpg';

// Set the caption for the photo
$caption = "Testcaption";

// Define the user agent
$agent = 'Instagram 6.21.2 Android (19/4.4.2; 480dpi; 1152x1920; Meizu; MX4; mx4; mt6595; en_US)';

// Define the GuID
$guid = GenerateGuid();

// Set the devide ID
$device_id = "android-".$guid;

/* LOG IN */
// You must be logged in to the account that you wish to post a photo too
// Set all of the parameters in the string, and then sign it with their API key using SHA-256
$data = '{"device_id":"'.$device_id.'","guid":"'.$guid.'","username":"'.$username.'","password":"'.$password.'","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
$sig = GenerateSignature($data);
$data = 'signed_body='.$sig.'.'.urlencode($data).'&ig_sig_key_version=6';
$login = SendRequest('accounts/login/', true, $data, $agent, false);


if(strpos($login[1], "Sorry, an error occurred while processing this request.")) {
    echo "Request failed, there's a chance that this proxy/ip is blocked";
} else {            
    if(empty($login[1])) {
        echo "Empty response received from the server while trying to login";
    } else {            
        // Decode the array that is returned
        $obj = @json_decode($login[1], true);

        if(empty($obj)) {
            echo "Could not decode the response: ".$body;
        } else {
            // Post the picture
            $data = GetPostData($filename);
            $post = SendRequest('media/upload/', true, $data, $agent, true);    

            if(empty($post[1])) {
                echo "Empty response received from the server while trying to post the image";
            } else {
                // Decode the response 
                $obj = @json_decode($post[1], true);

                if(empty($obj)) {
                    echo "Could not decode the response";
                } else {
                    $status = $obj['status'];

                    if($status == 'ok') {
                        // Remove and line breaks from the caption
                        $caption = preg_replace("/\r|\n/", "", $caption);

                        $media_id = $obj['media_id'];
                        $device_id = "android-".$guid;
                        $data = '{"device_id":"'.$device_id.'","guid":"'.$guid.'","media_id":"'.$media_id.'","caption":"'.trim($caption).'","device_timestamp":"'.time().'","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';   
                        $sig = GenerateSignature($data);
                        $new_data = 'signed_body='.$sig.'.'.urlencode($data).'&ig_sig_key_version=4';

                        // Now, configure the photo
                        $conf = SendRequest('media/configure/', true, $new_data, $agent, true);

                        if(empty($conf[1])) {
                            echo "Empty response received from the server while trying to configure the image";
                        } else {
                            if(strpos($conf[1], "login_required")) {
                                echo "You are not logged in. There's a chance that the account is banned";
                            } else {
                                $obj = @json_decode($conf[1], true);
                                $status = $obj['status'];

                                if($status != 'fail') {
                                    echo "Success";
                                } else {
                                    echo 'Fail';
                                }
                            }
                        }
                    } else {
                        echo "Status isn't okay";
                    }
                }
            }
        }
    }
}
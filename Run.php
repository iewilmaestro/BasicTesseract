<?php
error_reporting(0);
function Run($url, $ua, $data = null) {
    while (True){
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => 1,));
        if ($data) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,));
        }
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => $ua,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIEJAR => 'cookie.txt',
            CURLOPT_COOKIEFILE => 'cookie.txt',));
        $run = curl_exec($ch);
        curl_close($ch);
        return $run;
    }
}

/*header request*/
$uas = array();
$uas[]="Host: api-secure.solvemedia.com";
$uas[]="user-agent: ".$user_agent;
$uas[]="accept: *//*";
$uas[]="referer: https://earnbitmoon.club/";

/*Url Solvemedia*/
$solv = Run($url_solvmedia,$uas);
$challange=explode('"',$solv)[5];

/*Media hasil dari Url Solvemedia*/
$media = Run('https://api-secure.solvemedia.com/papi/media?c='.$challange.';w=300;h=150;fg=000000;bg=f8f8f8',$uas);
file_put_contents("img.png",$media);

/*Convert & crop & edit Media*/
shell_exec("convert img.png -threshold 70% -gravity Center -crop 250x142+0+10 +repage mg.png");

/*Membaca text di file mg.png*/
shell_exec("tesseract mg.png bypass -l eng --oem 3 --oem 0 --psm 4 --psm 5 --psm 6 --dpi 800 -c tessedit_char_whitelist=abcdefghijklmnopqrstuvwxyz");

/*hasil dari membaca tulisan*/
$captcha = file_get_contents('captcha.txt');

/*Menghilangkan Enter di file text*/
$respon = trim(str_replace("\n","",$captcha));

/*cek hasil respon*/
echo $respon;

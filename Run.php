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
$uas[]="user-agent: Mozilla/5.0 (Linux; Android 9; Redmi 6A) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.99 Mobile Safari/537.36";
$uas[]="accept: */*";
$uas[]="referer: https://earnbitmoon.club/";

/*Url Solvemedia*/
$solv = Run('https://api-secure.solvemedia.com/papi/_challenge.js?k=5TuPjHOPoHvCPuSfsUohIl19kOkG2877;f=_ACPuzzleUtil.callbacks%5B0%5D;l=en;t=img;s=standard;c=js,h5c,h5ct,svg,h5v,v/64,v/webm,h5a,a/mp3,a/ogg,ua/chrome,ua/chrome80,os/android,os/android9,fwv/BQ9big.zaot23,jslib/jquery,htmlplus;am=In0CX5KOj8AncVU.ko6PwA;ca=ajax;ts=1635401711;ct=1635332869;th=white;r=0.4098500684129729',$uas);
$challange=explode('"',$solv)[5];

/*Media hasil dari Url Solvemedia*/
$media = Run('https://api-secure.solvemedia.com/papi/media?c='.$challange.';w=300;h=150;fg=000000;bg=f8f8f8',$uas);
file_put_contents("img.png",$media);

/*Convert & crop & edit Media*/
shell_exec("convert img.png -threshold 70% -gravity Center -crop 250x142+0+10 +repage mg.png");

/*Membaca text di file mg.png*/
shell_exec("tesseract mg.png captcha -l eng --oem 3 --oem 0 --psm 4 --psm 5 --psm 6 --dpi 800 -c tessedit_char_whitelist=abcdefghijklmnopqrstuvwxyz");

/*Mengambil text*/
$captcha = file_get_contents('captcha.txt');

/*Menghilangkan Enter di file text*/
$respon = trim(str_replace("\n","",$captcha));

/*cek hasil respon*/
echo $respon;

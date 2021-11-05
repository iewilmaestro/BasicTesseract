<?php
error_reporting(0);

/*Convert & crop & edit Media*/
shell_exec("convert img.png -threshold 80% -gravity Center -crop 250x142+0+10 +repage mg.png");

/*Membaca text di file mg.png*/
shell_exec("tesseract mg.png captcha -l eng --oem 3 --oem 0 --psm 4 --psm 5 --psm 6 --dpi 800 -c tessedit_char_whitelist=abcdefghijklmnopqrstuvwxyz");

/*Mengambil text*/
$captcha = file_get_contents('captcha.txt');

/*Menghilangkan Enter di file text*/
$respon = trim(str_replace("\n","",$captcha));

/*cek hasil respon*/
echo $respon;

<?php
/*******************************************************************************
 * Google PageRank Scripti v1.0 (Farklı hostlarda sorun çıkarmayan versiyonu)
 *
 * İnternette dolaşan Google PageRank Scripti bazı hostinglerde işletim 
 * sistemine, PHP versiyonuna ve işletim sisteminin 32/64bit oluşuna bağlı 
 * olarak matematiksel işlemlerde farklı farklı değerler döndürmekte ve bu da 
 * scriptin her yerde düzgün çalışmamasına neden olmaktadır. Bu script farklı 
 * olarak 'toInt32()' fonksiyonunu kullanarak bu farklılığı ortadan kaldırmakta 
 * ve (denediğim) her hostta doğru PR değerlerini döndürmektedir.
 *
 * Sorunu çözmede aşağıdaki forum konusunun #264 numaralı mesajındaki kod 
 * kullanılmıştır:
 *
 * http://www.mobileread.com/forums/showthread.php?t=1670&page=18&pp=15
 *
 *
 * Kodun en güncel versiyonunu aşağıdaki adreste bulabilirsiniz:
 *
 * http://www.in-spiretech.com/php-pagerank/
 *
 *
 * Ağustos 2006, İstanbul
 * Hüseyin TÜFEKÇİLERLİ <htufekcilerli et yahoo.com>
 *
 ******************************************************************************/


/******************** Buradan itibaren kopyalamaya başlayın *******************/
define('GOOGLE_MAGIC', 0xE6359A60);

function toInt32(& $x)
{
    $z = hexdec(80000000);
    $y = (int)$x;
    // on 64bit OSs if $x is double, negative ,will return -$z in $y
    // which means 32th bit set (the sign bit)
    if ($y==-$z && $x<-$z) {
        $y = (int)((-1)*$x);// this is the hack, make it positive before
        $y = (-1)*$y; // switch back the sign
    }
    $x = $y;
}  

function zeroFill($a, $b)
{
    $z = hexdec(80000000);
    if ($z & $a) {
        $a = ($a>>1);
        $a &= (~$z);
        $a |= 0x40000000;
        $a = ($a>>($b-1));
    } else {
        $a = ($a>>$b);
    }

    return $a;
}

function mix($a,$b,$c)
{
    $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,13)));
    $b -= $c; $b -= $a; toInt32($b); $b = (int)($b ^ ($a<<8));
    $c -= $a; $c -= $b; toInt32($c); $c = (int)($c ^ (zeroFill($b,13)));
    $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,12)));
    $b -= $c; $b -= $a; toInt32($b); $b = (int)($b ^ ($a<<16));
    $c -= $a; $c -= $b; toInt32($c); $c = (int)($c ^ (zeroFill($b,5)));
    $a -= $b; $a -= $c; toInt32($a); $a = (int)($a ^ (zeroFill($c,3)));
    $b -= $c; $b -= $a; toInt32($b); $b = (int)($b ^ ($a<<10));
    $c -= $a; $c -= $b; toInt32($c); $c = (int)($c ^ (zeroFill($b,15)));
    return array($a,$b,$c);
}

function GoogleCH($url, $length=null, $init=GOOGLE_MAGIC)
{
    if(is_null($length)) {
        $length = sizeof($url);
    }
    $a = $b = 0x9E3779B9;
    $c = $init;
    $k = 0;
    $len = $length;

    while($len >= 12) {
        $a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24));
        $b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24));
        $c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24));
        $mix = mix($a,$b,$c);
        $a = $mix[0]; $b = $mix[1]; $c = $mix[2];
        $k += 12;
        $len -= 12;
    }

    $c += $length;

    switch($len) {
        case 11: $c+=($url[$k+10]<<24);
        case 10: $c+=($url[$k+9]<<16);
        case 9 : $c+=($url[$k+8]<<8);
        case 8 : $b+=($url[$k+7]<<24);
        case 7 : $b+=($url[$k+6]<<16);
        case 6 : $b+=($url[$k+5]<<8);
        case 5 : $b+=($url[$k+4]);
        case 4 : $a+=($url[$k+3]<<24);
        case 3 : $a+=($url[$k+2]<<16);
        case 2 : $a+=($url[$k+1]<<8);
        case 1 : $a+=($url[$k+0]);
    }

    $mix = mix($a,$b,$c);

    return ($mix[2] < 0 ) ? (4294967296 + $mix[2]) : $mix[2];
}

function strord($string)
{
    for($i=0;$i<strlen($string);$i++) {
        $result[$i] = ord($string{$i});
    }

    return $result;
}

function c32to8bit($arr32)
{
    for($i=0;$i<count($arr32);$i++) {
        for ($bitOrder=$i*4;$bitOrder<=$i*4+3;$bitOrder++) {
            $arr8[$bitOrder]=$arr32[$i]&255;
            $arr32[$i]=zeroFill($arr32[$i], 8);
        }
    }

    return $arr8;
}

function GoogleNewCH($ch)
{
    $ch = ((($ch/7) << 2) | (((int)fmod($ch,13))&7));
    $prbuf = array();
    $prbuf[0] = $ch;
    for($i = 1; $i < 20; $i++) {
        $prbuf[$i] = $prbuf[$i-1]-9;
    }
    $ch = GoogleCH(c32to8bit($prbuf), 80);

    return $ch;
}

function getPR($url)
{
    $url = 'info:' . $url;
    $ch = GoogleCH(strord($url));
    $ch = "6".GoogleNewCH($ch);

    $q = "http://toolbarqueries.google.com/search?client=navclient-auto&ch=".$ch."&ie=UTF-8&oe=UTF-8&features=Rank&q=".$url;

    if (extension_loaded('curl')) {
        $header[] = "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)\r\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$q);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $returned = curl_exec($ch);
        curl_close ($ch);
    } else {
        $returned = file_get_contents($q);
    }

    preg_match("/Rank_.*?:.*?:(\d+)/i", $returned, $matches);

    return isset($matches[1]) ? $matches[1] : 0;
}
/************************* Burada kopyalamayı bitirin *************************/


// Kullanım örneği:
echo getPR('www.in-spiretech.com');

?>
<?php

/**
 * PHP Implementation of MurmurHash3
 *
 * @author Stefano Azzolini (lastguest@gmail.com)
 * @see https://github.com/lastguest/murmurhash-php
 * @author Gary Court (gary.court@gmail.com)
 * @see http://github.com/garycourt/murmurhash-js
 * @author Austin Appleby (aappleby@gmail.com)
 * @see http://sites.google.com/site/murmurhash/
 *
 * @param string $key Text to hash.
 * @param number $seed Positive integer only
 * @return number 32-bit (base 32 converted) positive integer hash
 */
   
function murmurhash3_32_gc($key,$seed=0){
    function zfsr($a,$b){return (int)((float)$a/pow(2,(int)$b));}
    $klen = strlen($key);
    $bytes = $klen - ($remainder = $klen & 3);
    $h1 = $seed;
    $c1 = 0xcc9e2d51;
    $c2 = 0x1b873593;
    $i = 0;
    while ($i < $bytes) {
        $k1 = ((ord($key[$i]) & 0xff)) |
              ((ord($key[++$i]) & 0xff) << 8) |
              ((ord($key[++$i]) & 0xff) << 16) |
              ((ord($key[++$i]) & 0xff) << 24);
        ++$i;
        $k1 = (((($k1 & 0xffff) * $c1) + (((zfsr($k1 , 16) * $c1) & 0xffff) << 16))) & 0xffffffff;
        $k1 = ($k1 << 15) | zfsr($k1 , 17);
        $k1 = (((($k1 & 0xffff) * $c2) + (((zfsr($k1 , 16) * $c2) & 0xffff) << 16))) & 0xffffffff;

        $h1 ^= $k1;
        $h1 = ($h1 << 13) | zfsr($h1 , 19);
        $h1b = (((($h1 & 0xffff) * 5) + (((zfsr($h1 , 16) * 5) & 0xffff) << 16))) & 0xffffffff;
        $h1 = ((($h1b & 0xffff) + 0x6b64) + (((zfsr($h1b , 16) + 0xe654) & 0xffff) << 16));
    }
   
    $k1 = 0;

    switch ($remainder) {
        case 3: $k1 ^= (ord($key[$i + 2]) & 0xff) << 16;
        case 2: $k1 ^= (ord($key[$i + 1]) & 0xff) << 8;
        case 1: $k1 ^= (ord($key[$i]) & 0xff);

        $k1 = ((($k1 & 0xffff) * $c1) + (((zfsr($k1 , 16) * $c1) & 0xffff) << 16)) & 0xffffffff;
        $k1 = ($k1 << 15) | zfsr($k1 , 17);
        $k1 = ((($k1 & 0xffff) * $c2) + (((zfsr($k1 , 16) * $c2) & 0xffff) << 16)) & 0xffffffff;
        $h1 ^= $k1;
    }

    $h1 ^= $klen;

    $h1 ^= zfsr($h1 , 16);
    $h1 = ((($h1 & 0xffff) * 0x85ebca6b) + (((zfsr($h1 , 16) * 0x85ebca6b) & 0xffff) << 16)) & 0xffffffff;
    $h1 ^= zfsr($h1 , 13);
    $h1 = (((($h1 & 0xffff) * 0xc2b2ae35) + (((zfsr($h1 , 16) * 0xc2b2ae35) & 0xffff) << 16))) & 0xffffffff;
    $h1 ^= zfsr($h1 , 16);

    return base_convert(zfsr($h1,0),10,32);
}
<?php
// 应用公共文件

function getRandStr(int $num){
    $s = 'abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789';
    $str = '';
    while(($num--) > 0)
        $str.=substr($s,intval(rand(0,strlen($s))),1);
    return $str;
}

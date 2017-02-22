<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 21.02.2017
 * Time: 04:09
 */

/**
 * Herhangi bir tipteki girdinin detaylı şekilde çıktısını verir
 *
 * @param any $p Any type
 */
if (!function_exists('p')) {
    function p($a = null, $b = null, $is_null = null) {
        if (is_null($a)) {
            if($is_null){
                echo "<pre>";
                print_r("NULL DEĞER");
                echo "</pre>";
            }else{
                echo "<pre>";
                print_r("p() fonksiyonu yanlış kullanıldı !");
                echo "</pre>";
                die;
            }
        } else {
            if (is_null($b)) {
                echo "<pre>";
                print_r($a);
                echo "</pre>";
            } else {
                echo "<pre>";
                var_dump($a);
                echo "</pre>";
            }
        }
    }
}

/**
 * Verilen diziyi verilen key değerine göre gruplayıp geri döndürüyor
 *
 * @param $array Gruplanacak dizi
 * @param $key Gruplamak için kullanılacak key değeri
 * @return array
 */
if(!function_exists('array_group')){
    function array_group($array, $key){
        $result = [];
        foreach ($array as $a) {
            $tmp_key = $a[$key];
            if (isset($result[$tmp_key])) {
                $result[$tmp_key][] = $a;
            } else {
                $result[$tmp_key] = [$a];
            }
        }
        return $result;
    }
}


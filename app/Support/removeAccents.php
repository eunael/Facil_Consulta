<?php

/**
 * Remove accents from a string
 *
 * @param string $string
 * @return string
 */
function removeAccents(string $string): string {
    $unwantedArray = [
        'Á'=>'A', 'À'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A',
        'É'=>'E', 'È'=>'E', 'Ê'=>'E', 'Ë'=>'E',
        'Í'=>'I', 'Ì'=>'I', 'Î'=>'I', 'Ï'=>'I',
        'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O',
        'Ú'=>'U', 'Ù'=>'U', 'Û'=>'U', 'Ü'=>'U',
        'Ç'=>'C', 'Ñ'=>'N',
        'á'=>'a', 'à'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a',
        'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e',
        'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i',
        'ó'=>'o', 'ò'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o',
        'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u',
        'ç'=>'c', 'ñ'=>'n'
    ];
    return strtr($string, $unwantedArray);
};

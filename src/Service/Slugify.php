<?php

namespace App\Service;

class Slugify
{
    /**
     * @param string $input
     * @return string
     */
    public function generate(string $input):string
    {
        // tous les caractères UTF8
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/[“”«»„]/u'    =>   ' ',
        );
        //remplace chaque valeur de $utf8
        $input =preg_replace(array_keys($utf8),array_values($utf8),$input);
        $input =trim($input);
        $input= str_replace(' ', '-', $input);
        $input =str_replace(array("?","!","/","\'",".",",",":",";"),'',$input);
        $input= preg_replace('/--+/', '-', $input);
        $input = strtolower($input);

        return $input;

    }
}



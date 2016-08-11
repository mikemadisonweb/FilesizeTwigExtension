<?php

namespace AppBundle\Twig;

class BytesToSizeExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array
        (
            'bytes_to_size' => new \Twig_Function_Method($this, 'getSizeFromBytes')
        );
    }

    public function getSizeFromBytes($bytes)
    {
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;

        if ($bytes < $kilobyte)
            return $bytes.' Байт';
        else if ($bytes < $megabyte)
            return number_format($bytes/$kilobyte, 2, '.', '').' Кбайт';
        else if ($bytes < $gigabyte)
            return number_format($bytes/$megabyte, 2, '.', '').' Мбайт';
        else if ($bytes < $terabyte)
            return number_format($bytes/$gigabyte, 2, '.', '').' Гбайт';
        else
            return number_format($bytes/$terabyte, 2, '.', '').' Тбайт';
    }

    public function getName()
    {
        return 'BytesToSizeExtension';
    }
}
<?php

namespace Mary\WebBundle\Twig\Extension;

use JBZoo\Utils\Str as StringUtil;

class WebExtension extends \Twig_Extension
{
    public function __construct()
    {
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price', [$this, 'priceFilter']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('stripSpace', [$this, 'stripSpace']),
        ];
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '¥'.$price;
        return $price;
    }

    public function stripSpace($string)
    {
        return StringUtil::stripSpace($string);
    }

}
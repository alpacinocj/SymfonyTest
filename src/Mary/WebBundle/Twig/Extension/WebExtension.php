<?php

namespace Mary\WebBundle\Twig\Extension;

class WebExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price', [$this, 'priceFilter']),
        ];
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '¥'.$price;
        return $price;
    }
}
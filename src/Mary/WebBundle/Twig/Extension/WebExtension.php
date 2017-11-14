<?php

namespace Mary\WebBundle\Twig\Extension;

use JBZoo\Utils\Str as StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebExtension extends \Twig_Extension
{
    protected $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // set default options here ... todo
            'kernel_env' => 'dev'
        ]);
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
            new \Twig_SimpleFunction('getKernelEnv', [$this, 'getKernelEnv']),
            new \Twig_SimpleFunction('mbSubStr', [$this, 'mbSubStr']),
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

    public function getKernelEnv()
    {
        return $this->options['kernel_env'];
    }

    public function mbSubStr($string, $start, $length = 0)
    {
        return StringUtil::sub($string, $start, $length);
    }

}
<?php

namespace Mary\WebBundle\Twig\Extension;

use JBZoo\Utils\Str as StringUtil;

class WebExtension extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
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
            new \Twig_SimpleFunction('assetCss', [$this, 'assetCss']),
            new \Twig_SimpleFunction('assetJs', [$this, 'assetJs']),
            new \Twig_SimpleFunction('assetImage', [$this, 'assetImage']),
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

    public function assetCss($cssFile, $basePath = '/assets/maryweb')
    {
        echo '<link rel="stylesheet" href="'. $this->_getAssetPath('css', $basePath, $cssFile) .'">';
        return null;
    }

    public function assetJs($jsFile, $basePath = '/assets/maryweb')
    {
        echo '<script src="'. $this->_getAssetPath('js', $basePath, $jsFile) .'"></script>';
        return null;
    }

    public function assetImage($imageFile, $basePath = '/assets/maryweb', $width = 'auto', $height = 'auto')
    {
        echo '<img src="'. $this->_getAssetPath('images', $basePath, $imageFile) .'" alt="'. $imageFile .'" width="'. $width .'" height="'. $height .'">';
        return null;
    }

    protected function getAssetsService()
    {
        return $this->container->get('mary.webbundle.assets_service');
    }

    private function _getAssetPath($assetType, $basePath, $assetFile)
    {
        $assetsService = $this->getAssetsService();
        $assetsService->setAssetsBasePath($basePath);
        $path = $assetsService->getPackageByType($assetType)->getUrl($assetFile);
        return $path;
    }
}
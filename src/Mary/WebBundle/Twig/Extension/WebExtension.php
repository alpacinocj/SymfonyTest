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
            new \Twig_SimpleFunction('assetImg', [$this, 'assetImg']),
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

    public function assetCss($cssFile, $basePath = null)
    {
        $assetsService = $this->getAssetsService();
        if (null !== $basePath) {
            $assetsService->setAssetsBasePath($basePath);
        }
        echo "<link rel=\"stylesheet\" href=\"{$assetsService->getAssetPath($cssFile)}\">";
        return null;
    }

    public function assetJs($jsFile, $basePath = null)
    {
        $assetsService = $this->getAssetsService();
        if (null !== $basePath) {
            $assetsService->setAssetsBasePath($basePath);
        }
        echo "<script src=\"{$assetsService->getAssetPath($jsFile)}\"></script>";
        return null;
    }

    public function assetImg($imgFile, $basePath = null, $width = 'auto', $height = 'auto')
    {
        $assetsService = $this->getAssetsService();
        if (null !== $basePath) {
            $assetsService->setAssetsBasePath($basePath);
        }
        echo "<img src=\"{$assetsService->getAssetPath($imgFile)}\" alt=\"{$imgFile}\" width=\"{$width}\" height=\"{$height}\">";
        return null;
    }

    protected function getAssetsService()
    {
        return $this->container->get('mary.webbundle.assets_service');
    }


}
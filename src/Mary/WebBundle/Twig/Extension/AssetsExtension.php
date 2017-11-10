<?php

namespace Mary\WebBundle\Twig\Extension;

use Mary\WebBundle\Service\AssetsService;

class AssetsExtension extends \Twig_Extension
{
    private $assetsService;

    public function __construct(AssetsService $assetsService)
    {
        $this->assetsService = $assetsService;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('assetCss', [$this, 'assetCss']),
            new \Twig_SimpleFunction('assetJs', [$this, 'assetJs']),
            new \Twig_SimpleFunction('assetImg', [$this, 'assetImg']),
            new \Twig_SimpleFunction('uploadedImg', [$this, 'uploadedImg']),
        ];
    }

    public function assetCss($cssFile, $basePath = null)
    {
        if (null !== $basePath) {
            $this->assetsService->setAssetsBasePath($basePath);
        }
        echo "<link rel=\"stylesheet\" href=\"{$this->assetsService->getAssetPath($cssFile)}\">";
        return null;
    }

    public function assetJs($jsFile, $basePath = null)
    {
        if (null !== $basePath) {
            $this->assetsService->setAssetsBasePath($basePath);
        }
        echo "<script src=\"{$this->assetsService->getAssetPath($jsFile)}\"></script>";
        return null;
    }

    public function assetImg($imgFile, $basePath = null, $width = 'auto', $height = 'auto')
    {
        if (null !== $basePath) {
            $this->assetsService->setAssetsBasePath($basePath);
        }
        echo "<img src=\"{$this->assetsService->getAssetPath($imgFile)}\" alt=\"{$imgFile}\" width=\"{$width}\" height=\"{$height}\">";
        return null;
    }

    public function uploadedImg($targetFile, $width = 'auto', $height = 'auto')
    {
        return $this->assetImg($targetFile, 'uploads', $width, $height);
    }

}
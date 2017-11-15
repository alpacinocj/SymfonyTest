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
        $options = ['is_safe' => ['html']];
        return [
            new \Twig_SimpleFunction('assetCss', [$this, 'assetCss'], $options),
            new \Twig_SimpleFunction('assetJs', [$this, 'assetJs'], $options),
            new \Twig_SimpleFunction('assetImg', [$this, 'assetImg'], $options),
            new \Twig_SimpleFunction('uploadedImg', [$this, 'uploadedImg'], $options),
            new \Twig_SimpleFunction('uploadedThumbImg', [$this, 'uploadedThumbImg'], $options),
        ];
    }

    public function assetCss($cssFile, $basePath = null)
    {
        if (null !== $basePath) {
            $this->assetsService->setAssetsBasePath($basePath);
        }
        $link = "<link rel=\"stylesheet\" href=\"{$this->assetsService->getAssetPath($cssFile)}\">";
        return $link;
    }

    public function assetJs($jsFile, $basePath = null)
    {
        if (null !== $basePath) {
            $this->assetsService->setAssetsBasePath($basePath);
        }
        $script = "<script src=\"{$this->assetsService->getAssetPath($jsFile)}\"></script>";
        return $script;
    }

    public function assetImg($imgFile, $basePath = null, $width = 'auto', $height = 'auto')
    {
        if (null !== $basePath) {
            $this->assetsService->setAssetsBasePath($basePath);
        }
        $img = "<img src=\"{$this->assetsService->getAssetPath($imgFile)}\" alt=\"{$imgFile}\" width=\"{$width}\" height=\"{$height}\">";
        return $img;
    }

    public function uploadedImg($targetFile, $width = 'auto', $height = 'auto')
    {
        return $this->assetImg($targetFile, 'uploads', $width, $height);
    }

    public function uploadedThumbImg($targetFile, array $thumbSize)
    {
        $size = array_values($thumbSize);
        if (!isset($size[0]) || !isset($size[1])) {
            return null;
        }
        $baseFilename = basename($targetFile);
        list($filename, $extension) = explode('.', $baseFilename);
        $thumbFile = sprintf('%s_%s_%s.%s', $filename, $size[0], $size[1], $extension);
        $basePath = 'uploads/' . dirname($targetFile) . '/thumbs';
        return $this->assetImg($thumbFile, $basePath);
    }

}
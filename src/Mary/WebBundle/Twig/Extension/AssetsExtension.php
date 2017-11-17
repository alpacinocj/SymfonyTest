<?php

namespace Mary\WebBundle\Twig\Extension;

use Mary\WebBundle\Service\AssetsService;

class AssetsExtension extends \Twig_Extension
{
    private $assetsService;
    private $webConfig;

    public function __construct(AssetsService $assetsService, array $webConfig)
    {
        $this->assetsService = $assetsService;
        $this->webConfig = current($webConfig);
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

    public function uploadedThumbImg($targetFile, $thumbSizeKey)
    {
        $groupsConfig = $this->webConfig['uploads']['groups'];
        $group = $this->_getUplodedFileGroup($targetFile);

        if (!isset($groupsConfig[$group]['thumbnail_sizes'][$thumbSizeKey])) {
            // return original image
            return $this->uploadedImg($targetFile);
        }

        $thumbSize = $groupsConfig[$group]['thumbnail_sizes'][$thumbSizeKey];
        list($filename, $extension) = explode('.', basename($targetFile));
        $thumbFile = sprintf('%s_%s_%s.%s', $filename, $thumbSize['width'], $thumbSize['height'], $extension);
        $thumbFile = dirname($targetFile) . '/thumbs/' . $thumbFile;
        return $this->uploadedImg($thumbFile);
    }

    private function _getUplodedFileGroup($targetFile)
    {
        return substr($targetFile, 0, strpos($targetFile, '/'));
    }

}
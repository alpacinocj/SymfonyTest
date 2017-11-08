<?php

namespace Mary\WebBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Asset\Exception\InvalidArgumentException;

class AssetsService extends BaseService
{
    private $requestStack;
    private $assetsVersion;
    private $versionFormat;
    private $assetsBasePath;

    public function __construct(RequestStack $requestStack, $assetsVersion = null)
    {
        $this->requestStack = $requestStack;
        $this->assetsVersion = defined('APP_ENV_PROD') && APP_ENV_PROD ? $assetsVersion : $this->_getRandAssetsVersion();
        $this->versionFormat = null;
        $this->assetsBasePath = null;
    }

    private function _getRandAssetsVersion()
    {
        return microtime(true);
    }

    public function setVersionFormat($format)
    {
        $this->versionFormat = $format;
    }

    public function setAssetsBasePath($basePath)
    {
        $this->assetsBasePath = $basePath;
    }

    protected function getUrlPackage($path, $version = null, $format = null)
    {
        return new UrlPackage(
            $path,
            null === $version ? new EmptyVersionStrategy() : new StaticVersionStrategy($version, $format)
        );
    }

    protected function getPathPackage($path, $version = null, $format = null)
    {
        return new PathPackage(
            $path,
            null === $version ? new EmptyVersionStrategy() : new StaticVersionStrategy($version, $format)
        );
    }

    public function getAssetPath($assetFile)
    {
        $pathInfo = pathinfo($assetFile);
        $basePath = !empty($this->assetsBasePath) ? $this->assetsBasePath : $pathInfo['dirname'];
        $parse = parse_url($assetFile);
        if (isset($parse['scheme']) && isset($parse['host'])) {
            // absolute asset
            $package = $this->getUrlPackage($basePath, $this->assetsVersion, $this->versionFormat);
        } else {
            $package = $this->getPathPackage($basePath, $this->assetsVersion, $this->versionFormat);
        }
        $path = $package->getUrl($pathInfo['basename']);
        $this->_reset();
        return $path;
    }

    private function _reset()
    {
        $this->versionFormat = null;
        $this->assetsBasePath = null;
    }

}
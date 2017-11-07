<?php

namespace Mary\WebBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Asset\Exception\InvalidArgumentException;

class AssetsService extends BaseService
{
    private $requestStack;
    private $assetsVersion;
    private $versionFormat = null;
    private $assetsBasePath;
    private $allowAssetTypes = ['css', 'js', 'images'];

    public function __construct(RequestStack $requestStack, $assetsVersion = null)
    {
        $this->requestStack = $requestStack;
        $this->assetsVersion = defined('APP_ENV_PROD') && APP_ENV_PROD ? $assetsVersion : $this->_getRandAssetsVersion();
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

    protected function getPackage($path, $version = null, $format = null)
    {
        return new PathPackage(
            $path,
            null === $version ? new EmptyVersionStrategy() : new StaticVersionStrategy($version, $format)
        );
    }

    public function getPackageByType($assetType)
    {
        $assetType = strtolower($assetType);
        if (!in_array($assetType, $this->allowAssetTypes)) {
            throw new InvalidArgumentException('Asset type should be in the range: ' . implode(',', $this->allowAssetTypes));
        }
        return $this->getPackage($this->assetsBasePath . '/' . $assetType, $this->assetsVersion, $this->versionFormat);
    }

    public function getCssPackage()
    {
        return $this->getPackageByType('css');
    }

    public function getJsPackage()
    {
        return $this->getPackageByType('js');
    }

    public function getImagePackage()
    {
        return $this->getPackageByType('images');
    }
}
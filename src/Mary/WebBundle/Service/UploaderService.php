<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use JBZoo\Utils\Str as StrUtil;
use Symfony\Component\Yaml\Yaml;

class UploaderService extends BaseService
{
    private $config;
    private $options;
    private $filename;
    private $realPath;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $webConfig = Yaml::parse(file_get_contents(APP_CONFIG_PATH . 'config_web.yml'));
        $webConfig = current($webConfig);
        if (!isset($webConfig['uploads'])) {
            throw new FileException('Uploads Configuration Invalid.');
        }
        $this->config = $webConfig['uploads'];
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function enableThumbnail($group)
    {
        if (isset($this->config['groups'][$group])) {
            $this->config['groups'][$group]['thumbnail_enabled'] = true;
        }
        return $this;
    }

    public function disableThumbnail($group)
    {
        if (isset($this->config['groups'][$group])) {
            $this->config['groups'][$group]['thumbnail_enabled'] = false;
        }
        return $this;
    }

    public function getRealPath()
    {
        return $this->realPath;
    }

    public function upload(UploadedFile $file, $group)
    {
        // 检查Group是否已配置
        if (!$this->checkGroup($group)) {
            throw new FileException(sprintf('%s对应分组未正确配置', $group));
        }

        // 检查上传文件类型 TODO

        $filename = $this->rename($file);
        $targetPath = $this->makeTargetPath($group);
        $file->move($targetPath, $this->filename);
        $this->realPath = realpath($targetPath . '/' . $this->filename);

        // resize if enable
        if ($this->enableResize($group)) {
            $this->resize($group);
        }

        // file path will be store in database, eg: group/20171121/lsjfl2342ljlds.png
        $path = $this->getFilePathByGroup($targetPath, $group);
        return $path;
    }

    protected function checkGroup($group)
    {
        return in_array($group, array_keys($this->config['groups']));
    }

    protected function enableResize($group)
    {
        return isset($this->config['groups'][$group]['thumbnail_enabled']) && $this->config['groups'][$group]['thumbnail_enabled'];
    }

    protected function resize($group)
    {
        if (
            !isset($this->config['groups'][$group]['thumbnail_sizes']) ||
            empty($this->config['groups'][$group]['thumbnail_sizes'])
        ) {
            return false;
        }

        try {
            $imagick = new \Imagick();
            foreach ($this->config['groups'][$group]['thumbnail_sizes'] as $size) {
                $imagick->readImage($this->realPath);
                $imagick->resizeImage($size['width'], $size['height'], \Imagick::FILTER_LANCZOS, 1);
                $thumbFile = $this->getThumbFilename($size['width'], $size['height']);
                $imagick->writeImage($thumbFile);
            }
            $imagick->clear();
            $imagick->destroy();
        } catch (\Exception $e) {
            throw new FileException($e->getMessage());
        }

        return true;
    }

    protected function makeThumbFilePath()
    {
        $path = dirname($this->realPath) . '/thumbs';
        if (!is_dir($path)) {
            mkdir($path, 0666, true);
        }
        return $path;
    }

    protected function getThumbFilename($thumbWidth, $thumbHeight)
    {
        $thumbPath = $this->makeThumbFilePath();
        list($filename, $extension) = explode('.', $this->filename);
        return sprintf('%s_%s_%s.%s', $thumbPath . '/' . $filename, $thumbWidth, $thumbHeight, $extension);
    }

    protected function rename(UploadedFile $file)
    {
        $this->filename = StrUtil::random(12) . '.' . $file->guessClientExtension();
        return $this->filename;
    }

    protected function getFilePathByGroup($targetPath, $group)
    {
        return substr($targetPath, strpos($targetPath, $group)) . '/' . $this->filename;
    }

    public function makeTargetPath($group)
    {
        $group = trim($group, '/');
        $targetPath = APP_UPLOADS_PATH . $group . '/' . date('Ymd');
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0666, true);
        }
        return $targetPath;
    }

}
<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use JBZoo\Utils\Str as StrUtil;

class UploaderService extends BaseService
{
    private $uploadsDir;
    private $options;
    private $filename;
    private $realPath;

    public function __construct($uploadsDir, array $options)
    {
        $this->uploadsDir = rtrim($uploadsDir, '/');
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'thumb_switch' => false,
            'thumb_size' => [
                ['width' => '20', 'height' => '20'],
            ]
        ]);
    }

    public function closeThumbSwitch()
    {
        $this->options['thumb_switch'] = false;
        return $this;
    }

    public function upload(UploadedFile $file, $group)
    {
        $this->rename($file);
        $targetPath = $this->getTargetPath($group);
        $file->move($targetPath, $this->filename);
        $path = $this->getFilePathByGroup($targetPath, $group, $this->filename);
        $this->realPath = realpath($this->uploadsDir . '/' . $path);
        if ($this->options['thumb_switch']) {
            $this->resize();
        }
        return $path;
    }

    protected function resize()
    {
        $imagick = new \Imagick($this->realPath);
        $w = $imagick->getImageWidth();
        $h = $imagick->getImageHeight();
        foreach ($this->options['thumb_size'] as &$item) {
            $item['width'] = $item['width'] ?? $w;
            $item['height'] = $item['height'] ?? $h;
            $imagick->resizeImage($item['width'], $item['height'], \Imagick::FILTER_LANCZOS, 1);
            $thumbFile = $this->getThumbFilename($item['width'], $item['height']);
            $imagick->writeImage($thumbFile);
        }
        $imagick->clear();
        $imagick->destroy();
    }

    protected function getFullPath()
    {
        return $this->uploadsDir . '/' . $this->filename;
    }

    protected function getThumbFilename($thumbWidth, $thumbHeight)
    {
        list($filename, $extension) = explode('.', $this->filename);
        $dirname = dirname($this->realPath) . '/thumbs';
        if (!is_dir($dirname)) {
            mkdir($dirname, 0666, true);
        }
        return sprintf('%s_%s_%s.%s', $dirname . '/' . $filename, $thumbWidth, $thumbHeight, $extension);
    }

    protected function rename(UploadedFile $file)
    {
        $this->filename = StrUtil::random(12) . '.' . $file->guessClientExtension();
        return $this->filename;
    }

    protected function getFilePathByGroup($targetPath, $group, $filename)
    {
        return substr($targetPath, strpos($targetPath, $group)) . '/' . $filename;
    }

    public function getTargetPath($group)
    {
        $group = trim($group, '/');
        return $this->uploadsDir . '/' . $group . '/' . date('Ymd');
    }

}
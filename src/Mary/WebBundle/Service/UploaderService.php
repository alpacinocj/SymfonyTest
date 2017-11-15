<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
                ['width' => '20', 'height' => '20']
            ]
        ]);
    }

    public function openThumbSwitch()
    {
        $this->options['thumb_switch'] = true;
        return $this;
    }

    public function closeThumbSwitch()
    {
        $this->options['thumb_switch'] = false;
        return $this;
    }

    public function setThumbSize(array $sizeArr)
    {
        $this->options['thumb_size'] = $sizeArr;
        return $this;
    }

    public function getRealPath()
    {
        return $this->realPath;
    }

    public function upload(UploadedFile $file, $group)
    {
        $filename = $this->rename($file);
        $targetPath = $this->getTargetPath($group);
        $file->move($targetPath, $this->filename);
        $this->realPath = realpath($targetPath . '/' . $this->filename);
        // file path will be store in database, eg: group/20171121/lsjfl2342ljlds.png
        $path = $this->getFilePathByGroup($targetPath, $group);
        if ($this->options['thumb_switch']) {
            $this->resize();
        }
        return $path;
    }

    protected function resize()
    {
        try {
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
        } catch (\Exception $e) {
            throw new FileException($e->getMessage());
        }
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

    public function getTargetPath($group)
    {
        $group = trim($group, '/');
        $targetPath = $this->uploadsDir . '/' . $group . '/' . date('Ymd');
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0666, true);
        }
        return $targetPath;
    }

}
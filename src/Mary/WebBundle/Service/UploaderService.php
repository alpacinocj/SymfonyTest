<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use JBZoo\Utils\Str as StrUtil;
use Symfony\Component\Yaml\Yaml;
use Mary\Common\Util\Debugger;

class UploaderService extends BaseService
{
    const WATERMARK_MAX_FONT_SIZE = 20;
    const WATERMARK_MIN_FONT_SIZE = 8;
    const WATERMARK_TEXT_PROPORTION = 0.04;
    const WATERMARK_MARGIN_PROPORTION = 1.5;

    private $config;
    private $options;
    private $filename;
    private $realPath;

    public function __construct(array $webConfig, array $options = [])
    {
        $webConfig = current($webConfig);
        if (!isset($webConfig['uploads'])) {
            throw new FileException('Uploads Configuration Invalid.');
        }
        $this->config = $webConfig['uploads'];

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
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

        // 检查上传文件类型
        if (!$this->checkMimeType($file)) {
            throw new FileException('该类型文件不允许上传');
        }

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

    protected function checkMimeType(UploadedFile $file)
    {
        if (isset($this->config['allowed_mime_types']) && !empty($this->config['allowed_mime_types'])) {
            return in_array($file->getMimeType(), $this->config['allowed_mime_types']);
        }
        return true;
    }

    protected function enableResize($group)
    {
        return $this->config['groups'][$group]['thumbnail_enabled'];
    }

    protected function enableWatermark($group)
    {
        return $this->config['groups'][$group]['watermark_enabled'] && $this->config['groups'][$group]['watermark_target'];
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

                // 加水印
                if ($this->enableWatermark($group)) {
                    if (file_exists($this->config['groups'][$group]['watermark_target'])) {
                        // 图片水印 TODO

                    } else {
                        // 文字水印
                        $draw = $this->addTextWatermark($imagick, $group);
                    }

                    $imagick->drawImage($draw);
                }

                $imagick->writeImage($thumbFile);
            }
            $imagick->clear();
            $imagick->destroy();
        } catch (\Exception $e) {
            throw new FileException($e->getMessage());
        }

        return true;
    }

    // 计算水印边距
    private function _getWatermarkMargin()
    {

    }

    // 计算合适水印字体大小
    private function _getWatermarkFontSize($imgWidth, $imgHeight)
    {
        $min = min($imgWidth, $imgHeight);
        $size = ceil($min * self::WATERMARK_TEXT_PROPORTION);
        return ($size > self::WATERMARK_MAX_FONT_SIZE) ? self::WATERMARK_MAX_FONT_SIZE : ($size < self::WATERMARK_MIN_FONT_SIZE ? self::WATERMARK_MIN_FONT_SIZE : $size);
    }

    protected function addTextWatermark(\Imagick $imagick, $group)
    {
        $fontText = $this->config['groups'][$group]['watermark_target'];
        $fontPos = strtolower($this->config['groups'][$group]['watermark_position']);

        $width = $imagick->getImageWidth();
        $height = $imagick->getImageHeight();

        $draw = new \ImagickDraw();
        $draw->setFillColor(new \ImagickPixel('white'));
        $draw->setFontSize($this->_getWatermarkFontSize($width, $height));

        // 计算字体边界
        $metrics = $imagick->queryFontMetrics($draw, $fontText);
        $textWidth = $metrics['textWidth'];
        $textHeight = $metrics['textHeight'];

        // 计算起始坐标点
        $x = $y = 0;
        switch ($fontPos) {
            case 'center':
                $x = ($width - $textWidth) / 2;
                $y = ($height - $textHeight) / 2 + $textHeight / 2;
                break;
            case 'top':
                $x = ($width - $textWidth) / 2;
                $y = $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                break;
            case 'right':
                $x = $width - $textWidth - $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                $y = ($height - $textHeight) / 2 + $textHeight / 2;
                break;
            case 'bottom':
                $x = ($width - $textWidth) / 2;
                $y = $height - $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                break;
            case 'left':
                $x = $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                $y = ($height - $textHeight) / 2 + $textHeight / 2;
                break;
            case 'top-left':
                $x = $y = $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                break;
            case 'top-right':
                $x = $width - $textWidth - $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                $y = $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                break;
            case 'bottom-left':
                $x = $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                $y = $height - $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                break;
            case 'bottom-right':
                $x = $width - $textWidth - $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                $y = $height - $textHeight * self::WATERMARK_MARGIN_PROPORTION;
                break;
            default:
                break;
        }

        $draw->annotation($x, $y, $fontText);

        return $draw;
    }

    protected function addImageWatermark()
    {

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
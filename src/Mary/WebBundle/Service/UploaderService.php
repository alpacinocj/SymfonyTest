<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JBZoo\Utils\Str as StrUtil;

class UploaderService extends BaseService
{
    private $uploadsDir;

    public function __construct($uploadsDir)
    {
        $this->uploadsDir = rtrim($uploadsDir, '/');
    }

    public function upload(UploadedFile $file, $group)
    {
        $filename = $this->rename($file);
        $targetPath = $this->getTargetPath($group);
        $file->move($targetPath, $filename);
        return $this->getFilenameByGroup($targetPath, $group, $filename);
    }

    protected function rename(UploadedFile $file)
    {
        return StrUtil::random(12) . '.' . $file->guessClientExtension();
    }

    protected function getFilenameByGroup($targetPath, $group, $filename)
    {
        return substr($targetPath, strpos($targetPath, $group)) . '/' . $filename;
    }

    public function getTargetPath($group)
    {
        $group = trim($group, '/');
        return $this->uploadsDir . '/' . $group . '/' . date('Ymd');
    }

}
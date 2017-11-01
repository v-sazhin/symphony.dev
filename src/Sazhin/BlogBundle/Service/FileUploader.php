<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 01.11.2017
 * Time: 12:38
 */

namespace Sazhin\BlogBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file) :string
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
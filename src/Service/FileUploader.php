<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $uploadsAbsoluteDir;
    private $slugger;

    public function __construct(
        SluggerInterface $slugger,
        string $uploadsAbsoluteDir
    ) {
        $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
        $this->slugger = $slugger;
    }


    public function upload($file)
    {

        $fileName = sprintf(
            "%s_%s.%s",
            $this->slugger->slug($file->getClientOriginalName()),
            uniqid(),
            $file->getClientOriginalExtension()
        );

        try {

            $file->move($this->uploadsAbsoluteDir, $fileName);
        } catch (FileException $e) {
            // message d'erreur
        }


        return $fileName;
    }
}

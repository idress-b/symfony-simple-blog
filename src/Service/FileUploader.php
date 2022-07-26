<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class FileUploader
{
    private $uploadsAbsoluteDir;
    private $slugger;
    private $s3Key;
    private $s3Secret;
    private $s3Bucket;

    public function __construct(
        SluggerInterface $slugger,
        string $uploadsAbsoluteDir
      
    ) {
        $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
        $this->slugger = $slugger;
        $this->s3Key = $_ENV['AWS_KEY'];
        $this->s3Secret = $_ENV['AWS_SECRET'];
        $this->s3Bucket = $_ENV['AWS_BUCKET'];
    }


    public function upload($file)
    {
       
        $fileName = sprintf(
            "%s_%s.%s",
            $this->slugger->slug($file->getClientOriginalName()),
            uniqid(),
            $file->getClientOriginalExtension()
        );
      
        $s3 = new S3Client([
            'region'  => 'eu-west-3',
            'version' => 'latest',
            'credentials' => [
                'key'    => $this->s3Key,
                'secret' => $this->s3Secret,
            ]
        ]);		
        try
            {
                $result = $s3->putObject([
                'Bucket' => $this->s3Bucket,
                'Key'    => $fileName,
               'SourceFile' => $file->getpathname() 
               		
                ]);
            }
        catch(S3Exception $e)
            {
                echo $e;
            }
   

       
        return $fileName;
    }
   
    public function deleteFile($fileName)
    {
       
             
        $s3 = new S3Client([
            'region'  => 'eu-west-3',
            'version' => 'latest',
            'credentials' => [
                'key'    => $this->s3Key,
                'secret' => $this->s3Secret,
            ]
        ]);		
        try
            {
                $result = $s3->deleteObject([
                'Bucket' => $this->s3Bucket,
                'Key'    => $fileName
                          		
                ]);
            }
        catch(S3Exception $e)
            {
                echo $e;
            }
        
      
    }

}

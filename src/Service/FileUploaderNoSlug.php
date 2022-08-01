<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class FileUploaderNoSlug
{
    
   
    private $s3Key;
    private $s3Secret;
    private $s3Bucket;

    public function __construct(
      
    ) {
       
        $this->s3Key = $_ENV['AWS_KEY'];
        $this->s3Secret = $_ENV['AWS_SECRET'];
        $this->s3Bucket = $_ENV['AWS_BUCKET'];
    }


    public function upload($file, $uploadDir,$filename)
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
                $result = $s3->putObject([
                'Bucket' => $this->s3Bucket,
                'Key'    => $filename,
               'SourceFile' => $file->getpathname() 
               		
                ]);
            }
        catch(S3Exception $e)
            {
                echo $e;
            }
         
       
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

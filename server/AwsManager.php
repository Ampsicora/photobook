<?php

namespace CloudPhoto;

require_once '../credentials.php';
require_once '../aws/aws-autoloader.php';

use CloudPhoto\AWS_ACCESS_KEY_ID;
use CloudPhoto\AWS_SECRET_ACCESS_KEY;
use CloudPhoto\AWS_REGION;
use Aws\S3\S3Client;
use Aws\Rekognition\RekognitionClient; 
use Aws\Exception\AwsException;

class AwsManager
{
    public $s3;
    public $rekognition;
    private $currentBucketName;
    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => AWS_REGION,
            'credentials' => [
                'key' => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY
            ]
        ]);
        $this->currentBucketName = 'bucket-of-cloud-photo';
        $this->rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => AWS_REGION,
            'credentials' => [
                'key' => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY
            ]
        ]);
    }

    public function addNewBucket()
    {
        try
        {
            $bucket = $this->s3->createBucket([
                'Bucket' => $this->currentBucketName
            ]);
        }
        catch (AwsException $ex)
        {
            print($ex->getMessage());
        }
    }

    public function checkIfBucketExists()
    {
        try
        {
            $bucketList = $this->s3->listBuckets();
            foreach($bucketList as $bucket)
            {
                if ($bucket[0]['Name'] == $this->currentBucketName)
                {
                    return true;
                }
            }
            return false;
        }
        catch (AwsException $ex)
        {
            print($ex->getMessage());
        }
    }

    public function getSingleImage($imgName)
    {
        try {
            $result = $this->s3->getObject([
                'Bucket' => $this->currentBucketName,
                'Key'=> $imgName,
                'SaveAs' => '../uploads/tmpImage.jpeg'
            ]);
            return $result;
        }
        catch (AwsException $ex)
        {
            print($ex->getMessage());
        }
    }

    public function uploadImage($imgPath, $imgName)
    {
        $key = basename($imgPath);
        try {
            $this->s3->putObject([
                'Bucket' => $this->currentBucketName,
                'Key' => $key,
                'SourceFile' => $imgPath,
                'ACL' => 'private'
            ]);
        }
        catch (AwsException $ex)
        {
            print($ex->getMessage());
        }
    }

    public function scanImage($bucketObjectName)
    {
        try 
        {
            $result = $this->rekognition->detectLabels([
                'Image' => [
                    'S3Object' => [
                        'Bucket' => $this->currentBucketName,
                        'Name' => $bucketObjectName
                    ],
                ],
                'MinConfidence' => 70
            ]);

            return $result;
        }
        catch (AwsException $ex)
        {
            print($ex->getMessage());
        }
    }

    public function getPublicToken($imgName)
    {
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->currentBucketName,
            'Key' => $imgName
        ]);

        $request = $this->s3->createPresignedRequest($cmd, '+5 minutes');

        $presignedUrl = (string)$request->getUri();
        return $presignedUrl;
    }
}
?>
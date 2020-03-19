<?php

namespace CloudPhoto;

require_once '../server/AwsManager.php';

use CloudPhoto\AwsManager;
use CloudPhoto\LocalDbManager;

$toUpload = $_FILES['inputUpload'];
$newPath = '../uploads/'.$_FILES['inputUpload']['name'];

move_uploaded_file($_FILES['inputUpload']['tmp_name'], $newPath);

$awsManager = new AwsManager();
$dbManager = new LocalDbManager();
$doesBucketExists = $awsManager->checkIfBucketExists();
if ($doesBucketExists == true)
{
    $awsManager->uploadImage($newPath, $toUpload['name']);

    header('Location: ../user/photo-tags.php?pic='.$toUpload['name']);
}
else if ($doesBucketExists == false)
{
    $awsManager->addNewBucket();
    $awsManager->uploadImage($newPath, $toUpload['name']);

    header('Location: ../user/photo-tags.php?pic='.$toUpload['name']);
}
?>
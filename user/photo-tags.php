<?php

namespace CloudPhoto;

require_once '../server/AwsManager.php';

use CloudPhoto\AwsManager;

$awsManager = new AwsManager();
$imgUrl = $awsManager->getSingleImage($_GET['pic']);
$tags = $awsManager->scanImage($_GET['pic']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reset.css">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">    
    <script src="https://kit.fontawesome.com/1a0ef5ee31.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/clientStyle.css">
    <script>function showLink() { alert('Here\'s your public token: <?php echo $awsManager->getPublicToken($_GET['pic'])?>') }</script>
    
    <title>Photo Tags</title>
</head>
<body>
    <div class="container m-auto pt-5 col-10">
        <h1 class="text-center text-white" style="font-size: 30px">Your photo...</h1>
        <div class="card w-90 h-90">
            <div class="card-body cardUploaded" style="background-image: url('../uploads/tmpImage.jpeg'); background-size: contain; background-color: transparent;">
            </div>
            <div class="card-footer overflow-auto h-10">
                <p>
                    <?php
                    foreach($tags['Labels'] as $tag)
                    {
                        echo $tag['Name'].", Confidence: ".$tag['Confidence']."; ";
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="container d-flex m-auto justify-content-center">
                <button class="btn button-white" onclick="showLink()">Rendi pubblico</button>
        </div>
    </div>
</body>
</html>
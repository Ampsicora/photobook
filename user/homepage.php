<?php

session_start();

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
    <script src="../client/uploadImage.js"></script>
    <title>Home</title>
</head>
<body>
    <div class="container m-auto pt-5 col-8 ">
        <h1 class="text-center text-white" style="font-size: 30px">Hello, <?php print($_SESSION['USERNAME']); ?>!</h1>
        <div class="container row justify-content-around overflow-auto mainContainer mb-5">
            <div class="card" style="background: transparent">
                <div class="card-body" id="cardUploading">
                </div>
                <div class="card-footer" style="background-color: transparent">
                    <form action="../client/uploadImageScript.php" method="post" enctype="multipart/form-data">
                    <button id="btnUpload" class="btn button-white">Select a file...</button>
                    <input type="file" name="inputUpload" id="inputUpload" style="display: none" accept=".jpg, .jpeg. png">
                    <button type="submit" class="btn button-white">Upload image</button>
                    </form>
                </div>
            </div>
            <div class="card" style="background: transparent">
                <div class="card-body" id="cardSearch">
                </div>
                <div class="card-footer" style="background-color: transparent">
                <form action="#" method="post">
                    <button type="submit" class="btn button-white">Search image</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
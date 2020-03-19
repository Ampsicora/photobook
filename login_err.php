<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Photo</title>
    <link rel="stylesheet" href="../styles/reset.css">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/clientStyle.css">
    <script src="./client/loginForm.js"></script>
</head>
<body>
    <div class="container m-auto pt-5 col-6">
        <h1 class="text-center text-white" style="font-size: 50px">Cloud Photo</h1>
        <p class="pt-3 pb-3" style="color: red">Ops! Login attempt failed. Try again.</p>
        <form action="./client/loginScript.php" method="post" class="pt-5">
            <div class="form-group">
                <input type="text" class="form-control" name="userName" id="userName" placeholder="Enter username...">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password...">
            </div>
            <button type="submit" class="btn button-white">Log-In</button>
            <button id="btnBack" class="btn button-white">Back</button>
        </form>
    </div>
</body>
</html>
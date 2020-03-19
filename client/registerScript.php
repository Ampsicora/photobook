<?php
namespace CloudPhoto;

require_once '..\server\LocalDbManager.php';


use CloudPhoto\LocalDbManager;

$userName = $_POST['userName'];
$password = $_POST['password'];

$dbManager = new LocalDbManager();

$retVal = $dbManager->insertNewUser($userName, $password);

if ($retVal)
    echo('Success');
else
    echo('Failure');
?>
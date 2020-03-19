<?php

namespace CloudPhoto;

require_once '../server/LocalDbManager.php';
require_once '../credentials.php';

use CloudPhoto\LocalDbManager;

session_start();

$userName = $_POST['userName'];
$password = $_POST['password'];

$dbManager = new LocalDbManager();

$retVal = $dbManager->getUser($userName, $password);

if ($retVal != false)
{
    $_SESSION['ID_UTENTE'] = $retVal['ID_UTENTE'];
    $_SESSION['USERNAME'] = $retVal['USERNAME'];
    header('Location: ../user/homepage.php');
}
else
{
    header('Location: ../login_err.php');
}
?>
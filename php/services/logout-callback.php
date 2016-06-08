<?php
session_start();
$callbackUrl=$_SESSION["callback_url"];
session_destroy();
header('Location: '.$callbackUrl);
?>
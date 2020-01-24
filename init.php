<?php
require_once "vendor/autoload.php";

use App\SimpleEncryption;
use App\SimpleDecryption;
if (isset($_POST['enc'])) {
//    var_dump($_FILES['files']);
    new SimpleEncryption($_FILES['files']);
}
if(isset($_POST['dec'])){
    new SimpleDecryption($_FILES['files1']);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post" action="" enctype="multipart/form-data">
    <input type="file" name="files" >
    <input type="submit" name="enc">
</form>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="files1">
    <input type="submit" name="dec">
</form>
</body>
</html>

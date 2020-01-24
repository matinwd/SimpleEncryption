<?php

namespace App;
use SimpleInterface;
class SimpleDecryption implements \SimpleInterface
{
    /*
    * @parameter $file
    *
    */
    public $file;

    /*
     * @parameter $whiteList
     *
     */
    public $whiteList = ['plain/txt'];

    public function __construct($file)
    {
        $this->file = $file;
        $this->doInit($file);
    }


    public final function uploadFile(array $input): string
    {
        $dir = dirname(__DIR__) . '/locked/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $tmpFile = $input['tmp_name'];
        $newFile = $dir . time() . '-' . $input['name'];
        $up = move_uploaded_file($tmpFile, $newFile);
        if ($up) {
            return $newFile;
        }
        return false;
    }

    function decryption($file)
    {
        $fileName = explode('-', basename($file));
        $fileName = $fileName[1];
        $fileName = mb_substr($fileName, 0, 4);
        $dir = dirname(__DIR__) . '/unLocked/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $newFileName = $dir . mb_substr(md5(sha1(rand(121, 12313))), 0, 7) . '.txt';
        $file = str_split(file_get_contents($file), 3);
//        echo $file;
        $this->openAndWrite(
            ['fileName' => $fileName, 'newFileName' => $newFileName, 'file' => $file]
        );
        return $newFileName;
//    die();
    }

    function openAndWrite(array $arr)
    {
        $fh = fopen($arr['newFileName'], 'a+');
        foreach ($arr['file'] as $item) {
            $item = (int)$item - base64_decode($arr['fileName']);
            $item = mb_chr($item, 'utf8');
            fwrite($fh, $item);
        }
        fclose($fh);
    }

    final function downloadFile(string $file): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush(); // Flush system output buffer
        readfile($file);
    }

    public final function doInit(array $file): void
    {
        $valid = $this->validate($file);
        if(!$valid){
            die('please insert a true file');
        }
        $file = $this->uploadFile($file);
        $file = $this->decryption($file);
        $this->downloadFile($file);
    }

    public final function validate(array $file): bool
    {
        $fileExt = $file['type'];
        if (in_array($fileExt, $this->whiteList)) {
            return true;
        }
        die('please insert a text/plain file');
    }
}
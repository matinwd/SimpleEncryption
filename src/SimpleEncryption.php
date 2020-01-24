<?php

namespace App;
use SimpleInterface;
class SimpleEncryption implements \SimpleInterface
{
    /*
     * @parameter $file
     */
    public $file;

    /*
     * @parameter $whiteList
     */
    public $whiteList = ['text/plain'];

    public function __construct($file)
    {
//        echo "awdj";
        $this->file = $file;
        $this->doInit($this->file);
    }

    public final function downloadFile(string $file): void
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

    public final function uploadFile(array $input): string
    {
        if (!is_dir(dirname(__DIR__) . 'files')) {
//            echo 'file';
            mkdir(dirname(__DIR__) . 'files');
        }
        $dir = dirname(__DIR__) . 'files/';

        $tmpFile = $input['tmp_name'];
        $newFile = $dir . time() . $input['name'];
        $up = move_uploaded_file($tmpFile, $newFile);
        if ($up) {
            return $newFile;
        }
        return false;
    }

    public final function encryption(string $file): string
    {
        $number = rand(123, 321) + 134;
        if (!is_dir(dirname(__DIR__) . '/locked/')) {
            mkdir(dirname(__DIR__) . '/locked/');
        }

        $dirName = dirname(__DIR__) . '/locked/' . base64_encode($number) . 1 . '()' . '.txt';
        $fh = fopen($dirName, 'a+');
        $file = str_split(file_get_contents($file));
        foreach ($file as $item) {
            $item = mb_ord($item, 'utf8');
            $item = $item + $number;
            fwrite($fh, $item);
        }
        fclose($fh);
        return $dirName;
    }

    public final function doInit(array $file): void
    {
        $valid = $this->validate($file);
        if ($valid) {
            die("please insert a valid type");
        }
        $file = $this->uploadFile($file);
        $file = $this->encryption($file);
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
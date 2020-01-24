<?php
interface SimpleInterface{
    public function downloadFile(string $file) :void;

    public function uploadFile(array $input) :string;

    public function doInit(array $file) :void;

    public function validate(array $file) :bool ;
}
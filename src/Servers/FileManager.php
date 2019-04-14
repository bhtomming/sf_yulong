<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/14
 * Time: 14:00
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $targetDir;

    public function __construct($targetDir){
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file){
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir,$fileName);
        return $fileName;
    }

}
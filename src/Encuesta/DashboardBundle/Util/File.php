<?php
namespace Encuesta\DashboardBundle\Util;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
    public function uploadFile($file, $dir = '/uploads', $name = '_random', $ext = '_default')
    {
        $file_name = $name;

        if($file === null) {
            $file_name = null;
        }
        else {
            switch($name) {
                case '_random': $file_name = sha1(uniqid(mt_rand(), true));
                    break;
                case '_default': $file_name = $file->getClientOriginalName();
                    break;
            }

            switch($ext) {
                case '_default': $file_name .= '.'.$file->getClientOriginalExtension();
                    break;
                default: $file_name .= '.'.$ext;
                    break;
            }

            if(!is_dir($dir))
                mkdir($dir);

            $file->move($dir, $file_name);
        }

        return $file_name;
    }

    public function uploadFormFiles(FormInterface $form, $dir)
    {
        foreach($form->getFiles() as $file) {
            $this->uploadFile($file, $dir);
        }
    }
}
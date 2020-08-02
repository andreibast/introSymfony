<?php

namespace App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {

    /**
     * @var ContainerInterface
     */
    private $container;

    //we need to inject something in our controller
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function uploadFile(UploadedFile $file){

        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();


        $file->move(//we move the file somewhere
            $this->container->getParameter('uploads_dir'),
            $filename
        );

        return $filename;
    }
}
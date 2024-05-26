<?php

namespace App\Service;

use Arhitector\Yandex\Client\OAuth;
use Arhitector\Yandex\Disk;

class YandexDiskService
{
    private OAuth $client;
    private Disk $disk;

    public function __construct(string $token)
    {
        $this->client = new OAuth($token);
        $this->disk = new Disk($this->client);
    }

    public function uploadFile(string $imageFileName, $container)
    {

        $projectDir = $container->getParameter('kernel.project_dir');
//        $pathImage = realpath($projectDir . '/public/uploads/images/' . $imageFileName);
//        $fileName = $this->getFileName($pathImage);

//dump($projectDir);
//dump($pathImage);
//dump($fileName);
//exit();

        $resource = $this->disk->getResource('test.txt');
        $resource->upload($imageFileName);

        $response = $resource->toArray();

//        $this->deleteLocalFile($pathImage);
//dd($response);

        return $response['file'];
    }

    public function getFileName($pathImage): string
    {
        $fileInfo = pathinfo($pathImage);
        return  $fileInfo['basename'];
    }

    public function deleteLocalFile($pathImage): void
    {
        if (file_exists($pathImage)) {
            unlink($pathImage);
        }
    }
}

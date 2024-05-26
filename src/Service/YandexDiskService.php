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

    public function uploadFile(string $pathImage)
    {
        $fileName = $this->getFileName($pathImage);
        $resource = $this->disk->getResource($fileName);
        if (file_exists($pathImage)) {

            $resource->upload($pathImage);
            $response = $resource->toArray();

            $this->deleteLocalFile($pathImage);
        } else {
            echo "Файл не существует.";
        }


        return $response['file'];
    }

    public function getFileName($pathImage): string
    {
        $fileInfo = pathinfo($pathImage);
        $fileName = $fileInfo['filename'];

        $fileInfo = pathinfo($pathImage);
        $fileExtension = $fileInfo['extension'];

        return $fileName . '.' . $fileExtension;
    }

    public function deleteLocalFile($pathImage): void
    {
        if (file_exists($pathImage)) {
            unlink($pathImage);
        }
    }
}

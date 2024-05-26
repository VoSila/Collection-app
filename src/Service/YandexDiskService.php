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

    public function uploadFile(string $imageFile, string $imageFileName)
    {
        $resource = $this->disk->getResource($imageFileName);
        $resource->upload($imageFile);

        $response = $resource->toArray();

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

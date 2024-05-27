<?php

namespace App\Service;

use Spatie\Dropbox\Client;

class DropboxService
{
    private Client $client;

    public function __construct(string $token)
    {
        $this->client = new Client($token);
    }

    public function uploadFile($imageFile, string $imageFileName)
    {
        $stream = fopen($imageFile->getRealPath(), 'r+');
        $this->client->upload($imageFileName, $stream );

        $sharedLink = $this->client->createSharedLinkWithSettings($imageFileName, [
            'requested_visibility' => 'public'
        ]);

        return $this->changeDownloadParameter($sharedLink['url']);

    }

    function changeDownloadParameter($url) {
        $parts = explode('?', $url);
        if (count($parts) > 1) {
            $params = explode('&', $parts[1]);

            foreach ($params as &$param) {
                list($key, $value) = explode('=', $param);
                if ($key === 'dl' && $value === '0') {
                    $value = '1';
                }
                $param = $key . '=' . $value;
            }
            return $parts[0] . '?' . implode('&', $params);
        }
        return $url;
    }
}

<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Storage;

class FirebaseStorageService
{
    private Storage $storage;

    public function __construct($firebaseCredentialsPath)
    {
        $factory = (new Factory())
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        $this->storage = $factory;
    }

    public function uploadFile($file, $destination): string
    {
        $bucket = $this->storage->getBucket();

        $fileId = uniqid('file_');

        $upload = $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            ['name' => $destination . '/' . $fileId]
        );

        $currentTimestamp = time();
        $currentDatetime = date('Y-m-d H:i:s', $currentTimestamp);
        $futureTimestamp = $currentTimestamp + 300;
        $futureDatetime = date('Y-m-d H:i:s', $futureTimestamp);

        $publicUrl = $bucket->object($destination . '/' . $fileId)->signedUrl($futureDatetime);

        return $publicUrl;
    }
}

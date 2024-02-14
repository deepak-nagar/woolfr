<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    require 'sys/addons/vendor/autoload.php';

    use Google\Cloud\Storage\StorageClient;

    try {

        $jsonFileName = "";

        if ($files = glob("js/firebase/*.json")) {

            $jsonFileName = $files[0];
        }

        $storage = new StorageClient([

            'keyFilePath' => $jsonFileName,
            //'credentials' => $jsonFileName
        ]);

        $bucketName = 'test-bucket-5vh4r';
        $bucket = $storage->bucket($bucketName);

        if (!$bucket->exists()) {

            $response = $storage->createBucket($bucketName);
            echo "Your Bucket $bucketName is created successfully.";
        }

        $fileName = 'api/v2/method/1.jpg';
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->upload(

            fopen($fileName, 'r')
        );

        //$object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

        echo "https://storage.googleapis.com/".$bucketName."/".basename($fileName);

        echo "<br><br>";

        //print_r($object);
        exit;

    } catch(Exception $e) {

        echo $e->getMessage();
        exit;
    }

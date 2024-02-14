<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

require 'sys/addons/vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

class cdn extends db_connect
{
    private $ftp_url = "";
    private $ftp_server = "";
    private $ftp_user_name = "";
    private $ftp_user_pass = "";
    private $cdn_server = "";
    private $conn_id = false;

    public function __construct($dbo = NULL)
    {
        if (strlen($this->ftp_server) > 0) {

            $this->conn_id = @ftp_connect($this->ftp_server);
        }

        parent::__construct($dbo);
    }

    public function upload($file, $remote_file)
    {
        $remote_file = $this->cdn_server.$remote_file;

        if ($this->conn_id) {

            if (@ftp_login($this->conn_id, $this->ftp_user_name, $this->ftp_user_pass)) {

                // upload a file
                if (@ftp_put($this->conn_id, $remote_file, $file, FTP_BINARY)) {

                    return true;

                } else {

                    return false;
                }
            }
        }
    }

    public function uploadMyPhoto($imgFilename)
    {
        $result = $this->upload_GCS($imgFilename, 2);

        if ($result['error']) {

            rename($imgFilename, MY_PHOTOS_PATH.basename($imgFilename));

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "fileUrl" => APP_URL."/".MY_PHOTOS_PATH.basename($imgFilename)
            );
        }

        @unlink($imgFilename);

        return $result;
    }

    public function uploadPhoto($imgFilename)
    {

        $result = $this->upload_GCS($imgFilename, 0);

        if ($result['error']) {

            rename($imgFilename, PHOTO_PATH.basename($imgFilename));

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "fileUrl" => APP_URL."/".PHOTO_PATH.basename($imgFilename)
            );
        }

        @unlink($imgFilename);

        return $result;
    }

    public function uploadCover($imgFilename)
    {
        $result = $this->upload_GCS($imgFilename, 1);

        if ($result['error']) {

            rename($imgFilename, COVER_PATH.basename($imgFilename));

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "fileUrl" => APP_URL."/".COVER_PATH.basename($imgFilename)
            );
        }

        @unlink($imgFilename);

        return $result;
    }

    public function uploadChatImg($imgFilename)
    {
        rename($imgFilename, CHAT_IMAGE_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".CHAT_IMAGE_PATH.basename($imgFilename));

        return $result;
    }

    public function uploadVideo($imgFilename)
    {
        $result = $this->upload_GCS($imgFilename, 3);

        if ($result['error']) {

            rename($imgFilename, VIDEO_PATH.basename($imgFilename));

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "fileUrl" => APP_URL."/".VIDEO_PATH.basename($imgFilename)
            );

            @unlink($imgFilename);
        }

        @unlink($imgFilename);

        return $result;
    }

    public function upload_GCS($imgFilename, $type)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN,
            "error_description" => '',
            "fileUrl" => ""
        );

        $settings = new settings($this->db);
        $settings_result = $settings->get();
        unset($settings);

        $bucketName = "";

        switch ($type) {

            case 0: {

                if ($settings_result['gcs_photo']['intValue'] == 1) {

                    $bucketName = $settings_result['gcs_photo_bucket']['textValue'];
                }

                break;
            }

            case 1: {

                if ($settings_result['gcs_cover']['intValue'] == 1) {

                    $bucketName = $settings_result['gcs_cover_bucket']['textValue'];
                }

                break;
            }

            case 2: {

                if ($settings_result['gcs_gallery']['intValue'] == 1) {

                    $bucketName = $settings_result['gcs_gallery_bucket']['textValue'];
                }

                break;
            }

            case 3: {

                if ($settings_result['gcs_video']['intValue'] == 1) {

                    $bucketName = $settings_result['gcs_video_bucket']['textValue'];
                }

                break;
            }

            default: {

                break;
            }
        }

        if (strlen($bucketName) != 0) {

            try {

                $jsonFileName = "";

                if ($files = glob("js/firebase/*.json")) {

                    $jsonFileName = $files[0];
                }

                $storage = new StorageClient([

                    'keyFilePath' => $jsonFileName
                ]);

                $bucket = $storage->bucket($bucketName);

                if (!$bucket->exists()) {

                    $storage->createBucket($bucketName);
                }

                $bucket = $storage->bucket($bucketName);
                $object = $bucket->upload(

                    fopen($imgFilename, 'r')
                );

                $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

                $result['error'] = false;
                $result['error_code'] = ERROR_SUCCESS;
                $result['fileUrl'] = "https://storage.googleapis.com/".$bucketName."/".basename($imgFilename);

            } catch (Exception $e) {

                $result['error_description'] = $e->getMessage();
            }
        }

        return $result;
    }
}

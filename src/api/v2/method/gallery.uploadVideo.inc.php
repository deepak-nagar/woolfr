<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, https://ifsoft.co.uk, https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2020 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}


$imgFileUrl = "";
$videoFileUrl = "";

$error = true;
$error_code = ERROR_UNKNOWN;
$error_description = '';

$result = array(
    "error_code" => ERROR_UNKNOWN,
    "error" => true,
    "error_description" => '');

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    if (isset($_FILES['uploaded_video_file']['name'])) {

//        if (mime_content_type($_FILES['uploaded_video_file']['name']) === 'video/mp4') {
//
//
//
//        } else {
//
//            $error = true;
//            $error_code = ERROR_VIDEO_FILE_FORMAT;
//            $error_description = 'Error file format.';
//        }

        switch ($_FILES['uploaded_video_file']['error']) {

            case UPLOAD_ERR_OK:

                $error = false;
                $error_code = ERROR_SUCCESS;
                $error_description = 'UPLOAD_ERR_OK';

                break;

            case UPLOAD_ERR_NO_FILE:

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'No file sent.'; // No file sent.

                break;

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:

                $error = true;
                $error_code = ERROR_FILE_SIZE_BIG;
                $error_description = "Exceeded file size limit.";

                break;

            default:

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'Unknown error.';
        }

        if (!$error && $_FILES['uploaded_video_file']['size'] > VIDEO_FILE_MAX_SIZE) {

            $error = true;
            $error_code = ERROR_FILE_SIZE_BIG;
            $error_description = 'Exceeded file size limit.';
        }

        if (!$error) {

            $currentTime = time();
            $uploaded_file_ext = @pathinfo($_FILES['uploaded_video_file']['name'], PATHINFO_EXTENSION);
            $uploaded_file_ext = strtolower($uploaded_file_ext);

            if ($uploaded_file_ext === 'mp4') {

                if (@move_uploaded_file($_FILES['uploaded_video_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                    $cdn = new cdn($dbo);

                    $response = $cdn->uploadVideo(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                    if (!$response['error']) {

                        $videoFileUrl = $response['fileUrl'];

                        // Thumb

                        if (isset($_FILES['uploaded_file']['name'])) {

                            $currentTime = time();
                            $uploaded_file_ext = @pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
                            $uploaded_file_ext = strtolower($uploaded_file_ext);

                            if ($uploaded_file_ext === "jpg") {

                                if (@move_uploaded_file($_FILES['uploaded_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                                    $response = $cdn->uploadMyPhoto(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                                    if (!$response['error']) {

                                        $imgFileUrl = $response['fileUrl'];
                                    }
                                }
                            }
                        }

                        //

                        $result = array(
                            "error_code" => ERROR_SUCCESS,
                            "error" => false,
                            "error_description" => $error_description,
                            "imgFileUrl" => $imgFileUrl,
                            "videoFileUrl" => $videoFileUrl
                        );
                    }

                    unset($cdn);
                }
            } else {

                $result = array(
                    "error_code" => ERROR_VIDEO_FILE_FORMAT,
                    "error" => true,
                    "error_description" => 'Error file format.'
                );

            }

        } else {

            $result = array(
                "error_code" => $error_code,
                "error" => true,
                "error_description" => $error_description
            );
        }

    }

    echo json_encode($result);
    exit;
}

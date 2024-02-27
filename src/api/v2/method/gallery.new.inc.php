<?php

/*!
 * racconsquare.com
 *
 * https://racconsquare.com
 * racconsquare@gmail.com
 *
 * Copyright 2012-2021 Demyanchuk Dmitry (racconsquare@gmail.com)
 */

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $accessMode = isset($_POST['accessMode']) ? $_POST['accessMode'] : 0;

    $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 0;
    $itemShowInStream = isset($_POST['itemShowInStream']) ? $_POST['itemShowInStream'] : 1;

    $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
    $originImgUrl = isset($_POST['originImgUrl']) ? $_POST['originImgUrl'] : "";
    $previewImgUrl = isset($_POST['previewImgUrl']) ? $_POST['previewImgUrl'] : "";
    $imgUrl = isset($_POST['imgUrl']) ? $_POST['imgUrl'] : "";

    $videoUrl = isset($_POST['videoUrl']) ? $_POST['videoUrl'] : "";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $accessMode = helper::clearInt($accessMode);
    $itemType = helper::clearInt($itemType);
    $itemShowInStream = helper::clearInt($itemShowInStream);

    $comment = helper::clearText($comment);

    $comment = preg_replace( "/[\r\n]+/", "<br>", $comment); //replace all new lines to one new line
    $comment  = preg_replace('/\s+/', ' ', $comment);        //replace all white spaces to one space

    $comment = helper::escapeText($comment);

    $originImgUrl = helper::clearText($originImgUrl);
    $originImgUrl = helper::escapeText($originImgUrl);

    $previewImgUrl = helper::clearText($previewImgUrl);
    $previewImgUrl = helper::escapeText($previewImgUrl);

    $imgUrl = helper::clearText($imgUrl);
    $imgUrl = helper::escapeText($imgUrl);

    $videoUrl = helper::clearText($videoUrl);
    $videoUrl = helper::escapeText($videoUrl);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom($accountId);

    if (strlen($videoUrl) != 0) {

        $itemType = 1; // ITEM_TYPE_VIDEO
    }

    $result = $gallery->add($accessMode, $comment, $originImgUrl, $previewImgUrl, $imgUrl, $itemType, $itemShowInStream, $videoUrl);

    echo json_encode($result);
    exit;
}

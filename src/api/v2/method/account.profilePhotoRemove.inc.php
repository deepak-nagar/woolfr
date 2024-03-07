<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $userId = isset($_POST['userId']) ? $_POST['userId'] : 0;
    
    $accountId = helper::clearInt($accountId);

    $userId = helper::clearInt($userId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $galleryPrivate = new galleryPrivate($dbo);
    $galleryPrivate->setRequestFrom($accountId);


    $result = $galleryPrivate->removeProfilePic($userId);

    echo json_encode($result);
    exit;
}

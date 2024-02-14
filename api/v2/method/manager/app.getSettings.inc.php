<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2021 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $admin = new admin($dbo);
    $admin->setId($accountId);

    if (!$admin->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => false,
        "error_code" => ERROR_SUCCESS
    );

    $stats = new stats($dbo);

    $new_profile_photos_count = $stats->getUnModeratedAccountsPhotosCount();
    $new_profile_covers_count = $stats->getUnModeratedAccountsCoversCount();
    $new_media_items_count = $stats->getUnModeratedPhotosCount();

    $result['newProfilePhotosCount'] = $new_profile_photos_count;
    $result['newProfileCoversCount'] = $new_profile_covers_count;
    $result['newMediaItemsCount'] = $new_media_items_count;

    echo json_encode($result);
    exit;
}

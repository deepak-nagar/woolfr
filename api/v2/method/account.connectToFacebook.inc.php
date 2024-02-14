<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $uid = isset($_POST['uid']) ? $_POST['uid'] : '';

    $uid = helper::clearText($uid);
    $uid = helper::escapeText($uid);

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $accessToken = helper::clearText($accessToken);
    $accessToken = helper::escapeText($accessToken);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $result = array("error" => true);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $profileId = $helper->getUserIdByFacebook($uid);

    if ($profileId == 0) {

        $account = new account($dbo, $accountId);
        $account->setFacebookId($uid);

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS);

    } else {

        $result = array("error" => false,
                        "error_code" => ERROR_FACEBOOK_ID_TAKEN);
    }

    echo json_encode($result);
    exit;
}

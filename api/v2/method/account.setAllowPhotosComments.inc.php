<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 *  raccoonsquare@gmail.com
 *
 * Copyright 2012-2019 Demyanchuk Dmitry ( raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $allowPhotosComments = isset($_POST['allowPhotosComments']) ? $_POST['allowPhotosComments'] : 0;

    $allowPhotosComments = helper::clearInt($allowPhotosComments);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array("error" => false,
                    "error_code" => ERROR_SUCCESS);

    $account = new account($dbo, $accountId);

    $account->setAllowPhotosComments($allowPhotosComments);

    $result['allowPhotosComments'] = $account->getAllowPhotosComments();

    echo json_encode($result);
    exit;
}

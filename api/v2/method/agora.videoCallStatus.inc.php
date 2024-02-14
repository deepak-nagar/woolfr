<?php

/*!
 * https://racconsquare.com
 * racconsquare@gmail.com
 *
 * Copyright 2012-2023 Demyanchuk Dmitry (racconsquare@gmail.com)
 */

if (!defined("APP_SIGNATURE")) {

	header("Location: /");
	exit;
}

if (!empty($_POST)) {

	$accountId = isset($_POST['account_id']) ? $_POST['account_id'] : 0;
	$accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';

	$call_id = isset($_POST['call_id']) ? $_POST['call_id'] : 0;
    $time = isset($_POST['time']) ? $_POST['time'] : 0;
	$status = isset($_POST['status']) ? $_POST['status'] : 0;

	$call_id = helper::clearInt($call_id);
	$status = helper::clearInt($status);

	$auth = new auth($dbo);

	if (!$auth->authorize($accountId, $accessToken)) {

		api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
	}

	$result = array(
		"error" => true,
		"error_code" => ERROR_UNKNOWN
	);

	$agora = new agora($dbo);
	$agora->setRequestFrom($accountId);

    $result = $agora->statusVideoCall($call_id, $status, $time);

    echo json_encode($result);
    exit;
}


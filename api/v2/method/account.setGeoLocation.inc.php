<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $lat = isset($_POST['lat']) ? $_POST['lat'] : 0.000000;
    $lng = isset($_POST['lng']) ? $_POST['lng'] : 0.000000;

    $lat = floatval($lat);
    $lng = floatval($lng);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $account = new account($dbo, $accountId);

    if ($lat > 0 && $lng > 0) {

        $result = $account->setGeoLocation($lat, $lng);

    } else {

        $geo = new geo($dbo);

        $info = $geo->info(helper::ip_addr());

        if (array_key_exists("geoplugin_status", $info)) {

            if ($info['geoplugin_status'] == 200 || $info['geoplugin_status'] == 206) {

                $result = $account->setGeoLocation($info['geoplugin_latitude'], $info['geoplugin_longitude']);

            } else {

                $result = $account->setGeoLocation(GEO_DEFAULT_LAT, GEO_DEFAULT_LNG);
            }

        } else {

            $result = $account->setGeoLocation(GEO_DEFAULT_LAT, GEO_DEFAULT_LNG);
        }
    }

    echo json_encode($result);
    exit;
}

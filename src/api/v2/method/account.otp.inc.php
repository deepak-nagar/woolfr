<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2021 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */;

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

require_once 'sys/addons/vendor/autoload.php';

use Kreait\Firebase\Factory;

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\ServiceAccount;

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';

    $idToken = isset($_POST['token']) ? $_POST['token'] : '';

    $phoneNumber = helper::clearText($phoneNumber);
    $phoneNumber = helper::escapeText($phoneNumber);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN,
        "token" => "",
        "verified" => false
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $jsonFileName = "";

    if ($files = glob("js/firebase/*.json")) {

        $jsonFileName = $files[0];
    }

    $serviceAccount = ServiceAccount::fromValue($jsonFileName);

    $firebase = (new Factory)->withServiceAccount($serviceAccount);

    $firebaseAuth = $firebase->createAuth();

    try {

        $token = $firebaseAuth->verifyIdToken($idToken, true);

        $uid = $token->claims()->get('sub');

        $user = $firebaseAuth->getUser($uid);

        if ($user->phoneNumber != null) {

            if (!$helper->isPhoneNumberExists($user->phoneNumber)) {

                $account = new account($dbo, $accountId);
                $account->updateOtpVerification($user->phoneNumber, 1);

                $result = array(
                    "error" => false,
                    "error_code" => ERROR_SUCCESS,
                    "verified" => true
                );

            }  else {

                $result = array(
                    "error" => true,
                    "error_code" => ERROR_OTP_PHONE_NUMBER_TAKEN,
                    "verified" => false
                );
            }
        }

        $firebaseAuth->revokeRefreshTokens($uid);

    } catch (InvalidToken $e) {

        $result['token'] = $e->getMessage();

    } catch (\Kreait\Firebase\Exception\AuthException $e) {

        $result['token'] = $e->getMessage();

    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {

        $result['token'] = $e->getMessage();
    }

    echo json_encode($result);
    exit;
}

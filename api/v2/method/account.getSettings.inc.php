<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
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

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => false,
        "error_code" => ERROR_SUCCESS
    );

    $account = new account($dbo, $accountId);
    $accountInfo = $account->get();
    unset($account);


    $notifications_count = 0;
    $messages_count = 0;
    $matches_count = 0;
    $guests_count = 0;
    $friends_count = 0;

    // Get new messages count

    if (APP_MESSAGES_COUNTERS) {

        $msg = new msg($dbo);
        $msg->setRequestFrom($accountId);

        $messages_count = $msg->getNewMessagesCount();

        unset($msg);
    }

    // Get new notifications count

    $notifications = new notify($dbo);
    $notifications->setRequestFrom($accountId);

    $notifications_count = $notifications->getNewCount($accountInfo['lastNotifyView']);

    unset($notifications);

    // Get new matches count

    $matches = new matches($dbo, $accountId);
    $matches->setRequestFrom($accountId);

    $matches_count = $matches->getNewCount($accountInfo['lastMatchesView']);

    unset($matches);

    // Get new guests count

    $guests = new guests($dbo, $accountId);
    $guests->setRequestFrom($accountId);

    $guests_count = $guests->getNewCount($accountInfo['lastGuestsView']);

    unset($guests);

    // Get new friends count

    $friends = new friends($dbo, $accountId);
    $friends->setRequestFrom($accountId);

    $friends_count = $friends->getNewCount($accountInfo['lastFriendsView']);

    unset($friends);

    // Get chat settings

    $settings = new settings($dbo);

    $config = $settings->get();

    $arr = array();

    $arr = $config['allowSeenTyping'];
    $result['seenTyping'] = $arr['intValue'];

    $arr = $config['allowRewardedAds'];
    $result['rewardedAds'] = $arr['intValue'];

    $arr = $config['allowAdBannerInGalleryItem'];
    $result['allowAdBannerInGalleryItem'] = $arr['intValue'];

    $arr = $config['defaultGhostModeCost'];
    $result['defaultGhostModeCost'] = $arr['intValue'];

    $arr = $config['defaultVerifiedBadgeCost'];
    $result['defaultVerifiedBadgeCost'] = $arr['intValue'];

    $arr = $config['defaultDisableAdsCost'];
    $result['defaultDisableAdsCost'] = $arr['intValue'];

    $arr = $config['defaultProModeCost'];
    $result['defaultProModeCost'] = $arr['intValue'];

    $arr = $config['defaultSpotlightCost'];
    $result['defaultSpotlightCost'] = $arr['intValue'];

    $arr = $config['defaultMessagesPackageCost'];
    $result['defaultMessagesPackageCost'] = $arr['intValue'];

    $arr = $config['allowShowNotModeratedProfilePhotos'];
    $result['allowShowNotModeratedProfilePhotos'] = $arr['intValue'];

    //

    $arr = $config['interstitialAdAfterProfileView'];
    $result['interstitialAdAfterProfileView'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewGalleryItem'];
    $result['interstitialAdAfterNewGalleryItem'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewProfileLike'];
    $result['interstitialAdAfterNewProfileLike'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewLike'];
    $result['interstitialAdAfterNewLike'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewComment'];
    $result['interstitialAdAfterNewComment'] = $arr['intValue'];

    //

    $arr = $config['android_admob_app_id'];
    $result['android_admob_app_id'] = $arr['textValue'];

    $arr = $config['android_admob_banner_ad_unit_id'];
    $result['android_admob_banner_ad_unit_id'] = $arr['textValue'];

    $arr = $config['android_admob_rewarded_ad_unit_id'];
    $result['android_admob_rewarded_ad_unit_id'] = $arr['textValue'];

    $arr = $config['android_admob_interstitial_ad_unit_id'];
    $result['android_admob_interstitial_ad_unit_id'] = $arr['textValue'];

    $arr = $config['android_admob_banner_native_ad_unit_id'];
    $result['android_admob_banner_native_ad_unit_id'] = $arr['textValue'];

    //

    $arr = $config['ios_admob_app_id'];
    $result['ios_admob_app_id'] = $arr['textValue'];

    $arr = $config['ios_admob_banner_ad_unit_id'];
    $result['ios_admob_banner_ad_unit_id'] = $arr['textValue'];

    $arr = $config['ios_admob_rewarded_ad_unit_id'];
    $result['ios_admob_rewarded_ad_unit_id'] = $arr['textValue'];

    $arr = $config['ios_admob_interstitial_ad_unit_id'];
    $result['ios_admob_interstitial_ad_unit_id'] = $arr['textValue'];

    $arr = $config['ios_admob_banner_native_ad_unit_id'];
    $result['ios_admob_banner_native_ad_unit_id'] = $arr['textValue'];

    // Agora

    $arr = $config['agora_app_enabled'];
    $result['agora_app_enabled'] = $arr['intValue'];

    $arr = $config['agora_app_id'];
    $result['agora_app_id'] = $arr['textValue'];

    $arr = $config['agora_app_certificate'];
    $result['agora_app_certificate'] = $arr['textValue'];

    //

    $result['guestsCount'] = $guests_count;
    $result['messagesCount'] = $messages_count;
    $result['notificationsCount'] = $notifications_count;
    $result['newFriendsCount'] = $friends_count;
    $result['newMatchesCount'] = $matches_count;

    $result['free_messages_count'] = $accountInfo['free_messages_count'];
    $result['admob'] = $accountInfo['admob'];
    $result['ghost'] = $accountInfo['ghost'];
    $result['pro'] = $accountInfo['pro'];
    $result['verified'] = $accountInfo['verified'];
    $result['balance'] = $accountInfo['balance'];

    echo json_encode($result);
    exit;
}

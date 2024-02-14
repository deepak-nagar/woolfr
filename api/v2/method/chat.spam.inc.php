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

    $clientId = isset($_POST['client_id']) ? $_POST['client_id'] : 0;

    $accountId = isset($_POST['account_id']) ? $_POST['account_id'] : 0;
    $accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';

    $chatId = isset($_POST['chat_id']) ? $_POST['chat_id'] : 0;
    $spamSelection = isset($_POST['selection']) ? $_POST['selection'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $chatId = helper::clearInt($chatId);
    $spamSelection = helper::clearInt($spamSelection);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $msg = new msg($dbo);
    $msg->setRequestFrom($accountId);

    if ($chatId != 0) {

        $chatInfo = $msg->chatInfo($chatId);

        if (!$chatInfo['error']) {

            if ($chatInfo['toUserId'] == $accountId && $chatInfo['spamCheck'] == 1) {

                // set - not need more check for spam
                $msg->spamCheck($chatId, 0);

                // user say: its spam
                if ($spamSelection != 0) {

                    $spammer = new account($dbo, $chatInfo['fromUserId']);
                    $spammerInfo = $spammer->get();

                    if ($spammerInfo['state'] != ACCOUNT_STATE_BLOCKED) {

                        // get current spam level and increase
                        $spamLevel = $spammerInfo['spamLevel'] + 1;

                        // set new spam level
                        $spammer->setSpamLevel($spamLevel);

                        $settings = new settings($dbo);
                        $app_settings = $settings->get();
                        unset($settings);

                        // If SpamCheck feature Enabled

                        if ($app_settings['chatsSpamCheckFeature']['intValue'] == 1) {

                            // Close all authorizations

                            if ($app_settings['autoLogoutSpamLevel']['intValue'] != 0) {

                                if ($spamLevel >= $app_settings['autoLogoutSpamLevel']['intValue']) {

                                    $auth = new auth($dbo);
                                    $auth->removeAll($chatInfo['fromUserId']);
                                    unset($auth);
                                }
                            }

                            // Block account and Close all authorizations

                            if ($app_settings['autoBlockSpamLevel']['intValue'] != 0) {

                                if ($spamLevel >= $app_settings['autoBlockSpamLevel']['intValue']) {

                                    $auth = new auth($dbo);
                                    $auth->removeAll($chatInfo['fromUserId']);
                                    unset($auth);

                                    $spammer->setState(ACCOUNT_STATE_BLOCKED);

                                    $spotlight = new spotlight($dbo);
                                    $spotlight->delete($chatInfo['fromUserId']);
                                    unset($spotlight);

                                    $gallery = new gallery($dbo);
                                    $gallery->setRequestFrom($chatInfo['fromUserId']);
                                    $gallery->removeAll();
                                    unset($gallery);

                                    $msg = new msg($dbo);
                                    $msg->setRequestFrom($chatInfo['fromUserId']);
                                    $msg->removeAll();
                                    unset($msg);

                                    $gifts = new gift($dbo);
                                    $gifts->setRequestFrom($chatInfo['fromUserId']);
                                    $gifts->removeAll();
                                    unset($gifts);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    echo json_encode($result);
    exit;
}

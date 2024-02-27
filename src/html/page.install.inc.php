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

    include_once("sys/core/initialize.inc.php");

    $admin = new admin($dbo);
    $gift = new gift($dbo);
    $stickers = new sticker($dbo);

    if ($admin->getCount() > 0) {

        header("Location: /");
        exit;
    }

    $page_id = "install";

    $itemId = 14781822; // Dating App Android = 14781822
                        // Dating App iOS = 19393764
                        // My Social Network Android = 13965025
                        // My Social Network iOS = 19414706

    $error = false;
    $error_message = array();

    $pcode = '';
    $user_username = '';
    $user_fullname = '';
    $user_password = '';
    $user_password_repeat = '';

    $error_token = false;
    $error_username = false;
    $error_fullname = false;
    $error_password = false;
    $error_password_repeat = false;

    if (!empty($_POST)) {

        $error = false;

        $pcode = isset($_POST['pcode']) ? $_POST['pcode'] : '';
        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $user_fullname = isset($_POST['user_fullname']) ? $_POST['user_fullname'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $pcode = helper::clearText($pcode);
        $user_username = helper::clearText($user_username);
        $user_fullname = helper::clearText($user_fullname);
        $user_password = helper::clearText($user_password);
        $user_password_repeat = helper::clearText($user_password_repeat);

        $pcode = helper::escapeText($pcode);
        $user_username = helper::escapeText($user_username);
        $user_fullname = helper::escapeText($user_fullname);
        $user_password = helper::escapeText($user_password);
        $user_password_repeat = helper::escapeText($user_password_repeat);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
            $error_token = true;
            $error_message[] = 'Error!';
        }

        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $pcode)) {

            $error = true;
            $error_message[] = 'Invalid purchase code';
        }

        $raccoonsquare_response = helper::verify_pcode($pcode, ENVATO_ITEM_ID);

        if (!is_array($raccoonsquare_response)) {

            $error = true;
            $error_message[] = 'Invalid response from verifying purchase code server. Try later.';
            $error_message[] = 'Check curl module - module must be installed and active.';

        } else {

            $raccoonsquare_response = json_decode(json_encode($raccoonsquare_response, JSON_FORCE_OBJECT));
        }

        if (!$error && $raccoonsquare_response->error) {

            $error = true;
            $error_message[] = 'Invalid response from verifying purchase code envato server. Try later.';
        }

        if (!$error && $raccoonsquare_response->error_code == ENVATO_ERROR_PCODE_INVALID) {

            $error = true;
            $error_message[] = 'Invalid purchase code.';
        }

        if (!$error && $raccoonsquare_response->error_code == ENVATO_ERROR_PCODE_ILLEGAL) {

            $error = true;
            $error_message[] = 'This purchase code is already in use for another domain.';
        }

        if (!$error && $raccoonsquare_response->error_code == ENVATO_ERROR_PCODE_UNKNOWN) {

            $error = true;
            $error_message[] = 'We were unable to verify this purchase code. Try later..';
        }

        if (!$error && !helper::isCorrectLogin($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = 'Incorrect username.';
        }

        if (!$error && !helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_password = true;
            $error_message[] = 'Incorrect password.';
        }

        if (!$error) {

            $admin = new admin($dbo);

            // Create admin account

            $result = array();
            $result = $admin->signup($user_username, $user_password, $user_fullname, ADMIN_ACCESS_LEVEL_FULL);

            if (!$result['error']) {

                $access_data = $admin->signin($user_username, $user_password);

                if ($access_data['error'] === false) {

                    $clientId = 0; // Desktop version

                    admin::createAccessToken();

                    admin::setSession($access_data['accountId'], admin::getAccessToken(), $access_data['username'], $access_data['fullname'], $access_data['access_level']);

                    // Add standard settings

                    $settings = new settings($dbo);
                    $settings->createValue("admob", 1); //Default show admob
                    $settings->createValue("defaultBalance", 10); //Default balance for new users
                    $settings->createValue("defaultReferralBonus", 10); //Default bonus - referral signup
                    $settings->createValue("defaultFreeMessagesCount", 150); //Default free messages count after signup
                    $settings->createValue("defaultGhostModeCost", 100); //Default cost for ghost mode in credits
                    $settings->createValue("defaultVerifiedBadgeCost", 150); //Default cost for verified badge in credits
                    $settings->createValue("defaultDisableAdsCost", 200); //Default cost for disable ads in credits
                    $settings->createValue("defaultProModeCost", 170); //Default cost for pro mode feature in credits
                    $settings->createValue("defaultSpotlightCost", 30); //Default cost for adding to spotlight feature in credits
                    $settings->createValue("defaultMessagesPackageCost", 20); //Default cost for buy message package feature in credits
                    $settings->createValue("allowFriendsFunction", 1);
                    $settings->createValue("allowSeenTyping", 1);
                    $settings->createValue("allowMultiAccountsFunction", 1);
                    $settings->createValue("allowFacebookAuthorization", 1);
                    $settings->createValue("allowUpgradesSection", 1);
                    $settings->createValue("allowRewardedAds", 1);
                    $settings->createValue("photoModeration", 1); //Default on
                    $settings->createValue("coverModeration", 1); //Default on
                    $settings->createValue("galleryModeration", 1); //Default on
                    $settings->createValue("allowAdBannerInGalleryItem", 1); //Default on
                    $settings->createValue("defaultAllowMessages", 0); //Default off
                    $settings->createValue("allowShowNotModeratedProfilePhotos", 1); //Default on
                    $settings->createValue("allowAutoModerate", 0); //Default off
                    $settings->createValue("createChatsOnlyWithOTPVerified", 0); //Default off
                    $settings->createValue("envato_pcode", 0, $pcode); //Save purchase code
                    $settings->createValue("interstitialAdAfterProfileView", 2);
                    $settings->createValue("interstitialAdAfterNewGalleryItem", 2);
                    $settings->createValue("interstitialAdAfterNewProfileLike", 2);
                    $settings->createValue("interstitialAdAfterNewLike", 2);
                    $settings->createValue("interstitialAdAfterNewComment", 2);

                    $settings->createValue("android_admob_app_id", 0, 'ca-app-pub-3940256099942544~3347511713');
                    $settings->createValue("android_admob_banner_ad_unit_id", 0, 'ca-app-pub-3940256099942544/6300978111');
                    $settings->createValue("android_admob_rewarded_ad_unit_id", 0, 'ca-app-pub-3940256099942544/5224354917');
                    $settings->createValue("android_admob_interstitial_ad_unit_id", 0, 'ca-app-pub-3940256099942544/1033173712');
                    $settings->createValue("android_admob_banner_native_ad_unit_id", 0, 'ca-app-pub-3940256099942544/2247696110');

                    $settings->createValue("ios_admob_app_id", 0, 'ca-app-pub-3940256099942544~3347511713');
                    $settings->createValue("ios_admob_banner_ad_unit_id", 0, 'ca-app-pub-3940256099942544/6300978111');
                    $settings->createValue("ios_admob_rewarded_ad_unit_id", 0, 'ca-app-pub-3940256099942544/5224354917');
                    $settings->createValue("ios_admob_interstitial_ad_unit_id", 0, 'ca-app-pub-3940256099942544/1033173712');
                    $settings->createValue("ios_admob_banner_native_ad_unit_id", 0, 'ca-app-pub-3940256099942544/2247696110');

                    $settings->createValue("gcv_adult", 0);
                    $settings->createValue("gcv_violence", 0);
                    $settings->createValue("gcv_racy", 0);
                    $settings->createValue("gcv_spoof", 0);
                    $settings->createValue("gcv_medical", 0);

                    $settings->createValue("chatsSpamCheckFeature", 1); // Show by default
                    $settings->createValue("chatsOnlyByVerified", 0); // Default off
                    $settings->createValue("autoBlockSpamLevel", 10); // Default not block
                    $settings->createValue("autoLogoutSpamLevel", 3); // Default not block

                    $settings->createValue("gcs_photo", 0); // Disabled by default
                    $settings->createValue("gcs_cover", 0); // Disabled by default
                    $settings->createValue("gcs_gallery", 0); // Disabled by default
                    $settings->createValue("gcs_video", 0); // Disabled by default
                    $settings->createValue("gcs_auto_delete", 0);
                    $settings->createValue("gcs_photo_bucket", 0, "");
                    $settings->createValue("gcs_cover_bucket", 0, "");
                    $settings->createValue("gcs_gallery_bucket", 0, "");
                    $settings->createValue("gcs_video_bucket", 0, "");

                    unset($settings);

                    // Add standard gifts

                    if ($gift->db_getMaxId() < 1) {

                        for ($i = 1; $i < 31; $i++) {

                            $gift->db_add(3, 0, APP_URL."/".GIFTS_PATH.$i.".jpg");

                        }
                    }

                    // Add standard stickers

                    if ($stickers->db_getMaxId() < 1) {

                        for ($i = 1; $i < 28; $i++) {

                            $stickers->db_add(APP_URL."/stickers/".$i.".png");

                        }
                    }

                    // Add standard feelings

                    $feelings = new feelings($dbo);

                    if ($feelings->db_getMaxId() < 1) {

                        for ($i = 1; $i <= 12; $i++) {

                            $feelings->db_add(APP_URL."/feelings/".$i.".png");

                        }
                    }

                    // Redirect to Admin Panel main page

                    header("Location: /admin/main");
                    exit;
                }

                header("Location: /install");
            }
        }
    }

    auth::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = APP_TITLE;

    include_once("html/common/site_header.inc.php");
?>

<body class="remind-page">

    <?php

        include_once("html/common/site_topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <h1>Warning!</h1>
                    <p>Remember that now Create an account administrator!</p>

                    <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">
                        <p class="title"><?php echo $LANG['label-errors-title']; ?></p>
                        <ul>
                            <?php

                            foreach ($error_message as $msg) {

                                echo "<li>{$msg}</li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <form accept-charset="UTF-8" action="/install" class="custom-form" id="remind-form" method="post">

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                        <div class="row">
                            <div class="input-field col s12">
                                <p style="font-weight: 600">How to get purchase code you can read here: <a href="https://raccoonsquare.com/help/how_to_get_purchase_code/" target="_blank">How to get purchase code?</a></p>
                            </div>
                        </div>

                        <input id="pcode" name="pcode" placeholder="Purchase code" required="required" size="60" type="text" value="<?php echo $pcode; ?>">

                        <input id="user_username" name="user_username" placeholder="Username" required="required" size="30" type="text" value="<?php echo $user_username; ?>">
                        <input id="user_fullname" name="user_fullname" placeholder="Fullname" required="required" size="30" type="text" value="<?php echo $user_fullname; ?>">
                        <input id="user_password" name="user_password" placeholder="Password" required="required" size="30" type="password" value="">

                        <div class="login-button">
                            <input name="commit" class="button red" type="submit" value="Install">
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("html/common/site_footer.inc.php");
    ?>

</body>
</html>
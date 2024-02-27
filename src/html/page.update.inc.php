<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    error_reporting(E_ALL);

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    include_once("sys/core/initialize.inc.php");
    require_once 'sys/addons/vendor/autoload.php';

    //

    $update = new update($dbo);
    $update->modifyColumnSettingsTable1();
    unset($update);

    //

    $error = false;
    $pcode = '';
    $raccoonsquare_response = "";

    $settings = new settings($dbo);

    $update = new update($dbo);
    $update->addColumnToChatsTable();
    $update->addColumnToChatsTable2();

    $update->addColumnToAdminsTable();

    $update->addColumnToUsersTable15();

    $update->addColumnToGalleryTable1();
    $update->addColumnToGalleryTable2();
    $update->addColumnToGalleryTable3();

    $update->addColumnToUsersTable1();
    $update->addColumnToUsersTable2();
    $update->addColumnToUsersTable3();
    $update->addColumnToUsersTable4();
    $update->addColumnToUsersTable5();

    // For version 2.7

    $update->addColumnToUsersTable6();

    // Only For version 2.8

    $update->updateUsersTable();

    // For version 3.0

    $update->addColumnToUsersTable7();
    $update->addColumnToUsersTable8();
    $update->addColumnToUsersTable9();
    $update->addColumnToUsersTable10();

    // For version 3.1

    $update->addColumnToUsersTable11();
    $update->addColumnToUsersTable12();

    // For version 3.2

    $update->addColumnToUsersTable14();

    // For version 3.4

    $update->addColumnToMessagesTable1();

    // For version 3.5

    $update->addColumnToUsersTable16(); // add field sex_orientation
    $update->addColumnToUsersTable17(); // add field u_age
    $update->addColumnToUsersTable18(); // add field u_height
    $update->addColumnToUsersTable19(); // add field u_weight

    $update->addColumnToUsersTable20();
    $update->addColumnToUsersTable21();
    $update->addColumnToUsersTable22();

    // For version 3.6

    $update->addColumnToUsersTable23();
    $update->addColumnToUsersTable24();
    $update->addColumnToUsersTable25();

    $settings = new settings($dbo);
    $settings->createValue("admob", 1); //Default show admob
    $settings->createValue("defaultBalance", 10); //Default balance for new users
    $settings->createValue("defaultReferralBonus", 10); //Default bonus - referral signup
    $settings->createValue("defaultFreeMessagesCount", 150); //Default free messages count after signup
    $settings->createValue("allowFriendsFunction", 1);
    $settings->createValue("allowSeenTyping", 1);
    $settings->createValue("allowMultiAccountsFunction", 1);
    $settings->createValue("allowFacebookAuthorization", 1);
    $settings->createValue("allowUpgradesSection", 1);
    unset($settings);

    // For version 3.7

    $settings = new settings($dbo);
    $settings->createValue("allowSeenTyping", 1);
    unset($settings);

    $update->addColumnToUsersTable26();

    // For version 3.8

    $settings = new settings($dbo);
    $settings->createValue("allowRewardedAds", 1); //Default allow rewarded ads
    unset($settings);

    // For version 4.1

    $update->addColumnToGalleryTable4();

    // For version 4.2

    $update->addColumnToGalleryTable5();

    // For version 4.3

    $update->addColumnToUsersTable27();
    $update->addColumnToUsersTable28();
    $update->addColumnToUsersTable29();
    $update->addColumnToUsersTable30();
    $update->addColumnToUsersTable31();

    // For version 4.5

    $update->addColumnToUsersTable32();
    $update->addColumnToUsersTable33();
    $update->addColumnToUsersTable34();
    $update->addColumnToUsersTable35();
    $update->addColumnToUsersTable36();
    $update->addColumnToUsersTable37();
    $update->addColumnToUsersTable38();

    // For version 4.6

    $update->addColumnToAccessDataTable1();
    $update->addColumnToAccessDataTable2();
    $update->addColumnToAccessDataTable3();

    $update->addColumnToUsersTable39();

    $settings = new settings($dbo);
    $settings->createValue("photoModeration", 1); //Default on
    $settings->createValue("coverModeration", 1); //Default on
    $settings->createValue("galleryModeration", 1); //Default on
    $settings->createValue("allowAdBannerInGalleryItem", 1); //Default on
    $settings->createValue("defaultGhostModeCost", 100); //Default cost for ghost mode in credits
    $settings->createValue("defaultVerifiedBadgeCost", 150); //Default cost for verified badge in credits
    $settings->createValue("defaultDisableAdsCost", 200); //Default cost for disable ads in credits
    $settings->createValue("defaultProModeCost", 170); //Default cost for pro mode feature in credits
    $settings->createValue("defaultSpotlightCost", 30); //Default cost for adding to spotlight feature in credits
    $settings->createValue("defaultMessagesPackageCost", 20); //Default cost for buy message package feature in credits
    unset($settings);

    // For version 5.0

    // $update->updateUsersTable1();

    //    $settings = new settings($dbo);
    //    $settings->createValue("defaultAllowMessages", 0); //Default off
    //    unset($settings);

    // For version 5.1

    $settings = new settings($dbo);
    $settings->createValue("allowShowNotModeratedProfilePhotos", 1); //Default on
    unset($settings);

    // For version 5.2

    $update->addColumnToUsersTable40();
    $update->addColumnToUsersTable41();

    $settings = new settings($dbo);
    $settings->createValue("createChatsOnlyWithOTPVerified", 0); //Default off
    unset($settings);

    // For version 5.3

    $update->addColumnToAdminsTable1();

    // For version 5.9

    $settings = new settings($dbo);
    $settings->createValue("allowAutoModerate", 0); //Default off
    unset($settings);

    // For version 6.0

    $settings = new settings($dbo);
    $settings->createValue("interstitialAdAfterProfileView", 2);
    $settings->createValue("interstitialAdAfterNewGalleryItem", 2);
    $settings->createValue("interstitialAdAfterNewProfileLike", 2);
    $settings->createValue("interstitialAdAfterNewLike", 2);
    $settings->createValue("interstitialAdAfterNewComment", 2);
    unset($settings);

    // For version 6.1

    //$update->updateUsersTable2();

    // For version 6.2

    $settings = new settings($dbo);

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

    unset($settings);

    $update->addColumnToUsersTable42();

    // For version 6.4

    $settings = new settings($dbo);

    $settings->createValue("gcv_adult", 0);
    $settings->createValue("gcv_violence", 0);
    $settings->createValue("gcv_racy", 0);
    $settings->createValue("gcv_spoof", 0);
    $settings->createValue("gcv_medical", 0);

    $settings->createValue("chatsSpamCheckFeature", 1); // Show by default
    $settings->createValue("chatsOnlyByVerified", 0); // Default off
    $settings->createValue("autoBlockSpamLevel", 10); // Default not block
    $settings->createValue("autoLogoutSpamLevel", 3); // Default not block

    unset($settings);

    $update->addColumnToUsersTable43();
    $update->addColumnToUsersTable44();
    $update->addColumnToUsersTable45();

    $update->addColumnToChatsTable3();

    // For version 6.5

    $settings = new settings($dbo);
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

    // For version 6.7

    $update->addColumnToUsersTable46();

    $settings = new settings($dbo);
    $settings->createValue("agora_app_enabled", 1, "");
    $settings->createValue("agora_app_id", 0, "");
    $settings->createValue("agora_app_certificate", 0, "");
    unset($settings);

    // Add standard feelings

    $feelings = new feelings($dbo);

    if ($feelings->db_getMaxId() < 1) {

        for ($i = 1; $i <= 12; $i++) {

            $feelings->db_add(APP_URL."/feelings/".$i.".png");

        }
    }

    // Add standard stickers

    $stickers = new sticker($dbo);

    if ($stickers->db_getMaxId() < 1) {

        for ($i = 1; $i < 28; $i++) {

            $stickers->db_add(APP_URL."/stickers/".$i.".png");

        }
    }

    unset($stickers);
    unset($update);

    //

    auth::newAuthenticityToken();

    $page_id = "update";

    $css_files = array("my.css");
    $page_title = APP_TITLE;

    include_once("html/common/site_header.inc.php");
?>

<body class="remind-page sn-hide">

    <?php

        include_once("html/common/site_topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <div class="success-container" style="margin-top: 15px;">
                        <ul>
                            <b>Success!</b>
                            <br>
                            Your MySQL version: <?php echo $dbo->query('select version()')->fetchColumn(); ?>
                            <br>
                            Database refactoring success!
                        </ul>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("html/common/site_footer.inc.php");
    ?>

    <script>

        $('#update-form').submit(function(event) {

            event.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'submit'}).then(function(token) {

                    $('#update-form').prepend('<input type="hidden" name="recaptcha_token" value="'+ token + '">');
                    $('#update-form').unbind('submit').submit();
                });
            });
        });
    </script>

</body>
</html>
<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    // Administrator info

    $admin = new admin($dbo);
    $admin->setId(admin::getCurrentAdminId());

    $admin_info = $admin->get();

    //

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    $allowGalleryModeration = 1;

    $allowRewardedAds = 1;
    $allowAdBannerInGalleryItem = 1;
    $allowSeenTyping = 1;

    $allowFacebookAuthorization = 1;
    $allowMultiAccountsFunction = 1;

    $defaultFreeMessagesCount = 150;
    $defaultReferralBonus = 10;
    $defaultBalance = 10;

    $defaultGhostModeCost = 100;
    $defaultVerifiedBadgeCost = 150;
    $defaultDisableAdsCost = 200;
    $defaultProModeCost = 170;
    $defaultSpotlightCost = 30;
    $defaultMessagesPackageCost = 20;

    $defaultAllowMessages = 0;

    $allowAutoModerate = 0;
    $allowShowNotModeratedProfilePhotos = 1;
    $createChatsOnlyWithOTPVerified = 0;

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowGalleryModeration_checkbox = isset($_POST['allowGalleryModeration']) ? $_POST['allowGalleryModeration'] : '';

        $allowRewardedAds_checkbox = isset($_POST['allowRewardedAds']) ? $_POST['allowRewardedAds'] : '';
        $allowAdBannerInGalleryItem_checkbox = isset($_POST['allowAdBannerInGalleryItem']) ? $_POST['allowAdBannerInGalleryItem'] : '';
        $allowSeenTyping_checkbox = isset($_POST['allowSeenTyping']) ? $_POST['allowSeenTyping'] : '';

        $allowFacebookAuthorization_checkbox = isset($_POST['allowFacebookAuthorization']) ? $_POST['allowFacebookAuthorization'] : '';
        $allowMultiAccountsFunction_checkbox = isset($_POST['allowMultiAccountsFunction']) ? $_POST['allowMultiAccountsFunction'] : '';

        $defaultFreeMessagesCount = isset($_POST['defaultFreeMessagesCount']) ? $_POST['defaultFreeMessagesCount'] : 150;
        $defaultReferralBonus = isset($_POST['defaultReferralBonus']) ? $_POST['defaultReferralBonus'] : 10;
        $defaultBalance = isset($_POST['defaultBalance']) ? $_POST['defaultBalance'] : 10;

        $defaultGhostModeCost = isset($_POST['defaultGhostModeCost']) ? $_POST['defaultGhostModeCost'] : 100;
        $defaultVerifiedBadgeCost = isset($_POST['defaultVerifiedBadgeCost']) ? $_POST['defaultVerifiedBadgeCost'] : 150;
        $defaultDisableAdsCost = isset($_POST['defaultDisableAdsCost']) ? $_POST['defaultDisableAdsCost'] : 200;
        $defaultProModeCost = isset($_POST['defaultProModeCost']) ? $_POST['defaultProModeCost'] : 170;
        $defaultSpotlightCost = isset($_POST['defaultSpotlightCost']) ? $_POST['defaultSpotlightCost'] : 30;
        $defaultMessagesPackageCost = isset($_POST['defaultMessagesPackageCost']) ? $_POST['defaultMessagesPackageCost'] : 20;

        $defaultAllowMessages_checkbox = isset($_POST['defaultAllowMessages']) ? $_POST['defaultAllowMessages'] : '';

        $allowAutoModerate_checkbox = isset($_POST['allowAutoModerate']) ? $_POST['allowAutoModerate'] : '';

        $allowShowNotModeratedProfilePhotos_checkbox = isset($_POST['allowShowNotModeratedProfilePhotos']) ? $_POST['allowShowNotModeratedProfilePhotos'] : '';

        $createChatsOnlyWithOTPVerified_checkbox = isset($_POST['createChatsOnlyWithOTPVerified']) ? $_POST['createChatsOnlyWithOTPVerified'] : 0;

        if ($authToken === helper::getAuthenticityToken() && $admin_info['access_level'] < ADMIN_ACCESS_LEVEL_MODERATOR_RIGHTS) {

            if ($allowAdBannerInGalleryItem_checkbox === "on") {

                $allowAdBannerInGalleryItem = 1;

            } else {

                $allowAdBannerInGalleryItem = 0;
            }

            if ($allowGalleryModeration_checkbox === "on") {

                $allowGalleryModeration = 1;

            } else {

                $allowGalleryModeration = 0;
            }

            if ($allowRewardedAds_checkbox === "on") {

                $allowRewardedAds = 1;

            } else {

                $allowRewardedAds = 0;
            }

            if ($allowSeenTyping_checkbox === "on") {

                $allowSeenTyping = 1;

            } else {

                $allowSeenTyping = 0;
            }

            if ($allowFacebookAuthorization_checkbox === "on") {

                $allowFacebookAuthorization = 1;

            } else {

                $allowFacebookAuthorization = 0;
            }

            if ($allowMultiAccountsFunction_checkbox === "on") {

                $allowMultiAccountsFunction = 1;

            } else {

                $allowMultiAccountsFunction = 0;
            }

            if ($defaultAllowMessages_checkbox === "on") {

                $defaultAllowMessages = 1;

            } else {

                $defaultAllowMessages = 0;
            }

            if ($allowAutoModerate_checkbox === "on") {

                $allowAutoModerate = 1;

            } else {

                $allowAutoModerate = 0;
            }

            if ($allowShowNotModeratedProfilePhotos_checkbox === "on") {

                $allowShowNotModeratedProfilePhotos = 1;

            } else {

                $allowShowNotModeratedProfilePhotos = 0;
            }

            if ($createChatsOnlyWithOTPVerified_checkbox === "on") {

                $createChatsOnlyWithOTPVerified = 1;

            } else {

                $createChatsOnlyWithOTPVerified = 0;
            }

            $defaultBalance = helper::clearInt($defaultBalance);
            $defaultReferralBonus = helper::clearInt($defaultReferralBonus);
            $defaultFreeMessagesCount = helper::clearInt($defaultFreeMessagesCount);

            $settings->setValue("galleryModeration", $allowGalleryModeration);

            $settings->setValue("allowRewardedAds", $allowRewardedAds);
            $settings->setValue("allowAdBannerInGalleryItem", $allowAdBannerInGalleryItem);
            $settings->setValue("allowSeenTyping", $allowSeenTyping);

            $settings->setValue("allowFacebookAuthorization", $allowFacebookAuthorization);
            $settings->setValue("allowMultiAccountsFunction", $allowMultiAccountsFunction);

            $settings->setValue("defaultBalance", $defaultBalance);
            $settings->setValue("defaultReferralBonus", $defaultReferralBonus);
            $settings->setValue("defaultFreeMessagesCount", $defaultFreeMessagesCount);

            $settings->setValue("defaultAllowMessages", $defaultAllowMessages);

            $settings->setValue("allowAutoModerate", $allowAutoModerate);
            $settings->setValue("allowShowNotModeratedProfilePhotos", $allowShowNotModeratedProfilePhotos);

            $settings->setValue("createChatsOnlyWithOTPVerified", $createChatsOnlyWithOTPVerified);

            if (helper::clearInt($defaultGhostModeCost) > 0) {

                $defaultGhostModeCost = helper::clearInt($defaultGhostModeCost);
                $settings->setValue("defaultGhostModeCost", $defaultGhostModeCost);
            }

            if (helper::clearInt($defaultVerifiedBadgeCost) > 0) {

                $defaultVerifiedBadgeCost = helper::clearInt($defaultVerifiedBadgeCost);
                $settings->setValue("defaultVerifiedBadgeCost", $defaultVerifiedBadgeCost);
            }

            if (helper::clearInt($defaultDisableAdsCost) > 0) {

                $defaultDisableAdsCost = helper::clearInt($defaultDisableAdsCost);
                $settings->setValue("defaultDisableAdsCost", $defaultDisableAdsCost);
            }

            if (helper::clearInt($defaultProModeCost) > 0) {

                $defaultProModeCost = helper::clearInt($defaultProModeCost);
                $settings->setValue("defaultProModeCost", $defaultProModeCost);
            }

            if (helper::clearInt($defaultSpotlightCost) > 0) {

                $defaultSpotlightCost = helper::clearInt($defaultSpotlightCost);
                $settings->setValue("defaultSpotlightCost", $defaultSpotlightCost);
            }

            if (helper::clearInt($defaultMessagesPackageCost) > 0) {

                $defaultMessagesPackageCost = helper::clearInt($defaultMessagesPackageCost);
                $settings->setValue("defaultMessagesPackageCost", $defaultMessagesPackageCost);
            }
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['allowAdBannerInGalleryItem'];
    $allowAdBannerInGalleryItem = $arr['intValue'];

    $arr = $config['galleryModeration'];
    $allowGalleryModeration = $arr['intValue'];

    $arr = $config['allowRewardedAds'];
    $allowRewardedAds = $arr['intValue'];

    $arr = $config['allowSeenTyping'];
    $allowSeenTyping = $arr['intValue'];

    $arr = $config['allowFacebookAuthorization'];
    $allowFacebookAuthorization = $arr['intValue'];

    $arr = $config['allowMultiAccountsFunction'];
    $allowMultiAccountsFunction = $arr['intValue'];

    $arr = $config['defaultBalance'];
    $defaultBalance = $arr['intValue'];

    $arr = $config['defaultReferralBonus'];
    $defaultReferralBonus = $arr['intValue'];

    $arr = $config['defaultFreeMessagesCount'];
    $defaultFreeMessagesCount = $arr['intValue'];

    $arr = $config['defaultGhostModeCost'];
    $defaultGhostModeCost = $arr['intValue'];

    $arr = $config['defaultVerifiedBadgeCost'];
    $defaultVerifiedBadgeCost = $arr['intValue'];

    $arr = $config['defaultDisableAdsCost'];
    $defaultDisableAdsCost = $arr['intValue'];

    $arr = $config['defaultProModeCost'];
    $defaultProModeCost = $arr['intValue'];

    $arr = $config['defaultSpotlightCost'];
    $defaultSpotlightCost = $arr['intValue'];

    $arr = $config['defaultMessagesPackageCost'];
    $defaultMessagesPackageCost = $arr['intValue'];

    $arr = $config['defaultAllowMessages'];
    $defaultAllowMessages = $arr['intValue'];

    $arr = $config['allowAutoModerate'];
    $allowAutoModerate = $arr['intValue'];

    $arr = $config['allowShowNotModeratedProfilePhotos'];
    $allowShowNotModeratedProfilePhotos = $arr['intValue'];

    $arr = $config['createChatsOnlyWithOTPVerified'];
    $createChatsOnlyWithOTPVerified = $arr['intValue'];

    $page_id = "app";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "App Settings";

    include_once("html/common/admin_header.inc.php");
?>

<body class="fix-header fix-sidebar card-no-border">

    <div id="main-wrapper">

        <?php

            include_once("html/common/admin_topbar.inc.php");
        ?>

        <?php

            include_once("html/common/admin_sidebar.inc.php");
        ?>

        <div class="page-wrapper">

            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                            <li class="breadcrumb-item active">App Settings</li>
                        </ol>
                    </div>
                </div>

                <?php

                    if (!$admin_info['error'] && $admin_info['access_level'] > ADMIN_ACCESS_LEVEL_READ_WRITE_RIGHTS) {

                        ?>
                        <div class="card">
                            <div class="card-body collapse show">
                                <h4 class="card-title">Warning!</h4>
                                <p class="card-text">Your account does not have rights to make changes in this section! The changes you've made will not be saved.</p>
                            </div>
                        </div>
                        <?php
                    }
                ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">App Settings</h4>
                                <h6 class="card-subtitle">Change application settings</h6>

                                <form action="/admin/app" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">

                                        <p>
                                            <input type="checkbox" name="allowAdBannerInGalleryItem" id="allowAdBannerInGalleryItem" <?php if ($allowAdBannerInGalleryItem == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowAdBannerInGalleryItem">Show banner ad when viewing an object in the gallery</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowGalleryModeration" id="allowGalleryModeration" <?php if ($allowGalleryModeration == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowGalleryModeration">Mandatory pre-moderation for gallery media items</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowRewardedAds" id="allowRewardedAds" <?php if ($allowRewardedAds == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowRewardedAds">Allow Rewarded Ads</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowSeenTyping" id="allowSeenTyping" <?php if ($allowSeenTyping == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowSeenTyping">Allow Seen&Typing functions in chat</label>
                                        </p>

                                        <p style="display: none">
                                            <input type="checkbox" name="allowFacebookAuthorization" id="allowFacebookAuthorization" <?php if ($allowFacebookAuthorization == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowFacebookAuthorization">Allow registration/authorization via Facebook</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowMultiAccountsFunction" id="allowMultiAccountsFunction" <?php if ($allowMultiAccountsFunction == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowMultiAccountsFunction">Enable creation of multi-accounts</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="defaultAllowMessages" id="defaultAllowMessages" <?php if ($defaultAllowMessages == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="defaultAllowMessages">Allow private messages from all users by default (activating this option can increase the flow of spam in messages, each user can change this option in the settings of his account)</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowAutoModerate" id="allowAutoModerate" <?php if ($allowAutoModerate == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowAutoModerate">Automatically approve new photos and covers without manual moderation (not recommended. people will be able to post porn and other prohibited images)</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowShowNotModeratedProfilePhotos" id="allowShowNotModeratedProfilePhotos" <?php if ($allowShowNotModeratedProfilePhotos == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowShowNotModeratedProfilePhotos">Non-moderated profile photos and profile covers are visible to all users (this option is only for the application)</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="createChatsOnlyWithOTPVerified" id="createChatsOnlyWithOTPVerified" <?php if ($createChatsOnlyWithOTPVerified == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="createChatsOnlyWithOTPVerified">Creation of private chats by users is possible only after verification of the mobile phone number (OTP Verification)</label>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultBalance" class="active">Balance of the user after registration (credits)</label>
                                        <input class="form-control" id="defaultBalance" type="number" size="4" name="defaultBalance" value="<?php echo $defaultBalance; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultReferralBonus" class="active">Number of credits for referral registration</label>
                                        <input class="form-control" id="defaultReferralBonus" type="number" size="4" name="defaultReferralBonus" value="<?php echo $defaultReferralBonus; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultFreeMessagesCount" class="active">Number of free messages for the user</label>
                                        <input class="form-control" id="defaultFreeMessagesCount" type="number" size="4" name="defaultFreeMessagesCount" value="<?php echo $defaultFreeMessagesCount; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultGhostModeCost" class="active">Cost of activating the Ghost Mode function (in credits)</label>
                                        <input class="form-control" id="defaultGhostModeCost" type="number" size="4" name="defaultGhostModeCost" value="<?php echo $defaultGhostModeCost; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultVerifiedBadgeCost" class="active">Cost activate Verified Badge (in credits)</label>
                                        <input class="form-control" id="defaultVerifiedBadgeCost" type="number" size="4" name="defaultVerifiedBadgeCost" value="<?php echo $defaultVerifiedBadgeCost; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultDisableAdsCost" class="active">The cost of disabling the banner ad (in credits)</label>
                                        <input class="form-control" id="defaultDisableAdsCost" type="number" size="4" name="defaultDisableAdsCost" value="<?php echo $defaultDisableAdsCost; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultProModeCost" class="active">Pro mode activation cost (in credits)</label>
                                        <input class="form-control" id="defaultProModeCost" type="number" size="4" name="defaultProModeCost" value="<?php echo $defaultProModeCost; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultSpotlightCost" class="active">Price add to the spotlight (in credits)</label>
                                        <input class="form-control" id="defaultSpotlightCost" type="number" size="4" name="defaultSpotlightCost" value="<?php echo $defaultSpotlightCost; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultMessagesPackageCost" class="active">Cost for message package (in credits)</label>
                                        <input class="form-control" id="defaultMessagesPackageCost" type="number" size="4" name="defaultMessagesPackageCost" value="<?php echo $defaultMessagesPackageCost; ?>">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>



            </div> <!-- End Container fluid  -->

            <?php

                include_once("html/common/admin_footer.inc.php");
            ?>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>
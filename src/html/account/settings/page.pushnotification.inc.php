<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */


    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
        exit;
    }

    $accountId = auth::getCurrentUserId();

    $account = new account($dbo, $accountId);

    $error = false;
    $send_status = false;
    $fullname = "";

    if (auth::isSession()) {

        $ticket_email = "";
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowMessages = isset($_POST['allowMessages']) ? $_POST['allowMessages'] : '';

        $allowVideoCalls = isset($_POST['allowVideoCalls']) ? $_POST['allowVideoCalls'] : '';

        $allowShowMyGallery = isset($_POST['allowShowMyGallery']) ? $_POST['allowShowMyGallery'] : '';
        $allowShowMyGifts = isset($_POST['allowShowMyGifts']) ? $_POST['allowShowMyGifts'] : '';
        $allowShowMyInfo = isset($_POST['allowShowMyInfo']) ? $_POST['allowShowMyInfo'] : '';

        $allowShowMyFriends = isset($_POST['allowShowMyFriends']) ? $_POST['allowShowMyFriends'] : '';
        $allowShowMyLikes = isset($_POST['allowShowMyLikes']) ? $_POST['allowShowMyLikes'] : '';

        $allowMessages = helper::clearText($allowMessages);
        $allowMessages = helper::escapeText($allowMessages);

        $allowVideoCalls = helper::clearText($allowVideoCalls);
        $allowVideoCalls = helper::escapeText($allowVideoCalls);

        $allowShowMyGallery = helper::clearText($allowShowMyGallery);
        $allowShowMyGallery = helper::escapeText($allowShowMyGallery);

        $allowShowMyGifts = helper::clearText($allowShowMyGifts);
        $allowShowMyGifts = helper::escapeText($allowShowMyGifts);

        $allowShowMyInfo = helper::clearText($allowShowMyInfo);
        $allowShowMyInfo = helper::escapeText($allowShowMyInfo);

        $allowShowMyFriends = helper::clearText($allowShowMyFriends);
        $allowShowMyFriends = helper::escapeText($allowShowMyFriends);

        $allowShowMyLikes = helper::clearText($allowShowMyLikes);
        $allowShowMyLikes = helper::escapeText($allowShowMyLikes);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            if ($allowMessages === "on") {

                $account->setAllowMessages(1);

            } else {

                $account->setAllowMessages(0);
            }

            $privacy_settings = $account->getPrivacySettings();

            $privacy_likes = $privacy_settings['allowShowMyLikes'];
            $privacy_gifts = $privacy_settings['allowShowMyGifts'];
            $privacy_friends = $privacy_settings['allowShowMyFriends'];
            $privacy_gallery = $privacy_settings['allowShowMyGallery'];
            $privacy_info = $privacy_settings['allowShowMyInfo'];
            $privacy_video_calls = $privacy_settings['allowVideoCalls'];

            if ($allowShowMyGallery === "on") {

                $privacy_gallery = 1;

            } else {

                $privacy_gallery = 0;
            }

            if ($allowShowMyGifts === "on") {

                $privacy_gifts = 1;

            } else {

                $privacy_gifts = 0;
            }

            if ($allowShowMyInfo === "on") {

                $privacy_info = 1;

            } else {

                $privacy_info = 0;
            }

            if ($allowShowMyFriends === "on") {

                $privacy_friends = 1;

            } else {

                $privacy_friends = 0;
            }

            if ($allowShowMyLikes === "on") {

                $privacy_likes = 1;

            } else {

                $privacy_likes = 0;
            }

            if ($allowVideoCalls === "on") {

                $privacy_video_calls = 1;

            } else {

                $privacy_video_calls = 0;
            }

            $account->setPrivacySettings($privacy_likes, $privacy_gifts, $privacy_friends, $privacy_gallery, $privacy_info, $privacy_video_calls);

            header("Location: /account/settings/privacy?error=false");
            exit;
        }

        header("Location: /account/settings/privacy?error=true");
        exit;
    }

    $account->setLastActive();

    $accountInfo = $account->get();

    auth::newAuthenticityToken();

    $page_id = "pushnotification";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-push-notification']." | ".APP_TITLE;

    include_once("html/common/site_header.inc.php");

?>

<body class="settings-page">

    <?php

        include_once("html/common/site_topbar.inc.php");
    ?>

    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("html/common/site_sidenav.inc.php");
            ?>

            <div class="col-lg-9 col-md-12" id="content">

                <div class="main-content">

                    <div class="standard-page">

                        <h1><?php echo $LANG['page-privacy-settings']; ?></h1>

                        <div class="tab-container">
                            <nav class="tabs">
                                <a href="/account/settings"><span class="tab"><?php echo $LANG['page-profile-settings']; ?></span></a>
                                <a href="/account/settings/privacy"><span class="tab active"><?php echo $LANG['page-privacy-settings']; ?></span></a>
                                <a href="/account/balance"><span class="tab"><?php echo $LANG['page-balance']; ?></span></a>
                                <a href="/account/settings/services"><span class="tab"><?php echo $LANG['label-services']; ?></span></a>
                                <a href="/account/settings/password"><span class="tab"><?php echo $LANG['label-password']; ?></span></a>
                                <a href="/account/settings/referrals"><span class="tab"><?php echo $LANG['page-referrals']; ?></span></a>
                                <a href="/account/settings/blacklist"><span class="tab"><?php echo $LANG['page-blacklist']; ?></span></a>
                                <a href="/account/settings/otp"><span class="tab"><?php echo $LANG['page-otp']; ?></span></a>
                                <a href="/account/settings/deactivation"><span class="tab"><?php echo $LANG['page-deactivate-account']; ?></span></a>
                                <a href="/account/settings/pushnotification"><span class="tab"><?php echo $LANG['page-push-notification']; ?></span></a>
                            </nav>
                        </div>

                        <form accept-charset="UTF-8" action="/account/settings/privacy" autocomplete="off" class="edit_user" id="settings-form" method="post">

                        <div class="push-notification-container">
                    <div class="push-notification-sideway">
                        <h6>
                            likes
                        </h6>
                        <input type="checkbox" name="likes">
                    </div>   
                    <div class="push-notification-sideway">
                        <h6>
                            Matches
                        </h6>
                        <input type="checkbox" name="matches">
                    </div>  
                    <div class="push-notification-sideway">
                        <h6>
                            Friend Requests
                        </h6>
                        <input type="checkbox" name="friend_requests">
                    </div>  
                    <div class="push-notification-sideway">
                        <h6>
                            Private Message
                        </h6>
                        <input type="checkbox" name="private_messages">
                    </div> 
                    <div class="push-notification-sideway">
                        <h6>
                            Gifts
                        </h6>
                        <input type="checkbox" name="gifts">
                    </div> 
                    <div class="push-notification-sideway">
                        <h6>
                          Comments
                        </h6>
                        <input type="checkbox" name="comments">
                    </div> 
                    </div>


                        </form>
                    </div>


                </div>

            </div>

        </div>

    </div>


        <?php

            include_once("html/common/site_footer.inc.php");
        ?>

</body>
</html>
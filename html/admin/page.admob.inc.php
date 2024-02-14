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

    $error = false;
    $error_message = '';

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    $android_admob_app_id = "ca-app-pub-3940256099942544~3347511713";
    $android_admob_banner_ad_unit_id = "ca-app-pub-3940256099942544/6300978111";
    $android_admob_rewarded_ad_unit_id = "ca-app-pub-3940256099942544/5224354917";
    $android_admob_interstitial_ad_unit_id = "ca-app-pub-3940256099942544/1033173712";
    $android_admob_banner_native_ad_unit_id = "ca-app-pub-3940256099942544/2247696110";

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $android_admob_app_id = isset($_POST['android_admob_app_id']) ? $_POST['android_admob_app_id'] : '';
        $android_admob_banner_ad_unit_id = isset($_POST['android_admob_banner_ad_unit_id']) ? $_POST['android_admob_banner_ad_unit_id'] : '';
        $android_admob_rewarded_ad_unit_id = isset($_POST['android_admob_rewarded_ad_unit_id']) ? $_POST['android_admob_rewarded_ad_unit_id'] : '';
        $android_admob_interstitial_ad_unit_id = isset($_POST['android_admob_interstitial_ad_unit_id']) ? $_POST['android_admob_interstitial_ad_unit_id'] : '';
        $android_admob_banner_native_ad_unit_id = isset($_POST['android_admob_banner_native_ad_unit_id']) ? $_POST['android_admob_banner_native_ad_unit_id'] : '';

        if ($authToken === helper::getAuthenticityToken() && $admin_info['access_level'] < ADMIN_ACCESS_LEVEL_MODERATOR_RIGHTS) {

            $android_admob_app_id = helper::clearText($android_admob_app_id);
            $android_admob_banner_ad_unit_id = helper::clearText($android_admob_banner_ad_unit_id);
            $android_admob_rewarded_ad_unit_id = helper::clearText($android_admob_rewarded_ad_unit_id);
            $android_admob_interstitial_ad_unit_id = helper::clearText($android_admob_interstitial_ad_unit_id);
            $android_admob_banner_native_ad_unit_id = helper::clearText($android_admob_banner_native_ad_unit_id);

            $settings->setValue("android_admob_app_id", 0, $android_admob_app_id);

            if (strlen($android_admob_banner_ad_unit_id) == 0) {

                $android_admob_banner_ad_unit_id = "ca-app-pub-3940256099942544/6300978111";
            }

            $settings->setValue("android_admob_banner_ad_unit_id", 0, $android_admob_banner_ad_unit_id);

            if (strlen($android_admob_rewarded_ad_unit_id) == 0) {

                $android_admob_rewarded_ad_unit_id = "ca-app-pub-3940256099942544/5224354917";
            }

            $settings->setValue("android_admob_rewarded_ad_unit_id", 0, $android_admob_rewarded_ad_unit_id);

            if (strlen($android_admob_interstitial_ad_unit_id) == 0) {

                $android_admob_interstitial_ad_unit_id = "ca-app-pub-3940256099942544/1033173712";
            }

            $settings->setValue("android_admob_interstitial_ad_unit_id", 0, $android_admob_interstitial_ad_unit_id);

            if (strlen($android_admob_banner_native_ad_unit_id) == 0) {

                $android_admob_banner_native_ad_unit_id = "ca-app-pub-3940256099942544/2247696110";
            }

            $settings->setValue("android_admob_banner_native_ad_unit_id", 0, $android_admob_banner_native_ad_unit_id);
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['android_admob_app_id'];
    $android_admob_app_id = $arr['textValue'];

    $arr = $config['android_admob_banner_ad_unit_id'];
    $android_admob_banner_ad_unit_id = $arr['textValue'];

    $arr = $config['android_admob_rewarded_ad_unit_id'];
    $android_admob_rewarded_ad_unit_id = $arr['textValue'];

    $arr = $config['android_admob_interstitial_ad_unit_id'];
    $android_admob_interstitial_ad_unit_id = $arr['textValue'];

    $arr = $config['android_admob_banner_native_ad_unit_id'];
    $android_admob_banner_native_ad_unit_id = $arr['textValue'];

    //

    if (APP_DEMO && strlen($android_admob_app_id) > 14) {

        $android_admob_app_id = "*****".substr($android_admob_app_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_banner_ad_unit_id) > 14) {

        $android_admob_banner_ad_unit_id = "*****".substr($android_admob_banner_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_rewarded_ad_unit_id) > 14) {

        $android_admob_rewarded_ad_unit_id = "*****".substr($android_admob_rewarded_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_interstitial_ad_unit_id) > 14) {

        $android_admob_interstitial_ad_unit_id = "*****".substr($android_admob_interstitial_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_banner_native_ad_unit_id) > 14) {

        $android_admob_banner_native_ad_unit_id = "*****".substr($android_admob_banner_native_ad_unit_id, -14);
    }

    //

    $page_id = "admob";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "AdMob Settings | Admin Panel";

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
                            <li class="breadcrumb-item active">AdMob Settings</li>
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
                    <div class="col-md-12">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="card-title">Warning!</h4>
                                <p class="card-text">In application changes will take effect during the next user authorization.</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Admob Info</h4>
                                <h6 class="card-subtitle">How to get banner_ad_unit_id from AdMob: <a href="https://raccoonsquare.com/help/how_to_get_banner_ad_unit_id_from_admob/" target="_blank">https://raccoonsquare.com/help/how_to_get_banner_ad_unit_id_from_admob/</a></h6>
                                <h6 class="card-subtitle">How to get ad_unit_id for Rewarded Ads from Admob: <a href="https://raccoonsquare.com/help/how_to_get_rewarded_ad_unit_id_from_admob/" target="_blank">https://raccoonsquare.com/help/how_to_get_rewarded_ad_unit_id_from_admob/</a></h6>
                                <h6 class="card-subtitle">How to create Interstitial ad block you can read here: <a href="https://raccoonsquare.com/help/how_to_add_interstitial_ad_to_you_android_app/" target="_blank">https://raccoonsquare.com/help/how_to_add_interstitial_ad_to_you_android_app/</a></h6>

                                <form action="/admin/admob" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group d-none">
                                        <label for="android_admob_app_id" class="active">Admob App ID</label>
                                        <input class="form-control" id="android_admob_app_id" type="text" size="64" name="android_admob_app_id" value="<?php echo $android_admob_app_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_banner_ad_unit_id" class="active">Admob Banner Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_banner_ad_unit_id" type="text" size="64" name="android_admob_banner_ad_unit_id" value="<?php echo $android_admob_banner_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_rewarded_ad_unit_id" class="active">Admob Rewarded Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_rewarded_ad_unit_id" type="text" size="64" name="android_admob_rewarded_ad_unit_id" value="<?php echo $android_admob_rewarded_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_interstitial_ad_unit_id" class="active">Admob Interstitial Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_interstitial_ad_unit_id" type="text" size="64" name="android_admob_interstitial_ad_unit_id" value="<?php echo $android_admob_interstitial_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_banner_native_ad_unit_id" class="active">Admob Native Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_banner_native_ad_unit_id" type="text" size="64" name="android_admob_banner_native_ad_unit_id" value="<?php echo $android_admob_banner_native_ad_unit_id; ?>">
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

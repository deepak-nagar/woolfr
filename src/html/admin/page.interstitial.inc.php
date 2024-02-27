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

    $defaultInterstitialAdAfterProfileView = 2;
    $defaultInterstitialAdAfterNewGalleryItem = 2;
    $defaultInterstitialAdAfterNewProfileLike = 2;
    $defaultInterstitialAdAfterNewLike = 2;
    $defaultInterstitialAdAfterNewComment = 2;

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $defaultInterstitialAdAfterProfileView = isset($_POST['defaultInterstitialAdAfterProfileView']) ? $_POST['defaultInterstitialAdAfterProfileView'] : 2;
        $defaultInterstitialAdAfterNewGalleryItem = isset($_POST['defaultInterstitialAdAfterNewGalleryItem']) ? $_POST['defaultInterstitialAdAfterNewGalleryItem'] : 2;
        $defaultInterstitialAdAfterNewProfileLike = isset($_POST['defaultInterstitialAdAfterNewProfileLike']) ? $_POST['defaultInterstitialAdAfterNewProfileLike'] : 2;
        $defaultInterstitialAdAfterNewLike = isset($_POST['defaultInterstitialAdAfterNewLike']) ? $_POST['defaultInterstitialAdAfterNewLike'] : 2;
        $defaultInterstitialAdAfterNewComment = isset($_POST['defaultInterstitialAdAfterNewComment']) ? $_POST['defaultInterstitialAdAfterNewComment'] : 2;

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            $defaultInterstitialAdAfterProfileView = helper::clearInt($defaultInterstitialAdAfterProfileView);
            $defaultInterstitialAdAfterNewGalleryItem = helper::clearInt($defaultInterstitialAdAfterNewGalleryItem);
            $defaultInterstitialAdAfterNewProfileLike = helper::clearInt($defaultInterstitialAdAfterNewProfileLike);
            $defaultInterstitialAdAfterNewLike = helper::clearInt($defaultInterstitialAdAfterNewLike);
            $defaultInterstitialAdAfterNewComment = helper::clearInt($defaultInterstitialAdAfterNewComment);

            $settings->setValue("interstitialAdAfterProfileView", $defaultInterstitialAdAfterProfileView);
            $settings->setValue("interstitialAdAfterNewGalleryItem", $defaultInterstitialAdAfterNewGalleryItem);
            $settings->setValue("interstitialAdAfterNewProfileLike", $defaultInterstitialAdAfterNewProfileLike);
            $settings->setValue("interstitialAdAfterNewLike", $defaultInterstitialAdAfterNewLike);
            $settings->setValue("interstitialAdAfterNewComment", $defaultInterstitialAdAfterNewComment);
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['interstitialAdAfterProfileView'];
    $defaultInterstitialAdAfterProfileView = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewGalleryItem'];
    $defaultInterstitialAdAfterNewGalleryItem = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewProfileLike'];
    $defaultInterstitialAdAfterNewProfileLike = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewLike'];
    $defaultInterstitialAdAfterNewLike = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewComment'];
    $defaultInterstitialAdAfterNewComment = $arr['intValue'];

    $page_id = "interstitial";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Interstitial Settings";

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
                            <li class="breadcrumb-item active">Interstitial Settings</li>
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
                                <h4 class="card-title">Interstitial Settings</h4>
                                <h6 class="card-subtitle">Change Interstitial settings</h6>
                                <h6 class="card-subtitle">How to create interstitial ad block you can read here: <a href="https://raccoonsquare.com/help/how_to_add_interstitial_ad_to_you_android_app/" target="_blank">https://raccoonsquare.com/help/how_to_add_interstitial_ad_to_you_android_app/</a></h6>

                                <form action="/admin/interstitial" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterProfileView" class="active">Show ads after how many profiles have been view (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterProfileView" type="number" size="4" name="defaultInterstitialAdAfterProfileView" value="<?php echo $defaultInterstitialAdAfterProfileView; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewGalleryItem" class="active">Show ads after how many added gallery elements (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewGalleryItem" type="number" size="4" name="defaultInterstitialAdAfterNewGalleryItem" value="<?php echo $defaultInterstitialAdAfterNewGalleryItem; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewProfileLike" class="active">Show ads after how many profiles likes (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewProfileLike" type="number" size="4" name="defaultInterstitialAdAfterNewProfileLike" value="<?php echo $defaultInterstitialAdAfterNewProfileLike; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewLike" class="active">Show ads after how many likes (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewLike" type="number" size="4" name="defaultInterstitialAdAfterNewLike" value="<?php echo $defaultInterstitialAdAfterNewLike; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewComment" class="active">Show ads after how many comments added (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewComment" type="number" size="4" name="defaultInterstitialAdAfterNewComment" value="<?php echo $defaultInterstitialAdAfterNewComment; ?>">
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
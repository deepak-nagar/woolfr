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

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    require 'sys/addons/vendor/autoload.php';

    use Google\Cloud\Storage\StorageClient;

    // Administrator info

    $admin = new admin($dbo);
    $admin->setId(admin::getCurrentAdminId());

    $admin_info = $admin->get();

    //

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    $settings_result = $settings->get();

    if (strlen($settings_result['gcs_photo_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_photo_bucket', 0, 'qa-app-photo-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_cover_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_cover_bucket', 0, 'qa-app-cover-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_gallery_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_gallery_bucket', 0, 'qa-app-gallery-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_video_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_video_bucket', 0, 'qa-app-video-'.helper::generateHash(7));
    }

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $gcs_photo = isset($_POST['gcs_photo']) ? $_POST['gcs_photo'] : 0;
        $gcs_cover = isset($_POST['gcs_cover']) ? $_POST['gcs_cover'] : 0;
        $gcs_gallery = isset($_POST['gcs_gallery']) ? $_POST['gcs_gallery'] : 0;
        $gcs_video = isset($_POST['gcs_video']) ? $_POST['gcs_video'] : 0;

        $gcs_photo = helper::clearInt($gcs_photo);
        $gcs_cover = helper::clearInt($gcs_cover);
        $gcs_gallery = helper::clearInt($gcs_gallery);
        $gcs_video = helper::clearInt($gcs_video);

        if ($access_token === admin::getAccessToken() && $admin_info['access_level'] < ADMIN_ACCESS_LEVEL_MODERATOR_RIGHTS) {

            $settings->setValue("gcs_photo", $gcs_photo);
            $settings->setValue("gcs_cover", $gcs_cover);
            $settings->setValue("gcs_gallery", $gcs_gallery);
            $settings->setValue("gcs_video", $gcs_video);

            header("Location: /admin/gcs");
            exit;
        }
    }

    $page_id = "gcs";

    $error = false;
    $error_message = '';

    $css_files = array("mytheme.css");
    $page_title = "Google Cloud Storage";

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
                            <li class="breadcrumb-item active">Google Cloud Storage</li>
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
                                <h4 class="card-title">Google Cloud Storage</h4>
                                <h6 class="card-subtitle">Image filtering</h6>
                                <h6 class="card-subtitle">How to enable Google Cloud Storage you can read here: <a href="https://raccoonsquare.com/help/how_to_enable_google_cloud_vision/" target="_blank">https://raccoonsquare.com/help/how_to_enable_google_cloud_vision/</a></h6>
                                <h6 class="card-subtitle">If a match is found, the image will not be published. I recommend not to set values less than "LIKELY" and "VERY_LIKELY" - experiment with the parameters and find the most suitable for your project.</h6>

                                <?php

                                    try {

                                        $jsonFileName = "";

                                        if ($files = glob("js/firebase/*.json")) {

                                            $jsonFileName = $files[0];
                                        }

                                        $storage = new StorageClient([

                                            'keyFilePath' => $jsonFileName
                                        ]);

                                        ?>

                                            <form action="/admin/gcs" method="post">

                                                <input type="hidden" name="access_token" value="<?php echo admin::getAccessToken(); ?>">

                                                <?php

                                                    $result = $settings->get();
                                                ?>

                                                <div class="form-group">
                                                    <label>Profile Photos</label>
                                                    <select class="form-control" name="gcs_photo">
                                                        <option <?php if ($result['gcs_photo']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_photo']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Profile Covers</label>
                                                    <select class="form-control" name="gcs_cover">
                                                        <option <?php if ($result['gcs_cover']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_cover']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Gallery Images</label>
                                                    <select class="form-control" name="gcs_gallery">
                                                        <option <?php if ($result['gcs_gallery']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_gallery']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Video Files</label>
                                                    <select class="form-control" name="gcs_video">
                                                        <option <?php if ($result['gcs_video']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_video']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Save</button>
                                                    </div>
                                                </div>
                                            </form>

                                        <?php

                                    } catch (\Google\ApiCore\ValidationException $e) {

                                        echo "The service account \".json\" file was not found or the file is invalid.";

                                    } catch (Exception $e) {

                                        echo "Error: ".$e->getMessage();
                                    }
                                ?>

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
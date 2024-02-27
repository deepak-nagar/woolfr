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

    // Administrator info

    $admin = new admin($dbo);
    $admin->setId(admin::getCurrentAdminId());

    $admin_info = $admin->get();

    //

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $chatsSpamCheckFeature = isset($_POST['chatsSpamCheckFeature']) ? $_POST['chatsSpamCheckFeature'] : 1;
        $autoLogoutSpamLevel = isset($_POST['autoLogoutSpamLevel']) ? $_POST['autoLogoutSpamLevel'] : 3;
        $autoBlockSpamLevel = isset($_POST['autoBlockSpamLevel']) ? $_POST['autoBlockSpamLevel'] : 10;

        $chatsSpamCheckFeature = helper::clearInt($chatsSpamCheckFeature);
        $autoLogoutSpamLevel = helper::clearInt($autoLogoutSpamLevel);
        $autoBlockSpamLevel = helper::clearInt($autoBlockSpamLevel);

        if ($access_token === admin::getAccessToken() && $admin_info['access_level'] < ADMIN_ACCESS_LEVEL_MODERATOR_RIGHTS) {

            $settings->setValue("chatsSpamCheckFeature", $chatsSpamCheckFeature);
            $settings->setValue("autoLogoutSpamLevel", $autoLogoutSpamLevel);
            $settings->setValue("autoBlockSpamLevel", $autoBlockSpamLevel);

            header("Location: /admin/spamcheck");
            exit;
        }
    }

    $page_id = "spamcheck";

    $error = false;
    $error_message = '';

    $css_files = array("mytheme.css");
    $page_title = "SpamCheck Feature";

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
                            <li class="breadcrumb-item active">SpamCheck Feature</li>
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
                                <h4 class="card-title">SpamCheck Feature</h4>
                                <h6 class="card-subtitle">If the user has not linked and verified his mobile number - his account is "not verified and potential spammer".</h6>
                                <h6 class="card-subtitle">If such a user sends a message (creates a chat), then the chat receives the status "potential spam" and the recipient of the message will see the dialog and will be able to select "Spam" or "Not spam"</h6>
                                <h6 class="card-subtitle">Next, you can configure actions: what to do with such "unverified and dangerous message senders".</h6>
                                <h6 class="card-subtitle">Users who have linked and verified a phone number do not fall into this risk zone and this function does not apply to their accounts.</h6>

                                <form action="/admin/spamcheck" method="post">

                                    <input type="hidden" name="access_token" value="<?php echo admin::getAccessToken(); ?>">

                                    <?php

                                        $result = $settings->get();
                                    ?>

                                    <div class="form-group">
                                        <label>Feature Status (On/Off)</label>
                                        <select class="form-control" id="chatsSpamCheckFeature" name="chatsSpamCheckFeature">
                                            <option <?php if ($result['chatsSpamCheckFeature']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Disabled</option>
                                            <option <?php if ($result['chatsSpamCheckFeature']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Enabled</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Close authorizations after reports (count)</label>
                                        <select class="form-control" id="autoLogoutSpamLevel" name="autoLogoutSpamLevel" <?php if ($result['chatsSpamCheckFeature']['intValue'] == 0) echo 'disabled'; ?>>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Not active</option>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 3) echo 'selected="selected"'; ?> value="3">3</option>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 5) echo 'selected="selected"'; ?> value="5">5</option>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 8) echo 'selected="selected"'; ?> value="8"">8</option>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 11) echo 'selected="selected"'; ?> value="11">11</option>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 13) echo 'selected="selected"'; ?> value="13">13</option>
                                            <option <?php if ($result['autoLogoutSpamLevel']['intValue'] == 15) echo 'selected="selected"'; ?> value="15">15</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Block account and delete all data after reports (count)</label>
                                        <select class="form-control" id="autoBlockSpamLevel" name="autoBlockSpamLevel" <?php if ($result['chatsSpamCheckFeature']['intValue'] == 0) echo 'disabled'; ?>>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Not active</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 5) echo 'selected="selected"'; ?> value="5">5</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 7) echo 'selected="selected"'; ?> value="7">7</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 9) echo 'selected="selected"'; ?> value="9">9</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 10) echo 'selected="selected"'; ?> value="10">10</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 11) echo 'selected="selected"'; ?> value="11">11</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 13) echo 'selected="selected"'; ?> value="13">13</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 15) echo 'selected="selected"'; ?> value="15">15</option>
                                            <option <?php if ($result['autoBlockSpamLevel']['intValue'] == 17) echo 'selected="selected"'; ?> value="17">17</option>
                                        </select>
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

            <script>

                $('#chatsSpamCheckFeature').on('change', function() {

                    switch (this.value) {

                        case '0': {

                            $('#autoLogoutSpamLevel').attr('disabled', 'disabled');
                            $('#autoBlockSpamLevel').attr('disabled', 'disabled');

                            break;
                        }

                        case '1': {

                            $('#autoLogoutSpamLevel').removeAttr('disabled');
                            $('#autoBlockSpamLevel').removeAttr('disabled');

                            break;
                        }
                    }
                });
            </script>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>
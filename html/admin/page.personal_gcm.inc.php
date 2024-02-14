<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2021 Demyanchuk Dmitry (raccoonsquare@gmail.com)
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

    if (isset($_GET['id'])) {

        $accountId = isset($_GET['id']) ? $_GET['id'] : 0;

        $accountId = helper::clearInt($accountId);

        $account = new account($dbo, $accountId);
        $accountInfo = $account->get();

        if ($accountInfo['error'] === true) {

            header("Location: /admin/main");
            exit;
        }

    } else {

        header("Location: /admin/main");
        exit;
    }

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $message = isset($_POST['message']) ? $_POST['message'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : 1;

        $message = helper::clearText($message);
        $message = helper::escapeText($message);

        $type = helper::clearInt($type);

        if ($authToken === helper::getAuthenticityToken() && $admin_info['access_level'] < ADMIN_ACCESS_LEVEL_MODERATOR_RIGHTS) {

            if (strlen($message) != 0) {

                $gcm = new gcm($dbo, $accountId);
                $gcm->setData($type, $message, 0);
                $gcm->send();
            }
        }

        header("Location: /admin/personal_gcm?id=".$accountId);
        exit;
    }

    $page_id = "personal_gcm";

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Firebase Cloud Messages | Admin Panel";

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
                            <li class="breadcrumb-item"><a href="/admin/profile?id=<?php echo $accountId; ?>"><?php echo $accountInfo['fullname']; ?></a></li>
                            <li class="breadcrumb-item active">Send Push Notification</li>
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

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Send Push Notification</h4>

                                <form class="form-material m-t-40"  method="post" action="/admin/personal_gcm?id=<?php echo $accountId; ?>">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label>Message type</label>
                                        <select class="form-control" name="type">
                                            <option selected="selected" value="<?php echo GCM_NOTIFY_SYSTEM; ?>">It will be shown, even if the user is not authorized</option>
                                            <option value="<?php echo GCM_NOTIFY_PERSONAL; ?>">Only an authorized user</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label >Message text</label>
                                        <input placeholder="Message text" id="message" type="text" name="message" maxlength="100" class="form-control form-control-line">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Send</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>


                </div>

                <?php
                    $result = $stats->getAccountGcmHistory($accountId);

                    $inbox_loaded = count($result['data']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Recently personal messages sent to the user</h4>
                                        <div class="table-responsive">

                                            <table class="table color-table info-table">

                                                <thead>
                                                    <tr>
                                                        <th class="text-left">Id</th>
                                                        <th>Message</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Delivered</th>
                                                        <th>Create At</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php

                                                        foreach ($result['data'] as $key => $value) {

                                                            draw($value);
                                                        }

                                                    ?>
                                                </tbody>

                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php

                    } else {

                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title">History is empty.</h4>
                                            <p class="card-text">This means that there is no data to display :)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>


            </div> <!-- End Container fluid  -->

            <?php

                include_once("html/common/admin_footer.inc.php");
            ?>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>

<?php

    function draw($itemObj)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $itemObj['id']; ?></td>
            <td><?php echo $itemObj['msg']; ?></td>
            <td>
                <?php

                    switch ($itemObj['msgType']) {

                        case GCM_NOTIFY_SYSTEM: {

                            echo "It will be shown, even if not authorized";
                            break;
                        }

                        case GCM_NOTIFY_PERSONAL: {

                            echo "Only an authorized user";
                            break;
                        }

                        default: {

                            break;
                        }
                    }
                ?>
            </td>
            <td><?php if ($itemObj['status'] == 1) {echo "success";} else {echo "failure";} ?></td>
            <td><?php echo $itemObj['success']; ?></td>
            <td><?php echo date("Y-m-d H:i:s", $itemObj['createAt']); ?></td>
        </tr>

        <?php
    }

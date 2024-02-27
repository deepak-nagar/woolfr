<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (!auth::isSession()) {

        header('Location: /');
        exit;
    }

    $error = false;

    if (isset($_SESSION['upgrades-error'])) {

        $error = true;

        unset($_SESSION['upgrades-error']);
    }

    $account = new account($dbo, auth::getCurrentUserId());
    $accountInfo = $account->get();

    if ($accountInfo['error']) {

        header('Location: /');
        exit;
    }

    auth::setCurrentUserAdmobFeature($accountInfo['admob']);
    auth::setCurrentUserGhostFeature($accountInfo['ghost']);

    $settings = new settings($dbo);
    $config = $settings->get();

    $arr = $config['defaultGhostModeCost'];
    $ghostModeCost = $arr['intValue'];

    $arr = $config['defaultVerifiedBadgeCost'];
    $verifiedBadgeCost = $arr['intValue'];

    $arr = $config['defaultDisableAdsCost'];
    $disableAdsCost = $arr['intValue'];

    $arr = $config['defaultProModeCost'];
    $proModeCost = $arr['intValue'];

    $arr = $config['defaultMessagesPackageCost'];
    $messagePackageCost = $arr['intValue'];

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $act = isset($_POST['act']) ? $_POST['act'] : '';

        if (auth::getAccessToken() === $token) {

            switch ($act) {

                case "ghost-mode": {

                    if ($accountInfo['ghost'] == 0 && $accountInfo['balance'] >= $ghostModeCost) {

                        $result = $account->setGhost(1);

                        if (!$result['error']) {

                            $account->setBalance($accountInfo['balance'] - $ghostModeCost);
                            auth::setCurrentUserBalance($accountInfo['balance'] - $ghostModeCost);

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_GHOST_MODE, PT_CREDITS, $ghostModeCost);
                            unset($payments);

                            auth::setCurrentUserGhostFeature(1);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }

                case "verified-badge": {

                    if ($accountInfo['verified'] == 0 && $accountInfo['balance'] >= $verifiedBadgeCost) {

                        $result = $account->setVerify(1);

                        if (!$result['error']) {

                            $account->setBalance($accountInfo['balance'] - $verifiedBadgeCost);
                            auth::setCurrentUserBalance($accountInfo['balance'] - $verifiedBadgeCost);

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_VERIFIED_BADGE, PT_CREDITS, $verifiedBadgeCost);
                            unset($payments);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }

                case "pro-mode": {

                    if ($accountInfo['pro'] == 0 && $accountInfo['balance'] >= $proModeCost) {

                        $result = $account->setPro(1);

                        if (!$result['error']) {

                            $account->setBalance($accountInfo['balance'] - $proModeCost);
                            auth::setCurrentUserBalance($accountInfo['balance'] - $proModeCost);

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_PRO_MODE, PT_CREDITS, $proModeCost);
                            unset($payments);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }

                case "message-package": {

                    if ($accountInfo['pro'] == 0 && $accountInfo['balance'] >= $messagePackageCost) {

                        $result = $account->setFreeMessagesCount(auth::getCurrentFreeMessagesCount() + 100);
                        auth::setCurrentFreeMessagesCount(auth::getCurrentFreeMessagesCount() + 100);

                        if (!$result['error']) {

                            $account->setBalance($accountInfo['balance'] - $messagePackageCost);
                            auth::setCurrentUserBalance($accountInfo['balance'] - $messagePackageCost);

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_MESSAGE_PACKAGE, PT_CREDITS, $messagePackageCost);
                            unset($payments);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }

                case "admob-feature": {

                    if ($accountInfo['admob'] == 0 && $accountInfo['balance'] >= $disableAdsCost) {

                        $result = $account->setAdmob(1);

                        if (!$result['error']) {

                            $account->setBalance($accountInfo['balance'] - $disableAdsCost);
                            auth::setCurrentUserBalance($accountInfo['balance'] - $disableAdsCost);

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_DISABLE_ADS, PT_CREDITS, $disableAdsCost);
                            unset($payments);

                            auth::setCurrentUserAdmobFeature(1);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }
            }

            $fcm = new fcm($dbo);
            $fcm->setRequestFrom(auth::getCurrentUserId());
            $fcm->setRequestTo(auth::getCurrentUserId());
            $fcm->setType(GCM_NOTIFY_CHANGE_ACCOUNT_SETTINGS);
            $fcm->setTitle("You settings is changed.");
            $fcm->prepare();
            $fcm->send();
            unset($fcm);
        }

        header("Location: /account/upgrades");
        exit;
    }

    $page_id = "upgrades";

    $css_files = array("main.css");
    $page_title = $LANG['page-upgrades']." | ".APP_TITLE;

    include_once("html/common/site_header.inc.php");

?>

<body class="width-page">

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

                    <div class="card border-0">
                        <div class="card-header row mx-0">
                            <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                <h3 class="card-title"><?php echo $LANG['page-upgrades']; ?></h3>
                                <h5 class="card-description"><?php echo $LANG['page-upgrades-sub-title']; ?></h5>
                            </div>
                            <div class="col-12 col-sm-3 col-md-3 col-lg-3 p-0 text-center">
                                <a class="button green d-block p-2" href="/account/balance">
                                    <span><b><?php echo $accountInfo['balance']; ?> <?php echo $LANG['label-credits']; ?></b></span>
                                    <br>
                                    <span><?php echo $LANG['action-buy-credits']; ?></span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="main-content mt-4">

                    <?php

                    if ($error) {

                        ?>
                        <div class="standard-page p-3">
                            <div class="errors-container">
                                <ul>
                                    <i class="icofont icofont-exclamation-circle"></i> <?php echo $LANG['label-balance-not-enough']; ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <form id="verified-badge-form" action="/account/upgrades" method="post">

                        <input type="hidden" name="act" value="verified-badge">
                        <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">

                        <div class="card border-0 shadow-none">
                            <div class="card-header row mx-0">
                                <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                    <div class="upgrades-feature-container">
                                        <span class="upgrades-feature-badge upgrades-feature-verified-badge">
                                            <i class="iconfont icofont-check-alt"></i>
                                        </span>
                                        <h3 class="card-title">
                                            <?php echo $LANG['label-upgrades-verified-badge']; ?>
                                        </h3>
                                        <h5 class="card-description"><?php echo $LANG['label-upgrades-verified-badge-desc']; ?></h5>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                    <?php

                                    if ($accountInfo['verify'] == 0) {

                                        ?>

                                            <button type="submit" class="action-button button blue p-2"><i class="icofont icofont-verification-check"></i>
                                                <?php echo $LANG['action-activate']; ?>
                                                <br>
                                                <small><?php echo $verifiedBadgeCost." ".$LANG['label-payments-credits']." <strong>(".$LANG['label-life-time'].")</strong>"; ?></small>
                                            </button>
                                        <?php

                                    } else {

                                        ?>
                                            <button disabled type="submit" class="action-button button secondary p-2"><i class="icofont icofont-verification-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="ghost-feature-form" action="/account/upgrades" method="post">

                        <input type="hidden" name="act" value="ghost-mode">
                        <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">

                        <div class="card border-0 shadow-none">
                            <div class="card-header row mx-0">
                                <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                    <div class="upgrades-feature-container">
                                        <span class="upgrades-feature-badge upgrades-feature-ghost-mode">
                                            <i class="iconfont icofont-foot-print"></i>
                                        </span>
                                        <h3 class="card-title">
                                            <?php echo $LANG['label-upgrades-ghost-mode']; ?>
                                        </h3>
                                        <h5 class="card-description"><?php echo $LANG['label-upgrades-ghost-mode-desc']; ?></h5>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                    <?php

                                    if ($accountInfo['ghost'] == 0) {

                                        ?>

                                        <button type="submit" class="action-button button blue p-2"><i class="icofont icofont-verification-check"></i>
                                            <?php echo $LANG['action-activate']; ?>
                                            <br>
                                            <small><?php echo $ghostModeCost." ".$LANG['label-payments-credits']." <strong>(".$LANG['label-month-time'].")</strong>"; ?></small>
                                        </button>
                                        <?php

                                    } else {

                                        ?>
                                        <button disabled type="submit" class="action-button button p-2 secondary"><i class="icofont icofont-verification-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="admob-feature-form" action="/account/upgrades" method="post">

                        <input type="hidden" name="act" value="admob-feature">
                        <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">

                        <div class="card border-0 shadow-none">
                            <div class="card-header row mx-0">
                                <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                    <div class="upgrades-feature-container">
                                        <span class="upgrades-feature-badge upgrades-feature-ads">
                                            <i class="iconfont icofont-not-allowed"></i>
                                        </span>
                                        <h3 class="card-title">
                                            <?php echo $LANG['label-upgrades-off-admob']; ?>
                                        </h3>
                                        <h5 class="card-description"><?php echo $LANG['label-upgrades-off-admob-desc']; ?></h5>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                    <?php

                                    if ($accountInfo['admob'] == 0) {

                                        ?>

                                        <button type="submit" class="action-button button blue p-2"><i class="icofont icofont-verification-check"></i>
                                            <?php echo $LANG['action-activate']; ?>
                                            <br>
                                            <small><?php echo $disableAdsCost." ".$LANG['label-payments-credits']." <strong>(".$LANG['label-month-time'].")</strong>"; ?></small>
                                        </button>
                                        <?php

                                    } else {

                                        ?>
                                        <button disabled type="submit" class="action-button button p-2 secondary"><i class="icofont icofont-verification-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="pro-mode-form" action="/account/upgrades" method="post">

                        <input type="hidden" name="act" value="pro-mode">
                        <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">

                        <div class="card border-0 shadow-none">
                            <div class="card-header row mx-0">
                                <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                    <div class="upgrades-feature-container">
                                        <span class="upgrades-feature-badge upgrades-feature-pro-mode">
                                            <i class="iconfont icofont-label"></i>
                                        </span>
                                        <h3 class="card-title">
                                            <?php echo $LANG['label-upgrades-pro-mode']; ?>
                                        </h3>
                                        <h5 class="card-description"><?php echo $LANG['label-upgrades-pro-mode-desc']; ?></h5>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                    <?php

                                    if ($accountInfo['pro'] == 0) {

                                        ?>

                                        <button type="submit" class="action-button button blue p-2"><i class="icofont icofont-verification-check"></i>
                                            <?php echo $LANG['action-activate']; ?>
                                            <br>
                                            <small><?php echo $proModeCost." ".$LANG['label-payments-credits']." <strong>(".$LANG['label-life-time'].")</strong>"; ?></small>
                                        </button>
                                        <?php

                                    } else {

                                        ?>
                                        <button disabled type="submit" class="action-button button p-2 secondary"><i class="icofont icofont-verification-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </form>

                    <?php

                        if ($accountInfo['pro'] == 0) {

                            ?>
                                <form id="pro-mode-form" action="/account/upgrades" method="post">

                                    <input type="hidden" name="act" value="message-package">
                                    <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">

                                    <div class="card border-0 shadow-none">
                                        <div class="card-header row mx-0">
                                            <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                                <div class="upgrades-feature-container">
                                                    <span class="upgrades-feature-badge upgrades-feature-message-package">
                                                        <i class="iconfont icofont-ui-message"></i>
                                                    </span>
                                                    <h3 class="card-title">
                                                        <?php echo $LANG['label-upgrades-message-package']; ?>
                                                    </h3>
                                                    <h5 style="font-weight: normal" class="card-description"><?php echo sprintf($LANG['label-free-messages-count'], "<strong>".auth::getCurrentFreeMessagesCount()."</strong>"); ?></h5>
                                                    <h5 class="card-description"><?php echo $LANG['label-upgrades-message-package-desc']; ?></h5>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                                <button type="submit" class="action-button button blue p-2"><i class="icofont icofont-verification-check"></i>
                                                    <?php echo $LANG['action-activate']; ?>
                                                    <br>
                                                    <small><?php echo $messagePackageCost." ".$LANG['label-payments-credits']; ?></small>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php
                        }
                    ?>


                </div>
            </div>
        </div>

    </div>

    <?php

        include_once("html/common/site_footer.inc.php");
    ?>

        <script type="text/javascript">


        </script>


</body
</html>

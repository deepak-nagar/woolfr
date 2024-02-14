<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth::isSession()) {

        header('Location: /');
        exit;
    }

    $balance = auth::getCurrentUserBalance();

    $settings = new settings($dbo);
    $config = $settings->get();

    $arr = $config['defaultSpotlightCost'];
    $spotlightCost = $arr['intValue'];

    ?>
    <div class="row separator-bottom">
        <div class="gallery-intro-header col-12 p-3 py-4 m-0">

            <h1 class="gallery-title"><?php echo $LANG['label-you-balance']; ?>: <span class="account-balance" data-balance="<?php echo $balance; ?>"><?php echo $balance; ?></span> <?php echo $LANG['label-credits']; ?></h1>

            <a class="add-button button green" href="/account/balance">
                <?php echo $LANG['action-buy']; ?>
            </a>

        </div>
    </div>

    <div class="error-summary alert alert-warning mt-4">
        <?php echo sprintf($LANG['msg-spotlight-dlg-content'], "<strong>".$spotlightCost."</strong>"); ?>
    </div>
    <?php

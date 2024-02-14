<?php

    /*!
    * ifsoft.co.uk
    *
    * http://ifsoft.com.ua, https://ifsoft.co.uk, https://raccoonsquare.com
    * raccoonsquare@gmail.com
    *
    * Copyright 2012-2020 Demyanchuk Dmitry (raccoonsquare@gmail.com)
    */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!auth::isSession()) {

        header("Location: /");
        exit;
    }

	$profileId = $helper->getUserId($request[0]);

	$itemExists = true;

	$profile = new profile($dbo, $profileId);

	$profile->setRequestFrom(auth::getCurrentUserId());
	$profileInfo = $profile->get();

	if ($profileInfo['error']) {

        include_once("html/error.inc.php");
		exit;
	}

	if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        include_once("html/error.inc.php");
		exit;
	}

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom(auth::getCurrentUserId());

	$itemId = helper::clearInt($request[2]);

	$itemInfo = $gallery->info($itemId);

	if ($itemInfo['error']) {

        // Missing
        $itemExists = false;
	}

	if ($itemExists && $itemInfo['removeAt'] != 0) {

		// Missing
        $itemExists = false;
	}

	if ($itemExists && $profileInfo['id'] != $itemInfo['owner']['id']) {

        // Missing
        $itemExists = false;
    }

    if ($itemExists && auth::getCurrentUserId() != $itemInfo['owner']['id'] && $itemInfo['moderateAt'] == 0) {

        $settings = new settings($dbo);
        $settings_arr = $settings->get();

        if ($settings_arr['galleryModeration']['intValue'] == 1) {

            // Missing
            $itemExists = false;
        }
    }

    $access_denied = false;

    if ($profileInfo['id'] != auth::getCurrentUserId() && !$profileInfo['friend'] && $profileInfo['allowShowMyGallery'] == 1 && $itemInfo['showInStream'] == 0) {

        $access_denied = true;
    }

	$page_id = "image";

	$css_files = array("main.css", "my.css");

	$page_title = $profileInfo['fullname']." | ".APP_HOST."/".$profileInfo['username'];

    include_once("html/common/site_header.inc.php");

?>

<body class="">


	<?php
        include_once("html/common/site_topbar.inc.php");
	?>


	<div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("html/common/site_sidenav.inc.php");
            ?>

            <div class="col-lg-9 col-md-12" id="content">

                <div class="content-list-page">

                    <?php

                    if ($access_denied) {

                        ?>

                        <div class="card information-banner border-0">
                            <div class="card-header">
                                <div class="card-body">
                                    <h5 class="m-0"><?php echo $LANG['label-error-permission']; ?></h5>
                                </div>
                            </div>
                        </div>

                        <?php

                    } else {

                        if ($itemExists) {

                            if ($itemInfo['owner']['id'] == auth::getCurrentUserId()) {

                                ?>
                                    <div class="main-content">
                                        <div class="gallery-intro-header">
                                            <?php

                                                if ($itemInfo['moderateAt'] != 0) {

                                                    ?>
                                                        <h1 class="gallery-title pr-0"><i class="iconfont icofont-verification-check pr-1"></i><?php echo $LANG['label-item-moderation-success']; ?></h1>
                                                    <?php

                                                } else {

                                                    ?>
                                                        <h1 class="gallery-title pr-0"><i class="iconfont icofont-warning-alt pr-1"></i><?php echo $LANG['label-item-moderation-wait']; ?></h1>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>

                                <?php
                            }

                            ?>

                            <div class="items-list content-list m-0">

                                <?php

                                    draw::image($itemInfo, $LANG, $helper, false);

                                ?>

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card information-banner">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="m-0"><?php echo $LANG['label-item-missing']; ?></h5>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
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

		var replyToUserId = 0;

		<?php

            if (auth::getCurrentUserId() == $profileInfo['id']) {

                ?>
					var myPage = true;
				<?php
    		}
		?>

	</script>


</body
</html>
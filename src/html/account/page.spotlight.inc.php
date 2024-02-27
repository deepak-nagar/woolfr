<?php

    /*!
     * http://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
        exit;
    }

    $spotlight = new spotlight($dbo);
    $spotlight->setRequestFrom(auth::getCurrentUserId());

    $items_all = $spotlight->count();
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $spotlight->get($itemId);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::peopleCardviewItem($value, $LANG, true, $value['age'], $LANG['label-select-age'], "red");
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Items.more('/account/spotlight', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['html2'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "spotlight";

    $css_files = array();
    $page_title = $LANG['page-spotlight']." | ".APP_TITLE;

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

                    <?php

                        $result = $spotlight->get(0);

                        $items_loaded = count($result['items']);

                        $add_me = true;

                        if ($items_loaded > 0) {

                            if ($result['items'][0]['id'] == auth::getCurrentUserId()) {

                                $add_me = false;
                            }
                        }
                    ?>

                    <div class="card border-0 pt-2">
                        <div class="card-header row mx-0">
                            <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                <h3 class="card-title">
                                    <?php echo $LANG['page-spotlight']; ?>
                                </h3>
                                <h5 class="card-description"><?php echo $LANG['page-spotlight-desc']; ?></h5>
                            </div>

                            <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                <?php

                                    if ($add_me) {

                                        ?>
                                            <button type="submit" class="action-button button green p-2" onclick="Spotlight.prepare(); return false;">
                                                <i class="icofont icofont-plus"></i> <?php echo $LANG['action-add-me']; ?>
                                            </button>
                                        <?php
                                    }
                                ?>

                            </div>
                        </div>

                        <div class="content-list-page empty-banner-container">

                            <?php

                            if ($items_loaded == 0) {

                                ?>

                                <header class="top-banner info-banner empty-list-banner">


                                </header>

                                <?php
                            }
                            ?>

                        </div>

                    </div>
                </div>

                <div class="main-content cardview-content">

                    <div class="standard-page cardview-container items-container">

                        <?php

                        if ($items_loaded != 0) {

                            ?>

                            <div class="cardview items-view">

                                <?php

                                    foreach ($result['items'] as $key => $value) {

                                        draw::peopleCardviewItem($value, $LANG, true, $value['age'], $LANG['label-select-age'], "red");
                                    }
                                ?>
                            </div>

                            <?php

                            if ($items_all > 20) {

                                ?>

                                <header class="top-banner loading-banner">

                                    <div class="prompt">
                                        <button onclick="Items.more('/account/spotlight', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>

            </div>

        </div>

    </div>

        <?php

            include_once("html/common/site_footer.inc.php");
        ?>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

        </script>

</body>
</html>
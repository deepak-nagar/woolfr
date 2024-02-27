<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, https://ifsoft.co.uk, https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2020 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

    if (!$auth::isSession()) {

        header('Location: /');
        exit;
    }

    $balance = auth::getCurrentUserBalance();

    $feelings = new feelings($dbo);
    $feelings->setRequestFrom(auth::getCurrentUserId());

    $result = $feelings->db_get(0);

    ?>

    <div class="container-fluid">
        <div class="row">

            <?php

            array_unshift($result['items'], array(
                        "id" => 0,
                        "title" => "",
                        "imgUrl" => "/feelings/0.png",
                        "removeAt" => 0
                    ));

                foreach ($result['items'] as $key => $item) {

                    ?>

                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 my-2 p-2 gift feeling" data-id="<?php echo $item['id']; ?>">

                        <img src="<?php echo $item['imgUrl']; ?>" style="z-index: 2;">

                    </div>


                    <?php
                }

            ?>
        </div>
    </div>
    <?php

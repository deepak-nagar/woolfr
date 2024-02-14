<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (auth::isSession()) {

        header("Location: /");
        exit;
    }

    if (isset($_SESSION['oauth'])) {

        unset($_SESSION['oauth']);
        unset($_SESSION['oauth_id']);
        unset($_SESSION['oauth_name']);
        unset($_SESSION['oauth_email']);
        unset($_SESSION['oauth_link']);
        unset($_SESSION['oauth_img_link']);

        header("Location: /signup");
        exit;
    }

    header("Location: /");
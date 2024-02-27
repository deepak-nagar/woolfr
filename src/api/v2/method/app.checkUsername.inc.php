<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $username = isset($_POST['username']) ? $_POST['username'] : '';

    $username = helper::clearText($username);
    $username = helper::escapeText($username);

    $result = array("error" => true);

    if (!$helper->isLoginExists($username)) {

        $result = array("error" => false);
    }

    echo json_encode($result);
    exit;
}

<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2021 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    // If timezone is not installed on the server

    if (!ini_get('date.timezone')) {

        date_default_timezone_set('Europe/London'); // Please set you timezone identifier, see here: http://php.net/manual/en/timezones.php
    }

    include_once("sys/config/db.inc.php");
    include_once("sys/config/constants.inc.php");
    include_once("sys/config/lang.inc.php");
    include_once("sys/config/payments.inc.php");

    foreach ($C as $name => $val) {

        define($name, $val);
    }

    foreach ($B as $name => $val) {

        define($name, $val);
    }

    if(!isset($_SESSION)) {

        ini_set('session.cookie_domain', APP_HOST);
        session_set_cookie_params(0, '/', APP_HOST);
        @session_regenerate_id(true);
        session_start();
    }

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
    $dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

//    $dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));

    spl_autoload_register(function($class)
    {

        $filename = "sys/class/class.".$class.".inc.php";

        if (file_exists($filename)) {

            include_once($filename);
        }
    });

    $helper = new helper($dbo);
    $auth = new auth($dbo);


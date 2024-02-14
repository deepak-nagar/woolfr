<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    error_reporting(E_ALL ^ E_DEPRECATED);

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (isset($_GET['error'])) {

        header("Location: /account/settings/services");
        exit;
    }

    require_once 'sys/addons/vendor/autoload.php';

    $fb = new \Facebook\Facebook([
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v2.10',
        //'default_access_token' => '{access-token}', // optional
    ]);


    // login helper with redirect_uri
    $fb_helper = $fb->getRedirectLoginHelper();

    if (isset($_GET['state'])) {

        $fb_helper->getPersistentDataHandler()->set('state', $_GET['state']);
    }

    if (isset($_GET['code'])) {

        try {

            $accessToken = $fb_helper->getAccessToken();

            $response = $fb->get('/me', $accessToken);

            $me = $response->getGraphUser();

            $accountId = $helper->getUserIdByFacebook($me->getId());

            if ($accountId != 0) {

                //user with fb id exists in db
                header("Location: /account/settings/services?oauth_provider=facebook&status=error");
                exit;

            } else {

                //new user

                $account = new account($dbo, auth::getCurrentUserId());
                $account->setFacebookId($me->getId());

                header("Location: /account/settings/services?oauth_provider=facebook&status=connected");
                exit;
            }

        } catch (Exception $e) {

            header("Location: /account/settings/services");
            exit;
        }


    } else {

        $loginUrl = $fb_helper->getLoginUrl(APP_URL."/facebook/connect");
        header("Location: ".$loginUrl);
        exit;
    }

<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2023 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    error_reporting(E_ALL ^ E_DEPRECATED);

    if (auth::isSession()) {

        header("Location: /");
        exit;
    }

    if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook') {

        header("Location: /signup");
        exit;
    }

    if (isset($_GET['error'])) {

        header("Location: /signup");
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

            $account = new account($dbo, $accountId);
            $accountInfo = $account->get();

            if (!$accountInfo['error']) {

                //user with fb id exists in db

                if ($accountInfo['state'] == ACCOUNT_STATE_BLOCKED) {

                    header("Location: /");
                    exit;

                } else if ($accountInfo['state'] == ACCOUNT_STATE_DISABLED) {

                    header("Location: /");
                    exit;

                } else {

                    $account->setState(ACCOUNT_STATE_ENABLED);
                    $account->setLastActive();

                    $clientId = 0; // Desktop version

                    $auth = new auth($dbo);
                    $access_data = $auth->create($accountId, $clientId, APP_TYPE_WEB, "", "");

                    if ($access_data['error'] === false) {

                        auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['balance'], $accountInfo['pro'], $accountInfo['free_messages_count'], $account->getAccessLevel($access_data['accountId']), $access_data['accessToken']);
                        auth::setCurrentUserAdmobFeature($accountInfo['admob']);
                        auth::setCurrentUserGhostFeature($accountInfo['ghost']);
                        auth::updateCookie($accountInfo['username'], $access_data['accessToken']);

                        header("Location: /account/find");
                    }
                }

            } else {

                //new user
                $_SESSION['oauth'] = 'facebook';
                $_SESSION['oauth_id'] = $me->getId();
                $_SESSION['oauth_name'] = $me->getName();

                $_SESSION['oauth_email'] = "";

                if (!is_null($me->getEmail())) {

                    $_SESSION['oauth_email'] = $me->getEmail();
                }

                header("Location: /signup");
                exit;
            }

            exit;

        } catch (Facebook\Exceptions\FacebookClientException $e) {

            echo 'Client error: '.$e->getMessage();
            exit;

        } catch(Facebook\Exceptions\FacebookResponseException $e) {

            // When Graph returns an error
            echo 'Graph returned an error: '.$e->getMessage();
            exit;

        } catch(Facebook\Exceptions\FacebookSDKException $e) {

            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;

        }  catch (Exception $e) {

            echo 'Generic error: '.$e->getMessage();
            exit;
        }

    } else {

        $loginUrl = $fb_helper->getLoginUrl(APP_URL."/facebook/login");
        header("Location: ".$loginUrl);
        exit;
    }

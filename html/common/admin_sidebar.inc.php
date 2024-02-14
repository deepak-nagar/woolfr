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

?>

<aside class="left-sidebar">

    <div class="scroll-sidebar"> <!-- Sidebar scroll-->

        <nav class="sidebar-nav"> <!-- Sidebar navigation-->

            <ul id="sidebarnav">

                <li class="nav-small-cap">General</li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "main") { echo "active";} ?>" href="/admin/main" aria-expanded="false">
                            <i class="ti-dashboard"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "gifts") { echo "active";} ?>" href="/admin/gifts" aria-expanded="false">
                            <i class="ti-gift"></i>
                            <span class="hide-menu">Gifts</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "stickers") { echo "active";} ?>" href="/admin/stickers" aria-expanded="false">
                            <i class="ti-face-smile"></i>
                            <span class="hide-menu">Stickers</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "vision") { echo "active";} ?>" href="/admin/vision" aria-expanded="false">
                            <i class="ti-eye"></i>
                            <span class="hide-menu">Google Cloud Vision</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "gcs") { echo "active";} ?>" href="/admin/gcs" aria-expanded="false">
                            <i class="ti-cloud-up"></i>
                            <span class="hide-menu">Google Cloud Storage</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "spamcheck") { echo "active";} ?>" href="/admin/spamcheck" aria-expanded="false">
                            <i class="ti-na"></i>
                            <span class="hide-menu">SpamCheck Feature</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "agora") { echo "active";} ?>" href="/admin/agora" aria-expanded="false">
                            <i class="ti-video-camera"></i>
                            <span class="hide-menu">Agora Settings</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "admob") { echo "active";} ?>" href="/admin/admob" aria-expanded="false">
                            <i class="ti-layout-list-post"></i>
                            <span class="hide-menu">Admob</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "interstitial") { echo "active";} ?>" href="/admin/interstitial" aria-expanded="false">
                            <i class="ti-layout-media-center"></i>
                            <span class="hide-menu">Interstitial Ad</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "upgrades") { echo "active";} ?>" href="/admin/upgrades" aria-expanded="false">
                            <i class="ti-star"></i>
                            <span class="hide-menu">Upgrades</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "gcm") { echo "active";} ?>" href="/admin/gcm" aria-expanded="false">
                            <i class="ti-bell"></i>
                            <span class="hide-menu">Push Notifications</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "users") { echo "active";} ?>" href="/admin/users" aria-expanded="false">
                            <i class=" ti-search"></i>
                            <span class="hide-menu">Search Users</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "support") { echo "active";} ?>" href="/admin/support" aria-expanded="false">
                            <i class="ti-help-alt"></i>
                            <span class="hide-menu">Support</span>
                        </a>
                    </li>

                <li class="nav-devider"></li>

                <li class="nav-small-cap">Moderation</li>

                <li>
                    <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "profile_photos_moderation") { echo "active";} ?>" href="/admin/moderation_profile_photos" aria-expanded="false">
                        <i class="ti-user"></i>
                        <span class="hide-menu">Profile Photos Moderation</span>
                    </a>
                </li>

                <li>
                    <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "profile_covers_moderation") { echo "active";} ?>" href="/admin/moderation_profile_covers" aria-expanded="false">
                        <i class="ti-image"></i>
                        <span class="hide-menu">Profile Covers Moderation</span>
                    </a>
                </li>

                <li>
                    <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "photos_moderation") { echo "active";} ?>" href="/admin/moderation_photos" aria-expanded="false">
                        <i class="ti-image"></i>
                        <span class="hide-menu">Gallery Items Moderation</span>
                    </a>
                </li>

                <li class="nav-devider"></li>

                <li class="nav-small-cap">Stream</li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "stream_photos") { echo "active";} ?>" href="/admin/stream_photos" aria-expanded="false">
                            <i class="ti-image"></i>
                            <span class="hide-menu">Photos Stream</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "stream_messages") { echo "active";} ?>" href="/admin/stream_messages" aria-expanded="false">
                            <i class="ti-comment-alt"></i>
                            <span class="hide-menu">Messages Stream</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "stream_comments") { echo "active";} ?>" href="/admin/stream_comments" aria-expanded="false">
                            <i class="ti-comments"></i>
                            <span class="hide-menu">Comments Stream</span>
                        </a>
                    </li>

                <li class="nav-devider"></li>

                <li class="nav-small-cap">Reports</li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "reports") { echo "active";} ?>" href="/admin/profile_reports" aria-expanded="false">
                            <i class="ti-face-sad"></i>
                            <span class="hide-menu">Profile Reports</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "photo_reports") { echo "active";} ?>" href="/admin/photo_reports" aria-expanded="false">
                            <i class="ti-gallery"></i>
                            <span class="hide-menu">Photo Reports</span>
                        </a>
                    </li>

                <li class="nav-devider"></li>

                <li class="nav-small-cap">Mobile</li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "purchases") { echo "active";} ?>" href="/admin/purchases" aria-expanded="false">
                            <i class="ti-money"></i>
                            <span class="hide-menu">In-app Purchases</span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "app") { echo "active";} ?>" href="/admin/app" aria-expanded="false">
                            <i class="ti-mobile"></i>
                            <span class="hide-menu">App Settings</span>
                        </a>
                    </li>

                <li class="nav-devider"></li>

                <li class="nav-small-cap">Settings</li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "settings") { echo "active";} ?>" href="/admin/settings" aria-expanded="false">
                            <i class="ti-settings"></i>
                            <span class="hide-menu">Settings</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark <?php if (isset($page_id) && $page_id === "admins") { echo "active";} ?>" href="/admin/admins" aria-expanded="false">
                            <i class="ti-crown"></i>
                            <span class="hide-menu">Administrators</span>
                        </a>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark" href="/admin/logout?access_token=<?php echo admin::getAccessToken(); ?>&continue=/" aria-expanded="false">
                            <i class="ti-power-off"></i>
                            <span class="hide-menu">Logout</span>
                        </a>
                    </li>

            </ul>
        </nav> <!-- End Sidebar navigation -->
    </div> <!-- End Sidebar scroll-->
</aside>
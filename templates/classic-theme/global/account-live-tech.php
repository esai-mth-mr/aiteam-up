<?php

overall_header(__("Daily Live Tech Calls"));

?>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php
    include_once TEMPLATE_PATH . '/dashboard_sidebar.php';
    ?>
    <!-- Dashboard Content
        ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("Daily Live Tech Calls") ?>
                </h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("Daily  Live Tech Calls") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="dashboard-box document-box margin-top-0 margin-bottom-30">
                <!-- Headline -->
                <!-- <h2>DAILY M-F LIVE CALL - TECH</h2>
                <h2>NOTICE: Thursday 23rd will be CANCELLED due to Thanksgiving.</h2>
                <h2>Daily (M-F) at 2:00PM PST</h2>
                <a href="https://us06web.zoom.us/j/84326982934">https://us06web.zoom.us/j/84326982934</a>
                <h2>Daily (M-F) at 11:00AM PST</h2>
                <a href="https://us06web.zoom.us/j/84326982934">https://us06web.zoom.us/j/84326982934</a> -->
                <div class="headline" style="justify-content: space-between;"></div>
                <div class="content with-padding">
                    <div style="display: flex; align-items: center;">
                        <img class="daily_live_tech_calls_img_class" style="" src="<?php echo $config['site_url'] . 'storage/banner/' . $config['daily_live_tech_calls_img']?>">
                    </div>
                    <span style="height: 40px; display: block;"></span>
                    <div class="daily_tech_calls_button">
                        <a style="color: white; font-size: 20px; font-weight: bold;" href="<?php echo $config['daily_tech_calls_link']?>" target="_blank"><?php _e("Get Your Tech Questions Answered Live!")?></a>
                    </div>    
                </div>
            </div>

            <?php print_adsense_code('footer_top'); ?>
            <!-- Footer -->
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-copyright">
                    <?php _esc($config['copyright_text']); ?>
                </div>
                <ul class="footer-social-links">
                    <?php
                    if ($config['facebook_link'] != "")
                        echo '<li><a href="' . _esc($config['facebook_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                    if ($config['twitter_link'] != "")
                        echo '<li><a href="' . _esc($config['twitter_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                    if ($config['instagram_link'] != "")
                        echo '<li><a href="' . _esc($config['instagram_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                    if ($config['linkedin_link'] != "")
                        echo '<li><a href="' . _esc($config['linkedin_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                    if ($config['pinterest_link'] != "")
                        echo '<li><a href="' . _esc($config['pinterest_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                    if ($config['youtube_link'] != "")
                        echo '<li><a href="' . _esc($config['youtube_link'], false) . '" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>
<?php ob_start() ?>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';

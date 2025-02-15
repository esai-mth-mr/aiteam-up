<?php
overall_header(__("Dashboard"));
?>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        height: 26px;
        width: 50px;
        border-radius: 13px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        border-radius: 11px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
</style>

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
            <div class="dashboard-headline headline_temp_dashboard">
                <div style="display: flex; margin-top: auto; margin-bottom: auto;">
                    <h3 style="font-weight: bold;"><?php _e("Welcome") ?></h3>
                    <h3 style="font-weight: bold;"><?php _e(", $customer_name") ?></h3>
                </div>
                <!-- Breadcrumbs -->
                <div style="background-color: <?php echo get_option("dashboard_quick_board_color") ?>;" class="uai_quick_guide_board dashboard_quick_guide">
                    <div style="color: <?php echo get_option("dashboard_quick_letter_color") ?>;" class="uai_quick_guide_board_text">
                        <div class="uai_quick_guide_board_text_title">
                            <a><?php _e(get_option("dashboard_quick_guide_title")) ?></a>
                        </div>
                        <div class="uai_quick_guide_board_text_content">
                            <a><?php _e(get_option("dashboard_quick_guide_text")) ?>
                            </a>
                        </div>
                        <div class="uai_quick_guide_board_text_actions">
                            <!-- <div class="uai_quick_guide_board_text_actions_gotit">
                                <a><?php _e("Got it") ?></a>
                            </div> -->
                            <div class="uai_quick_guide_board_text_actions_learnmore">
                                <a style="color: <?php echo get_option("dashboard_quick_url_color") ?>;" href="<?php echo get_option("dashboard_learn_more_link") ?>" target="_blank"><i class="icon-feather-help-circle"></i> <?php _e("Learn more") ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="uai_quick_guide_board_video">
                        <div class="uai_quick_guide_board_video_main">
                            <?php $video_link = get_option("onboarding_video_link"); ?>
                            <?php $video_file = get_option("onboarding_video_file"); ?>

                            <?php if (!empty($video_link) && empty($video_file)) { ?>
                                <?php if (filter_var($video_link, FILTER_VALIDATE_URL)) : ?>
                                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                        <!-- <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe> -->
                                        <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                    </div>
                                <?php else : ?>
                                    <p>Invalid video link</p>
                                <?php endif; ?>
                            <?php } elseif (!empty($video_file) && empty($video_link)) { ?>
                                <?php if (filter_var($video_file)) : ?>
                                    <video style="border-radius: 15px;" width="100%" height="auto" controls autoplay>
                                        <source src="<?php echo $config['site_url'] . 'storage/video/' . $video_file ?>" alt="" rel="external" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php else : ?>
                                    <p>Invalid video file</p>
                                <?php endif; ?>
                            <?php } elseif (!empty($video_link) && !empty($video_file)) { ?>
                                <?php if (filter_var($video_link, FILTER_VALIDATE_URL)) : ?>
                                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                        <!-- <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe> -->
                                        <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                    </div>
                                <?php else : ?>
                                    <p>Invalid video link</p>
                                <?php endif; ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

 
            <!-- Fun Facts Container -->
            <div class="fun-facts-container">
                <div class="fun-fact" data-fun-fact-color="#b81b7f">
                    <div class="fun-fact-text">
                        <span><?php _e("Words Used"); ?></span>
                        <h4>
                            <?php _esc(number_format($total_words_used)); ?>
                            <small>/ <?php _esc(
                                            $membership_settings['ai_words_limit'] == -1
                                                ? __('Unlimited')
                                                : number_format($membership_settings['ai_words_limit'] + get_user_option($_SESSION['user']['id'], 'total_words_available', 0))
                                        ); ?></small>
                        </h4>
                    </div>
                    <div class="fun-fact-icon"><i class="icon-feather-trending-up"></i></div>
                </div>
                <?php if ($config['enable_ai_images']) { ?>
                    <div class="fun-fact" data-fun-fact-color="#36bd78">
                        <div class="fun-fact-text">
                            <span><?php _e("Images Used"); ?></span>
                            <h4>
                                <?php _esc(number_format($total_images_used)); ?>
                                <small>/ <?php _esc(
                                                $membership_settings['ai_images_limit'] == -1
                                                    ? __('Unlimited')
                                                    : number_format($membership_settings['ai_images_limit'] + get_user_option($_SESSION['user']['id'], 'total_images_available', 0))
                                            ); ?></small>
                            </h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-feather-bar-chart-2"></i></div>
                    </div>
                <?php } else { ?>
                    <div class="fun-fact" data-fun-fact-color="#36bd78">
                        <div class="fun-fact-text">
                            <span><?php _e("Total Documents"); ?></span>
                            <h4>
                                <?php _esc(number_format($total_documents_created)); ?>
                            </h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-feather-bar-chart-2"></i></div>
                    </div>
                <?php }
                if ($config['enable_speech_to_text']) { ?>
                    <div class="fun-fact" data-fun-fact-color="#efa80f">
                        <div class="fun-fact-text">
                            <span><?php _e("Speech to Text"); ?></span>
                            <h4>
                                <?php _esc(number_format($total_speech_used)); ?>
                                <small>/ <?php _esc(
                                                $membership_settings['ai_speech_to_text_limit'] == -1
                                                    ? __('Unlimited')
                                                    : number_format($membership_settings['ai_speech_to_text_limit'] + get_user_option($_SESSION['user']['id'], 'total_speech_available', 0))
                                            ); ?></small>
                            </h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-feather-headphones"></i></div>
                    </div>
                <?php } else { ?>
                    <div class="fun-fact" data-fun-fact-color="#efa80f">
                        <div class="fun-fact-text">
                            <span><?php _e("Membership"); ?></span>
                            <h4>
                                <small><?php _esc($membership_name); ?></small>
                            </h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-feather-headphones"></i></div>
                    </div>
                <?php } ?>
            </div>

            <!-- Dashboard Box -->
            <div class="dashboard-box main-box-in-row">
                <div class="headline">
                    <h3><i class="icon-feather-bar-chart-2"></i> <?php _e("Word used this month"); ?></h3>
                </div>
                <div class="content">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="chart" width="100" height="350"></canvas>
                    </div>
                </div>
            </div>
            <!-- Dashboard Box / End -->

            <!-- Dashboard for Beta program Box -->
            <div class="dashboard-box main-box-in-row">
                <div class="headline" style="display: block;">
                    <h3 style="font-weight: bold;"><i class="icon-feather-chevrons-up"></i> <?php _e("AI TeamUP Labs."); ?></h3>
                    <a style="margin-left: 20px;"><?php _e("Pioneering the Future of AI Collaboration") ?></a>
                </div>
                <div class="content display-flex-toggle">
                    <div class="beta_program_text">
                        <a style="font-weight: bold;"><?php _e("Welcome to the AI TeamUP Beta Program") ?></a>
                        <br>
                        <br>
                        <a><?php _e("Join the forefront of innovation with our AI TeamUP Labs, where you have the exclusive opportunity to explore the newest features we've crafted for enhanced collaboration in the realm of artificial intelligence. As part of this program, you'll gain early access to cutting-edge tools that are set to redefine teamwork in AI.") ?></a>
                        <br>
                        <br>
                        <a><?php _e("We're committed to continuous improvement and value your insights. Your experience and feedback are critical; they will directly influence the evolution of these features. Together, let's shape the future of AI collaboration.") ?></a>
                        <br>
                        <br>
                    </div>
                    <div class="beta_program_image">
                        <img style="width: 65%;" src="<?php echo $config['site_url'] . "storage/banner/" . $config['dashboard_labs_banner'] ?>">
                    </div>
                </div>
            </div>
            <!-- Dashboard Box / End -->

            <!-- Dashboard for copy workflow Box -->
            <div class="dashboard-box main-box-in-row">
                <div style="display: flex; justify-content: space-between;" class="headline">
                    <h3 style="font-weight: bold;"><i class="icon-feather-bar-chart-2"></i> <?php _e("Enhanced Data Visualization Tools:"); ?></h3>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="content display-flex-toggle">
                    <div class="beta_program_text">
                        <a><?php _e("Transform data into insights with our new suite of intuitive and interactive charts and graphs.") ?></a>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <!-- Dashboard Box / End -->

            <!-- Dashboard for copy workflow Box -->
            <div class="dashboard-box main-box-in-row">
                <div style="display: flex; justify-content: space-between;" class="headline">
                    <h3 style="font-weight: bold;"><i class="icon-feather-bar-chart-2"></i> <?php _e("Real-time AI Development Streams:"); ?></h3>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="content display-flex-toggle">
                    <div class="beta_program_text">
                        <a><?php _e("Watch and participate in live AI model training sessions, with the ability to contribute and learn.") ?></a>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <!-- Dashboard Box / End -->

            <!-- Dashboard for copy workflow Box -->
            <div class="dashboard-box main-box-in-row">
                <div style="display: flex; justify-content: space-between;" class="headline">
                    <h3 style="font-weight: bold;"><i class="icon-feather-bar-chart-2"></i> <?php _e("Custom AI Model Builders:"); ?></h3>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="content display-flex-toggle">
                    <div class="beta_program_text">
                        <a><?php _e("Experience the simplicity of creating AI models with our drag-and-drop interface, no coding required.") ?></a>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <!-- Dashboard Box / End -->

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

<!-- <script>
    (function() {
        var pp = document.createElement('script'),
            ppr = document.getElementsByTagName('script')[0];
        stid = 'L0xwU04xTyt2aFc1ZUVmNE9aemdzQT09';
        pp.type = 'text/javascript';
        pp.async = true;
        pp.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 's01.live2support.com/dashboardv2/chatwindow/';
        ppr.parentNode.insertBefore(pp, ppr);
    })();
</script> -->

<?php ob_start() ?>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/chart.min.js"></script>
<script>
    Chart.defaults.global.defaultFontFamily = "Nunito";
    Chart.defaults.global.defaultFontColor = '#888';
    Chart.defaults.global.defaultFontSize = '14';

    var ctx = document.getElementById('chart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',

        // The data for our dataset
        data: {
            labels: <?php _esc($days); ?>,
            // Information about the dataset
            datasets: [{
                label: "<?php _e('Words Used'); ?>",
                backgroundColor: '<?php _esc($config['theme_color']); ?>15',
                borderColor: '<?php _esc($config['theme_color']); ?>',
                borderWidth: "3",
                data: <?php _esc($word_used); ?>,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHitRadius: 10,
                pointBackgroundColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointBorderWidth: 3,
                lineTension: 0.5,
            }]
        },

        // Configuration options
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10,
            },
            legend: {
                display: false
            },
            title: {
                display: false
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: false
                    },
                    gridLines: {
                        borderDash: [6, 10],
                        color: "#d8d8d8",
                        lineWidth: 1,
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: false
                    },
                    gridLines: {
                        display: false
                    },
                }],
            },
            tooltips: {
                backgroundColor: '#333',
                titleFontSize: 13,
                titleFontColor: '#fff',
                bodyFontColor: '#fff',
                bodyFontSize: 13,
                displayColors: false,
                xPadding: 10,
                yPadding: 10,
                intersect: false
            }
        },
    });
</script>

<!-- Footer / End -->
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';

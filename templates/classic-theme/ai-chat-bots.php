<?php

overall_header(__("AI Team"));

?>
<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php
    include_once TEMPLATE_PATH . '/dashboard_sidebar.php';
    include_once TEMPLATE_PATH . '/global/embed-modal.php';
    include_once TEMPLATE_PATH . '/global/embed-generate-modal.php';
    ?>
    <!-- Dashboard Content
        ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3 class="d-flex align-items-center">
                    <?php _e("AI Team") ?>
                    <div class="word-used-wrapper margin-left-10">
                        <i class="icon-feather-bar-chart-2"></i>
                        <?php echo '<i id="quick-words-left">' .
                            _esc(number_format((float)$total_words_used), 0) . '</i> / ' .
                            ($words_limit == -1
                                ? __('Unlimited')
                                : _esc(number_format($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)), 0));
                        ?>
                        <strong><?php _e('Words Used'); ?></strong>
                    </div>
                </h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("AI Team") ?></li>
                    </ul>
                </nav>
            </div>
            <?php if ($membership_ai_chat) { ?>
                <div class="notification notice">
                    <?php _e("We provide a team of skilled AI experts who are prepared to assist you with a wide range of needs.") ?>
                </div>

                <?php
                // } else { 
                ?>
                <!-- <div class="notification small-notification error"> -->
                <!-- <?php _e("Upgrade your membership plan to use this feature.") ?> -->
                <!-- </div> -->
            <?php }
            ?>

            <div>
                <input id="chat-bot-search" placeholder="<?php _e('Search...'); ?>" type="text" class="with-border border-pilled">
            </div>
            <div class="template-categories margin-bottom-30">
                <ul>
                    <li class="active"><a href="javascript:void();" class="ai-templates-category" data-category="all"><?php _e("All AI Team members") ?></a></li>

                    <li><a href="javascript:void();" class="ai-templates-category" data-category="hired"><?php _e("Hired") ?></a></li>
                    <li><a href="javascript:void();" class="ai-templates-category" data-category="available"><?php _e("Available") ?></a></li>
                    <li><a href="javascript:void();" class="ai-templates-category" data-category="uai"><?php _e("UAi Agent") ?></a></li>

                </ul>
            </div>

            <div class="row ai-template-blocks">
                <!-- <?php
                        /* if default chat bot is enabled */
                        if (get_option("enable_default_chat_bot", 1)) { ?>
                    <div class="col-lg-3 col-md-4 col-sm-6" data-search="<?php _esc($ai_chat_bot_name) ?>">
                        <div class="dashboard-box margin-top-0 margin-bottom-30">
                            <div class="content text-center">
                                <img src="<?php _esc($config['site_url']); ?>storage/profile/<?php _esc($ai_chat_bot_avatar) ?>" alt="<?php _esc($ai_chat_bot_name) ?>" class="rounded" width="100%">
                                <div class="padding-top-20 padding-right-20 padding-left-20 padding-bottom-20">
                                    <h3><?php _esc($ai_chat_bot_name) ?></h3>
                                    <small><?php _e('Default Bot'); ?></small>
                                    <div class="margin-top-15">
                                        <a href="<?php url('AI_CHAT') ?>" class="button button-sliding-icon ripple-effect full-width"><?php _e('Chat Now') ?>
                                            <i class="icon-feather-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?> -->

                <?php foreach ($chat_bots as $category) {
                    $translations = json_decode((string) $category['translations'], true);
                    $category_title = !empty($translations[$config['lang_code']]['title'])
                        ? $translations[$config['lang_code']]['title']
                        : $category['title'];

                    foreach ($category['chat_bots'] as $chat_bot) {
                        $translations = json_decode((string) $chat_bot['translations'], true);
                        $role = !empty($translations[$config['lang_code']]['role'])
                            ? $translations[$config['lang_code']]['role']
                            : $chat_bot['role'];
                        $hired = in_array($chat_bot['id'], $ai_chat_bot_hired);

                        $temp_message = substr($chat_bot['bio'], 0, 80);
                        $img_url = get_avatar_url_by_name($chat_bot['image']);
                ?>
                        <div data-hired="<?= $hired; ?>" <?php if ($chat_bot['is_uai_agent'] == 1) {
                                                                echo 'data-uai="1"';
                                                            } else {
                                                                echo 'data-uai="0"';
                                                            }; ?> class="col-lg-3 col-md-4 col-sm-6 category-<?php _esc($category['id']) ?> ai-team-staffs" data-search="<?php _esc($chat_bot['name'] . ' ' . $role . ' ' . $category_title) ?>">
                            <div class="theme-manage dashboard-box margin-top-0 margin-bottom-30 <?php echo (!in_array($chat_bot['id'], $membership_ai_chatbots)) ? 'chatbots-pro' : ''; ?>">
                                <div class="content text-center ai-templates-pro">
                                    <div class="chatbots-pro-pan ">
                                        <div style="position: relative;">
                                            <img style="margin-top: -2px;" src="<?php _esc($img_url); ?>" alt="<?php _esc($chat_bot['name']) ?>" class="rounded image-hover" width="100%">
                                            <?php if ($chat_bot['is_uai_agent'] == 1) { ?>
                                                <div class="uai-agent-ribbon">
                                                    <a>UAi</a>
                                                </div>
                                            <?php } ?>
                                            <div class="chatbots-pro-pan-1"></div>
                                        </div>
                                        <div class="chatbots-hire-fire">
                                            <?php if ($chat_bot['is_uai_agent'] == 1) { ?>
                                                <div class="uai-agent-embed dashboard-status-button yellow" id = "<?php echo $chat_bot['id'] ?>">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                    <a>embed</a>
                                                </div>
                                            <?php } ?>
                                            <?php if (in_array($chat_bot['id'], $ai_chat_bot_hired)) { ?>
                                                <div id="fireButton-<?= $chat_bot['id'] ?>" style="width: 70%; margin-left: 15%;">
                                                    <a href="<?php url('AI_CHAT') ?>/<?php _esc($chat_bot['id']) ?>" class="initial-fire-hire-button button button-sliding-icon ripple-effect full-width hire-fire-button"><?php _e('Chat') ?></a>
                                                    <button onclick="fire_bot(<?= $chat_bot['id'] ?>)" class="initial-fire-hire-button button button-sliding-icon ripple-effect full-width hire-fire-button"><?php _e('Fire') ?></button>
                                                </div>
                                                <div id="hireButton-<?= $chat_bot['id'] ?>" style="width: 70%; margin-left: 15%; display: none;">
                                                    <span style="width: 40%;"></span>
                                                    <button onclick="hire_bot(<?= $chat_bot['id'] ?>)" class="initial-fire-hire-button button button-sliding-icon ripple-effect full-width hire-fire-button"><?php _e('Hire now') ?></button>
                                                    <span style="width: 40%;"></span>
                                                </div>
                                            <?php } else { ?>
                                                <div id="fireButton-<?= $chat_bot['id'] ?>" style="width: 70%; margin-left: 15%; display: none;">
                                                    <a href="<?php url('AI_CHAT') ?>/<?php _esc($chat_bot['id']) ?>" class="initial-fire-hire-button button button-sliding-icon ripple-effect full-width hire-fire-button"><?php _e('Chat') ?></a>
                                                    <button onclick="fire_bot(<?= $chat_bot['id'] ?>)" class="initial-fire-hire-button button button-sliding-icon ripple-effect full-width hire-fire-button"><?php _e('Fire') ?></button>
                                                </div>
                                                <div id="hireButton-<?= $chat_bot['id'] ?>" style="width: 70%; margin-left: 15%;">
                                                    <span style="width: 40%;"></span>
                                                    <button onclick="hire_bot(<?= $chat_bot['id'] ?>)" class="initial-fire-hire-button button button-sliding-icon ripple-effect full-width hire-fire-button"><?php _e('Hire now') ?></button>
                                                    <span style="width: 40%;"></span>
                                                </div>
                                            <?php } ?>
                                        </div>


                                    </div>
                                    <div id="message-container" style="height: 180px; overflow: auto;" class="padding-top-20 padding-right-20 padding-left-20 padding-bottom-20">
                                        <h3 class="bot_name"><?php _esc($chat_bot['name']) ?></h3>
                                        <div class="margin-top-10" style=" color: <?php echo get_option("ai_employee_bio_letter_color")?>; "><?php _esc($role) ?></div>
                                        <small id="message-text" style="word-wrap: break-word;  color: <?php echo get_option("ai_employee_bio_letter_color")?>;"><?php echo $temp_message ?></small>
                                    </div>
                                </div>

                                <!-- <div class="content text-center"> -->
                                <!-- status icon  -->
                                <!-- <?php if (!in_array($chat_bot['id'], $ai_chat_bot_hired)) { ?>
                                        <span class="dashboard-status-button red" title="<?php _e("This member is available. You should hire") ?>" data-tippy-placement="top"><i class="fa fa-gift"></i> <?php _e("Available") ?></span>
                                    <?php } else { ?>
                                        <span class="dashboard-status-button green" title="<?php _e("This memeber was hired. You can chat now.") ?>" data-tippy-placement="top"><i class="fa fa-gift"></i> <?php _e("Hired") ?></span>
                                    <?php } ?> -->
                                <!--  -->
                                <!-- <img src="<?php _esc($img_url); ?>" alt="<?php _esc($chat_bot['name']) ?>" class="rounded" width="100%"> -->
                                <!-- <div style="max-height: 200px;" class="padding-top-20 padding-right-20 padding-left-20 padding-bottom-20">
                                        <h3 style="color: #0180DE; font-size: 20px; font-weight: 700;"><?php _esc($chat_bot['name']) ?></h3>
                                        <div class="margin-top-10"><?php _esc($role) ?></div>
                                        <small class="margin-top-10"><?php echo $temp_message ?></small> -->
                                <!-- chat now button -->
                                <!-- <div class="margin-top-15 d-flex">
                                            <?php if (in_array($chat_bot['id'], $ai_chat_bot_hired)) { ?>
                                                <a href="<?php url('AI_CHAT') ?>/<?php _esc($chat_bot['id']) ?>" class="button button-sliding-icon ripple-effect full-width"><?php _e('Chat') ?>
                                                    <i class="icon-feather-arrow-right"></i></a>
                                            <?php } else { ?>
                                                <a style="cursor: not-allowed; opacity: 0.5; color: white;" class="button button-sliding-icon ripple-effect full-width"><?php _e('Chat') ?>
                                                    <i class="icon-feather-arrow-right"></i></a>
                                            <?php } ?>
                                        </div> -->
                                <!-- fire/hire button -->
                                <!-- <?php if (in_array($chat_bot['id'], $ai_chat_bot_hired)) { ?>
                                            <div class="margin-top-15 d-flex">
                                                <button onclick="fire_bot(<?= $chat_bot['id'] ?>)" style="background-color: #ea5252; color: white;" class="button button-sliding-icon ripple-effect full-width"><?php _e('Fire') ?>
                                                    <i class="icon-feather-arrow-right"></i></button>
                                            </div>
                                        <?php } else { ?>
                                            <div class="margin-top-15 d-flex">
                                                <button onclick="hire_bot(<?= $chat_bot['id'] ?>)" style="background-color: #449626; color: white" class="button button-sliding-icon ripple-effect full-width"><?php _e('Hire') ?>
                                                    <i class="icon-feather-arrow-right"></i></button>
                                            </div>
                                        <?php } ?> -->
                                <!-- </div> -->
                                <!-- </div> -->

                            </div>
                        </div>
                <?php }
                } ?>
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

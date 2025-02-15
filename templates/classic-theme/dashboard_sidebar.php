<?php
global $current_user;
$plan_settings = $current_user['plan']['settings']; ?>
<!-- Dashboard Sidebar
    ================================================== -->
<div class="dashboard-sidebar">
    <div class="dashboard-sidebar-inner" data-simplebar>
        <div class="dashboard-nav-container">

            <!-- Responsive Navigation Trigger -->
            <a href="#" class="dashboard-responsive-nav-trigger">
                <span class="hamburger hamburger--collapse">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </span>
                <span class="trigger-title"><?php _e("Dashboard Navigation") ?></span>
            </a>
            <!-- Navigation -->
            <div class="dashboard-nav">
                <div class="dashboard-nav-inner">
                    <ul data-submenu-title="<?php _e("My Account") ?>">
                        <li class="<?php echo CURRENT_PAGE == 'app/dashboard' ? 'active' : ''; ?>"><a href="<?php url("DASHBOARD") ?>"><i class="icon-feather-grid"></i> <?php _e("Dashboard") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'app/all-speech-to-text' || CURRENT_PAGE == 'app/all-image' || CURRENT_PAGE == 'app/all-documents' || CURRENT_PAGE == 'app/all-chat' || CURRENT_PAGE == 'app/all-speeches' || CURRENT_PAGE == 'app/all-templates'? 'active-submenu' : ''; ?>">
                            <a href="#"><i class="icon-feather-file-text"></i> <?php _e("My Documents") ?></a>
                            <ul >
                                <li class="<?php echo CURRENT_PAGE == 'app/all-documents' ? 'active' : ''; ?>"><a href="<?php url("ALL_DOCUMENTS") ?>"><?php _e("All Documents") ?></a></li>
                                <?php if ($config['enable_ai_templates']) { ?>
                                    <li class="<?php echo CURRENT_PAGE == 'app/all-templates' ? 'active' : ''; ?>"><a href="<?php url("ALL_TEMPLATES") ?>"><?php _e("Templates") ?></a></li>
                                <?php } ?>
                                <?php if ($config['enable_ai_images']) { ?>
                                    <li class="<?php echo CURRENT_PAGE == 'app/all-image' ? 'active' : ''; ?>"><a href="<?php url("ALL_IMAGES") ?>"><?php _e("AI Images") ?></a></li>
                                <?php } ?>

                                <?php if ($config['enable_ai_chat']) { ?>
                                    <li class="<?php echo CURRENT_PAGE == 'app/all-chat' ? 'active' : ''; ?>"><a href="<?php url("ALL_CHAT") ?>"><?php _e("AI Team") ?></a></li>
                                <?php } ?>

                                <?php if (get_option('enable_text_to_speech', 0)) { ?>
                                    <li class="<?php echo CURRENT_PAGE == 'app/all-speeches' ? 'active' : ''; ?>"><a href="<?php url("ALL_SPEECHES") ?>"><?php _e("AI Voiceover") ?></a></li>
                                <?php } ?>
                                <?php if (get_option('enable_speech_to_text', 0)) { ?>
                                    <li style="margin-bottom: 10px;" class="<?php echo CURRENT_PAGE == 'app/all-speech-to-text' ? 'active' : ''; ?>"><a href="<?php url("ALL_SPEECHE_TO_TEXT") ?>"><?php _e("AI Speech To Text") ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>

                    <ul data-submenu-title="<?php _e("Organize and Manage") ?>">
                        <?php if (get_option('enable_ai_templates', 1)) { ?>
                            <li class="<?php echo CURRENT_PAGE == 'app/ai-templates' ? 'active' : ''; ?>">
                                <a href="<?php url("AI_TEMPLATES") ?>"><i class="icon-feather-layers"></i> <?php _e("Templates") ?></a>
                            </li>

                            <?php
                        }
                        if ($config['enable_ai_images']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_images_limit'])) { ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-images' ? 'active' : ''; ?>"><a href="<?php url("AI_IMAGES") ?>"><i class="icon-feather-image"></i> <?php _e("AI Images") ?></a></li>
                            <?php }
                        }

                        if ($config['enable_ai_chat']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_chat'])) { ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-chat' || CURRENT_PAGE == 'app/ai-chat-bots' ? 'active' : ''; ?>">
                                    <a href="<?php url("AI_CHAT_BOTS") ?>">
                                        <i class="icon-feather-message-circle"></i> <?php _e("AI Team") ?>
                                    </a>
                                </li>
                            <?php }
                        }

                        if ($config['enable_speech_to_text']) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_speech_to_text_limit'])) {
                            ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-speech-text' ? 'active' : ''; ?>"><a href="<?php url("AI_SPEECH_TEXT") ?>"><i class="icon-feather-headphones"></i> <?php _e("AI Speech to Text") ?></a>
                                </li>
                            <?php }
                        }

                        if (get_option('enable_text_to_speech', 0)) {
                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_text_to_speech_limit'])) { ?>
                                <li class="<?php echo CURRENT_PAGE == 'app/ai-text-speech' ? 'active' : ''; ?>"><a href="<?php url("AI_TEXT_SPEECH") ?>"><i class="icon-feather-volume-2"></i> <?php _e("AI Voiceover") ?></a>
                                </li>
                        <?php }
                        }

                        // if ($config['enable_ai_code']) {
                            // if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_code'])) { ?>
                                <!-- <li class="<?php //echo CURRENT_PAGE == 'app/ai-code' ? 'active' : ''; ?>"><a -->
                                            <!-- href="<?php //url("AI_CODE") ?>"><i -->
                                                <!-- class="icon-feather-code"></i> <?php //_e("AI Code") ?></a></li> -->

                        <li class="<?php echo (CURRENT_PAGE == 'app/uai' || CURRENT_PAGE == 'app/uai-chat-history') ? 'active' : ''; ?>">
                            <a href="<?php url("UAI") ?>">
                                <!-- <img src="<?= $config['site_url']; ?>storage/ai-img.png" alt="" class="mr-11" /> <?php _e("UAi") ?> -->
                                <i class="icon-feather-octagon"></i>
                                <?php _e("UAi Leads") ?>
                                <!-- <img src="<?= $config['site_url']; ?>storage/ai-img.png" alt="" class="mr-11" /> -->
                            </a>
                        </li>
                      
                    </ul>

                    <ul data-submenu-title="<?php _e("Features") ?>">
                        <?php 
                            if(!empty($plan_settings['user_permissions'])){
                                foreach($plan_settings['user_permissions'] as $slug){
                                    $menu = getUserSidebarMenus($slug);
                                    if(!empty($menu)){
                                        echo '<li><a href="javascript:void(0);">'.$menu['icon'].'&nbsp;'.$menu['title'].'</a></li>';
                                    }
                                }
                            }
                        ?>
                    </ul>

                    <ul data-submenu-title="<?php _e("Account") ?>">

                        <?php if ($config['enable_affiliate_program']) {
                            if (get_option('allow_affiliate_payouts', 1)) { ?>
                                <li class="<?= CURRENT_PAGE == 'global/affiliate-program' || CURRENT_PAGE == 'global/withdrawals' ? 'active-submenu' : ''; ?>">
                                    <a href="<?php url("AFFILIATE-PROGRAM") ?>"><i class="icon-feather-share-2"></i> <?php _e("Partner Program") ?></a>
                                    <ul>
                                        <li class="<?= CURRENT_PAGE == 'global/affiliate-program' ? 'active' : ''; ?>">
                                            <a href="<?php url("AFFILIATE-PROGRAM") ?>"><?php _e("Partner Program") ?></a>
                                        </li>
                                        <li class="<?= CURRENT_PAGE == 'global/withdrawals' ? 'active' : ''; ?>"><a href="<?php url("WITHDRAWALS") ?>"><?php _e("Withdrawals") ?></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } else { ?>
                                <li class="<?= CURRENT_PAGE == 'global/affiliate-program' ? 'active' : ''; ?>"><a href="<?php url("AFFILIATE-PROGRAM") ?>"><i class="icon-feather-share-2"></i> <?php _e("Partner Program") ?></a>
                                </li>
                        <?php }
                        } ?>
                        <li class="<?php echo CURRENT_PAGE == 'global/membership' ? 'active' : ''; ?>"><a href="<?php url("MEMBERSHIP") ?>"><i class="icon-feather-gift"></i> <?php _e("Membership") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/transaction' ? 'active' : ''; ?>"><a href="<?php url("TRANSACTION") ?>"><i class="icon-feather-file-text"></i> <?php _e("Transactions") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/account-setting' ? 'active' : ''; ?>"><a href="<?php url("ACCOUNT_SETTING") ?>"><i class="icon-feather-log-out"></i> <?php _e("Account Setting") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/custom-support' ? 'active' : ''; ?>"><a href="<?php url("CUSTOM_SUPPORT") ?>"><i class="icon-feather-eye"></i> <?php _e("Support & Training") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/account-live-tech' ? 'active' : ''; ?>"><a href="<?php url("Daily_Live_Tech") ?>"><i class="icon-feather-settings"></i> <?php _e("Daily Live Tech Calls") ?></a></li>
                        <li class="<?php echo CURRENT_PAGE == 'global/account-ai-ploy' ? 'active' : ''; ?>"><a href="<?php url("AI_PLOYEES") ?>"><i class="icon-feather-airplay"></i> <?php _e("AI Ployees Marketplace") ?></a></li>
                        <li><a href="<?php url("LOGOUT") ?>"><i class="icon-material-outline-power-settings-new"></i> <?php _e("Logout") ?></a></li>
                    </ul>

                </div>
            </div>
            <!-- Navigation / End -->
        </div>
    </div>
</div>
<!-- Dashboard Sidebar / End -->
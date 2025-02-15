<?php
global $config;
?>

<!-- ====== Hero Start ====== -->
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo $config['site_url']; ?>/includes/assets/css/icons.css" />
<link rel="icon" href="<?php echo $config['site_url'] . "storage/logo/" . $config['site_favicon'] ?>" type="image/x-icon">
<script src="<?php _esc(TEMPLATE_URL); ?>/js/jquery.min.js"></script>
<script src="<?php echo $config['site_url']; ?>/templates/classic-theme/js/word-toggle.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">

<div class="login_board">
    <div class="login_form_board">
        <!-- <span style="height: 150px; display: block;"></span> -->
        <div class="login_form_board_main">
            <a href="<?php echo $config['site_url']?>">
                <img style="height: 70px; margin-left: auto; margin-right: auto; display: block;" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['site_logo']; ?>">
            </a>
            <span style="height: 30px; display: block;"></span>
            <div style="margin-right: 10px;">

                <form method="post">
                    <a style="color: rgb(178, 178, 178); font-size: 14px;">Email or Username*</a>
                    <div class="de elInputWrapper de-input-block elAlign_center elMargin0 ui-droppable de-editable" id="tmp_input-60496-172" data-de-type="input" data-de-editing="false" data-title="input" data-ce="false" data-trigger="none" data-animate="fade" data-delay="500" type="first_name" style="margin-top: 5px; outline: none; cursor: pointer; font-family: Roboto, Helvetica, sans-serif !important;" data-element-theme="customized" aria-disabled="false" data-google-font="Roboto">
                        <input id="username" type="text" placeholder="you@yourawesomebusiness.com" name="username" class="elInput elInput100 elAlign_left elInputMid elInputStyl0 elInputIRight es-lightgreyInput elInputBG2 required1 elInputBR0 elInputIColor elInputI0 garlic-auto-save" data-type="extra" style="" required>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                        <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div>
                    </div>

                    <a style="color: rgb(178, 178, 178); font-size: 14px;">Password*</a>
                    <div class="de elInputWrapper de-input-block elAlign_center elMargin0 ui-droppable de-editable" id="input-22536-116" data-de-type="input" data-de-editing="false" data-title="input" data-ce="false" data-trigger="none" data-animate="fade" data-delay="500" type="email" style="margin-top: 5px; outline: none; cursor: pointer; font-family: Roboto, Helvetica, sans-serif !important;" data-element-theme="customized" aria-disabled="false" data-google-font="Roboto">
                        <input id="password" type="password" placeholder="your strong password" name="password" class="elInput elInput100 elAlign_left elInputMid elInputStyl0 elInputIBlack elInputIRight es-lightgreyInput elInputBG2 elInputBR0 required1 elInputI0 garlic-auto-save" data-type="extra" style="" required>
                    </div>

                    <div style="display: flex; align-items: baseline;">
                        <input style="width: 15px; height: 15px;" type="checkbox" />
                        <span style="width: 20px;"></span>
                        <a>Remember me for 2 weeks</a>
                    </div>
                    <span style="height: 20px; display: block;"></span>
                    <button name="submit" type="submit" style="height: auto; padding-top: 10px; padding-bottom: 10px; background-color: #edbc4e; width: 100%;">
                        <a style="margin-left: auto; margin-right: auto; display: table; color: rgb(71, 71, 71); font-size: 18px; font-weight: bold;">Log In</a>
                    </button>
                </form>
                <span style="height: 20px; display: block;"></span>
                <a href="<?php url("LOGIN") ?>?fstart=1" style="color: rgb(0, 117, 178); font-size: 14px; font-weight: 700;">Forgot your password?</a>
                <span style="height: 20px; display: block;"></span>
                <a style="margin-left: auto; margin-right: auto; display: table; font-size: 14px;">New to us?</a>
                <div style="border: 1px solid rgba(0,0,0,0.2) ; margin-left: auto; margin-right: auto; width: 150px; height: auto; padding-top: 3px; padding-bottom: 3px; background-color: white; border-radius: 5px;">
                    <a href="<?php echo $config['site_url'] . "signup" ?>" style="margin-left: auto; margin-right: auto; display: table; color: rgb(96, 96, 96);; font-size: 14px; font-weight: bold;">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
    <div class="login_brand_board">
        <div class="login_brand_board_main">
            <div class="login_brand_board_title">
                <b style="color: #e43b2c !important; font-weight: 900; font-size: 16px;">Attention:</b><a style="font-size: 16px; color: white; font-weight: 900;">Ready To Transform Your Business Content & Lead Generation?</a>
                <!-- <b class="login_brand_board_title_1">"Just Give Us 5 Short Days,</b> -->
            </div>
            <span style="height: 10px; display: block;"></span>
            <!-- <div class="login_brand_board_title_2_board">
                <b class="login_brand_board_title_1" style="font-size: 16px !important; color: white !important; font-weight: 500 !important;">And We’ll Guide You Through The Process Of Building</b>
            </div> -->
            <div class="m-top-minus-20"><b class="f-ubuntu f-w-900 f-size-34 f-color-e43b2c">"Just Give Us 5 Short Days,</b></div>
            <span style="height: 10px; display: block;"></span>
            <div style="width: fit-content; margin: auto;"><b class="f-ubuntu f-w-700 f-size-16 f-color-w">And We’ll Guide You Through The Process Of Building</b></div>
            <span style="height: 30px; display: block;"></span>
            <div style="width: fit-content; margin: auto;"><b class="f-ubuntu f-w-bold f-size-36 f-color-w">Your First AI Team (UAi)</b></div>
            <span style="height: 15px; display: block;"></span>
            <div style="width: 60%; margin: auto;"><b class="f-ubuntu f-w-700 f-size-18 f-color-w">Boost Your Business with UAi: Your 5-Day Path to AI-Powered Content and Leads.</b></div>
            <div style="width: 70%; height: auto; margin-bottom: 40px; margin-top: 10px; margin-left: auto; margin-right: auto;">
                <?php $video_link = get_option("login_video_link"); ?>
                <?php $video_file = get_option("login_video_file"); ?>

                <?php if (!empty($video_link) && empty($video_file)) { ?>
                    <?php if (filter_var($video_link, FILTER_VALIDATE_URL)) : ?>
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <!-- <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe> -->
                            <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%; margin-left: -50%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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
                            <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%; margin-left: -50%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    <?php else : ?>
                        <p>Invalid video link</p>
                    <?php endif; ?>
                <?php } ?>
            </div>
            <div style="background-color: black; width: 60%; margin-left: 20%; height: auto; padding-left: 15px; padding-right: 15px; padding-top: 5px; padding-bottom: 5px;">
                <a class="f-ubuntu f-w-400 f-size-16 f-color-w f-line-h-23">(No Prior AI Knowledge? No Problem! This Challenge Is Designed For All Skill Levels And Businesses Looking To Harness The Power Of AI.)</a>
            </div>
            <span style="height: 30px; display: block;"></span>
            <div><b class="f-ubuntu f-w-bold f-size-30 f-color-e43b2c">NEXT CHALLENGE STARTS SOON</b></div>
            <span style="height: 30px; display: block;"></span>
            <div class="login_brand_board_new_ch_button">
                <a style="display: block; align-items: center; color: white;" href="<?php echo $config['cta_link'] ?>" target="_blank">
                    <i class="fa fa_prepended fas fa-ticket-alt"></i>
                    <span style="width: 10px;"></span>
                    <b class="f-size-18 f-w-600 f-ubuntu"> JOIN THE 'Your First AI Team' Challenge For FREE</b>
                </a>
                <a style="font-size: 14px; opacity: 0.7; color: white;">Coming Soon</a>
            </div>
        </div>

    </div>
</div>
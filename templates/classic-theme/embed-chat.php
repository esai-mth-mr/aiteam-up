<html lang="en" style="height: auto; ">
<?php global $config; ?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

</head>
<link rel="stylesheet" type="text/css" href=" <?php echo $config['site_url'] . "templates/classic-theme/css/embed-style.css" ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $config['site_url'] . "includes/assets/css/icons.css" ?>">

<body style="background-color: transparent;">
    <?php if ($stand_alone == "true") { ?>
        <div class="embed_chat_wraper <?php if ($is_iframe != "true") echo 'margin_15' ?>">
            <div id="embed_chat_icon" style="z-index: 9999; position: fixed; display: none !important;" data-stand_alone=<?php echo $stand_alone ?> data-uai_font_letter_color=<?php echo $uai_font_letter_color ?> data-uai_board_color=<?php echo $uai_board_color ?> data-user_font_letter_color=<?php echo $user_font_letter_color ?> data-user_board_color=<?php echo $user_board_color ?> data-autoresponder=<?php echo $auto_responder ?> data-embed-id="<?php echo $embed_id ?>" data-embed-bot-image=<?php echo $bot_image ?> data-embed-user-image=<?php echo $user_image ?> data-embed-user-name=<?php echo $user_name ?>>
                <img class="aiteamup_embed_chat_icon_wrapper <?php if ($embed_horizental == 'right') {
                                                                    echo 'aiteamup_embed_right_horizental' ?> 
                                                        <?php } else {
                                                                    echo 'aiteamup_embed_left_horizental' ?> <?php } ?>" src=" <?php echo $config['site_url'] . "storage/logo/" . $embed_icon ?>" ; width="100%" ; height="100%" ;>
            </div>

            <?php if ($is_iframe == "true") { ?>
                <div id="embed_chat_pre_board" style="<?php if ($auto_responder != "None") {
                                                            echo "display: block;" ?>
                    <?php } else {
                                                            echo "display : none;" ?>
                    <?php } ?>" class="aiteamup_embed_pre_chat_board aiteamup_embed_iframe">
                <?php } else { ?>

                    <div id="embed_chat_pre_board" style="<?php if ($auto_responder != "None") {
                                                                echo "display: block; margin-left:5%;" ?>
                                                <?php } else {
                                                                echo "display : none;" ?>
                                                <?php } ?>" class="aiteamup_embed_pre_chat_board 
                                                <?php if ($embed_horizental == 'right') {
                                                    echo 'aiteamup_embed_right_horizental' ?> 
                                                <?php } else {
                                                    echo 'aiteamup_embed_left_horizental' ?> <?php } ?>">

                    <?php } ?>

                    <div class=" aiteamup_embed_title"><a>👋 Hi, This is <?php echo $embed_name ?></a></div>

                    <div id="embed_chat_bot_profile" class="aiteamup_embed_bot_profile_board">
                        <div style="display: flex; width: auto; margin-left: 100px;">
                            <img class="aiteamup_embed_bot_profile_avatar" src="<?php echo $bot_image ?>" ; width="100%" ; height="100%" ;>
                            <div class="aiteamup_embed_bot_intro">
                                <a class="aiteamup_embed_bot_name"><?php echo $bot_name ?></a>
                                <!-- <a> <?php echo substr($bot_role, 0, 20) . "..." ?></a> -->
                                <a> <?php echo $bot_role ?></a>
                            </div>
                        </div>
                    </div>

                    <div id="embed_chat_pre_pan" class="aiteamup_embed_pre_chat_pan" style="<?php if($is_iframe == "true") { echo "margin-top: 17%"?> <?php }?>">
                        <div style="margin-left: 10%; width: 80%; margin-top: 30px;">
                            <div style="margin-bottom: 50px; display: flex; justify-content: center;">
                                <a style="font-size: 20px; font-weight: bold;">Enter the information below to start chatting</a>
                                <!-- <br>
                                <a>Your information is kept confidential.</a> -->
                            </div>
                            <!-- <a>Name</a> -->
                            <input id="aiteamup_embed_pre_name_input" type="text" class="aiteamup_embed_message_input" placeholder="First Name" style="width: 100% !important;" />
                            <!-- <a>Email address</a> -->
                            <input id="aiteamup_embed_pre_email_input" type="email" class="aiteamup_embed_message_input" placeholder="Email address" style="width: 100% !important;" />
                            <!-- <div>
                                <div class="form-label"><?php _e("Upload your image"); ?></div>
                                <div class="col-md-12 embed-image-wraper" id="upload-embed-icon">
                                    <label for="embed_pre_image" class="upload_img_wrapper show-select-uai-image" style="height: 100px; display: flex; justify-content: center; align-items: center; cursor: pointer;">
                                        <img id="pre-uploaded-image" src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px;">
                                    </label>
                                    <input name="embed_pre_image" id="embed_pre_image" type="file" class="with-border small-input" accept=".png, .jpg, .jpeg" style="display: none;" onchange="displaySelectedImage()">
                                </div>
                            </div> -->
                            <div style="display: flex; margin-top: 50px;">
                                <input id="embed_chat_terms" style="width: 20px; height: 20px;" type="checkbox" />
                                <span style="width: 20px;"></span>
                                <div>I agree to the <a href="<?php echo $embed_terms ?>" target="_blank">terms and conditions</a> of interacting with this AI model.</div>
                            </div>
                            <div style="background-color: <?php echo $embed_start_chat_btn_background_color ?>;" class="embed_pre_start_chat_btn">
                                <a><?php echo $embed_start_chat_text ?></a>
                            </div>
                            <?php if ($brand_toggle == 1) { ?>
                                <div style="width: 100%; position: absolute; bottom : 0">
                                    <a style=" display: flex; justify-content: center; font-size: 12px;" href="https://aiteamup.com" target="_blank">Powered by AITeamUP.</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div style="height: 10px;"></div>

                    </div>
                    <div id="embed_chat_board" class="aiteamup_embed_chat_board <?php if ($is_iframe == "true") {
                                                                                    echo "aiteamup_embed_chat_board_iframe" ?> <?php } ?>" style="<?php if ($is_iframe == "true") {
                                                                                                                                                        echo "margin-top: 0%;" ?> <?php } ?> <?php
                                                                                                                                                                                                if ($auto_responder == "None") {
                                                                                                                                                                                                    echo "display: block !impotant;" ?>
                        <?php } else {
                                                                                                                                                                                                    echo "display : none;" ?>
                        <?php } ?>">
                        <div class="aiteamup_embed_title" style="width: 100% !important;"><a>👋 Hi, This is <?php echo $embed_name ?></a></div>

                        <div id="embed_chat_bot_profile" class="aiteamup_embed_bot_profile_board">
                            <div style="display: flex; width: auto; margin-left: 100px;">
                                <img class="aiteamup_embed_bot_profile_avatar" src="<?php echo $bot_image ?>" ; width="100%" ; height="100%" ;>
                                <div class="aiteamup_embed_bot_intro">
                                    <a class="aiteamup_embed_bot_name"><?php echo $bot_name ?></a>
                                    <!-- <a> <?php echo substr($bot_role, 0, 20) . "..." ?></a> -->
                                    <a> <?php echo $bot_role ?></a>
                                </div>
                            </div>
                            <div id="new_chat_btn" class="new_chat_btn">
                                <i class="icon-feather-refresh-ccw"></i>
                                <a>New Chat</a>
                            </div>
                        </div>

                        <div id="embed_chat_pan" class="aiteamup_embed_chat_pan">
                            <div class="aiteamup_embed_chat_content_board">
                                <img class="aiteamup_embed_chat_profile_avatar" src="<?php echo $bot_image ?>" ; width="100%" ; height="100%" ;>
                                <div class="aiteamup_embed_chat_content" style="background-color: <?php echo $uai_board_color ?> !important;"><a class="aiteamup_embed_letter" style="color: <?php echo $uai_font_letter_color ?> !important;"><?php echo $welcome_message ?></a></div>
                            </div>
                        </div>

                        <div style="height: 10px;"></div>
                        <div class="pre_made_message_pan">
                            <div class="pre_made_message_board row">
                                <?php foreach ($questions as $key => $question) {
                                    $words = explode(" ", $question);
                                    $firstThreeWords = implode(" ", array_slice($words, 0, 3));
                                    $remainingPart = implode(" ", array_slice($words, 3)); ?>
                                    <div class="col-sm-6 pre_made_message_item" data-question="<?php echo $question; ?>">
                                        <div class="form-group">
                                            <div>
                                                <div class="pre_made_content_board">
                                                    <div style="font-weight: bold; font-size: 18px; margin: 10px;"><?php echo $firstThreeWords ?></div>
                                                    <div style="font-size: 15px; margin: 10px;"><?php echo $remainingPart ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>


                        <div style="width: 95%;margin-left: 2.5%; display: flex;">
                            <input id="aiteamup_embed_message_input" class="aiteamup_embed_message_input" />
                            <button class="aiteamup_embed_message_send_btn"><img width="24" height="24" src="https://img.icons8.com/material-outlined/24/ffffff/filled-sent.png" alt="filled-sent" /></button>
                        </div>
                        <?php if ($brand_toggle == 1) { ?>
                            <div style="width: 100%; position: absolute; bottom : 0">
                                <a style=" display: flex; justify-content: center; font-size: 12px;" href="https://aiteamup.com" target="_blank">Powered by AITeamUP.</a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <!-- for embed chat--> 
                    <div class="embed_chat_wraper">

                        <div id="embed_chat_icon" style="z-index: 9999; position: fixed;" data-uai_font_letter_color=<?php echo $uai_font_letter_color ?> data-uai_board_color=<?php echo $uai_board_color ?> data-user_font_letter_color=<?php echo $user_font_letter_color ?> data-user_board_color=<?php echo $user_board_color ?> data-autoresponder=<?php echo $auto_responder ?> data-embed-id="<?php echo $embed_id ?>" data-embed-bot-image=<?php echo $bot_image ?> data-embed-user-image=<?php echo $user_image ?> data-embed-user-name=<?php echo $user_name ?>>
                            <img class="aiteamup_embed_chat_icon_wrapper <?php if ($embed_horizental == 'right') {
                                                                                echo 'aiteamup_embed_right_horizental' ?> 
                                                        <?php } else {
                                                                                echo 'aiteamup_embed_left_horizental' ?> <?php } ?>" src=" <?php echo $config['site_url'] . "storage/logo/" . $embed_icon ?>" ; width="100%" ; height="100%" ;>
                        </div>

                        <div style="position: absolute; margin-bottom: 90px; width: 400px !important; margin-left: none;" id="embed_chat_pre_board" class="aiteamup_embed_pre_chat_board d-none <?php if ($embed_horizental == 'right') {
                                                                                                                                                                                                    echo 'aiteamup_embed_right_horizental' ?> 
                                                        <?php } else {
                                                                                                                                                                                                    echo 'aiteamup_embed_left_horizental' ?> <?php } ?>">
                            <div class="aiteamup_embed_title"><a>👋 Hi, This is <?php echo $embed_name ?></a></div>
                            <div id="embed_chat_bot_profile" class="aiteamup_embed_bot_profile_board">
                                <div style="display: flex; width: auto; margin-left: 100px;">
                                    <img class="aiteamup_embed_bot_profile_avatar" src="<?php echo $bot_image ?>" ; width="100%" ; height="100%" ;>
                                    <div class="aiteamup_embed_bot_intro">
                                        <a class="aiteamup_embed_bot_name"><?php echo $bot_name ?></a>
                                        <!-- <a> <?php echo substr($bot_role, 0, 20) . "..." ?></a> -->
                                        <a> <?php echo $bot_role ?></a>
                                    </div>
                                </div>
                                <div id="new_chat_btn" class="new_chat_btn">
                                    <i class="icon-feather-refresh-ccw"></i>
                                    <a>New Chat</a>
                                </div>
                            </div>
                            <div id="embed_chat_pre_pan" class="aiteamup_embed_pre_chat_pan">
                                <div style="margin-left: 10%; width: 80%; margin-top: 30px;">
                                    <div style="margin-bottom: 20px;">
                                        <a style="font-size: 15px; font-weight: bold;">Enter the information below to start chatting</a>
                                    </div>
                                    <input id="aiteamup_embed_pre_name_input" type="text" class="aiteamup_embed_message_input" placeholder="First Name" style="width: 100% !important;" />
                                    <input id="aiteamup_embed_pre_email_input" type="email" class="aiteamup_embed_message_input" placeholder="Email address" style="width: 100% !important;" />
                                    <!-- <div>
                                        <div class="form-label"><?php _e("Upload your image"); ?></div>
                                        <div class="col-md-12 embed-image-wraper" id="upload-embed-icon">
                                            <label for="embed_pre_image" class="upload_img_wrapper show-select-uai-image" style="height: 100px; display: flex; justify-content: center; align-items: center; cursor: pointer;">
                                                <img id="pre-uploaded-image" src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px;">
                                            </label>
                                            <input name="embed_pre_image" id="embed_pre_image" type="file" class="with-border small-input" accept=".png, .jpg, .jpeg" style="display: none;" onchange="displaySelectedImage()">
                                        </div>
                                        </div> -->
                                    <div style="display: flex;">
                                        <input id="embed_chat_terms" style="width: 20px; height: 20px;" type="checkbox" />
                                        <span style="width: 20px;"></span>
                                        <div>I agree to the <a href="<?php echo $embed_terms ?>" target="_blank">terms and conditions</a> of interacting with this AI model.</div>
                                    </div>
                                    <div style="background-color: <?php echo $embed_start_chat_btn_background_color ?>;" class="embed_pre_start_chat_btn">
                                        <a><?php echo $embed_start_chat_text ?></a>
                                    </div>
                                </div>
                                <?php if ($brand_toggle == 1) { ?>
                                    <div style="width: 100%; position: absolute; bottom : 0">
                                        <a style=" display: flex; justify-content: center; font-size: 12px;" href="https://aiteamup.com" target="_blank">Powered by AITeamUP.</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div style="height: 10px;"></div>

                        <div id="embed_chat_board" class="aiteamup_embed_chat_board aiteamup_embed_chat_board_embed d-none <?php if ($embed_horizental == 'right') {
                                                                                                echo 'aiteamup_embed_right_horizental' ?> 
                                                        <?php } else {
                                                                                                echo 'aiteamup_embed_left_horizental' ?> <?php } ?>">
                            <div class="aiteamup_embed_title"><a>👋 Hi, This is <?php echo $embed_name ?></a></div>
                            
                            <div id="embed_chat_bot_profile" class="aiteamup_embed_bot_profile_board">
                                <div style="display: flex; width: auto; margin-left: 100px;">
                                    <img class="aiteamup_embed_bot_profile_avatar" src="<?php echo $bot_image ?>" ; width="100%" ; height="100%" ;>
                                    <div class="aiteamup_embed_bot_intro">
                                        <a class="aiteamup_embed_bot_name"><?php echo $bot_name ?></a>
                                        <a> <?php echo $bot_role ?></a>
                                    </div>
                                </div>
                                <div id="new_chat_btn" class="new_chat_btn">
                                    <i class="icon-feather-refresh-ccw"></i>
                                    <a>New Chat</a>
                                </div>
                            </div>

                            <div id="embed_chat_pan" class="aiteamup_embed_chat_pan aiteamup_embed_chat_pan_embed">
                                <div class="aiteamup_embed_chat_content_board">
                                    <img class="aiteamup_embed_chat_profile_avatar" src="<?php echo $bot_image ?>" ; width="100%" ; height="100%" ;>
                                    <div class="aiteamup_embed_chat_content" style="background-color: <?php echo $uai_board_color ?> !important;"><a class="aiteamup_embed_letter" style="color: <?php echo $uai_font_letter_color ?> !important;"><?php echo $welcome_message ?></a></div>
                                </div>
                            </div>
                            <div style="height: 10px;"></div>
                            <div class="pre_made_message_pan">
                                <div class="pre_made_message_board pre_made_message_board_embed">
                                    <?php foreach ($questions as $key => $question) {
                                        $words = explode(" ", $question);
                                        $firstThreeWords = implode(" ", array_slice($words, 0, 3));
                                        $remainingPart = implode(" ", array_slice($words, 3)); ?>
                                        <div class="pre_made_message_item" data-question="<?php echo $question; ?>">
                                            <div class="form-group">
                                                <div>
                                                    <div class="pre_made_content_board">
                                                        <div style="font-weight: bold; font-size: 18px; margin: 10px;"><?php echo $firstThreeWords ?></div>
                                                        <div style="font-size: 15px; margin: 10px;"><?php echo $remainingPart ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div style="width: 95%;margin-left: 2.5%; display: flex;">
                                <input id="aiteamup_embed_message_input" class="aiteamup_embed_message_input" />
                                <button class="aiteamup_embed_message_send_btn"><img width="24" height="24" src="https://img.icons8.com/material-outlined/24/ffffff/filled-sent.png" alt="filled-sent" /></button>
                            </div>
                            <?php if ($brand_toggle == 1) { ?>
                                <div style="width: 100%; position: absolute; bottom : 0">
                                    <a style=" display: flex; justify-content: center; font-size: 12px;" href="https://aiteamup.com" target="_blank">Powered by AITeamUP.</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>


                <script src="<?php echo $config['site_url'] . "templates/classic-theme/js/jquery.min.js" ?>"></script>
                <script src="<?php echo $config['site_url'] . "templates/classic-theme/js/snackbar.js" ?>"></script>
                <script src="<?php echo $config['site_url'] . "templates/classic-theme/js/custom.js" ?>"></script>

                <script>
                    // embed chat



                    // function displaySelectedImage() {
                    //     const input = document.getElementById("embed_pre_image");
                    //     const image = document.getElementById("pre-uploaded-image");

                    //     if (input.files && input.files[0]) {
                    //         const reader = new FileReader();

                    //         reader.onload = function(e) {
                    //             image.src = e.target.result;
                    //         };

                    //         reader.readAsDataURL(input.files[0]);
                    //     }
                    // }
                </script>
</body>

</html>
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

<div class="signup_board">
    <div class="signup_form_board">
        <a href="<?php url("INDEX") ?>">
            <img class="hompage-header-bar-main-icon" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['site_logo']; ?>">
        </a>
        <span style="height: 55px; display: block;"></span>

        <form method="post" id="register-account-form" action="#" accept-charset="UTF-8" onsubmit="e.preventDefault();document.getElementById('submit-btn').disabled = true;">

            <div class="signup_form_main_board">
                <span style="height: 15px;"></span>
                <div class="signup_form_main_board_title">Create Your Account</div>
                <span style="height: 15px;"></span>
                <div class="signup_form_main_board_title_1">
                    <a style="color: #383737; font-weight: 400;">Start Your </a>
                    <a style="color: #ff220a; font-weight: 700;">FREE</a>
                    <a>14 Day Trial Now!</a>
                </div>
                <span style="height: 15px;"></span>
                <div class="progress_bar">
                    <div class="progress_bar_completed">
                        <a style="color: white !important; margin-left: auto; margin-right: auto;">Almost Complete...</a>
                    </div>
                </div>
                <span style="height: 15px;"></span>
                <div>
                    <a>Already have an account? </a>
                    <a href="<?php echo $config['site_url'] . "login" ?>" style="color: rgb(255, 34, 10); font-weight: 700; font-size: 14px;">Log in!</a>
                </div>
                <span style="height: 15px;"></span>
                <div style="width: 80%;">
                    <div class="form-group">
                        <input type="text" placeholder="Full Name..." style="border: solid 1px #c1c1c1; height: 60px !important;" class="elInput elInput100 elAlign_left elInputBR5 elInputI0 elInputIBlack elInputIRight elInputStyle1 elInputBG2 elInputLarge required1 garlic-auto-save" data-type="extra" wtx-context="8D78AB19-E8D9-437F-8F51-7CD4BA97795A" data-listener-added_9241e53a="true" value="<?php _esc($name_field) ?>" id="name" name="name" onBlur="checkAvailabilityName()" required>
                        <span id="name-availability-status"><?php if ($name_error != "") {
                                                                _esc($name_error);
                                                            } ?></span>
                    </div>
                    <div class="form-group">

                        <input type="text" placeholder="Username " value="<?php _esc($username_field) ?>" id="Rusername" name="username" onBlur="checkAvailabilityUsername()" required style="border: solid 1px #c1c1c1; height: 60px !important;" name="custom_type" class="elInput elInput100 elAlign_left elInputBR5 elInputI0 elInputIBlack elInputIRight elInputStyle1 elInputBG2 elInputLarge required1 garlic-auto-save" data-type="extra" wtx-context="A450307E-737D-4399-9B91-2BF6B1C392AC" data-custom-type="Username ">
                        <span id="user-availability-status"><?php if ($username_error != "") {
                                                                _esc($username_error);
                                                            } ?></span>
                    </div>
                    <div class="form-group">

                        <input type="text" placeholder="Email Address" style="border: solid 1px #c1c1c1; height: 60px !important;" name="email" class="elInput elInput100 elAlign_left elInputBR5 elInputI0 elInputIBlack elInputIRight elInputStyle1 elInputBG2 elInputLarge required1 garlic-auto-save" data-type="extra" wtx-context="A450307E-737D-4399-9B91-2BF6B1C392AC" data-custom-type="" data-listener-added_9241e53a="true" value="<?php _esc($email_field) ?>" name="email" id="email" onBlur="checkAvailabilityEmail()" required>
                        <span id="email-availability-status"><?php if ($email_error != "") {
                                                                    _esc($email_error);
                                                                } ?></span>
                    </div>
                    <div class="form-group">

                        <input type="password" placeholder="Password" style="border: solid 1px #c1c1c1; height: 60px !important;" class="elInput elInput100 elAlign_left elInputBR5 elInputI0 elInputIBlack elInputIRight elInputStyle1 elInputBG2 elInputLarge required1 garlic-auto-save" data-type="extra" wtx-context="A450307E-737D-4399-9B91-2BF6B1C392AC" data-custom-type="Password" data-listener-added_9241e53a="true" id="Rpassword" name="password" onBlur="checkAvailabilityPassword()" required>
                        <span id="password-availability-status"><?php if ($password_error != "") {
                                                                    _esc($password_error);
                                                                } ?></span>
                    </div>
                    <div style="display: none !important;" class="checkbox">
                        <input type="checkbox" id="agree_for_term" name="agree_for_term" value="1" required checked>
                        <label for="agree_for_term"><span class="checkbox-icon"></span> <?php _e("By clicking on Register button you are agree to our") ?> <?php _e("Terms & Condition") ?></label>
                    </div>
                </div>
                <div style="width: 80%; color: #383737; font-family: sans-serif; font-weight: 400; font-size: 15px; color: #2f2f2f;">
                    <a>By Providing us with your information you are consenting to the collection and use of your information in accordance with our </a>
                    <a href="<?php echo $config['termcondition_link'] ?>">Terms of Service</a>
                    <a> and </a>
                    <a href="<?php echo $config['privacy_link'] ?>">Privacy Policy</a>
                </div>
                <span style="height: 15px;"></span>
                <input type="hidden" name="submit" value="submit">
                <button class="signup_form_main_board_continue_btn" id="submit-btn" type="submit">
                    <a style="margin: auto;">Continue <i class="fa fa-arrow-right"></i></a>
                </button>
                <span style="height: 15px;"></span>
                <div style="font-size: 12px; font-family: sans-serif; font-weight: 400; color: rgba(56, 55, 55, 0.498); width: 80%; display: flex; align-items: center;">
                    <a style="margin: auto;">* We Respect Your Privacy - We Will Not Sell, Rent Or Spam Your Email... *</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    var error = "";

    function checkAvailabilityName() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
            data: 'name=' + $("#name").val(),
            type: "POST",
            success: function(data) {
                if (data != "success") {
                    error = 1;
                    $("#name").removeClass('has-success');
                    $("#name-availability-status").html(data);
                    $("#name").addClass('has-error mar-zero');
                } else {
                    error = 0;
                    $("#name").removeClass('has-error mar-zero');
                    $("#name-availability-status").html("");
                    $("#name").addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }

    function checkAvailabilityUsername() {
        var $item = $("#Rusername").closest('.form-group');
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
            data: 'username=' + $("#Rusername").val(),
            type: "POST",
            success: function(data) {
                if (data != "success") {
                    error = 1;
                    $item.removeClass('has-success');
                    $("#user-availability-status").html(data);
                    $item.addClass('has-error');
                } else {
                    error = 0;
                    $item.removeClass('has-error');
                    $("#user-availability-status").html("");
                    $item.addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }

    function checkAvailabilityEmail() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                if (data != "success") {
                    error = 1;
                    $("#email").removeClass('has-success');
                    $("#email-availability-status").html(data);
                    $("#email").addClass('has-error mar-zero');
                } else {
                    error = 0;
                    $("#email").removeClass('has-error mar-zero');
                    $("#email-availability-status").html("");
                    $("#email").addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }

    function checkAvailabilityPassword() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
            data: 'password=' + $("#Rpassword").val(),
            type: "POST",
            success: function(data) {
                if (data != "success") {
                    error = 1;
                    $("#Rpassword").removeClass('has-success');
                    $("#password-availability-status").html(data);
                    $("#Rpassword").addClass('has-error mar-zero');
                } else {
                    error = 0;
                    $("#Rpassword").removeClass('has-error mar-zero');
                    $("#password-availability-status").html("");
                    $("#Rpassword").addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
</script>
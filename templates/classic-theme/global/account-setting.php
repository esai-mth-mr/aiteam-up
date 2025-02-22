<?php
overall_header(__("Account Setting"));

$theme_mode = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id'])->theme_color;
$user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);

$custom_instructions = ORM::for_table($config['db']['pre'] . 'custom_instructions')->where('user_id', $_SESSION['user']['id'])->find_one();

?>
<!-- Dashboard Container -->
<div class="dashboard-container">

    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>

    <!-- Dashboard Content
        ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3><?php _e("Account Setting") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("Account Setting") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-settings"></i> <?php _e("Account Setting") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e('Avatar'); ?></h5>
                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="images/*" id="avatar" name="avatar" />
                                                <label class="uploadButton-button ripple-effect" for="avatar"><?php _e('Upload Avatar') ?></label>
                                                <span class="uploadButton-file-name"><?php _e('Use 150x150px for better use') ?></span>
                                            </div>
                                            <?php if (!empty($avatar_error)) {
                                                _esc($avatar_error);
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Username") ?> *</h5>
                                            <div class="input-with-icon-left">
                                                <i class="la la-user"></i>
                                                <input type="text" class="with-border" id="username" name="username" value="<?php _esc($username) ?>" onBlur="checkAvailabilityUsername()">
                                            </div>
                                            <span id="user-availability-status"><?php if ($username_error != "") {
                                                                                    _esc($username_error);
                                                                                } ?></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Email Address") ?> *</h5>
                                            <div class="input-with-icon-left">
                                                <i class="la la-envelope"></i>
                                                <input type="text" class="with-border" id="email" name="email" value="<?php _esc($email_field) ?>" onBlur="checkAvailabilityEmail()">
                                            </div>
                                            <span id="email-availability-status"><?php if ($email_error != "") {
                                                                                        _esc($email_error);
                                                                                    } ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="submit-field">
                                            <h5><?php _e("New Password") ?></h5>
                                            <input type="password" id="password" name="password" class="with-border" onkeyup="checkAvailabilityPassword()">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="submit-field">
                                            <h5><?php _e("Confirm Password") ?></h5>
                                            <input type="password" id="re_password" name="re_password" class="with-border" onkeyup="checkRePassword()">
                                        </div>
                                    </div>
                                </div>
                                <span id="password-availability-status"><?php if ($password_error != "") {
                                                                            _esc($password_error);
                                                                        } ?></span>
                                <button type="submit" name="submit" class="button ripple-effect"><?php _e("Save Changes") ?></button>
                            </form>
                        </div>
                    </div>
                    <?php if (get_option('enable_tax_billing', 1)) { ?>
                        <div class="dashboard-box">
                            <div class="headline">
                                <h3><i class="icon-material-outline-description"></i> <?php _e("Billing Details") ?></h3>
                            </div>
                            <div class="content">
                                <div class="content with-padding">
                                    <div class="notification notice"><?php _e("These details will be used in invoice and payments.") ?></div>
                                    <?php if ($billing_error == "1") { ?>
                                        <div class="notification error"><?php _e("All fields are required.") ?></div>
                                    <?php } ?>
                                    <form method="post" accept-charset="UTF-8">
                                        <div class="submit-field">
                                            <h5><?php _e("Type") ?></h5>
                                            <select name="billing_details_type" id="billing_details_type" class="with-border selectpicker" required>
                                                <option value="personal" <?php if ($billing_details_type == "personal") {
                                                                                echo 'selected';
                                                                            } ?>><?php _e("Personal") ?></option>
                                                <option value="business" <?php if ($billing_details_type == "business") {
                                                                                echo 'selected';
                                                                            } ?>><?php _e("Business") ?></option>
                                            </select>
                                        </div>
                                        <div class="submit-field billing-tax-id">
                                            <h5>
                                                <?php
                                                if ($config['invoice_admin_tax_type'] != "") {
                                                    _esc($config['invoice_admin_tax_type']);
                                                } else {
                                                    _e("Tax ID");
                                                }
                                                ?>
                                            </h5>
                                            <input type="text" id="billing_tax_id" name="billing_tax_id" class="with-border" value="<?php _esc($billing_tax_id) ?>">
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Name") ?> *</h5>
                                            <input type="text" id="billing_name" name="billing_name" class="with-border" value="<?php _esc($billing_name) ?>" required>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Address") ?> *</h5>
                                            <input type="text" id="billing_address" name="billing_address" class="with-border" value="<?php _esc($billing_address) ?>" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="submit-field">
                                                    <h5><?php _e("City") ?> *</h5>
                                                    <input type="text" id="billing_city" name="billing_city" class="with-border" value="<?php _esc($billing_city) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="submit-field">
                                                    <h5><?php _e("State") ?> *</h5>
                                                    <input type="text" id="billing_state" name="billing_state" class="with-border" value="<?php _esc($billing_state) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="submit-field">
                                                    <h5><?php _e("Zip code") ?> *</h5>
                                                    <input type="text" id="billing_zipcode" name="billing_zipcode" class="with-border" value="<?php _esc($billing_zipcode) ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Country") ?> *</h5>
                                            <select name="billing_country" id="billing_country" class="with-border selectpicker" data-live-search="true" required>
                                                <?php
                                                foreach ($countries as $country) {
                                                ?>
                                                    <option value="<?php _esc($country['code']) ?>" <?php _esc($country['selected']) ?>><?php _esc($country['asciiname']) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="billing-submit" class="button ripple-effect"><?php _e("Save Changes") ?></button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-trash-2"></i> <?php _e("Delete Account") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <form method="post" accept-charset="UTF-8" onsubmit="return confirm('<?php echo escape(__('By deleting the account, all of your stored data will be deleted and you can not undo this action. Are you sure?')); ?>')">
                                <div class="notification error " style="opacity: <?php if($theme_mode === 'dark') { echo '0.5';?> <?php } ?>; color: <?php if($theme_mode === 'dark') { echo 'black';?> <?php } ?>; background-color: <?php if($theme_mode === 'light') { echo '#ffe9e9';?> <?php } else { echo '#26c0c0';?> <?php }?>"><?php _e('By deleting the account, all of your stored data will be deleted and you can not undo this action.'); ?></div>
                                <div class="submit-field">
                                    <h5><?php _e("Current Password") ?></h5>
                                    <input type="password" id="password" name="password" class="with-border" required>
                                    <?php if (!empty($delete_account_error)) {
                                        _esc($delete_account_error);
                                    } ?>
                                </div>
                                <button type="submit" name="delete-account" class="<?php if($theme_mode === 'white') { echo 'red'?> <?php } else { echo 'blue'?> <?php }?> button ripple-effect"><?php _e("Delete Account") ?></button>
                            </form>
                        </div>
                    </div>
                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-zap"></i> <?php _e("Cancel Subscription") ?></h3>
                        </div>
                        <div class="content with-padding">
                            <form method="post" accept-charset="UTF-8" onsubmit="return confirm('<?php echo escape(__('By canceling the account, you will never use your account again and you can not undo this action. Are you sure?')); ?>')">
                                <div class="submit-field">
                                    <h5><?php _e("Current Password") ?></h5>
                                    <input type="password" id="password" name="password" class="with-border" required>
                                    <?php if (!empty($delete_account_error)) {
                                        _esc($delete_account_error);
                                    } ?>
                                </div>
                                <button type="submit" name="cancel-subscription" class="<?php if($theme_mode === 'white') { echo 'red'?> <?php } else { echo 'blue'?> <?php }?> button ripple-effect"><?php _e("Cancel Subscription") ?></button>
                            </form>
                        </div>
                    </div>
                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-camera"></i> <?php _e("Appearence") ?></h3>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 30px;" class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class=""><?php _e('Customize how AI TeamUP looks on your device') ?></label>
                                        <select name="theme_mode" id="theme_mode" class="with-border selectpicker" data-live-search="true" required>
                                            <option value="light" <?php if ($theme_mode === 'light') echo 'selected'; ?>><?php _e("light") ?></option>
                                            <option value="dark" <?php if ($theme_mode === 'dark') echo 'selected'; ?>><?php _e("dark") ?></option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <button onclick=save_theme_color() class="button ripple-effect"><?php _e("Save Changes") ?></button>
                        </div>

                    </div>

                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-cloud-drizzle"></i> <?php _e("Brand Voice") ?></h3>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 30px;" class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class=""><?php _e('What would you like AITeamUP to know about you to provide better responses.') ?></label>
                                        <div class="content">
                                            <div class="content with-padding">
                                                <div class="submit-field">
                                                    <h5><?php _e("Where are you based?") ?></h5>
                                                    <input type="text" id="user_based" name="user_based" class="with-border" value="<?php _esc($custom_instructions['user_based']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("What do you do for work?") ?></h5>
                                                    <input type="text" id="user_do_for" name="user_do_for" class="with-border" value="<?php _esc($custom_instructions['user_do_for']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("What are your hobbies and interests?") ?></h5>
                                                    <input type="text" id="user_hobbies" name="user_hobbies" class="with-border" value="<?php _esc($custom_instructions['user_hobbies']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("What subjects can you talk about for hours?") ?></h5>
                                                    <input type="text" id="user_subjects" name="user_subjects" class="with-border" value="<?php _esc($custom_instructions['user_subjects']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("What are some goals you have?") ?></h5>
                                                    <input type="text" id="user_goals" name="user_goals" class="with-border" value="<?php _esc($custom_instructions['user_goals']) ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class=""><?php _e('How would you like AITeamUP to respond?') ?></label>
                                        <div class="content">
                                            <div class="content with-padding">
                                                <div class="submit-field">
                                                    <h5><?php _e("How formal or casual should AITeamUP be?") ?></h5>
                                                    <input type="text" id="res_formal" name="res_formal" class="with-border" value="<?php _esc($custom_instructions['res_formal']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("How long or short should responses generally be?") ?></h5>
                                                    <input type="text" id="res_long" name="res_long" class="with-border" value="<?php _esc($custom_instructions['res_long']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("How do you want be addressed?") ?></h5>
                                                    <input type="text" id="res_address" name="res_address" class="with-border" value="<?php _esc($custom_instructions['res_address']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("Should AITeamUP have opinions on topics or remain neutral?") ?></h5>
                                                    <input type="text" id="res_opinions" name="res_opinions" class="with-border" value="<?php _esc($custom_instructions['res_opinions']) ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <button onclick=save_custom_instruction() class="button ripple-effect"><?php _e("Save Changes") ?></button>
                        </div>
                    </div>

                    <!-- auto responder-->
                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-email"></i> <?php _e("Auto responder integration.") ?></h3>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 30px;" class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="content">
                                            <div class="content with-padding">
                                                <div class="submit-field">
                                                    <h5><?php _e("Mailchimp") ?></h5>
                                                    <input type="text" id="mailchimp_api_key" name="mailchimp_api_key" class="with-border" placeholder="mailchimp api key" value="<?php _esc($user['mailchimp_api_key']) ?>">
                                                    <input type="text" id="mailchimp_list_id" name="mailchimp_list_id" class="with-border" placeholder="mailchimp list id" value="<?php _esc($user['mailchimp_list_id']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("Active Campaign") ?></h5>
                                                    <input type="text" id="activecampaign_api_key" name="activecampaign_api_key" class="with-border" placeholder="Active Campaign api key" value="<?php _esc($user['activecampaign_api_key']) ?>">
                                                    <input type="text" id="activecampaign_list_id" name="activecampaign_list_id" class="with-border" placeholder="Active Campaign list id" value="<?php _esc($user['activecampaign_list_id']) ?>">
                                                    <input type="text" id="activecampaign_account_id" name="activecampaign_account_id" class="with-border" placeholder="Active Campaign account id" value="<?php _esc($user['activecampaign_account_id']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("SendLane") ?></h5>
                                                    <input type="text" id="sendlane_api_key" name="sendlane_api_key" class="with-border" placeholder="SendLane api key" value="<?php _esc($user['sendlane_api_key']) ?>">
                                                    <input type="text" id="sendlane_list_id" name="sendlane_list_id" class="with-border" placeholder="SendLane list id" value="<?php _esc($user['sendlane_list_id']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("GetResponse") ?></h5>
                                                    <input type="text" id="getresponse_api_key" name="getresponse_api_key" class="with-border" placeholder="GetResponse api key" value="<?php _esc($user['getresponse_api_key']) ?>">
                                                    <input type="text" id="getresponse_campaign_id" name="getresponse_campaign_id" class="with-border" placeholder="GetResponse campaign id" value="<?php _esc($user['getresponse_campaign_id']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("iContact") ?></h5>
                                                    <input type="text" id="icontact_api_username" name="icontact_api_username" class="with-border" placeholder="iContact api username" value="<?php _esc($user['icontact_api_username']) ?>">
                                                    <input type="text" id="icontact_api_password" name="icontact_api_password" class="with-border" placeholder="iContact api password" value="<?php _esc($user['icontact_api_password']) ?>">
                                                    <input type="text" id="icontact_account_id" name="icontact_account_id" class="with-border" placeholder="iContact account id" value="<?php _esc($user['icontact_account_id']) ?>">
                                                    <input type="text" id="icontact_list_id" name="icontact_list_id" class="with-border" placeholder="iContact list id" value="<?php _esc($user['icontact_list_id']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("ConstantContact") ?></h5>
                                                    <input type="text" id="constantcontact_api_key" name="constantcontact_api_key" class="with-border" placeholder="ConstantContact api key" value="<?php _esc($user['constantcontact_api_key']) ?>">
                                                    <input type="text" id="constantcontact_list_id" name="constantcontact_list_id" class="with-border" placeholder="ConstantContact list id" value="<?php _esc($user['constantcontact_list_id']) ?>">
                                                </div>
                                                <div class="submit-field">
                                                    <h5><?php _e("Drip") ?></h5>
                                                    <input type="text" id="drip_api_token" name="drip_api_token" class="with-border" placeholder="Drip api token" value="<?php _esc($user['drip_api_token']) ?>">
                                                    <input type="text" id="drip_account_id" name="drip_account_id" class="with-border" placeholder="Drip account id" value="<?php _esc($user['drip_account_id']) ?>">
                                                    <input type="text" id="drip_tag" name="drip_tag" class="with-border" placeholder="Drip tag" value="<?php _esc($user['drip_tag']) ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <button onclick=save_autoresponder() class="button ripple-effect"><?php _e("Save Changes") ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->
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
<script>
    var error = "";

    function checkAvailabilityUsername() {
        jQuery.ajax({
            url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
            data: 'username=' + $("#username").val(),
            type: "POST",
            success: function(data) {
                if (data != "success") {
                    error = 1;
                    $("#user-availability-status").html(data);
                } else {
                    error = 0;
                    $("#user-availability-status").html("");
                }
            },
            error: function() {}
        });
    }

    function checkAvailabilityEmail() {
        jQuery.ajax({
            url: "<?php _esc($config['app_url']) ?>global/check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                if (data != "success") {
                    error = 1;
                    $("#email-availability-status").html(data);
                } else {
                    error = 0;
                    $("#email-availability-status").html("");
                }
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }

    function checkAvailabilityPassword() {
        var length = $('#password').val().length;
        if (length != 0) {
            var PASSLENG = "<?php _e('Password must be between 4 and 20 characters long') ?>";
            if (length < 5 || length > 21) {
                $("#password-availability-status").html("<span class='status-not-available'>" + PASSLENG + "</span>");
            } else {
                $("#password-availability-status").html("");
            }
        }
    }

    function checkRePassword() {
        if ($('#password').val() != $('#re_password').val()) {
            var PASS = "<?php _e('The passwords you entered did not match') ?>";
            $("#password-availability-status").html("<span class='status-not-available'>" + PASS + "</span>");
        } else {
            $("#password-availability-status").html("");
        }
    }
    jQuery(window).on('load', function() {
        jQuery('#password').val("");
    });

    $('#billing_details_type').on('change', function() {
        if ($(this).val() == 'business')
            $('.billing-tax-id').slideDown();
        else
            $('.billing-tax-id').slideUp();
    }).trigger('change');
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';

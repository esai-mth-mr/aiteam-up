<?php
$user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
if ($user->plan_type != NULL) {
    overall_header(__("Membership Plan"));
}
?>
<?php if ($user->plan_type == NULL) { ?>
    <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/style.css">
<?php } ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<?php print_adsense_code('header_bottom'); ?>

<script>
    var session_uname = "<?php _esc($username) ?>";
    var session_uid = "<?php _esc($user_id) ?>";
    var session_img = "<?php _esc($userpic) ?>";
    // Language Var
    var LANG_ERROR_TRY_AGAIN = "<?php _e("Error: Please try again.") ?>";
    var LANG_LOGGED_IN_SUCCESS = "<?php _e("Logged in successfully. Redirecting...") ?>";
    var LANG_ERROR = "<?php _e("Error") ?>";
    var LANG_CANCEL = "<?php _e("Cancel") ?>";
    var LANG_DELETED = "<?php _e("Deleted") ?>";
    var LANG_ARE_YOU_SURE = "<?php _e("Are you sure?") ?>";
    var LANG_YOU_WANT_DELETE = "<?php _e("You want to delete this job") ?>";
    var LANG_YES_DELETE = "<?php _e("Yes, delete it") ?>";
    var LANG_PROJECT_DELETED = "<?php _e("Project has been deleted") ?>";
    var LANG_RESUME_DELETED = "<?php _e("Resume Deleted.") ?>";
    var LANG_EXPERIENCE_DELETED = "<?php _e("Experience Deleted.") ?>";
    var LANG_COMPANY_DELETED = "<?php _e("Company Deleted.") ?>";
    var LANG_SHOW = "<?php _e("Show") ?>";
    var LANG_HIDE = "<?php _e("Hide") ?>";
    var LANG_HIDDEN = "<?php _e("Hidden") ?>";
    var LANG_TYPE_A_MESSAGE = "<?php _e("Type a message") ?>";
    var LANG_ADD_FILES_TEXT = "<?php _e("Add files to the upload queue and click the start button.") ?>";
    var LANG_ENABLE_CHAT_YOURSELF = "<?php _e("Could not able to chat yourself.") ?>";
    var LANG_JUST_NOW = "<?php _e("Just now") ?>";
    var LANG_PREVIEW = "<?php _e("Preview") ?>";
    var LANG_SEND = "<?php _e("Send") ?>";
    var LANG_FILENAME = "<?php _e("Filename") ?>";
    var LANG_STATUS = "<?php _e("Status") ?>";
    var LANG_SIZE = "<?php _e("Size") ?>";
    var LANG_DRAG_FILES_HERE = "<?php _e("Drag files here") ?>";
    var LANG_STOP_UPLOAD = "<?php _e("Stop Upload") ?>";
    var LANG_ADD_FILES = "<?php _e("Add files") ?>";
    var LANG_CHATS = "<?php _e("Chats") ?>";
    var LANG_NO_MSG_FOUND = "<?php _e("No message found") ?>";
    var LANG_ONLINE = "<?php _e("Online") ?>";
    var LANG_OFFLINE = "<?php _e("Offline") ?>";
    var LANG_TYPING = "<?php _e("Typing...") ?>";
    var LANG_GOT_MESSAGE = "<?php _e("You got a message") ?>";
    var LANG_COPIED_SUCCESSFULLY = "<?php _e("Copied successfully.") ?>";
    var DEVELOPER_CREDIT = <?php _esc(get_option('developer_credit', 1)) ?>;
    var LIVE_CHAT = <?php _esc(get_option('enable_live_chat', 0)) ?>;

    if ($("body").hasClass("rtl")) {
        var rtl = true;
    } else {
        var rtl = false;
    }
</script>

<script src="<?php _esc(TEMPLATE_URL); ?>/js/chosen.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/tippy.all.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/simplebar.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/bootstrap-slider.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/bootstrap-select.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/snackbar.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/counterup.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/magnific-popup.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/slick.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/jquery.cookie.min.js?ver=<?php _esc($config['version']); ?>"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/user-ajax.js?ver=<?php _esc($config['version']); ?>"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/custom.js?ver=<?php _esc($config['version']); ?>"></script>

<?php if ($user->plan_type != NULL) { ?>

    <!-- Titlebar
    ================================================== -->
    <!-- <div id="titlebar" class="gradient">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php _e("Membership Plan") ?></h2>
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                            <li><?php _e("Membership Plan") ?></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Page Content
    ================================================== -->
<?php } ?>
<?php if ($user->plan_type == NULL) { ?>
    <div class="main-header-right container">
        <div class="main-header-left" style="margin-top: 90px; ">
            <div class="logo-wrapper"><a href="<?php echo $config['site_url'] . "logout" ?>"><img src="<?php echo $config['site_url'] . 'storage/logo/' . $config['site_logo'] ?>" alt="" height="42"></a></div>
        </div>
        <div class="mobile-sidebar">
            <div class="media-body text-right switch-sm">
                <label class="switch ml-3"><i class="font-primary icon-feather-align-center" id="sidebar-toggle"></i></label>
            </div>
        </div>
    <?php } ?>

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <section class="pricing-sec">
                    <div class="cus-container">
                        <div class="banner-text">
                            <?php if ($user->group_id != "free" && $user->plan_type != "free") { ?>
                                <div></div>

                            <?php } else { ?>
                                <h1 class="main-title">
                                    <a>Choose Your Plan And Start
                                        Your Free 14-Day Trial</a>
                                </h1>
                            <?php } ?>

                            <div class="headline-wrapper">
                                <h4 style="font-family: sans-serif !important; font-size: 20px; color: #2f2f2f !important;">Save up to <a style="font-weight: bold !important;">$3,468/year</a> with the <a style="font-weight: bold !important;">‘AI Maximizer’</a> plan <br>Our pricing is clear without the shenanigans.</h4>
                            </div>
                        </div>
                        <div class="btn-wrapper">
                            <div class="cus-row">
                                <div class="cus-col-3 mob-none"></div>
                                <?php if ($userData['group_id'] !== "free") { ?>
                                    <div class="cus-col-3">
                                        <div class="text-center">
                                            <a href="javascript:void(0)" data-action="annually" class="show_price_by_action btn <?php if (strtolower($userData['plan_type']) === 'annually') { ?> btnOne <?php } else { ?> btnTwo <?php } ?>"> Annual Discount </a>
                                        </div>
                                    </div>
                                    <div class="cus-col-3">
                                        <div class="text-center">
                                            <a href="javascript:void(0)" data-action="monthly" class="show_price_by_action btn <?php if (strtolower($userData['plan_type']) === 'monthly') { ?> btnOne <?php } else { ?> btnTwo <?php } ?>"> Monthly Plans </a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="cus-col-3">
                                        <div class="text-center">
                                            <a href="javascript:void(0)" data-action="annually" class="show_price_by_action btn btnTwo"> Annual Discount </a>
                                        </div>
                                    </div>
                                    <div class="cus-col-3">
                                        <div class="text-center">
                                            <a href="javascript:void(0)" data-action="monthly" class="show_price_by_action btn btnOne"> Monthly Plans </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($user->group_id != "free" && $user->plan_type != "free") { ?>
                                <div></div>
                            <?php } else { ?>
                                <div class="maximize_icon">
                                    <img style="width: 100%; height: 100%;" src="<?php echo $config['site_url'] . 'storage/logo/' . $config['pricingBrandLogo'] ?>">
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>

                <section class="pricing-wrapper">
                    <div class="cus-container">
                        <div class="cus-row">
                            <form name="form1" id="new_membership_form" method="post" action="<?php url("MEMBERSHIP") ?>">
                                <input type="hidden" name="billed-type" id="billed-type">
                                <input type="hidden" name="upgrade" id="upgrade">
                            </form>

                            <input type="hidden" name="user_plan_name" id="user_plan_name" value="<?php echo $userData['plan']['name']; ?>">
                            <input type="hidden" name="user_plan_type" id="user_plan_type" value="<?php echo $userData['plan_type']; ?>">

                            <?php

                            $x = 1;
                            if (!empty($userData['plan_type']) && ($userData['plan_type'] !== NULL)) {
                                unset($sub_types['free']);
                            }
                            foreach ($sub_types as $key => $plan) { ?>

                                <!-- MONTHLY MEMBERSHIP PLAN DIV START HERE -->
                                <div class="cus-col-4 monthly_membership_plan <?php if (($userData['group_id'] !== "free") && (strtolower($userData['plan_type']) !== 'monthly')) { ?> d-none <?php } ?>">
                                    <div class="pricing-box mb-30<?php if ($plan['recommended'] === 'yes') { ?> blue-box <?php } ?>">
                                        <div class="pricing-title text-center">
                                            <h2 class="semi-title"><?php echo $plan['title']; ?></h2>
                                        </div>
                                        <div class="pricing-panel-price text-center">
                                            <?php if ($plan['id'] == 1) { ?>
                                                <span class="base-price">normally $147/mo</span>
                                            <?php } else if ($plan['id'] == 2) { ?>
                                                <span class="base-price">normally $197/mo</span>

                                            <?php } else { ?>
                                                <span class="base-price">normally $497/mo</span>

                                            <?php } ?>
                                            <div class="discounted-price">
                                                <!-- <span>$</span> -->
                                                <?php if ($total_monthly > 0) ?>
                                                <h1 class="main-title monthly_price_number"><?php _esc($plan['monthly_price']) ?>/<?php _esc("Mo") ?></h1>
                                            </div>

                                            <div class="plan-time">
                                                <?php if ($plan['id'] == 1) { ?>
                                                    <p>Billed annually (Save$240/year)</p>
                                                <?php } else if ($plan['id'] == 2) { ?>
                                                    <p>Billed annually (Save$480/year)</p>
                                                <?php } else { ?>
                                                    <p>Billed annually (Save$3,468/year)</p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="free-trial">
                                            <div class="<?php if ($plan['recommended'] === 'yes') { ?> bounce <?php } ?>">
                                                <?php if ((!empty($userData['plan_type']) && ($userData['plan_type'] === "monthly")) && (!empty($userData['plan']) && ($userData['plan']['name'] == $plan['title']))) { ?>
                                                    <button type="button" class="btn btnOne text-center">Current Plan</button>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0)" class="btn btnTwo text-center" onclick="chooseMembershipPlan('monthly','<?= $key; ?>','<?= $plan['monthly_price_number']; ?>' )"> <?php if (checkloggedin()) { ?> Choose Plan <?php } else { ?> Start Free Trial <?php } ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="plan-feature-list">
                                            <ul>
                                                <li>
                                                    <i class="far fa-check-square"></i> <span> Full Access to <strong> Multiple AI Models </strong></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <span><strong><?php if ($plan['id'] == '1') {
                                                                        echo "500,000"; ?> <?php } else if ($plan['id'] == '2') {
                                                                                            echo "1,000,000"; ?> <?php } else {
                                                                                                                    echo "3,500,000";
                                                                                                                } ?></strong> Tokens</span>
                                                </li>
                                                <?php if ($config['enable_ai_images']) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i>
                                                        <span><strong><?php if ($plan['id'] == '1') {
                                                                            echo "1000"; ?> <?php } else if ($plan['id'] == '2') {
                                                                                            echo "1500"; ?> <?php } else {
                                                                                                            echo "5000";
                                                                                                        } ?></strong> AI Image Tokens <strong>(Visualize, Create, Inspire)</strong></span>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <span> ​Access to <strong><?php _esc(is_string($plan['ai_voices_limit']) ? $plan['ai_voices_limit'] : number_format($plan['ai_voices_limit'])) ?>+ AI Voices</strong></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <span><strong>25</strong> + Languages <strong>(Unlock Global Market Potential)</strong></span>
                                                </li>
                                                <li>
                                                    <?php if ($plan['title'] === 'AI Pro') { ?>
                                                        <i class="far fa-check-square"></i> <span><strong> 1000 mins </strong> of AI Voice Generation </span>
                                                    <?php } else if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i> <span><strong> 5000 mins </strong> of AI Voice Generation </span>
                                                    <?php } else { ?>
                                                        <i class="far fa-check-square"></i> <span><strong> 500 mins </strong> of AI Voice Generation </span>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong>Unlimited</strong><span> <?php _e(" PDF & Audio Downloads") ?> </span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i><?php _e(" Custom BrandVoice") ?> <strong>(Echoing Your Brand's Tone)</strong> <span></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i></span><?php _e(" ​Analytics & Insights") ?>
                                                </li>
                                                <?php if ($config['enable_ai_chat']) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i>
                                                        <!-- <strong><?php //_esc(count($plan['ai_chatbots'])) 
                                                                        ?> AI</strong> <?php _e("Power Team") ?> -->
                                                        <?php if ($plan['title'] === 'AI Pro') { ?>
                                                            <span><strong>19 AI</strong> <?php _e("Based Employess") ?><strong>(Highly Trained)</strong></span>
                                                        <?php } else if ($plan['title'] === 'AI Maximizer') { ?>
                                                            <span><strong>36 AI</strong> <?php _e("Based Employess") ?><strong>(Highly Trained)</strong></span>
                                                        <?php } else { ?>
                                                            <span><strong>10 AI</strong> <?php _e("Based Employess") ?><strong>(Highly Trained)</strong></span>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>
                                                <?php if (get_option("enable_ai_templates", 1)) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i> <span> Instant Basic Templates</span> <?php if ($plan['title'] === 'AI Maximizer') { ?><span>+ Pro</span><?php } ?> <strong>(Effortless Start & Create)</strong>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong>Automated</strong> <span> Social Media Posting & Scheduling</span> <strong>(Coming Soon)</strong>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <strong><?php _esc(is_string($plan['ai_uai_agent_limit']) ? $plan['ai_uai_agent_limit'] : number_format($plan['ai_uai_agent_limit'])) ?> <?php _e("UAi Custom Lead Generation") ?></strong>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong>Email integrations</strong> <span> (Autoresponders)</span>
                                                </li>
                                                <?php if ($config['enable_speech_to_text']) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i> <span><?php _e(" AI Speech to Text") ?></span><strong> (Turn Audio Into Content)</strong>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <i class="far fa-check-square"></i> <span><?php _e(" AI Ployees Marketplace ") ?> <strong> (Coming Soon) </strong></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong> World Class </strong></span> <span><?php _e("Training Academy ") ?>
                                                </li>

                                                <li>
                                                    <i class="far fa-check-square"></i> <?php if ($plan['title'] === 'AI Maximizer') { ?> <strong>Priority</strong> <?php } else { ?> <span>Standard</span> <?php } ?> <strong> Support </strong>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><strong>​Live Chat Support</strong>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><span>​​AI Legends</span> <strong>​(Elite Expertise Mind Hack)</strong>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><strong>Priority</strong><span> Template Requests</span>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><strong>Personalized </strong><span>Onboarding</span>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><span>Early Access to </span> <strong>"NEW FEATURES" </strong>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="free-trial">
                                            <div class="<?php if ($plan['recommended'] === 'yes') { ?> bounce <?php } ?>">
                                                <?php if ((!empty($userData['plan_type']) && ($userData['plan_type'] == 'monthly')) && (!empty($userData['plan']) && ($userData['plan']['name'] == $plan['title']))) { ?>
                                                    <button type="button" class="btn btnOne text-center">Current Plan</button>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0)" class="btn btnTwo text-center" onclick="chooseMembershipPlan('monthly','<?= $key; ?>','<?= $plan['monthly_price_number']; ?>' )"><?php if (checkloggedin()) { ?> Choose Plan <?php } else { ?> Start Free Trial <?php } ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- MONTHLY MEMBERSHIP PLAN DIV END HERE --->


                                <!-- ANNUALLY MEMBERSHIP PLAN DIV START HERE -->
                                <div class="cus-col-4 annually_membership_plan <?php if (($userData['group_id'] === "free") || (strtolower($userData['plan_type']) !== 'annually')) { ?> d-none <?php } ?>">
                                    <div class="pricing-box mb-30<?php if ($plan['recommended'] === 'yes') { ?> blue-box <?php } ?>">
                                        <div class="pricing-title text-center">
                                            <h2 class="semi-title"><?php echo $plan['title']; ?></h2>
                                        </div>
                                        <div class="pricing-panel-price text-center">
                                            <?php if ($plan['id'] == 1) { ?>
                                                <span class="base-price">normally $147/mo</span>
                                            <?php } else if ($plan['id'] == 2) { ?>
                                                <span class="base-price">normally $197/mo</span>

                                            <?php } else { ?>
                                                <span class="base-price">normally $497/mo</span>

                                            <?php } ?>
                                            <div class="discounted-price">
                                                <?php if ($total_annual > 0) ?>
                                                <h1 class="main-title annual_price_number"><?php _esc($plan['annual_price']) ?>/<?php _esc("Mo") ?></h1>
                                            </div>
                                            <div class="plan-time">
                                                <?php if ($plan['id'] == 1) { ?>
                                                    <p>Billed annually (Save$240/year)</p>
                                                <?php } else if ($plan['id'] == 2) { ?>
                                                    <p>Billed annually (Save$480/year)</p>
                                                <?php } else { ?>
                                                    <p style="color: rgb(255, 34, 10) !important;">Billed annually (Save$3,468/year)</p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="free-trial">
                                            <div class="<?php if ($plan['recommended'] === 'yes') { ?> bounce <?php } ?>">

                                                <?php if ((!empty($userData['plan_type']) && ($userData['plan_type'] === 'annually')) && (!empty($userData['plan']) && ($userData['plan']['name'] == $plan['title']))) { ?>
                                                    <button type="button" class="btn btnOne text-center">Current Plan</button>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0)" class="btn btnTwo text-center" onclick="chooseMembershipPlan('annually','<?= $key; ?>','<?= $plan['annual_price_number']; ?>' )"><?php if (checkloggedin()) { ?> Choose Plan <?php } else { ?> Start Free Trial <?php } ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="plan-feature-list">
                                            <ul>
                                                <li>
                                                    <i class="far fa-check-square"></i> <span> Full Access to <strong> Multiple AI Models </strong></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <span><strong><?php if ($plan['id'] == '1') {
                                                                        echo "500,000"; ?> <?php } else if ($plan['id'] == '2') {
                                                                                            echo "1,000,000"; ?> <?php } else {
                                                                                                                    echo "3,500,000";
                                                                                                                } ?></strong> Tokens</span>
                                                </li>
                                                <?php if ($config['enable_ai_images']) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i>
                                                        <span><strong><?php if ($plan['id'] == '1') {
                                                                            echo "1000"; ?> <?php } else if ($plan['id'] == '2') {
                                                                                            echo "1500"; ?> <?php } else {
                                                                                                            echo "5000";
                                                                                                        } ?></strong> AI Image Tokens <strong>(Visualize, Create, Inspire)</strong></span>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <span> ​Access to <strong><?php _esc(is_string($plan['ai_voices_limit']) ? $plan['ai_voices_limit'] : number_format($plan['ai_voices_limit'])) ?>+ AI Voices</strong></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <span><strong>25</strong> + Languages <strong>(Unlock Global Market Potential)</strong></span>
                                                </li>
                                                <li>
                                                    <?php if ($plan['title'] === 'AI Pro') { ?>
                                                        <i class="far fa-check-square"></i> <span><strong> 1000 mins </strong> of AI Voice Generation </span>
                                                    <?php } else if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i> <span><strong> 5000 mins </strong> of AI Voice Generation </span>
                                                    <?php } else { ?>
                                                        <i class="far fa-check-square"></i> <span><strong> 500 mins </strong> of AI Voice Generation </span>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i><strong>Unlimited</strong> <span> <?php _e(" PDF & Audio Downloads") ?> </span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i><?php _e(" Custom BrandVoice") ?> <strong>(Echoing Your Brand's Tone)</strong> <span></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i></span><?php _e(" ​Analytics & Insights") ?>
                                                </li>
                                                <?php if ($config['enable_ai_chat']) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i>
                                                        <?php if ($plan['title'] === 'AI Pro') { ?>
                                                            <span><strong>19 AI</strong> <?php _e("Based Employess") ?><strong>(Highly Trained)</strong></span>
                                                        <?php } else if ($plan['title'] === 'AI Maximizer') { ?>
                                                            <span><strong>36 AI</strong> <?php _e("Based Employess") ?><strong>(Highly Trained)</strong></span>
                                                        <?php } else { ?>
                                                            <span><strong>10 AI</strong> <?php _e("Based Employess") ?><strong>(Highly Trained)</strong></span>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>
                                                <?php if (get_option("enable_ai_templates", 1)) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i> <span> Instant Basic Templates</span> <?php if ($plan['title'] === 'AI Maximizer') { ?><span>+ Pro</span><?php } ?><strong>(Effortless Start & Create)</strong>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong>Automated</strong> <span> Social Media Posting & Scheduling</span> <strong>(Coming Soon)</strong>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i>
                                                    <strong><?php _esc(is_string($plan['ai_uai_agent_limit']) ? $plan['ai_uai_agent_limit'] : number_format($plan['ai_uai_agent_limit'])) ?> <?php _e("UAi Custom") ?></strong><span> Lead Generation</span><strong><?php if ($plan['title'] === 'AI Maximizer') { ?> (White-label) <?php } else { ?> (Watermark) <?php } ?> </strong>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong>Email integrations</strong> <span> (Autoresponders)</span>
                                                </li>
                                                <?php if ($config['enable_speech_to_text']) { ?>
                                                    <li>
                                                        <i class="far fa-check-square"></i> <span><?php _e(" AI Speech to Text") ?></span><strong> (Turn Audio Into Content)</strong>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <i class="far fa-check-square"></i> <span><?php _e(" AI Ployees Marketplace ") ?> <strong> (Coming Soon) </strong></span>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i> <strong> World Class </strong></span> <span><?php _e("Training Academy ") ?>
                                                </li>
                                                <li>
                                                    <i class="far fa-check-square"></i> <?php if ($plan['title'] === 'AI Maximizer') { ?> <strong>Priority</strong> <?php } else { ?> <span>Standard</span> <?php } ?> <strong> Support </strong>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><strong>​Live Chat Support</strong>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><span>​​AI Legends</span> <strong>​(Elite Expertise Mind Hack)</strong>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><strong>Priority</strong><span> Template Requests</span>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><strong>Personalized </strong><span>Onboarding</span>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                                <li>
                                                    <?php if ($plan['title'] === 'AI Maximizer') { ?>
                                                        <i class="far fa-check-square"></i><span>Early Access to </span> <strong>"NEW FEATURES" </strong>
                                                    <?php } else { ?>
                                                        <i class="fa-fw far fa-times-circle"></i><strong>-</strong>
                                                    <?php } ?>
                                                </li>

                                            </ul>
                                        </div>

                                        <div class="free-trial">
                                            <div class="<?php if ($plan['recommended'] === 'yes') { ?> bounce <?php } ?>">
                                                <?php if ((!empty($userData['plan_type']) && ($userData['plan_type'] == 'annually')) && (!empty($userData['plan']) && ($userData['plan']['name'] == $plan['title']))) { ?>
                                                    <button type="button" class="btn btnOne text-center">Current Plan</button>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0)" class="btn btnTwo text-center" onclick="chooseMembershipPlan('annually','<?= $key; ?>','<?= $plan['annual_price_number']; ?>' )"><?php if (checkloggedin()) { ?> Choose Plan <?php } else { ?> Start Free Trial <?php } ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- ANNUALLY MEMBERSHIP PLAN DIV END HERE --->

                            <?php } ?>
                        </div>

                        <a style="margin: auto; display: table;">Features labeled as ”Beta” don’t represent final functionality, and may be restricted to a higher plan in the future.</a>
                        <a style="margin: auto; display: table; font-family: sans-serif; font-size: 38px; line-height: 49px; font-weight: 700; color: #2f2f2f;">Seeking a custom solution for your unique content needs?</a>
                        <span style="height: 30px; display: block;"></span>
                        <a style="margin: auto; display: table; font-family: sans-serif; font-size: 23px; font-weight: 500; color: #2f2f2f; text-align: center;">Discover AI TeamUP's expertly crafted tools and fully managed service. We combine conversion expertise with our powerful technology to deliver personalized, scalable content creation just for you.</a>

                        <div style="margin-top: 30px; outline: none; cursor: pointer; width: 100%; background-color: rgb(32, 22, 82); padding-top: 20px; padding-bottom: 20px; display: flex;">
                            <a style="margin: auto; color: rgb(255, 255, 255); font-weight: 600; background-color: rgb(32, 22, 82); font-size: 20px;" href="https://calendly.com/aiteamup/30min" target="_blank">
                                <span class="elButtonMain"><i class="fa_prepended fas fa-angle-double-right" contenteditable="false"></i> Go Custom <i class="fa_appended fas fa-angle-double-right" contenteditable="false"></i></span>
                                <span class="elButtonSub"></span>
                            </a>
                        </div>
                        <span style="height: 30px; display: block;"></span>

                        <?php if ($user->group_id != "free" && $user->plan_type != "free") { ?>
                            <div></div>
                        <?php } else { ?>
                            <a style="margin: auto; display: table; font-family: sans-serif; font-size: 32px; line-height: 42px; font-weight: 700; color: #2f2f2f;">Frequently Asked Questions</a>

                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_1" class="home_feature_7_faq" onclick="toggleFaq('h_faq_1_content')">
                                <a>What can I unlock with the 14-day free trial?</a>
                            </div>
                            <div id="h_faq_1_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>Step into the future of content creation with AI TeamUP's 14-day free trial, where every feature is at your fingertips. This isn't just a sneak peek; it's a full-power experience! You'll turbocharge your content strategy, crafting quality material with unprecedented speed. Imagine slashing your content budget while multiplying output — that's what we offer. We're so confident in our platform's value, we give you the keys to the kingdom: risk-free. By the end of the trial, the results will speak for themselves, making the decision to continue with us a no-brainer.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_2" class="home_feature_7_faq" onclick="toggleFaq('h_faq_2_content')">
                                <a>Why do I have to put in my credit card details for a free trial?</a>
                            </div>
                            <div id="h_faq_2_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>We understand that providing credit card details can be a hassle. The reason we ask is to ensure seamless service and to verify the authenticity of users to protect our community. Your card won't be charged during the free trial—it's just to prepare for a smooth transition should you choose to continue creating amazing content with AI TeamUP after the trial. We want your experience and business to flourish without interruption, ensuring you maintain momentum in your content strategy.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_3" class="home_feature_7_faq" onclick="toggleFaq('h_faq_3_content')">
                                <a>Can I change my plan after I sign up?</a>
                            </div>
                            <div id="h_faq_3_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>Absolutely! You can upgrade or downgrade your plan at any time directly from your dashboard. Your changes will be effective immediately.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_4" class="home_feature_7_faq" onclick="toggleFaq('h_faq_4_content')">
                                <a>Is it easy to cancel if I'm not satisfied?</a>
                            </div>
                            <div id="h_faq_4_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>Yes, it's as simple as can be. We believe in the freedom to choose — no outdated contracts here. With AI TeamUP, you have the flexibility to cancel your trial at any time right from your dashboard, just a few clicks away, or reach out to us directly at Support@aiteamup.com. We're committed to earning your satisfaction with excellence, not locking you in. If you decide we're not the right fit, parting ways is hassle-free. After all, we're here to make your life easier, not to hold you back!</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_5" class="home_feature_7_faq" onclick="toggleFaq('h_faq_5_content')">
                                <a>How secure is my data with AI TeamUP?</a>
                            </div>
                            <div id="h_faq_5_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>We take your privacy and data security seriously. Our platform uses state-of-the-art security measures to ensure your data is protected at all times.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_6" class="home_feature_7_faq" onclick="toggleFaq('h_faq_6_content')">
                                <a>How does AI TeamUP enhance my existing content strategy?</a>
                            </div>
                            <div id="h_faq_6_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>AI TeamUP is engineered to amplify your content strategy by over 10X, accelerating your content creation process while maintaining high quality. Our AI-powered platform integrates smoothly with your existing workflow, supercharging your content production to save you time and reduce costs. Whether it's drafting, editing, or publishing, AI TeamUP streamlines these tasks, freeing you up to focus on strategy and growth. Get ready to create more content, faster and smarter, without the usual hassle or expense.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_7" class="home_feature_7_faq" onclick="toggleFaq('h_faq_7_content')">
                                <a>What does 'AI Based Employees' mean?</a>
                            </div>
                            <div id="h_faq_7_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>This refers to our advanced AI tools designed to emulate the skills of top-performing employees, enhancing your content creation with 'highly trained' efficiency.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_8" class="home_feature_7_faq" onclick="toggleFaq('h_faq_8_content')">
                                <a>What kind of support can I expect?</a>
                            </div>
                            <div id="h_faq_8_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>With every plan, you'll gain access to our comprehensive support center, tutorials, and training resources to help you get the most out of AI TeamUP. You can easily create support tickets right from your account for any inquiries. For AI Maximizer members, we offer priority support, ensuring your tickets are moved to the front of the queue, and provide live chat services for instant help when you need it.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_9" class="home_feature_7_faq" onclick="toggleFaq('h_faq_9_content')">
                                <a>How does the 'AI Legends' feature work?</a>
                            </div>
                            <div id="h_faq_9_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>With AI Legends, you gain access to our most sophisticated AI, modeled after the minds of industry-leading marketers and business experts, available exclusively in the AI Maximizer plan.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_10" class="home_feature_7_faq" onclick="toggleFaq('h_faq_10_content')">
                                <a>What does 'Priority Template Requests' entail?</a>
                            </div>
                            <div id="h_faq_10_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>This feature allows AI Maximizer subscribers to fast-track their requests for specific content templates, ensuring your needs are prioritized and met swiftly.</a>
                            </div>
                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_11" class="home_feature_7_faq" onclick="toggleFaq('h_faq_11_content')">
                                <a>How does the 'AI Ployees Marketplace' work?</a>
                            </div>
                            <div id="h_faq_11_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>The AI Ployees Marketplace is a dynamic platform where creators can both contribute and utilize custom AI employees. Leveraging our innovative 'UAi Lead Generation' feature within the dashboard, users can design specialized AI bots tailored to specific tasks. These creations can then be listed on the marketplace for sale or shared freely. It's not just about enhancing productivity; it's also an opportunity for creators to earn revenue and make a positive impact by providing valuable tools to others in the AI TeamUP community.</a>
                            </div>

                            <span style="display: block; height: 15px;"></span>
                            <div id="h_faq_12" class="home_feature_7_faq" onclick="toggleFaq('h_faq_12_content')">
                                <a>What Exactly Are 'Tokens' on AI TeamUP and How Do I Use Them?</a>
                            </div>
                            <div id="h_faq_12_content" class="home_feature_7_faq_content">
                                <span style="display: block; height: 10px;"></span>
                                <a>Tokens on our platform are essentially digital credits that allow you to access various AI-driven features we offer. You can think of them as 'AI currency' - each time you use one of our services, such as generating an image or creating content, a certain number of tokens are used. The number of tokens required depends on the complexity or scope of your request. We offer different package plans, each with a varying allotment of tokens to suit different needs and scales of use. Our AI Maximizer plan, for instance, provides the highest number of tokens, catering to users who require extensive use of our AI services. This plan is ideal for those who need a robust amount of AI interactions, whether for large-scale content creation, detailed image generation, or other intensive AI tasks. Each plan is designed to give you the flexibility and resources to make the most out of our AI capabilities for your projects or business needs.</a>
                            </div>

                            <span style="display: block; height: 80px;"></span>

                            <div class="bottom-border">
                                <span class="line-seperator"></span>
                            </div>
                        <?php } ?>

                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="margin-top-80"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var showMoreBtn = document.getElementById('showMoreBtn');
            var showMoreBtnText = document.querySelector('.home_feature_4_show_more_btn_text');
            var showMoreBtnIcon = document.querySelector('.fa-angle-double-down');
            var hiddenBots = document.querySelectorAll('.home_feature_4_bot.hidden');

            showMoreBtn.addEventListener('click', function() {
                hiddenBots.forEach(function(bot) {
                    bot.classList.toggle('hidden');
                });

                // Toggle the icon class based on the current state
                if (showMoreBtnText.innerText === 'Show More') {
                    showMoreBtnText.innerText = 'Show Less';
                    showMoreBtnIcon.classList.replace('fa-angle-double-down', 'fa-angle-double-up');
                } else {
                    showMoreBtnText.innerText = 'Show More';
                    showMoreBtnIcon.classList.replace('fa-angle-double-up', 'fa-angle-double-down');
                }
            });
        });

        function toggleFaq(contentId) {
            var content = document.getElementById(contentId);
            if (content.style.maxHeight === '' || content.style.maxHeight === '0px') {
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                content.style.maxHeight = '0';
            }
        }
    </script>

    <?php if ($user->plan_type == NULL) { ?>
        <script src="<?php echo $config['site_url'] . "templates/classic-theme/js/jquery.min.js" ?>"></script>
        <script src="<?php echo $config['site_url'] . "templates/classic-theme/js/snackbar.js" ?>"></script>
        <script src="<?php echo $config['site_url'] . "templates/classic-theme/js/custom.js" ?>"></script>
    <?php } ?>

    <?php
    if ($user->plan_type != NULL) {
        // overall_footer();
    }
    ?>
    <?php if ($user->plan_type == NULL) { ?>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 footer-copyright">
                        <p class="mb-0"><?php _e('Copyright') ?> © <?php _esc(date('Y'))  ?> <a href="https://aiteamup.com" target="_blank">AITeamUp</a>. <?php _e('All rights reserved.') ?> </p>
                    </div>
                    <div class="col-md-6">
                        <p class="float-right mb-0"><?php _e('Hand-crafted & made with') ?> <i class="icon-feather-heart"></i></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
<?php } ?>
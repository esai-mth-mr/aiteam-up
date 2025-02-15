<!DOCTYPE html>
<html lang="<?php _esc($config['lang_code']); ?>" dir="<?php _esc($lang_direction); ?>">

<head>

    <title><?php _esc($page_title); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="author" content="<?php _esc($config['site_title']); ?>">
    <meta name="keywords" content="<?php _esc($config['meta_keywords']); ?>">
    <meta name="description" content="<?php ($meta_desc == '') ? _esc($config['meta_description']) : _esc($meta_desc); ?>">


    <!-- SEO Meta Tags -->
    <meta name="description" content="Discover AI TeamUP, the leading AI platform for transforming digital marketing and content creation. Elevate engagement, drive growth, and innovate with AI.">
    <meta name="keywords" content="AI TeamUP, AI technology for marketing, Content creation AI, Social media automation, AI digital marketing, AI content management, AI for business growth, AI-driven marketing strategies, Targeted marketing, AI Audience engagement AI">
    <meta name="author" content="Your Name or Company Name">
    <meta name="robots" content="index, follow"> <!-- To allow search engine indexing -->

    <!-- Open Graph Meta Tags (for sharing on social media) -->
    <meta property="og:title" content="AI TeamUP - Revolutionizing Business Productivity | AI-Powered Solutions">
    <meta property="og:description" content="Discover AI TeamUP, the leading AI platform for transforming digital marketing and content creation. Elevate engagement, drive growth, and innovate with AI.">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo $config['site_url'] . "storage/logo/" . $config['site_favicon'] ?>" type="image/x-icon">

    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//google.com">
    <link rel="dns-prefetch" href="//apis.google.com">
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
    <link rel="dns-prefetch" href="//gstatic.com">
    <link rel="dns-prefetch" href="//oss.maxcdn.com">

    <meta property="fb:app_id" content="<?php _esc($config['facebook_app_id']); ?>" />
    <meta property="og:site_name" content="<?php _esc($config['site_title']); ?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:url" content="<?php _esc($page_link); ?>" />
    <meta property="og:title" content="<?php _esc($page_title); ?>" />
    <meta property="og:description" content="<?php _esc($meta_desc); ?>" />
    <meta property="og:type" content="<?php _esc($meta_content); ?>" />

    <?php if ($meta_content == 'article') { ?>
        <meta property="article:author" content="#" />
        <meta property="article:publisher" content="#" />
        <meta property="og:image" content="<?php _esc($meta_image); ?>" />
    <?php
    }
    if ($meta_content == 'website') {
        echo '<meta property="og:image" content="' . $meta_image . '"/>';
    }

    $theme_mode = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id'])->theme_color;
    $group_id = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id'])->group_id;

    function getComplementaryColor($colorHex)
    {
        // Remove the '#' symbol if present
        $colorHex = ltrim($colorHex, '#');

        // Convert the hexadecimal color to its RGB components
        $red = hexdec(substr($colorHex, 0, 2));
        $green = hexdec(substr($colorHex, 2, 2));
        $blue = hexdec(substr($colorHex, 4, 2));

        // Calculate the complementary color by subtracting each component from 255
        $complementaryRed = 255 - $red;
        $complementaryGreen = 255 - $green;
        $complementaryBlue = 255 - $blue;

        // Convert the complementary RGB components back to hexadecimal
        $complementaryColorHex = sprintf("#%02X%02X%02X", $complementaryRed, $complementaryGreen, $complementaryBlue);

        return $complementaryColorHex;
    }

    $languageFlags = array(
        'ar' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/1280px-Flag_of_Saudi_Arabia.svg.png', // Arabic
        'en' => 'https://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/1280px-Flag_of_the_United_States.svg.png', // US
        // 'en' => 'https://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/1280px-Flag_of_the_United_Kingdom.svg.png', // English
        'fr' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Flag_of_France.svg/1280px-Flag_of_France.svg.png', // French
        'sv' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Sweden.svg/1280px-Flag_of_Sweden.svg.png', // Swedish
        'dk' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Denmark.svg/1280px-Flag_of_Denmark.svg.png', // Danish
        'it' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Flag_of_Italy.svg/1280px-Flag_of_Italy.svg.png', // Italian
        'de' => 'https://upload.wikimedia.org/wikipedia/en/thumb/b/ba/Flag_of_Germany.svg/1280px-Flag_of_Germany.svg.png', // German
        'es' => 'https://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Flag_of_Spain.svg/1280px-Flag_of_Spain.svg.png', // Spanish
        'pl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Flag_of_Poland.svg/1280px-Flag_of_Poland.svg.png', // Polish
        'pt' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/1280px-Flag_of_Portugal.svg.png', // Portuguese
        'ja' => 'https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/1280px-Flag_of_Japan.svg.png', // Japanese
        'zh' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/1280px-Flag_of_the_People%27s_Republic_of_China.svg.png', // Chinese
        'he' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Israel.svg/1280px-Flag_of_Israel.svg.png', // Hebrew
        'ru' => 'https://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/1280px-Flag_of_Russia.svg.png', // Russian
        'bn' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Flag_of_Bangladesh.svg/1280px-Flag_of_Bangladesh.svg.png', // Bengali
        'cz' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/1280px-Flag_of_the_Czech_Republic.svg.png', // Czech
        'hi' => 'https://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/1280px-Flag_of_India.svg.png', // Hindi
        'bg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Bulgaria.svg/1280px-Flag_of_Bulgaria.svg.png', // Bulgarian
        'ro' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Romania.svg/1280px-Flag_of_Romania.svg.png', // Romanian
        'th' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Flag_of_Thailand.svg/1280px-Flag_of_Thailand.svg.png', // Thai
        'tr' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Flag_of_Turkey.svg/1280px-Flag_of_Turkey.svg.png', // Turkish
        'ur' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Flag_of_Pakistan.svg/1280px-Flag_of_Pakistan.svg.png', // Urdu
        'vi' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/1280px-Flag_of_Vietnam.svg.png', // Vietnamese
        'fi' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Finland.svg/1280px-Flag_of_Finland.svg.png', // Finnish
        'gr' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Greece.svg/1280px-Flag_of_Greece.svg.png', // Greek
        'hu' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Flag_of_Hungary.svg/1280px-Flag_of_Hungary.svg.png', // Hungarian
        'id' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Indonesia.svg/1280px-Flag_of_Indonesia.svg.png', // Indonesian
        'ms' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Flag_of_Malaysia.svg/1280px-Flag_of_Malaysia.svg.png', // Malay
        'nl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/1280px-Flag_of_the_Netherlands.svg.png', // Dutch
        'no' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Norway.svg/1280px-Flag_of_Norway.svg.png', // Norwegian
        'ua' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Ukraine.svg/1280px-Flag_of_Ukraine.svg.png', // Ukrainian
        'au' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Flag_of_Australia.svg/1280px-Flag_of_Australia.svg.png', // Australian
        'be' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Flag_of_Belgium.svg/1280px-Flag_of_Belgium.svg.png', // Belgian
        'br' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Brazil.svg/1280px-Flag_of_Brazil.svg.png', // Brazilian
        'ca' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Canada_%28Pantone%29.svg/1280px-Flag_of_Canada_%28Pantone%29.svg.png', // Canadian
        'cl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Flag_of_Chile.svg/1280px-Flag_of_Chile.svg.png', // Chilean
        'co' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Colombia.svg/1280px-Flag_of_Colombia.svg.png', // Colombian
        'eg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Egypt.svg/1280px-Flag_of_Egypt.svg.png', // Egyptian
        'et' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Flag_of_Ethiopia.svg/1280px-Flag_of_Ethiopia.svg.png', // Ethiopian
        'ie' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Flag_of_Ireland.svg/1280px-Flag_of_Ireland.svg.png', // Irish
        'ke' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Kenya.svg/1280px-Flag_of_Kenya.svg.png', // Kenyan
        'mx' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Mexico.svg/1280px-Flag_of_Mexico.svg.png', // Mexican
        'nz' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Flag_of_New_Zealand.svg/1280px-Flag_of_New_Zealand.svg.png', // New Zealander
        'ng' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Flag_of_Nigeria.svg/1280px-Flag_of_Nigeria.svg.png', // Nigerian
        'pe' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Flag_of_Peru.svg/1280px-Flag_of_Peru.svg.png', // Peruvian
        'ir' => 'https://aiteamup.com/storage/logo/Iran.png', // Iranian
        'ph' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/1280px-Flag_of_the_Philippines.svg.png', // Filipino
        'sg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/1280px-Flag_of_Singapore.svg.png', // Singaporean
        'za' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/1280px-Flag_of_South_Africa.svg.png', // South African
        'ch' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/1280px-Flag_of_Switzerland.svg.png', // Switzerland

    );

    ?>

    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="<?php _esc($page_title); ?>">
    <meta property="twitter:description" content="<?php _esc($meta_desc); ?>">
    <meta property="twitter:domain" content="<?php _esc($config['site_url']); ?>">
    <meta name="twitter:image:src" content="<?php _esc($meta_image); ?>" />
    <link rel="shortcut icon" href="<?php _esc($config['site_url']); ?>storage/logo/<?php _esc($config['site_favicon']); ?>">

    <script async>
        var themecolor = '<?php _esc($config['theme_color']); ?>';
        var mapcolor = '<?php _esc($config['map_color']); ?>';
        var siteurl = '<?php _esc($config['site_url']); ?>';
        var template_name = '<?php _esc($config['tpl_name']); ?>';
        var ajaxurl = "<?php _esc(str_replace(['https:', 'http:'], ['', ''], $config['app_url'])); ?>user-ajax.php";
    </script>

    <?php
    if (!empty($config['quickad_user_secret_file'])) {
    ?>
        <script>
            var ajaxurl = '<?php _esc(str_replace(['https:', 'http:'], ['', ''], $config['app_url']) . $config['quickad_user_secret_file'] . '.php'); ?>';
        </script>
    <?php
    }
    ?>

    <!--Loop for Theme Color codes-->
    <style>
        :root {
            <?php
            $themecolor = $theme_mode == "dark" ? getComplementaryColor($config['theme_color']) : $config['theme_color'];
            $colors = array();
            list($r, $g, $b) = sscanf($themecolor, "#%02x%02x%02x");
            $i = 0.01;
            while ($i <= 1) {
                echo "--theme-color-" . str_replace('.', '_', $i) . ": rgba($r,$g,$b,$i);";
                $i += 0.01;
            }
            echo "--theme-color-1: rgba($r,$g,$b,1);";
            ?>
        }

        .theme-manage {
            <?php
            $themecolor = $config['theme_color'];
            $colors = array();
            list($r, $g, $b) = sscanf($themecolor, "#%02x%02x%02x");
            $i = 0.01;
            while ($i <= 1) {
                echo "--theme-color-" . str_replace('.', '_', $i) . ": rgba($r,$g,$b,$i);";
                $i += 0.01;
            }
            echo "--theme-color-1: rgba($r,$g,$b,1);";
            ?>
        }

        .ai-templates-pro {
            <?php
            $ai_employee_card_color = get_option('ai_employee_card_color');
            echo "background-color: $ai_employee_card_color!important; border: none !important;";
            ?>
        }
    </style>
    <!--Loop for Theme Color codes-->

    <link rel="stylesheet" href="<?php _esc($config['site_url']); ?>includes/assets/css/icons.css">
    <?php if ($lang_direction == 'rtl') {
        echo '<link rel="stylesheet" href="' . TEMPLATE_URL . '/css/rtl.css?ver=' . $config['version'] . '">';
    } else {
        echo '<link rel="stylesheet" href="' . TEMPLATE_URL . '/css/style.css?ver=' . $config['version'] . '">';
    } ?>
    <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/color.css?ver=<?php _esc($config['version']); ?>">
    <?php if (isset($_COOKIE['quickapp'])) { ?>
        <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/app.css?ver=<?php _esc($config['version']); ?>">
    <?php } ?>
    <script src="<?php _esc(TEMPLATE_URL); ?>/js/jquery.min.js"></script>

    <?php if (CURRENT_PAGE == 'app/home') { ?>
        <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/landing/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/landing/animate.css" />
        <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/landing/lineicons.css" />
        <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/landing/styles.css" />
    <?php } ?>

    <?php print_live_chat_code() ?>

    <!-- ===External Code=== -->
    <?php _esc($config['external_code']); ?>
    <!-- ===/External Code=== -->
</head>
<?php
$theme_mode = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id'])->theme_color;
?>

<script src="<?php $config['site_url'] ?>templates/classic-theme/js/user-ajax.js"></script>

<body data-role="page" class="<?php _esc($lang_direction); ?><?php if ($theme_mode === "dark") echo ' dark' ?>" id="page">

    <?php
    global $current_user;
    $plan_settings = $current_user['plan']['settings'];
    $site_title = $config['site_title'];

    ?>

    <?php if (CURRENT_PAGE == 'app/home') { ?>
        <div>
            <!-- ====== Header Start ====== -->
            <header class="ud-header">
                <?php print_adsense_code('header_top'); ?>
                <div class="container">

                    <div class="row">
                        <div class="col-lg-12">
                            <nav class="navbar navbar-expand-lg">
                                <a class="navbar-brand" href="<?php url("INDEX") ?>">
                                    <?php
                                    $logo_dark = $config['site_url'] . 'storage/logo/' . $config['site_logo'];
                                    $logo_white = $config['site_url'] . 'storage/logo/' . $config['site_logo_footer'];
                                    ?>
                                    <img src="<?php _esc($logo_white) ?>" data-logo="<?php _esc($logo_white); ?>" data-stickylogo="<?php _esc($logo_dark); ?>" alt="<?php _esc($config['site_title']); ?>" />
                                </a>
                                <div class="navbar-collapse">
                                    <ul id="nav" class="navbar-nav mx-auto">
                                        <li class="nav-item">
                                            <a class="ud-menu-scroll" href="#home"><?php _e("Home") ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="ud-menu-scroll" href="#features"><?php _e("Features") ?></a>
                                        </li>
                                        <?php if ($config['show_membershipplan_home']) { ?>
                                            <li class="nav-item">
                                                <a class="ud-menu-scroll" href="#pricing"><?php _e("Pricing") ?></a>
                                            </li>
                                        <?php }
                                        if (get_option("enable_faqs", '1')) {
                                        ?>
                                            <li class="nav-item">
                                                <a class="ud-menu-scroll" href="#faq"><?php _e("FAQs") ?></a>
                                            </li>
                                        <?php
                                        }
                                        if ($config['blog_enable'] && $config['show_blog_home']) { ?>
                                            <li class="nav-item">
                                                <a class="ud-menu-scroll" href="#blog"><?php _e("Blog") ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                                <div class="navbar-btn d-flex align-items-center">
                                    <?php
                                    if ($is_login) {
                                    ?>
                                        <div class="header-notifications user-menu">
                                            <div class="header-notifications-trigger">
                                                <a href="#" title="<?php _esc($username); ?>">
                                                    <div class="user-avatar status-online"><img src="<?php _esc($config['site_url']); ?>storage/profile/<?php _esc($userpic) ?>" alt="<?php _esc($username); ?>"></div>
                                                </a>
                                            </div>
                                            <!-- Dropdown -->
                                            <div class="header-notifications-dropdown">
                                                <ul class="user-menu-small-nav">
                                                    <li><a href="<?php url("DASHBOARD") ?>"><i class="icon-feather-grid"></i> <?php _e("Dashboard") ?></a>
                                                    </li>
                                                    <?php if (get_option("enable_ai_templates", 1)) { ?>
                                                        <li><a href="<?php url("AI_TEMPLATES") ?>"><i class="icon-feather-layers"></i> <?php _e("Templates") ?>
                                                            </a>
                                                        </li>
                                                        <?php }
                                                    if ($config['enable_ai_images']) {
                                                        if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_images_limit'])) { ?>
                                                            <li><a href="<?php url("AI_IMAGES") ?>"><i class="icon-feather-image"></i> <?php _e("AI Images") ?>
                                                                </a>
                                                            </li>
                                                        <?php }
                                                    }
                                                    if ($config['enable_ai_chat']) {
                                                        if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_chat'])) { ?>
                                                            <li><a href="<?php url("AI_CHAT_BOTS") ?>"><i class="icon-feather-message-circle"></i> <?php _e("AI Team") ?>
                                                                </a></li>
                                                        <?php }
                                                    }
                                                    if ($config['enable_speech_to_text']) {
                                                        if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_speech_to_text_limit'])) { ?>
                                                            <li><a href="<?php url("AI_SPEECH_TEXT") ?>"><i class="icon-feather-headphones"></i> <?php _e("AI Speech to Text") ?>
                                                                </a></li>
                                                        <?php }
                                                    }
                                                    if (get_option('enable_text_to_speech', 0)) {
                                                        if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_text_to_speech_limit'])) { ?>
                                                            <li><a href="<?php url("AI_TEXT_SPEECH") ?>"><i class="icon-feather-volume-2"></i> <?php _e("AI Voiceover") ?>
                                                                </a></li>
                                                    <?php }
                                                    } ?>

                                                    <li>
                                                        <a href="<?php url("UAI") ?>"><i class="icon-feather-volume-2"></i> <?php _e("UAi") ?></a>
                                                    </li>

                                                    <?php if ($config['enable_ai_code']) {
                                                        if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_code'])) { ?>
                                                            <!-- <li><a href="<?php url("AI_CODE") ?>"><i
                                                                    class="icon-feather-code"></i> <?php _e("AI Code") ?>
                                                        </a>
                                                    </li> -->
                                                    <?php }
                                                    } ?>
                                                    <li><a href="<?php url("UAI") ?>"><i class="icon-feather-volume-2"></i> <?php _e("UAi") ?>
                                                        </a></li>
                                                    <li><a href="<?php url("ALL_DOCUMENTS") ?>"><i class="icon-feather-file-text"></i> <?php _e("All Documents") ?>
                                                        </a></li>
                                                    <li><a href="<?php url("MEMBERSHIP") ?>"><i class="icon-feather-gift"></i> <?php _e("Membership") ?></a>
                                                    </li>
                                                    <li><a href="<?php url("ACCOUNT_SETTING") ?>"><i class="icon-feather-settings"></i> <?php _e("Account Setting") ?>
                                                        </a></li>
                                                    <li><a href="<?php url("ACCOUNT_SETTING") ?>"><i class="icon-feather-settings"></i> <?php _e("Support") ?>
                                                        </a></li>
                                                    <?php
                                                    foreach ($html_pages as $html_page) { ?>
                                                        <li>
                                                            <a href="<?php _esc($config['site_url'] . 'page/' . $html_page['slug']) ?>"><i class="icon-feather-file-text"></i> <?php _esc($html_page['title']) ?>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <li><a href="<?php url("LOGOUT") ?>"><i class="icon-feather-log-out"></i> <?php _e("Logout") ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <a href="#sign-in-dialog" class="popup-with-zoom-anim ud-main-btn ud-white-btn ripple-effect">
                                            <span><?php _e("Join Now") ?>&nbsp;</span><i class="icon-feather-log-in"></i>
                                        </a>
                                    <?php } ?>
                                    <?php if ($config['userlangsel']) { ?>
                                        <div class="btn-group bootstrap-select language-switcher">
                                            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
                                                <span class="filter-option pull-left" id="selected_lang"><?php _esc($config['lang_code']); ?></span>&nbsp;
                                                <span class="caret"></span>
                                            </button>
                                            <div class="dropdown-menu scrollable-menu open">
                                                <ul class="dropdown-menu inner">
                                                    <?php
                                                    foreach ($languages as $lang) {

                                                        $flagImage = isset($languageFlags[$lang['code']]) ? $languageFlags[$lang['code']] : '';

                                                        echo '<li style="display: flex" data-lang="' . $lang['file_name'] . '">
                                                        <img style="border-radius:10px; margin-top:7px; width:20px; height:20px;" src="' . $flagImage . '"></img>
                                                        <span style="width: 10px;"></span>
                                                    <a role="menuitem" tabindex="-1" rel="alternate" onclick="langConvert(\'' . $lang['code'] . '\')">' . $lang['name'] . '</a>
                                                  </li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <!-- ====== Header End ====== -->
        <?php } else { ?>
            <!-- Wrapper -->
            <div id="wrapper">
                <!-- Header Container
        ================================================== -->
                <header id="header-container" class="fullwidth">
                    <?php print_adsense_code('header_top'); ?>

                    <?php

                    if (($config['non_active_msg'] == 1 && $userstatus == 0)) { ?>
                        <?php if ($page_title == 'Membership Plan - ' . $site_title) { ?>
                            <div></div>
                        <?php } else { ?>
                            <div class="user-status-message">
                                <div class="container container-active-msg">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <i class="icon-lock text-18"></i>
                                            <span><?php _e('Your email address is not verified. Please verify your email address to use all the features.'); ?></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <a class="button ripple-effect gray resend_buttons<?php _esc($user_id) ?> resend" href='javascript:void(0);' id="<?php _esc($user_id) ?>"><?php _e('Resend Email'); ?></a>
                                            <span class='resend_count' id='resend_count<?php _esc($user_id) ?>'></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <!-- Header -->
                    <?php

                    // $string_array = ['Blog - ' . $site_title, 'Feedback - ' . $site_title, 'Contact Us - ' . $site_title, 'Frequently Asked Questions - ' . $site_title, 'Membership Plan - ' . $site_title, 'High Ticket AI Blueprint - ' . $site_title];
                    $string_array = ['Blog - ' . $site_title, 'Feedback - ' . $site_title, 'Contact Us - ' . $site_title, 'Frequently Asked Questions - ' . $site_title, 'High Ticket AI Blueprint - ' . $site_title, 'Temp Pricing - ' . $site_title, 'Membership Plan - ' . $site_title];
                    if (in_array($page_title, $string_array)) { ?>
                        <div></div>

                        <?php if ($page_title == 'Membership Plan - ' . $site_title && $group_id != "free") { ?>
                            <div id="header">
                                <div class="container">
                                    <!-- Left Side Content -->
                                    <div class="left-side">
                                        <!-- Logo -->
                                        <div id="logo">
                                            <a href="<?php url("DASHBOARD") ?>">
                                                <?php
                                                $logo_dark = $config['site_url'] . 'storage/logo/' . $config['site_logo'];
                                                $logo_white = $config['site_url'] . 'storage/logo/' . $config['image_dark_logo'];
                                                ?>
                                                <img style="height: 60px, width : 270px" src="<?php $theme_mode == "light" ? _esc($logo_dark) : _esc($logo_white) ?>" data-sticky-logo="<?php _esc($logo_dark); ?>" data-transparent-logo="<?php _esc($logo_white); ?>" alt="<?php _esc($config['site_title']); ?>">
                                            </a>
                                        </div>

                                        <a href="javascript:void(0);" class="header-icon d-none">
                                            <i class="fa fa-bars"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="header-icon toggleFullScreen d-none">
                                            <i class="icon-feather-maximize"></i>
                                        </a>

                                    </div>
                                    <!-- Left Side Content / End -->


                                    <!-- Right Side Content / End -->
                                    <div class="right-side">
                                        <?php
                                        if ($is_login) {
                                        ?>

                                            <!-- User Menu -->
                                            <div class="header-widget">

                                                <!-- Messages -->
                                                <div class="header-notifications user-menu">
                                                    <div class="header-notifications-trigger">
                                                        <a href="#" title="<?php _esc($username); ?>">
                                                            <div class="user-avatar status-online"><img src="<?php _esc($config['site_url']); ?>storage/profile/<?php _esc($userpic) ?>" alt="<?php _esc($username); ?>"></div>
                                                        </a>
                                                    </div>
                                                    <!-- Dropdown -->
                                                    <div class="header-notifications-dropdown">
                                                        <ul class="user-menu-small-nav">
                                                            <li><a href="<?php url("DASHBOARD") ?>"><i class="icon-feather-grid"></i> <?php _e("Dashboard") ?></a>
                                                            </li>
                                                            <?php if (get_option("enable_ai_templates", 1)) { ?>
                                                                <li><a href="<?php url("AI_TEMPLATES") ?>"><i class="icon-feather-layers"></i> <?php _e("Templates") ?>
                                                                    </a>
                                                                </li>
                                                                <?php }
                                                            if ($config['enable_ai_images']) {
                                                                if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_images_limit'])) { ?>
                                                                    <li><a href="<?php url("AI_IMAGES") ?>"><i class="icon-feather-image"></i> <?php _e("AI Images") ?>
                                                                        </a>
                                                                    </li>
                                                                <?php }
                                                            }
                                                            if ($config['enable_ai_chat']) {
                                                                if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_chat'])) { ?>
                                                                    <li><a href="<?php url("AI_CHAT_BOTS") ?>"><i class="icon-feather-message-circle"></i> <?php _e("AI Team") ?>
                                                                        </a></li>
                                                                <?php }
                                                            }
                                                            if ($config['enable_speech_to_text']) {
                                                                if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_speech_to_text_limit'])) { ?>
                                                                    <li><a href="<?php url("AI_SPEECH_TEXT") ?>"><i class="icon-feather-headphones"></i> <?php _e("AI Speech to Text") ?>
                                                                        </a></li>
                                                                <?php }
                                                            }
                                                            if (get_option('enable_text_to_speech', 0)) {
                                                                if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_text_to_speech_limit'])) { ?>
                                                                    <li><a href="<?php url("AI_TEXT_SPEECH") ?>"><i class="icon-feather-volume-2"></i> <?php _e("AI Voiceover") ?>
                                                                        </a></li>
                                                                <?php }
                                                            }
                                                            if ($config['enable_ai_code']) {
                                                                if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_code'])) { ?>
                                                                    <!-- <li><a href="<?php url("AI_CODE") ?>"><i
                                                                    class="icon-feather-code"></i> <?php _e("AI Code") ?>
                                                        </a>
                                                    </li> -->
                                                            <?php }
                                                            } ?>
                                                            <li><a href="<?php url("UAI") ?>"><i class="icon-feather-volume-2"></i> <?php _e("UAi") ?>
                                                                </a></li>
                                                            <li><a href="<?php url("ALL_DOCUMENTS") ?>"><i class="icon-feather-file-text"></i> <?php _e("All Documents") ?>
                                                                </a></li>
                                                            <li><a href="<?php url("MEMBERSHIP") ?>"><i class="icon-feather-gift"></i> <?php _e("Membership") ?></a>
                                                            </li>
                                                            <li><a href="<?php url("ACCOUNT_SETTING") ?>"><i class="icon-feather-settings"></i> <?php _e("Account Setting") ?>
                                                                </a></li>
                                                            <li><a href="<?php url("CUSTOM_SUPPORT") ?>"><i class="icon-feather-eye"></i> <?php _e("Support") ?>
                                                                </a></li>
                                                            <?php
                                                            foreach ($html_pages as $html_page) { ?>
                                                                <li>
                                                                    <a href="<?php _esc($config['site_url'] . 'page/' . $html_page['slug']) ?>"><i class="icon-feather-file-text"></i> <?php _esc($html_page['title']) ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <li><a href="<?php url("LOGOUT") ?>"><i class="icon-feather-log-out"></i> <?php _e("Logout") ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- User Menu / End -->
                                        <?php } else { ?>
                                            <div class="header-widget">
                                                <a href="#sign-in-dialog" class="popup-with-zoom-anim button ripple-effect">
                                                    <span><?php _e("Join Now") ?>&nbsp;</span><i class="icon-feather-log-in"></i>
                                                </a>
                                            </div>
                                        <?php } ?>

                                        <?php if ($config['userlangsel']) { ?>
                                            <div class="header-widget">
                                                <div class="btn-group bootstrap-select language-switcher">
                                                    <button style="display: flex;" type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
                                                        <img style="border-radius:10px; margin-top:13px; width:20px; height:20px; margin-right: 10px;" src="<?php echo isset($languageFlags[$config['lang_code']]) ? $languageFlags[$config['lang_code']] : ''; ?>"></img>
                                                        <span class="filter-option pull-left" id="selected_lang"><?php if ($config['lang_code'] == "en") {
                                                                                                                        echo "US"; ?> <?php } else {
                                                                                                                                    _esc($config['lang_code']);
                                                                                                                                } ?></span>&nbsp;
                                                        <span class="caret"></span>
                                                    </button>
                                                    <a onclick=""></a>
                                                    <div class="dropdown-menu scrollable-menu open">
                                                        <ul class="dropdown-menu inner">
                                                            <?php
                                                            foreach ($languages as $lang) {

                                                                if ($lang['name'] == "English") {
                                                                    $lang['name'] = "USA";
                                                                }

                                                                $flagImage = isset($languageFlags[$lang['code']]) ? $languageFlags[$lang['code']] : '';

                                                                echo '<li style="display: flex" data-lang="' . $lang['file_name'] . '">
                                                        <img style="border-radius:10px; margin-top:7px; width:20px; height:20px;" src="' . $flagImage . '"></img>
                                                        <span style="width: 10px;"></span>
                                                    <a role="menuitem" tabindex="-1" rel="alternate" onclick="langConvert(\'' . $lang['code'] . '\')">' . $lang['name'] . '</a>
                                                  </li>';

                                                                //   <a role="menuitem" tabindex="-1" rel="alternate" href="' . url("HOME", false) . '/' . $lang['code'] . '">' . $lang['name'] . '</a>

                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- Right Side Content / End -->

                                </div>
                            </div>
                        <?php } ?>
                    <?php } else if ($meta_desc) { ?>
                        <div class="hompage-header">
                            <div class="hompage-header-letter">
                                <?php _e("Limited Time:") ?>
                                <a href="<?php echo $config['site_url'] . "membership/changeplan" ?>" id="link-89120" class="" style="color: rgb(244, 249, 252)">Save BIG With the ‘AI Maximizer’ Plan</a>
                            </div>
                        </div>

                        <div class="hompage-header-bar">
                            <div class="hompage-header-bar-main">
                                <a href="<?php url("INDEX") ?>">
                                    <img class="hompage-header-bar-main-icon" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['site_logo']; ?>">
                                </a>
                                <div class="hompage-header-bar-main-action-board">
                                    <div class="hompage-header-bar-main-action">
                                        <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "#ait_team_up_section" ?>">What's Included</a></h2>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "membership/changeplan" ?>">Pricing</a></h2>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "login" ?>">Log In</a></h2>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                    </div>
                                    <div class="hompage-header-bar-main-action-button"><a style="color: white !important;" href="<?php echo $config['site_url'] . "signup" ?>">Try For Free</a></div>
                                </div>
                            </div>
                        </div>
                        <div style="height: 100px; display: block;"></div>
                    <?php } else { ?>
                        <div id="header">
                            <div class="container">
                                <!-- Left Side Content -->
                                <div class="left-side">
                                    <!-- Logo -->
                                    <div id="logo">
                                        <a href="<?php url("DASHBOARD") ?>">
                                            <?php
                                            $logo_dark = $config['site_url'] . 'storage/logo/' . $config['site_logo'];
                                            $logo_white = $config['site_url'] . 'storage/logo/' . $config['image_dark_logo'];
                                            ?>
                                            <img style="height: 60px, width : 270px" src="<?php $theme_mode == "light" ? _esc($logo_dark) : _esc($logo_white) ?>" data-sticky-logo="<?php _esc($logo_dark); ?>" data-transparent-logo="<?php _esc($logo_white); ?>" alt="<?php _esc($config['site_title']); ?>">
                                        </a>
                                    </div>

                                    <a href="javascript:void(0);" class="header-icon d-none">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="header-icon toggleFullScreen d-none">
                                        <i class="icon-feather-maximize"></i>
                                    </a>

                                </div>
                                <!-- Left Side Content / End -->


                                <!-- Right Side Content / End -->
                                <div class="right-side">
                                    <?php
                                    if ($is_login) {
                                    ?>

                                        <!-- User Menu -->
                                        <div class="header-widget">

                                            <!-- Messages -->
                                            <div class="header-notifications user-menu">
                                                <div class="header-notifications-trigger">
                                                    <a href="#" title="<?php _esc($username); ?>">
                                                        <div class="user-avatar status-online"><img src="<?php _esc($config['site_url']); ?>storage/profile/<?php _esc($userpic) ?>" alt="<?php _esc($username); ?>"></div>
                                                    </a>
                                                </div>
                                                <!-- Dropdown -->
                                                <div class="header-notifications-dropdown">
                                                    <ul class="user-menu-small-nav">
                                                        <li><a href="<?php url("DASHBOARD") ?>"><i class="icon-feather-grid"></i> <?php _e("Dashboard") ?></a>
                                                        </li>
                                                        <?php if (get_option("enable_ai_templates", 1)) { ?>
                                                            <li><a href="<?php url("AI_TEMPLATES") ?>"><i class="icon-feather-layers"></i> <?php _e("Templates") ?>
                                                                </a>
                                                            </li>
                                                            <?php }
                                                        if ($config['enable_ai_images']) {
                                                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_images_limit'])) { ?>
                                                                <li><a href="<?php url("AI_IMAGES") ?>"><i class="icon-feather-image"></i> <?php _e("AI Images") ?>
                                                                    </a>
                                                                </li>
                                                            <?php }
                                                        }
                                                        if ($config['enable_ai_chat']) {
                                                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_chat'])) { ?>
                                                                <li><a href="<?php url("AI_CHAT_BOTS") ?>"><i class="icon-feather-message-circle"></i> <?php _e("AI Team") ?>
                                                                    </a></li>
                                                            <?php }
                                                        }
                                                        if ($config['enable_speech_to_text']) {
                                                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_speech_to_text_limit'])) { ?>
                                                                <li><a href="<?php url("AI_SPEECH_TEXT") ?>"><i class="icon-feather-headphones"></i> <?php _e("AI Speech to Text") ?>
                                                                    </a></li>
                                                            <?php }
                                                        }
                                                        if (get_option('enable_text_to_speech', 0)) {
                                                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_text_to_speech_limit'])) { ?>
                                                                <li><a href="<?php url("AI_TEXT_SPEECH") ?>"><i class="icon-feather-volume-2"></i> <?php _e("AI Voiceover") ?>
                                                                    </a></li>
                                                            <?php }
                                                        }
                                                        if ($config['enable_ai_code']) {
                                                            if (!get_option('hide_plan_disabled_features') || (get_option('hide_plan_disabled_features') && $plan_settings['ai_code'])) { ?>
                                                                <!-- <li><a href="<?php url("AI_CODE") ?>"><i
                                                                    class="icon-feather-code"></i> <?php _e("AI Code") ?>
                                                        </a>
                                                    </li> -->
                                                        <?php }
                                                        } ?>
                                                        <li><a href="<?php url("UAI") ?>"><i class="icon-feather-volume-2"></i> <?php _e("UAi") ?>
                                                            </a></li>
                                                        <li><a href="<?php url("ALL_DOCUMENTS") ?>"><i class="icon-feather-file-text"></i> <?php _e("All Documents") ?>
                                                            </a></li>
                                                        <li><a href="<?php url("MEMBERSHIP") ?>"><i class="icon-feather-gift"></i> <?php _e("Membership") ?></a>
                                                        </li>
                                                        <li><a href="<?php url("ACCOUNT_SETTING") ?>"><i class="icon-feather-settings"></i> <?php _e("Account Setting") ?>
                                                            </a></li>
                                                        <li><a href="<?php url("CUSTOM_SUPPORT") ?>"><i class="icon-feather-eye"></i> <?php _e("Support") ?>
                                                            </a></li>
                                                        <?php
                                                        foreach ($html_pages as $html_page) { ?>
                                                            <li>
                                                                <a href="<?php _esc($config['site_url'] . 'page/' . $html_page['slug']) ?>"><i class="icon-feather-file-text"></i> <?php _esc($html_page['title']) ?>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <li><a href="<?php url("LOGOUT") ?>"><i class="icon-feather-log-out"></i> <?php _e("Logout") ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- User Menu / End -->
                                    <?php } else { ?>
                                        <div class="header-widget">
                                            <a href="#sign-in-dialog" class="popup-with-zoom-anim button ripple-effect">
                                                <span><?php _e("Join Now") ?>&nbsp;</span><i class="icon-feather-log-in"></i>
                                            </a>
                                        </div>
                                    <?php } ?>

                                    <?php if ($config['userlangsel']) { ?>
                                        <div class="header-widget">
                                            <div class="btn-group bootstrap-select language-switcher">
                                                <button style="display: flex;" type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
                                                    <img style="border-radius:10px; margin-top:13px; width:20px; height:20px; margin-right: 10px;" src="<?php echo isset($languageFlags[$config['lang_code']]) ? $languageFlags[$config['lang_code']] : ''; ?>"></img>
                                                    <span class="filter-option pull-left" id="selected_lang"><?php if ($config['lang_code'] == "en") {
                                                                                                                    echo "US"; ?> <?php } else {
                                                                                                                                    _esc($config['lang_code']);
                                                                                                                                } ?></span>&nbsp;
                                                    <span class="caret"></span>
                                                </button>
                                                <a onclick=""></a>
                                                <div class="dropdown-menu scrollable-menu open">
                                                    <ul class="dropdown-menu inner">
                                                        <?php
                                                        foreach ($languages as $lang) {

                                                            if ($lang['name'] == "English") {
                                                                $lang['name'] = "USA";
                                                            }

                                                            $flagImage = isset($languageFlags[$lang['code']]) ? $languageFlags[$lang['code']] : '';

                                                            echo '<li style="display: flex" data-lang="' . $lang['file_name'] . '">
                                                        <img style="border-radius:10px; margin-top:7px; width:20px; height:20px;" src="' . $flagImage . '"></img>
                                                        <span style="width: 10px;"></span>
                                                    <a role="menuitem" tabindex="-1" rel="alternate" onclick="langConvert(\'' . $lang['code'] . '\')">' . $lang['name'] . '</a>
                                                  </li>';

                                                            //   <a role="menuitem" tabindex="-1" rel="alternate" href="' . url("HOME", false) . '/' . $lang['code'] . '">' . $lang['name'] . '</a>

                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- Right Side Content / End -->

                            </div>
                        </div>
                    <?php } ?>


                    <!-- Header / End -->
                </header>
                <div class="clearfix"></div>
                <!-- Header Container / End -->
            <?php } ?>

            <script>
                function langConvert(langCode) {
                    var formData = new FormData();
                    formData.append("langCode", langCode);

                    $.ajax({
                        type: "POST",
                        url: ajaxurl + "?action=convert_language",
                        data: formData,
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {

                            if (!response.success) {
                                Snackbar.show({
                                    text: response.error,
                                    pos: "top-center",
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 4000,
                                    textColor: "#ea5252",
                                    backgroundColor: "#ffe6e6",
                                });
                            } else {
                                window.location.reload();
                            }
                        },
                    });

                }
            </script>
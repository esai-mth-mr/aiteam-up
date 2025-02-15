<?php
global $config;

$all_items = array();
// $all_items[0]['title'] = "Blog Ideas";
// $all_items[0]['content'] = "Article/blog ideas that you can use to generate more traffic, leads, and sales for your business.";
// $all_items[0]['image'] = $config['f7Icon1'];
// $all_items[1]['title'] = "Article Writer";
// $all_items[1]['content'] = "Create a fully complete high quality article from a title and outline text.";
// $all_items[1]['image'] = $config['f7Icon2'];

$titles = array(
    "Blog Ideas",
    "Article Writer",
    "Article Rewriter",
    "Content Rephrase",
    "Facebook Ads",
    "Google Ad Descriptions",
    "LinkedIn Ad ",
    "App & SMS Notifications",
    "Passive to Active Voice",
    "Company Vision",
    "Company Mission",
    "Emails",
    "Storytelling ",
    "2nd Grade Summary",
    "Quora Answers",
    "Product Descriptions",
    "TikTok Video Scripts",
    "SEO Meta Tags ",
    "Social Media Post ",
    "Instagram Captions",
    "Instagram Hashtags",
    "Twitter Tweets",
    "YouTube Descriptions",
    "LinkedIn Posts",
    "Translate",
    "FAQs",
    "FAQ Answers",
    "Testimonials / Reviews"
);

$contents = array(
    "Article/blog ideas that you can use to generate more traffic, leads, and sales for your business.",
    "Create a fully complete high-quality article from a title and outline text.",
    "Copy an article, paste it in to the program, and with just one click you'll have an entirely different article to read.",
    "Rephrase your content in a different voice and style to appeal to different readers.",
    "Facebook ad copies that make your ads truly stand out.",
    "The best-performing Google ad copy converts visitors into customers.",
    "Professional and eye-catching ad descriptions that will make your product shine.",
    "Notification messages for your apps, websites, and mobile devices that keep users coming back for more.",
    "Easy and quick solution to converting your passive voice sentences into active voice sentences.",
    "A vision that attracts the right people, clients, and employees.",
    "A clear and concise statement of your company's goals and purpose.",
    "Professional-looking emails that help you engage leads and customers.",
    "Engaging and persuasive stories that will capture your reader's attention and interest.",
    "Translates difficult text into simpler concepts.",
    "Answers to Quora questions that will position you as an authority.",
    "Authentic product descriptions that rank on the first page of the search results.Descriptions for Amazon, Shopify, ClickFunnels, etc.",
    "Video scripts that are ready to shoot and will make you go viral.",
    "A set of optimized meta title and meta description tags that will boost your search rankings for your home page.",
    "Write a post for yourself or business to be published on any social media platform. More output = More lead$",
    "Captions that turn your images into attention-grabbing Instagram posts.",
    "Trending and highly relevant hashtags to help you get more followers and engagement.",
    "Generate tweets using AI, that are relevant and on-trend.",
    "Catchy and persuasive YouTube descriptions that help your videos rank higher.",
    "Inspiring LinkedIn posts that will help you build trust and authority in your industry.",
    "Translate your content into any language you want.",
    "Generate frequently asked questions based on your product description.",
    "Generate creative answers to questions (FAQs) about your business or website.",
    "Add social proof to your website by generating user testimonials and what to ask."
);
$images = array(
    $config['f7Icon1'],
    $config['f7Icon2'],
    $config['f7Icon3'],
    $config['f7Icon4'],
    $config['f7Icon5'],
    $config['f7Icon6'],
    $config['f7Icon7'],
    $config['f7Icon8'],
    $config['f7Icon9'],
    $config['f7Icon10'],
    $config['f7Icon11'],
    $config['f7Icon12'],
    $config['f7Icon13'],
    $config['f7Icon14'],
    $config['f7Icon15'],
    $config['f7Icon16'],
    $config['f7Icon17'],
    $config['f7Icon18'],
    $config['f7Icon19'],
    $config['f7Icon20'],
    $config['f7Icon21'],
    $config['f7Icon22'],
    $config['f7Icon23'],
    $config['f7Icon24'],
    $config['f7Icon25'],
    $config['f7Icon26'],
    $config['f7Icon27'],
    $config['f7Icon28'],
);

for ($i = 0; $i < count($titles); $i++) {
    $all_items[$i]['title'] = $titles[$i];
    $all_items[$i]['content'] = $contents[$i];
    $all_items[$i]['image'] = $images[$i];
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
    'ir' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Flag_of_Iran.svg/1280px-Flag_of_Iran.svg.png', // Iranian
    'ph' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/1280px-Flag_of_the_Philippines.svg.png', // Filipino
    'sg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/1280px-Flag_of_Singapore.svg.png', // Singaporean
    'za' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/1280px-Flag_of_South_Africa.svg.png', // South African
    'ch' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/1280px-Flag_of_Switzerland.svg.png', // Switzerland

);

$languages = get_language_list('', 'selected', true);

?>

<!-- ====== Hero Start ====== -->

<head>
    <!-- SEO Meta Tags -->
    <link rel="icon" href="<?php echo $config['site_url'] . "storage/logo/" . $config['site_favicon'] ?>" type="image/x-icon">
    <meta name="description" content="Discover AI TeamUP, the leading AI platform for transforming digital marketing and content creation. Elevate engagement, drive growth, and innovate with AI.">
    <meta name="keywords" content="AI TeamUP, AI technology for marketing, Content creation AI, Social media automation, AI digital marketing, AI content management, AI for business growth, AI-driven marketing strategies, Targeted marketing, AI Audience engagement AI">
    <meta name="author" content="Your Name or Company Name">
    <meta name="robots" content="index, follow"> <!-- To allow search engine indexing -->

    <!-- Open Graph Meta Tags (for sharing on social media) -->
    <meta property="og:title" content="AI TeamUP - Revolutionizing Business Productivity | AI-Powered Solutions">
    <meta property="og:description" content="Discover AI TeamUP, the leading AI platform for transforming digital marketing and content creation. Elevate engagement, drive growth, and innovate with AI.">
    <!-- Logo -->

    <meta property="og:image" content="<?php echo $config['site_url'] . "storage/logo/" . $config['site_logo'] ?>">
</head>


<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo $config['site_url']; ?>/includes/assets/css/icons.css" />
<script src="<?php _esc(TEMPLATE_URL); ?>/js/jquery.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
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

<!-- <section class="ud-hero" id="home">
        <div class="anim-elements">
            <div class="anim-element"></div>
            <div class="anim-element"></div>
            <div class="anim-element"></div>
            <div class="anim-element"></div>
            <div class="anim-element"></div>
        </div>
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-hero-content wow fadeInUp" data-wow-delay=".2s">
                        <img width="180" class="mb-4"
                             src="<?php _esc(TEMPLATE_URL . '/images/home-hero-icon.png'); ?>"
                             alt="<?php _e("Best AI Content Writer") ?>">
                        <h1 class="ud-hero-title">
                            <?php _e("Best AI Content Writer") ?>
                        </h1>
                        <p class="ud-hero-desc">
                            <?php _e("Create SEO-optimized and unique content for your blogs, ads, emails, and website 10X faster & save hours of work.") ?>
                        </p>
                        <ul class="ud-hero-buttons">
                            <li>
                                <a href="<?php url("SIGNUP") ?>" class="ud-main-btn ud-white-btn">
                                    <?php _e("Get Started For Free") ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div
                            class="ud-hero-brands-wrapper wow fadeInUp"
                            data-wow-delay=".3s"
                    >
                        <?php _e("No credit card required.") ?>
                    </div>
                    <div class="ud-hero-image wow fadeInUp" data-wow-delay=".3s">
                        <img class="ud-hero-image-main" src="<?php _esc(TEMPLATE_URL); ?>/images/hero-image.png"
                             alt="hero-image"/>
                        <img
                                src="<?php _esc(TEMPLATE_URL); ?>/images/dotted-shape.svg"
                                alt="shape"
                                class="shape shape-1"
                        />
                        <img
                                src="<?php _esc(TEMPLATE_URL); ?>/images/dotted-shape.svg"
                                alt="shape"
                                class="shape shape-2"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section> -->
<!-- ====== Hero End ====== -->

<!-- ====== Header Start ====== -->
<div style="width: 100%; height: auto; background-color: #F1F4F8;">
    <div class="hompage-header">
        <div class="hompage-header-letter">
            <?php _e("Limited Time:") ?>
            <a href="<?php echo $config['site_url'] . "membership/changeplan" ?>" id="link-89120" class="" style="color: rgb(244, 249, 252)"><?php _e("Save BIG With the ‘AI Maximizer’ Plan") ?></a>
        </div>
    </div>

    <div class="mobile_header">
        <a href="<?php url("INDEX") ?>">
            <img class="mobile_hompage-header-bar-main-icon" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['site_logo']; ?>">
        </a>

        <i class="mobile_dropdown_menu icon-menu" id="mobile_dropdown_menu"></i>

        <?php if ($config['userlangsel']) { ?>
            <div class="mobile_lang_toggle lang_toggle_btn">
                <div class="header-widget">
                    <div class="btn-group bootstrap-select language-switcher">
                        <button style="display: flex;" type="button" class="btn dropdown-toggle btn-default btn_hover" data-toggle="dropdown">
                            <img style="border-radius:10px; margin-top:13px; width:20px; height:20px; margin-right: 10px;" src="<?php echo isset($languageFlags[$config['lang_code']]) ? $languageFlags[$config['lang_code']] : ''; ?>"></img>
                            <span class="filter-option pull-left" id="selected_lang"><?php if ($config['lang_code'] == "en") {
                                                                                            echo "US"; ?> <?php } else {
                                                                                                            _esc($config['lang_code']);
                                                                                                        } ?></span>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <a onclick=""></a>
                        <div class="dropdown-menu scrollable-menu open ">
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
            </div>

        <?php } ?>
    </div>

    <div class="mobile_dropdown_content_board" id="mobile_dropdown_content_board">
        <ul style="list-style-type: none;">
            <li>
                <div class="hompage-header-bar-main-action-button"><a style="color: white !important;" href="<?php echo $config['site_url'] . "signup" ?>"><?php _e("Try For Free") ?></a></div>
            </li>
            <li class="mobile_dropdown_content_list">
                <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "membership/changeplan" ?>"><?php _e("Pricing") ?></a></h2>
            </li>
            <li class="mobile_dropdown_content_list">
                <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "login" ?>"><?php _e("Log In") ?></a></h2>
            </li>

            <li class="mobile_dropdown_content_list">
                <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "#ait_team_up_section" ?>"><?php _e("What's Included") ?></a></h2>
            </li>
        </ul>

    </div>

    <div class="hompage-header-bar">
        <div class="hompage-header-bar-main">
            <a href="<?php url("INDEX") ?>">
                <img class="hompage-header-bar-main-icon" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['site_logo']; ?>">
            </a>
            <div class="hompage-header-bar-main-action-board">
                <div class="hompage-header-bar-main-action">
                    <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "#ait_team_up_section" ?>"><?php _e("What's Included") ?></a></h2>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "membership/changeplan" ?>"><?php _e("Pricing") ?></a></h2>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <h2><a style="color: #181e48 !important;" href="<?php echo $config['site_url'] . "login" ?>"><?php _e("Log In") ?></a></h2>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                </div>
                <div class="hompage-header-bar-main-action-button"><a style="color: white !important;" href="<?php echo $config['site_url'] . "signup" ?>"><?php _e("Try For Free") ?></a></div>
                <?php if ($config['userlangsel']) { ?>
                    <div class="lang_toggle_btn">
                        <div class="header-widget">
                            <div class="btn-group bootstrap-select language-switcher">
                                <button style="display: flex;" type="button" class="btn dropdown-toggle btn-default btn_hover" data-toggle="dropdown">
                                    <img style="border-radius:10px; margin-top:13px; width:20px; height:20px; margin-right: 10px;" src="<?php echo isset($languageFlags[$config['lang_code']]) ? $languageFlags[$config['lang_code']] : ''; ?>"></img>
                                    <span class="filter-option pull-left" id="selected_lang"><?php if ($config['lang_code'] == "en") {
                                                                                                    echo "US"; ?> <?php } else {
                                                                                                                    _esc($config['lang_code']);
                                                                                                                } ?></span>&nbsp;
                                    <span class="caret"></span>
                                </button>
                                <a onclick=""></a>
                                <div class="dropdown-menu scrollable-menu open ">
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
                    </div>

                <?php } ?>
            </div>

        </div>
    </div>

    <!-- ====== Header End ====== -->

    <!-- ====== Section 1 Start ====== -->

    <section id="home_feature_1" class="home_feature_1">
        <div class="home_feature_1-title">
            <a style="font-family: Caveat, Helvetica, sans-serif !important ; font-weight: 700 !important;"><?php _e("Struggling to Scale Content Creation? AI TeamUP is Your Shortcut to Content Mastery!") ?></a>
        </div>
        <div class="space_height_70"></div>
        <div class="home_feature_1-title-1">
            <b><?php _e("Meet") ?></b>
            <b> <?php _e("AITeamUP.") ?></b>
        </div>
        <div class="space_height_70"></div>
        <div class="home_feature_1-title-2">
            <span style="height: 20px; display: block;"></span>
            <a><?php _e("SAVE") ?></a>
            <a class="home_feature_1_title_2_trans"></a>
            <span class="span_title_2"></span>
            <a><?php _e("WITH AI TEAMUP.") ?></a>
        </div>
        <div style="height: 50px;"></div>
        <div class="home_feature_1-title-3">
            <b><?php _e("Content That Makes Your Leads Do What You Want Them To Do.") ?></b>
        </div>
        <div style="height: 50px;"></div>
        <div class="home_feature_1_free_trial_btn">
            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
            <br>
            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
        </div>
        <div style="height: 5px;"></div>
        <a><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
        <div style="height: 30px;"></div>
        <div class="home_feature_1_brand_item">
            <a style="color: rgb(29, 46, 59); font-size: 18px; font-weight: 700;"><i style="color: red;" class="fa_prepended fas fa-check-circle"></i>&nbsp;<?php _e("Zero Prompting Skills Needed") ?></a>
            <span style="height: 15px;"></span>
            <a style="color: rgb(29, 46, 59); font-size: 18px; font-weight: 700;"><i style="color: red;" class="fa_prepended fas fa-check-circle"></i>&nbsp;<?php _e("Works For Every Type Of Business") ?></a>
            <span style="height: 15px;"></span>
            <a style="color: rgb(29, 46, 59); font-size: 18px; font-weight: 700;"><i style="color: red;" class="fa_prepended fas fa-check-circle"></i>&nbsp;<?php _e("Create, Promote, Convert") ?></a>
        </div>
        <div class="website_height_30"></div>
        <!-- ======onboarding video -->
        <div class="website_video">
            <?php $video_link = get_option("website_video_link"); ?>
            <?php $video_file = get_option("website_video_file"); ?>

            <?php if (!empty($video_link) && empty($video_file)) { ?>
                <?php if (filter_var($video_link, FILTER_VALIDATE_URL)) : ?>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                        <!-- <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe> -->
                        <iframe src="<?php echo $video_link ?>" style="position: absolute; margin-left: -50%; width: 100%; height: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                <?php else : ?>
                    <p><?php _e("Invalid video link") ?></p>
                <?php endif; ?>
            <?php } elseif (!empty($video_file) && empty($video_link)) { ?>
                <?php if (filter_var($video_file)) : ?>
                    <video style="border-radius: 15px;" width="100%" height="auto" controls autoplay>
                        <source src="<?php echo $config['site_url'] . 'storage/video/' . $video_file ?>" alt="" rel="external" type="video/mp4">
                        <?php _e("Your browser does not support the video tag.") ?>
                    </video>
                <?php else : ?>
                    <p><?php _e("Invalid video file") ?></p>
                <?php endif; ?>
            <?php } elseif (!empty($video_link) && !empty($video_file)) { ?>
                <?php if (filter_var($video_link, FILTER_VALIDATE_URL)) : ?>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                        <!-- <iframe src="<?php echo $video_link ?>" style="position: absolute; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe> -->
                        <iframe src="<?php echo $video_link ?>" style="position: absolute; margin-left: -50%; width: 100%; height: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                <?php else : ?>
                    <p><?php _e("Invalid video link") ?></p>
                <?php endif; ?>
            <?php } ?>
        </div>

        <div class="home_feature_1_brand_2">
            <div style="display: inline-block;">
                <img style="height: 80px !important" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['f1Brand1']; ?>">
                <div class="brand_2_font"><?php _e("Proven AI") ?></div>
                <div class="brand_2_font"><?php _e("Templates") ?></div>
            </div>
            <div style="display: inline-block;">
                <img style="height: 80px !important" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['f1Brand2']; ?>">
                <span style="height: 10px; display: block;"></span>
                <div class="brand_2_font"><?php _e("AI Image Creator") ?></div>
            </div>
            <div style="display: inline-block;">
                <img style="height: 80px !important" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['f1Brand3']; ?>">
                <div class="brand_2_font"><?php _e("Analytics &") ?></div>
                <div class="brand_2_font"><?php _e("Insights") ?></div>
            </div>
            <div style="display: inline-block;">
                <img style="height: 80px !important" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['f1Brand4']; ?>">
                <div class="brand_2_font"><?php _e("Member Areas") ?></div>
                <div class="brand_2_font"><?php _e("& Courses") ?></div>
            </div>
            <div style="display: inline-block;">
                <img style="height: 80px !important" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['f1Brand5']; ?>">
                <span style="height: 10px; display: block;"></span>
                <div class="brand_2_font"><?php _e("AI Employees") ?></div>
            </div>
            <div style="display: inline-block;">
                <img style="height: 80px !important" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $config['f1Brand6']; ?>">
                <span style="height: 10px; display: block;"></span>
                <div class="brand_2_font"><?php _e("Clone Yourself") ?></div>
            </div>
        </div>
    </section>

    <!-- ====section 2 start-->
    <section id="home_feature_2" class="home_feature_2">
        <div style="height: 55px;"></div>
        <div class="home_feature_2_brand_image">
            <img style="height: 50px;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f2BrandImg'] ?>" alt="<?php _esc($config['site_title']); ?>" alt="<?php _esc($ai_image['description']) ?>" data-tippy-placement="top" title="<?php _esc($ai_image['description']) ?>">
        </div>
        <div style="height: 20px;"></div>
        <a class="home_feature_2_title_1"><?php _e("World Class Entrepreneurs") ?></a>
        <div class="website_height_30"></div>
        <a class="home_feature_2_title_1"><?php _e("Love Using AITeamUP") ?></a>
        <div style="height: 30px;"></div>
        <a class="home_feature_2_title_2"><?php _e("See For Yourself...") ?></a>
        <div style="height: 30px;"></div>
        <div class="home_feature_2_feedback">
            <div class="home_feature_2_feedback_item">
                <div class="home_feature_2_feedback_item_board">
                    <div style="height: 130px;"></div>
                    <a style="font-size: 20px; font-weight: 800;">Raze Khalili</a>
                    <div style="height: 10px;"></div>
                    <a style="font-size: 14px;"><?php _e("Verified AI TeamUP User") ?></a>
                    <div style="height: 10px;"></div>
                    <a style="font-size: 15px; font-weight: 700;"><?php _e("Crafting stellar articles has never been quicker") ?></a>
                    <div style="height: 5px;"></div>
                    <a style="margin-bottom: 20px;"><?php _e("Discovering AI TeamUP has been a game-changer for our content strategy. The platform's advanced AI capabilities have empowered us to produce polished articles rapidly, cutting down editing time to just minutes. What once took hours of meticulous writing, now unfolds with efficiency and ease, thanks to AI TeamUP.”") ?></a>
                    <span style="height: 30px; display: block;"></span>
                </div>
                <div class="home_feature_2_feedback_item_img">
                    <img style="width: 150px; height: 150px; border-radius: 10px;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['feedbackAvatar_1'] ?>" alt="<?php _esc($config['site_title']); ?>">
                </div>
            </div>
            <span style="height: 30px; display: block;"></span>
            <div class="home_feature_2_feedback_item">
                <div class="home_feature_2_feedback_item_board">
                    <div style="height: 130px;"></div>
                    <a style="font-size: 20px; font-weight: 800;">Bob Freer</a>
                    <div style="height: 10px;"></div>
                    <a style="font-size: 14px;"><?php _e("Verified AI TeamUP User") ?></a>
                    <div style="height: 10px;"></div>
                    <a style="font-size: 15px; font-weight: 700;"><?php _e("Boosted our team's efficiency exponentially!") ?></a>
                    <div style="height: 5px;"></div>
                    <a style="margin-bottom: 20px;"><?php _e("AI TeamUP has revolutionized the way we craft content, serving as an invaluable asset not only for engaging our community but also for enhancing our operational workflow. It has been instrumental in conserving our resources, allowing us to focus on excellence while streamlining content creation. ”") ?></a>
                    <span style="height: 30px; display: block;"></span>
                </div>
                <div class="home_feature_2_feedback_item_img">
                    <img style="width: 150px; height: 150px; border-radius: 10px;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['feedbackAvatar_2'] ?>" alt="<?php _esc($config['site_title']); ?>">
                </div>
            </div>
            <span style="height: 30px; display: block;"></span>

            <div class="home_feature_2_feedback_item">
                <div class="home_feature_2_feedback_item_board">
                    <div style="height: 130px;"></div>
                    <a style="font-size: 20px; font-weight: 800;">Suzi Bonine</a>
                    <div style="height: 10px;"></div>
                    <a style="font-size: 14px;"><?php _e("Verified AI TeamUP User") ?></a>
                    <div style="height: 10px;"></div>
                    <a style="font-size: 15px; font-weight: 700;"><?php _e("AI TeamUP goes beyond just article writing;") ?></a>
                    <div style="height: 5px;"></div>
                    <a style="margin-bottom: 20px;"><?php _e("it's a versatile powerhouse for creating compelling ads, detailed product descriptions, and jazzing up any sentence that needs a dash of creativity. It's highly customizable to fit virtually any niche you can imagine.”") ?></a>
                    <span style="height: 30px; display: block;"></span>
                </div>
                <div class="home_feature_2_feedback_item_img">
                    <img style="width: 150px; height: 150px; border-radius: 10px;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['feedbackAvatar_3'] ?>" alt="<?php _esc($config['site_title']); ?>">
                </div>
            </div>
        </div>
        <span style="height: 20px; display: block;"></span>
        <div class="home_feature_1_free_trial_btn">
            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
            <br>
            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
        </div>
        <span style="height: 10px;"></span>
        <a style="font-size: 14px;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
    </section>

    <!-- section 3 start-->
    <section id="home_feature_3" class="home_feature_3">
        <div class="home_feature_3_board">
            <span style="height: 50px; display: block;"></span>
            <div class="home_feature_3_1">
                <div class="home_feature_3_1_text">
                    <a class="home_feature_3_1_text_title"><?php _e("So, Why AITeamUP?") ?></a>
                    <span style="height: 50px; display: block;"></span>
                    <div style="color: #4d5256; font-size: 20px; font-family: sans-serif !important; line-height: 26px; font-weight: 500;">
                        <a style="font-weight: bold; color: black !important;"><?php _e("Let's face it") ?></a> <?php _e("— In today's fast-paced digital landscape, capturing attention is paramount. Imagine AITeamUP as your creative ally, streamlining tasks and unleashing content brilliance.") ?>
                    </div>
                    <span style="height: 20px; display: block;"></span>
                    <!-- <div style="color: rgb(29, 46, 59); font-size: 20px;">
                        <a style="font-weight: bold; color: black !important;">That's Where AITeamUP Comes In.</a>
                        <br>
                        <a>It's your shortcut to efficiency, guiding your business to peak productivity. AITeamUP, where innovation meets seamless workflow.</a>
                    </div> -->

                    <div style="color: #4d5256; font-size: 20px; font-family: sans-serif !important; line-height: 26px; font-weight: 500;">
                        <a style="font-weight: bold; color: black !important;"><?php _e("That’s Where AITeamUP Comes In.") ?></a>
                        <br>
                        <?php _e("It's your shortcut to efficiency, guiding your business to peak productivity. AITeamUP, where innovation meets seamless workflow.") ?>
                    </div>

                </div>
                <img class="home_feature_3_1_img" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f3Brand1'] ?>">
            </div>
            <div class="home_feature_3_2">
                <hi class="home_feature_3_2_title"><b><?php _e("The Power of Content, Unlocked by AITeamUP, is Your Secret to Online Success.") ?></b></hi>
                <span style="height: 20px; display: block;"></span>
                <h2 class="home_feature_3_2_content"><?php _e("It’s the distinction between those who struggle to monetize and the rare few who do—thanks to the transformative power of") ?>
                    <b><?php _e("AITeamUP in content creation & Automation!") ?></b>
                </h2>
                <span style="height: 40px; display: block;"></span>
                <div class="home_feature_3_2_cart">
                    <div class="home_feature_3_2_cart_pan">
                        <span style="height: 30px; display: block;"></span>
                        <h1><b style="color: rgb(29, 46, 59); font-size: 32px; font-family: sans-serif;"><?php _e("Without AITeamUP") ?></b></h1>
                        <span style="height: 40px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px;" class="fa-fw fas fa-times-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("Manual Content Creation") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px;" class="fa-fw fas fa-times-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;">​<?php _e("Limited Output") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px;" class="fa-fw fas fa-times-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("Resource Drain") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px;" class="fa-fw fas fa-times-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("​​Missed Opportunities") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                    </div>
                    <div class="home_feature_3_2_cart_pan">
                        <span style="height: 30px; display: block;"></span>
                        <h1><b style="color: rgb(29, 46, 59); font-size: 32px; font-family: sans-serif;"><?php _e("With AITeamUP") ?></b></h1>
                        <span style="height: 40px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px; color: rgb(228, 59, 44) !important;" class="fa-fw fas fa-check-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("Automated Content Creation") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px; color: rgb(228, 59, 44) !important;" class="fa-fw fas fa-check-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("Scalable Output") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px; color: rgb(228, 59, 44) !important;" class="fa-fw fas fa-check-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("Resource Optimization") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                        <div class="home_feature_3_2_cart_pan_item">
                            <i style="margin-left: 10px; color: rgb(228, 59, 44) !important;" class="fa-fw fas fa-check-circle"></i>
                            <a style="color: rgb(77, 82, 86) !important; margin-left: 10px; font-size: 20px; font-weight: 700; font-family: sans-serif;"><?php _e("Seizing Opportunities") ?></a>
                        </div>
                        <span style="height: 15px; display: block;"></span>
                    </div>
                </div>
            </div>
            <div class="home_feature_3_3">
                <h2><b style="font-family: Caveat; font-size: 36px; line-height: normal; font-weight: bold;"><?php _e("Do you enjoy the feeling of happiness?") ?></b></h2>
                <span style="height: 15px; display: block;"></span>
                <hi class="home_feature_3_2_title"><b><?php _e("Then Sign Up For AITeamUP Free Trial Today!") ?></b></hi>
                <span style="height: 15px; display: block;"></span>
                <h2 style="font-family: sans-serif; text-align: center; font-size: 19px; color: rgb(77, 82, 86);"><?php _e("Turbocharge Your Business with AITeamUP! Beyond tools, we're your AI-driven team, streamlining processes to achieve your goals faster. Elevate your impact, unlock market potential effortlessly!") ?></h2>
                <span style="height: 40px; display: block;"></span>

                <div class="home_feature_1_free_trial_btn">
                    <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                    <br>
                    <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                </div>
                <div style="height: 5px;"></div>
                <a><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                <div style="height: 25px;"></div>
            </div>
        </div>
    </section>

    <!-- section 4 start -->
    <section id="home_feature_4">
        <div class="home_feature_4">
            <div class="home_feature_4_board">
                <span style="height: 30px; display: block;"></span>
                <h1 class="home_feature_4_title">
                    <b class="home_feature_4_title_text"><?php _e("Maximize Your Earnings with AITeamUP, Efficient, Cost-Effective Solutions to Replace Freelancers, Social Media Agency and Inefficient Staff. Gain Control and Boost Productivity Now!") ?></b>
                </h1>
                <span style="height: 20px; display: block;"></span>
                <h2 class="home_feature_4_content"><?php _e("Meet Your New AITeamUp, The Ultimate AI Partner Ready to Deliver Tailored Growth Solutions for Your Business, Whenever You Need!") ?></h2>
                <span style="height: 30px; display: block;"></span>
                <div class="home_feature_4_bots">
                    <?php $count = 0; ?>
                    <?php foreach ($ai_chat_bots as $bot) { ?>
                        <div class="home_feature_4_bot <?php echo ($count >= 8) ? 'hidden' : ''; ?>">
                            <img style="max-width: 80%; border-radius: 5px;" src="<?php echo _esc($config['site_url'], 0) . 'storage/bot_images/' . $bot['image']; ?>">
                            <span style="height: 20px; display: block;"></span>
                            <h1><?php echo $bot['name'] ?></h1>
                            <span style="height: 10px; display: block;"></span>
                            <div><?php echo $bot['role'] ?></div>
                            <span style="height: 10px; display: block;"></span>
                            <div class="home_feature_4_bot_chat_btn">
                                <i class="fa_prepended far fa-comment"></i>
                                <span style="width: 10px; display: block;"></span>
                                <a style="color: white !important;" href="<?php echo $config['site_url'] . "signup" ?>"><?php _e("Chat Now") ?></a>
                            </div>
                        </div>
                        <?php $count++; ?>
                    <?php } ?>
                </div>
                <span style="height: 50px; display: block;"></span>
                <div id="showMoreBtn" class="home_feature_4_show_more_btn">
                    <i class="fa_prepended fas fa-angle-double-down"></i>
                    <span style="width: 10px; display: block;"></span>
                    <a class="home_feature_4_show_more_btn_text"><?php _e("Show More") ?></a>
                </div>
                <span style="height: 50px; display: block;"></span>
            </div>
        </div>
    </section>

    <!-- section 5 start -->
    <section id="home_feature_5">
        <div class="home_feature_5">
            <div class="home_feature_5_board">
                <span style="height: 30px; display: block;"></span>
                <h1 class="home_feature_4_title">
                    <b><?php _e("Multi-Language Support") ?></b>
                </h1>
                <span style="height: 20px; display: block;"></span>
                <h2 class="home_feature_4_content"><?php _e("AITeamUP is capable of generating content in over 50 languages, including English, Japanese, and Chinese. This feature helps to break down language barriers for users worldwide.") ?></h2>
                <span style="height: 20px; display: block; border-bottom: dotted 1px rgba(47, 47, 47, 0.15);"></span>
                <span style="height: 20px; display: block;"></span>
                <h2 class="home_feature_5_content">🇿🇦 🇪🇬 🇩🇿 🇸🇩 🇮🇶 🇲🇦 🇸🇦 🇾🇪 🇸🇾 🇹🇳 🇸🇴 🇹🇩 🇦🇪 🇯🇴 🇱🇾 🇱🇧 🇵🇸 🇴🇲 🇲🇷 🇰🇼 🇶🇦 🇧🇭 🇩🇯 🇰🇲 🇪🇭 🇧🇬 🇦🇩 🇨🇿 🏴󠁧󠁢󠁷 🇩🇰 🇩🇪 🇦🇹 🇨🇭 🇱🇺 🇱🇮 🇬🇷 🇨🇾 🇮🇳 🇺🇸 🇵🇰 🇬🇧 🇨🇦 🇦🇺 🇭🇰 🇳🇿 🇫🇯 🇸🇧 🇲🇹 🇻🇺 🇼🇸 🇬🇺 🇻🇨 🇬🇩 🇻🇮 🇫🇲 🇯🇪 🇬🇮 🇳🇫 🇲🇸 🇫🇰 🇨🇽 🇲🇽 🇨🇴 🇪🇸 🇦🇷 🇵🇪 🇻🇪 🇨🇱 🇬🇹 🇪🇨 🇧🇴 🇨🇺 🇩🇴 🇭🇳 🇵🇾 🇸🇻 🇳🇮 🇨🇷 🇵🇦 🇺🇾 🇬🇶 🇵🇷 🇪🇪 🇮🇷 🇦🇫 🇹🇯 🇫🇮 🇨🇩 🇫🇷 🇲🇬 🇨🇲 🇨🇮 🇳🇪 🇧🇫 🇲🇱 🇸🇳 🇹🇩 🇬🇳 🇷🇼 🇧🇪 🇧🇮 🇧🇯 🇭🇹 🇹🇬 🇨🇫 🇨🇬 🇬🇦 🇬🇶 🇰🇲 🇲🇨 🇼🇫 🇧🇱 🇵🇲 🇮🇱 🇷🇸 🇭🇷 🇧🇦 🇽🇰 🇲🇪 🇭🇺 🇮🇩 🇮🇸 🇮🇹 🇸🇲 🇻🇦 🇯🇵 🇰🇭 🇰🇷 🇰🇵 🇱🇹 🇱🇻 🇲🇳 🇳🇴 🇳🇱 🇸🇷 🇨🇼 🇦🇼 🇸🇽 🇵🇱 🇧🇷 🇦🇴 🇲🇿 🇵🇹 🇬🇼 🇹🇱 🇲🇴 🇨🇻 🇸🇹 🇷🇴 🇲🇩 🇷🇺 🇸🇰 🇸🇮 🇸🇪 🇦🇽 🇹🇭 🇹🇷 🇺🇦 🇻🇳 🇨🇳 🇹🇼 🇸🇬</h2>
                <span style="height: 40px; display: block;"></span>
                <div class="home_feature_5_detail">
                    <div class="home_feature_5_detail_1">
                        <div class="home_feature_5_detail_title"><?php _e("New Content") ?></div>
                        <span style="height: 20px; display: block;"></span>
                        <div class="home_feature_5_detail_content"><?php _e("You can choose from 50+ languages to generate your content.") ?></div>
                    </div>
                    <span style="width: 100px; display: block;"></span>
                    <span style="height: 20px; display: block;"></span>
                    <div class="home_feature_5_detail_1">
                        <div class="home_feature_5_detail_title"><?php _e("Rewrite") ?></div>
                        <span style="height: 20px; display: block;"></span>
                        <div style="font-size: 18px !important; line-height: 26px !important;" class="home_feature_5_detail_content"><?php _e("AITeamUP will automatically detect the language of your audio content and can rewrite it either in the same language or in any other language of your choice.") ?></div>
                    </div>
                    <span style="width: 100px; display: block;"></span>
                    <span style="height: 20px; display: block;"></span>
                    <div class="home_feature_5_detail_1">
                        <div class="home_feature_5_detail_title"><?php _e("Generate Images") ?></div>
                        <span style="height: 20px; display: block;"></span>
                        <div class="home_feature_5_detail_content"><?php _e("You can provide prompts in any of the supported languages.") ?></div>
                    </div>
                </div>
            </div>
            <span style="display: block; height: 50px; width: 100%; border-bottom: solid 1px rgba(47, 47, 47, 0.1);"></span>
        </div>
    </section>

    <!-- section 5 start -->
    <section id="home_feature_6">
        <div class="home_feature_5">
            <span style="display: block; height: 100px;"></span>
            <div class="home_feature_5_board">
                <div class="home_feature_6_1">
                    <img style="height: 400px; margin-top: auto; margin-bottom: auto; margin-left: auto; margin-right: auto; display: block;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f5Brand1'] ?>">
                    <div class="home_feature_6_1_text">
                        <h1 style="text-align: left !important; width: 100% !important; font-family: sans-serif !important; font-size: 32px !important; line-height: 42px !important; color: #1d2e3b !important;" class="home_feature_4_title">
                            <b><?php _e('AITeamUP "Channels" Engaging Content to Your Audience!') ?></b>
                        </h1>
                        <span style="display: block; height: 10px;"></span>
                        <div class="home_feature_6_1_content">
                            <u><?php _e("We’re the pioneers in content optimization") ?></u> <?php _e(", offering the first-ever platform adept at captivating <b>audiences from major platforms like Facebook, Google, YouTube, Instagram, and TikTok.</b> Our expertise shines in grabbing potential clients' attention with AI-driven <b>'Scroll Stopping'</b> content, strategically designed to direct them seamlessly to your funnel!") ?>
                        </div>
                        <span style="display: block; height: 10px;"></span>
                        <div class="home_feature_6_1_content"><?php _e("If you’ve ever engaged with content on these sites and found yourself deeply interested in what you're reading or watching, chances are —&nbsp;<b>it's likely that AI had a hand in it. Skilled creators frequently utilize AI to elevate their content, making it more engaging and impactful. So, the next time you find yourself absorbed in such content, remember, there's a good chance it's been enhanced by the power of AI!") ?></b></div>
                        <span style="display: block; height: 10px;"></span>
                        <div class="home_feature_6_1_content"><?php _e("And here’s an exciting fact: You’re experiencing AITeamUP's magic right now! <b>(Yes, our AI content really do make a difference!)") ?></b><br>
                            <div><br></div>
                            <div><br></div>
                        </div>
                    </div>
                </div>
                <span style="display: block; height: 20px;"></span>
                <div class="home_feature_1-title">
                    <a style="font-family: Caveat, Helvetica, sans-serif !important ; font-weight: 700 !important;"><?php _e("But That's Not All AI TeamUP Does...") ?></a>
                </div>
                <span style="display: block; height: 20px;"></span>
                <h1 class="home_feature_4_title">
                    <b><?php _e("AITeamUP Your All-in-One AI Platform for Marketing, Lead Generation, Sales Enhancement, and Revolutionizing Customer Engagement.") ?></b>
                </h1>
                <span style="display: block; height: 20px;"></span>
                <h2 class="home_feature_6_2_title">
                    <b><?php _e("Your All-Encompassing Hub of AI-Driven Business Solutions!") ?></b>
                </h2>
                <span style="display: block; height: 20px;"></span>
                <img style="height: 40px; display: block; margin-left: auto; margin-right: auto;" src="https://statics.myclickfunnels.com/image/28933/file/a60267b5312f1c9335dd5a42beab474d.svg">
                <span style="display: block; height: 30px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 class="home_feature_6_cart_title"><?php _e("Your Personal Copywriter") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 30px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Transforming your content strategy from a time-consuming task to an inspiring session of creativity. What used to take days of meticulous writing and editing can now be achieved before your coffee cools!") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, you unlock...") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);" class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab">
                            <li speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i> <a><?php _e("Unparalleled content creation speed, fueling more engagements and conversions.") ?></a>
                            </li>
                            <span style="height: 10px; display: block;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("A fully adaptable content generator that molds to your unique brand voice, no technical expertise required.") ?>
                            </li>
                            <span style="height: 10px; display: block;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("A vast library of high-converting content templates at your fingertips, ready to captivate your audience.") ?>
                            </li>
                        </ul>

                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 21px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Conventional content creation with AI-driven efficiency, offering scalable AI solutions for businesses of any size, and replaces the traditional writing process with a user-friendly generator, complemented by a vast library of high-conversion templates.") ?></div>
                        </div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart1'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                </div>
                <span style="display: block; height: 40px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component display_block_toggle">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart2'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="color: rgb(29, 46, 59); font-weight: 700; text-align: left; font-size: 42px; font-family: sans-serif; line-height: 1.3em;"><?php _e("Your Creative Content Architect") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 40px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Craft compelling narratives with ease and let every word count. Say goodbye to content that doesn’t perform and hello to stories that capture hearts and minds.") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, you experience...") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);" class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab">
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Seamless Content Creation that turns your ideas into polished, ready-to-publish pieces in moments.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Enhanced Readability that ensures your content is not only engaging but also easy to understand and enjoy.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Style & Tone Customization to match your brand voice and resonate deeply with your audience.") ?>
                            </li>
                        </ul>
                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 22px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Outdated writing methods with a cutting-edge AI that refines raw ideas into captivating content, traditional content creation with seamless, intuitive workflows, and static style guides with dynamic customization tailored to your brand’s unique voice.") ?></div>
                        </div>
                    </div>
                    <div class="home_feature_6_cart_component display_none_toggle">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart2'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                </div>
                <span style="display: block; height: 40px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="color: rgb(29, 46, 59); font-weight: 700; text-align: left; font-size: 42px; font-family: sans-serif; line-height: 1.3em;"><?php _e("Your Audio-to-Content Catalyst") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 40px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Unleash the power of your spoken words and transform them into compelling written content with ease. Gone are the days of manually transcribing hours of audio or video—AI TeamUP does it for you in moments.") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, you have the power to...") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab" style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);">
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Convert your webinars, podcasts, and videos into text with our cutting-edge speech-to-text technology.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Transform transcriptions into engaging social media content, blog posts, and articles, amplifying your reach and revenue.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Repurpose your audiovisual assets into a plethora of content formats, tapping into new audiences and engagement opportunities.") ?>
                            </li>
                        </ul>
                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 22px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Traditional transcription with advanced AI speech-to-text, seamlessly converting spoken words into versatile, engaging written content for wider audience reach and increased interaction.") ?></div>
                        </div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart3'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                </div>
                <span style="display: block; height: 40px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component display_block_toggle">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart4'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="color: rgb(29, 46, 59); font-weight: 700; text-align: left; font-size: 42px; font-family: sans-serif; line-height: 1.3em;"><?php _e("Your Guardian of Ethical and Secure AI") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 40px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Step into the future of AI with confidence. With AITeamUP, embrace the power of artificial intelligence without compromising on security, ethics, or reliability.") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, you benefit from...") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab" style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);">
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Incident-Proof Security: Our robust security measures shield your business from the vulnerabilities exposed in AI incidents.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Customized AI Models: Tailored AI solutions that fit your unique business needs, enhancing both efficiency and security.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Advanced Data Protection: We prioritize your data's safety with stringent data masking and thorough audits.") ?>
                            </li>
                        </ul>
                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 22px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Reactive security measures with proactive threat mitigation ensure ethical AI practices and provide peace of mind, allowing you to focus on business growth while we secure your AI operations.") ?></div>
                        </div>
                    </div>
                    <div class="home_feature_6_cart_component display_none_toggle">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart4'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                </div>
                <span style="display: block; height: 40px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="color: rgb(29, 46, 59); font-weight: 700; text-align: left; font-size: 42px; font-family: sans-serif; line-height: 1.3em;"><?php _e("Transforming Words into Visual Masterpieces") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 40px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Unlock the full potential of AI-driven imagery with AITeamUP

Transform your ideas into stunning visuals, effortlessly bridging

the gap between imagination and reality.") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, you gain access to...") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab" style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);">
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Advanced AI Image Generation: Create compelling, high-quality images from simple text descriptions.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Visual Content Customization: Tailor images to perfectly match your brand's aesthetic and message.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Infinite Creative Possibilities: Explore an endless array of visual styles and concepts with just a few clicks.") ?>
                            </li>
                        </ul>
                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 22px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Outdated image processes with advanced AI visuals; static audience engagement with dynamic, AI-crafted content; and traditional storytelling with visually compelling AI narratives.") ?></div>
                        </div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart5'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                </div>
                <span style="display: block; height: 40px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component display_block_toggle">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart6'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="color: rgb(29, 46, 59); font-weight: 700; text-align: left; font-size: 42px; font-family: sans-serif; line-height: 1.3em;"><?php _e("Your Mind’s AI Counterpart ") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 40px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Discover UAi Leads, where bespoke AI meets innovative content and lead generation. Tailored to your thinking, this initiative redefines your approach to digital content and business growth.

Increased brand exposure + targeted, relevant content + an engaged audience = customers ready to buy.") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, Reveal UAi's Unique Advantages....") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab" style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);">
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("​The 100x Content System: Amplify your content reach across social platforms with a proven system designed for maximum impact and minimal effort.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("​Supercharge Your Content Output with an AI Version of you.CEOs, Founders, Solopreneurs, Coaches, Consultants, and Course Creators - Maximize Leads, Revenue, and Profits Without the High Costs of a Large Team") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Exponential Lead Growth: Achieve a 10x increase in qualified lead flow, without additional ad spend or content creation burdens.") ?>
                            </li>
                        </ul>
                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 22px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Generic AI tools and manual lead strategies with a Custom-Built AI that understands you, a 100x Content System for expansive reach, and Exponential Lead Growth for amplified business potential.") ?></div>
                        </div>
                    </div>
                    <div class="home_feature_6_cart_component display_none_toggle">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart6'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                </div>
                <span style="display: block; height: 40px;"></span>
                <div class="home_feature_6_cart">
                    <div class="home_feature_6_cart_component">
                        <h2 style="text-align: left !important; width: 100% !important; font-size: 22px !important; color: rgb(228, 59, 44); font-family: sans-serif;">
                            <b><?php _e("AITeamUP Is…") ?></b>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="color: rgb(29, 46, 59); font-weight: 700; text-align: left; font-size: 42px; font-family: sans-serif; line-height: 1.3em;"><?php _e("Your Social Media Game Changer") ?>&nbsp;</b></h1>
                        <span style="display: block; height: 40px;"></span>
                        <h2 class="home_feature_6_1_content" style="font-size: 16px !important; line-height: 21px;">
                            <?php _e("Elevate your content and automate your social media

effortlessly. Embrace boundless creativity and join the

revolution in digital expression with AITeamUP.") ?>
                        </h2>
                        <span style="display: block; height: 20px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("With AITeamUP, you unlock...") ?>
                        </h1>
                        <span style="display: block; height: 15px;"></span>
                        <ul class="ne elBulletList elBulletListNew elBulletList2 listBorder0 noBullet" data-bold="inherit" data-gramm="false" contenteditable="false" speechify-initial-font-family="sans-serif" speechify-initial-font-size="14px" wt-ignore-input="true" data-quillbot-element="zL4F5GbCYdGinHbUQgGab" style="font-size: 16px; text-align: left; line-height: 23px; font-family: sans-serif; color: rgb(77, 82, 86);">
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Effortless Content Elevation: Take your social media presence to new heights with minimal effort.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Revolutionary Automation: Transform the way you manage social media with intelligent, automated solutions.") ?>
                            </li>
                            <span style="display: block; height: 15px;"></span>
                            <li style="font-size: 16px; text-align: left;" speechify-initial-font-family="sans-serif" speechify-initial-font-size="18px">
                                <i contenteditable="false" class="fa-fw fas fa-angle-right" style="color: rgb(228, 59, 44);" speechify-initial-font-family="&quot;Font Awesome 5 Free&quot;" speechify-initial-font-size="18px"></i><?php _e("Limitless Creative Exploration: Break through the barriers of conventional content with AI-driven creativity, opening a world of new possibilities.") ?>
                            </li>
                        </ul>
                        <span style="display: block; height: 15px;"></span>
                        <h1 style="font-weight: bold; color: black; font-family: sans-serif;" class="home_feature_6_1_content">
                            <?php _e("AITeamUP Replaces:") ?>
                        </h1>
                        <div class="de elHeadlineWrapper ui-droppable de-editable" id="tmp_paragraph-36434" data-de-type="headline" data-de-editing="false" data-title="Paragraph" data-ce="true" data-trigger="none" data-animate="fade" data-delay="500" style="margin-top: 10px; outline: none; cursor: pointer; font-size: 16px; text-align: left; line-height: 22px; font-family: sans-serif; color: rgb(77, 82, 86);" data-quillbot-parent="LvjbPjZd0186pp43OMPry" aria-disabled="false">
                            <quillbot-extension-highlights data-element-id="LvjbPjZd0186pp43OMPry" data-element-type="html" style="position: relative; top: 0px; left: 0px; z-index: 1 !important; pointer-events: none;"></quillbot-extension-highlights>
                            <div class="ne elHeadline hsSize1 lh5 elMargin0 elBGStyle0 hsTextShadow0 lh4" data-bold="inherit" style="text-align: left; color: rgb(77, 82, 86);" data-gramm="false" contenteditable="false" wt-ignore-input="true" data-quillbot-element="LvjbPjZd0186pp43OMPry"><?php _e("Manual management with automated, impactful strategies, expanding beyond traditional content boundaries with AI-driven creativity, and streamlining workflows with effortless, cutting-edge automation.") ?></div>
                        </div>
                    </div>
                    <span style="display: block; width: 20px;"></span>
                    <div class="home_feature_6_cart_component">
                        <span style="display: block; height: 50px;"></span>
                        <img style="height: 400px; display: block; margin-left: auto; margin-right: auto;" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart7'] ?>">
                        <div class="home_feature_1_free_trial_btn">
                            <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                            <br>
                            <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                        </div>
                        <div style="height: 5px;"></div>
                        <a style="align-items: center; display: block;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                        <div style="height: 30px;"></div>
                    </div>
                </div>
                <span style="display: block; height: 20px;"></span>
                <img style="height: 40px; display: block; margin-left: auto; margin-right: auto;" src="https://statics.myclickfunnels.com/image/28933/file/a60267b5312f1c9335dd5a42beab474d.svg">
                <span style="display: block; height: 30px;"></span>
                <div id="ait_team_up_section">
                    <h1 class="home_feature_4_title">
                        <b><?php _e("AITeamUP Gives You Everything You Need To Succeed Online") ?></b>
                    </h1>
                </div>
                <span style="display: block; height: 20px;"></span>
                <div class="home_feature_1-title">
                    <a style="font-family: Caveat, Helvetica, sans-serif !important ; font-weight: 700 !important;"><?php _e("Plus UAi Lead Agent To Get Customers To Find You!") ?></a>
                </div>
                <span style="display: block; height: 50px;"></span>
                <div class="home_feature_4_bots">
                    <?php foreach ($all_items as $item) { ?>
                        <div class="home_feature_4_bot">
                            <img style="height: 40px;" src="<?php echo _esc($config['site_url'], 0) . 'storage/logo/' . $item['image']; ?>">
                            <span style="height: 20px; display: block;"></span>
                            <h1 style="text-align: center; font-size: 20px;">
                                <b>
                                    <u><?php _e($item['title'])  ?></u>
                                </b>
                            </h1>
                            <span style="height: 10px; display: block;"></span>
                            <div><?php _e($item['content'])  ?></div>
                        </div>
                    <?php } ?>
                </div>
                <span style="display: block; height: 30px;"></span>
                <h1 style="font-size: 36px !important; font-weight: 700 !important; width: 70% !important; " class="home_feature_4_title">
                    <b><?php _e("Try AITeamUP Today For Free And Witness The Awesomeness For Yourself") ?></b>
                </h1>
                <span style="display: block; height: 20px;"></span>
                <div class="home_feature_1_free_trial_btn">
                    <a href="<?php echo $config['site_url'] . "signup" ?>" class="home_feature_1_free_trial_btn_content1"><?php _e("Start Your Free Trial Today >>") ?><i style="width: 50px !important; height: 50px !important;" class="lni lni-chevron-down-circle"></i></a>
                    <br>
                    <a class="home_feature_1_free_trial_btn_content2"><?php _e("Dive into the future of efficiency— no catch, all the gains.") ?></a>
                </div>
                <div style="height: 5px;"></div>
                <a style="margin-left: auto; margin-right: auto;"><?php _e("Get Started In Less Than 60 Seconds • Cancel Anytime") ?></a>
                <div style="height: 30px;"></div>
            </div>
        </div>
    </section>

    <section id="home_feature_7">
        <div class="home_feature_7">
            <span style="display: block; height: 50px;"></span>
            <div class="home_feature_5_board">
                <div>
                    <h1 class="home_feature_7_title">
                        <b><?php _e("Frequently Asked Questions") ?></b>
                    </h1>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_1" class="home_feature_7_faq" onclick="toggleFaq('h_faq_1_content')">
                    <a><?php _e("Do I have to pay for updates or new features on AI TeamUP?") ?></a>
                </div>
                <div id="h_faq_1_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("No, you don't need to pay for major updates at AI TeamUP. All significant upgrades, designed to enhance the functionality and efficiency of the service you originally signed up for, are provided FREE of charge. This ensures you always have access to the latest advancements on our platform. However, while our core services continue to evolve, we also develop new, premium standalone features that you can choose to add on. These optional features are separate from the core offerings and are meant to provide additional capabilities and enrich your experience. Their availability does not affect the quality or scope of the services you initially subscribed to, and you can opt to use them as per your specific needs and goals.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_2" class="home_feature_7_faq" onclick="toggleFaq('h_faq_2_content')">
                    <a><?php _e("If I'm not satisfied with AI TeamUP, how do I cancel my subscription?") ?></a>
                </div>
                <div id="h_faq_2_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("Cancelling your subscription with AI TeamUP is straightforward and doesn't require any phone calls or emails. Simply log in to your account, go to your account settings, and click on 'Cancel My Account.' We've designed this process to be as hassle-free as possible, allowing you to manage your subscription autonomously. While we're sad to see you go, we understand that our solution might not fit everyone's needs, and we aim to make your experience as smooth as possible, even when you're leaving us. Your feedback is always welcome, as it helps us to continue improving our services.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_3" class="home_feature_7_faq" onclick="toggleFaq('h_faq_3_content')">
                    <a><?php _e("Who owns the data and content, including users, created by subscribers on AI TeamUP?") ?></a>
                </div>
                <div id="h_faq_3_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("You do! At AI TeamUP, we ensure that any content you create or upload, as well as the information regarding your users, is 100% owned by you. Our platform operates under the principle that subscribers retain complete ownership of their intellectual property. We are committed to safeguarding your rights and providing a secure environment where your ownership is respected and protected. Your creations and user data are yours alone, and we uphold this principle at all times.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_4" class="home_feature_7_faq" onclick="toggleFaq('h_faq_4_content')">
                    <a><?php _e("Can AI TeamUP handle high user traffic and data loads?") ?></a>
                </div>
                <div id="h_faq_4_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("Absolutely! AI TeamUP is designed to handle substantial user traffic and large volumes of data efficiently. Regardless of the load, our dependable infrastructure running on cutting-edge technology ensures scalability and reliability, enabling us to meet the needs of our users. We continuously monitor and upgrade our systems to provide seamless performance and uptime, ensuring that your experience with AI TeamUP remains smooth and uninterrupted, even during peak usage times or under heavy data loads.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_5" class="home_feature_7_faq" onclick="toggleFaq('h_faq_5_content')">
                    <a><?php _e("Do I need to install any software to use AI TeamUP?") ?></a>
                </div>
                <div id="h_faq_5_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("No, you don't need to install anything to use AI TeamUP. Our platform is designed to be easily accessible through a web browser, eliminating the need for any downloads or installations. This approach ensures that you can start using our services immediately, without any setup hassles. It also allows for greater flexibility, as you can access AI TeamUP from any device with an internet connection, ensuring convenience and ease of use wherever you are.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_6" class="home_feature_7_faq" onclick="toggleFaq('h_faq_6_content')">
                    <a><?php _e("Can I integrate my favorite autoresponders into AI TeamUP?") ?></a>
                </div>
                <div id="h_faq_6_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("Yes, at AI TeamUP, we understand the importance of having your favorite tools work in harmony. That's why our platform is designed to be compatible with a variety of popular autoresponders. We offer seamless integration capabilities, allowing you to connect your preferred autoresponder services directly with AI TeamUP. This integration enhances your workflow efficiency and ensures a more cohesive experience. If you need any assistance in setting up these integrations or have specific autoresponders in mind, our support team is always ready to help you optimize your experience on our platform.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_7" class="home_feature_7_faq" onclick="toggleFaq('h_faq_7_content')">
                    <a><?php _e("If I cancel my AI TeamUP account, what happens to my data?") ?></a>
                </div>
                <div id="h_faq_7_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("As with most software-as-a-service platforms, including AI TeamUP, canceling your account will render your data inaccessible. However, we provide you with a straightforward process to download your data as CSV files before you finalize the cancellation. This ensures you have the chance to retain all your important information. Once you've downloaded everything you need and proceeded with the cancellation, your data will become inaccessible and will be permanently deleted from our servers as per our data retention policies. It's always a good practice to back up your data before canceling, ensuring you don't lose any vital information.") ?></a>
                </div>
                <span style="display: block; height: 15px;"></span>
                <div id="h_faq_8" class="home_feature_7_faq" onclick="toggleFaq('h_faq_8_content')">
                    <a><?php _e("If I have questions about AI TeamUP, is there someone I can talk to?") ?></a>
                </div>
                <div id="h_faq_8_content" class="home_feature_7_faq_content">
                    <span style="display: block; height: 10px;"></span>
                    <a><?php _e("Yes, absolutely! At AI TeamUP, we pride ourselves on having a first-class support team available 24 hours a day, 7 days a week. Whenever you have questions or need assistance, you can reach out to us simply by clicking on the 'Chat support' link at the bottom of any of our pages. Our dedicated team is always ready to provide you with timely and helpful responses to ensure your experience with AI TeamUP is smooth and satisfying. We're here to assist you around the clock, so don't hesitate to contact us whenever you need it.") ?></a>
                </div>
                <span style="display: block; height: 80px;"></span>

            </div>
        </div>
    </section>

</div>

<!-- Customerly Live Chat Snippet Code -->
<script>
     !function(){var e=window,i=document,t="customerly",n="queue",o="load",r="settings",u=e[t]=e[t]||[];if(u.t){return void u.i("[customerly] SDK already initialized. Snippet included twice.")}u.t=!0;u.loaded=!1;u.o=["event","attribute","update","show","hide","open","close"];u[n]=[];u.i=function(t){e.console&&!u.debug&&console.error&&console.error(t)};u.u=function(e){return function(){var t=Array.prototype.slice.call(arguments);return t.unshift(e),u[n].push(t),u}};u[o]=function(t){u[r]=t||{};if(u.loaded){return void u.i("[customerly] SDK already loaded. Use customerly.update to change settings.")}u.loaded=!0;var e=i.createElement("script");e.type="text/javascript",e.async=!0,e.src="https://messenger.customerly.io/launcher.js";var n=i.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};u.o.forEach(function(t){u[t]=u.u(t)})}();
    
     customerly.load({
           "app_id": "9f46bfc6"
     });
</script>
<!-- End of Customerly Live Chat Snippet Code -->

<script>
    $(document).ready(function() {
        $('#mobile_dropdown_menu').click(function() {
            $(this).toggleClass('icon-menu fa fa-times-circle-o');
            $('#mobile_dropdown_content_board').toggleClass('show');
        });
    });

    function langConvert(langCode) {

        var formData = new FormData();
        formData.append("langCode", langCode);

        let ajaxurl = "<?php echo $config['site_url'] . "php/awwhehgrjy.php" ?>";
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
                    window.location.href = "<?php echo $config['site_url'] ?>" + "?langCode=" + langCode;
                } else {
                    window.location.reload();
                }
            },
        });

    }

    var words = ['<?php _e("TIME") ?>', '<?php _e("MONEY") ?>', '<?php _e("ENERGY") ?>', '<?php _e("HASSLE") ?>', '<?php _e("WORKLOAD") ?>', '<?php _e("STRESS") ?>', '<?php _e("MANPOWER") ?>'],
        part,
        i = 0,
        offset = 0,
        len = words.length,
        forwards = true,
        skip_count = 0,
        skip_delay = 15,
        speed = 70;
    var wordflick = function() {
        setInterval(function() {
            if (forwards) {
                if (offset >= words[i].length) {
                    ++skip_count;
                    if (skip_count == skip_delay) {
                        forwards = false;
                        skip_count = 0;
                    }
                }
            } else {
                if (offset == 0) {
                    forwards = true;
                    i++;
                    offset = 0;
                    if (i >= len) {
                        i = 0;
                    }
                }
            }
            part = words[i].substr(0, offset);
            if (skip_count == 0) {
                if (forwards) {
                    offset++;
                } else {
                    offset--;
                }
            }
            $('.home_feature_1_title_2_trans').text(part);
        }, speed);
    };

    $(document).ready(function() {
        wordflick();
    });
</script>

<?php overall_footer(); ?>
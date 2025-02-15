<?php
overall_header(__("Frequently Asked Questions"));
?>
<?php print_adsense_code('header_bottom'); ?>
<!-- <div id="titlebar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _e("Frequently Asked Questions") ?></h2>
                <span><?php _e("Got Questions? We've Got Answers!") ?></span>
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href='<?php echo $config['site_url'] . 'dashboard' ?>'><?php _e("Dashboard") ?></a></li>
                        <li><?php _e("FAQ") ?></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div> -->

<div class="hompage-header">
    <div class="hompage-header-letter">
        Limited Time:
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
<div class="container">
    <div style="margin-bottom: 230px !important;" class="margin-bottom-50">

        <!-- Accordion -->
        <div class="accordion js-accordion">

            <?php
            $i = 0;
            foreach ($faq as $qa) { ?>
                <!-- Accordion Item -->
                <div class="accordion__item js-accordion-item <?php if ($i == 0) { ?> active <?php } ?>">
                    <div class="accordion-header js-accordion-header"><?php _esc($qa['title']) ?></div>

                    <!-- Accordtion Body -->
                    <div class="accordion-body js-accordion-body">

                        <!-- Accordion Content -->
                        <div class="accordion-body__contents">
                            <?php _esc($qa['content']) ?>
                        </div>

                    </div>
                    <!-- Accordion Body / End -->
                </div>
                <!-- Accordion Item / End -->
            <?php $i++;
            } ?>

        </div>
        <!-- Accordion / End -->
    </div>
    <!-- faq-page -->
</div>
<?php
overall_footer();
?>
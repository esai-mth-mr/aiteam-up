<div class="tab-pane" id="quick_logo_watermark">
    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
        <div class="quick-card card">
            <div class="card-header">
                <h5><?php _e('Logo') ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Favicon upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Favicon') ?>
                                <code>*</code></label>
                            <div class="screenshot">
                                <img style="height: 200px !important;" class="redux-option-image" id="favicon_uploader" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['site_favicon'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="favicon" onchange="readURL(this,'favicon_uploader')">
                            <span class="help-block"><?php _e('Ideal Size 16x16 PX') ?></span>
                        </div>
                    </div>

                    <!-- Site Logo upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Logo') ?><code>*</code></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="image_logo_uploader" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['site_logo'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="file" onchange="readURL(this,'image_logo_uploader')">
                            <span class="help-block"><?php _e('Ideal Size 170x60 PX') ?></span>
                        </div>
                    </div>

                    <!-- Site Logo upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Footer Logo') ?>
                                <code>*</code></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="image_flogo_uploader" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['site_logo_footer'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="footer_logo" onchange="readURL(this,'image_flogo_uploader')">
                            <span class="help-block"><?php _e('Display in the footer') ?></span>
                        </div>
                    </div>

                    <!-- Site Logo upload for Dark theme-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Site Logo for Dark Theme') ?>
                                <code>*</code></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="image_dark_logo_uploader" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['image_dark_logo'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="dark_logo" onchange="readURL(this,'image_dark_logo_uploader')">
                            <span class="help-block"><?php _e('Display in dark theme') ?></span>
                        </div>
                    </div>

                    <!-- Admin Logo upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Admin Logo') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="adminlogo" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['site_admin_logo'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="adminlogo" onchange="readURL(this,'adminlogo')">
                            <span class="help-block"><?php _e('Ideal Size 235x62 PX') ?></span>
                        </div>
                    </div>

                    <!-- Partner Banner upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Partner Banner') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="banner" src="<?php _esc($config['site_url']); ?>/storage/banner/<?php echo $config['home_banner'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="banner" onchange="readURL(this,'banner')">
                            <span class="help-block"><?php _e('Display in partner program') ?></span>
                        </div>
                    </div>

                    <!-- Daily Live Tech Calls Banner upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Daily Live Tech Calls Banner') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="daily_live_tech_calls_img" src="<?php _esc($config['site_url']); ?>/storage/banner/<?php echo $config['daily_live_tech_calls_img'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="daily_live_tech_calls_img" onchange="readURL(this,'daily_live_tech_calls_img')">
                            <span class="help-block"><?php _e('Display in Daily Live Tech Calls') ?></span>
                        </div>
                    </div>

                    <!-- AI Ployees Marketplace Banner upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('AI Ployees Marketplace Banner') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="ai_ployees_marketplace_img" src="<?php _esc($config['site_url']); ?>/storage/banner/<?php echo $config['ai_ployees_marketplace_img'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="ai_ployees_marketplace_img" onchange="readURL(this,'ai_ployees_marketplace_img')">
                            <span class="help-block"><?php _e('Display in AI Ployees Marketplace') ?></span>
                        </div>
                    </div>

                     <!-- Dashboard AITeamUp Labs Banner upload-->
                     <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Dashboard AITeamUp Labs Banner') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="dashboard_labs_banner" src="<?php _esc($config['site_url']); ?>/storage/banner/<?php echo $config['dashboard_labs_banner'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="dashboard_labs_banner" onchange="readURL(this,'dashboard_labs_banner')">
                            <span class="help-block"><?php _e('Display in AI Ployees Marketplace') ?></span>
                        </div>
                    </div>

                    <!-- home_feature_2_brand_image upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 2 Brand Image') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f2BrandImg" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f2BrandImg'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f2BrandImg" onchange="readURL(this,'f2BrandImg')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- pricing_brand_logo upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Pricing Brand Logo') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="pricingBrandLogo" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['pricingBrandLogo'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="pricingBrandLogo" onchange="readURL(this,'pricingBrandLogo')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feedbackAvatar_1 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feedback Avatar 1') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="feedbackAvatar_1" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['feedbackAvatar_1'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="feedbackAvatar_1" onchange="readURL(this,'feedbackAvatar_1')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feedbackAvatar_2 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feedback Avatar 2') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="feedbackAvatar_2" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['feedbackAvatar_2'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="feedbackAvatar_2" onchange="readURL(this,'feedbackAvatar_2')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feedbackAvatar_3 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feedback Avatar 3') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="feedbackAvatar_3" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['feedbackAvatar_3'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="feedbackAvatar_3" onchange="readURL(this,'feedbackAvatar_3')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_1_brand_1 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 1 Image 1') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f1Brand1" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f1Brand1'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f1Brand1" onchange="readURL(this,'f1Brand1')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_1_brand_2 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 1 Image 2') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f1Brand2" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f1Brand2'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f1Brand2" onchange="readURL(this,'f1Brand2')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_1_brand_3 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 1 Image 3') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f1Brand3" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f1Brand3'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f1Brand3" onchange="readURL(this,'f1Brand3')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_1_brand_4 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 1 Image 4') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f1Brand4" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f1Brand4'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f1Brand4" onchange="readURL(this,'f1Brand4')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_1_brand_5 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 1 Image 5') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f1Brand5" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f1Brand5'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f1Brand5" onchange="readURL(this,'f1Brand5')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>
                    <!-- feature_1_brand_6 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 1 Image 6') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f1Brand6" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f1Brand6'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f1Brand6" onchange="readURL(this,'f1Brand6')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_3_brand_1 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 3 Image 1') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f3Brand1" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f3Brand1'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f3Brand1" onchange="readURL(this,'f3Brand1')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_5_brand_1 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 5 Image 1') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f5Brand1" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f5Brand1'] ?>" alt="" target="_blank" rel="external">
                            </div>
                            <input class="form-control input-sm" type="file" name="f5Brand1" onchange="readURL(this,'f5Brand1')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_6_cart_1 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 1') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart1" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart1'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart1" onchange="readURL(this,'f6Cart1')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>
                    <!-- feature_6_cart_2 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 2') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart2" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart2'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart2" onchange="readURL(this,'f6Cart2')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_6_cart_3 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 3') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart3" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart3'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart3" onchange="readURL(this,'f6Cart3')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_6_cart_4 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 4') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart4" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart4'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart4" onchange="readURL(this,'f6Cart4')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_6_cart_5 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 5') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart5" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart5'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart5" onchange="readURL(this,'f6Cart5')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_6_cart_6 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 6') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart6" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart6'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart6" onchange="readURL(this,'f6Cart6')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_6_cart_7 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 6 Cart Image 7') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f6Cart7" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f6Cart7'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f6Cart7" onchange="readURL(this,'f6Cart7')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_1 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 1') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon1" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon1'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon1" onchange="readURL(this,'f7Icon1')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_2 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 2') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon2" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon2'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon2" onchange="readURL(this,'f7Icon2')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_3 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 3') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon3" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon3'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon3" onchange="readURL(this,'f7Icon3')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_4 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 4') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon4" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon4'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon4" onchange="readURL(this,'f7Icon4')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_5 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 5') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon5" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon5'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon5" onchange="readURL(this,'f7Icon5')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_6 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 6') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon6" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon6'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon6" onchange="readURL(this,'f7Icon6')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_7 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 7') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon7" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon7'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon7" onchange="readURL(this,'f7Icon7')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_8 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 8') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon8" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon8'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon8" onchange="readURL(this,'f7Icon8')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_9 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 9') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon9" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon9'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon9" onchange="readURL(this,'f7Icon9')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_10 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 10') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon10" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon10'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon10" onchange="readURL(this,'f7Icon10')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_11 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 11') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon11" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon11'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon11" onchange="readURL(this,'f7Icon11')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_12 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 12') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon12" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon12'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon12" onchange="readURL(this,'f7Icon12')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_13 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 13') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon13" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon13'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon13" onchange="readURL(this,'f7Icon13')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_14 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 14') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon14" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon14'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon14" onchange="readURL(this,'f7Icon14')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_15 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 15') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon15" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon15'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon15" onchange="readURL(this,'f7Icon15')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_16 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 16') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon16" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon16'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon16" onchange="readURL(this,'f7Icon16')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_17 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 17') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon17" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon17'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon17" onchange="readURL(this,'f7Icon17')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_18 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 18') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon18" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon18'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon18" onchange="readURL(this,'f7Icon18')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_19 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 19') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon19" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon19'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon19" onchange="readURL(this,'f7Icon19')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_20 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 20') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon20" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon20'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon20" onchange="readURL(this,'f7Icon20')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_21 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 21') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon21" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon21'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon21" onchange="readURL(this,'f7Icon21')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_22 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 22') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon22" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon22'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon22" onchange="readURL(this,'f7Icon22')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_23 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 23') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon23" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon23'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon23" onchange="readURL(this,'f7Icon23')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_24 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 24') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon24" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon24'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon24" onchange="readURL(this,'f7Icon24')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_25 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 25') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon25" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon25'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon25" onchange="readURL(this,'f7Icon25')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_26 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 26') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon26" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon26'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon26" onchange="readURL(this,'f7Icon26')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_27 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 27') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon27" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon27'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon27" onchange="readURL(this,'f7Icon27')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                    <!-- feature_7_icon_28 upload-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label"><?php _e('Feature 7 Icon 28') ?></label>
                            <div class="screenshot"><img style="height: 200px !important;" class="redux-option-image" id="f7Icon28" src="<?php _esc($config['site_url']); ?>/storage/logo/<?php echo $config['f7Icon28'] ?>">
                            </div>
                            <input class="form-control input-sm" type="file" name="f7Icon28" onchange="readURL(this,'f7Icon28')">
                            <span class="help-block"><?php _e('Display in website') ?></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <input type="hidden" name="logo_watermark" value="1">
                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
            </div>
        </div>
    </form>
</div>
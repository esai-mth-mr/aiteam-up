<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/support.css">

<!-- Modal -->
<div class="modal fade" id="embed-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ticket-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col">
                    <h3 class="page-title mb-2">
                        Generate Embed Code for Your UAi Lead Agent.
                    </h3>
                </div>
            </div>
            <div class="modal-body ticket-modal-body">
                <div class="form-body ticket-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Name"); ?> <a style="color: red;">*</a></div>
                                <input type="text" class="form-control ticket-input" id="embed-name" placeholder="Name your UAi" value="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Website URL (optional)"); ?></div>
                                <input type="text" class="form-control ticket-input" id="embed-website" placeholder="https://example.com" value="">
                            </div>
                        </div>

                        <!-- <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Autoresponder (optional)"); ?></div>
                                <select class="form-select" id="embed-autoresponder" required="">
                                    <option value="Autoresponder" selected="">Select Autoresponder.</option>
                                </select>
                            </div>
                        </div> -->

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Enable Lead Collection: (Select Autoresponders)"); ?></div>
                                <select class="form-select" id="embed-autoresponder-list" required="">
                                    <option value="None" selected>None</option>
                                    <option value="MailChimp">MailChimp</option>
                                    <option value="Active Campaign">Active Campaign</option>
                                    <option value="SendLane">SendLane</option>
                                    <option value="GetResponse">GetResponse</option>
                                    <option value="iContact">iContact</option>
                                    <option value="ConstantContact">ConstantContact</option>
                                    <!-- <option value="Kalaviya">Kalaviya</option> -->
                                    <option value="Drip">Drip</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label" style="font-weight: bold;"><?php _e("Click on the “Face” button below and select an image that represents your UAi Lead Agent. Image formats JPG, PNG, or SVG"); ?><a style="color: red;">*</a></div>
                                <div class="col-md-12 embed-image-wraper" id="upload-embed-icon">
                                    <label for="embed_image" class="upload_img_wrapper show-select-uai-image" style="height: 60px; display: flex; justify-content: center; align-items: center; cursor: pointer;">
                                        <img id="uploaded-image" src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px;">
                                    </label>
                                    <input name="embed_image" id="embed_image" type="file" class="with-border small-input" accept=".png, .jpg, .jpeg" style="display: none;" onchange="displayEmbedSelectedImage()">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">

                            <div class="form-label" style="font-weight: bold;"><?php _e("UAi Chatbot Message Color."); ?></div>
                            <div class="form-label" style="font-weight: bold;"><?php _e("Choose a distinct color for messages answered by UAi."); ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class=""><?php _e('UAi Message color:') ?></label>
                                <div>
                                    <input id="uai_font_letter_color" type="color" class="form-control" value="#ffffff">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class=""><?php _e('UAi message board color:') ?></label>
                                <div>
                                    <input id="uai_board_color" type="color" class="form-control" value="#E43B2C">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class=""><?php _e('User message color:') ?></label>
                                <div>
                                    <input id="user_font_letter_color" type="color" class="form-control" value="#111111">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class=""><?php _e('User message board color:') ?></label>
                                <div>
                                    <input id="user_board_color" type="color" class="form-control" value="#ffffff">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-label" style="font-weight: bold;"><?php _e("Horizental Alignment."); ?></div>
                            <div class="form-label" style="font-weight: bold;"><?php _e("Choose the Horizental Alignment to position your UAi chat on either the left or right side of the screen. (Default : right)"); ?></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="embed_horizental unselected-horizental left-horizental"><i class="icon-feather-arrow-left-circle"></i><a style="margin-left: 30px;">Left</a></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="embed_horizental selected-horizental right-horizental"><a style="margin-right: 30px;">Right</a><i class="icon-feather-arrow-right-circle"></i></div>
                        </div>

                        <div style="margin-top: 20px;" class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Your Terms of Condition url"); ?><a style="color: red;">*</a></div>
                                <input type="text" class="form-control ticket-input" id="embed_terms" placeholder="https://example.com">
                            </div>
                        </div>

                        <div style="margin-top: 20px;" class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Chat Button Text"); ?></div>
                                <input type="text" class="form-control ticket-input" id="embed_start_chat_text"  value="Start Chatting">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class=""><?php _e('Chat Button Background Color:') ?></label>
                                <div>
                                    <input id="embed_start_chat_btn_background_color" type="color" class="form-control" value="#E43B2C">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-label" style="font-weight: bold;"><?php _e("Watermark."); ?></div>
                        </div>

                        <div class="form-toggle-option col-md-12">
                            <div>
                                <label><?php _e('Powered by AITeamUP.') ?></label>
                            </div>
                            <div>
                                <input type="hidden" value="0">
                                <label class="switch switch-sm">
                                    <input id="brand_toggle" type="checkbox" value="0">
                                    <span class="switch-state"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <button type="button" class="btn btn-primary w-100 embed-send embed-create-btn" style="background-color: #E43B2C !important; border : none">Generate Embed</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function displayEmbedSelectedImage() {
        const input = document.getElementById('embed_image');
        const image = document.getElementById('uploaded-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                image.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('brand_toggle').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('brand_toggle').value = '1';
        } else {
            document.getElementById('brand_toggle').value = '0';
        }
    });
</script>
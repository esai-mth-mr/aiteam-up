<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/support.css">

<!-- Modal -->
<div class="modal fade" id="ticket-add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ticket-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col">
                    <div class="page-pretitle">
                        <?php _e("Generate new support request. We will answer as soon as possible.") ?>
                    </div>
                    <h2 class="page-title mb-2">
                        <?php _e("Create New Support Request") ?>
                    </h2>
                </div>
            </div>
            <div class="modal-body ticket-modal-body">
                <div class="form-body ticket-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Support Category"); ?></div>
                                <select class="form-select" id="ticket-create-category" required="">
                                    <option value="General Inquiry" selected=""> <?php _e("General Inquiry") ?> </option>
                                    <option value="Technical Issue"><?php _e("Technical Issue") ?> </option>
                                    <option value="Improvement Idea"> <?php _e("Improvement Idea") ?> </option>
                                    <option value="Feedback"> <?php _e("Feedback") ?> </option>
                                    <option value="Other"> <?php _e("Other") ?> </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Support Priority"); ?></div>
                                <select class="form-select tomselected ts-hidden-accessible" id="ticket-create-priority" required="" tabindex="-1">
                                    <option value="Normal"> <?php _e("Normal") ?></option>
                                    <option value="High"><?php _e("High") ?></option>
                                    <option value="Critical"><?php _e("Critical") ?></option>
                                    <option value="Low" selected=""><?php _e("Low") ?></option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Subject"); ?></div>
                                <input type="text" class="form-control ticket-input" id="ticket-create-title" placeholder="<?php _e("Please enter subject of the support request"); ?>" value="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Message"); ?></div>
                                <textarea id="ticket-create-content" rows="5" cols="30" class="tiny-editor ticket-input ticket-textarea" id="pageContent" placeholder="<?php _e("Please enter your message") ?>"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100 ticket-send ticket-create-btn"><?php _e("Send"); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
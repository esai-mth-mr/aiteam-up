<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/support.css">

<!-- Modal -->
<div class="modal fade" id="ticket-view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ticket-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="page-title mb-2" id="exampleModalLabel">Support Request <a id="ticket-view-id"></a></h2>
            </div>
            <div class="modal-body ticket-modal-body">
                <div class="form-body ticket-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Support Category"); ?></div>
                                <input type="text" class="form-control ticket-view-input" id="ticket-view-category" value="" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Priority"); ?></div>
                                <input type="text" class="form-control ticket-view-input" id="ticket-view-priority" value="" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Subject"); ?></div>
                                <input type="text" class="form-control ticket-view-input" id="ticket-view-title" value="" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Time"); ?></div>
                                <input type="text" class="form-control ticket-view-input" id="ticket-view-time" value="" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-label"><?php _e("Status"); ?></div>
                                <input type="text" class="form-control ticket-view-input" id="ticket-view-status" value="" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-label"><?php _e("Messages"); ?></div>
                            <div id="ticket-view-chat-history"></div>
                            <div class="mb-3">
                                <textarea id="ticket-chat-send" rows="5" class="form-control tiny-editor ticket-view-input ticket-textarea"></textarea>
                            </div>
                            <button id="support-chat-send-btn" class="button ripple-effect">
                                <?php _e('Send') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
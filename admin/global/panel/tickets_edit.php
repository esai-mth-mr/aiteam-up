<?php
require_once '../../includes.php';

$fetchTicket = ORM::for_table($config['db']['pre'] . 'support')->find_one($_GET['id']);
$fetchuser = ORM::for_table($config['db']['pre'] . 'user')->find_one($_GET['user_id']);

$chat_history = ORM::for_table($config['db']['pre'] . 'support_chats')->select('message')->select('author')->where('ticket_id', $_GET['id'])->order_by_asc('id')->find_array();

$fetchusername = $fetchuser['username'];
$fetchuseremail = $fetchuser['email'];

?>
<div class="slidePanel-content">
    <header class="slidePanel-header">
        <div class="slidePanel-overlay-panel">
            <div class="slidePanel-heading">
                <h2><?php _e('Tickets # ' . $fetchTicket['id']) ?></h2>
            </div>
            <div class="slidePanel-actions">
                <button id="post_sidePanel_ticket_data" class="btn-icon btn-primary" title="<?php _e('Save') ?>">
                    <i class="icon-feather-check"></i>
                </button>
                <button class="btn-icon slidePanel-close" title="<?php _e('Close') ?>">
                    <i class="icon-feather-x"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="slidePanel-inner">
        <div id="post_error"></div>
        <form method="post" data-ajax-action="admin_ticket_edit" id="ticketPanel_form">
            <div class="form-body">
                <div class="form-group" style="display: none!important;">
                    <label for="id_ticket_id"><?php _e("ID"); ?></label>
                    <input id="id_ticket_id" type="text" class="form-control" name="ticket_id" value="<?php echo htmlspecialchars($fetchTicket['id']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="id_title"><?php _e("Title"); ?></label>
                    <input id="id_title" type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($fetchTicket['title']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="id_category"><?php _e("Category"); ?></label>
                    <select id="admin_category_option" class="form-control" name="category">
                        <option value="General Inquiry" <?php echo ($fetchTicket['category'] == "General Inquiry") ? "selected" : "" ?>><?php _e("General Inquiry"); ?></option>
                        <option value="Technical Issue" <?php echo ($fetchTicket['category'] == "Technical Issue") ? "selected" : "" ?>><?php _e("Technical Issue"); ?></option>
                        <option value="Improvement Idea" <?php echo ($fetchTicket['category'] == "Improvement Idea") ? "selected" : "" ?>><?php _e("Improvement Idea"); ?></option>
                        <option value="Feedback" <?php echo ($fetchTicket['category'] == "Feedback") ? "selected" : "" ?>><?php _e("Feedback"); ?></option>
                        <option value="Other" <?php echo ($fetchTicket['category'] == "Other") ? "selected" : "" ?>><?php _e("Other"); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_priority"><?php _e("Priority"); ?></label>
                    <select id="admin_priority_option" class="form-control" name="priority">
                        <option value="Low" <?php echo ($fetchTicket['priority'] == "Low") ? "selected" : "" ?>><?php _e("Low"); ?></option>
                        <option value="Normal" <?php echo ($fetchTicket['priority'] == "Normal") ? "selected" : "" ?>><?php _e("Normal"); ?></option>
                        <option value="High" <?php echo ($fetchTicket['priority'] == "High") ? "selected" : "" ?>><?php _e("High"); ?></option>
                        <option value="Critical" <?php echo ($fetchTicket['priority'] == "Critical") ? "selected" : "" ?>><?php _e("Critical"); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_created_at"><?php _e("Created At"); ?></label>
                    <input id="id_created_at" type="text" class="form-control" name="created_at" value="<?php echo htmlspecialchars($fetchTicket['update_at']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="id_fullname"><?php _e("User"); ?></label>
                    <input id="id_fullname" type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($fetchuser['name']); ?>" readonly>
                </div>
                <div>
                    <label for="id_email"><?php _e("Email"); ?></label>
                    <input id="id_email" type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($fetchuser['email']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label><?php _e("Status"); ?></label>
                    <select id="admin_status_option" class="form-control" name="status">
                        <option value="open" <?php echo ($fetchTicket['status'] == "open") ? "selected" : "" ?>><?php _e("Open"); ?></option>
                        <option value="progress" <?php echo ($fetchTicket['status'] == "progress") ? "selected" : "" ?>><?php _e("Progress"); ?></option>
                        <option value="resolved" <?php echo ($fetchTicket['status'] == "resolved") ? "selected" : "" ?>><?php _e("Resolved"); ?></option>
                    </select>
                </div>

                <input type="hidden" name="submit">
            </div>
            <input type="hidden" name="submit">

        </form>
        <div class="form-label"> <label> <?php _e("Messages"); ?></label></div>
        <div id="ticket-view-chat-history-admin">
            <?php
            foreach ($chat_history as $chat) {
                $side = $chat['author'] == "admin" ? "me" : "";

                echo
                    '<div class="message-bubble ' . $side . '">
                        <div class="message-bubble-inner">
                            <div class="message-text">
                                <div class="markdown-body"> ' . $chat['message'] . '</div>
                            </div>
                        </div>
                        <div style="margin-bottom: 10px" class="clearfix"></div>
                    </div>';
            }
            ?>

        </div>
        <div class="mb-3">
            <textarea id="ticket-chat-send" rows="5" class="form-control ticket-view-input ticket-textarea"></textarea>
        </div>
        <button style="width: auto !important;" id="send_ticket_message" class="btn-icon btn-primary" title="<?php _e('Send') ?>" onclick="support_send_message('<?php echo $_GET['id'] ?>')">
            <a>Send</a>
        </button>
    </div>
</div>
<script>
    $('[name="current_plan"]').off().on('change', function() {
        if ($(this).val() == 'free') {
            $('.plan_expiration_date').slideUp();
        } else {
            $('.plan_expiration_date').slideDown();
        }
    }).trigger('change');
</script>
<script src="../assets/plugins/tinymce/tinymce.min.js"></script>
<script src="../assets/js/script.js"></script>
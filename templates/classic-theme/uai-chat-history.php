<?php

overall_header(__("UAi"));
global $config;
$firstIteration = true;
$all = $_GET['all'];

require_once('global/uai-custom-modal.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/jquery.multiselect.css" />
<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <!-- Dashboard Content
        ================================================== -->
    <div class="dashboard-content-container " data-simplebar>
        <div class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <div style="background-color: white; border-radius: 5px;">
                    <a class="d-flex align-items-center" style="font-size: 20px; font-weight: bold;">
                        <?php _e("Chat History : ") ?>
                        <?php echo $agent_name ?>
                    </a>
                </div>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><a href="<?php url("UAI") ?>"><?php _e("UAi") ?></a></li>
                        <li><?php _e("Chat History") ?></li>
                    </ul>
                </nav>
            </div>

            <div class="messages-container margin-top-0">
                <div class="messages-container-inner">
                    <div class="bg-1 nodark-invert">
                        <div class="sorting-tool-bar" data-site-url=<?php echo $config['site_url'] ?> data-selected_start_date=<?php echo $selected_start_date ?> data-selected_end_date=<?php echo $selected_end_date ?> data-first-time=<?php echo $first_time ?> data-last-time=<?php echo $last_time ?> data-agent_id=<?php echo $agent_id ?>>
                            <div style="display: flex;">
                                <div class="sorting-chat-history ml-15 mb-20" style="<?php if ($all) echo 'background-color : var(--theme-color-1);' ?>">
                                    <a href="<?php echo $config["site_url"] . "uai/chat-history/?id=$agent_id&all=true" ?>" id="all-chat-history" style="font-size: 15px; font-weight: bold; color: inherit; <?php if ($all) echo 'color: white !important;' ?>">All Chats History</a>
                                </div>
                                <div id="date-picker" class="sorting-calendar ml-15 mb-20 <?php if ($selected_start_date || $selected_end_date) echo "filter-selected" ?>">
                                    <i class="icon-feather-calendar" style="<?php if ($selected_start_date || $selected_end_date) echo 'color :white;' ?>"></i>
                                    <a id="date-picker-text" style='font-size: 15px; font-weight: bold; <?php if ($selected_start_date || $selected_end_date) echo "color :white" ?>'></a>
                                    <div class="date-list" id="date-list" style="color: inherit !important;">
                                        <!-- Date list items will be generated dynamically using JavaScript -->
                                    </div>
                                </div>
                                <div class="sorting-newest ml-15 mb-20">
                                    <i class="icon-feather-arrow-up"></i>
                                    <a style="font-size: 15px; font-weight: bold;">Sort by Newest.</a>
                                </div>
                            </div>

                            <div class="action-bar mb-20 mr-10">
                                <a href="#" class="button ripple-effect btn-sm mr-10" id="chat_history_export_to_word" data-tippy-placement="top" title="<?php _e("Export as Word Document") ?>"><i class="fa fa-file-word-o"></i></a>
                                <a href="#" class="button ripple-effect btn-sm mr-10" id="chat_history_export_to_txt" title="<?php _e("Export as Text File") ?>" data-tippy-placement="top"><i class="fa fa-file-text-o"></i></a>
                                <a href="#" style="display: none;" class="button ripple-effect btn-sm" id="chat_history_delete" title="<?php _e("Delete Embed Chat") ?>" data-tippy-placement="top"><i class="fa fa-trash-o"></i></a>
                            </div>
                        </div>

                        <div class="cis-main-wrapper">
                            <div class="cis-left-wrap" style="padding: 0px;">
                                <?php if (count($group_chats) > 0) {
                                    foreach ($group_chats as $index => $chat) { ?>
                                        <div class="embed_list_item <?php echo $firstIteration ? 'embed_selected' : ''; ?>">
                                            <div class="embed_list_item_tool_bar">
                                                <div style="display: flex; height: 15px;">
                                                    <input type="checkbox" class="quick-check ml-10" data-embed_id=<?php echo $index ?>>
                                                    <i class="icon-feather-map-pin ml-10"></i>
                                                </div>
                                                <div><a class="mr-10"><?php echo $chat['chat_history'][0]['time_diff'] ?></a></div>
                                            </div>
                                            <div style="width: 100%; height: 40%; align-items: center;">
                                                <a class="ml-10"><?php echo substr($chat['last_user_message'], 0, 25) . "..."; ?></a>
                                            </div>
                                        </div>
                                    <?php $firstIteration = false;
                                    } ?>
                                <?php } else { ?>
                                    <div class="embed_list_item" style="display: flex; align-items: center; justify-content: center;"><a> No UAi Agent Chat Yet </a></div>
                                <?php }  ?>

                            </div>
                            <div class="cis-right-wrap">
                                <?php if (count($group_chats[$first_embed_id]['chat_history']) > 0) {
                                    $chat_histories = array_reverse($group_chats[$first_embed_id]['chat_history']);
                                    foreach ($chat_histories as $chat_history) { ?>
                                        <div style="display: flex; flex-direction: row-reverse;">
                                            <div class="mr-15 mt-15" style="width: fit-content; max-width: 80%; background-color: #367df2; color: white; padding: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-left-radius: 10px;">
                                                <a><?php echo $chat_history['user_message'] ?></a>
                                            </div>
                                        </div>
                                        <div class="message-container" data-chat-id="<?php echo $chat_history['chat_id'] ?>">
                                            <div class="mt-15 ml-15" style="width: fit-content; max-width: 80%; background-color: #e9e9e9; padding: 10px; border-top-left-radius: 15px; border-top-right-radius: 15px; border-bottom-right-radius: 15px;">
                                                <a class="ai-message"><?php echo $chat_history['ai_message'] ?></a>
                                                <textarea type="text" class="ai-message-input" style="display: none;"></textarea>
                                            </div>
                                            <a class="revise-answer-btn mt-15 ml-15" style="color: #367df2; margin-top: 5px; font-size: 12px; cursor: pointer;">Incorrect? Revise answer.</a>
                                        </div>
                                <?php }
                                } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="root_path" id="root_path" value="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>">

<?php ob_start() ?>

<script src="<?php _esc($config['site_url'] . $config['admin_folder']); ?>/assets/plugins/tinymce/tinymce.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL); ?>/js/uai.js?ver=<?php _esc($config['version']); ?>"></script>

<script>
    tinymce.init({
        selector: '.tiny-editor',
        min_height: 300,
        resize: false,
        plugins: 'advlist lists table autolink link wordcount fullscreen autoresize',
        toolbar: [
            "blocks | bold italic underline strikethrough | alignleft aligncenter alignright  | link blockquote",
            "undo redo | removeformat | table | bullist numlist | outdent indent"
        ],
        menubar: "",
        // link
        relative_urls: false,
        link_assume_external_targets: true,
        content_style: 'body { font-size:14px }'
    });

    $(document).ready(function() {
        setTimeout(function() {
            $(document).find('.tox.tox-tinymce').addClass('nodark-invert')
        }, 100);
    })
</script>

<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';

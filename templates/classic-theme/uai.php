<?php

overall_header(__("UAi"));

require_once('global/uai-custom-modal.php');
include_once TEMPLATE_PATH . '/global/embed-modal.php';
include_once TEMPLATE_PATH . '/global/embed-generate-modal.php';

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/jquery.multiselect.css" />
<!-- Dashboard Container -->
<div class="dashboard-container uai-action" data-chat-history-url=<?php echo $config['site_url'] . 'uai/chat-history/?id=' ?>>
    <?php include_once TEMPLATE_PATH . '/dashboard_sidebar.php'; ?>
    <!-- Dashboard Content
        ================================================== -->
    <div class="dashboard-content-container " data-simplebar>
        <div style="padding: 0px; padding-left: 50px;" class="dashboard-content-inner">
            <?php print_adsense_code('header_bottom'); ?>
            <!-- Dashboard Headline -->
            <div class="dashboard-headline dashboar_headline_temp">
                <h3 class="d-flex align-items-center">
                    <?php _e("Build Your Own UAi Lead Agent") ?>
                    <div class="word-used-wrapper margin-left-10">
                        <i class="icon-feather-bar-chart-2"></i>
                        <?php echo '<i id="quick-images-left">' .
                            _esc(number_format((float)$total_uai_agent_used), 0) . '</i> / ' .
                            ($uai_agent_limit == -1
                                ? __('Unlimited')
                                : _esc(number_format($uai_agent_limit + get_user_option($_SESSION['user']['id'], 'total_uai_agent_limit', 0)), 0)); ?>
                        <strong><?php _e('UAi Agent Used'); ?></strong>
                    </div>
                </h3>

                <div style="background-color: <?php echo get_option("uai_quick_board_color") ?>;" class="uai_quick_guide_board">
                    <div style="color: <?php echo get_option("uai_quick_letter_color") ?>;" class="uai_quick_guide_board_text">
                        <div class="uai_quick_guide_board_text_title">
                            <a><?php _e(get_option("uai_quick_guide_title")) ?></a>
                        </div>
                        <div class="uai_quick_guide_board_text_content">
                            <a><?php _e(get_option("uai_quick_guide_text")) ?>
                            </a>
                        </div>
                        <div class="uai_quick_guide_board_text_actions">
                            <!-- <div class="uai_quick_guide_board_text_actions_gotit">
                                <a><?php _e("Got it") ?></a>
                            </div> -->
                            <div class="uai_quick_guide_board_text_actions_learnmore">
                                <a style="color: <?php echo get_option("uai_quick_url_color") ?>;" href="<?php echo $uai_learn_more_link ?>" target="_blank"><i class="icon-feather-help-circle"></i> <?php _e("Learn more") ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="uai_quick_guide_board_video">
                        <div class="uai_quick_guide_board_video_main">
                            <iframe src="<?php echo $uai_quick_video_link ?>" style=" width: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <!-- Breadcrumbs -->
                <!-- <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("UAi") ?></li>
                    </ul>
                </nav> -->
            </div>

            <div class="messages-container margin-top-0">
                <div class="messages-container-inner">
                    <div class="bg-1 nodark-invert">
                        <div class="cis-main-wrapper">
                            <div class="cis-left-wrap">
                                <button class="gradient-btn-1 create_uai_agent_btn" href="javascript:void(0)"><i class="fa-solid fa-robot mr-5"></i> <?php echo _e('Create UAi Agent') ?> </button>
                                <div class="search-dir-box">
                                    <input placeholder="<?php echo _e('Search training directory') ?>" <?php if (count($directories) > 0) { ?> id="search_training_directory" <?php } ?> />
                                    <!-- <button href="javascript:void(0)" class="btn-blue create_uai_folder_btn" >
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"></path><path d="M13.5 10a.5.5 0 0 1 .5.5V12h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V13h-1.5a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z"></path></svg>
                                    </button> -->
                                </div>
                                <hr class="cis-line my-20" />

                                <div id="directories-list">
                                    <?php if (count($directories) > 0) {
                                        foreach ($directories as $dir) { ?>
                                            <a href="javascript:void(0)" class="show_training_document" id="uai-agent-<?php _esc($dir->id); ?>" data-id="<?php _esc($dir->id); ?>" data-text="<?php echo _esc($dir->agent_name); ?>" data-aichatbot="<?php echo _esc($dir->ai_chat_bot_id); ?>">
                                                <div class="icon-strip-box">
                                                    <div class="isb-left-wrap" id="folder-text-name">
                                                        <!-- <i class="fa-regular fa-folder mr-5"></i> <?php //echo _esc($dir->agent_name); 
                                                                                                        ?><span class="ml-5" id="charsCount_<?php //_esc($dir->id); 
                                                                                                                                            ?>">(<?php //if($dir->word_count > 0) { echo $dir->word_count; } else { echo 0; }  
                                                                                                                                                                                        ?>)</span> -->
                                                        <i class="fa-regular fa-folder mr-5"></i> <?php echo _esc($dir->agent_name); ?><span class="ml-5" id="charsCount_<?php _esc($dir->id); ?>"></span>
                                                    </div>
                                                    <!-- <div class="isb-right-wrap"><span id="listCount_<?php //_esc($dir->id); 
                                                                                                            ?>"><?php //if($dir->doc_count > 0) { echo $dir->doc_count; } else { echo 0; }  
                                                                                                                ?></span>&nbsp;<i class="fa-solid fa-trash-can ml-5 delete_uai_folder"></i></div> -->
                                                    <div class="isb-right-wrap" style="margin-left: 50%;">
                                                        <i class="fa-solid icon-feather-corner-right-up chathistory_uai_agent_btn embed_uai_bot_btn" data-tippy-placement="top" data-tippy data-original-title="Embed" title="Embed" data-bot_id="<?php echo _esc($dir->ai_chat_bot_id); ?>"></i>&nbsp;                                                        
                                                        <i onclick="chat_history_func( <?php echo $dir->id ?>)" class="fa-solid fa-history chathistory_uai_agent_btn" data-tippy-placement="top" data-tippy data-original-title="Chat History" title="Chat History"></i>
                                                        <i class="fa-solid fa-pencil edit_uai_agent_btn" data-tippy-placement="top" data-tippy data-original-title="Edit" title="Edit"></i>&nbsp;
                                                        <i class="fa-solid fa-trash-can delete_uai_agent" data-tippy-placement="top" data-tippy data-original-title="Delete" title="Delete"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php }
                                    } else { ?>
                                        <a href="javascript:void(0)">
                                            <div class="d-block icon-strip-box text-center">
                                                <div class="isb-left-wrap"> <?php echo _e('No UAi Agent Created Yet') ?> </div>
                                            </div>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="cis-right-wrap">
                                <!-- CREATE TRAINING DIRECTORY START -->
                                <div class="uai_section_content folder_training_document d-none">
                                    <div class="header-wrap">
                                        <h2 class="header" id="uai_agent_name"></h2>
                                    </div>
                                    <div class="cis-mw-body">
                                        <form method="post" enctype="multipart/form-data" id="training-document-form">
                                            <input type="hidden" name="agent_id" id="agent_id">
                                            <input type="hidden" name="agent_chatbot_id" id="agent_chatbot_id">

                                            <h2 class="header-2">Create training Documents</h2>
                                            <div class="cols-3-row">
                                                <div class="col-4">
                                                    <div class="action-card active training-doc-action" data-section="write">
                                                        <div class="ac-icon"><i class="fa-solid fa-pen-nib"></i></div>
                                                        <h2 class="ac-title"> Write </h2>
                                                        <div class="ac-disc"> Write Training Document </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="action-card training-doc-action" data-section="upload">
                                                        <div class="ac-icon"><i class="fa-solid fa-upload"></i></div>
                                                        <h2 class="ac-title"> Upload </h2>
                                                        <div class="ac-disc"> Upload your document </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="action-card training-doc-action" data-section="import">
                                                        <div class="ac-icon"><i class="fa-solid fa-link"></i></div>
                                                        <h2 class="ac-title"> Import Website </h2>
                                                        <div class="ac-disc"> Import from web URL </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="document_id" id="document_id">
                                            <!-- WRITE TRAINING DOCUMENT SECTION -->
                                            <div class="training-document-section" id="write_document_section">
                                                <div class="form-field-wrap my-30">
                                                    <label class="label">Document Title</label>
                                                    <input type="text" name="doc_title" id="doc_title" class="form-field with-border small-input" placeholder="Title" />
                                                </div>
                                                <h2 class="header-2">Content</h2>
                                                <div class="content-editor-wrap">
                                                    <textarea class="tiny-editor nodark-invert" id="document_content"></textarea>
                                                </div>

                                                <div class="btn-wrap-1 mt-30 mb-30 text-end">
                                                    <button class="gradient-btn-1 create-document-btn" type="button" name="create-document-btn"> Create </button>
                                                </div>
                                            </div>
                                            <!-- WRITE TRAINING DOCUMENT SECTION END -->

                                            <!-- UPLOAD TRAINING DOCUMENT SECTION -->
                                            <div class="training-document-section d-none" id="upload_document_section">
                                                <div class="form-field-wrap my-30 upload-box">
                                                    <div class="upload-box-in">
                                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                                                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"></path>
                                                        </svg>
                                                        <p>
                                                            Click to upload or drag and drop <br>file like image, PDF &amp; Text File. <br>
                                                            <span id="choosen_file"></span>
                                                        </p>
                                                        <input class="cursor-pointer with-border small-input" type="file" name="upload_file" id="upload_file" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx, .odt, .txt">
                                                    </div>
                                                </div>

                                                <div class="btn-wrap-1 mt-30 mb-30 text-end">
                                                    <button class="gradient-btn-1 create-document-btn" type="button"> Upload </button>
                                                </div>
                                            </div>
                                            <!-- UPLOAD TRAINING DOCUMENT SECTION END -->

                                            <!-- IMPORT WEBSITE URL OF TRAINING DOCUMENT SECTION -->
                                            <div class="training-document-section d-none" id="import_document_section">
                                                <div class="form-field-wrap my-30">
                                                    <label class="label">Website URL</label>
                                                    <input type="url" name="website_url" id="website_url" class="form-field with-border small-input" placeholder="Enter Website URL" />
                                                </div>

                                                <div class="btn-wrap-1 mt-30 mb-30 text-end">
                                                    <button class="gradient-btn-1 create-document-btn" type="button"> Import </button>
                                                </div>
                                            </div>
                                            <!-- IMPORT WEBSITE URL OF TRAINING DOCUMENT SECTION END -->
                                        </form>

                                        <h2 class="header-2">Stored Document</h2>
                                        <div class="table-wrap">
                                            <table class="cis-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                        <th>Created on</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="store-document-list">
                                                    <tr>
                                                        <td colspan="4" class="text-center">No document created yet</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- CREATE TRAINING DIRECTORY END -->
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

    function chat_history_func(agentId) {
        let chatHistoryBaseURI = $(".uai-action").data("chat-history-url");
        let redirectURI = chatHistoryBaseURI + agentId + "&all=true";

        window.location.href = redirectURI;
    }

    $(document).ready(function() {
        setTimeout(function() {
            $(document).find('.tox.tox-tinymce').addClass('nodark-invert')
        }, 100);
    })
</script>

<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH . '/overall_footer_dashboard.php';

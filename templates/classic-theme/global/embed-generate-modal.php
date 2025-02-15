<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/support.css">

<!-- Modal -->
<div class="modal fade" id="embed-generate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-embed-id="">
    <div class="modal-dialog ticket-modal" role="document">
        <div class="modal-content" style="overflow: auto; height: 900px;">
            <div class="modal-header">
                <div class="col">
                    <h2 class="page-title mb-2">
                        Copy Paste Embed Code.
                    </h2>
                </div>
            </div>
            <!-- <div class="modal-body ticket-modal-body">
                <div style="width: 80%; height: auto; margin-left: 10%;">
                    <h5>Put this code anywhere in your page's body:</h5>
                    <div class="embed-generate-board">
                        <a class="embed_generate_letter">
                        </a>
                    </div>
                    <div class="copy-code-btn">
                        <a>copy</a>
                    </div>
                </div>
            </div> -->

            <div class="modal-body">
                <div style="width: 80%; height: auto; margin-left: 10%; margin-bottom: 30px;">
                    <h5>Embed Code Instructions</h5>
                    <div><a>"Seamlessly Integrate AI: Your Guide to Embedding"</a></div>
                    <div style="font-size: 14px; width: 99%; text-align: justify;"><a>Place this script tag within the body of your webpage to effortlessly integrate our AI technology. It's a simple copy-and-paste away from enhancing your site's interactive capabilities.</a></div>

                    <div style="background-color: var(--theme-color-0_1);padding: 5px 0px 5px 10px;border-radius: 5px;display: flex;justify-content: space-between;align-items: center; margin-top: 20px;">
                        <a class="embed_generate_letter">https://aiteamup.com</a>
                        <div>
                            <button class="copy-code-btn button red ripple-effect btn-sm" style="background-color: #DC2F75 !important;" title="Copy" data-tippy-placement="top"> <i class="icon-feather-copy"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body" style="margin-top: -40px !important;">
                <div style="width: 80%; height: auto; margin-left: 10%; margin-bottom: 30px;">
                    <h5>Iframe Customization</h5>
                    <div><a>"Tailor Your Iframe: Custom Dimensions"</a></div>
                    <div style="font-size: 14px; width: 99%; text-align: justify;"><a>Adjust the height and width in the provided code snippet to fit the iframe perfectly within your site layout. Customize for a seamless user experience with a minimum height set for optimal display.</a></div>
                    <div style="background-color: var(--theme-color-0_1);padding: 5px 0px 5px 10px;border-radius: 5px;display: flex;justify-content: space-between;align-items: center; margin-top: 20px;">
                        <a class="iframe_script">https://aiteamup.com</a>
                        <div>
                            <button class="iframe_code_copy_btn button red ripple-effect btn-sm" style="background-color: #DC2F75 !important;" title="Copy" data-tippy-placement="top"> <i class="icon-feather-copy"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body" style="margin-top: -40px !important;">
                <div style="width: 80%; height: auto; margin-left: 10%; margin-bottom: 30px;">
                    <h5>Direct Access to UAI Chatbot</h5>
                    <div><a>"Unlock UAI Chatbot: Shareable Access Link"</a></div>
                    <div style="font-size: 14px; width: 99%; text-align: justify;"><a>Use this link to grant immediate access to the UAI chatbot. Sharing this enables others to interact with the AI directly from their browser.</a></div>

                    <div style="background-color: var(--theme-color-0_1);padding: 5px 0px 5px 10px;border-radius: 5px;display: flex;justify-content: space-between;align-items: center; margin-top: 20px;">
                        <a class="embed_link_letter">https://aiteamup.com</a>
                        <button class="stand_alone_link_copy_btn button red ripple-effect btn-sm" style="background-color: #DC2F75 !important;" title="Copy" data-tippy-placement="top"> <i class="icon-feather-copy"></i></button>
                    </div>
                </div>
            </div>

            <div class="modal-body" style="margin-top: -40px !important; margin-bottom: 30px;">
                <div style="width: 80%; height: auto; margin-left: 10%; margin-bottom: 30px;">
                    <h5>Editable Pre-Made Questions</h5>
                    <div><a>"Customizable Queries: Tailored Conversations"</a></div>
                    <div style="font-size: 14px; width: 99%; text-align: justify;"><a>Start with our pre-made question templates to guide your users. Easily modify these prompts to suit the specific needs of your audience and your business.</a></div>
                </div>

                <div class="pre_made_message_pan">
                    <button class="edit_pre_made_questions_btn button red ripple-effect btn-sm" style="background-color: #DC2F75 !important; position: absolute; right: 60px; top: 155px" title="Edit" data-tippy-placement="top"> <i class="icon-feather-edit"></i></button>
                    <div class="pre_made_message_board row">

                    </div>
                </div>
                
                <div class="pre_made_questions_edit_pan" style="display: none;">
                    <input id="pre_made_questions_edit_1" type="text" class="form-control" value="">
                    <input id="pre_made_questions_edit_2" type="text" class="form-control" value="">
                    <input id="pre_made_questions_edit_3" type="text" class="form-control" value="">
                    <input id="pre_made_questions_edit_4" type="text" class="form-control" value="">
                    <div>
                        <button class="edit_pre_made_questions_cancel_btn button red ripple-effect btn-sm" style="background-color: #DC2F75 !important;" title="Cancel" data-tippy-placement="top"> <i class="icon-feather-arrow-left-circle"></i></button>
                        <button class="edit_pre_made_questions_ok_btn button red ripple-effect btn-sm" style="background-color: #DC2F75 !important;" title="OK" data-tippy-placement="top"> <i class="icon-feather-check"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
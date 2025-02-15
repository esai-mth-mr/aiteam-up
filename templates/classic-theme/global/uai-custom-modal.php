<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL); ?>/css/uai-modal.css">

<!-- CREATE UAi Agent START -->
<div class="modal fade" id="create_uai_agent_modal" tabindex="-1" role="dialog" aria-labelledby="create_uai_agent_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered uai-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
               <div class="col text-center">
					<h3 class="page-title mb-2"> <?php echo _e('Customize Your UAi') ?> </h3>
                    <h5><?php echo _e('Create your UAi purpose') ?></h5>
                </div>
            </div>
            <div class="modal-body uai-modal-body">
                <div class="w3-bar w3-border-bottom uai-tab-btn">
                    <button class="gradient-btn-1  uai-general-form uai-consent-btn nodark-invert" data-consent="general"> <?php echo _e('General') ?> </button>
                    <button class="gradient-btn-1  uai-knowledge-form uai-consent-btn nodark-invert" data-consent="knowledge"> <?php echo _e('Knowledge') ?> </button>
                </div>

                <div class="form-body uai-form nodark-invert">
                    <form method="post" enctype="multipart/form-data" id="uai-agent-create-form">
                        <div id="general-form" class="w3-container uai-consent-tab">
                            <div class="col-md-12">
                                <div class="form-field-wrap mb-30">
                                    <label class="label"><?php echo _e('UAi Name') ?>:</label>
                                    <input name="agent_name" id="agent_name" type="text" class="with-border small-input nodark-invert" placeholder="<?php echo _e('UAi Agent Name') ?>" autocomplete="off" />
                                </div>
                                
                                <div class="form-field-wrap mb-30 uai-gender-div">
                                    <label class="label"><?php echo _e('Gender') ?>:</label>
                                    <label for="agent_gender" class="d-flex"><input type="radio" name="agent_gender" id="agent_gender" value="Male" > <?php echo _e('Male') ?> </label>
                                    <label for="agent_gender" class="d-flex"><input type="radio" name="agent_gender" id="agent_gender" value="Female"> <?php echo _e('Female') ?> </label>
                                </div>
                                                        
                                <div class="form-field-wrap mb-30">
                                    <label class="label"><?php echo _e('UAi Agent Role') ?>:</label>
                                    <input name="agent_role" id="agent_role" type="text" class="with-border small-input nodark-invert" placeholder="<?php echo _e('UAi Agent Role') ?>" autocomplete="off" />
                                </div>
                            
                                <div class="form-field-wrap mb-30">
                                    <label class="label"><?php echo _e('Role Description') ?>:</label>
                                    <textarea name="agent_role_desc" id="agent_role_desc" class="with-border small-input nodark-invert" placeholder="<?php echo _e('UAi Agent Role Description') ?>" rows="3" style="resize:none;" ></textarea>
                                </div>

                                <div class="upload-box">
                                    <div class="col-md-8">
                                        <div class="upload-box-in">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"></path>
                                            </svg>
                                            <p>
                                                <?php echo _e('Role DescriptionUAi Agent Role DescriptionClick to upload or drag and drop') ?> <br> <?php echo _e('file like') ?> image, PDF &amp; DOCX. <br>
                                                <span id="choosen_file_name"></span>
                                            </p>
                                            <input name="agent_image" id="agent_image" type="file" class="with-border small-input" accept=".png, .jpg, .jpeg">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="upload_img_wrapper show-select-uai-image" style="height: 160px; display: flex; justify-content: center; align-items: center; padding: 5px;">
                                            <img src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-end mb-30">
                                <button type="button" class="gradient-btn-1 btn btn-primary uai-create-btn uai-consent-btn" data-consent="knowledge" > <?php echo _e('Next') ?> </button>
                            </div>
                        </div>

                        <div id="knowledge-form" class="w3-container uai-consent-tab">
                            <div class="row mb-30">
                                <label class="label col-md-2"> <?php echo _e('Tone') ?>:</label>
                                <div class="col-md-4">
                                    <select name="agent_tone" id="agent_tone" class="with-border small-input nodark-invert"  >
                                        <option value=""> <?php echo _e('Select Tone') ?> </option>
                                        <option value="authoritative"> <?php echo _e('Authoritative') ?> </option>
                                        <option value="clinical"> <?php echo _e('Clinical') ?> </option>
                                        <option value="cold"> <?php echo _e('Cold') ?> </option>
                                        <option value="confident"> <?php echo _e('Confident') ?> </option>
                                        <option value="cynical"> <?php echo _e('Cynical') ?> </option>
                                        <option value="emotional"> <?php echo _e('Emotional') ?> </option>
                                        <option value="empathetic"> <?php echo _e('Empathetic') ?> </option>
                                        <option value="formal"> <?php echo _e('Formal') ?> </option>
                                        <option value="friendly"> <?php echo _e('Friendly') ?> </option>
                                        <option value="humorous"> <?php echo _e('Humorous') ?> </option>
                                        <option value="informal"> <?php echo _e('Informal') ?> </option>
                                        <option value="ironic"> <?php echo _e('Ironic') ?> </option>
                                        <option value="optimistic"> <?php echo _e('Optimistic') ?> </option>
                                        <option value="pessimistic"> <?php echo _e('Pessimistic') ?> </option>
                                        <option value="playful"> <?php echo _e('Playful') ?> </option>
                                        <option value="sarcastic"> <?php echo _e('Sarcastic') ?> </option>
                                        <option value="serious"> <?php echo _e('Serious') ?> </option>
                                        <option value="sympathetic"> <?php echo _e('Sympathetic') ?> </option>
                                        <option value="tentative"> <?php echo _e('Tentative') ?> </option>
                                    </select>
                                </div>

                                <label class="label col-md-2"><?php echo _e('Response Style') ?>:</label>
                                <div class="col-md-4">
                                    <select name="agent_response_style" id="agent_response_style" class="with-border small-input nodark-invert" >
                                        <option value=""> <?php echo _e('Select Response Style') ?> </option>
                                        <option value="Academic"> <?php echo _e('Academic') ?> </option>
                                        <option value="Analytical"> <?php echo _e('Analytical') ?> </option>
                                        <option value="Argumentative"> <?php echo _e('Argumentative') ?> </option>
                                        <option value="Conversational"> <?php echo _e('Conversational') ?> </option>
                                        <option value="Creative"> <?php echo _e('Creative') ?> </option>
                                        <option value="Critical"> <?php echo _e('Critical') ?> </option>
                                        <option value="Descriptive"> <?php echo _e('Descriptive') ?> </option>
                                        <option value="Epigrammatic"> <?php echo _e('Epigrammatic') ?> </option>
                                        <option value="Epistolary"> <?php echo _e('Epistolary') ?> </option>
                                        <option value="Expository"> <?php echo _e('Expository') ?> </option>
                                        <option value="Informative"> <?php echo _e('Informative') ?> </option>
                                        <option value="Instructive"> <?php echo _e('Instructive') ?> </option>
                                        <option value="Journalistic"> <?php echo _e('Journalistic') ?> </option>
                                        <option value="Metaphorical"> <?php echo _e('Metaphorical') ?> </option>
                                        <option value="Narrative"> <?php echo _e('Narrative') ?> </option>
                                        <option value="Persuasive"> <?php echo _e('Persuasive') ?> </option>
                                        <option value="Poetic"> <?php echo _e('Poetic') ?> </option>
                                        <option value="Satirical"> <?php echo _e('Satirical') ?> </option>
                                        <option value="Technical"> <?php echo _e('Technical') ?> </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="cursor-pointer d-flex justify-content-center" style="gap:1em !important;">
                                    <div>
                                        <small style="font-size : 100%" class="form-error" id="form-error"></small>
                                    </div>
                                </label>
                            </div>
                                
                            <!-- <div class="col-md-12">
                                <label class="cursor-pointer d-flex justify-content-center" style="gap:1em !important;">
                                    <input class="rounded w-5 h-5 cursor-pointer check-all-folder" type="checkbox" data-folder-count="<?php //_esc(count($directories)) ?>">
                                    <span style="font-size: 20px;">All folders</span>
                                </label>
                            </div>
                            <hr />
                            <div class="multi_select_folder uai-folder-list">
                                <?php //if(count($directories) > 0) { 
                                //foreach($directories as $dir) { ?>
                                    <div style="margin-bottom: 45px;">
                                        <span style="cursor: pointer; position: relative;" class="uai-folder-span" data-folderId="<?php //_esc($dir->id) ?>"><?php //_esc($dir->folder_name) ?></span>
                                    </div>
                                <?php //} } ?>
                            </div> -->
                            
                            <div class="col-md-12 text-end">
                                <button type="button" class="gradient-btn-1 btn btn-primary uai-create-btn proceed_create_agent" href="javascript:void(0)" > <?php echo _e('Create') ?> </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CREATE UAi Agent END -->

<!-- EDIT UAi Agent START -->
<div class="modal fade" id="edit_uai_agent_modal" tabindex="-1" role="dialog" aria-labelledby="edit_uai_agent_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered uai-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
               <div class="col text-center">
					<h3 class="page-title mb-2"> <?php echo _e('Customize Your UAi') ?> </h3>
                    <h5><?php echo _e('Edit your UAi purpose') ?></h5>
                </div>
            </div>
            <div class="modal-body uai-modal-body">
                <div class="w3-bar w3-border-bottom uai-tab-btn">
                    <button class="edit_uai_general_form uai-general-form edit-uai-consent-btn nodark-invert" data-consent="general"><?php echo _e('General') ?>  </button>
                    <button class="edit_uai_knowledge_form uai-knowledge-form edit-uai-consent-btn nodark-invert" data-consent="knowledge"> <?php echo _e('Knowledge') ?> </button>
                </div>

                <div class="form-body uai-form nodark-invert" style="overflow-x:hidden;overflow-y:auto;">
                    <form method="post" enctype="multipart/form-data" id="uai-agent-update-form">
                        <input type="hidden" name="edit_uai_agent_id" id="edit_uai_agent_id">
                        <input type="hidden" name="edit_uai_agent_img" id="edit_uai_agent_img">
                        <div id="edit-general-form" class="w3-container edit-uai-consent-tab">
                            <div class="col-md-12">
                                <div class="form-field-wrap mb-30">
                                    <label class="label"><?php echo _e('UAi Name') ?>:</label>
                                    <input name="edit_agent_name" id="edit_agent_name" type="text" class="with-border small-input nodark-invert" placeholder="<?php echo _e('UAi Agent Name') ?>" autocomplete="off" />
                                </div>
                                
                                <div class="form-field-wrap mb-30 uai-gender-div d-flex ">
                                    <label class="label"><?php echo _e('Gender') ?>:</label>
                                    <label for="edit_agent_gender" class="d-flex"><input type="radio" name="edit_agent_gender" id="edit_agent_male" class="edit_agent_gender" value="Male" > <?php echo _e('Male') ?> </label>
                                    <label for="edit_agent_gender" class="d-flex"><input type="radio" name="edit_agent_gender" id="edit_agent_female" class="edit_agent_gender" value="Female"> <?php echo _e('Female') ?> </label>
                                </div>
                                                        
                                <div class="form-field-wrap mb-30">
                                    <label class="label"><?php echo _e('UAi Agent Role') ?>:</label>
                                    <input name="edit_agent_role" id="edit_agent_role" type="text" class="with-border small-input nodark-invert" placeholder="<?php echo _e('UAi Agent Role') ?>" autocomplete="off" />
                                </div>
                            
                                <div class="form-field-wrap mb-30">
                                    <label class="label"> <?php echo _e('Role Description') ?>:</label>
                                    <textarea name="edit_agent_role_desc" id="edit_agent_role_desc" class="with-border small-input nodark-invert" placeholder="<?php echo _e('UAi Agent Role Description') ?>" rows="3" style="resize:none;" ></textarea>
                                </div>

                                <div class="upload-box">
                                    <div class="col-md-8">
                                        <div class="upload-box-in">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"></path>
                                            </svg>
                                            <p>
                                                <?php echo _e('Click to upload or drag and drop') ?> <br> <?php echo _e('file like') ?> image, PDF &amp; DOCX. <br>
                                                <span id="choosen_file_name"></span>
                                            </p>
                                            <input name="edit_agent_image" id="edit_agent_image" type="file" class="with-border small-input" accept=".png, .jpg, .jpeg">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="upload_img_wrapper show-select-uai-image" id="show-select-uai-image" style="height: 160px; display: flex; justify-content: center; align-items: center; padding: 5px;">
                                            <img src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-end mb-30">
                                <button type="button" class="gradient-btn-1 btn btn-primary uai-create-btn edit-uai-consent-btn" data-consent="knowledge" > <?php echo _e('Next') ?> </button>
                            </div>
                        </div>

                        <div id="edit-knowledge-form" class="d-none w3-container edit-uai-consent-tab">
                            <div class="row mb-30">
                                <label class="label col-md-2"> <?php echo _e('Tone') ?>:</label>
                                <div class="col-md-4">
                                    <select name="edit_agent_tone" id="edit_agent_tone" class="with-border small-input nodark-invert"  >
                                        <option value=""> <?php echo _e('Select Tone') ?> </option>
                                        <option value="Authoritative"> <?php echo _e('Authoritative') ?> </option>
                                        <option value="Clinical"> <?php echo _e('Clinical') ?> </option>
                                        <option value="Cold"> <?php echo _e('Cold') ?> </option>
                                        <option value="Confident"> <?php echo _e('Confident') ?> </option>
                                        <option value="Cynical"> <?php echo _e('Cynical') ?> </option>
                                        <option value="Emotional"> <?php echo _e('Emotional') ?> </option>
                                        <option value="Empathetic"> <?php echo _e('Empathetic') ?> </option>
                                        <option value="Formal"> <?php echo _e('Formal') ?> </option>
                                        <option value="Friendly"> <?php echo _e('Friendly') ?> </option>
                                        <option value="Humorous"> <?php echo _e('Humorous') ?> </option>
                                        <option value="Informal"> <?php echo _e('Informal') ?> </option>
                                        <option value="Ironic"> <?php echo _e('Ironic') ?> </option>
                                        <option value="Optimistic"> <?php echo _e('Optimistic') ?> </option>
                                        <option value="Pessimistic"> <?php echo _e('Pessimistic') ?> </option>
                                        <option value="Playful"> <?php echo _e('Playful') ?> </option>
                                        <option value="Sarcastic"> <?php echo _e('Sarcastic') ?> </option>
                                        <option value="Serious"> <?php echo _e('Serious') ?> </option>
                                        <option value="Sympathetic"> <?php echo _e('Sympathetic') ?> </option>
                                        <option value="Tentative"> <?php echo _e('Tentative') ?> </option>
                                    </select>
                                </div>

                                <label class="label col-md-2"><?php echo _e('Response Style') ?>:</label>
                                <div class="col-md-4">
                                    <select name="edit_agent_response_style" id="edit_agent_response_style" class="with-border small-input nodark-invert" >
                                        <option value=""> <?php echo _e('Select Response Style') ?> </option>
                                        <option value="Academic"> <?php echo _e('Academic') ?> </option>
                                        <option value="Analytical"> <?php echo _e('Analytical') ?> </option>
                                        <option value="Argumentative"> <?php echo _e('Argumentative') ?> </option>
                                        <option value="Conversational"> <?php echo _e('Conversational') ?> </option>
                                        <option value="Creative"> <?php echo _e('Creative') ?> </option>
                                        <option value="Critical"> <?php echo _e('Critical') ?> </option>
                                        <option value="Descriptive"> <?php echo _e('Descriptive') ?> </option>
                                        <option value="Epigrammatic"> <?php echo _e('Epigrammatic') ?> </option>
                                        <option value="Epistolary"> <?php echo _e('Epistolary') ?> </option>
                                        <option value="Expository"> <?php echo _e('Expository') ?> </option>
                                        <option value="Informative"> <?php echo _e('Informative') ?> </option>
                                        <option value="Instructive"> <?php echo _e('Instructive') ?> </option>
                                        <option value="Journalistic"> <?php echo _e('Journalistic') ?> </option>
                                        <option value="Metaphorical"> <?php echo _e('Metaphorical') ?> </option>
                                        <option value="Narrative"> <?php echo _e('Narrative') ?> </option>
                                        <option value="Persuasive"> <?php echo _e('Persuasive') ?> </option>
                                        <option value="Poetic"> <?php echo _e('Poetic') ?> </option>
                                        <option value="Satirical"> <?php echo _e('Satirical') ?> </option>
                                        <option value="Technical"> <?php echo _e('Technical') ?> </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="cursor-pointer d-flex justify-content-center" style="gap:1em !important;">
                                    <div>
                                        <small class="form-error" id="form-error"></small>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="col-md-12 text-end">
                                <button type="button" class="gradient-btn-1 btn btn-primary w-100 uai-create-btn proceed_update_agent" href="javascript:void(0)" > <?php echo _e('Update') ?> </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- EDIT UAi Agent END -->

<!-- EDIT TRAINING DOCUMENT START -->
<div class="modal fade" id="edit_document_textarea_modal" tabindex="-1" role="dialog" aria-labelledby="edit_document_textarea_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered uai-modal" role="document">
        <div class="modal-content">
            <div class="modal-body uai-modal-body">
                <div class="form-body uai-form nodark-invert">
                    <div class="row">
                        <div class="col-md-12 form-field-wrap">
                            <div class="mb-3">
                                <input type="hidden" name="edit_document_id" id="edit_document_id">
                                <input type="hidden" name="edit_document_type" id="edit_document_type">
                                <input type="hidden" name="edit_ai_chat_bot" id="edit_ai_chat_bot">
                                <textarea class="tiny-editor nodark-invert" id="edit_document_content"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="gradient-btn-1 btn btn-primary w-100 uai-create-btn uai-document-update" style="padding:6px 20px !important;" > <?php echo _e('Update') ?> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- EDIT TRAINING DOCUMENT END -->
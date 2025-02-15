<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('includes.php');

if (!checkloggedadmin()) {
    exit('Access Denied.');
}

//SidePanel Ajax Function
if (isset($_REQUEST['action'])) {
    if (!check_allow()) {
        $status = "error";
        $message = __("permission denied for demo.");
        echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }
    if ($_REQUEST['action'] == "addEditAdmin") {
        addEditAdmin();
    }
    if ($_REQUEST['action'] == "addEditUser") {
        addEditUser();
    }
    if ($_REQUEST['action'] == "addEditSubscriber") {
        addEditSubscriber();
    }
    if ($_REQUEST['action'] == "addEditCurrency") {
        addEditCurrency();
    }
    if ($_REQUEST['action'] == "addEditTimezone") {
        addEditTimezone();
    }
    if ($_REQUEST['action'] == "addEditTestimonial") {
        addEditTestimonial();
    }
    if ($_REQUEST['action'] == "addEditTax") {
        addEditTax();
    }
    if ($_REQUEST['action'] == "addLanguage") {
        addLanguage();
    }
    if ($_REQUEST['action'] == "editLanguage") {
        editLanguage();
    }
    if ($_REQUEST['action'] == "addMembershipPlan") {
        addMembershipPlan();
    }
    if ($_REQUEST['action'] == "editMembershipPlan") {
        editMembershipPlan();
    }
    if ($_REQUEST['action'] == "editPrepaidPlan") {
        editPrepaidPlan();
    }
    // ticket comment and status edit
    if ($_REQUEST['action'] == "admin_ticket_edit") {
        admin_ticket_edit();
    }
    if ($_REQUEST['action'] == "addTax") {
        addTax();
    }
    if ($_REQUEST['action'] == "editTax") {
        editTax();
    }
    if ($_REQUEST['action'] == "addStaticPage") {
        addStaticPage();
    }
    if ($_REQUEST['action'] == "editStaticPage") {
        editStaticPage();
    }
    if ($_REQUEST['action'] == "addFAQentry") {
        addFAQentry();
    }
    if ($_REQUEST['action'] == "editFAQentry") {
        editFAQentry();
    }
    if ($_REQUEST['action'] == "transactionEdit") {
        transactionEdit();
    }
    if ($_REQUEST['action'] == "paymentEdit") {
        paymentEdit();
    }
    if ($_REQUEST['action'] == "addBlogCat") {
        addBlogCat();
    }
    if ($_REQUEST['action'] == "editBlogCat") {
        editBlogCat();
    }
    if ($_REQUEST['action'] == "saveEmailTemplate") {
        saveEmailTemplate();
    }
    if ($_REQUEST['action'] == "getEmailTemplates") {
        getEmailTemplates();
    }
    if ($_REQUEST['action'] == "addEmailTemplateForm") {
        addEmailTemplateForm();
    }
    if ($_REQUEST['action'] == "testEmailTemplate") {
        testEmailTemplate();
    }
    if ($_REQUEST['action'] == "editWithdrawal") {
        editWithdrawal();
    }
    if ($_REQUEST['action'] == "editAdvertise") {
        editAdvertise();
    }

    if ($_REQUEST['action'] == "editAIDocument") {
        editAIDocument();
    }
    if ($_REQUEST['action'] == "editAITemplate") {
        editAITemplate();
    }
    if ($_REQUEST['action'] == "editAICustomTemplate") {
        editAICustomTemplate();
    }
    if ($_REQUEST['action'] == "editAITplCategory") {
        editAITplCategory();
    }
    if ($_REQUEST['action'] == "editAPIKey") {
        editAPIKey();
    }
    if ($_REQUEST['action'] == "editAIChatBot") {
        editAIChatBot();
    }
    if ($_REQUEST['action'] == "editAIChatBotCategory") {
        editAIChatBotCategory();
    }
    if ($_REQUEST['action'] == "editAIChatPrompts") {
        editAIChatPrompts();
    }

    if ($_GET['action'] == "SaveSettings") {
        SaveSettings();
    }

    if ($_REQUEST['action'] == "delete_video") {
        delete_video();
    }

    if ($_GET['action'] == "remove_document") {
        remove_document();
    }
}

function change_config_file_settings($filePath, $newSettings, $lang)
{
    // Update $fileSettings with any new values
    $fileSettings = array_merge($lang, $newSettings);
    // Build the new file as a string
    $newFileStr = "<?php\n";
    foreach ($fileSettings as $name => $val) {
        // Using var_export() allows you to set complex values such as arrays and also
        // ensures types will be correct
        $newFileStr .= '$lang[' . var_export($name, true) . '] = ' . var_export($val, true) . ";\n";
    }
    // Closing tag intentionally omitted, you can add one if you want

    // Write it back to the file
    file_put_contents($filePath, $newFileStr);
}

function addEditAdmin()
{
    global $config;
    $_POST = validate_input($_POST);

    $image = null;
    $error = array();
    $name_length = mb_strlen($_POST['name']);

    if (empty($_POST["name"])) {
        $error[] = __("Enter your full name.");
    } elseif (($name_length < 2) or ($name_length > 41)) {
        $error[] = __("Name must be between 2 and 40 characters long.");
    }

    if (empty($_POST['username'])) {
        $error[] = __('Username is required');
    } elseif (isset($_POST['id'])) {
        $count = ORM::for_table($config['db']['pre'] . 'admins')
            ->where('username', $_POST['username'])
            ->where_not_equal('id', $_POST['id'])
            ->count();
        if ($count) {
            $error[] = __('Username is already available');
        }
    }

    // Check if this is an Email availability
    $_POST["email"] = strtolower($_POST["email"]);

    if (empty($_POST["email"])) {
        $error[] = __("Please enter an email address");
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error[] = __("This is not a valid email address");
    } elseif (isset($_POST['id'])) {
        $count = ORM::for_table($config['db']['pre'] . 'admins')
            ->where('email', $_POST['email'])
            ->where_not_equal('id', $_POST['id'])
            ->count();
        if ($count) {
            $error[] = __("An account already exists with that e-mail address");
        }
    }

    if (empty($error)) {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/profile/";
            $result = quick_file_upload('image', $target_dir);
            if ($result['success']) {
                $image = $result['file_name'];
                resizeImage(100, $target_dir . $image, $target_dir . $image);
                if (!empty($_POST['id'])) {
                    // remove old image
                    $info = ORM::for_table($config['db']['pre'] . 'admins')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if (!empty(trim($info['image'])) && $info['image'] != "default_user.png") {
                        if (file_exists($target_dir . $info['image'])) {
                            unlink($target_dir . $info['image']);
                        }
                    }
                }
            } else {
                $error[] = $result['error'];
            }
        }
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            $admins = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_POST['id']);
            $admins->name = validate_input($_POST['name']);
            $admins->username = validate_input($_POST['username']);
            $admins->email = validate_input($_POST['email']);
            if (!empty($_POST['password'])) {
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 13]);
                $admins->password_hash = $pass_hash;
            }
            if (!empty($image)) {
                $admins->image = $image;
            }
            $admins->save();
        } else {
            $password = $_POST["password"];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

            $admins = ORM::for_table($config['db']['pre'] . 'admins')->create();
            $admins->name = validate_input($_POST['name']);
            $admins->username = validate_input($_POST['username']);
            $admins->email = validate_input($_POST['email']);
            $admins->password_hash = $pass_hash;
            if (!empty($image)) {
                $admins->image = $image;
            }
            $admins->save();
        }

        if ($admins->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditUser()
{
    global $config;
    $error = array();
    $image = null;
    $now = date("Y-m-d H:i:s");
    if (!isset($_POST['user_type'])) {
        $_POST['user_type'] = "user";
    }

    $name_length = mb_strlen(($_POST['name']));

    if (empty($_POST["name"])) {
        $error[] = __("Enter your full name.");
    } elseif (($name_length < 2) or ($name_length > 41)) {
        $error[] = __("Name must be between 2 and 40 characters long.");
    }

    if (empty($_POST['username'])) {
        $error[] = __('Username is required');
    } elseif (!isset($_POST['id'])) {
        $count = check_username_exists($_POST["username"]);
        if ($count) {
            $error[] = __('Username is already available');
        }
    }

    // Check if this is an Email availability
    $_POST["email"] = mb_strtolower($_POST["email"]);

    if (empty($_POST["email"])) {
        $error[] = __("Please enter an email address");
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error[] = __("This is not a valid email address");
    } elseif (!isset($_POST['id'])) {
        $count = check_account_exists($_POST["email"]);
        if ($count) {
            $error[] = __("An account already exists with that e-mail address");
        }
    }

    if (empty($error)) {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/profile/";
            $result = quick_file_upload('image', $target_dir);
            if ($result['success']) {
                $image = $result['file_name'];
                resizeImage(100, $target_dir . $image, $target_dir . $image);
                if (isset($_POST['id'])) {
                    // remove old image
                    $info = ORM::for_table($config['db']['pre'] . 'user')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if (!empty(trim($info['image'])) && $info['image'] != "default_user.png") {
                        if (file_exists($target_dir . $info['image'])) {
                            unlink($target_dir . $info['image']);
                        }
                    }
                }
            } else {
                $error[] = $result['error'];
            }
        }
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            /* Update plan */
            $subsc_check = ORM::for_table($config['db']['pre'] . 'upgrades')
                ->where('user_id', $_POST['id'])
                ->count();
            if ($_POST['current_plan'] != 'free') {
                $expires = strtotime($_POST['plan_expiration_date']);
                if ($subsc_check == 1) {
                    $upgrades = ORM::for_table($config['db']['pre'] . 'upgrades')
                        ->use_id_column('upgrade_id')
                        ->where('user_id', validate_input($_POST['id']))
                        ->find_one();
                    $upgrades->sub_id = validate_input($_POST['current_plan']);
                    $upgrades->upgrade_expires = validate_input($expires);
                    $upgrades->save();
                } else {
                    $upgrades_insert = ORM::for_table($config['db']['pre'] . 'upgrades')->create();
                    $upgrades_insert->sub_id = $_POST['current_plan'];
                    $upgrades_insert->user_id = $_POST['id'];
                    $upgrades_insert->upgrade_lasttime = time();
                    $upgrades_insert->upgrade_expires = $expires;
                    $upgrades_insert->status = "Active";
                    $upgrades_insert->save();
                }
            } else {
                ORM::for_table($config['db']['pre'] . 'upgrades')
                    ->where_equal('user_id', $_POST['id'])
                    ->delete_many();
            }

            // reset the plan uses if the plan is changed
            $user_data = get_user_data(null, $_POST['id']);
            if ($user_data['group_id'] != $_POST['current_plan']) {
                update_user_option($_POST['id'], 'total_words_used', 0);
                update_user_option($_POST['id'], 'total_images_used', 0);
                update_user_option($_POST['id'], 'total_speech_used', 0);
                update_user_option($_POST['id'], 'total_text_to_speech_used', 0);

                update_user_option($_POST['id'], 'last_reset_time', time());
            }

            update_user_option($_POST['id'], 'total_words_available', validate_input($_POST['total_words_available']));
            update_user_option($_POST['id'], 'total_images_available', validate_input($_POST['total_images_available']));
            update_user_option($_POST['id'], 'total_speech_available', validate_input($_POST['total_speech_available']));
            update_user_option($_POST['id'], 'total_text_to_speech_available', validate_input($_POST['total_text_to_speech_available']));

            $users = ORM::for_table($config['db']['pre'] . 'user')->find_one($_POST['id']);
            $users->group_id = validate_input($_POST['current_plan']);
            $users->status = validate_input($_POST['status']);
            $users->name = validate_input($_POST['name']);
            $users->username = validate_input($_POST['username']);
            $users->user_type = validate_input($_POST['user_type']);
            $users->email = validate_input($_POST['email']);
            $users->sex = validate_input($_POST['sex']);
            $users->description = validate_input($_POST['about'], true);
            $users->country = validate_input($_POST['country']);

            if ($_POST['type'] == "test") {
                $users->group_id = 3;
                $users->plan_type = "annually";
                $users->status = 1;
            }

            if (!empty($_POST['password'])) {
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 13]);
                $users->password_hash = $pass_hash;
            }
            if (!empty($image)) {
                $users->image = $image;
            }
            $users->updated_at = $now;
            $users->save();

            /* Update trial done */
            update_user_option($_POST['id'], 'package_trial_done', (int) $_POST['plan_trial_done']);
        } else {
            $password = $_POST["password"];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

            $confirm_id = get_random_id();

            $users = ORM::for_table($config['db']['pre'] . 'user')->create();
            $users->status = '0';
            $users->name = validate_input($_POST['name']);
            $users->username = validate_input($_POST['username']);
            $users->user_type = validate_input($_POST['user_type']);
            $users->email = validate_input($_POST['email']);
            $users->sex = validate_input($_POST['sex']);
            $users->description = validate_input($_POST['about'], true);
            $users->country = validate_input($_POST['country']);
            $users->confirm = $confirm_id;
            $users->referral_key = uniqid(get_random_string(5));
            $users->password_hash = $pass_hash;
            if (!empty($image)) {
                $users->image = $image;
            }
            $users->created_at = $now;
            $users->updated_at = $now;

            if ($_POST['type'] == "test") {
                $users->group_id = 3;
                $users->plan_type = "annually";
                $users->status = 1;
            }

            $users->save();
        }

        if ($users->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditSubscriber()
{
    global $config;
    $_POST = validate_input($_POST);

    if (isset($_POST['id']) && $_POST['id'] != "") {
        $subscriber_update = ORM::for_table($config['db']['pre'] . 'subscriber')->find_one($_POST['id']);
        $subscriber_update->set('email', $_POST['email']);
        $subscriber_update->set('joined', date('Y-m-d'));
        $subscriber_update->save();
    } else {
        /* Save email */
        $subscriber_insert = ORM::for_table($config['db']['pre'] . 'subscriber')->create();
        $subscriber_insert->email = $_POST['email'];
        $subscriber_insert->joined = date('Y-m-d');
        $subscriber_insert->save();
    }

    $result = array('status' => 'success', 'message' => __('Saved Successfully.'));

    echo json_encode($result);
}

function addEditCurrency()
{
    global $config;
    $_POST = validate_input($_POST);
    $in_left = $_POST['in_left'];
    $decimal_places = ($_POST['decimal_places'] != "") ? $_POST['decimal_places'] : 2;
    if (strlen($_POST['code']) > 3) {
        $status = "error";
        $message = __('Currency code max length is 3.');
        echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }

    if (isset($_POST['id'])) {
        $update_currency = ORM::for_table($config['db']['pre'] . 'currencies')->find_one($_POST['id']);
        $update_currency->set('name', $_POST['name']);
        $update_currency->set('code', $_POST['code']);
        $update_currency->set('html_entity', $_POST['html_entity']);
        $update_currency->set('font_arial', $_POST['font_arial']);
        $update_currency->set('font_code2000', $_POST['font_code2000']);
        $update_currency->set('unicode_decimal', $_POST['unicode_decimal']);
        $update_currency->set('unicode_hex', $_POST['unicode_hex']);
        $update_currency->set('decimal_places', $decimal_places);
        $update_currency->set('decimal_separator', $_POST['decimal_separator']);
        $update_currency->set('thousand_separator', $_POST['thousand_separator']);
        $update_currency->set('in_left', $in_left);
        $update_currency->save();
    } else {
        $insert_currency = ORM::for_table($config['db']['pre'] . 'currencies')->create();
        $insert_currency->name = $_POST['name'];
        $insert_currency->code = $_POST['code'];
        $insert_currency->html_entity = $_POST['html_entity'];
        $insert_currency->font_arial = $_POST['font_arial'];
        $insert_currency->font_code2000 = $_POST['font_code2000'];
        $insert_currency->unicode_decimal = $_POST['unicode_decimal'];
        $insert_currency->unicode_hex = $_POST['unicode_hex'];
        $insert_currency->decimal_places = $decimal_places;
        $insert_currency->decimal_separator = $_POST['decimal_separator'];
        $insert_currency->thousand_separator = $_POST['thousand_separator'];
        $insert_currency->in_left = $in_left;
        $insert_currency->save();
    }
    $status = "success";
    $message = __("Saved Successfully");

    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditTimezone()
{
    global $config;

    $_POST = validate_input($_POST);
    if ($_POST['time_zone_id'] == "") {
        $status = "error";
        $message = __('Please fill the required fields.');
        echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }
    $gmt = ($_POST['gmt'] != "") ? $_POST['gmt'] : 0;
    $dst = ($_POST['dst'] != "") ? $_POST['dst'] : 0;
    $raw = ($_POST['raw'] != "") ? $_POST['raw'] : 0;

    if (isset($_POST['id'])) {
        $timezones = ORM::for_table($config['db']['pre'] . 'time_zones')->find_one($_POST['id']);
        $timezones->set('country_code', $_POST['country_code']);
        $timezones->set('time_zone_id', $_POST['time_zone_id']);
        $timezones->set('gmt', $gmt);
        $timezones->set('dst', $dst);
        $timezones->set('raw', $raw);
        $timezones->save();
    } else {
        $timezones = ORM::for_table($config['db']['pre'] . 'time_zones')->create();
        $timezones->country_code = $_POST['country_code'];
        $timezones->time_zone_id = $_POST['time_zone_id'];
        $timezones->gmt = $gmt;
        $timezones->dst = $dst;
        $timezones->raw = $raw;
        $timezones->save();
    }

    if ($timezones->id()) {
        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditTestimonial()
{
    global $config;

    $_POST = validate_input($_POST);

    $title = $_POST['name'];
    $designation = $_POST['designation'];
    $description = $_POST['content'];

    $image = null;
    $error = array();

    if (empty($title)) {
        $error[] = __("Name is required.");
    }
    if (empty($designation)) {
        $error[] = __("Designation is required.");
    }
    if (empty($description)) {
        $error[] = __("Content is required.");
    }

    if (empty($error)) {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/testimonials/";
            $result = quick_file_upload('image', $target_dir);
            if ($result['success']) {
                $image = $result['file_name'];
                resizeImage(100, $target_dir . $image, $target_dir . $image);
                if (isset($_POST['id'])) {
                    // remove old image
                    $info = ORM::for_table($config['db']['pre'] . 'testimonials')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if (!empty(trim($info['image'])) && $info['image'] != "default_user.png") {
                        if (file_exists($target_dir . $info['image'])) {
                            unlink($target_dir . $info['image']);
                        }
                    }
                }
            } else {
                $error[] = $result['error'];
            }
        }
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            $test = ORM::for_table($config['db']['pre'] . 'testimonials')->find_one($_POST['id']);
            $test->name = $title;
            $test->designation = $designation;
            $test->content = $description;
            if ($image) {
                $test->image = $image;
            }
            $test->save();
        } else {
            $test = ORM::for_table($config['db']['pre'] . 'testimonials')->create();
            $test->name = $title;
            $test->designation = $designation;
            $test->image = $image;
            $test->content = $description;
            $test->save();
        }

        $status = "success";
        $message = __("Saved Successfully");

        echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    echo $json;
    die();
}

function addEditTax()
{
    global $config;

    $_POST = validate_input($_POST);

    if (isset($_POST['submit'])) {

        if ($_POST['internal_name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['description'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['value'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        if (isset($_POST['id'])) {
            $taxes = ORM::for_table($config['db']['pre'] . 'taxes')->find_one($_POST['id']);
            $taxes->internal_name = validate_input($_POST['internal_name']);
            $taxes->name = validate_input($_POST['name']);
            $taxes->description = validate_input($_POST['description']);
            $taxes->value = validate_input($_POST['value']);
            $taxes->value_type = validate_input($_POST['value_type']);
            $taxes->type = validate_input($_POST['type']);
            $taxes->billing_type = validate_input($_POST['billing_type']);
            $taxes->countries = isset($_POST['countries']) ? validate_input(implode(',', $_POST['countries'])) : null;
            $taxes->save();
        } else {
            $taxes = ORM::for_table($config['db']['pre'] . 'taxes')->create();
            $taxes->internal_name = validate_input($_POST['internal_name']);
            $taxes->name = validate_input($_POST['name']);
            $taxes->description = validate_input($_POST['description']);
            $taxes->value = validate_input($_POST['value']);
            $taxes->value_type = validate_input($_POST['value_type']);
            $taxes->type = validate_input($_POST['type']);
            $taxes->billing_type = validate_input($_POST['billing_type']);
            $taxes->countries = isset($_POST['countries']) ? validate_input(implode(',', $_POST['countries'])) : null;
            $taxes->datetime = date('Y-m-d H:i:s');
            $taxes->save();
        }

        if ($taxes->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addLanguage()
{
    global $config, $lang;
    $_POST = validate_input($_POST);

    if (isset($_POST['submit'])) {
        if (isset($_POST['name']) && $_POST['name'] != "") {

            $post_langname = str_replace(' ', '', $_POST['name']);
            $post_filename = str_replace(' ', '', strtolower($_POST['file_name']));

            $filePath = '../includes/lang/lang_' . $post_filename . '.php';
            if (!file_exists($filePath)) {

                $source = 'en';
                $target = $_POST['code'];
                $trans = new GoogleTranslate();
                $newLangArray = array();
                foreach ($lang as $key => $value) {
                    if ($_POST['auto_tran'] == 1) {
                        $result = $trans->translate($source, $target, $value);
                        $result = !empty($result) ? $result : $value;
                    } else {
                        $result = $value;
                    }

                    $newLangArray[$key] = $result;
                }
                fopen($filePath, "w");
                change_config_file_settings($filePath, $newLangArray, $lang);

                $insert_language = ORM::for_table($config['db']['pre'] . 'languages')->create();
                $insert_language->code = $_POST['code'];
                $insert_language->name = $post_langname;
                $insert_language->direction = $_POST['direction'];
                $insert_language->file_name = $post_filename;
                $insert_language->active = $_POST['active'];
                $insert_language->save();

                if ($insert_language->id()) {
                    $status = "success";
                    $message = __("Saved Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $message = __("Same language file exists. Change language file name.");
                echo $json = '{"status" : "error","message" : "' . $message . '"}';
                die();
            }
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editLanguage()
{
    global $config;
    $_POST = validate_input($_POST);
    if (isset($_POST['id'])) {

        $update_language = ORM::for_table($config['db']['pre'] . 'languages')->find_one($_POST['id']);
        $update_language->set('code', $_POST['code']);
        $update_language->set('name', $_POST['name']);
        $update_language->set('direction', $_POST['direction']);
        $update_language->set('active', $_POST['active']);
        $update_language->save();

        if ($update_language) {
            $status = "success";
            $message = __("Saved Successfully");
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addMembershipPlan()
{
    global $config, $lang;

    if (isset($_POST['submit'])) {
        $_POST = validate_input($_POST);

        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['monthly_price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['annual_price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['lifetime_price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        $recommended = isset($_POST['recommended']) ? "yes" : "no";
        $active = isset($_POST['active']) ? 1 : 0;

        $_POST['ai_model'] = !empty($_POST['ai_model']) ? $_POST['ai_model'] : get_option('open_ai_model');
        $_POST['ai_chat_model'] = !empty($_POST['ai_chat_model']) ? $_POST['ai_chat_model'] : get_option('open_ai_chat_model');

        $_POST['ai_templates'] = !empty($_POST['ai_templates']) ? $_POST['ai_templates'] : array();
        $_POST['ai_chatbots'] = !empty($_POST['ai_chatbots']) ? $_POST['ai_chatbots'] : array();

        $settings = array(
            'ai_model' => $_POST['ai_model'],
            'ai_templates' => $_POST['ai_templates'],
            'ai_words_limit' => (int) $_POST['ai_words_limit'],
            'ai_images_limit' => (int) $_POST['ai_images_limit'],
            'ai_uai_agent_limit' => (int) $_POST['ai_uai_agent_limit'],
            'ai_voices_limit' => (int) $_POST['ai_voices_limit'],
            'ai_chat' => (int) $_POST['ai_chat'],
            'ai_chatbots' => $_POST['ai_chatbots'],
            'ai_chat_model' => $_POST['ai_chat_model'],
            'ai_code' => (int) $_POST['ai_code'],
            'ai_text_to_speech_limit' => (int) $_POST['ai_text_to_speech_limit'],
            'ai_speech_to_text_limit' => (int) $_POST['ai_speech_to_text_limit'],
            'ai_speech_to_text_file_limit' => (int) $_POST['ai_speech_to_text_file_limit'],
            'analytics' => (int) $_POST['analytics'],
            'show_ads' => (int) $_POST['show_ads'],
            'live_chat' => (int) $_POST['live_chat'],
            'custom' => array()
        );

        $plan_custom = ORM::for_table($config['db']['pre'] . 'plan_options')
            ->where('active', 1)
            ->order_by_asc('position')
            ->find_many();
        foreach ($plan_custom as $custom) {
            if (!empty($custom['title']) && trim($custom['title']) != '' && !empty($_POST['custom_' . $custom['id']])) {
                $settings['custom'][$custom['id']] = 1;
            }
        }

        $insert_subscription = ORM::for_table($config['db']['pre'] . 'plans')->create();
        $insert_subscription->name = validate_input($_POST['name']);
        $insert_subscription->badge = $_POST['badge'];
        $insert_subscription->monthly_price = $_POST['monthly_price'];
        $insert_subscription->annual_price = $_POST['annual_price'];
        $insert_subscription->lifetime_price = $_POST['lifetime_price'];
        $insert_subscription->settings = json_encode($settings);
        $insert_subscription->taxes_ids = isset($_POST['taxes']) ? validate_input(implode(',', $_POST['taxes'])) : null;
        $insert_subscription->status = $active;
        $insert_subscription->recommended = $recommended;
        $insert_subscription->date = date('Y-m-d H:i:s');
        $insert_subscription->save();

        if ($insert_subscription->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editMembershipPlan()
{
    global $config, $lang;

    if (isset($_POST['submit'])) {
        $_POST = validate_input($_POST);

        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        $active = $_POST['active'] ? 1 : 0;

        $_POST['ai_model'] = !empty($_POST['ai_model']) ? $_POST['ai_model'] : get_option('open_ai_model');
        $_POST['ai_chat_model'] = !empty($_POST['ai_chat_model']) ? $_POST['ai_chat_model'] : get_option('open_ai_chat_model');

        $_POST['ai_templates'] = !empty($_POST['ai_templates']) ? $_POST['ai_templates'] : array();
        $_POST['ai_chatbots'] = !empty($_POST['ai_chatbots']) ? $_POST['ai_chatbots'] : array();
        $userPermissions = !empty($_POST['user_permissions']) ? $_POST['user_permissions'] : array();

        $settings = array(
            'ai_model' => $_POST['ai_model'],
            'ai_templates' => $_POST['ai_templates'],
            'user_permissions' => $userPermissions,
            'ai_words_limit' => (int) $_POST['ai_words_limit'],
            'ai_images_limit' => (int) $_POST['ai_images_limit'],
            'ai_uai_agent_limit' => (int) $_POST['ai_uai_agent_limit'],
            'ai_voices_limit' => (int) $_POST['ai_voices_limit'],
            'ai_chat' => (int) $_POST['ai_chat'],
            'ai_chatbots' => $_POST['ai_chatbots'],
            'ai_chat_model' => $_POST['ai_chat_model'],
            'ai_code' => (int) $_POST['ai_code'],
            'ai_text_to_speech_limit' => (int) $_POST['ai_text_to_speech_limit'],
            'ai_speech_to_text_limit' => (int) $_POST['ai_speech_to_text_limit'],
            'ai_speech_to_text_file_limit' => (int) $_POST['ai_speech_to_text_file_limit'],
            'analytics' => (int) $_POST['analytics'],
            'show_ads' => (int) $_POST['show_ads'],
            'live_chat' => (int) $_POST['live_chat'],
            'custom' => array()
        );

        $plan_custom = ORM::for_table($config['db']['pre'] . 'plan_options')
            ->where('active', 1)
            ->order_by_asc('position')
            ->find_many();
        foreach ($plan_custom as $custom) {
            if (!empty($custom['title']) && trim($custom['title']) != '' && !empty($_POST['custom_' . $custom['id']])) {
                $settings['custom'][$custom['id']] = 1;
            }
        }

        switch ($_POST['id']) {
            case 'free':
                $plan = json_encode(array(
                    'id' => 'free',
                    'name' => validate_input($_POST['name']),
                    'badge' => $_POST['badge'],
                    'settings' => $settings,
                    'status' => $active
                ), JSON_UNESCAPED_UNICODE);
                update_option_value('free_membership_plan', $plan);
                break;
            case 'trial':
                $plan = json_encode(array(
                    'id' => 'trial',
                    'name' => validate_input($_POST['name']),
                    'badge' => $_POST['badge'],
                    'days' => (int) $_POST['days'],
                    'settings' => $settings,
                    'status' => $active
                ), JSON_UNESCAPED_UNICODE);
                update_option_value('trial_membership_plan', $plan);
                break;
            default:
                if ($_POST['monthly_price'] == "") {
                    echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
                    die();
                }
                if ($_POST['annual_price'] == "") {
                    echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
                    die();
                }
                if ($_POST['lifetime_price'] == "") {
                    echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
                    die();
                }

                $recommended = $_POST['recommended'] ? "yes" : "no";

                $insert_subscription = ORM::for_table($config['db']['pre'] . 'plans')->find_one($_POST['id']);
                $insert_subscription->name = validate_input($_POST['name']);
                $insert_subscription->badge = $_POST['badge'];
                $insert_subscription->monthly_price = $_POST['monthly_price'];
                $insert_subscription->annual_price = $_POST['annual_price'];
                $insert_subscription->lifetime_price = $_POST['lifetime_price'];
                $insert_subscription->settings = json_encode($settings);
                $insert_subscription->taxes_ids = isset($_POST['taxes']) ? validate_input(implode(',', $_POST['taxes'])) : null;
                $insert_subscription->status = $active;
                $insert_subscription->recommended = $recommended;
                $insert_subscription->date = date('Y-m-d H:i:s');
                $insert_subscription->save();
                break;
        }

        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function admin_ticket_edit()
{
    global $config;

    if (isset($_POST['ticket_id'])) {

        $ticket = ORM::for_table($config['db']['pre'] . 'support')->find_one($_POST['ticket_id']);
        $ticket->status = $_POST['status'];
        $ticket->category = $_POST['category'];
        $ticket->priority = $_POST['priority'];

        $ticket->save();

        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editPrepaidPlan()
{
    global $config;

    if (isset($_POST['submit'])) {
        $_POST = validate_input($_POST);

        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        $active = $_POST['active'] ? 1 : 0;

        $settings = array(
            'ai_words_limit' => (int) $_POST['ai_words_limit'],
            'ai_images_limit' => (int) $_POST['ai_images_limit'],
            'ai_text_to_speech_limit' => (int) $_POST['ai_text_to_speech_limit'],
            'ai_speech_to_text_limit' => (int) $_POST['ai_speech_to_text_limit'],
        );

        if ($_POST['price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        $recommended = $_POST['recommended'] ? "yes" : "no";

        if (isset($_POST['id'])) {
            $prepaid_plans = ORM::for_table($config['db']['pre'] . 'prepaid_plans')->find_one($_POST['id']);
        } else {
            $prepaid_plans = ORM::for_table($config['db']['pre'] . 'prepaid_plans')->create();
        }
        $prepaid_plans->name = validate_input($_POST['name']);
        $prepaid_plans->price = $_POST['price'];
        $prepaid_plans->settings = json_encode($settings);
        $prepaid_plans->taxes_ids = isset($_POST['taxes']) ? validate_input(implode(',', $_POST['taxes'])) : null;
        $prepaid_plans->status = $active;
        $prepaid_plans->recommended = $recommended;
        $prepaid_plans->date = date('Y-m-d H:i:s');
        $prepaid_plans->save();

        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addStaticPage()
{
    global $config;
    $error = array();

    if (empty($_POST['name'])) {
        $error[] = __('Name required');
    }
    if (empty($_POST['title'])) {
        $error[] = __('Title required.');
    }
    if (empty($_POST['content'])) {
        $error[] = __('Content required.');
    }
    if (empty($error)) {
        if (empty($_POST['slug']))
            $slug = create_slug($_POST['name']);
        else
            $slug = create_slug($_POST['slug']);

        $insert_page = ORM::for_table($config['db']['pre'] . 'pages')->create();
        $insert_page->translation_lang = 'en';
        $insert_page->name = validate_input($_POST['name']);
        $insert_page->category = validate_input($_POST['category']);
        $insert_page->title = validate_input($_POST['title']);
        $insert_page->content = validate_input($_POST['content'], true, true);
        $insert_page->slug = $slug;
        $insert_page->type = validate_input($_POST['type']);
        $insert_page->active = validate_input($_POST['active']);
        $insert_page->save();

        $id = $insert_page->id();

        $update_page = ORM::for_table($config['db']['pre'] . 'pages')->find_one($id);
        $update_page->set('translation_of', $id);
        $update_page->set('parent_id', $id);
        $update_page->save();

        $rows = ORM::for_table($config['db']['pre'] . 'languages')
            ->select_many('code', 'name')
            ->where('active', '1')
            ->where_not_equal('code', 'en')
            ->find_many();

        foreach ($rows as $fetch) {
            $insert_page = ORM::for_table($config['db']['pre'] . 'pages')->create();
            $insert_page->translation_lang = $fetch['code'];
            $insert_page->translation_of = $id;
            $insert_page->parent_id = $id;
            $insert_page->name = validate_input($_POST['name']);
            $insert_page->title = validate_input($_POST['title']);
            $insert_page->category = validate_input($_POST['category']);
            $insert_page->content = validate_input($_POST['content'], true, true);
            $insert_page->slug = $slug;
            $insert_page->type = validate_input($_POST['type']);
            $insert_page->active = validate_input($_POST['active']);
            $insert_page->save();
        }
        $status = "success";
        $message = __('Saved Successfully');
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    echo $json;
    die();
}

function editStaticPage()
{
    global $config;
    $error = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['name'])) {
            $error[] = __('Name required');
        }
        if (empty($_POST['title'])) {
            $error[] = __('Title required.');
        }
        if (empty($_POST['content'])) {
            $error[] = __('Content required.');
        }
        if (empty($error)) {
            if (empty($_POST['slug']))
                $slug = create_slug($_POST['name']);
            else
                $slug = create_slug($_POST['slug']);

            $update_page = ORM::for_table($config['db']['pre'] . 'pages')->find_one($_POST['id']);
            $update_page->set('name', validate_input($_POST['name']));
            $update_page->set('category', validate_input($_POST['category']));
            $update_page->set('title', validate_input($_POST['title']));
            $update_page->set('content', validate_input($_POST['content'], true, true));
            $update_page->set('slug', $slug);
            $update_page->set('type', validate_input($_POST['type']));
            $update_page->set('active', validate_input($_POST['active']));
            $update_page->save();

            $status = "success";
            $message = __('Saved Successfully');

            echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    echo $json;
    die();
}

function addFAQentry()
{
    global $config;
    $error = array();

    if (empty($_POST['title'])) {
        $error[] = __('Title required.');
    }
    if (empty($_POST['content'])) {
        $error[] = __('Content required.');
    }
    if (empty($error)) {

        $insert_faq = ORM::for_table($config['db']['pre'] . 'faq_entries')->create();
        $insert_faq->translation_lang = 'en';
        $insert_faq->faq_title = validate_input($_POST['title']);
        $insert_faq->faq_content = validate_input($_POST['content'], true);
        $insert_faq->active = $_POST['active'];
        $insert_faq->save();

        $id = $insert_faq->id();

        $faqs = ORM::for_table($config['db']['pre'] . 'faq_entries')
            ->use_id_column('faq_id')
            ->find_one($id);
        $faqs->translation_of = $id;
        $faqs->parent_id = $id;
        $faqs->save();

        $rows = ORM::for_table($config['db']['pre'] . 'languages')
            ->select_many('code', 'name')
            ->where_not_equal('code', 'en')
            ->find_many();

        foreach ($rows as $fetch) {
            $insert_faq = ORM::for_table($config['db']['pre'] . 'faq_entries')->create();
            $insert_faq->translation_lang = $fetch['code'];
            $insert_faq->translation_of = $id;
            $insert_faq->parent_id = $id;
            $insert_faq->faq_title = validate_input($_POST['title']);
            $insert_faq->faq_content = validate_input($_POST['content'], true);
            $insert_faq->active = $_POST['active'];
            $insert_faq->save();
        }
        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editFAQentry()
{
    global $config;
    $error = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['title'])) {
            $error[] = __('Title required.');
        }
        if (empty($_POST['content'])) {
            $error[] = __('Content required.');
        }
        if (empty($error)) {

            $faqs = ORM::for_table($config['db']['pre'] . 'faq_entries')
                ->use_id_column('faq_id')
                ->find_one(validate_input($_POST['id']));
            $faqs->faq_title = validate_input($_POST['title']);
            $faqs->faq_content = validate_input($_POST['content'], true);
            $faqs->active = validate_input($_POST['active']);
            $faqs->save();

            if ($faqs->id()) {
                $status = "success";
                $message = __("Saved Successfully");
            } else {
                $status = "error";
                $message = __("Error: Please try again.");
            }
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function transactionEdit()
{
    global $config;
    if (isset($_POST['id'])) {

        if (isset($_POST['status'])) {

            if ($_POST['status'] == "success") {
                $transaction_id = $_POST['id'];
                transaction_success($transaction_id);
            } else {
                $transaction = ORM::for_table($config['db']['pre'] . 'transaction')->find_one($_POST['id']);
                $transaction->status = $_POST['status'];
                $transaction->save();
            }
            $status = "success";
            $message = __("Saved Successfully");
        } else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function paymentEdit()
{
    global $config;

    if (isset($_POST['id'])) {
        $payment = ORM::for_table($config['db']['pre'] . 'payments')
            ->use_id_column('payment_id')
            ->find_one($_POST['id']);
        $payment->set('payment_title', validate_input($_POST['title']));
        $payment->set('payment_install', validate_input($_POST['install']));
        $payment->save();

        if (isset($_POST['paypal_sandbox_mode'])) {
            update_option_value("paypal_sandbox_mode", isset($_POST['paypal_sandbox_mode']) ? $_POST['paypal_sandbox_mode'] : "");
            update_option_value("paypal_payment_mode", isset($_POST['paypal_payment_mode']) ? $_POST['paypal_payment_mode'] : "");
            update_option_value("paypal_api_client_id", isset($_POST['paypal_api_client_id']) ? $_POST['paypal_api_client_id'] : "");
            update_option_value("paypal_api_secret", isset($_POST['paypal_api_secret']) ? $_POST['paypal_api_secret'] : "");
        }

        if (isset($_POST['stripe_secret_key'])) {
            update_option_value("stripe_payment_mode", $_POST['stripe_payment_mode']);
            update_option_value("stripe_publishable_key", $_POST['stripe_publishable_key']);
            update_option_value("stripe_secret_key", $_POST['stripe_secret_key']);
            update_option_value("stripe_webhook_secret", $_POST['stripe_webhook_secret']);
        }

        if (isset($_POST['paystack_public_key'])) {
            update_option_value("paystack_public_key", $_POST['paystack_public_key']);
            update_option_value("paystack_secret_key", $_POST['paystack_secret_key']);
        }

        if (isset($_POST['payumoney_merchant_key'])) {
            update_option_value("payumoney_sandbox_mode", $_POST['payumoney_sandbox_mode']);
            update_option_value("payumoney_merchant_key", $_POST['payumoney_merchant_key']);
            update_option_value("payumoney_merchant_salt", $_POST['payumoney_merchant_salt']);
            update_option_value("payumoney_merchant_id", $_POST['payumoney_merchant_id']);
        }

        if (isset($_POST['checkout_account_number'])) {
            update_option_value("2checkout_sandbox_mode", $_POST['2checkout_sandbox_mode']);
            update_option_value("checkout_account_number", $_POST['checkout_account_number']);
            update_option_value("checkout_public_key", $_POST['checkout_public_key']);
            update_option_value("checkout_private_key", $_POST['checkout_private_key']);
        }

        if (isset($_POST['company_bank_info'])) {
            update_option_value("company_bank_info", $_POST['company_bank_info']);
        }

        if (isset($_POST['company_cheque_info'])) {
            update_option_value("company_cheque_info", $_POST['company_cheque_info']);
            update_option_value("cheque_payable_to", $_POST['cheque_payable_to']);
        }

        if (isset($_POST['skrill_merchant_id'])) {
            update_option_value("skrill_merchant_id", $_POST['skrill_merchant_id']);
        }

        if (isset($_POST['nochex_merchant_id'])) {
            update_option_value("nochex_merchant_id", $_POST['nochex_merchant_id']);
        }

        if (isset($_POST['CCAVENUE_MERCHANT_KEY'])) {
            update_option_value("CCAVENUE_MERCHANT_KEY", $_POST['CCAVENUE_MERCHANT_KEY']);
            update_option_value("CCAVENUE_ACCESS_CODE", $_POST['CCAVENUE_ACCESS_CODE']);
            update_option_value("CCAVENUE_WORKING_KEY", $_POST['CCAVENUE_WORKING_KEY']);
        }

        if (isset($_POST['PAYTM_ENVIRONMENT'])) {
            update_option_value("PAYTM_ENVIRONMENT", $_POST['PAYTM_ENVIRONMENT']);
            update_option_value("PAYTM_MERCHANT_KEY", $_POST['PAYTM_MERCHANT_KEY']);
            update_option_value("PAYTM_MERCHANT_MID", $_POST['PAYTM_MERCHANT_MID']);
            update_option_value("PAYTM_MERCHANT_WEBSITE", $_POST['PAYTM_MERCHANT_WEBSITE']);
        }

        if (isset($_POST['mollie_api_key'])) {
            update_option_value("mollie_api_key", $_POST['mollie_api_key']);
        }

        if (isset($_POST['iyzico_api_key'])) {
            update_option_value("iyzico_sandbox_mode", $_POST['iyzico_sandbox_mode']);
            update_option_value("iyzico_api_key", $_POST['iyzico_api_key']);
            update_option_value("iyzico_secret_key", $_POST['iyzico_secret_key']);
        }

        if (isset($_POST['midtrans_client_key'])) {
            update_option_value("midtrans_sandbox_mode", $_POST['midtrans_sandbox_mode']);
            update_option_value("midtrans_client_key", $_POST['midtrans_client_key']);
            update_option_value("midtrans_server_key", $_POST['midtrans_server_key']);
        }

        if (isset($_POST['paytabs_profile_id'])) {
            update_option_value("paytabs_profile_id", $_POST['paytabs_profile_id']);
            update_option_value("paytabs_secret_key", $_POST['paytabs_secret_key']);
        }

        if (isset($_POST['telr_store_id'])) {
            update_option_value("telr_sandbox_mode", $_POST['telr_sandbox_mode']);
            update_option_value("telr_store_id", $_POST['telr_store_id']);
            update_option_value("telr_authkey", $_POST['telr_authkey']);
        }

        if (isset($_POST['razorpay_api_key'])) {
            update_option_value("razorpay_api_key", $_POST['razorpay_api_key']);
            update_option_value("razorpay_secret_key", $_POST['razorpay_secret_key']);
        }

        if (isset($_POST['flutterwave_api_key'])) {
            update_option_value("flutterwave_api_key", $_POST['flutterwave_api_key']);
            update_option_value("flutterwave_secret_key", $_POST['flutterwave_secret_key']);
        }

        if (isset($_POST['yoomoney_shop_id'])) {
            update_option_value("yoomoney_shop_id", $_POST['yoomoney_shop_id']);
            update_option_value("yoomoney_secret_key", $_POST['yoomoney_secret_key']);
        }

        if (isset($_POST['coinbase_api_key'])) {
            update_option_value("coinbase_api_key", $_POST['coinbase_api_key']);
            update_option_value("coinbase_webhook_secret", $_POST['coinbase_webhook_secret']);
        }

        if (isset($_POST['mercadopago_access_token'])) {
            update_option_value("mercadopago_access_token", $_POST['mercadopago_access_token']);
        }
        if (isset($_POST['paddle_sandbox_mode'])) {
            update_option_value("paddle_sandbox_mode", $_POST['paddle_sandbox_mode']);
            update_option_value("paddle_vendor_id", $_POST['paddle_vendor_id']);
            update_option_value("paddle_api_key", $_POST['paddle_api_key']);
            update_option_value("paddle_public_key", $_POST['paddle_public_key']);
        }

        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addBlogCat()
{
    global $config;
    $_POST = validate_input($_POST);
    $name = $_POST['title'];
    $slug = $_POST['slug'];
    if (trim($name) != '' && is_string($name)) {
        if (empty($slug))
            $slug = create_blog_cat_slug($name);
        else
            $slug = create_blog_cat_slug($slug);

        if (check_allow()) {
            $blog_cat = ORM::for_table($config['db']['pre'] . 'blog_categories')->create();
            $blog_cat->title = $name;
            $blog_cat->slug = $slug;
            $blog_cat->active = $_POST['active'];
            $blog_cat->save();

            $id = $blog_cat->id();
            if ($id) {
                $blog_pos = ORM::for_table($config['db']['pre'] . 'blog_categories')->find_one($id);
                $blog_pos->position = validate_input($id);
                $blog_pos->save();
            }
        }
        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editBlogCat()
{
    global $config;
    $_POST = validate_input($_POST);
    $name = $_POST['title'];
    $slug = $_POST['slug'];
    $id = $_POST['id'];

    if (trim($name) != '' && is_string($name) && trim($id) != '') {
        if (check_allow()) {
            $blog_cat = ORM::for_table($config['db']['pre'] . 'blog_categories')->find_one($id);

            if ($blog_cat['slug'] != $slug) {
                if ($slug == "")
                    $slug = create_blog_cat_slug($name);
                else
                    $slug = create_blog_cat_slug($slug);
            }

            $blog_cat->title = $name;
            $blog_cat->slug = $slug;
            $blog_cat->active = $_POST['active'];
            $blog_cat->save();
        }
        $status = "success";
        $message = __("Saved Successfully");
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function saveEmailTemplate()
{
    // update_option_value("email_message_signup_details", $_POST['email_message_signup_details']);
    // update_option_value("email_message_signup_confirm", $_POST['email_message_signup_confirm']);
    // update_option_value("email_message_forgot_pass", $_POST['email_message_forgot_pass']);
    // update_option_value("email_message_contact", $_POST['email_message_contact']);
    // update_option_value("email_message_feedback", $_POST['email_message_feedback']);
    // update_option_value("emailHTML_withdraw_accepted", $_POST['emailHTML_withdraw_accepted']);
    // update_option_value("emailHTML_withdraw_rejected", $_POST['emailHTML_withdraw_rejected']);
    // update_option_value("emailHTML_withdraw_request", $_POST['emailHTML_withdraw_request']);

    // update_option_value("email_sub_signup_details", stripslashes(validate_input($_POST['email_sub_signup_details'])));
    // update_option_value("email_sub_signup_confirm", stripslashes(validate_input($_POST['email_sub_signup_confirm'])));
    // update_option_value("email_sub_forgot_pass", stripslashes(validate_input($_POST['email_sub_forgot_pass'])));
    // update_option_value("email_sub_contact", stripslashes(validate_input($_POST['email_sub_contact'])));
    // update_option_value("email_sub_feedback", stripslashes(validate_input($_POST['email_sub_feedback'])));
    // update_option_value("email_sub_withdraw_accepted", stripslashes(validate_input($_POST['email_sub_withdraw_accepted'])));
    // update_option_value("email_sub_withdraw_rejected", stripslashes(validate_input($_POST['email_sub_withdraw_rejected'])));
    // update_option_value("email_sub_withdraw_request", stripslashes(validate_input($_POST['email_sub_withdraw_request'])));

    // $status = "success";
    // $message = __("Saved Successfully");

    // echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    // die();

    global $config;
    $pdo = ORM::get_db();

    if (count($_POST['template_id']) > 0) {
        $arrCount = count($_POST['template_id']);
        $numCount = 0;
        foreach ($_POST['template_id'] as $key => $postId) {
            $sql = "UPDATE `" . $config['db']['pre'] . "email_template` SET `subject` = '" . $_POST['template_subject'][$key] . "', `message` = '" . $_POST['template_messsage'][$key] . "' WHERE `id` = '" . $postId . "' ";
            $pdo->query($sql);
            $numCount++;
        }
        if ($arrCount === $numCount) {
            $result['success'] = true;
            $result['message'] = __('Template Saved Successfully');
            die(json_encode($result));
        } else if (($numCount > 0) && ($numCount < $arrCount)) {
            $result['success'] = true;
            $result['message'] = __('Partial Template Saved Successfully');
            die(json_encode($result));
        } else {
            $result['success'] = false;
            $result['message'] = __('Failed to Update Template Content');
            die(json_encode($result));
        }
    }
    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function addEmailTemplateForm()
{
    // test
    global $config;

    $email_template_table = ORM::for_table($config['db']['pre'] . 'email_template')->create();
    $email_template_table->title = ucfirst($_POST['template_title']);
    $email_template_table->keyword = $_POST['template_keyword'];
    $template_shortcodes = $_POST['template_shortcodes'];
    $commaSeparatedShortcodes = implode(',', $template_shortcodes);
    $email_template_table->shortcodes = json_encode($commaSeparatedShortcodes);
    $email_template_table->created_at = date('Y-m-d H:i:s');
    $email_template_table->updated_at = date('Y-m-d H:i:s');
    $email_template_table->save();

    $last_id = $email_template_table->id();
    $data = ORM::for_table($config['db']['pre'] . 'email_template')
        ->where('id', $last_id)
        ->find_array();

    if ($data) {
        $result['status'] = true;
        $result['data'] = $data[0];
        $result['message'] = __('Successfully Saved.');
        die(json_encode($result));
    }
    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

/*function old_testEmailTemplate()
{
    global $config, $lang, $link;
    $_POST = validate_input($_POST);
    $errors = null;

    $test_to_email = $_POST['test_to_email'];
    $test_to_name = $_POST['test_to_name'];

    foreach ($_POST['templates'] as $template) {
        switch ($template) {
            case 'signup-details':
                $html = $config['email_sub_signup_details'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{USER_FULLNAME}', $test_to_name, $html);
                $email_subject = $html;

                $html = $config['email_message_signup_details'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{USERNAME}', "demo", $html);
                $html = str_replace('{PASSWORD}', "demo", $html);
                $html = str_replace('{USER_ID}', "1", $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{USER_FULLNAME}', $test_to_name, $html);
                $email_body = $html;

                $errors = email($test_to_email, $test_to_name, $email_subject, $email_body);
                break;

            case 'create-account':
                $html = $config['email_sub_signup_confirm'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{USER_FULLNAME}', $test_to_name, $html);
                $email_subject = $html;

                $confirmation_link = $link['SIGNUP'] . "?confirm=123456&user=1";

                $html = $config['email_message_signup_confirm'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{CONFIRMATION_LINK}', $confirmation_link, $html);
                $html = str_replace('{USERNAME}', "demo", $html);
                $html = str_replace('{USER_ID}', "1", $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{USER_FULLNAME}', $test_to_name, $html);
                $email_body = $html;

                $errors = email($test_to_email, $test_to_name, $email_subject, $email_body);
                break;

            case 'forgot-pass':
                $html = $config['email_sub_forgot_pass'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{USER_FULLNAME}', $test_to_name, $html);
                $email_subject = $html;

                $forget_password_link = $config['site_url'] . "login?forgot=sd1213f1x1&r=21d1d2d12&e=12&t=1213231";

                $html = $config['email_message_forgot_pass'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('FORGET_PASSWORD_LINK', $forget_password_link, $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{USER_FULLNAME}', $test_to_name, $html);
                $email_body = $html;

                $errors = email($test_to_email, $test_to_name, $email_subject, $email_body);
                break;

            case 'contact_us':
                $html = $config['email_sub_contact'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{CONTACT_SUBJECT}', "Contact Email", $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{NAME}', $test_to_name, $html);
                $email_subject = $html;


                $html = $config['email_message_contact'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{NAME}', $test_to_name, $html);
                $html = str_replace('{CONTACT_SUBJECT}', "Contact Email", $html);
                $html = str_replace('{MESSAGE}', "Test Message", $html);
                $email_body = $html;

                $errors = email($test_to_email, $test_to_name, $email_subject, $email_body);
                break;
            case 'feedback':
                $html = $config['email_sub_feedback'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{FEEDBACK_SUBJECT}', "Feedback Email", $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{NAME}', $test_to_name, $html);
                $email_subject = $html;


                $html = $config['email_message_feedback'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{NAME}', $test_to_name, $html);
                $html = str_replace('{PHONE}', "1234567890", $html);
                $html = str_replace('{FEEDBACK_SUBJECT}', "Feedback Email", $html);
                $html = str_replace('{MESSAGE}', "Test Message", $html);
                $email_body = $html;

                $errors = email($test_to_email, $test_to_name, $email_subject, $email_body);
                break;

            case 'report':
                $html = $config['email_sub_report'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{NAME}', $test_to_name, $html);
                $html = str_replace('{USERNAME}', $test_to_name, $html);
                $html = str_replace('{VIOLATION}', __("Advertising another website"), $html);
                $email_subject = $html;


                $html = $config['email_message_report'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{EMAIL}', $test_to_email, $html);
                $html = str_replace('{NAME}', $test_to_name, $html);
                $html = str_replace('{USERNAME}', $test_to_name, $html);
                $html = str_replace('{USERNAME2}', "Violator Username", $html);
                $html = str_replace('{VIOLATION}', __("Advertising another website"), $html);
                $html = str_replace('{URL}', "https://example.com", $html);
                $html = str_replace('{DETAILS}', "Violator Message details here", $html);
                $email_body = $html;

                $errors = email($test_to_email, $test_to_name, $email_subject, $email_body);
                break;
        }
    }

    $status = "success";
    $message = __("Email Sent Successfully");

    if (is_array($errors) && !empty($errors)) {
        $status = "error";
        $message = implode('<br />', $errors);
    }
    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}*/


function emailTemplates($testEmail, $testName, $keyword, $data)
{
    global $config, $lang, $link;
    $errors = null;
    $html = $data->subject;
    $html = $data->message;

    switch ($keyword) {
        case 'signup-details':
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_subject = $html;

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{USERNAME}', "demo", $html);
            $html = str_replace('{PASSWORD}', "demo", $html);
            $html = str_replace('{USER_ID}', "1", $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;

        case 'create-account':
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_subject = $html;

            $confirmation_link = $link['SIGNUP'] . "?confirm=123456&user=1";

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{CONFIRMATION_LINK}', $confirmation_link, $html);
            $html = str_replace('{USERNAME}', "demo", $html);
            $html = str_replace('{USER_ID}', "1", $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;

        case 'forgot-pass':
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_subject = $html;

            $forget_password_link = $config['site_url'] . "login?forgot=sd1213f1x1&r=21d1d2d12&e=12&t=1213231";

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('FORGET_PASSWORD_LINK', $forget_password_link, $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;

        case 'contact_us':
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{CONTACT_SUBJECT}', "Contact Email", $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{NAME}', $testName, $html);
            $email_subject = $html;

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{NAME}', $testName, $html);
            $html = str_replace('{CONTACT_SUBJECT}', "Contact Email", $html);
            $html = str_replace('{MESSAGE}', "Test Message", $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;

        case 'feedback':
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{FEEDBACK_SUBJECT}', "Feedback Email", $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{NAME}', $testName, $html);
            $email_subject = $html;

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{NAME}', $testName, $html);
            $html = str_replace('{PHONE}', "1234567890", $html);
            $html = str_replace('{FEEDBACK_SUBJECT}', "Feedback Email", $html);
            $html = str_replace('{MESSAGE}', "Test Message", $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;

        case 'report':
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{NAME}', $testName, $html);
            $html = str_replace('{USERNAME}', $testName, $html);
            $html = str_replace('{VIOLATION}', __("Advertising another website"), $html);
            $email_subject = $html;

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{NAME}', $testName, $html);
            $html = str_replace('{USERNAME}', $testName, $html);
            $html = str_replace('{USERNAME2}', "Violator Username", $html);
            $html = str_replace('{VIOLATION}', __("Advertising another website"), $html);
            $html = str_replace('{URL}', "https://example.com", $html);
            $html = str_replace('{DETAILS}', "Violator Message details here", $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;

        default:
            $html = $data->subject;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_subject = $html;

            $html = $data->message;
            $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace('{USERNAME}', "demo", $html);
            $html = str_replace('{USER_ID}', "1", $html);
            $html = str_replace('{EMAIL}', $testEmail, $html);
            $html = str_replace('{USER_FULLNAME}', $testName, $html);
            $email_body = $html;

            // $errors = email($testEmail, $testName, $email_subject, $email_body);
            email_send($testEmail, $config['admin_email'], $email_subject, $email_body);
            break;
    }
}

function testEmailTemplate()
{
    global $config, $lang, $link;
    $_POST = validate_input($_POST);
    $errors = null;

    $testEmail = $_POST['test_to_email'];
    $testName = $_POST['test_to_name'];

    if (count($_POST['templates']) > 0) {
        foreach ($_POST['templates'] as $keyword) {
            $data = ORM::for_table($config['db']['pre'] . 'email_template')
                ->select_many('subject', 'message')
                ->where('keyword', $keyword)
                ->find_one();

            emailTemplates($testEmail, $testName, $keyword, $data);
        }
        $result['status'] = 'success';
        $result['message'] = __('Send Email Successfully');
        die(json_encode($result));
    } else {
        $result['status'] = 'error';
        $result['message'] = __('Email Template should be required');
        die(json_encode($result));
    }

    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function getEmailTemplates()
{
    global $config;
    $data = ORM::for_table($config['db']['pre'] . 'email_template')
        ->order_by_asc('id')
        ->find_array();

    // if(count($data) > 0) {
    //     foreach($data as $dt) {
    //         $dt->subject = ($dt->subject === NULL) ? "" : $dt->subject;
    //     }
    // }


    if ($data) {
        $result['status'] = true;
        $result['data'] = $data;
        die(json_encode($result));
    }
    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}


function editWithdrawal()
{
    global $config;

    if (isset($_POST['id'])) {

        if (isset($_POST['status'])) {
            $withdraw = ORM::for_table($config['db']['pre'] . 'withdrawal')->find_one(validate_input($_POST['id']));
            $user_id = $withdraw['user_id'];
            $amount = $withdraw['amount'];

            $userdata = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id);

            if ($_POST['status'] == "reject") {
                $withdraw->reject_reason = validate_input($_POST['reject_reason']);

                $total = $userdata['balance'] + $amount;
                $userdata->balance = number_format($total, 2, '.', '');
                $userdata->save();


                /*User : Withdraw request rejected*/
                $html = $config['email_sub_withdraw_rejected'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{USER_ID}', $user_id, $html);
                $html = str_replace('{USERNAME}', $userdata['username'], $html);
                $html = str_replace('{EMAIL}', $userdata['email'], $html);
                $html = str_replace('{USER_FULLNAME}', $userdata['name'], $html);
                $html = str_replace('{AMOUNT}', $amount, $html);
                $email_subject = $html;

                $html = $config['emailHTML_withdraw_rejected'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{USER_ID}', $user_id, $html);
                $html = str_replace('{USERNAME}', $userdata['username'], $html);
                $html = str_replace('{EMAIL}', $userdata['email'], $html);
                $html = str_replace('{USER_FULLNAME}', $userdata['name'], $html);
                $html = str_replace('{AMOUNT}', $amount, $html);
                $html = str_replace('{REJECT_REASON}', nl2br(validate_input($_POST['reject_reason'])), $html);
                $email_body = $html;

                email($userdata['email'], $userdata['name'], $email_subject, $email_body);
            }

            if ($_POST['status'] == "success") {

                /*User : Withdraw request accepted*/
                $html = $config['email_sub_withdraw_accepted'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{USER_ID}', $user_id, $html);
                $html = str_replace('{USERNAME}', $userdata['username'], $html);
                $html = str_replace('{EMAIL}', $userdata['email'], $html);
                $html = str_replace('{USER_FULLNAME}', $userdata['name'], $html);
                $html = str_replace('{AMOUNT}', $amount, $html);
                $email_subject = $html;

                $html = $config['emailHTML_withdraw_accepted'];
                $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace('{USER_ID}', $user_id, $html);
                $html = str_replace('{USERNAME}', $userdata['username'], $html);
                $html = str_replace('{EMAIL}', $userdata['email'], $html);
                $html = str_replace('{USER_FULLNAME}', $userdata['name'], $html);
                $html = str_replace('{AMOUNT}', $amount, $html);
                $email_body = $html;

                email($userdata['email'], $userdata['name'], $email_subject, $email_body);
            }

            $withdraw->status = validate_input($_POST['status']);
            $withdraw->save();

            $result['status'] = 'success';
            $result['message'] = __('Successfully Saved.');
        } else {
            $result['status'] = 'error';
            $result['message'] = __('Unexpected error, please try again.');
        }
    } else {
        $result['status'] = 'error';
        $result['message'] = __('Unexpected error, please try again.');
    }

    die(json_encode($result));
}

function editAdvertise()
{
    global $config;

    if (!empty($_POST['id'])) {

        $adsense = ORM::for_table($config['db']['pre'] . 'adsense')->find_one(validate_input($_POST['id']));
        $adsense->provider_name = validate_input($_POST['provider_name']);
        $adsense->large_track_code = $_POST['large_track_code'];
        $adsense->tablet_track_code = $_POST['tablet_track_code'];
        $adsense->phone_track_code = $_POST['phone_track_code'];
        $adsense->status = validate_input($_POST['status']);
        $adsense->save();

        $result['status'] = 'success';
        $result['id'] = $adsense->id();
        $result['message'] = __('Successfully Saved.');
        die(json_encode($result));
    }
    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function editAIDocument()
{
    global $config;

    if (!empty($_POST['id'])) {
        $content = validate_input($_POST['content'], true);
        $_POST = validate_input($_POST);
        $_POST['content'] = $content;

        if (empty($_POST['title'])) {
            $result['status'] = 'error';
            $result['message'] = __('Title is required.');
            die(json_encode($result));
        }

        $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->find_one($_POST['id']);
        $content->title = $_POST['title'];
        $content->content = $_POST['content'];
        $content->save();

        $result['status'] = 'success';
        $result['id'] = $content->id();
        $result['message'] = __('Successfully Saved.');
        die(json_encode($result));
    }
    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function editAITemplate()
{
    global $config;

    if (!empty($_POST['id'])) {
        $_POST = validate_input($_POST);

        if (empty($_POST['title'])) {
            $result['status'] = 'error';
            $result['message'] = __('Title is required.');
            die(json_encode($result));
        }
        if (empty($_POST['category'])) {
            $result['status'] = 'error';
            $result['message'] = __('Category is required.');
            die(json_encode($result));
        }
        if (empty($_POST['description'])) {
            $result['status'] = 'error';
            $result['message'] = __('Description is required.');
            die(json_encode($result));
        }

        $_POST['icon'] = empty($_POST['icon']) ? 'fa fa-check-square' : $_POST['icon'];

        $settings = [
            'language' => $_POST['language'],
            'quality_type' => $_POST['quality_type'],
            'tone_of_voice' => $_POST['tone_of_voice'],
        ];

        $template = ORM::for_table($config['db']['pre'] . 'ai_templates')->find_one($_POST['id']);
        $template->title = $_POST['title'];
        $template->icon = $_POST['icon'];
        $template->category_id = $_POST['category'];
        $template->description = $_POST['description'];
        $template->translations = json_encode($_POST['translations'], JSON_UNESCAPED_UNICODE);
        $template->settings = json_encode($settings);
        $template->active = $_POST['active'];
        $template->save();

        $result['status'] = 'success';
        $result['id'] = $template->id();
        $result['message'] = __('Successfully Saved.');
        die(json_encode($result));
    }
    $result['status'] = 'error';
    $result['message'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function editAICustomTemplate()
{
    global $config;

    $_POST = validate_input($_POST);

    if (empty($_POST['title'])) {
        $result['status'] = 'error';
        $result['message'] = __('Title is required.');
        die(json_encode($result));
    }
    if (empty($_POST['category'])) {
        $result['status'] = 'error';
        $result['message'] = __('Category is required.');
        die(json_encode($result));
    }
    if (empty($_POST['description'])) {
        $result['status'] = 'error';
        $result['message'] = __('Description is required.');
        die(json_encode($result));
    }
    if (empty($_POST['prompt'])) {
        $result['status'] = 'error';
        $result['message'] = __('Prompt is required.');
        die(json_encode($result));
    }

    // check slug
    if (!empty($_POST['slug'])) {
        if (!preg_match('/^[a-z0-9]+(-?[a-z0-9]+)*$/i', $_POST['slug'])) {
            $result['status'] = 'error';
            $result['message'] = __('Slug is invalid.');
            die(json_encode($result));
        }

        if (ORM::for_table($config['db']['pre'] . 'ai_custom_templates')
            ->where('slug', $_POST['slug'])
            ->where_not_equal('id', !empty($_POST['id']) ? $_POST['id'] : 0)
            ->count()
        ) {

            $result['status'] = 'error';
            $result['message'] = __('Slug is already available.');
            die(json_encode($result));
        }
    }
    if (empty($_POST['slug'])) {
        $_POST['slug'] = create_custom_template_slug($_POST['title']);
    }

    $_POST['icon'] = empty($_POST['icon']) ? 'fa fa-check-square' : $_POST['icon'];

    $parameter = array();
    if (!empty($_POST['parameter_title'])) {
        foreach ($_POST['parameter_title'] as $key => $title) {
            $parameter[] = array(
                'title' => validate_input($title),
                'type' => $_POST['parameter_type'][$key],
                'placeholder' => escape($_POST['parameter_placeholder'][$key]),
                'options' => $_POST['parameter_type'][$key] == 'select'
                    ? escape($_POST['parameter_options'][$key])
                    : '',
                'translations' => $_POST['parameter_translations'][$key],
                'required' => isset($_POST['parameter_required'][$key]) ? 1 : 0
            );
        }
    }

    $settings = [
        'language' => $_POST['language'],
        'quality_type' => $_POST['quality_type'],
        'tone_of_voice' => $_POST['tone_of_voice'],
    ];

    if (!empty($_POST['id'])) {
        $template = ORM::for_table($config['db']['pre'] . 'ai_custom_templates')->find_one($_POST['id']);
    } else {
        $template = ORM::for_table($config['db']['pre'] . 'ai_custom_templates')->create();
    }
    $template->slug = $_POST['slug'];
    $template->title = $_POST['title'];
    $template->icon = $_POST['icon'];
    $template->category_id = $_POST['category'];
    $template->description = $_POST['description'];
    $template->prompt = $_POST['prompt'];
    $template->parameters = json_encode($parameter, JSON_UNESCAPED_UNICODE);
    $template->translations = json_encode($_POST['translations'], JSON_UNESCAPED_UNICODE);
    $template->settings = json_encode($settings);
    $template->active = $_POST['active'];
    $template->save();

    $result['status'] = 'success';
    $result['id'] = $template->id();
    $result['message'] = __('Successfully Saved.');
    die(json_encode($result));
}

function editAITplCategory()
{
    global $config;

    $_POST = validate_input($_POST);

    if (empty($_POST['title'])) {
        $result['status'] = 'error';
        $result['message'] = __('Title is required.');
        die(json_encode($result));
    }

    if (!empty($_POST['id'])) {
        $template = ORM::for_table($config['db']['pre'] . 'ai_template_categories')->find_one($_POST['id']);
    } else {
        $template = ORM::for_table($config['db']['pre'] . 'ai_template_categories')->create();
    }
    $template->title = $_POST['title'];
    $template->translations = json_encode($_POST['translations'], JSON_UNESCAPED_UNICODE);
    $template->active = $_POST['active'];
    $template->save();

    $result['status'] = 'success';
    $result['id'] = $template->id();
    $result['message'] = __('Successfully Saved.');
    die(json_encode($result));
}

function editAPIKey()
{
    global $config;

    $_POST = validate_input($_POST);

    if (empty($_POST['title'])) {
        $result['status'] = 'error';
        $result['message'] = __('Title is required.');
        die(json_encode($result));
    }
    if (empty($_POST['api_key'])) {
        $result['status'] = 'error';
        $result['message'] = __('API key is required.');
        die(json_encode($result));
    }

    if (!empty($_POST['id'])) {
        $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->find_one($_POST['id']);
    } else {
        $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->create();
    }
    $api_key->title = $_POST['title'];
    $api_key->api_key = $_POST['api_key'];
    $api_key->type = $_POST['type'];
    $api_key->active = $_POST['active'];
    $api_key->save();

    $result['status'] = 'success';
    $result['id'] = $api_key->id();
    $result['message'] = __('Successfully Saved.');
    die(json_encode($result));
}

function remove_document()
{
    global $config;
    $bot_id = $_POST['bot_id'];
    $doc_id = $_POST['doc_id'];

    $seleceted_doc = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->find_one($doc_id);

    $s3_key = $seleceted_doc->s3_key;

    $seleceted_doc->delete();

    delete_s3_file($s3_key);

    $documents = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('ai_chat_bot_id', $bot_id)->find_many();

    if (count($documents) > 0) {
        updateDocumentPromptInChatBot($bot_id);
    } else {
        $bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($bot_id);
        $bot->assistant_id = NULL;
        $bot->save();
    }

    $document_data = array();
    foreach ($documents as $document) {
        $document_data[] = array(
            'id' => $document->id,
            'title' => $document->title,
        );
    }

    $result['success'] = true;
    $result['bot_id'] = $bot_id;
    $result['documents'] = $document_data;
    $result['message'] = "Deleted successfully.";
    die(json_encode($result));
}

function delete_s3_file($s3_key)
{
    require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

    $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));

    try {
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => get_option('ai_tts_aws_s3_region'),
            'credentials' => $credentials
        ]);
    } catch (Exception $e) {
        $result['success'] = false;
        $result['error'] = __('Incorrect AWS credentials.');
        $result['api_error'] = $e->getMessage();
        die(json_encode($result));
    }
    $s3->deleteObject([
        'Bucket' => "aiteamup-live",
        'Key' => $s3_key,
    ]);
}

function editAIChatBot()
{
    global $config;

    $welcome_message = $_POST['welcome_message'];
    $_POST = validate_input($_POST);
    $_POST['welcome_message'] = validate_input($welcome_message, true);

    $image = null;

    if (empty($_POST['name'])) {
        $result['status'] = 'error';
        $result['message'] = __('Name is required.');
        die(json_encode($result));
    }
    if (empty($_POST['prompt'])) {
        $result['status'] = 'error';
        $result['message'] = __('Prompt is required.');
        die(json_encode($result));
    }

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $target_dir = ROOTPATH . "/storage/bot_images/";
        $result = quick_file_upload('image', $target_dir);
        if ($result['success']) {
            $image = $result['file_name'];
            resizeImage(300, $target_dir . $image, $target_dir . $image);
            if (isset($_POST['id'])) {
                // remove old image
                $info = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')
                    ->select('image')
                    ->find_one($_POST['id']);

                if (!empty(trim((string)$info['image'])) && $info['image'] != "default_user.png") {
                    if (file_exists($target_dir . $info['image'])) {
                        unlink($target_dir . $info['image']);
                    }
                }
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = $result['error'];
            die(json_encode($result));
        }
    }

    if (isset($_POST['import_website']) || $_POST['import_website'] != "") {
        $url = $_POST['import_website'];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 9; Pixel 3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36'); // Set a user agent string

            $response = curl_exec($ch);
            // if (curl_errno($ch)) {
            //     echo 'cURL Error: ' . curl_error($ch);
            // }
            curl_close($ch);

            if ($response) {
                $doc = new DOMDocument();
                libxml_use_internal_errors(true); // Handle any HTML errors
                // Load the HTML content into the DOMDocument
                $doc->loadHTML($response);
                // Remove script elements from the DOM
                $scriptNodes = $doc->getElementsByTagName('script');
                foreach ($scriptNodes as $scriptNode) {
                    $scriptNode->parentNode->removeChild($scriptNode);
                }
                // Remove style elements from the DOM
                $styleNodes = $doc->getElementsByTagName('style');
                foreach ($styleNodes as $styleNode) {
                    $styleNode->parentNode->removeChild($styleNode);
                }
                // Create a DOMXPath object to query the DOM
                $xpath = new DOMXPath($doc);
                // Query for all text nodes within the body
                $textNodes = $xpath->query('//body//text()');
                $cleanedContent = '';
                foreach ($textNodes as $textNode) {
                    // Append the text content to the cleaned content
                    $cleanedText = trim($textNode->nodeValue);
                    // Filter out specific unwanted content
                    if (
                        !empty($cleanedText) &&
                        !preg_match('/\b(\/\/|=|{|}|function|const|var|\(\))\b/', $cleanedText)
                    ) {
                        $cleanedContent .= $cleanedText . ' ';
                    }
                }
                // Optionally, you can remove excess whitespace
                $cleanedContent = preg_replace('/\s+/', ' ', $cleanedContent);
                $tempFile = tempnam(sys_get_temp_dir(), 'user_import_website');
                $tempFilePath = $tempFile . '.txt';
                $title = $url;
                $file_name = "imported_website_content.txt";
                // Write title and content to the temporary file
                file_put_contents($tempFilePath, "Title: $title\nContent: $cleanedContent");
                $job_id = uniqid();
                $s3_key = 'aiteamup_files/' . $job_id . "_" . $file_name;
                $s3_url = upload_file_to_s3($tempFilePath, $s3_key);
                $website_data = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->create();

                $website_data->ai_chat_bot_id   =   $_POST['id'];;
                $website_data->title            =   $title;
                $website_data->action           =   "import";
                $website_data->file_url         =   $s3_url;
                $website_data->s3_key           =   $s3_key;

                $website_data->save();

                if ($website_data) {
                    updateDocumentPromptInChatBot($_POST['id']);
                } else {
                    $result['status'] = 'error';
                    $result['message'] = __('Training Documents not found');
                    die(json_encode($result));
                }
                // $data->title        =   $_POST['web_url'];
                // $data->content      =   $cleanedContent;
                // $data->word_count   =   strlen($cleanedContent);
            }
        } catch (\Throwable $th) {
            $result['success'] = false;
            $result['error'] = __('Please import valid URL. This website is not valid');
        }
    }

    if (isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != "") {
        $data = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->create();
        $data->ai_chat_bot_id   =   $_POST['id'];
        $data->action           =   "upload";
        $data->title            =   $_FILES['upload_file']['name'];

        $file = $_FILES['upload_file']['tmp_name'];
        $file_name = basename($_FILES['upload_file']['name']);
        $file_extension = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);

        if (in_array($file_extension, ['pdf', 'doc', 'docx', 'txt'])) {

            $job_id = uniqid();

            $s3_key = 'aiteamup_files/' . $job_id . "_" . $file_name;

            $s3_url = upload_file_to_s3($file, $s3_key);

            $data->file_url   =   $s3_url;
            $data->s3_key = $s3_key;

            $data->save();
        } else {
            $result['status'] = 'error';
            $result['message'] = __('Invalid file format, Try again.');
            die(json_encode($result));
        }

        if ($data) {
            updateDocumentPromptInChatBot($_POST['id']);
        } else {
            $result['status'] = 'error';
            $result['message'] = __('Training Documents not found');
            die(json_encode($result));
        }
    }

    if (!empty($_POST['id'])) {
        $bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($_POST['id']);
    } else {
        $bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->create();
    }
    $bot->name = $_POST['name'];
    $bot->welcome_message = $_POST['welcome_message'];
    $bot->role = $_POST['role'];
    $bot->prompt = $_POST['prompt'];
    $bot->bio = $_POST['bio'];
    $bot->category_id = $_POST['category'];
    $bot->translations = json_encode($_POST['translations'], JSON_UNESCAPED_UNICODE);
    $bot->active = $_POST['active'];

    if ($image)
        $bot->image = $image;

    $bot->save();

    $result['status'] = 'success';
    $result['id'] = $bot->id();
    $result['message'] = __('Successfully Saved.');
    die(json_encode($result));
}

function upload_file_assistants($api_key, $file_url)
{
    $endpoint = "https://api.openai.com/v1/files";

    $headers = [
        'Authorization: Bearer ' . $api_key,
    ];

    $data = [
        'purpose' => 'assistants',
        'file' => $file_url,
    ];

    $ch = curl_init($endpoint);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

    $file = curl_exec($ch);

    if (curl_errno($ch)) {
        $result['success'] = false;
        $result['error'] = __('Error in file uplaod for assistants.');
        die(json_encode($result));
    } else {
        $decodedResponse = json_decode($file, true);

        if (isset($decodedResponse['id'])) {
            $fileId = $decodedResponse['id'];

            return $fileId;
        } else {
            $result['success'] = false;
            $result['error'] = __('Unexpected Error in file uplaod for assistants.');
            die(json_encode($result));
        }
    }

    curl_close($ch);
}

function chat_bot_train($bot_id)
{
    global $config;

    $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

    // $assistant = enable_retrival($api_key);


    $getDocument = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('ai_chat_bot_id', $bot_id)->find_many();

    $fileIds = [];

    if (count($getDocument) > 0) {
        foreach ($getDocument as $doc) {

            $name = uniqid();
            $file_dir = '/storage/ai_audios/';
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_dir .  $name;
            $file_extension = pathinfo($doc->file_url, PATHINFO_EXTENSION);

            $file_path .= "." . $file_extension;

            file_put_contents($file_path, file_get_contents($doc->file_url));

            $c_file = curl_file_create($file_path);

            $fileId = upload_file_assistants($api_key, $c_file);

            $doc->assistant_file_id = $fileId;

            $doc->save();

            unlink($file_path);

            $fileIds[] = $fileId;
        }
    }

    // Add the file to the assistant

    $ai_chat_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($bot_id);

    $bot_name = $ai_chat_bot->name;

    $instruction = $ai_chat_bot->prompt;

    $assistant = add_file_to_assistant($api_key, $fileIds, $bot_name, $instruction);

    $ai_chat_bot->assistant_id = $assistant->id;

    $ai_chat_bot->save();

    return true;
}

function add_file_to_assistant($api_key, $fileIds, $bot_name, $instruction)
{

    $endpoint = "https://api.openai.com/v1/assistants";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'OpenAI-Beta: assistants=v1',
    ];

    $data = [
        'instructions' => $instruction,
        'name' => $bot_name,
        'tools' => [
            [
                'type' => 'retrieval',
            ],
        ],
        'model' => 'gpt-4-1106-preview',
        'file_ids' => $fileIds,
    ];

    $ch = curl_init($endpoint);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    $output = json_decode($response);

    if (curl_errno($ch)) {
        $result['success'] = false;
        $result['error'] = __('Error in add file to the assistant.');
        die(json_encode($result));
    } else {
        return $output;
    }

    curl_close($ch);
}

// Function to manage ai chat bot prompt msg 
function updateDocumentPromptInChatBot($aiChatBotId)
{
    if (checkloggedin()) {
        chat_bot_train($aiChatBotId);

        return true;
    }
    return false;
}

function upload_file_to_s3($file, $s3_key)
{
    require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

    $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));

    try {
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => get_option('ai_tts_aws_s3_region'),
            'credentials' => $credentials
        ]);
    } catch (Exception $e) {
        $result['success'] = false;
        $result['error'] = __('Incorrect AWS credentials.');
        $result['api_error'] = $e->getMessage();
        die(json_encode($result));
    }

    // backet name
    $bucket = 'aiteamup-live';

    try {
        $s3_result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $s3_key,
            'Body' => fopen($file, 'r'),
            'ACL' => 'public-read', // Adjust the ACL based on your requirements
        ]);
    } catch (Exception $e) {

        $result['success'] = false;
        $result['error'] = __('Error in uploading file to S3.');
        $result['api_error'] = $e->getMessage();
        die(json_encode($result));
    }

    $file_url = $s3_result->get('ObjectURL');

    return $file_url;
}

function editAIChatBotCategory()
{
    global $config;

    $_POST = validate_input($_POST);

    if (empty($_POST['title'])) {
        $result['status'] = 'error';
        $result['message'] = __('Title is required.');
        die(json_encode($result));
    }

    if (!empty($_POST['id'])) {
        $template = ORM::for_table($config['db']['pre'] . 'ai_chat_bots_categories')->find_one($_POST['id']);
    } else {
        $template = ORM::for_table($config['db']['pre'] . 'ai_chat_bots_categories')->create();
    }
    $template->title = $_POST['title'];
    $template->translations = json_encode($_POST['translations'], JSON_UNESCAPED_UNICODE);
    $template->active = $_POST['active'];
    $template->save();

    $result['status'] = 'success';
    $result['id'] = $template->id();
    $result['message'] = __('Successfully Saved.');
    die(json_encode($result));
}

function editAIChatPrompts()
{
    global $config;

    $_POST = validate_input($_POST);

    if (empty($_POST['title'])) {
        $result['status'] = 'error';
        $result['message'] = __('Title is required.');
        die(json_encode($result));
    }
    if (empty($_POST['prompt'])) {
        $result['status'] = 'error';
        $result['message'] = __('Prompt is required.');
        die(json_encode($result));
    }

    if (!empty($_POST['id'])) {
        $prompt = ORM::for_table($config['db']['pre'] . 'ai_chat_prompts')->find_one($_POST['id']);
    } else {
        $prompt = ORM::for_table($config['db']['pre'] . 'ai_chat_prompts')->create();
    }
    $prompt->title = $_POST['title'];
    $prompt->chat_bots = !empty($_POST['chat_bots']) ? validate_input(implode(',', $_POST['chat_bots'])) : null;
    $prompt->description = $_POST['description'];
    $prompt->prompt = $_POST['prompt'];
    $prompt->translations = json_encode($_POST['translations'], JSON_UNESCAPED_UNICODE);
    $prompt->active = $_POST['active'];

    $prompt->save();

    $result['status'] = 'success';
    $result['id'] = $prompt->id();
    $result['message'] = __('Successfully Saved.');
    die(json_encode($result));
}

function SaveSettings()
{

    global $config, $lang, $link;
    $status = "";
    if (isset($_POST['logo_watermark'])) {
        $valid_formats = array("jpg", "jpeg", "png", "gif"); // Valid image formats
        if (isset($_FILES['banner']) && $_FILES['banner']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['banner']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/banner/"; //Image upload directory
                $bannername = stripslashes($_FILES['banner']['name']);
                $size = filesize($_FILES['banner']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($bannername);
                $ext = strtolower($ext);
                $banner_name = "bg" . '.' . $ext;
                $newBgname = $uploaddir . $config['home_banner'];
                //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('banner', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("home_banner", $result['file_name']);
                    $status = "success";
                    $message = __("Banner updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f2BrandImg']) && $_FILES['f2BrandImg']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f2BrandImg']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f2BrandImg']['name']);
                $size = filesize($_FILES['f2BrandImg']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f2BrandImg" . '.' . $ext;
                $newBgname = $uploaddir . $config['f2BrandImg'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f2BrandImg', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f2BrandImg", $result['file_name']);
                    $status = "success";
                    $message = __("Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['daily_live_tech_calls_img']) && $_FILES['daily_live_tech_calls_img']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['daily_live_tech_calls_img']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/banner/"; //Image upload directory
                $filename = stripslashes($_FILES['daily_live_tech_calls_img']['name']);
                $size = filesize($_FILES['daily_live_tech_calls_img']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "daily_live_tech_calls_img" . '.' . $ext;
                $newBgname = $uploaddir . $config['daily_live_tech_calls_img'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('daily_live_tech_calls_img', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("daily_live_tech_calls_img", $result['file_name']);
                    $status = "success";
                    $message = __("Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['ai_ployees_marketplace_img']) && $_FILES['ai_ployees_marketplace_img']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['ai_ployees_marketplace_img']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/banner/"; //Image upload directory
                $filename = stripslashes($_FILES['ai_ployees_marketplace_img']['name']);
                $size = filesize($_FILES['ai_ployees_marketplace_img']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "ai_ployees_marketplace_img" . '.' . $ext;
                $newBgname = $uploaddir . $config['ai_ployees_marketplace_img'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('ai_ployees_marketplace_img', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("ai_ployees_marketplace_img", $result['file_name']);
                    $status = "success";
                    $message = __("Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['dashboard_labs_banner']) && $_FILES['dashboard_labs_banner']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['dashboard_labs_banner']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/banner/"; //Image upload directory
                $filename = stripslashes($_FILES['dashboard_labs_banner']['name']);
                $size = filesize($_FILES['dashboard_labs_banner']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "dashboard_labs_banner" . '.' . $ext;
                $newBgname = $uploaddir . $config['dashboard_labs_banner'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('dashboard_labs_banner', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("dashboard_labs_banner", $result['file_name']);
                    $status = "success";
                    $message = __("Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['pricingBrandLogo']) && $_FILES['pricingBrandLogo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['pricingBrandLogo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['pricingBrandLogo']['name']);
                $size = filesize($_FILES['pricingBrandLogo']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "pricingBrandLogo" . '.' . $ext;
                $newBgname = $uploaddir . $config['pricingBrandLogo'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('pricingBrandLogo', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("pricingBrandLogo", $result['file_name']);
                    $status = "success";
                    $message = __("Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['feedbackAvatar_1']) && $_FILES['feedbackAvatar_1']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['feedbackAvatar_1']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['feedbackAvatar_1']['name']);
                $size = filesize($_FILES['feedbackAvatar_1']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "feedbackAvatar_1" . '.' . $ext;
                $newBgname = $uploaddir . $config['feedbackAvatar_1'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('feedbackAvatar_1', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("feedbackAvatar_1", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['feedbackAvatar_2']) && $_FILES['feedbackAvatar_2']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['feedbackAvatar_2']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['feedbackAvatar_2']['name']);
                $size = filesize($_FILES['feedbackAvatar_2']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "feedbackAvatar_2" . '.' . $ext;
                $newBgname = $uploaddir . $config['feedbackAvatar_2'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('feedbackAvatar_2', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("feedbackAvatar_2", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['feedbackAvatar_3']) && $_FILES['feedbackAvatar_3']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['feedbackAvatar_3']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['feedbackAvatar_3']['name']);
                $size = filesize($_FILES['feedbackAvatar_3']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "feedbackAvatar_3" . '.' . $ext;
                $newBgname = $uploaddir . $config['feedbackAvatar_3'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('feedbackAvatar_3', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("feedbackAvatar_3", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f1Brand1']) && $_FILES['f1Brand1']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f1Brand1']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f1Brand1']['name']);
                $size = filesize($_FILES['f1Brand1']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f1Brand1" . '.' . $ext;
                $newBgname = $uploaddir . $config['f1Brand1'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f1Brand1', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f1Brand1", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f1Brand2']) && $_FILES['f1Brand2']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f1Brand2']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f1Brand2']['name']);
                $size = filesize($_FILES['f1Brand2']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f1Brand2" . '.' . $ext;
                $newBgname = $uploaddir . $config['f1Brand2'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f1Brand2', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f1Brand2", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }
        if (isset($_FILES['f1Brand3']) && $_FILES['f1Brand3']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f1Brand3']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f1Brand3']['name']);
                $size = filesize($_FILES['f1Brand3']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f1Brand3" . '.' . $ext;
                $newBgname = $uploaddir . $config['f1Brand3'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f1Brand3', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f1Brand3", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f1Brand4']) && $_FILES['f1Brand4']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f1Brand4']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f1Brand4']['name']);
                $size = filesize($_FILES['f1Brand4']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f1Brand4" . '.' . $ext;
                $newBgname = $uploaddir . $config['f1Brand4'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f1Brand4', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f1Brand4", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f1Brand5']) && $_FILES['f1Brand5']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f1Brand5']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f1Brand5']['name']);
                $size = filesize($_FILES['f1Brand5']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f1Brand5" . '.' . $ext;
                $newBgname = $uploaddir . $config['f1Brand5'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f1Brand5', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f1Brand5", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f1Brand6']) && $_FILES['f1Brand6']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['f1Brand6']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (in_array($ext, $valid_formats)) {

                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['f1Brand6']['name']);
                $size = filesize($_FILES['f1Brand6']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "f1Brand6" . '.' . $ext;
                $newBgname = $uploaddir . $config['f1Brand6'];
                // //Moving file to uploads folder
                if (file_exists($newBgname)) {
                    unlink($newBgname);
                }
                $result = quick_file_upload('f1Brand6', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("f1Brand6", $result['file_name']);
                    $status = "success";
                    $message = __("Avatat Image updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['f3Brand1']) && $_FILES['f3Brand1']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f3Brand1']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f3Brand1']['name']);
                    $size = filesize($_FILES['f3Brand1']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f3Brand1" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f3Brand1'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f3Brand1', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f3Brand1", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f5Brand1']) && $_FILES['f5Brand1']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f5Brand1']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f5Brand1']['name']);
                    $size = filesize($_FILES['f5Brand1']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f5Brand1" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f5Brand1'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f5Brand1', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f5Brand1", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f6Cart1']) && $_FILES['f6Cart1']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart1']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart1']['name']);
                    $size = filesize($_FILES['f6Cart1']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart1" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart1'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart1', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart1", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }
        if (isset($_FILES['f6Cart2']) && $_FILES['f6Cart2']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart2']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart2']['name']);
                    $size = filesize($_FILES['f6Cart2']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart2" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart2'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart2', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart2", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f6Cart3']) && $_FILES['f6Cart3']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart3']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart3']['name']);
                    $size = filesize($_FILES['f6Cart3']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart3" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart3'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart3', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart3", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f6Cart4']) && $_FILES['f6Cart4']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart4']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart4']['name']);
                    $size = filesize($_FILES['f6Cart4']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart4" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart4'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart4', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart4", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f6Cart5']) && $_FILES['f6Cart5']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart5']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart5']['name']);
                    $size = filesize($_FILES['f6Cart5']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart5" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart5'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart5', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart5", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f6Cart6']) && $_FILES['f6Cart6']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart6']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart6']['name']);
                    $size = filesize($_FILES['f6Cart6']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart6" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart6'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart6', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart6", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f6Cart7']) && $_FILES['f6Cart7']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f6Cart7']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f6Cart7']['name']);
                    $size = filesize($_FILES['f6Cart7']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f6Cart7" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f6Cart7'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f6Cart7', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f6Cart7", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon1']) && $_FILES['f7Icon1']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon1']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon1']['name']);
                    $size = filesize($_FILES['f7Icon1']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon1" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon1'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon1', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon1", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon2']) && $_FILES['f7Icon2']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon2']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon2']['name']);
                    $size = filesize($_FILES['f7Icon2']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon2" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon2'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon2', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon2", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon3']) && $_FILES['f7Icon3']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon3']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon3']['name']);
                    $size = filesize($_FILES['f7Icon3']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon3" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon3'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon3', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon3", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon4']) && $_FILES['f7Icon4']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon4']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon4']['name']);
                    $size = filesize($_FILES['f7Icon4']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon4" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon4'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon4', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon4", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon5']) && $_FILES['f7Icon5']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon5']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon5']['name']);
                    $size = filesize($_FILES['f7Icon5']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon5" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon5'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon5', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon5", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon6']) && $_FILES['f7Icon6']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon6']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon6']['name']);
                    $size = filesize($_FILES['f7Icon6']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon6" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon6'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon6', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon6", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon7']) && $_FILES['f7Icon7']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon7']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon7']['name']);
                    $size = filesize($_FILES['f7Icon7']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon7" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon7'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon7', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon7", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon8']) && $_FILES['f7Icon8']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon8']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon8']['name']);
                    $size = filesize($_FILES['f7Icon8']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon8" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon8'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon8', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon8", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon9']) && $_FILES['f7Icon9']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon9']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon9']['name']);
                    $size = filesize($_FILES['f7Icon9']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon9" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon9'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon9', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon9", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon10']) && $_FILES['f7Icon10']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon10']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon10']['name']);
                    $size = filesize($_FILES['f7Icon10']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon10" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon10'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon10', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon10", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon11']) && $_FILES['f7Icon11']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon11']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon11']['name']);
                    $size = filesize($_FILES['f7Icon11']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon11" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon11'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon11', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon11", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon12']) && $_FILES['f7Icon12']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon12']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon12']['name']);
                    $size = filesize($_FILES['f7Icon12']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon12" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon12'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon12', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon12", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon13']) && $_FILES['f7Icon13']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon13']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon13']['name']);
                    $size = filesize($_FILES['f7Icon13']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon13" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon13'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon13', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon13", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon14']) && $_FILES['f7Icon14']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon14']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon14']['name']);
                    $size = filesize($_FILES['f7Icon14']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon14" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon14'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon14', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon14", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon15']) && $_FILES['f7Icon15']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon15']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon15']['name']);
                    $size = filesize($_FILES['f7Icon15']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon15" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon15'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon15', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon15", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon16']) && $_FILES['f7Icon16']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon16']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon16']['name']);
                    $size = filesize($_FILES['f7Icon16']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon16" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon16'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon16', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon16", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon17']) && $_FILES['f7Icon17']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon17']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon17']['name']);
                    $size = filesize($_FILES['f7Icon17']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon17" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon17'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon17', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon17", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon18']) && $_FILES['f7Icon18']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon18']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon18']['name']);
                    $size = filesize($_FILES['f7Icon18']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon18" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon18'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon18', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon18", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon19']) && $_FILES['f7Icon19']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon19']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon19']['name']);
                    $size = filesize($_FILES['f7Icon19']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon19" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon19'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon19', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon19", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon20']) && $_FILES['f7Icon20']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon20']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon20']['name']);
                    $size = filesize($_FILES['f7Icon20']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon20" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon20'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon20', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon20", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon21']) && $_FILES['f7Icon21']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon21']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon21']['name']);
                    $size = filesize($_FILES['f7Icon21']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon21" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon21'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon21', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon21", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon22']) && $_FILES['f7Icon22']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon22']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon22']['name']);
                    $size = filesize($_FILES['f7Icon22']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon22" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon22'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon22', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon22", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon23']) && $_FILES['f7Icon23']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon23']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon23']['name']);
                    $size = filesize($_FILES['f7Icon23']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon23" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon23'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon23', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon23", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon24']) && $_FILES['f7Icon24']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon24']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon24']['name']);
                    $size = filesize($_FILES['f7Icon24']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon24" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon24'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon24', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon24", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon25']) && $_FILES['f7Icon25']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon25']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon25']['name']);
                    $size = filesize($_FILES['f7Icon25']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon25" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon25'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon25', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon25", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon26']) && $_FILES['f7Icon26']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon26']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon26']['name']);
                    $size = filesize($_FILES['f7Icon26']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon26" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon26'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon26', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon26", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon27']) && $_FILES['f7Icon27']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon27']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon27']['name']);
                    $size = filesize($_FILES['f7Icon27']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon27" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon27'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon27', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon27", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['f7Icon28']) && $_FILES['f7Icon28']['tmp_name'] != "") {
            try {
                $filename = stripslashes($_FILES['f7Icon28']['name']);
                $ext = getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {

                    $uploaddir = "../storage/logo/"; //Image upload directory
                    $filename = stripslashes($_FILES['f7Icon28']['name']);
                    $size = filesize($_FILES['f7Icon28']['tmp_name']);
                    //Convert extension into a lower case format

                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    $image_name = "f7Icon28" . '.' . $ext;
                    $newBgname = $uploaddir . $config['f7Icon28'];
                    // //Moving file to uploads folder
                    if (file_exists($newBgname)) {
                        unlink($newBgname);
                    }
                    $result = quick_file_upload('f7Icon28', $uploaddir);
                    //Moving file to uploads folder
                    if ($result['success']) {
                        update_option_value("f7Icon28", $result['file_name']);
                        $status = "success";
                        $message = __("Avatat Image updated Successfully");
                    } else {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                } else {
                    $status = "error";
                    $message = __("Only allowed jpg, jpeg, png");
                }
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }
        }

        if (isset($_FILES['favicon']) && $_FILES['favicon']['tmp_name'] != "") {

            $filename = stripslashes($_FILES['favicon']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['favicon']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "favicon" . '.' . $ext;
                $newLogo = $uploaddir . $config['site_favicon'];
                if (file_exists($newLogo) && !is_dir($newLogo)) {
                    unlink($newLogo);
                }
                $result = quick_file_upload('favicon', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("site_favicon", $result['file_name']);
                    $status = "success";
                    $message = __("Site Favicon icon updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['file']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name'] . "_logo" . '.' . $ext;
                $newLogo = $uploaddir . $config['site_logo'];
                if (file_exists($newLogo) && !is_dir($newLogo)) {
                    unlink($newLogo);
                }
                $result = quick_file_upload('file', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("site_logo", $result['file_name']);
                    $status = "success";
                    $message = __("Site Logo updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['footer_logo']) && $_FILES['footer_logo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['footer_logo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['footer_logo']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name'] . "_footer_logo" . '.' . $ext;
                $newLogo = $uploaddir . $config['site_logo_footer'];
                if (file_exists($newLogo) && !is_dir($newLogo)) {
                    unlink($newLogo);
                }
                $result = quick_file_upload('footer_logo', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {

                    update_option_value("site_logo_footer", $result['file_name']);
                    $status = "success";
                    $message = __("Site Logo updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['dark_logo']) && $_FILES['dark_logo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['dark_logo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['dark_logo']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name'] . "dark_logo" . '.' . $ext;
                $newLogo = $uploaddir . $config['image_dark_logo'];
                if (file_exists($newLogo) && !is_dir($newLogo)) {
                    unlink($newLogo);
                }
                $result = quick_file_upload('dark_logo', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {

                    update_option_value("image_dark_logo", $result['file_name']);
                    $status = "success";
                    $message = __("Site Logo for Dark Theme updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if (isset($_FILES['adminlogo']) && $_FILES['adminlogo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['adminlogo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['adminlogo']['name']);
                $size = filesize($_FILES['adminlogo']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $adminlogo_name = "adminlogo" . '.' . $ext;
                $adminlogo = $uploaddir . $config['site_admin_logo'];
                if (file_exists($adminlogo) && !is_dir($adminlogo)) {
                    unlink($adminlogo);
                }
                $result = quick_file_upload('adminlogo', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("site_admin_logo", $result['file_name']);
                    $status = "success";
                    $message = __("Admin Logo updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if ($status == "") {
            $status = "success";
            $message = __("Saved Successfully");
        }
    }

    if (isset($_POST['general_setting'])) {
        $_POST['site_url'] = rtrim($_POST['site_url'], '/') . '/';
        update_option_value("site_url", $_POST['site_url']);
        update_option_value("site_title", $_POST['site_title']);
        // // update_option_value("disable_landing_page", $_POST['disable_landing_page']);
        // // update_option_value("enable_maintenance_mode", $_POST['enable_maintenance_mode']);
        // // update_option_value("enable_user_registration", $_POST['enable_user_registration']);
        // // update_option_value("enable_faqs", $_POST['enable_faqs']);
        // // update_option_value("non_active_msg",$_POST['non_active_msg']);
        // update_option_value("non_active_allow",$_POST['non_active_allow']);
        // update_option_value("transfer_filter",$_POST['transfer_filter']);
        update_option_value("default_user_plan", validate_input($_POST['default_user_plan']));
        // // update_option_value("hide_plan_disabled_features",validate_input($_POST['hide_plan_disabled_features']));
        // update_option_value("cron_exec_time",validate_input($_POST['cron_exec_time']));
        update_option_value("userlangsel", $_POST['userlangsel']);
        update_option_value("termcondition_link", validate_input($_POST['termcondition_link']));
        update_option_value("privacy_link", validate_input($_POST['privacy_link']));
        update_option_value("cookie_link", validate_input($_POST['cookie_link']));
        // // update_option_value("after_login_link",validate_input($_POST['after_login_link']));
        // update_option_value("cookie_consent",$_POST['cookie_consent']);
        // // update_option_value("quickad_debug",$_POST['quickad_debug']);
        update_option_value("developer_credit", $_POST['developer_credit']);
        update_option_value("onboarding_video_link", $_POST['onboarding_video_link']);
        update_option_value("daily_tech_calls_link", $_POST['daily_tech_calls_link']);
        update_option_value("ai_ployee_marketplace_link", $_POST['ai_ployee_marketplace_link']);
        update_option_value("uai_quick_video_link", $_POST['uai_quick_video_link']);
        update_option_value("uai_learn_more_link", $_POST['uai_learn_more_link']);
        update_option_value("dashboard_learn_more_link", $_POST['dashboard_learn_more_link']);
        update_option_value("login_video_link", $_POST['login_video_link']);
        update_option_value("website_video_link", $_POST['website_video_link']);
        update_option_value("faq_url", $_POST['faq_link']);
        update_option_value("cta_link", $_POST['cta_link']);

        // store general video 
        $valid_formats = array("mp4"); // Valid image formats
        if (isset($_FILES['onboarding_video_file']) && $_FILES['onboarding_video_file']['name'] != "") {

            $filename = stripslashes($_FILES['onboarding_video_file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/video/"; //Image upload directory
                $filename = stripslashes($_FILES['onboarding_video_file']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name'] . "_video" . '.' . $ext;
                $newVideo = $uploaddir . $config['onboarding_video_file'];

                if (file_exists($newVideo) && !is_dir($newVideo)) {
                    chmod($newVideo, 0777);
                    unlink($newVideo);
                }
                $result = quick_file_upload('onboarding_video_file', $uploaddir, "video/*");
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("onboarding_video_file", $result['file_name']);
                    $status = "success";
                    $message = __("General video updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed mp4");
            }
        }

        if (isset($_FILES['login_video_file']) && $_FILES['login_video_file']['name'] != "") {

            $filename = stripslashes($_FILES['login_video_file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/video/"; //Image upload directory
                $filename = stripslashes($_FILES['login_video_file']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name'] . "_video" . '.' . $ext;
                $newVideo = $uploaddir . $config['login_video_file'];

                if (file_exists($newVideo) && !is_dir($newVideo)) {
                    chmod($newVideo, 0777);
                    unlink($newVideo);
                }
                $result = quick_file_upload('login_video_file', $uploaddir, "video/*");
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("login_video_file", $result['file_name']);
                    $status = "success";
                    $message = __("Login video updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed mp4");
            }
        }

        if (isset($_FILES['website_video_file']) && $_FILES['website_video_file']['name'] != "") {

            $filename = stripslashes($_FILES['website_video_file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/video/"; //Image upload directory
                $filename = stripslashes($_FILES['website_video_file']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name'] . "_video" . '.' . $ext;
                $newVideo = $uploaddir . $config['website_video_file'];

                if (file_exists($newVideo) && !is_dir($newVideo)) {
                    chmod($newVideo, 0777);
                    unlink($newVideo);
                }
                $result = quick_file_upload('website_video_file', $uploaddir, "video/*");
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("website_video_file", $result['file_name']);
                    $status = "success";
                    $message = __("Website video updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed mp4");
            }
        }

        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['quick_map'])) {
        update_option_value("map_type", validate_input($_POST['map_type']));
        update_option_value("openstreet_access_token", validate_input($_POST['openstreet_access_token']));
        update_option_value("gmap_api_key", validate_input($_POST['gmap_api_key']));
        update_option_value("map_color", validate_input($_POST['map_color']));
        update_option_value("home_map_latitude", validate_input($_POST['home_map_latitude']));
        update_option_value("home_map_longitude", validate_input($_POST['home_map_longitude']));
        update_option_value("contact_latitude", validate_input($_POST['contact_latitude']));
        update_option_value("contact_longitude", validate_input($_POST['contact_longitude']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['international'])) {

        if (isset($_POST['currency'])) {
            $info = ORM::for_table($config['db']['pre'] . 'currencies')->find_one($_POST['currency']);
            $currency_sign = $info['html_entity'];
            $currency_code = $info['code'];
            $currency_pos = $info['in_left'];
        }

        update_option_value("specific_country", $_POST['specific_country']);
        update_option_value("lang", $_POST['lang']);
        update_option_value("browser_lang", (int) $_POST['browser_lang']);
        update_option_value("timezone", $_POST['timezone']);
        update_option_value("currency_sign", $currency_sign);
        update_option_value("currency_code", $currency_code);
        update_option_value("currency_pos", $currency_pos);
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['email_setting'])) {

        update_option_value("admin_email", $_POST['admin_email']);
        update_option_value("email_pub_key", $_POST['email_pub_key']);
        update_option_value("email_pri_key", $_POST['email_pri_key']);
        update_option_value("support_email", $_POST['support_email']);

        // update_option_value("smtp_host", $_POST['smtp_host']);
        // update_option_value("smtp_port", $_POST['smtp_port']);
        // update_option_value("smtp_username", $_POST['smtp_username']);
        // update_option_value("smtp_password", $_POST['smtp_password']);
        // update_option_value("smtp_secure", $_POST['smtp_secure']);
        // update_option_value("smtp_auth", $_POST['smtp_auth']);


        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['theme_setting'])) {
        // update_option_value("show_membershipplan_home",validate_input($_POST['show_membershipplan_home']));
        // update_option_value("show_partner_logo_home",validate_input($_POST['show_partner_logo_home']));
        // update_option_value("meta_keywords",validate_input($_POST['meta_keywords']));
        // update_option_value("meta_description",validate_input($_POST['meta_description']));
        // update_option_value("external_code",$_POST['external_code']);
        // update_option_value("pinterest_link",validate_input($_POST['pinterest_link']));

        update_option_value("theme_color", validate_input($_POST['theme_color']));
        update_option_value("ai_employee_card_color", validate_input($_POST['ai_employee_card_color']));
        update_option_value("ai_employee_bio_letter_color", validate_input($_POST['ai_employee_bio_letter_color']));
        update_option_value("footer_bg_color", validate_input($_POST['footer_bg_color']));
        update_option_value("footer_letter_color", validate_input($_POST['footer_letter_color']));
        update_option_value("footer_icon_color", validate_input($_POST['footer_icon_color']));
        update_option_value("uai_quick_board_color", validate_input($_POST['uai_quick_board_color']));
        update_option_value("dashboard_quick_board_color", validate_input($_POST['dashboard_quick_board_color']));
        update_option_value("dashboard_quick_letter_color", validate_input($_POST['dashboard_quick_letter_color']));
        update_option_value("uai_quick_letter_color", validate_input($_POST['uai_quick_letter_color']));
        update_option_value("uai_quick_url_color", validate_input($_POST['uai_quick_url_color']));
        update_option_value("embed_preboard_start_btn_color", validate_input($_POST['embed_preboard_start_btn_color']));
        update_option_value("dashboard_quick_url_color", validate_input($_POST['dashboard_quick_url_color']));
        update_option_value("contact_address", validate_input($_POST['contact_address']));
        update_option_value("contact_phone", validate_input($_POST['contact_phone']));
        update_option_value("contact_email", validate_input($_POST['contact_email']));
        update_option_value("footer_text", validate_input($_POST['footer_text']));
        update_option_value("uai_quick_guide_text", validate_input($_POST['uai_quick_guide_text']));
        update_option_value("dashboard_quick_guide_text", validate_input($_POST['dashboard_quick_guide_text']));
        update_option_value("android_app_link", validate_input($_POST['android_app_link']));
        update_option_value("ios_app_link", validate_input($_POST['ios_app_link']));
        update_option_value("copyright_text", validate_input($_POST['copyright_text']));
        update_option_value("uai_quick_guide_title", validate_input($_POST['uai_quick_guide_title']));
        update_option_value("embed_chat_button_text", validate_input($_POST['embed_chat_button_text']));
        update_option_value("dashboard_quick_guide_title", validate_input($_POST['dashboard_quick_guide_title']));
        update_option_value("facebook_link", validate_input($_POST['facebook_link']));
        update_option_value("twitter_link", validate_input($_POST['twitter_link']));
        update_option_value("instagram_link", validate_input($_POST['instagram_link']));
        update_option_value("linkedin_link", validate_input($_POST['linkedin_link']));
        update_option_value("youtube_link", validate_input($_POST['youtube_link']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['billing_details'])) {
        update_option_value("enable_tax_billing", validate_input($_POST['enable_tax_billing']));
        update_option_value("invoice_nr_prefix", validate_input($_POST['invoice_nr_prefix']));
        update_option_value("invoice_admin_name", validate_input($_POST['invoice_admin_name']));
        update_option_value("invoice_admin_email", validate_input($_POST['invoice_admin_email']));
        update_option_value("invoice_admin_phone", validate_input($_POST['invoice_admin_phone']));
        update_option_value("invoice_admin_address", validate_input($_POST['invoice_admin_address']));
        update_option_value("invoice_admin_city", validate_input($_POST['invoice_admin_city']));
        update_option_value("invoice_admin_state", validate_input($_POST['invoice_admin_state']));
        update_option_value("invoice_admin_zipcode", validate_input($_POST['invoice_admin_zipcode']));
        update_option_value("invoice_admin_country", validate_input($_POST['invoice_admin_country']));
        update_option_value("invoice_admin_tax_type", validate_input($_POST['invoice_admin_tax_type']));
        update_option_value("invoice_admin_tax_id", validate_input($_POST['invoice_admin_tax_id']));

        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['ai_settings'])) {
        // update_option_value("bad_words", validate_input($_POST['bad_words']));
        update_option_value("single_model_for_plans", (int) validate_input($_POST['single_model_for_plans']));
        update_option_value("open_ai_model", validate_input($_POST['open_ai_model']));

        update_option_value("open_ai_api_key", validate_input($_POST['open_ai_api_key']));
        update_option_value("enable_ai_templates", validate_input($_POST['enable_ai_templates']));
        update_option_value("ai_languages", validate_input($_POST['ai_languages']));
        update_option_value("ai_default_lang", validate_input($_POST['ai_default_lang']));
        update_option_value("ai_default_quality_type", validate_input($_POST['ai_default_quality_type']));
        update_option_value("ai_default_tone_voice", validate_input($_POST['ai_default_tone_voice']));
        update_option_value("ai_default_max_langth", validate_input((int)$_POST['ai_default_max_langth']));

        update_option_value("enable_ai_images", validate_input($_POST['enable_ai_images']));
        update_option_value("ai_image_api", validate_input($_POST['ai_image_api']));
        update_option_value("ai_image_api_key", validate_input($_POST['ai_image_api_key']));
        update_option_value("show_ai_images_home", validate_input($_POST['show_ai_images_home']));
        update_option_value("ai_images_home_limit", (int)validate_input($_POST['ai_images_home_limit']));

        update_option_value("enable_speech_to_text", validate_input($_POST['enable_speech_to_text']));

        update_option_value("enable_ai_code", validate_input($_POST['enable_ai_code']));
        update_option_value("ai_code_max_token", validate_input($_POST['ai_code_max_token']));

        update_option_value("enable_ai_chat", validate_input($_POST['enable_ai_chat']));
        update_option_value("open_ai_chat_model", validate_input($_POST['open_ai_chat_model']));
        update_option_value("ai_chat_max_token", validate_input($_POST['ai_chat_max_token']));
        update_option_value("enable_default_chat_bot", validate_input($_POST['enable_default_chat_bot']));
        update_option_value("ai_chat_bot_name", validate_input($_POST['ai_chat_bot_name']));
        update_option_value("enable_ai_chat_mic", validate_input($_POST['enable_ai_chat_mic']));
        update_option_value("enable_ai_chat_prompts", validate_input($_POST['enable_ai_chat_prompts']));
        // update_option_value("enable_chat_typing_effect", validate_input($_POST['enable_chat_typing_effect']));
        update_option_value("enable_ai_chat_enter_send", validate_input($_POST['enable_ai_chat_enter_send']));

        update_option_value("enable_text_to_speech", validate_input($_POST['enable_text_to_speech']));
        update_option_value("ai_tts_language", validate_input($_POST['ai_tts_language']));
        update_option_value("ai_tts_voice", validate_input($_POST['ai_tts_voice']));
        update_option_value("ai_tts_aws_access_key", validate_input($_POST['ai_tts_aws_access_key']));
        update_option_value("ai_tts_aws_secret_key", validate_input($_POST['ai_tts_aws_secret_key']));
        update_option_value("ai_tts_aws_region", validate_input($_POST['ai_tts_aws_region']));

        update_option_value("ai_proxies", validate_input($_POST['ai_proxies']));

        $valid_formats = array("jpg", "jpeg", "png"); // Valid image formats
        if (isset($_FILES['chat_bot_avatar']) && $_FILES['chat_bot_avatar']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['chat_bot_avatar']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/profile/"; //Image upload directory
                $filename = stripslashes($_FILES['chat_bot_avatar']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $newLogo = $uploaddir . $config['chat_bot_avatar'];
                if (file_exists($newLogo) && !is_dir($newLogo)) {
                    unlink($newLogo);
                }
                $result = quick_file_upload('chat_bot_avatar', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option_value("chat_bot_avatar", $result['file_name']);
                    resizeImage(300, $uploaddir . $result['file_name'], $uploaddir . $result['file_name']);
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            } else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }
        }

        if ($status == "") {
            $status = "success";
            $message = __("Saved Successfully");
        }
    }

    if (isset($_POST['affiliate_program_settings'])) {
        update_option_value("enable_affiliate_program", validate_input($_POST['enable_affiliate_program']));
        update_option_value("affiliate_rule", validate_input($_POST['affiliate_rule']));
        update_option_value("affiliate_commission_rate", validate_input((int) $_POST['affiliate_commission_rate']));
        update_option_value("allow_affiliate_payouts", validate_input((int) $_POST['allow_affiliate_payouts']));
        update_option_value("affiliate_minimum_payout", validate_input((int) $_POST['affiliate_minimum_payout']));
        update_option_value("affiliate_payout_methods", validate_input($_POST['affiliate_payout_methods']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['live_chat_settings'])) {
        update_option_value("enable_live_chat", validate_input((int) $_POST['enable_live_chat']));
        update_option_value("tawkto_chat_link", validate_input($_POST['tawkto_chat_link']));
        update_option_value("tawkto_membership", validate_input((int) $_POST['tawkto_membership']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['social_login_setting'])) {
        update_option_value("facebook_app_id", validate_input($_POST['facebook_app_id']));
        update_option_value("facebook_app_secret", validate_input($_POST['facebook_app_secret']));
        update_option_value("google_app_id", validate_input($_POST['google_app_id']));
        update_option_value("google_app_secret", validate_input($_POST['google_app_secret']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['recaptcha_setting'])) {

        update_option_value("recaptcha_mode", validate_input($_POST['recaptcha_mode']));
        update_option_value("recaptcha_public_key", validate_input($_POST['recaptcha_public_key']));
        update_option_value("recaptcha_private_key", validate_input($_POST['recaptcha_private_key']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['blog_setting'])) {

        update_option_value("blog_enable", validate_input($_POST['blog_enable']));
        update_option_value("blog_banner", validate_input($_POST['blog_banner']));
        update_option_value("show_blog_home", validate_input($_POST['show_blog_home']));
        update_option_value("blog_page_limit", validate_input((int) $_POST['blog_page_limit']));
        update_option_value("blog_comment_enable", validate_input($_POST['blog_comment_enable']));
        update_option_value("blog_comment_approval", validate_input($_POST['blog_comment_approval']));
        update_option_value("blog_comment_user", validate_input($_POST['blog_comment_user']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['testimonials_setting'])) {

        update_option_value("testimonials_enable", validate_input($_POST['testimonials_enable']));
        update_option_value("show_testimonials_blog", validate_input($_POST['show_testimonials_blog']));
        update_option_value("show_testimonials_home", validate_input($_POST['show_testimonials_home']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['valid_purchase_setting'])) {

        // Set API Key
        $code = $_POST['purchase_key'];
        $buyer_email = (isset($_POST['buyer_email'])) ? validate_input($_POST['buyer_email']) : "";
        $installing_version = 'pro';


        $url = "https://bylancer.com/api/api.php?verify-purchase=" . $code . "&version=" . $installing_version . "&site_url=" . $config['site_url'] . "&email=" . $buyer_email;
        // Open cURL channel
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Set the user agent
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Decode returned JSON
        $output = json_decode(curl_exec($ch), true);
        // Close Channel
        curl_close($ch);

        if ($output['success']) {
            if (isset($config['quickad_secret_file']) && $config['quickad_secret_file'] != "") {
                $fileName = $config['quickad_secret_file'];
            } else {
                $fileName = get_random_string();
            }

            if (isset($config['quickad_user_secret_file']) && $config['quickad_user_secret_file'] != "") {
                $userFileName = $config['quickad_user_secret_file'];
            } else {
                $userFileName = get_random_string();
            }
            file_put_contents($fileName . '.php', $output['data']);
            file_put_contents('../php/' . $userFileName . '.php', $output['user_data']);
            $success = true;

            echo "<script>console.log('$fileName')</script>";

            update_option_value("quickad_secret_file", $fileName);
            update_option_value("quickad_user_secret_file", $userFileName);


            update_option_value("purchase_key", $_POST['purchase_key']);
            update_option_value("purchase_type", $output['purchase_type']);
            $status = "success";
            $message = __("Purchase code verified successfully");
        } else {
            $status = "error";
            $message = $output['error'];
        }
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function delete_video()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $video = ORM::for_table($config['db']['pre'] . 'options')
            ->where('option_name', $_POST['option_name'])
            ->find_one();

        if (!empty($video->option_value)) {
            $video_dir = "../storage/video/";
            $main_video = trim((string) $video->option_value);
            // delete video
            if (!empty($main_video)) {
                $file = $video_dir . $main_video;
                if (file_exists($file))
                    unlink($file);
            }

            $pdo = ORM::get_db();
            $sql = "UPDATE `" . $config['db']['pre'] . "options` SET `option_value` = '" . NULL . "' WHERE `option_name` = '" . $_POST['option_name'] . "' ";

            if ($pdo->query($sql)) {
                $result['success'] = true;
                $result['message'] = __('Deleted Successfully');
                die(json_encode($result));
            } else {
                $result['success'] = false;
                $result['message'] = __('Failed to delete');
                die(json_encode($result));
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

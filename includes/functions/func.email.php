<?php

/**
 * Send email with templates
 *
 * @param string $template
 * @param int|null $user_id
 * @param string|null $password
 * @param int|null $product_id
 * @param string|null $item_title
 */

function email_send($recipient_email, $sender_email, $subject, $html_content)
{
    global $config, $lang, $link;
    $apikey = $config['email_pub_key'];
    $apisecret = $config['email_pri_key'];

    $url = 'https://api.mailjet.com/v3.1/send';

    $data = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $sender_email,
                ],
                'To' => [
                    [
                        'Email' => $recipient_email,
                    ],
                ],
                'Subject' => $subject,
                'HTMLPart' => $html_content,  // Use 'HTMLPart' for HTML content
            ],
        ],
    ];

    $json_data = json_encode($data);

    // Send the email
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, "$apikey:$apisecret");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
    $response = curl_exec($ch);

    if ($response === false) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code === 200) {
            return true;
        } else {
            return false;
        }
    }
    
    curl_close($ch);
}

function email_template($template, $user_id = null, $password = null, $product_id = null, $item_title = null)
{
    global $config, $lang, $link;

    if ($user_id != null) {
        $userdata = get_user_data(null, $user_id);
        $username = $userdata['username'];
        $user_email = $userdata['email'];
        $user_fullname = $userdata['name'];
        $confirm_id =  $userdata['confirm'];
    }

    // GET SUBJECT & MESSAGE FROM DATABASE BASED ON EMAIL TEMPLATE KEYWORD
    $data = ORM::for_table($config['db']['pre'] . 'email_template')
                ->select_many('subject','message')
                ->where('keyword', $template)
                ->find_one();

    /*SEND ACCOUNT DETAILS EMAIL*/
    if ($template == "signup-details") {

        // $html = $config['email_sub_signup_details'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $html = str_replace('{PASSWORD}', $password, $html);
        $email_subject = $html;

        // $html = $config['email_message_signup_details'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $html = str_replace('{PASSWORD}', $password, $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;

        /* //Send 1 copy to admin
        *  //Uncomment if you want admin notify on register
        *  email($config['admin_email'],$config['site_title'],$email_subject,$email_body);
        * */
    }

    /*SEND CONFIRMATION EMAIL*/
    if ($template == "create-account") {
        // $html = $config['email_sub_signup_confirm'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $email_subject = $html;

        $confirmation_link = $link['SIGNUP'] . "?confirm=" . $confirm_id . "&user=" . $user_id;
        // $html = $config['email_message_signup_confirm'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $html = str_replace('{CONFIRMATION_LINK}', $confirmation_link, $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;
    }

    /*SEND AD APPROVE EMAIL*/
    if ($template == "ad_approve") {
        $ad_link = $link['POST-DETAIL'] . "/" . $product_id;

        // $html = $config['email_sub_ad_approve'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{ADTITLE}', $item_title, $html);
        $html = str_replace('{SELLER_NAME}', $user_fullname, $html);
        $html = str_replace('{SELLER_EMAIL}', $user_email, $html);
        $email_subject = $html;

        // $html = $config['email_message_ad_approve'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{ADTITLE}', $item_title, $html);
        $html = str_replace('{ADLINK}', $ad_link, $html);
        $html = str_replace('{SELLER_NAME}', $user_fullname, $html);
        $html = str_replace('{SELLER_EMAIL}', $user_email, $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;
    }

    /*SEND RESUBMISSION AD APPROVE EMAIL*/
    if ($template == "re_ad_approve") {
        $ad_link = $link['POST-DETAIL'] . "/" . $product_id;

        // $html = $config['email_sub_re_ad_approve'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{ADTITLE}', $item_title, $html);
        $html = str_replace('{ADLINK}', $ad_link, $html);
        $html = str_replace('{SELLER_NAME}', $user_fullname, $html);
        $html = str_replace('{SELLER_EMAIL}', $user_email, $html);
        $email_subject = $html;

        // $html = $config['email_message_re_ad_approve'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{ADTITLE}', $item_title, $html);
        $html = str_replace('{ADLINK}', $ad_link, $html);
        $html = str_replace('{SELLER_NAME}', $user_fullname, $html);
        $html = str_replace('{SELLER_EMAIL}', $user_email, $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;
    }

    /*SEND CONTACT EMAIL TO SELLER*/
    if ($template == "contact_seller") {
        $ad_link = $link['POST-DETAIL'] . "/" . $product_id;

        // $html = $config['email_sub_contact_seller'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{ADTITLE}', $item_title, $html);
        $html = str_replace('{ADLINK}', $ad_link, $html);
        $html = str_replace('{SELLER_NAME}', $user_fullname, $html);
        $html = str_replace('{SELLER_EMAIL}', $user_email, $html);
        $html = str_replace('{SENDER_NAME}', $_POST['name'], $html);
        $html = str_replace('{SENDER_EMAIL}', $_POST['email'], $html);
        $html = str_replace('{SENDER_PHONE}', $_POST['phone'], $html);
        $email_subject = $html;

        // $html = $config['email_message_contact_seller'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{ADTITLE}', $item_title, $html);
        $html = str_replace('{ADLINK}', $ad_link, $html);
        $html = str_replace('{SELLER_NAME}', $user_fullname, $html);
        $html = str_replace('{SELLER_EMAIL}', $user_email, $html);
        $html = str_replace('{SENDER_NAME}', $_POST['name'], $html);
        $html = str_replace('{SENDER_EMAIL}', $_POST['email'], $html);
        $html = str_replace('{SENDER_PHONE}', $_POST['phone'], $html);
        $html = str_replace('{MESSAGE}', $_POST['message'], $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;
    }

    /*SEND CONTACT EMAIL TO ADMIN*/
    if ($template == "contact") {
        // $html = $config['email_sub_contact'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{CONTACT_SUBJECT}', $_POST['subject'], $html);
        $html = str_replace('{NAME}', $_POST['name'], $html);
        $html = str_replace('{EMAIL}', $_POST['email'], $html);
        $email_subject = $html;

        // $html = $config['email_message_contact'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{NAME}', $_POST['name'], $html);
        $html = str_replace('{EMAIL}', $_POST['email'], $html);
        $html = str_replace('{CONTACT_SUBJECT}', $_POST['subject'], $html);
        $html = str_replace('{MESSAGE}', $_POST['message'], $html);
        $email_body = $html;

        // email($config['admin_email'], $config['site_title'], $email_subject, $email_body);
        email_send($config['admin_email'], $config['admin_email'], $email_subject, $email_body);
        return;
    }

    /*SEND FEEDBACK TO ADMIN */
    if ($template == "feedback") {

        // $html = $config['email_sub_feedback'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{FEEDBACK_SUBJECT}', $_POST['subject'], $html);
        $html = str_replace('{NAME}', $_POST['name'], $html);
        $html = str_replace('{EMAIL}', $_POST['email'], $html);
        $email_subject = $html;

        // $html = $config['email_message_feedback'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{NAME}', $_POST['name'], $html);
        $html = str_replace('{EMAIL}', $_POST['email'], $html);
        $html = str_replace('{FEEDBACK_SUBJECT}', $_POST['subject'], $html);
        $html = str_replace('{MESSAGE}', $_POST['message'], $html);
        $email_body = $html;

        // email($config['admin_email'], $config['site_title'], $email_subject, $email_body);
        email_send($config['admin_email'], $config['admin_email'], $email_subject, $email_body);
        return;
    }

    /*SEND REPORT TO ADMIN*/
    if ($template == "report") {

        // $html = $config['email_sub_report'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{EMAIL}', $_POST['email'], $html);
        $html = str_replace('{NAME}', $_POST['name'], $html);
        $html = str_replace('{USERNAME}', $_POST['username'], $html);
        $html = str_replace('{VIOLATION}', $_POST['violation'], $html);
        $email_subject = $html;

        // $html = $config['email_message_report'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{EMAIL}', $_POST['email'], $html);
        $html = str_replace('{NAME}', $_POST['name'], $html);
        $html = str_replace('{USERNAME}', $_POST['username'], $html);
        $html = str_replace('USERNAME2', $_POST['username2'], $html);
        $html = str_replace('{VIOLATION}', $_POST['violation'], $html);
        $html = str_replace('{URL}', $_POST['url'], $html);
        $html = str_replace('{DETAILS}', $_POST['details'], $html);
        $email_body = $html;

        // email($config['admin_email'], $config['site_title'], $email_subject, $email_body);
        email_send($config['admin_email'], $config['admin_email'], $email_subject, $email_body);
        return;
    }

    if ($template == "withdraw_rejected") {
        /*User : Withdraw request rejected*/
        // $html = $config['email_sub_withdraw_rejected'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $email_subject = $html;

        // $html = $config['emailHTML_withdraw_rejected'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;
    }

    if ($template == "withdraw_accepted") {
        /*User : Withdraw request accepted*/

        // $html = $config['email_sub_withdraw_accepted'];
        $html = $data->subject;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $email_subject = $html;

        // $html = $config['emailHTML_withdraw_accepted'];
        $html = $data->message;
        $html = str_replace('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace('{USER_ID}', $user_id, $html);
        $html = str_replace('{USERNAME}', $username, $html);
        $html = str_replace('{EMAIL}', $user_email, $html);
        $html = str_replace('{USER_FULLNAME}', $user_fullname, $html);
        $email_body = $html;

        // email($user_email, $user_fullname, $email_subject, $email_body);
        email_send($user_email, $config['admin_email'], $email_subject, $email_body);
        return;
    }

    if ($template == "rating_recieved") {
        // 
    }
}

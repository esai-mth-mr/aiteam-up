<?php
global $config;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Max-Age: 86400");

// $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

// // Split the query string into key-value pairs
// $parameters = explode('?', $queryString);

// $paramArray = [];

// // Iterate through the parameters to extract key-value pairs
// foreach ($parameters as $param) {
//     list($key, $value) = explode('=', $param);
//     $paramArray[$key] = $value;
// }

$embed_id = $_GET['e'];

$stand_alone = $_GET['s'];

$is_iframe = $_GET['i'];

$height = $_GET['heigt'];
$width = $_GET['width'];

$embed = ORM::for_table($config['db']['pre'] . 'embed')->find_one($embed_id);

if (!$embed) {
    die("Use valid embed code.");
}

$user_id = $embed->user_id;
$user = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id);

if (!$user) {
    die("Use valid embed code.");
}

$bot_id = $embed->bot_id;

$bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($bot_id);

if (!$bot) {
    die("Use valid embed code.");
}

if($bot->user_id != $embed->user_id) {
    die("Use valid embed code.");
}

$embed_name = $embed->embed_name;
$uai_font_letter_color = $embed->uai_font_letter_color;
$uai_board_color = $embed->uai_board_color;
$user_font_letter_color = $embed->user_font_letter_color;
$user_board_color = $embed->user_board_color;
$autoresponder = $embed->autoresponder_list;
$user_image = $config['site_url'] . "storage/profile/default_user.png";
$user_name = $user->name;
$bot_name = $bot->name;
$bot_image = $config['site_url'] . "storage/bot_images/" . $bot->image;
$bot_role = $bot->role;
$welcome_message = $bot->welcome_message;
$embed_icon = $embed->icon;
$embed_terms = $embed->embed_terms;
$embed_start_chat_btn_background_color = $embed->embed_start_chat_btn_background_color;
$embed_start_chat_text = $embed->embed_start_chat_text;
$brand_toggle = $embed->brand_toggle;
$embed_horizental = $embed->embed_horizental;
$embed_pre_made_questions = $embed->pre_made_questions;
$questions = explode("||", $embed_pre_made_questions);

HtmlTemplate::display('embed-chat', array(
    'embed_id' => $embed_id,
    'bot_name' => $bot_name,
    'bot_image' => $bot_image,
    'bot_role' => $bot_role,
    'welcome_message' => $welcome_message,
    'embed_icon' => $embed_icon,
    'auto_responder' => $autoresponder,
    'user_image' => $user_image,
    'user_name' => $user_name,
    'uai_font_letter_color' => $uai_font_letter_color,
    'uai_board_color' => $uai_board_color,
    'user_font_letter_color' => $user_font_letter_color,
    'user_board_color' => $user_board_color,
    'brand_toggle' => $brand_toggle,
    'embed_horizental' =>$embed_horizental,
    'embed_name' => $embed_name,
    'stand_alone' => $stand_alone,
    'is_iframe' => $is_iframe,
    'height' => $height,
    'width' => $width,
    'embed_terms' => $embed_terms,
    'embed_start_chat_btn_background_color' => $embed_start_chat_btn_background_color,
    'embed_start_chat_text' => $embed_start_chat_text,
    'questions' => $questions
));

// die($bot_id . "---" . $embed->embed_website);

// // Check if other parameters exist and concatenate them


// die($bot_id . "---" . $conv_id);
// // Parse the query string into an associative array
// parse_str($queryString, $queryParams);

// // Access individual query parameters
// $bot_id = isset($queryParams['b']) ? $queryParams['b'] : null;
// $conv_id = isset($queryParams['c']) ? $queryParams['c'] : null;
// $user_id = isset($queryParams['u']) ? $queryParams['u'] : null;
// $website = isset($queryParams['website']) ? $queryParams['website'] : null;

// die($bot_id . "---" . $conv_id . "---" . $user_id . "---" . $website);
// if(isset($current_user['id']))
// {
//     // $sql = "SELECT fd.id, fd.user_id, fd.folder_name, COALESCE(SUM(doc.word_count), 0) AS word_count, COUNT(doc.directory_id) AS doc_count 
//     //     FROM `" . $config['db']['pre'] . "uai_folder` fd 
//     //     LEFT JOIN `" . $config['db']['pre'] . "uai_training_doc` doc ON doc.directory_id = fd.id 
//     //     WHERE fd.user_id = '".$_SESSION['user']['id']."' 
//     //     GROUP BY fd.id 
//     //     ORDER BY fd.id DESC";
//     // $rows = ORM::for_table($config['db']['pre'].'uai_folder')->raw_query($sql)->find_many();

//     $rows = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->order_by_desc('id')->find_many();

//     HtmlTemplate::display('uai', array(
//         'directories' => $rows,
//     ));
// }
// else{
//     headerRedirect($link['LOGIN']);
// }
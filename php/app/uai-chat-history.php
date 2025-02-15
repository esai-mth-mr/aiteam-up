<?php
global $config;

// if disabled by admin
if (!$config['enable_ai_chat']) {
    page_not_found();
}

$login_status = checkloggedin();

if (!$login_status) {
    headerRedirect($link['LOGIN']);
}

$agent_id = $_GET['id'];

$uai_agent = ORM::for_table($config['db']['pre'] . 'uai_agent')->find_one($agent_id);

if (!$uai_agent) {
    page_not_found();
}

$user_id = $uai_agent->user_id;

if ($user_id != $_SESSION['user']['id']) {
    page_not_found();
}

$bot_id = $uai_agent->ai_chat_bot_id;

$agent_name = $uai_agent->agent_name;

$query = ORM::for_table($config['db']['pre'] . 'ai_chat')
    ->where(array('user_id' => $user_id, 'bot_id' => $bot_id))
    ->order_by_desc('date');

if (!$_GET['all']) {

    $start_date_raw = isset($_GET['start']) ? $_GET['start'] : null;
    $end_date_raw = isset($_GET['end']) ? $_GET['end'] : null;

    // Parse JavaScript-style date strings into DateTime objects
    $start_date_string = ($start_date_raw !== null) ? substr($start_date_raw, 4, 11) : null;
    $end_date_string = ($end_date_raw !== null) ? substr($end_date_raw, 4, 11) : null;

    $startDateTime = DateTime::createFromFormat('M d Y', $start_date_string);
    $endDateTime = DateTime::createFromFormat('M d Y', $end_date_string);

    $start_date = $startDateTime->format('Y-m-d H:i:s');
    $end_date = $endDateTime->format('Y-m-d H:i:s');

    if ($start_date !== null) {
        $query->where_gte('date', $start_date);
    }

    if ($end_date !== null) {
        $query->where_lte('date', $end_date);
    }
}

$ai_chats = $query->find_many();

$first_embed_id = $ai_chats[0]->embed_id;


$ai_chats1 = ORM::for_table($config['db']['pre'] . 'ai_chat')
    ->where(array('user_id' => $user_id, 'bot_id' => $bot_id))
    ->order_by_desc('date')
    ->find_many();

if (!empty($ai_chats1)) {
    // Get the last AI chat's date
    $first_time = $ai_chats1[count($ai_chats1) - 1]->date;
    $last_time = $ai_chats1[0]->date;

} else {
    $last_time = null; // or any default value you prefer
}

try {
    $currentPST = new DateTime('now', new DateTimeZone('America/Los_Angeles'));
} catch (Exception $e) {
    die('Error getting current time: ' . $e->getMessage());
}

// Group the results by embed_id
$groupedChats = [];
foreach ($ai_chats as $chat) {
    $embedId = $chat->embed_id;
    if (!isset($groupedChats[$embedId])) {
        $groupedChats[$embedId] = [
            'chat_history' => [],
            'last_user_message' => null,
        ];
    }

    // Date from chat history
    $chatDate = new DateTime($chat->date);

    $timeDiff = $currentPST->diff($chatDate);

    // Format the result
    if ($timeDiff->y > 0) {
        $time_diff = 'about ' . $timeDiff->y . ' years ago';
    } elseif ($timeDiff->m > 0) {
        $time_diff = 'about ' . $timeDiff->m . ' months ago';
    } elseif ($timeDiff->d > 0) {
        $time_diff = 'about ' . $timeDiff->d . ' days ago';
    } elseif ($timeDiff->h > 0) {
        $time_diff = 'about ' . $timeDiff->h . ' hours ago';
    } elseif ($timeDiff->i > 0) {
        $time_diff = 'about ' . $timeDiff->i . ' minutes ago';
    } else {
        $time_diff = 'just now';
    }

    // Add chat to chat history
    $groupedChats[$embedId]['chat_history'][] = [
        'user_message' => $chat->user_message,
        'ai_message' => $chat->ai_message,
        'date' => $chat->date,
        'time_diff' => $time_diff,
        'chat_id' => $chat->id
    ];

    // Set last_user_message
    if ($groupedChats[$embedId]['last_user_message'] === null) {
        $groupedChats[$embedId]['last_user_message'] = $chat->user_message;
    }
}

HtmlTemplate::display('uai-chat-history', array(
    'group_chats' => $groupedChats,
    'first_time' => $first_time,
    'last_time' => $last_time,
    'first_embed_id' => $first_embed_id,
    'agent_id' => $agent_id,
    'agent_name' => $agent_name,
    'selected_start_date' => $start_date,
    'selected_end_date' => $end_date,
));

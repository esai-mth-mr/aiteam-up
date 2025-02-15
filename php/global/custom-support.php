<?php
global $config;
if (isset($current_user['id'])) {

    if (!isset($_GET['page']))
        $page = 1;
    else
        $page = $_GET['page'];

    $limit = 10;

    $orm = ORM::for_table($config['db']['pre'] . 'support')
        ->where('user_id', $_SESSION['user']['id'])
        ->order_by_desc('id');

    $total = $orm->count();

    $rows = $orm
        ->limit($limit)
        ->offset(($page - 1) * $limit)
        ->find_many();

    $tickets = array();
    foreach ($rows as $row) {
        $tickets[$row['id']]['id'] = $row['id'];
        $tickets[$row['id']]['title'] = $row['title'];
        $tickets[$row['id']]['category'] = $row['category'];
        $tickets[$row['id']]['priority'] = $row['priority'];
        $tickets[$row['id']]['content'] = strlimiter(strip_tags((string) $row['content']), 20);
        $tickets[$row['id']]['date'] = date('d M, Y', strtotime($row['update_at']));
        $tickets[$row['id']]['admin_comment'] = strlimiter(strip_tags((string) $row['admin_comment']), 20);
        $tickets[$row['id']]['status'] = $row['status'];

    }

    $pagging = pagenav($total, $page, $limit, $link['ALL_tickets']);

    HtmlTemplate::display('global/custom-support', array(
        'tickets' => $tickets,
        'pagging' => $pagging,
        'show_paging' => (int)($total > $limit),
    ));
} else {
    headerRedirect($link['LOGIN']);
}
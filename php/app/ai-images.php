<?php
global $config;

// if disabled by admin
if(!$config['enable_ai_images']) {
    page_not_found();
}

if(isset($current_user['id']))
{
    $start = date('Y-m-01');
    $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

    $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);

    $membership = get_user_membership_detail($_SESSION['user']['id']);
    $images_limit = $membership['settings']['ai_images_limit'];

    $activeTag = "";
    // get active tag imagid
    if(isset($_GET['tag_id']) && !empty($_GET['tag_id'])) {
        $tag0Th = ORM::for_table($config['db']['pre'] . 'ai_tags')
            ->where('user_id', $_SESSION['user']['id'])
            ->where('id',$_GET['tag_id'])
            ->find_one();
        $activeTag = $tag0Th->tag;
    }
    else if(empty($_GET['tag_id'])) {
        $tag0Th = ORM::for_table($config['db']['pre'] . 'ai_tags')
            ->where('user_id', $_SESSION['user']['id'])
            ->order_by_desc('id')
            ->find_one();
    }
    
    // get ai images
    $ai_images = ORM::for_table($config['db']['pre'] . 'ai_images')
        ->where('user_id', $_SESSION['user']['id'])
        ->where('tag_id',$tag0Th->id)
        ->order_by_desc('id')
        // ->limit(30)
        ->find_many();

    $ai_tags = ORM::for_table($config['db']['pre'] . 'ai_tags')
        ->where('user_id', $_SESSION['user']['id'])
        ->order_by_desc('id')
        ->find_many();

    HtmlTemplate::display('ai-images', array(
        'total_images_used' => $total_images_used,
        'images_limit' => $images_limit,
        'ai_images' => $ai_images,
        'ai_tags'   =>  $ai_tags,
        'active_tag' => $activeTag,
    ));
}
else{
    headerRedirect($link['LOGIN']);
}
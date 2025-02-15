<?php
global $config;

if(!$config['enable_ai_images']) {
    page_not_found();
}


if(isset($current_user['id']))
{
    // $sql = "SELECT fd.id, fd.user_id, fd.folder_name, COALESCE(SUM(doc.word_count), 0) AS word_count, COUNT(doc.directory_id) AS doc_count 
    //     FROM `" . $config['db']['pre'] . "uai_folder` fd 
    //     LEFT JOIN `" . $config['db']['pre'] . "uai_training_doc` doc ON doc.directory_id = fd.id 
    //     WHERE fd.user_id = '".$_SESSION['user']['id']."' 
    //     GROUP BY fd.id 
    //     ORDER BY fd.id DESC";
    // $rows = ORM::for_table($config['db']['pre'].'uai_folder')->raw_query($sql)->find_many();
    
    $total_uai_agent_used = get_user_option($_SESSION['user']['id'], 'total_uai_agent_used', 0);

    $membership = get_user_membership_detail($_SESSION['user']['id']);
    $uai_agent_limit = $membership['settings']['ai_uai_agent_limit'];

    $rows = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->order_by_desc('id')->find_many();

    $uai_quick_video_link = get_option("uai_quick_video_link");
    $uai_learn_more_link = get_option("uai_learn_more_link");

    HtmlTemplate::display('uai', array(
        'directories' => $rows,
        'total_uai_agent_used' => $total_uai_agent_used,
        'uai_agent_limit' => $uai_agent_limit,
        'uai_quick_video_link' => $uai_quick_video_link,
        'uai_learn_more_link' => $uai_learn_more_link
    ));
}
else{
    headerRedirect($link['LOGIN']);
}
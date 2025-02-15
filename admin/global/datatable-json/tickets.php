<?php
require_once('includes.php');
global $config;
$params = $columns = $order = $totalRecords = $data = array();
$params = $_REQUEST;

//define index of column
$columns = array(
    'id',
    'title',
    'content',
    'update_at',
    'name',
    'email',
    'admin_comment',
    'category',
    'priority',
    'status'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if (!empty($params['search']['value'])) {
    $where .= " WHERE ";
    $where .= " ( id LIKE '" . $params['search']['value'] . "%' ";
    $where .= " OR title LIKE '" . $params['search']['value'] . "%' ";
    $where .= " OR content LIKE '" . $params['search']['value'] . "%' ";
    $where .= " OR admin_comment LIKE '" . $params['search']['value'] . "%' ";
    $where .= " OR category LIKE '" . $params['search']['value'] . "%' ";
    $where .= " OR priority LIKE '" . $params['search']['value'] . "%' ";
    $where .= " OR status LIKE '" . $params['search']['value'] . "%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM `" . $config['db']['pre'] . "support` ";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if (isset($where) && $where != '') {

    $sqlTot .= $where;
    $sqlRec .= $where;
}

if ($params['order'][0]['column'] == '8') {
    $sqlRec .=  " ORDER BY FIELD(priority, NULL, 'Low', 'Normal', 'High', 'Critical') " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";
} else {
    $sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";
}


$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

// $current_user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {
    $id = $row['id'];

    $user_id = ORM::for_table($config['db']['pre'] . 'support')->find_one($row['id'])->user_id;

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id)->name;
    $email = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id)->email;

    $title = $row['title'];
    $type = $row['user_type'] == 'user' ? '' : 'Employer';
    $content = strlimiter(strip_tags((string) $row['content']), 40);
    $admin_comment = strlimiter(strip_tags((string) $row['admin_comment']), 40);
    $image = $row['image'];
    $category = $row['category'];
    $priority = "<span class='badge priority-" . htmlspecialchars(strtolower($row['priority'])) . "'>" . htmlspecialchars($row['priority']) . "</span>";
    $status = $row['status'];
    $created_at  = date('d M, y', strtotime($row['update_at']));
    if ($image == "")
        $image = "default_user.png";

    if ($status == "open") {
        $status = '<span class="badge badge-warning">' . __('Open') . '</span>';
    } elseif ($status == "progress") {
        $status = '<span class="badge badge-info">' . __('Progress') . '</span>';
    } else {
        $status = '<span class="badge badge-success">' . __('Resolved') . '</span>';
    }

    $rows = array();
    $rows[] = '<td>' . "# " . $id . '</td>';
    // $rows[] = '<td>
    //             <div class="d-flex align-items-center">
    //                 <img class="m-r-10" src="'.$config['site_url'].'storage/profile/'.$image.'" width="50">
    //                 <div>
    //                     <h6>'.$title.'</h6>
    //                     <span>@'.$username.'</span>
    //                 </div>
    //             </div>
    //         </td>';
    $rows[] = '<td>' . $title . '</td>';
    $rows[] = '<td>' . $content . '</td>';
    $rows[] = '<td>' . $created_at . '</td>';
    $rows[] = '<td>' . $user . '</td>';
    $rows[] = '<td>' . $email . '</td>';
    $rows[] = '<td>' . $admin_comment . '</td>';
    $rows[] = '<td>' . $category . '</td>';
    $rows[] = '<td>' . $priority . '</td>';
    $rows[] = '<td>' . $status . '</td>';

    // foreach ($chat_history as $key => $chat) {
    //     $side = $chat->author == "admin" ? "me" : "";
    //     $message = $chat->message;
    //     $rows[] =
    //         `<div class="message-bubble ">
    //             <div class="message-bubble-inner">
    //                 <div class="message-text" >
    //                     <div class="markdown-body">$message</div>
    //                 </div>
    //             </div>
    //             <div class="clearfix"></div>
    //         </div>`;
    // }

    // $rows[] = '<td>
    //             <div class="btn-group">
    //                 <a href="#" title="' . __('Login as user') . '" class="btn-icon btn-primary mr-1 login-as-user" data-user-id="' . $id . '" data-tippy-placement="top"><i class="icon-feather-log-in"></i></a>
    //                 <a href="#" data-url="panel/users_edit.php?id=' . $id . '" data-toggle="slidePanel" title="' . __('Edit') . '" class="btn-icon mr-1" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
    //                 <a href="javascript:void(0)" class="btn-icon btn-xs btn-danger item-js-delete" data-ajax-action="deleteusers" title="' . __('Delete') . '" data-tippy-placement="top"><i class="icon-feather-user-x"></i></a>
    //             </div>
    //         </td>';
    $rows[] = '<td>
                <div class="btn-group">
                    <a href="#" data-url="panel/tickets_edit.php?id=' . $id . '?user_id=' . $user_id . '" data-toggle="slidePanel" title="' . __('View Details') . '" class="btn-icon mr-1" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                </div>
            </td>';
    // $rows[] = '<td>
    //             <div class="checkbox">
    //             <input type="checkbox" id="check_' . $id . '" value="' . $id . '" class="quick-check">
    //             <label for="check_' . $id . '"><span class="checkbox-icon"></span></label>
    //         </div>
    //         </td>';

    $rows['DT_RowId'] = $id;
    $data[] = $rows;
}

$json_data = array(
    "draw"            => intval($params['draw']),
    "recordsTotal"    => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data"            => $data,   // total data array
);

echo json_encode($json_data);  // send data as json format
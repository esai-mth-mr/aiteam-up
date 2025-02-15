<?php

/**
 * @package AITeamUp - OpenAI Content & Image Generator
 * @author AITeamUp
 * @version 4.1
 * @Updated Date: 28/Aug/2023
 * @Copyright 2015-23 AITeamUp
 */


define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH . "/php/");

try {
    require_once ROOTPATH . '/includes/autoload.php';
    require_once ROOTPATH . '/includes/lang/lang_' . $config['lang'] . '.php';
} catch (\Throwable $th) {
    die($th);
}

try {
    global $config, $lang;
    
    $getUsers = ORM::for_table($config['db']['pre'] . 'upgrades')->find_many();
    if(count($getUsers) > 0) 
    {
        $currentDate = date('Y-m-d');
        foreach ($getUsers as $usr) {
            $userdata = get_user_data(null, $usr->user_id);

            if ($userdata['plan_type'] !== NULL) {
                
                $startTimeStamp = strtotime($currentDate);
                $endTimeStamp = strtotime(date('Y-m-d', $usr->upgrade_expires));
                
                if(strtolower($userdata['plan_type']) === 'free') {
                    $planDate = $usr->date_trial_ends;
                    $endTimeStamp = strtotime($usr->date_trial_ends);
                }

                if($startTimeStamp <= $endTimeStamp) {
                    $timeDiff = abs($endTimeStamp - $startTimeStamp);
                    $dateTimeDiff = $timeDiff/86400;  // 86400 seconds in one day
                    // to convert days in integer
                    $numberDays = intval($dateTimeDiff);

                    // Send email if plan expiration number of days less or equal to 3 days.
                    $subject = "Membership Plan expiration alert";
                    if(($numberDays > 0) && ($numberDays <= 3)) {
                        
                        $content = "<h3>Hey! ".$userdata['username']."</h3>";
                        $content .= "<p>Your membership plan is going to expired within ".$numberDays." day(s). To enjoy the uninterrupted services, Please renew it.</p>";
                        $content .= "<br>";
                        $content .= "<p>Thanks</p>";

                        email_send($userdata['email'], $config['admin_email'], $subject, $content);
                    }
                    else if($numberDays == 0) {
                        
                        $content = "<h3>Hey! ".$userdata['username']."</h3>";
                        $content .= "<p>Your membership plan is going to expired by today. To enjoy the uninterrupted services, Please renew it.</p>";
                        $content .= "<br>";
                        $content .= "<p>Thanks</p>";

                        email_send($userdata['email'], $config['admin_email'], $subject, $content);
                    }
                }
                else if($startTimeStamp > $endTimeStamp) 
                {
                    // udpate user group id as free to stop the usages and for ask to user renew or buy the membership plan
                    $updateTag = ORM::for_table($config['db']['pre'] . 'user')->find_one($usr->user_id);
                    $updateTag->set('group_id', 'free');
                    $updateTag->save();

                    // Send email if plan expired.
                    $subject = "Membership Plan has been expired";

                    $content = "<h3>Hey! ".$userdata['username']."</h3>";
                    $content .= "<p>Your membership plan has been expired. To enjoy the uninterrupted services, Please buy the membership plan or renew it.</p>";
                    $content .= "<br>";
                    $content .= "<p>Thanks</p>";

                    email_send($userdata['email'], $config['admin_email'], $subject, $content);
                }
            }
        }
    }
} 
catch (\Throwable $th) {
    die($th);
}
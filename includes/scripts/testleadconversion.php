<?php
/**
 * Created by PhpStorm.
 * User: iftekar
 * Date: 5/4/17
 * Time: 2:04 PM
 */


$genarr = $AI->db->GetAll("SELECT * FROM genealogy_tree WHERE parent != child and depth=1");

foreach ($genarr as $val){
    echo $val['child']."<br/>";
    echo $val['parent']."<br/>";
    echo "SELECT * FROM user WHERE userID= ".$val['child']."<br/>";
    $userarr = $AI->db->GetAll("SELECT * FROM users WHERE userID= ".$val['child']);
    echo "user arr <br/>";
    echo "<pre>";
    print_r($userarr[0]['parent']);
    echo "</pre>";
    if($val['parent']!=$userarr[0]['parent']){
        $timezone_res = db_query('update users set parent ='.$val['parent'].' where userID = '.$val['child']);
        echo 'user table misconfigured';
        $arr[$val['child']]=$val['child'];
    }
    if(!isset($userarr[0]['parent'])){
        echo 'user table data not found !!';
    }


    echo "Lead part started !!<br/>";
    echo print_r($userarr[0]['lead_id']);
    echo "<br/>";
    $leadarr = $AI->db->GetAll("SELECT * FROM lead_management WHERE id= ".$userarr[0]['lead_id']);
    echo $leadarr[0]['ownerID']."<br/>";

    if($leadarr[0]['ownerID']!=$val['parent']){
        echo "lead manager lmismatch issue";
        $arrlead[$val['child']]=$val['child'];
        db_query('update lead_management set ownerID ='.$val['parent'].' where id = '.$userarr[0]['lead_id']);
    }
    echo "Loop ends";
    echo "<br/>";
}

echo "<pre>";
print_r($genarr);
echo "</pre>";

echo "<br/>";
print_r($arr);
echo "<br/>";
print_r($arrlead);
require_once(ai_cascadepath('includes/plugins/pop3/api.php'));

set_time_limit(0);
$user_enarr = $AI->db->GetAll("SELECT * FROM users WHERE account_type = 'Doctor' ");
foreach ($user_enarr as $vals){
    db_query('delete  from  genealogy_tree where child = '.$vals['userID']);
    db_query('delete  from  genealogy where userID = '.$vals['userID']);
    db_query('delete  from  user_mails where userID = '.$vals['userID']);
    $cpanelusr = 'nexmed';
    $cpanelpass = 'l0PS8AyMm0aB';
    $xmlapi = new xmlapi('galaxy.apogeehost.com');
    $xmlapi->set_port( 2083 );
    $xmlapi->password_auth($cpanelusr,$cpanelpass);
    $xmlapi->set_debug(0); //output actions in the error log 1 for true and 0 false
    $result = $xmlapi->api1_query($cpanelusr, 'Email', 'delpop', array(strtolower($vals['username']).'@nexmedsolutions.com','nexmedsolutions.com'));

}
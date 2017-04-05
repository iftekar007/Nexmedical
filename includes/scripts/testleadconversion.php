<?php
/**
 * Created by PhpStorm.
 * User: iftekar
 * Date: 5/4/17
 * Time: 2:04 PM
 */
echo 90;

$genarr = $AI->db->GetAll("SELECT * FROM genealogy_tree WHERE parent != child");

echo "<pre>";
print_r($genarr);
echo "</pre>";
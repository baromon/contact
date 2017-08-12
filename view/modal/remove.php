<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/tool.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/bdd.php';

use NG\Tool;
use NG\Db;

if (Tool\tool::ajaxCheck() && Tool\tool::domainCheck()) {

    // Init
    $bdd = new Db\Bdd();
    $bdd->connect();
    $data = $bdd->selectUid($_POST['uid']);

    //Get template
    $template = file_get_contents('remove.html', FILE_USE_INCLUDE_PATH);

    // Put information in template
    $patterns = array();
    $replacements = array();
    foreach ($data as $key => $value) {
        $patterns[] = '/###' . $key . '###/';
        $replacements[] = $value;
    }
    $html = preg_replace($patterns, $replacements, $template);

    // Return param
    $json_arr = array('action' => 'delModal', 'html' => $html, 'uid' => $_POST['uid']);
    echo json_encode($json_arr);
} else {
    header('Location:/');
}
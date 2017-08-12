<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/tool.php';

use NG\Tool;

if (Tool\tool::ajaxCheck() && Tool\tool::domainCheck()) {

    $target = $_SERVER['DOCUMENT_ROOT'] . '/img/profil/tmp';
    if(file_exists($target)) {
        unlink($target);
    }

    if(Tool\tool::uploadImg($_FILES['photoFile'], $target)) {
        $html = '<img src="img/profil/tmp?' . time() . '" alt="photo">';
    }else {
        $html = "Erreur téléchargement image";
    }

    // Return param
    $json_arr = array('html' => $html);
    echo json_encode($json_arr);
} else {
    header('Location:/');
}
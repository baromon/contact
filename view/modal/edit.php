<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/tool.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/bdd.php';

use NG\Tool;
use NG\Db;

if (Tool\tool::ajaxCheck() && Tool\tool::domainCheck()) {

    // Init Bdd
    $bdd = new Db\Bdd();
    $bdd->connect();

    // Init template
    $template = file_get_contents('edit.html', FILE_USE_INCLUDE_PATH);
    $patterns = array();
    $replacements = array();

    // Set information depend of call by add or edit
    if (isset($_POST['uid']) && !empty($_POST['uid'])) {

        // Get contact information
        $data = $bdd->selectUid($_POST['uid']);

        // Set edit data
        $data['Title'] = 'Modifier le contact ' . $data['prenom'] . ' ' . $data['nom'];
        $data['nomBouton'] = 'Sauvegarder';
        $data['idBouton'] = 'editContactButton';
        $data['actionBouton'] = 'editModal';
        $data['photoHtml'] = '<img src="img/profil/' . $data['photo'] . '" alt="photo">';
    } else {
        // Get contact information
        $tmp = $bdd->selectInterval(0, 1);
        $data = array();
        foreach ($tmp[0] as $key => $value) {
            $data[$key] = "";
        }
        // Set add data
        $data['Title'] = 'Ajouter un nouveau contact';
        $data['nomBouton'] = 'Ajouter';
        $data['idBouton'] = 'addContactButton';
        $data['actionBouton'] = 'addModal';
        $data['photoHtml'] = '';
    }

    // Set civilite information
    $data['civiliteExplain'] = '';
    $data['civiliteM'] = '';
    $data['civiliteMme'] = '';
    $data['civiliteMlle'] = '';
    if ($data['civilite'] != 'M' AND $data['civilite'] != 'Mme' AND $data['civilite'] != 'Mlle') {
        $data['civiliteExplain'] = 'selected';
    } else {
        $data['civilite' . $data['civilite']] = 'selected';
    }

    // Set countries information
    $countries = $bdd->selectCountries();
    $str = '<option value="0">Choisir un pays</option>';
    foreach ($countries as $key => $val) {
        $str .= '<option value="' . $val['uid'] . '"';
        if ($val['cn_short_fr'] == $data['cn_short_fr'] && isset($data['cn_short_fr'])) {
            $str .= ' selected';
        }
        $str .= '>' . $val['cn_short_fr'] . '</option>';
    }
    $data['selectPays'] = $str;


    // Set presention information
    $data['classRowHeader'] = 'col-xs-3 col-sm-3 col-md-3 col-lg-3';
    $data['classRowInput'] = 'col-xs-7 col-sm-7 col-md-7 col-lg-7';

    // Put information in template
    foreach ($data as $key => $value) {
        $patterns[] = '/###' . $key . '###/';
        $replacements[] = $value;
    }
    $html = preg_replace($patterns, $replacements, $template);

    // Return param
    $json_arr = array('action' => $data['actionBouton'], "html" => $html);
    echo json_encode($json_arr);
} else {
    header('Location:/');
}
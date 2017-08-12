<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/tool.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/contact.php';

use NG\Tool;
use NG\Db;
use NG\Model;

if (Tool\tool::ajaxCheck() && Tool\tool::domainCheck()) {

    // Init
    $bdd = new Db\Bdd();
    $bdd->connect();

    // Action depend of variable action
    $json_arr = array('action' => $_POST['action']);

    if ($_POST['action'] == 'delBdd') {
        $bdd->deleteUid($_POST['uid']);
    } else if ($_POST['action'] == 'addModal' || $_POST['action'] == 'editModal') {

        $contact = new Model\Contact($_POST);

        // Check form validity
        $info = $contact->check($_POST['action']);
        if (isset($info['fail'])) {
            $json_arr['success'] = false;
            $inputFail = array();
            $infoFail = array();
            foreach ($info as $key => $value) {
                if ($value != "") {
                    $inputFail[] = $key;
                    $infoFail[] = $value;
                }
            }
            $json_arr['inputFail'] = $inputFail;
            $json_arr['infoFail'] = $infoFail;
        } else {

            // Prepare information to send to bdd
            $data = $contact->getArrayToSendBdd($_POST['action']);

            $success = true;
            $file = $_FILES['photoFile'];
            if (isset($file) && is_uploaded_file($file['tmp_name'])) {
                $data['photo'] = time() . '_' . $file["name"];
                $target = $_SERVER['DOCUMENT_ROOT'] . "/img/profil/" . $data['photo'];
                if (!Tool\tool::uploadImg($file, $target)) {
                    $success = false;
                    $json_arr['success'] = false;
                    $json_arr['inputFail'] = ['photo'];
                    $json_arr['infoFail'] = ['Erreur'];
                }
            }

            // Update contact
            if ($success == true) {
                $uid = 0;
                if ($_POST['action'] == 'addModal') {
                    $uid = $bdd->addContact($data);
                } else if ($_POST['action'] == 'editModal') {
                    $uid = $bdd->editContact($data);
                }
                $json_arr['success'] = true;
                $json_arr['uid'] = $uid;
            }
        }
    }

    // Return param
    echo json_encode($json_arr);
} else {
    header('Location:/');
}
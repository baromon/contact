<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/class/tool.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/class/pagination.php';

use NG\Tool;
use NG\Db;
use NG\Page;


if (Tool\tool::domainCheck() AND Tool\tool::ajaxCheck()) {

    /***************************************************************************************************************/
    // Init

    // Init class
    $bdd = new Db\Bdd();
    $bdd->connect();
    $page = new Page\Pagination();

    // refresh page case
    if ($_POST['curPage'] == -2) {

        // Current page setting
        if (isset($_SESSION['curPage'])) {
            $_POST['curPage'] = $_SESSION['curPage'];
        } else {
            $_POST['curPage'] = 1;
        }

        // Search word setting
        if (isset($_SESSION['searchWord'])) {
            $_POST['searchWord'] = $_SESSION['searchWord'];
        } else {
            $_POST['searchWord'] = "";
        }
        $_POST['searchWordDisplay'] = $_POST['searchWord'];

        // Row per page setting
        if (isset($_COOKIE['rowPerPage'])) {
            $_POST['rowPerPage'] = $_COOKIE['rowPerPage'];
        } else {
            $_POST['rowPerPage'] = 20;
        }
    }

    // Set variable
    $page->config($bdd->count($_POST['searchWord']), $_POST['rowPerPage']);
    $_POST['curPage'] = $page->setCurPage($_POST['curPage']);
    $allContact = $bdd->selectInterval($page->getFirstRowPrint(), $_POST['rowPerPage'], $_POST['searchWord']);

    // Save variable in case of refresh
    $_SESSION['curPage'] = $page->getCurPage();
    $_SESSION['searchWord'] = $_POST['searchWord'];
    setcookie("rowPerPage", $_POST['rowPerPage']);

    /***************************************************************************************************************/
    // Contact

    // Template
    $htmlContactPattern = file_get_contents('displayPattern.html', FILE_USE_INCLUDE_PATH);
    $template = file_get_contents('display.html', FILE_USE_INCLUDE_PATH);
    $patterns = array();
    $htmlContact = '';

    // Data to insert in template
    if (empty($allContact)) {
        $htmlContactPattern = '';
        $htmlContact = '';
        $htmlPagination = '<p class="text-center">Aucun contact trouvé.</p>';
    } else {

        foreach ($allContact as $data) {

            // Set info to replace template data
            $replacements = array();
            foreach ($data as $key => $val) {
                $patterns[] = '/###' . $key . '###/';
                $replacements[] = $val;
            }
            // Merge data and template
            $htmlContact .= preg_replace($patterns, $replacements, $template);
        }


        /***************************************************************************************************************/
        // Pagination

        // Data to insert in template
        $data['paginationInfo'] = 'page ' . $_POST['curPage'] . ' / ' . $page->getNbPage();
        $data['paginationInfoHidendXs'] = 'Contact ' . ($page->getFirstRowPrint() + 1) . ' à ' . $page->getLastRowPrint() . ' sur ' . $page->getNbRow();
        $data['first'] = 1;
        $data['prec'] = $_POST['curPage'] - 1;
        $data['curPage'] = $_POST['curPage'];
        $data['next'] = $_POST['curPage'] + 1;
        $data['last'] = $page->getNbPage();

        // Hide / show pagination button
        if ($_POST['curPage'] == 1) {
            $data['backward'] = 'none';
        } else {
            $data['backward'] = 'inline';
        }
        if ($_POST['curPage'] == $page->getNbPage()) {
            $data['forward'] = 'none';
        } else {
            $data['forward'] = 'inline';
        }

        // Insert data in template
        $template = file_get_contents('pagination.html', FILE_USE_INCLUDE_PATH);
        $patterns = array();
        $replacements = array();
        foreach ($data as $key => $value) {
            $patterns[] = '/###' . $key . '###/';
            $replacements[] = $value;
        }
        $htmlPagination = preg_replace($patterns, $replacements, $template);
    }

    /***************************************************************************************************************/
// Search

// Template
    $template = file_get_contents('search.html', FILE_USE_INCLUDE_PATH);

// Data to insert in template
    $data['searchWord'] = $_POST['searchWord'];
    if (empty($_POST['searchWordDisplay'])) {
        $data['searchWordDisplay'] = "";
        $data['searchWordExplain'] = 'Veuillez saisir un contact';
    } else {
        $data['searchWordDisplay'] = $_POST['searchWordDisplay'];
        $data['searchWordExplain'] = "";
    }

// Set row per page
    $data['10rowPerPage'] = '';
    $data['20rowPerPage'] = '';
    $data['30rowPerPage'] = '';
    $data['100rowPerPage'] = '';
    $data[$_POST['rowPerPage'] . 'rowPerPage'] = 'selected';

// Insert data in template
    $patterns = array();
    $replacements = array();
    foreach ($data as $key => $value) {
        $patterns[] = '/###' . $key . '###/';
        $replacements[] = $value;
    }
    $htmlSearch = preg_replace($patterns, $replacements, $template);

    /***************************************************************************************************************/
// Return param

    $json_arr = array("htmlContactPattern" => $htmlContactPattern, "htmlContact" => $htmlContact,
        "htmlPagination" => $htmlPagination, "htmlSearch" => $htmlSearch, "tool" => Tool\tool::domainCheck());
    echo json_encode($json_arr);
} else {
    header('Location:/');
}
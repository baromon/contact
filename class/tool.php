<?php

namespace NG\Tool;

require_once $_SERVER['DOCUMENT_ROOT'] . '.php';

class tool
{

    static public function convmail($email)
    {
        $p = str_split(trim($email));
        $new_mail = '';
        foreach ($p as $val) {
            $new_mail .= '&#' . ord($val) . ';';
        }
        return $new_mail;
    }

    static public function cleanUpValues($allValues)
    {
        foreach ($allValues as $key => $value) {
            if (is_array($value)) {
                $allValues[$key] = self::cleanUpValues($value);
            } else {
                $allValues[$key] = addslashes(htmlspecialchars(trim(strip_tags($value))));
            }
        }
        return $allValues;
    }

    static public function ajaxCheck()
    {
        $check = false;
        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'];

        if (!empty($requestedWith) && strtolower($requestedWith) == 'xmlhttprequest') {
            $check = true;
        }
        return $check;
    }

    static public function domainCheck()
    {
        global $domain;
        $check = false;
        $referer = substr($_SERVER['HTTP_REFERER'], 0, strlen($domain));
        if ($referer == $domain) {
            $check = true;
        }
        return $check;
    }

    static public function uploadImg($file, $target)
    {
        $type = strtolower($file['type']);
        if (isset($file) && !file_exists($target) && is_uploaded_file($file['tmp_name'])
            && $file['error'] == UPLOAD_ERR_OK && $file["size"] < 500000
            && ($type == "image/gif" || $type == "image/png" || $type == "image/jpeg")) {

            if (move_uploaded_file($_FILES["photoFile"]['tmp_name'], $target)) {
                return true;
            }
        }
        return false;
    }
}
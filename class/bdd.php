<?php

namespace NG\Db;

require_once $_SERVER['DOCUMENT_ROOT'] . '.php';


class Bdd
{
    protected $bdd = null;

    protected $SELECT_CONTACT = "SELECT contact.*, countries.cn_short_fr, LCASE(countries.cn_iso_2) AS cn_iso_2 FROM contact";
    protected $COUNTRIE_JOIN = "INNER JOIN countries ON contact.pays = countries.uid";

    public function connect()
    {
        global $host, $database, $user, $pass;
        try {
            $this->bdd = new \PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $user, $pass);
        } catch (\PDOException  $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /*****************************************************************************************************************/
    // Get info

    public function count($search = "")
    {
        $sqlSearch = $this->sqlSearch($search);
        $reponse = $this->bdd->query("SELECT COUNT(uid) FROM contact" . $sqlSearch);
        $data = $reponse->fetch(\PDO::FETCH_NUM);
        return $data[0];
    }

    protected function sqlSearch($search)
    {
        $sqlSearch = "";
        if (!empty($search)) {
            $sqlSearch = " WHERE contact.nom LIKE '%" . $search . "%'
                             OR contact.prenom LIKE '%" . $search . "%'";
        }
        return $sqlSearch;
    }

    public function selectInterval($from, $maxRow, $search = "")
    {
        $sqlSearch = $this->sqlSearch($search);
        $reponse = $this->bdd->query($this->SELECT_CONTACT . " " . $this->COUNTRIE_JOIN . $sqlSearch . " LIMIT " . $from . ", " . $maxRow);
        return $reponse->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectUid($uid)
    {
        $reponse = $this->bdd->query($this->SELECT_CONTACT . " " . $this->COUNTRIE_JOIN . " " . "WHERE contact.uid = " . $uid);
        return $reponse->fetch(\PDO::FETCH_ASSOC); // Only one contact
    }

    public function selectCountries()
    {
        $reponse = $this->bdd->query("SELECT countries.cn_short_fr, LCASE(countries.cn_iso_2) AS cn_iso_2, countries.uid FROM countries");
        return $reponse->fetchAll(\PDO::FETCH_ASSOC);
    }

    /*****************************************************************************************************************/
    // Remove info

    public function deleteUid($uid)
    {
        if (isset($uid) && !empty($uid)) {
            $this->bdd->exec("DELETE FROM contact WHERE uid = " . $uid);
        }
    }

    /*****************************************************************************************************************/
    // Set info

    public function addContact($data)
    {
        $req = $this->bdd->prepare("INSERT INTO contact" . " " . $this->sqlRequestAddInfo($data));
        $req->execute($data);
        $uid = $this->bdd->lastInsertId(); // Save uid to display the contact

        return $uid;
    }

    protected function sqlRequestAddInfo($data)
    {
        $str = '(';

        $first = true;
        foreach ($data as $key => $val) {
            if ($first == false)
                $str .= ', ';
            else
                $first = false;
            $str .= $key;
        }

        $str .= ') VALUES (';

        $first = true;
        foreach ($data as $key => $val) {
            if ($first == false)
                $str .= ', ';
            else
                $first = false;
            $str .= ':' . $key;
        }
        $str .= ')';
        return $str;
    }


    public function editContact($data)
    {
        $req = $this->bdd->prepare("UPDATE contact SET " . $this->requestEditInfo($data) . " WHERE uid=:uid");
        $req->execute($data);

        return $data['uid'];
    }

    protected function requestEditInfo($data)
    {
        $first = true;
        $str = '';
        foreach ($data as $key => $val) {
            if ($key != 'uid') {
                if ($first == false)
                    $str .= ', ';
                else
                    $first = false;

                $str .= $key . '=:' . $key;
            }
        }
        return $str;
    }
}
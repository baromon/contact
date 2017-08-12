<?php

namespace NG\Model;

require_once $_SERVER['DOCUMENT_ROOT'] . '/class/tool.php';

use NG\Tool;

class Contact
{
    private $uid = 0;

    private $nom = "";
    private $prenom = "";
    private $civilite = "";
    private $email = "";
    private $adresse = "";

    private $photo = "";
    private $societe = "";
    private $telephone = "";
    private $mobile = "";
    private $zip = "";
    private $ville = "";
    private $pays = "";

    private $cn_short_fr = "";
    private $cn_iso_2 = 0;

    private $robot = "invalid";


    public function __construct($arrayData)
    {
        if (isset($arrayData)) {
            $this->set($arrayData);
        }
    }

    public function set($arrayData)
    {
        if (is_array($arrayData)) {
            foreach ($arrayData as $key => $value) {
                $methodName = 'set' . strtoupper(substr($key, 0, 1)) . substr($key, 1);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

    public function getArrayToSendBdd($action)
    {
        $arrayData = array();
        if ($action == 'editModal') {
            $arrayData ['uid'] = $this->uid;
        }
        $arrayData ['civilite'] = $this->civilite;
        $arrayData ['nom'] = $this->nom;
        $arrayData ['prenom'] = $this->prenom;
        $arrayData ['email'] = $this->email;
        $arrayData ['adresse'] = $this->adresse;
        $arrayData ['photo'] = $this->photo;
        $arrayData ['societe'] = $this->societe;
        $arrayData ['telephone'] = $this->telephone;
        $arrayData ['mobile'] = $this->mobile;
        $arrayData ['zip'] = $this->zip;
        $arrayData ['ville'] = $this->ville;
        $arrayData ['pays'] = $this->pays;

        $arrayData = Tool\tool::cleanUpValues($arrayData);
        return $arrayData;
    }

    public function check($action)
    {
        $check = array();

        if ($action == 'editModal') {
            $check ['uid'] = $this->checkUid();
        }
        $check ['civilite'] = $this->checkCivilite();
        $check ['nom'] = $this->checkNom();
        $check ['prenom'] = $this->checkPrenom();
        $check ['email'] = $this->checkEmail();
        $check ['adresse'] = $this->checkAdresse();
        $check ['pays'] = $this->checkPays();
        $check ['robot'] = $this->checkRobot();

        foreach ($check as $value) {
            if ($value != "") {
                $check['fail'] = true;
            }
        }
        return $check;
    }

    /*************************************************************************************************************/

    public function checkUid()
    {
        if (!is_numeric($this->uid)) {
            return "L'uid n'est pas un nombre";
        } else if ($this->uid < 0) {
            return "L'uid est négatif";
        }
        return '';
    }

    public function checkCivilite()
    {
        if ($this->civilite == "") {
            return "La civilité est vide";
        } else if ($this->civilite != "M" AND $this->civilite != "Mme" AND $this->civilite != "Mlle") {
            return "La civilité est invalide";
        }
        return '';
    }

    public function checkNom()
    {
        if ($this->nom == "") {
            return "Le nom est vide";
        }
        return '';
    }

    public function checkPrenom()
    {
        if ($this->prenom == "") {
            return "Le prénom est vide";
        }
        return '';
    }

    public function checkEmail()
    {
        if ($this->email == "") {
            return "Le mail est vide";
        }
        return '';
    }

    public function checkAdresse()
    {
        if ($this->adresse == "") {
            return "L'adresse est vide";
        }
        return '';
    }

    public function checkPays()
    {
        if (!is_numeric($this->pays)) {
            return "L'identifiant du pays n'est pas un nombre";
        } else if ($this->pays <= 0) {
            return "L'identifiant du pays n'est pas un nombre";
        }
        return '';
    }

    public function checkRobot()
    {
        if ($this->robot != "") {
            return 'Attaque de robot';
        }
        return "";
    }

    /*************************************************************************************************************/

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * @param mixed $civilite
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * @param mixed $societe
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param mixed $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return mixed
     */
    public function getCn_short_fr()
    {
        return $this->cn_short_fr;
    }

    /**
     * @param mixed $cn_short_fr
     */
    public function setCn_short_fr($cn_short_fr)
    {
        $this->cn_short_fr = $cn_short_fr;
    }

    /**
     * @return mixed
     */
    public function getCn_iso_2()
    {
        return $this->cn_iso_2;
    }

    /**
     * @param mixed $cn_iso_2
     */
    public function setCn_iso_2($cn_iso_2)
    {
        $this->cn_iso_2 = $cn_iso_2;
    }

    /**
     * @return string
     */
    public function getRobot()
    {
        return $this->robot;
    }

    /**
     * @param string $robot
     */
    public function setRobot($robot)
    {
        $this->robot = $robot;
    }
}
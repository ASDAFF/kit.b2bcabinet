<?php

namespace Kit\B2BCabinet\Personal;

class Buyer
{

    protected $id = 0;
    protected $name = '';
    protected $org = '';
    public function __construct($buyer = array()) {
        if($buyer['ID']) {
            $this->id = $buyer['ID'];
        }

        if($buyer['NAME']) {
            $this->name = $buyer['NAME'];
        }
    }

    public function genEditUrl($rule = '')
    {
        return str_replace('#ID#', $this->id, $rule);
    }

    public function genUrl($rule) {
        return str_replace('#ID#', $this->id, $rule);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function delete($idUser = 0) {
        $dbUserProps = \CSaleOrderUserProps::GetList(
            array(),
            array(
                "ID" => $this->id,
                "USER_ID" => $idUser
            ));

        if ($arUserProps = $dbUserProps->Fetch()) {
            \CSaleOrderUserProps::Delete($arUserProps["ID"]);
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getOrg() {
        return $this->org;
    }

    public function setOrg($org) {
        $this->org = $org;
    }

    public function getFullName() {
        $result = $this->getName();

        if(!empty($this->getOrg())) {
            $result = $this->getOrg().' ('.$result.')';
        }
        return $result;
    }

}
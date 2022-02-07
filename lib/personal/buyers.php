<?php

namespace Kit\B2BCabinet\Personal;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

class Buyers extends \KitB2bCabinet
{

    protected $hasB2BCabinet = false;
    protected $companyNameCode = false;

    public function __construct() {
        $this->hasB2BCabinet = Loader::includeModule('kit.b2bcabinet');
        $this->companyNameCode = Option::get('kit.b2bcabinet','PROFILE_ORG_NAME','COMPANY');
    }

    public function findBuyersForUser($idUser = 0) {
        $listBuyers = array();

        if($idUser > 0) {
            $filter = array("USER_ID" => $idUser);
            if($this->hasB2BCabinet) {
                $filter['PERSON_TYPE_ID'] = unserialize(Option::get('kit.b2bcabinet','BUYER_PERSONAL_TYPE','a:0:{}'));
            }
            $rsBuyers = \CSaleOrderUserProps::GetList( array(), $filter );
            while ($buyer = $rsBuyers->fetch()) {
                $listBuyers[$buyer['ID']] = new Buyer($buyer);
            }
            if($this->hasB2BCabinet && count($listBuyers) > 0) {
                $db_propVals = \CSaleOrderUserPropsValue::GetList(
                    array("ID" => "ASC"),
                    array(
                        "USER_PROPS_ID"=>array_keys($listBuyers),
                        'CODE' => $this->companyNameCode
                    )
                );
                while ($arPropVals = $db_propVals->Fetch()) {
                    if($arPropVals['VALUE']) {
                        $listBuyers[$arPropVals['USER_PROPS_ID']]->setOrg($arPropVals['VALUE']);
                    }
                }
            }
        }

        return $listBuyers;
    }
}
<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

$moduleId = 'kit.b2bcabinet';

$buyerPersonalTypes = Option::get($moduleId, 'BUYER_PERSONAL_TYPE', '', WIZARD_SITE_ID);

$moduleId = 'kit.checkcompany';
if(!empty($buyerPersonalTypes) && Loader::includeModule($moduleId))
{
    Option::set($moduleId, 'PERSON_TYPES', $buyerPersonalTypes);
    Option::set($moduleId, 'API_KEY', 'b7d8c3596993b5bb45fe728635b9487dfb3c7c35');
    Option::set($moduleId, 'SECRET_KEY', 'bd135be2605fc8b52c6dbe78a6b9a4ac51bcaebd');
    Option::set($moduleId, 'GROUP_DEFAULT_RIGHT', 'D');

    $buyerPersonalTypes = unserialize($buyerPersonalTypes);
    foreach ($buyerPersonalTypes as $index => $buyerPersonalType) {
        Option::set($moduleId, 'ADDRESS_'. $buyerPersonalType, 'ADDRESS');
        Option::set($moduleId, 'COMPANY_'. $buyerPersonalType, ( $index == 1 ? 'NAME' : 'COMPANY' ) );
        Option::set($moduleId, 'F_'. $buyerPersonalType, ( $index == 1 ? 'CONTACT_NAME' : 'CONTACT_PERSON' ) );
        Option::set($moduleId, 'INN_'. $buyerPersonalType, 'INN' );
        Option::set($moduleId, 'INPUT_'. $buyerPersonalType, 'INN' );
        Option::set($moduleId, 'KPP_'. $buyerPersonalType, 'KPP' );
    }
}

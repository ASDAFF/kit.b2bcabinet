<?
    $arResult['USER_ACCOUNT'] = CSaleUserAccount::GetByID($USER->GetID());
    if(!empty($arResult['USER_ACCOUNT']) && !empty($arResult['USER_ACCOUNT']['CURRENT_BUDGET'])) {
        $arResult['USER_ACCOUNT']['FORMAT_CURRENT_BUDGET'] = CCurrencyLang::CurrencyFormat($arResult['USER_ACCOUNT']['CURRENT_BUDGET'], $arResult['USER_ACCOUNT']['CURRENCY'], true);
    }
?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(isset($arParams['MANAGER_ID']) && !empty($arParams['MANAGER_ID']))
{
    $managerID = $arParams['MANAGER_ID'];
}
else
{
    if(is_object($USER) && $USER->IsAuthorized())
    {
        $managerID = '';
        $userID = $USER->GetID();
        $resUser = CUser::GetByID($userID);
        $arUser = $resUser->fetch();
        //TODO -- need add user field: UF_P_MANAGER_ID
        $managerID = $arUser['UF_P_MANAGER_ID'];
    }
}

if(!empty($managerID)) {
    $resManager = CUser::GetByID($managerID);
    $arManager = $resManager->fetch();

    if(!empty($arManager))
    {
        if(!empty($arParams['SHOW_FIELDS']))
        {
            foreach ($arParams['SHOW_FIELDS'] as $field)
            {
                $arResult[$field] = $arManager[$field];
            }
        }
    }
}

if(
    (isset($arParams['USER_PROPERTY']) && !empty($arParams['USER_PROPERTY'])) &&
    (is_object($USER) && $USER->IsAuthorized())
)
{
    $order = array('sort' => 'asc');
    $by = array('sort');
    $arUserFields = $arParams['USER_PROPERTY'];

    $rsUser = CUser::GetList(
        $order,
        $by,
        array( 'ID' => $USER->GetID()),
        array('SELECT' => $arUserFields)
    );

    $res = $rsUser->fetch();

    foreach ($arUserFields as $arUserField) {
        if(isset($res[$arUserField]) && !empty($res[$arUserField]))
        {
            $arResult['USER_PROPERTY'][$arUserField] = $res[$arUserField];
        }
    }

}
$this->IncludeComponentTemplate();      
?>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(isset($arResult['PERSONAL_PHOTO']) && !empty($arResult['PERSONAL_PHOTO']))
{
    $imageFile = CFile::GetFileArray($arResult['PERSONAL_PHOTO']);
    if($imageFile !== false)
    {
        $arResult['PERSONAL_PHOTO'] = CFile::ResizeImageGet(
            $imageFile,
            array("width" => 100, "height" => 100),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }
}
?>
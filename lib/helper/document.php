<?php
namespace Kit\B2bCabinet\Helper;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

Loc::loadMessages(__FILE__);

/**
 * Class Document
 * For working with module documents
 *
 * @package Kit\B2bCabinet\Helper
 */
class Document
{
    /**
     * Selected IBLOCKS in setting
     */
    public const  IBLOCKS_ID   = 'DOCUMENT_IBLOCKS_ID';

    /**
     * Selected IBLOCKS TYPE in setting
     */
    public const  IBLOCKS_TYPE = 'DOCUMENT_IBLOCKS_TYPE';

    /**
     * Get current dir
     * @param $dirname current dir
     *
     * @return mixed|string
     */
    public static function getSelfDir($dirname) {
        $lastDir = '';

        $ex = explode(DIRECTORY_SEPARATOR, $dirname);
        if(!empty($ex)) {
            $lastDir = $ex[count($ex) - 1];
        }
        return $lastDir;
    }

    /**
     * Get selected infoblocks from settings
     *
     * @param  bool  $allFields return all fields
     *
     * @return array|bool
     */
    public static function getIblocks($allFields = false) {
        if(Config::get(self::IBLOCKS_ID)) {
            $arr = unserialize(Config::get(self::IBLOCKS_ID));
            $types = self::getIblocksType();

            if(!empty($arr) && !empty($types) && Loader::includeModule('iblock')) {
                $iblocks = \CIBlock::GetList([], [
                    'LID' => SITE_ID,
                    'ID' => $arr,
                    'TYPE' => $types,
                    'ACTIVE' => 'Y'
                ]);
                $arr = [];
                while($fields = $iblocks->Fetch()) {
                    if(!$allFields) {
                        $arr[] = $fields['ID'];
                    } else {
                        $arr[] = $fields;
                    }
                }

                return $arr;
            }
        }

        return false;
    }

    /**
     * Get infoblocks types
     *
     * @return bool|string
     */
    public static function getIblocksType() {
        $type = Config::get(self::IBLOCKS_TYPE);
        $allType = Config::getIblockTypes();

        if(!empty($allType[$type])) {
            return $type;
        }

        return false;
    }

    /**
     * @param $path document folder
     *
     * @return bool|mixed
     */
    public static function getIblockFromPath($path) {
        global $APPLICATION;
        $path = Config::getPath().$path.'/';
        $path = str_replace($path, '', $APPLICATION->GetCurDir());
        if(!empty($path)) {
            if(stripos($path, '/') !== false) {
                $ex = explode("/", $path);
                $path = $ex[0];
            }
            return self::getIblockFromCode($path);

        }

        return false;
    }

    /**
     * Get ID iblock in CODE
     * @param $code
     *
     * @return bool|mixed
     */
    public static function getIblockFromCode($code) {
        if(!empty($code) && !empty(self::getIblocksType())) {
            $iblocks = \CIBlock::GetList([], [
                'LID' => SITE_ID,
                'TYPE' => self::getIblocksType(),
                'CODE' => $code,
                'ACTIVE' => 'Y'
            ]);
            $fields = $iblocks->Fetch();
            if(!empty($fields['ID']))
                return $fields['ID'];
        }

        return false;
    }

    /**
     * Check permission current user for delete document
     *
     * @param $id
     *
     * @return bool
     */
    public static function checkPermissionElement($id) {
        global $USER;

        if(\CModule::IncludeModule('iblock')) {
            $docId = \CIBlockElement::GetList(
                ["SORT" => "ASC"],
                [
                    'ACTIVE'           => 'Y',
                    '=ID'              => (int)$id,
                    'IBLOCK_TYPE'      => self::getIblocksType(),
                    'IBLOCK_ID'        => self::getIblocks(),
                    'PROPERTY_USER.ID' => $USER->GetID(),
                ],
                false,
                ['nTopCount' => 1],
                ['ID', 'IBLOCK_ID']
            )->Fetch();
            if(!empty($docId['IBLOCK_ID'])) {
                if(\CIBlock::GetPermission($docId['IBLOCK_ID']) >= 'W') {
                    return true;
                }
            }

        }

        return false;
    }

    /**
     * Check url document section
     * If valid then return IblockID
     * If not valid then redirect to main page documents
     *
     * @param $path Path root document page
     *
     * @return integer
     */
    public function checkUrl($path) {
        global $APPLICATION;
        global $USER;

        $thisDir = Document::getSelfDir($path);
        $iblockType = Document::getIblocksType();
        $iblockID = Document::getIblockFromPath($thisDir);
        $iblocks = Document::getIblocks(true);

        if(empty($iblocks) || empty($iblockType) || empty($iblockID)) {
            $pathDocuments = Config::getPath().$thisDir.'/';

            if(empty($iblockID) && $APPLICATION->GetCurDir() != $pathDocuments) {
                LocalRedirect($pathDocuments);
            }

            if(!empty($iblocks)) {
                $APPLICATION->SetTitle(Loc::getMessage('LISTS'));
                foreach($iblocks as $fields) {
                    ?><a href="<?= $pathDocuments ?><?= $fields["CODE"] ?>/"><?= $fields["NAME"] ?></a><br><?php
                }
            } else {
                $APPLICATION->SetTitle(Loc::getMessage('ERROR'));
                if($USER->IsAdmin()) {
                    echo Loc::getMessage('ERROR_DOCUMENT_PAGE_ADMIN');
                } else {
                    echo Loc::getMessage('ERROR_DOCUMENT_PAGE');
                }
            }

            require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
            exit;
        }

        return $iblockID;
    }
}
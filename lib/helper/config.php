<?php
namespace Kit\B2bCabinet\Helper;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Web;

Loc::loadMessages(__FILE__);

/**
 * Class Config
 *
 * @package Kit\B2bCabinet\Helper
 */
class Config
{
    /**
     * @param $name
     * @param  string  $site
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function get($name, $site = '') {
        return Option::get(\KitB2bCabinet::MODULE_ID, $name, "", (!empty($site) ? $site : SITE_ID));
    }

    /**
     * @param $name
     * @param $value
     * @param  string  $site
     *
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function set($name, $value, $site = '') {
        return Option::set( \KitB2bCabinet::MODULE_ID, $name, $value, (!empty($site) ? $site : SITE_ID) );
    }

    /**
     * Root path
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function getPath() {
        $methodIstall = self::get('method_install');
        if($methodIstall == 'AS_TEMPLATE') {
            $path = self::get('PATH');
            $path = trim(trim(self::get('PATH'), "\\\/"));
            if(empty($path))
                $path = \KitB2bCabinet::PATH;
            return SITE_DIR.$path.'/';
        }

        return SITE_DIR;
    }

    /**
     * All iblock types
     *
     * @return array
     */
    public static function getIblockTypes()
    {
        $return = [];
        try {
            Loader::includeModule('iblock');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $rs = \Bitrix\Iblock\TypeTable::getList(
            [
                'select' => [
                    'ID',
                    'LANG_MESSAGE.NAME',
                ],
                'filter' => [
                    'LANG_MESSAGE.LANGUAGE_ID' => LANGUAGE_ID,
                ],
            ]
        );
        while ($iType = $rs->fetch()) {
            $return[$iType['ID']] = '['.$iType['ID'].'] '.$iType['IBLOCK_TYPE_LANG_MESSAGE_NAME'];
        }

        return $return;
    }

    /**
     * All sites
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getSites()
    {
        $sites = [];
        
        $rs = \Bitrix\Main\SiteTable::getList([
            'select' => ['SITE_NAME', 'LID'],
            'filter' => ['ACTIVE' => 'Y'],
        ]);
        
        while ($site = $rs->fetch()) {
            $sites[$site['LID']] = $site['SITE_NAME'];
        }
        
        if (!is_array($sites) || count($sites) == 0) {
            echo "Cannot get sites";
        }
        
        return $sites;
    }

    /**
     * Checks the SITE parameter in URI
     * If empty, then redirect to the current site
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function checkUriSite() {
        $request = Request::getInstance();
        $sites = array_keys(self::getSites());
        $uri = new Web\Uri($request->uri());
        $uri->addParams(array("site"=>$sites[0]));
        LocalRedirect($uri->getUri(), true);
    }

    /**
     * Method install modules (AS_SITE or AS_TEMPLATE)
     *
     * @return string AS_TEMPLATE || AS_SITE
     */
    public static function getMethodInstall($siteId = false) {
        return self::get('method_install', (!empty($siteId) ? $siteId : ''));
    }
}
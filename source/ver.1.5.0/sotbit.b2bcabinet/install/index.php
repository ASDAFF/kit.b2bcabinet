<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;

IncludeModuleLangFile(__FILE__);

class sotbit_b2bcabinet extends CModule
{
    const MODULE_ID = 'sotbit.b2bcabinet';
    const TEMPLATE_NAME = 'b2bcabinet';
    var $MODULE_ID = 'sotbit.b2bcabinet';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('sotbit.b2bcabinet_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('sotbit.b2bcabinet_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('sotbit.b2bcabinet_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('sotbit.b2bcabinet_PARTNER_URI');
    }

    function DeleteUrlRewrite()
    {
        $_795913841 = array();
        if (file_exists('urlrewrite.php')) {
            include('urlrewrite.php');
        }

        $_110589672 = array(
            array('CONDITION' => '#^/b2bcabinet/documents/#',
                'RULE' => '',
                'ID' => 'bitrix:news',
                'PATH' => '/b2bcabinet/documents/index.php',
                'SORT' => 100,
            ),
            array('CONDITION' => '#^/b2bcabinet/order/#',
                'RULE' => '',
                'ID' => 'bitrix:sale.personal.order',
                'PATH' => '/b2bcabinet/orders/index.php',
                'SORT' => 100,
            ),
        );

        foreach ($_110589672 as $_1320546302) {
            if (in_array($_1320546302, $_795913841)) {
                CUrlRewriter::Delete($_1320546302);
            }
        }
    }

    function DoInstall()
    {
        $this->InstallEvents();
        $this->InstallFiles();
        $this->InstallDB();
        RegisterModule(self::MODULE_ID);
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', self::MODULE_ID, '\Sotbit\B2bCabinet\EventHandlers', 'onBuildGlobalMenuHandler');
        EventManager::getInstance()->registerEventHandler('main', 'OnPageStart', self::MODULE_ID, '\Sotbit\B2bCabinet\EventHandlers', 'onPageStart');
        return true;
    }

    function InstallFiles($_601688183 = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/', true, true);
        return true;
    }

    function InstallDB($_601688183 = array())
    {
        return true;
    }

    function DoUninstall()
    {
        UnRegisterModule(self::MODULE_ID);
        $this->UnInstallEvents();
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->DeleteSiteTemplate();
        $this->DeleteUsers();
        $this->DeleteWizard($_SERVER['DOCUMENT_ROOT'] . '/bitrix/wizards/sotbit');
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', self::MODULE_ID, '\Sotbit\B2bCabinet\EventHandlers', 'OnBuildGlobalMenuHandler');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnPageStart', self::MODULE_ID, '\Sotbit\B2bCabinet\EventHandlers', 'onPageStart');
        return true;
    }

    function UnInstallDB($_601688183 = array())
    {
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/wizards/sotbit/b2bcabinet/site/templates/b2bcabinet', $_SERVER['DOCUMENT_ROOT'] . '/local/templates/b2bcabinet');
        $_1073068217 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/wizards/sotbit/b2bcabinet/site/public/ru/';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/b2bcabinet')) {
            DeleteDirFiles($_1073068217 . 'b2bcabinet', $_SERVER['DOCUMENT_ROOT'] . '/b2bcabinet');
        } else {
            DeleteDirFiles($_1073068217 . 'site', $_SERVER['DOCUMENT_ROOT']);
        }
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/components/sotbit/b2bcabinet.catalog.smart.filter')) {
            $_39244912 = array();
            $_39244912 = scandir($_SERVER['DOCUMENT_ROOT'] . '/local/components/sotbit/');
            if (!empty($_39244912)) {
                foreach ($_39244912 as $_113930043) {
                    if ($_113930043 == '.' || $_113930043 == '..') {
                        continue;
                    }
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/components/sotbit/' . $_113930043)) {
                        DeleteDirFilesEx('/local/components/sotbit/' . $_113930043);
                    }
                }
            }
            DeleteDirFiles($_1073068217 . 'root/local/components/sotbit', $_SERVER['DOCUMENT_ROOT'] . '/local/components/sotbit');
        }
        return true;
    }

    function DeleteSiteTemplate()
    {
        CSiteTemplate::Delete(self::TEMPLATE_NAME);
        return true;
    }

    function DeleteUsers()
    {
        $_1107336931 = Option::get(self::MODULE_ID, 'MANAGER_ID', '', WIZARD_SITE_ID);
        if (!empty($_1107336931)) {
            $_282126001 = new CUser();
            $_282126001->Delete($_1107336931);
        }
        unset($_282126001);
        $_2080500913 = Option::get(self::MODULE_ID, 'TEST_USER_ID', '', WIZARD_SITE_ID);
        if (!empty($_2080500913)) {
            $_282126001 = new CUser();
            $_282126001->Delete($_2080500913);
        }
    }

    function DeleteWizard($_1772565682)
    {
        if (is_dir($_1772565682) === true) {
            $_126060925 = array_diff(scandir($_1772565682), array('.', '..'));
            foreach ($_126060925 as $_1932476137) {
                $this->DeleteWizard(realpath($_1772565682) . '/' . $_1932476137);
            }
            return rmdir($_1772565682);
        } else if (is_file($_1772565682) === true) {
            return unlink($_1772565682);
        }
        return true;
    }
}
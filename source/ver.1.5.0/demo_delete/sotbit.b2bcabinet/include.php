<?php

IncludeModuleLangFile(__FILE__);
CModule::AddAutoloadClasses('sotbit.b2bcabinet',
    array(
        'PHPExcel' => 'classes/PHPExcel/PHPExcel.php',
        'PHPExcel_IOFactory' => 'classes/PHPExcel/PHPExcel/IOFactory.php'
    )
);

class SotbitB2bCabinet
{
    const MODULE_ID = 'sotbit.b2bcabinet';
    const PATH = 'b2bcabinet';
    static private $_1347303887 = null;

    public function getDemo()
    {
        if (self::$_1347303887 === false || self::$_1347303887 === null) self::__1147077620();
        return !(self::$_1347303887 == 0 || self::$_1347303887 == 3);
    }

    private static function __1147077620()
    {
        self::$_1347303887 = \Bitrix\Main\Loader::includeSharewareModule(SotbitB2bCabinet::MODULE_ID);
    }

    public function returnDemo()
    {
        if (self::$_1347303887 === false || self::$_1347303887 === null) self::__1147077620();
        return self::$_1347303887;
    }

    public function checkInstalledModules(array $_153994115)
    {
        foreach ($_153994115 as $_121003038) {
            if (!\Bitrix\Main\Loader::includeModule($_121003038)) return false;
        }
        return true;
    }
}
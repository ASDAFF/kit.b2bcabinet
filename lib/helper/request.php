<?php
namespace Kit\B2bCabinet\Helper;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class Request
{
    protected static $_instance;
    protected static $_request;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
            self::$_request = Application::getInstance()->getContext()->getRequest();
        }

        return self::$_instance;
    }

    public function get($code = '') {
        return $code ? self::$_request->get($code) : self::$_request;
    }

    public function getPost($code = '') {
        return $code ? self::$_request->getPost($code) : self::$_request;
    }

    public function method($code = '') {
        return self::$_request->getRequestMethod();
    }

    public function uri() {
        return self::$_request->getRequestUri();
    }

    private function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }
}
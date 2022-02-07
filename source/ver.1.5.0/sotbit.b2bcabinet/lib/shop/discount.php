<?php
namespace Sotbit\B2BCabinet\Shop;

class Discount extends \SotbitB2bCabinet {
    protected $name = '';

    public function __construct($filter = array()) {
        if($this->getDemo() && \Bitrix\Main\Loader::includeModule('catalog')) {
            $discount = \Bitrix\Catalog\DiscountTable::getList(array(
                'filter' => $filter,
                'select' => array(
                    'ID',
                    'NAME',
                ),
                'limit' => 1
            ))->fetch();

            if($discount['ID'] > 0) {
                $this->name = $discount['NAME'];
            }
        }
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}
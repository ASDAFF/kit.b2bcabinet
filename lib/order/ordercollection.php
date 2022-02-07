<?php
namespace Kit\B2BCabinet\Order;

use Bitrix\Sale\Internals\PersonTypeTable;

class OrderCollection extends \KitB2bCabinet {
    private $limit = 2;
    
    public function __construct() {
    }
    
    public function getOrders($filter = array()) {
        $result = [];
        if($this->getDemo()) {
            $orders = array();
            $listStatusNames = \Bitrix\Sale\OrderStatus::getAllStatusesNames(LANGUAGE_ID);
            $personTypes = array();
            $orderList = \Bitrix\Sale\Order::getList(array(
                'select' => array(
                    "ID",
                    'PERSON_TYPE_ID',
                    'STATUS_ID',
                    'ACCOUNT_NUMBER',
                    'PRICE',
                    'CURRENCY',
                    'DELIVERY_ID',
                    'DATE_INSERT'
                ),
                'filter' => $filter,
                'order' => array("ID" => "DESC"),
                'limit' => $this->limit
            ));
            while($order = $orderList->fetch()) {
                $result[$order['ID']] = new Order($order);
                $result[$order['ID']]->setStatus(array(
                    $order['STATUS_ID'] => $listStatusNames[$order['STATUS_ID']]
                ));
                $orders[$order['ID']] = $order;
                $personTypes[$order['ID']] = $order['PERSON_TYPE_ID'];
            }
            
            $rsPersonTypes = PersonTypeTable::getList(array('filter' => array('ID' => $personTypes)));
            
            while($personType = $rsPersonTypes->fetch()) {
                foreach($personTypes as $idOrder => $idPersonType) {
                    if($idPersonType == $personType['ID']) {
                        $result[$idOrder]->setPersonType($personType['NAME']);
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getLimit() {
        return $this->limit;
    }
    
    public function setLimit($limit) {
        $this->limit = $limit;
    }
}
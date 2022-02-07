<?php
namespace Kit\B2BCabinet\Shop;

use Bitrix\Sale\Internals\PersonTypeTable;

class OrderCollection extends \KitB2BCabinet
{
    public $orderList = [];
    private $limit = 2;

    public function getOrders($filter = [])
    {
        if($this->getDemo()) {
            $orders = [];
            $listStatusNames = \Bitrix\Sale\OrderStatus::getAllStatusesNames(LANGUAGE_ID);
            $personTypes = [];
            $orderList = \Bitrix\Sale\Order::getList(['select' => ["ID", 'PERSON_TYPE_ID', 'STATUS_ID', 'ACCOUNT_NUMBER', 'PRICE', 'CURRENCY', 'DELIVERY_ID', 'DATE_INSERT'],
                                                      'filter' => $filter,
                                                      'order'  => ["ID" => "DESC"],
                                                      'limit' => $this->limit]
            );
            while($order = $orderList->fetch()) {
                $this->orderList[$order['ID']] = new Order($order);
                $this->orderList[$order['ID']]->setStatus([$order['STATUS_ID'] => $listStatusNames[$order['STATUS_ID']]]);
                $orders[$order['ID']] = $order;
                $personTypes[$order['ID']] = $order['PERSON_TYPE_ID'];
            }
            $rsPersonTypes = PersonTypeTable::getList(['filter' => ['ID' => $personTypes]]);
            while($personType = $rsPersonTypes->fetch()) {
                foreach($personTypes as $idOrder => $idPersonType) {
                    if($idPersonType == $personType['ID']) {
                        $this->orderList[$idOrder]->setPersonType($personType['NAME']);
                    }
                }
            }
        }

        return $this->orderList;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
}
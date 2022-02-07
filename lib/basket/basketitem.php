<?php
namespace Kit\B2BCabinet\Basket;

class BasketItem
{
	protected $id = 0;
	protected $price = 0;
	protected $discountPrice = 0;
	protected $qnt = 0;
	protected $fullPrice = 0;
	protected $currency = 'RUB';
	protected $element;
	public function __construct($item = array())
	{
		$this->element = new \Kit\B2BCabinet\Element();

		if($item['ID'])
		{
			$this->id = $item['ID'];
		}

		if($item['PRODUCT_ID'])
		{
			$this->element->setId($item['PRODUCT_ID']);
		}

		if($item['NAME'])
		{
			$this->element->setName($item['NAME']);
		}
		if($item['DETAIL_PAGE_URL'])
		{
			$this->element->setUrl($item['DETAIL_PAGE_URL']);
		}
		if($item['QUANTITY'])
		{
			$this->qnt = $item['QUANTITY'];
		}
		if($item['PRICE'])
		{
			$this->price = $item['PRICE'];
			$this->fullPrice = $this->price * $this->qnt;
		}
		if($item['DISCOUNT_PRICE'])
		{
			$this->discountPrice = $item['DISCOUNT_PRICE'];
		}
		if($item['CURRENCY'])
		{
			$this->currency = $item['CURRENCY'];
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($price)
	{
		$this->price = $price;
	}

	public function getDiscountPrice()
	{
		return $this->discountPrice;
	}

	public function setDiscountPrice($discountPrice)
	{
		$this->discountPrice = $discountPrice;
	}

	public function getFullPrice()
	{
		return $this->fullPrice;
	}

	public function setFullPrice($fullPrice)
	{
		$this->fullPrice = $fullPrice;
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function setCurrency($currency)
	{
		$this->currency = $currency;
	}

	public function getElement()
	{
		return $this->element;
	}

	public function setElement($element)
	{
		$this->element = $element;
	}

	public function getQnt()
	{
		return $this->qnt;
	}

	public function setQnt($qnt)
	{
		$this->qnt = $qnt;
	}
}
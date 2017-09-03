<?php


namespace Aniart\Main\Repositories;

use \Bitrix\Catalog;

class StubProductsRepository extends AbstractIblockElementRepository
{
	protected $arOrder;
	protected $arNavParams;
	protected $arrFilter;
	protected $arSelect = array(
		'NAME',
		'DETAIL_PICTURE',
		'DETAIL_PAGE_URL'
	);
	
	public function newInstance(array $fields = array())
	{
		return app('Product', array($fields));
	}
	
	public function setOrder($arOrder){
		
		$this->arOrder = $arOrder;
	}
	
	public function setNav($arNavParams){
		
		$this->arNavParams = $arNavParams;
	}
	
	public function setFilter($arrFilter){
		global $$arrFilter;
		$this->arrFilter = $$arrFilter;
		if (!is_array($arrFilter)) {
			$this->arrFilter = array();
		}
		
	}
	
	public function getViewedProductIds($except_id = null,$limit = 5){
		$ids = array();
		
		$filter = array(
			'=FUSER_ID' => \CSaleBasket::GetBasketUserID(),
			'=SITE_ID' => SITE_ID
		);
		
		if($except_id){
			$filter['!=ELEMENT_ID'] =$except_id;
		}
		
		$viewedIterator = Catalog\CatalogViewedProductTable::getList(array(
			'select' => array('ELEMENT_ID'),
			'filter' => $filter,
			'order' => array('DATE_VISIT' => 'DESC'),
			'limit' => $limit
		));
		while ($viewedProduct = $viewedIterator->fetch())
		{
			$ids[] = (int)$viewedProduct['ELEMENT_ID'];
		}


		if(!empty($ids)) {
			$arFilter = array(
				'ID' => $ids
			);
			if(!empty($this->arrFilter))
				
				$arFilter = array_merge($arFilter,$this->arrFilter);
			
			return $this->getList(
				$this->arOrder,
				$arFilter,
				false,
				$this->arNavParams
			);
		}
		return false;
	}
	
	private function getUserID(){
		global $USER;
		return $USER->GetID();
	}
}
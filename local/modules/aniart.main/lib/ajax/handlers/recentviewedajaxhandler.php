<?php
namespace Aniart\Main\Ajax\Handlers;
use Aniart\Main\Ajax\AbstractAjaxHandler;


class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
	protected function isExist($prodId)
	{
		\Bitrix\Main\Loader::includeModule('catalog');
		global $USER;
		$filter = array(
			'FUSER_ID' => $USER->GetID(),
			'ELEMENT_ID' => $prodId
		);
		
		$viewedIterator = \Bitrix\Catalog\CatalogViewedProductTable::getList(array(
			'select' => array('ID'),
			'filter' => $filter,
		));
		
		if ($viewedProduct = $viewedIterator->fetch())
		{
			
			return $viewedProduct['ID'];
		}
		return false;
	}
	
	public function deleteItem()
	{
		
		$prodId = (int)$this->post['productId'];
		if($viewed_id = $this->isExist($prodId)){
			
			$result = \Bitrix\Catalog\CatalogViewedProductTable::delete($viewed_id);
			if($result->isSuccess()){
				return $this->setOk();
			}
			else{
				return $this->setError($result->getErrorMessages());
			}
		}
	}
}
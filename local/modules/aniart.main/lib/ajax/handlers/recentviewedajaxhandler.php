<?php


namespace Aniart\Main\Ajax\Handlers;


use Aniart\Main\Ajax\AbstractAjaxHandler;
use Bitrix\Catalog\CatalogViewedProductTable;
use CSaleBasket;

class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
	public function deleteItem()
	{
		$id = $this->request['productId'];
        $saleUserID = CSaleBasket::GetBasketUserID();
        $filter = array('=FUSER_ID' => $saleUserID, '=SITE_ID' => SITE_ID, 'ELEMENT_ID' => $id);

        $res = CatalogViewedProductTable::getList(array(
            'select' => array('PRODUCT_ID', 'ELEMENT_ID', 'ID'),
            'filter' => $filter,
            'order' => array('DATE_VISIT' => 'DESC')
        ))->Fetch();

        if (!$res) {
            $this->setError('Товар не найден в просмотреных');
            return;
        }

        $deleteResult = CatalogViewedProductTable::delete($res['ID']);
        if ($deleteResult->isSuccess()) {
            $this->setOK(['deletedID' => $res['ELEMENT_ID']]);
        } else {
            $this->setError($this->formatErrors($deleteResult->getErrorMessages()));
        }
	}

    private function formatErrors($errors)
    {
        if (is_array($errors)) return implode(". ", $errors);

        return $errors;
    }
}
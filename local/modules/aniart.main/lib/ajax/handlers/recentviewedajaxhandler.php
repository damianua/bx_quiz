<?php


namespace Aniart\Main\Ajax\Handlers;


use Aniart\Main\Ajax\AbstractAjaxHandler;

class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
	public function deleteItem()
	{
            $prodId = (int)$this->post["productId"];
            if ( 
                $prodId 
                && \Bitrix\Main\Loader::includeModule('catalog') 
            ){
                $result = \Bitrix\Catalog\CatalogViewedProductTable::delete($prodId);
                if($result->isSuccess()){
                    echo json_encode(["STATUS"=>"OK"]);
                }else{
                    echo json_encode(["STATUS"=>"ERROR"]);
                }
            }
	}
}
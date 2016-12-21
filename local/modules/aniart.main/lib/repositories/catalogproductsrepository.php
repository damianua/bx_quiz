<?php


namespace Aniart\Main\Repositories;


class CatalogProductsRepository extends AbstractIblockElementRepository
{
    private  $productParams = array();
	public function newInstance(array $fields = array())
	{
		return app('Product', [array_merge($fields,$this->productParams)]);
	}

	public function getItems($params)
	{
	    $this->productParams = (array)$params['product_params'];
        $params['select'] = array_merge($this->getDefaultSelect(), (array)$params['select']);
		return $this->getList(array(), $params['filter'], false, false, $params['select']);
	}

	private function getDefaultSelect() {
	    return [
	        'ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE'
        ];
    }
}
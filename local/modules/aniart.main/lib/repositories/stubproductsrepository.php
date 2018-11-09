<?php


namespace Aniart\Main\Repositories;


class StubProductsRepository extends AbstractIblockElementRepository
{
	public function newInstance(array $fields = array())
	{
		return app('Product', [$fields]);
	}

	public function getItemsByIds(array $ids = [])
	{
		$result = $this->getList(array(), array("ID" => $ids), false, false, array("ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "PROPERTY_MORE_PHOTO"), true);
		return $result;
	}
}
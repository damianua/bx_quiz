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
<<<<<<< HEAD
		return [
			$this->newInstance(),
			$this->newInstance(),
			$this->newInstance(),
		];
=======
	    if (!empty($ids)){
            return $this->getList(array(), array("ID" => $ids));
        }

		return false;
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
	}
}
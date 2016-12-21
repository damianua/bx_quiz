<?php


namespace Aniart\Seo\Repositories;


use Aniart\Main\Repositories\AbstractHLBlockElementsRepository;

class SeoPagesRepository extends AbstractHLBlockElementsRepository
{
	public function newInstance(array $fields)
	{
		return app('SeoPage', array($fields));
	}
}
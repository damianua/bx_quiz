<?php


namespace Aniart\Seo\Repositories;
use Aniart\Main\Repositories\AbstractHLBlockElementsRepository as AbstractHLBlockElementsRepositorySeo;

class SeoPagesRepository extends AbstractHLBlockElementsRepositorySeo
{
	public function newInstance(array $fields)
	{
		return app('SeoPage', array($fields));
	}
}
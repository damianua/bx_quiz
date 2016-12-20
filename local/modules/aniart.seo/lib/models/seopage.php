<?php


namespace Aniart\Seo\Models;


use Aniart\Seo\Interfaces\SeoParamsInterface;
use Aniart\Main\Models\AbstractHLElementModel;

class SeoPage extends AbstractHLElementModel implements SeoParamsInterface
{
	public function getSort()
	{
		return (int)$this->fields['UF_SORT'];
	}

	public function getPageUri()
	{
		return $this->fields['UF_PAGE'];
	}

	public function getPageTitle()
	{
		return $this->fields['UF_PAGE_TITLE'];
	}

	public function getMetaTitle()
	{
		return $this->fields['UF_META_TITLE'];
	}

	public function getKeywords()
	{
		return $this->fields['UF_KEYWORDS'];
	}

	public function getDescription()
	{
		return $this->fields['UF_DESCRIPTION'];
	}
}
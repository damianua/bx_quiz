<?php
$modulePath = dirname(__FILE__);
\Bitrix\Main\Loader::includeModule('aniart.main');



app()->bind([
	'SeoPage' => '\Aniart\Main\Models\SeoPage'
]);

app()->singleton([
	'SeoParamsCollector' => '\Aniart\Main\Seo\SeoParamsCollector',
	'SeoPagesRepository' => function(\Aniart\Main\ServiceLocator $locator){
		return new Aniart\Main\Repositories\SeoPagesRepository(Bitrix\Main\Config\Option::get("aniart.seo","HL_SEO_PAGES_ID"));
	},
	'SmartSeo' => function(\Aniart\Main\ServiceLocator $locator) {
		$smartSeo = \Aniart\Main\SmartSeo\SmartSeo::getInstance();
		try {
			$smartSeo->init(new \Aniart\Main\SmartSeo\Repositories\HLBlockPagesRepository());
			return $smartSeo;
		} catch (Exception $e) {
		}
	}
]);

?>
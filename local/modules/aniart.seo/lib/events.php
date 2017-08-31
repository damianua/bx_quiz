<?php
namespace Aniart\Seo;

use \Aniart\Seo\SmartSeo;
use \Aniart\Seo\SeoParamsCollector;

use \Aniart\Main\ServiceLocator;
use \Aniart\Main\Repositories\SeoPagesRepository;
use \Aniart\Main\SmartSeo\Repositories\HLBlockPagesRepository;
use \Bitrix\Main\Loader;

class Events {

    static public function onEpilog() {
        $uriString = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getRequestUri();
        $isAdminPanel = preg_match("|^/bitrix/.*$|", $uriString)?true:false;
        
        if (Loader::includeModule('aniart.main') && !$isAdminPanel) {
            app()->bind([
                'SeoPage' => '\Aniart\Main\Models\SeoPage',
            ]);
            app()->singleton([
                'SeoParamsCollector' => '\Aniart\Seo\SeoParamsCollector',
                'SeoPagesRepository' => function(ServiceLocator $locator) {
                    return new SeoPagesRepository(\Bitrix\Main\Config\Option::get("aniart.seo", "hl_block_id"));
                },
                'SmartSeo' => function(ServiceLocator $locator) {
                    $smartSeo = SmartSeo::getInstance();
                    $smartSeo->init(new HLBlockPagesRepository());
                    return $smartSeo;
                },
            ]);

            /**
             * @var SmartSeo $smartSeo
             */
            $smartSeo = app('SmartSeo');
            if ($smartSeo->isPageFound()) {
                $pageMeta = $smartSeo->getPageMeta();
                seo()->setPageTitle($pageMeta['page_title'], true);
                seo()->setMetaTitle($pageMeta['meta_title'], true);
                seo()->setKeywords($pageMeta['keywords'], true);
                seo()->setDescription($pageMeta['description'], true);
            }
            seo()->process();
        }
    }

}

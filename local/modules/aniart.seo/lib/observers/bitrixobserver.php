<?php


namespace Aniart\Seo\Observers;

use Aniart\Seo\SmartSeo\SmartSeo as SmartSeo;

class BitrixObserver
{
    public static function onEpilog()
    {
        self::setSeoParams();
    }

    protected static function setSeoParams()
    {
        /**
         * @var SmartSeo $smartSeo
         */
        $smartSeo = app('SmartSeo');

        if($smartSeo && $smartSeo->isPageFound()) {

            $pageMeta = $smartSeo->getPageMeta();
            seo()->setPageTitle($pageMeta['page_title'], true);
            seo()->setMetaTitle($pageMeta['meta_title'], true);
            seo()->setKeywords($pageMeta['keywords'], true);
            seo()->setDescription($pageMeta['description'], true);
        }

        seo()->process();
    }
}
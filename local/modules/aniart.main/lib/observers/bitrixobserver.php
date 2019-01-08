<?php
<<<<<<< HEAD


namespace Aniart\Main\Observers;

use Aniart\Main\SmartSeo\SmartSeo as SmartSeo;

=======
namespace Aniart\Main\Observers;

>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
class BitrixObserver
{
    public static function onProlog()
    {
	    global $APPLICATION;
        self::initAdditionalUserParams();
	    $APPLICATION->oAsset->addJs('/local/js/jquery.min.js');
	    $APPLICATION->oAsset->addJs('/local/js/aniart.widget.js');
    }

    public static function onEpilog()
    {
<<<<<<< HEAD
        self::setSeoParams();
=======
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
    }

    protected static function initAdditionalUserParams()
    {
        global $USER;
        if(!$USER->IsAuthorized()){
            return;
        }
        if(!isset($_SESSION['SESS_AUTH']['PERSONAL_MOBILE'])){
            $userData = \CUser::GetByID($USER->GetID())->Fetch();
            if($userData){
                $_SESSION['SESS_AUTH']['PERSONAL_MOBILE'] = $userData['PERSONAL_MOBILE'];
            }
        }
    }
<<<<<<< HEAD

    protected static function setSeoParams()
    {
	    /**
	     * @var SmartSeo $smartSeo
	     */
	    $smartSeo = app('SmartSeo');
	    if($smartSeo->isPageFound()) {
		    $pageMeta = $smartSeo->getPageMeta();
		    seo()->setPageTitle($pageMeta['page_title'], true);
		    seo()->setMetaTitle($pageMeta['meta_title'], true);
		    seo()->setKeywords($pageMeta['keywords'], true);
		    seo()->setDescription($pageMeta['description'], true);
	    }
        seo()->process();
    }
=======
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
}
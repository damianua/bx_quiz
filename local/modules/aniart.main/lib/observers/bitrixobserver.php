<?php


namespace Aniart\Main\Observers;

class BitrixObserver
{
    public static function onProlog()
    {
	    global $APPLICATION;
        self::initAdditionalUserParams();
	    $APPLICATION->oAsset->addJs('/local/js/jquery.min.js');
	    $APPLICATION->oAsset->addJs('/local/js/aniart.widget.js');
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

}
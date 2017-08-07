<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('aniart.main')) {
    throw new SystemException(Loc::getMessage("ANIART_EVENT_INSTALL_ERROR_ANIART_MAIN_NOT_INSTALED"));
}
if ( !Loader::includeModule('highloadblock') ) {
    throw new SystemException(Loc::getMessage("ANIART_EVENT_INSTALL_ERROR_HIGHLOAD_NOT_INSTALED"));
}



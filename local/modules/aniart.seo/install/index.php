<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

Class aniart_seo extends CModule {

    function __construct() {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");
        $this->MODULE_ID = 'aniart.seo';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("ANIART_EVENT_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("ANIART_EVENT_MODULE_DESC");
        $this->PARTNER_NAME = Loc::getMessage("ANIART_EVENT_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("ANIART_EVENT_PARTNER_URI");
    }

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot = false) {
        if ($notDocumentRoot)
            return str_ireplace($_SERVER["DOCUMENT_ROOT"], '', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    //Проверяем что система поддерживает D7
    public function isVersionD7() {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB() {
        $path = $this->GetPath()."/lib/hbprovider.php";
        if ( file_exists($path) ) 
        {
            require_once $path;
            $objHBProvider = new \Aniart\Seo\HBProvider();
            $objHBProvider->Init();        
        }
        return true;
    }

    function UnInstallDB() {
        $path = $this->GetPath()."/lib/hbprovider.php";
        if ( file_exists($path) ) 
        {
            require_once $path;
            $objHBProvider = new \Aniart\Seo\HBProvider();
            $objHBProvider->Init();        
            $objHBProvider->Delete();
            \Bitrix\Main\Config\Option::delete("aniart.seo", ["name" => "hl_block_id"]);
        }
        return true;
    }

    function InstallEvents() {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler('main', 'OnEpilog', $this->MODULE_ID, '\Aniart\Seo\Events', 'onEpilog');
    }

    function UnInstallEvents() {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'OnEpilog', $this->MODULE_ID, '\Aniart\Seo\Events', 'onEpilog');
    }

    function InstallFiles() {
        return true;
    }

    function UnInstallFiles() {
        return true;
    }

    function DoInstall() {
        global $APPLICATION;
        
        if ($this->isVersionD7()) {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        } else {
            throw new SystemException(Loc::getMessage("ANIART_EVENT_INSTALL_ERROR_VERSION"));
        }
        if (!Loader::includeModule('aniart.main')) {
            throw new SystemException(Loc::getMessage("ANIART_EVENT_INSTALL_ERROR_ANIART_MAIN_NOT_INSTALED"));
        }
        if ( !Loader::includeModule('highloadblock') ) {
            throw new SystemException(Loc::getMessage("ANIART_EVENT_INSTALL_ERROR_HIGHLOAD_NOT_INSTALED"));
        }
    }

    function DoUninstall() {
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
    }
}

?>
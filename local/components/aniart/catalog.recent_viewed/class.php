<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Iblock\Component\ElementList;
use \Bitrix\Catalog;

class CatalogViewedElementListComponent extends ElementList
{
    /**
     * кешируемые ключи arResult
     * @var array()
     */
    protected $cacheKeys = array();

    /**
     * дополнительные параметры, от которых должен зависеть кеш
     * @var array
     */
    protected $cacheAddon = "сtg-rct-vwd";

    /**
     * подключает языковые файлы
     */
    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    /**
     * подготавливает входные параметры
     * @param array $arParams
     * @return array
     */

    public function onPrepareComponentParams($params)
    {
        $result = array(
            "IBLOCK_ID" => intval($params['IBLOCK_ID']),
            "CACHE_TIME" => intval($params['CACHE_TIME']) > 0 ? intval($params['CACHE_TIME']) : 3600,
            "CACHE_TYPE" => strlen($params['CACHE_TYPE']) > 0 ? $params['CACHE_TYPE'] : "A",
            "MESS_BTN_BUY" => strlen($params['MESS_BTN_BUY']) > 0 ? $params['MESS_BTN_BUY'] : GetMessage("CVP_MESS_BTN_BUY_DEFAULT"),
            "MESS_BTN_DETAIL" => strlen($params['MESS_BTN_DETAIL']) > 0 ? $params['MESS_BTN_DETAIL'] : GetMessage("CVP_MESS_BTN_DETAIL_DEFAULT"),
            "MESS_BTN_SUBSCRIBE" => strlen($params['MESS_BTN_SUBSCRIBE']) > 0 ? $params['MESS_BTN_SUBSCRIBE'] : GetMessage("CVP_MESS_BTN_SUBSCRIBE_DEFAULT"),
            "PAGE_ELEMENT_COUNT" => intval($params['PAGE_ELEMENT_COUNT']) > 0 ? intval($params['PAGE_ELEMENT_COUNT']) : 5,
            "SECTION_ELEMENT_ID" => intval($params['SECTION_ELEMENT_ID']) > 0 ? intval($params['SECTION_ELEMENT_ID']) : $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],
            "SECTION_ID" => intval($params['SECTION_ID']) > 0 ? intval($params['SECTION_ID']) : $GLOBALS["CATALOG_CURRENT_SECTION_ID"],
            "SHOW_FROM_SECTION" => strlen($params['SHOW_FROM_SECTION']) > 0 ? $params['SHOW_FROM_SECTION'] : "N",
            "SHOW_IMAGE" => strlen($params['SHOW_IMAGE']) > 0 ? $params['SHOW_IMAGE'] : "Y",
            "SHOW_NAME" => strlen($params['SHOW_NAME']) > 0 ? $params['SHOW_NAME'] : "Y",

        );
        return $result;
    }

    /**
     * определяет читать данные из кеша или нет
     * @return bool
     */
    protected function readDataFromCache()
    {
        if ($this->arParams['CACHE_TYPE'] == 'N')
            return false;

        return !($this->StartResultCache($this->arParams['CACHE_TIME'], $this->cacheAddon));
    }

    /**
     * кеширует ключи массива arResult
     */
    protected function putDataToCache()
    {
        if (is_array($this->cacheKeys) && sizeof($this->cacheKeys) > 0) {
            $this->SetResultCacheKeys($this->cacheKeys);
        }
    }

    /**
     * прерывает кеширование
     */
    protected function abortDataCache()
    {
        $this->AbortResultCache();
    }

    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('catalog'))
            throw new Main\LoaderException(Loc::getMessage('STANDARD_ELEMENTS_LIST_CLASS_IBLOCK_MODULE_NOT_INSTALLED'));
    }

    /**
     * проверяет заполнение обязательных параметров
     * @throws SystemException
     */
    protected function checkParams()
    {
        if ($this->arParams['IBLOCK_ID'] <= 0)
            throw new Main\ArgumentNullException('IBLOCK_ID');
    }

    /**
     * выполяет действия перед кешированием
     */
    protected function executeProlog()
    {

    }

    /**
     * получение результатов
     */
    protected function getResult()
    {
        if (!Main\Loader::includeModule('sale'))
        {
            return array();
        }

        $UserId = (int)CSaleBasket::GetBasketUserID(false);
        if ($UserId <= 0)
        {
            return array();
        }
        $this->arResult['BASKET_ID'] = $UserId;


        $arId = array();
        $filter = array(
            '=FUSER_ID' => $UserId,
            '=SITE_ID' => $this->getSiteId()
        );

        if ($this->arParams['SECTION_ELEMENT_ID'] > 0)
        {
            $filter['!=ELEMENT_ID'] = $this->arParams['SECTION_ELEMENT_ID'];
        }

        $resViewed = Catalog\CatalogViewedProductTable::getList(array(
            'select' => array('ELEMENT_ID'),
            'filter' => $filter,
            'order' => array('DATE_VISIT' => 'DESC'),
            'limit' => $this->arParams['PAGE_ELEMENT_COUNT']
        ));
        while ($elementViewed = $resViewed->fetch())
        {
            $arId[] = $elementViewed['ELEMENT_ID'];
        }

        $this->filterFields = $this->getFilter();
        $this->filterFields['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
        $this->initPricesQuery();

        $arId = array_slice($this->filterByParams($arId, array(), false), 0, $this->arParams['PAGE_ELEMENT_COUNT']);

        if (!empty($arId)){
            $this -> arResult['ELEMENT_VIEWED'] = $arId;
        }else{
            $this -> arResult['ELEMENT_VIEWED'] = array();
        };

        //return $arId;
    }

    /**
     * выполняет действия после выполения компонента, например установка заголовков из кеша
     */
    protected function executeEpilog()
    {

    }

    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->checkParams();
            //$this->executeProlog();
            $this->getResult();
            //$this->executeEpilog();
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            $this->abortDataCache();
            ShowError($e->getMessage());
        }
    }
}
?>
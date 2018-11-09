<?php
/**
 * Created by PhpStorm.
 * User: damian
 * Date: 04.12.14
 * Time: 17:23
 */

namespace Aniart\Main\Repositories;


use Aniart\Main\Interfaces\ErrorableInterface;
use Aniart\Main\Models\AbstractModel;
use Aniart\Main\Traits\ErrorTrait;

abstract class AbstractIblockElementRepository implements ErrorableInterface
{
    use ErrorTrait;
    
    protected $iblockId;
    protected $selectedFields = array();

    public function __construct($iblockId)
    {
        $this->iblockId = $iblockId;
    }

    public function getIblockId()
    {
        return $this->iblockId;
    }

    /**
     * @param array $fields
     * @return \Aniart\Main\Models\AbstractModel
     */
    abstract public function newInstance(array $fields = array());

    /**
     * @param array $arOrder
     * @param array $arFilter
     * @param mixed $arGroupBy
     * @param mixed $arNavStartParams
     * @param array $arSelectFields
     * @param bool $getPrice
     * @return \Aniart\Main\Models\AbstractModel[]|array
     */
    public function getList(array $arOrder = Array("SORT"=>"ASC"), array $arFilter = Array(), $arGroupBy = false, $arNavStartParams = false,
                                   array $arSelectFields=Array(), $getPrice = false)
    {
        $result = array();
      	$arFilter['IBLOCK_ID'] = $this->iblockId;
        $arSelectFields = array_merge($this->selectedFields, $arSelectFields);
        $rsElements = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
        if($rsElements->SelectedRowsCount() > 0){
            while($arElement = $rsElements->GetNext()){
                if ($getPrice) {
                    $price = $this->getOfferPrice($arElement["ID"]);
                    $arElement = array_merge($arElement, $price);
                }
	            $result[$arElement['ID']] = $this->newInstance($arElement);
            }
        }

        return $result;
    }
    public function getOfferPrice($productID)
    {
        $result = array();
        $res = \CCatalogSKU::getOffersList($productID, $this->iblockId);
        $firstOffer = reset($res[$productID]);
        $arPrice = \CPrice::GetBasePrice($firstOffer["ID"]);
        $result["PRICE"] = $arPrice["PRICE"];
        $result["CURRENCY"] = $arPrice["CURRENCY"];
        return $result;
    }

    public function getById($id)
    {
        $elements = $this->getList(array('SORT' => 'ASC'), array('ID' => $id));
        if(!empty($elements)){
        	return current($elements);
        }
        return false;
    }

	public function getByCode($code)
	{
		$elements = $this->getList(array(), array('CODE' => $code));
		if(!empty($elements)){
			return current($elements);
		}
		return false;
	}
    
    public function getByXmlId($xmlId)
    {
    	$elements = $this->getList(array('SORT' => 'ASC'), array('XML_ID' => $xmlId));
    	if(!empty($elements)){
    		return current($elements);
    	}
    	return false;
    }

    public function add(array $arFields = array(), $bWorkFlow=false, $bUpdateSearch=true, $bResizePictures=false)
    {
        $arFields['IBLOCK_ID'] = $this->iblockId;
        $iblockElement = new \CIBlockElement();
        if($id = $iblockElement->Add($arFields, $bWorkFlow, $bUpdateSearch, $bResizePictures)){
            return $id;
        }
        else{
            return $this->addError($iblockElement->LAST_ERROR);
        }
    }

    /**
     * @param $id
     * @param array $arFields
     * @param bool $safeMode - при частичном обновлении свойств, остальные свойства не будут обнулены
     * @param bool $bWorkFlow
     * @param bool $bUpdateSearch
     * @param bool $bResizePictures
     * @param bool $bCheckDiskQuota
     * @return bool
     */
    public function update($id, array $arFields = array(), $safeMode = false,
                           $bWorkFlow=false, $bUpdateSearch=true, $bResizePictures=false, $bCheckDiskQuota=true)
    {
        $arFields['IBLOCK_ID'] = $this->iblockId;
        if(isset($arFields['PROPERTY_VALUES']) && $safeMode){
            $properties = $arFields['PROPERTY_VALUES'];
            unset($arFields['PROPERTY_VALUES']);
        }
        $iblockElement = new \CIBlockElement();
        if(!$iblockElement->Update($id, $arFields,  $bWorkFlow, $bUpdateSearch, $bResizePictures, $bCheckDiskQuota)){
            return $this->addError($iblockElement->LAST_ERROR);
        }
        else{
            if(!empty($properties)){
                \CIBlockElement::SetPropertyValuesEx($id, $arFields['IBLOCK_ID'], $properties);
            }
        }

        return $id;
    }

    public function save(AbstractModel $model, $safeMode = true,
                         $bWorkFlow=false, $bUpdateSearch=true, $bResizePictures=false, $bCheckDiskQuota=true)
    {
        $id = $model->getId();
        if($id > 0){
            return $this->update($id, $model->getFields(), $safeMode, $bWorkFlow, $bUpdateSearch, $bResizePictures, $bCheckDiskQuota);
        }
        else{
            return $this->add($model->getFields(), $bWorkFlow, $bUpdateSearch, $bResizePictures);
        }
    }

    public function delete($id)
    {
        /**
         * @var \CMain $APPLICATION
         */
        global $APPLICATION;
        if(!\CIBlockElement::Delete($id)){
            if($ex = $APPLICATION->GetException()){
                $this->addError($ex->GetString(), $ex->GetID());
            }
            return false;
        }

        return true;
    }
}

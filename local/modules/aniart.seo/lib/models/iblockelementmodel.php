<?php 
namespace Aniart\Seo\Models;

use Aniart\Seo\Interfaces\SeoParamsInterface;
use Aniart\Main\Models\IblockElementModel as MainIblockElementModel;
use Bitrix\Iblock\InheritedProperty\ElementValues;

class IblockElementModel extends MainIblockElementModel implements SeoParamsInterface
{
    protected $seoParams;

    public function getPageTitle(){
        return $this->getSeoParamValue('ELEMENT_PAGE_TITLE');
    }

    public function getMetaTitle(){
        return $this->getSeoParamValue('ELEMENT_META_TITLE');
    }

    public function getKeywords(){
        return $this->getSeoParamValue('ELEMENT_META_KEYWORDS');
    }

    public function getDescription(){
        return $this->getSeoParamValue('ELEMENT_META_DESCRIPTION');
    }

    protected function getSeoParamValue($paramName)
    {
        $this->getSeoParams();
        return $this->seoParams[$paramName];
    }

    public function getSeoParams()
    {
        if(is_null($this->seoParams)){
            $this->obtainSeoParams();
        }
        return $this->seoParams;
    }

    public function obtainSeoParams()
    {
        $seoParamsValues = array();
        if(($iblockId = $this->getIblockId()) && ($id = $this->getId())) {
            $seoParams = new ElementValues($iblockId, $id);
            if ($seoParams) {
                $seoParamsValues = $seoParams->getValues();
            }
        }
        $this->seoParams = $seoParamsValues;

        return $this;
    }
}
?>
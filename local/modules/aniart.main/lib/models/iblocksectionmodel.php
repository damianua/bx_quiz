<?php 
namespace Aniart\Main\Models;

use Aniart\Main\Interfaces\SeoParamsInterface;
use Bitrix\Iblock\InheritedProperty\SectionValues;

class IblockSectionModel extends AbstractModel implements SeoParamsInterface
{
	public function getId()
	{
		return $this->ID;	
	}
	
	public function getName()
	{
		return $this->{'~NAME'} ? $this->{'~NAME'} : $this->NAME;
	}

    public function getCode()
    {
        return $this->CODE;
    }
	
	public function getUrl() {
		return $this->SECTION_PAGE_URL;
	}

	public function getXMLId()
	{
		return $this->XML_ID;
	}
	
	public function getIblockId()
	{
		return $this->IBLOCK_ID;
	}
	
	public function getPropertyValue($propName, $index = false)
	{
		$result = false;
		if(!empty($propName)){
			$propValue = $this->{'UF_'.$propName};
			$propValue = $propValue ? $propValue : $this->PROPERTIES[$propName]['VALUE'];
			if($propValue && $index !== false){
				$propValue = $propValue[$index];
			}
			$result = $propValue;
		}
		return $result;
	}
	
	public function getPreviewPictureId()
	{
		return $this->PREVIEW_PICTURE;
	}

    public function getPageTitle(){
        return $this->getSeoParamValue('SECTION_PAGE_TITLE');
    }

    public function getMetaTitle(){
        return $this->getSeoParamValue('SECTION_META_TITLE');
    }

    public function getKeywords(){
        return $this->getSeoParamValue('SECTION_META_KEYWORDS');
    }

    public function getDescription(){
        return $this->getSeoParamValue('SECTION_META_DESCRIPTION');
    }

    protected function getSeoParamValue($paramName)
    {
        $this->getSeoParams();
        return $this->seoParams[$paramName];
    }

    protected function getSeoParams()
    {
        if(is_null($this->seoParams)){
            $this->obtainSeoParams();
        }
        return $this->seoParams;
    }

    protected function obtainSeoParams()
    {
        $seoParamsValues = array();
        if(($iblockId = $this->getIblockId()) && ($id = $this->getId())){
            $seoParams = new SectionValues($iblockId, $id);
            if($seoParams){
                $seoParamsValues = $seoParams->getValues();
            }
        }
        $this->seoParams = $seoParamsValues;

        return $this;
    }
}

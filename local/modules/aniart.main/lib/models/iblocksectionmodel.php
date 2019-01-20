<?php 
namespace Aniart\Main\Models;

use Bitrix\Iblock\InheritedProperty\SectionValues;

class IblockSectionModel extends AbstractModel
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
}

<?php

namespace Aniart\Main\Models;

use Aniart\Main\Interfaces\ProductInterface;

class StubProduct extends IblockElementModel //implements ProductInterface
{
	public function __construct(array $fields = array())
	{
		parent::__construct($fields);
	}
	
	
	public function getPreviewPicture()
	{
		
		return $this->getResizePreviewPictire($this->PREVIEW_PICTURE);
	}
	
	public function getPrice($format = false)
	{
		global $USER;
		$price = $this->CATALOG_PRICE_1;
		$currency = null;
		if (!$price) {
			if ($offers = $this->getOffers()) {
				$arPrice = \CCatalogProduct::GetOptimalPrice($offers[0], 1, array(2), array());
				$price = $arPrice['PRICE']['PRICE'];
				$currency = $arPrice['PRICE']['CURRENCY'];
			}
		}
		return $format ? \CCurrencyLang::CurrencyFormat($price, $currency) : $price;
	}
	
	public function getDetailPageUrl()
	{
		return $this->DETAIL_PAGE_URL;
	}
	
	private function getOffers()
	{
		$arOffers = array();
		$arInfo = \CCatalogSKU::GetInfoByProductIBlock($this->IBLOCK_ID);
		if (is_array($arInfo)) {
			$rsOffers = \CIBlockElement::GetList(
				array(),
				array(
					'IBLOCK_ID' => $arInfo['IBLOCK_ID'],
					'PROPERTY_' . $arInfo['SKU_PROPERTY_ID'] => $this->ID
				),
				false,
				false,
				array('ID')
			);
			while ($arOffer = $rsOffers->GetNext()) {
				$arOffers[] = $arOffer['ID'];
			}
		}
		return $arOffers;
	}
	
	private function getResizePreviewPictire($file, $width = 250, $height = 280, $resizeType = BX_RESIZE_IMAGE_EXACT)
	{
		
		$arFile = \CFile::ResizeImageGet(
			$file,
			Array("width" => $width, "height" => $height),
			$resizeType
		);
		return $arFile['SRC'];
	}
}
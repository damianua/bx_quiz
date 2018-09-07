<?php


namespace Aniart\Main\Repositories;

use Aniart\Main\Models\StubProduct;
use Bitrix\Currency\CurrencyManager;


class StubProductsRepository extends AbstractIblockElementRepository
{
    public function newInstance(array $fields = array())
    {
        return app('Product', [$fields]);
    }

    protected function getOffersPrices(array $ids)
    {
        $offers_ids = array();
        $offers_to_product = [];
        $prices = [];

        if (empty($ids))
            return $prices;

        $elements = \CIBlockElement::GetList(
            array('SORT' => 'ASC'),
            array('PROPERTY_CML2_LINK' => $ids),
            false,
            false,
            array('ID', 'IBLOCK_ID', 'PROPERTY_CML2_LINK')
        );
        while ($element = $elements->Fetch()) {
            $offers_to_product[$element['ID']] = $element['PROPERTY_CML2_LINK_VALUE'];
            $offers_ids[] = $element['ID'];

        }

        if (empty($offers_ids))
            return $prices;

        $db_res = \CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $offers_ids,
                'CURRENCY' => CurrencyManager::getBaseCurrency()
            ),
            false,
            false,
            array('PRODUCT_ID', 'PRICE')
        );
        while ($ar_res = $db_res->Fetch()) {
            $prices[$offers_to_product[$ar_res['PRODUCT_ID']]][] = $ar_res['PRICE'];
        }
        foreach ($prices as $id => $price) {
            $prices[$id] = min($price);
        }
        return $prices;
    }

    public function getItemsByIds(array $ids)
    {
        $prices = $this->getOffersPrices($ids);

        $elements = $this->getList(
            array('SORT' => 'ASC'),
            array('ID' => $ids),
            false,
            false,
            array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_MORE_PHOTO', 'PREVIEW_PICTURE')
        );
        /**
         * @var StubProduct[] $elements
         */
        foreach ($elements as $element) {
            $curr_fields = $element->getFields();
            $fields = array(
                'PRICE' => $prices[$element->getId()],
                'CURRENCY' => CurrencyManager::getBaseCurrency(),
                'PICTURE_SRC' => $curr_fields['PREVIEW_PICTURE'] > 0 ? \CFile::GetPath($curr_fields['PREVIEW_PICTURE']) : \CFile::GetPath($curr_fields['PROPERTY_MORE_PHOTO_VALUE']),
            );
            $element->mergeFields($fields);
        }
        if (!empty($elements)) {
            return $elements;
        }
        return [];
    }
}
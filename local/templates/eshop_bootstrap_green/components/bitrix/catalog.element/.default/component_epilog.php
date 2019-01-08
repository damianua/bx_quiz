<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;

	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

if (isset($templateData['JS_OBJ']))
{
	?>
	<script>
		BX.ready(BX.defer(function(){
			if (!!window.<?=$templateData['JS_OBJ']?>)
			{
				window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
			}
		}));
	</script>

	<?
	// check compared state
	if ($arParams['DISPLAY_COMPARE'])
	{
		$compared = false;
		$comparedIds = array();
		$item = $templateData['ITEM'];

		if (!empty($_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]))
		{
			if (!empty($item['JS_OFFERS']))
			{
				foreach ($item['JS_OFFERS'] as $key => $offer)
				{
					if (array_key_exists($offer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
					{
						if ($key == $item['OFFERS_SELECTED'])
						{
							$compared = true;
						}

						$comparedIds[] = $offer['ID'];
					}
				}
			}
			elseif (array_key_exists($item['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
			{
				$compared = true;
			}
		}

		if ($templateData['JS_OBJ'])
		{
			?>
			<script>
				BX.ready(BX.defer(function(){
					if (!!window.<?=$templateData['JS_OBJ']?>)
					{
						window.<?=$templateData['JS_OBJ']?>.setCompared('<?=$compared?>');

						<? if (!empty($comparedIds)): ?>
						window.<?=$templateData['JS_OBJ']?>.setCompareInfo(<?=CUtil::PhpToJSObject($comparedIds, false, true)?>);
						<? endif ?>
					}
				}));
			</script>
			<?
		}
	}

	// select target offer
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$offerNum = false;
	$offerId = (int)$this->request->get('OFFER_ID');
	$offerCode = $this->request->get('OFFER_CODE');

	if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
	{
		$offerNum = array_search($offerId, $templateData['OFFER_IDS']);
	}
	elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
	{
		$offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
	}

	if (!empty($offerNum))
	{
		?>
		<script>
			BX.ready(function(){
				if (!!window.<?=$templateData['JS_OBJ']?>)
				{
					window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
				}
			});
		</script>
		<?
	}
}

// component epilog
function RECENT_VIEWED_CACHE ($IBLOCK_ID, $ID){
    ob_start();

    $GLOBALS["APPLICATION"]->IncludeComponent(
        "aniart:catalog.recent_viewed",
        ".default",
        array(
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "DETAIL_URL" => "",
            "IBLOCK_ID" => $IBLOCK_ID,
            "IBLOCK_TYPE" => "catalog",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "PAGE_ELEMENT_COUNT" => "5",
            "SECTION_ELEMENT_ID" => $ID,
            "SHOW_FROM_SECTION" => "N",
            "SHOW_IMAGE" => "Y",
            "SHOW_NAME" => "Y",
            "COMPONENT_TEMPLATE" => ".default"
        )
    );

    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;
}

$OutPut= preg_replace(
    "/#RECENT_VIEWED#/is",
    RECENT_VIEWED_CACHE($arParams['IBLOCK_ID'], $arResult["ID"]),
    $arResult["CACHED_TPL"]
);
echo $OutPut;
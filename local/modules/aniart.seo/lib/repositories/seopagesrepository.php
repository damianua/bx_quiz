<?php


namespace Aniart\Seo\Repositories;

use \Aniart\Main\Repositories\AbstractHLBlockElementsRepository;
use Bitrix\Highloadblock\HighloadBlockTable;
use Aniart\Seo\SmartSeo\HlSettings;

class SeoPagesRepository extends AbstractHLBlockElementsRepository
{

    public function __construct($hlblock_id)
    {
        if(!static::$entities[$hlblock_id]){
            $hlblock = HighloadBlockTable::getById($hlblock_id)->fetch();
            if(!$hlblock)
            {
                $hlblock = HighloadBlockTable::getList(array(
                        'filter' => array(
                            'TABLE_NAME' => HlSettings::getHLTableName(),
                    ))
                )->fetch();

                if($hlblock) {

                    $hlblock_id = $hlblock['ID'];

                } else {

                    $hlblock_id = HlSettings::createHLBlockFromSettings();
                    $hlblock = HighloadBlockTable::getById($hlblock_id)->fetch();
                    
                }

            }

            static::$entities[$hlblock_id] = HighloadBlockTable::compileEntity($hlblock)->getDataClass();
            $this->hlblock_id = $hlblock_id;
        }
    }

    public function newInstance(array $fields)
	{
		return app('SeoPage', array($fields));
	}
}
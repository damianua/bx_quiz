<?

namespace Aniart\Seo\SmartSeo;

use Aniart\Seo\SmartSeo\Interfaces\HlSettingsInterface;
use Bitrix\Highloadblock\HighloadBlockTable;

class HlSettings implements HlSettingsInterface {

    protected static $errors = [];

    protected static $hlEntityName = 'AniartSEOHL';
    protected static $hlTableName  = 'aniart_seo_hl';
    protected static $hlFieldsData = [
        'UF_PAGE' => [
            'FIELD_NAME' => 'UF_PAGE',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_PAGE',
            'SORT' => '100',
            'MULTIPLE' => NULL,
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => "Y",
            'EDIT_IN_LIST' => NULL,
            'IS_SEARCHABLE' => "Y",
            'SETTINGS' => array(
                'DEFAULT_VALUE' => '/',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            "EDIT_FORM_LABEL" => [
                "ru" => "UF_PAGE",
                "en" => "UF_PAGE",
            ],
            "LIST_COLUMN_LABEL" => [
                "ru" => "UF_PAGE",
                "en" => "UF_PAGE",
            ],
            "LIST_FILTER_LABEL" => [
                "ru" => "UF_PAGE",
                "en" => "UF_PAGE",
            ],
        ],
        'UF_PAGE_TITLE' => [
            'FIELD_NAME' => 'UF_PAGE_TITLE',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_PAGE_TITLE',
            'SORT' => '100',
            'MULTIPLE' => NULL,
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => "Y",
            'EDIT_IN_LIST' => NULL,
            'IS_SEARCHABLE' => "Y",
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 'UF_PAGE_TITLE example',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            "EDIT_FORM_LABEL" => [
                "ru" => "UF_PAGE_TITLE",
                "en" => "UF_PAGE_TITLE",
            ],
            "LIST_COLUMN_LABEL" => [
                "ru" => "UF_PAGE_TITLE",
                "en" => "UF_PAGE_TITLE",
            ],
            "LIST_FILTER_LABEL" => [
                "ru" => "UF_PAGE_TITLE",
                "en" => "UF_PAGE_TITLE",
            ],
        ],
        'UF_SORT' => [
            'FIELD_NAME' => 'UF_SORT',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_SORT',
            'SORT' => '100',
            'MULTIPLE' => NULL,
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => "Y",
            'EDIT_IN_LIST' => NULL,
            'IS_SEARCHABLE' => "Y",
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 'UF_SORT example',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            "EDIT_FORM_LABEL" => [
                "ru" => "UF_SORT",
                "en" => "UF_SORT",
            ],
            "LIST_COLUMN_LABEL" => [
                "ru" => "UF_SORT",
                "en" => "UF_SORT",
            ],
            "LIST_FILTER_LABEL" => [
                "ru" => "UF_SORT",
                "en" => "UF_SORT",
            ],
        ],
        'UF_META_TITLE' => [
            'FIELD_NAME' => 'UF_META_TITLE',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_META_TITLE',
            'SORT' => '100',
            'MULTIPLE' => NULL,
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => "Y",
            'EDIT_IN_LIST' => NULL,
            'IS_SEARCHABLE' => "Y",
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 'UF_META_TITLE example',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            "EDIT_FORM_LABEL" => [
                "ru" => "UF_META_TITLE",
                "en" => "UF_META_TITLE",
            ],
            "LIST_COLUMN_LABEL" => [
                "ru" => "UF_META_TITLE",
                "en" => "UF_META_TITLE",
            ],
            "LIST_FILTER_LABEL" => [
                "ru" => "UF_META_TITLE",
                "en" => "UF_META_TITLE",
            ],
        ],
        'UF_KEYWORDS' => [
            'FIELD_NAME' => 'UF_KEYWORDS',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_KEYWORDS',
            'SORT' => '100',
            'MULTIPLE' => NULL,
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => "Y",
            'EDIT_IN_LIST' => NULL,
            'IS_SEARCHABLE' => "Y",
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 'UF_KEYWORDS example',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            "EDIT_FORM_LABEL" => [
                "ru" => "UF_KEYWORDS",
                "en" => "UF_KEYWORDS",
            ],
            "LIST_COLUMN_LABEL" => [
                "ru" => "UF_KEYWORDS",
                "en" => "UF_KEYWORDS",
            ],
            "LIST_FILTER_LABEL" => [
                "ru" => "UF_KEYWORDS",
                "en" => "UF_KEYWORDS",
            ],
        ],
        'UF_DESCRIPTION' => [
            'FIELD_NAME' => 'UF_DESCRIPTION',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_DESCRIPTION',
            'SORT' => '100',
            'MULTIPLE' => NULL,
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => "Y",
            'EDIT_IN_LIST' => NULL,
            'IS_SEARCHABLE' => "Y",
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 'UF_DESCRIPTION example',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            "EDIT_FORM_LABEL" => [
                "ru" => "UF_DESCRIPTION",
                "en" => "UF_DESCRIPTION",
            ],
            "LIST_COLUMN_LABEL" => [
                "ru" => "UF_DESCRIPTION",
                "en" => "UF_DESCRIPTION",
            ],
            "LIST_FILTER_LABEL" => [
                "ru" => "UF_DESCRIPTION",
                "en" => "UF_DESCRIPTION",
            ],
        ],
    ];

    /**
     * @return string
     */
    public static function getHLTableName() {
        return self::$hlTableName;
    }

    /**
     * @return integer
     */
    public static function createHLBlockFromSettings() {

        $hlBlockResult = HighloadBlockTable::add([
            'NAME'       => self::$hlEntityName,
            'TABLE_NAME' => self::$hlTableName
        ]);

        if(!$hlBlockResult->isSuccess()) {
            self::$errors[] = $hlBlockResult->getErrorMessages();
            return false;
        }

        $hlBlockId = $hlBlockResult->getId();

        self::addFields($hlBlockId, self::$hlFieldsData);

        if(!count(self::$errors)) {
            return false;
        }

        return $hlBlockId;
    }

    /**
     * @param $hlBlockId integer
     * @param $hlFieldsData array
     * @return boolean
     */
    public static function addFields($hlBlockId, $hlFieldsData) {

        self::$errors = [];

        foreach ($hlFieldsData as $hlFieldData)
        {
            self::addField($hlBlockId, $hlFieldData);
        }
    }

    /**
     * @param $hlBlockId integer
     * @param $hlFieldData array
     * @return boolean
     */
    public static function addField($hlBlockId, $hlFieldData) {
        
        $userField  = new \CUserTypeEntity;

        $hlFieldData['ENTITY_ID'] = 'HLBLOCK_' . $hlBlockId;

        $userFieldId = $userField->Add($hlFieldData);

        if(!$userFieldId) {
            global $APPLICATION;
            self::$errors = $APPLICATION->GetException();
        }
    }

    /**
     * @return array
     */
    public static function getErrors() {
        return self::$errors;
    }

    /**
     * @return string
     */
    public static function getLastError() {
        $lastError = '';

        if(count(self::$errors))
            $lastError = array_pop(self::$errors);

        return $lastError;
    }
}
?>
<?php


namespace Aniart\Seo\Multilang\Models;

use Aniart\Main\Multilang\Models\IblockElementModelML as MainIblockElementModelML;
use Aniart\Seo\Multilang\Interfaces\SeoParamsMLInterface;
use Aniart\Seo\Multilang\MultiLangSeoParamsTrait;

class IblockElementModelML extends MainIblockElementModelML implements SeoParamsMLInterface
{
    use MultiLangSeoParamsTrait;
    /**
     * @var I18n
     */
    protected $i18n;

    public function __construct(array $fields = array(), I18n $i18n = null)
    {
        parent::__construct($fields);
        $this->i18n = $i18n ?: app()->make('I18n');
    }

    public function getName($lang = null)
    {
        return $this->i18n('NAME', $lang);
    }

    public function i18n($code, $lang = null)
    {
        $lang = $lang ?: $this->i18n->getCurrentLang()->code;
        if(!$this->i18n->isLangDefault($lang)){
            $code.= '_'.strtoupper($lang);
        }
        if(!($message = $this->fields[$code])){
            $message = $this->getPropertyValue($code);
        }
        return $this->normalizeMultiLangMessage($message);
    }

    protected function normalizeMultiLangMessage($message)
    {
        if(is_array($message)){
            if(isset($message['TYPE'])){
                $message = (($message['TYPE'] == 'html') ? html_entity_decode($message['TEXT']) : $message['TEXT']);
            }
        }
        return $message;
    }


}
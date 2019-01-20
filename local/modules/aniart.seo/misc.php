<?php
/**
 *  Еще некоторые узконаправленные функции, которые работают в рамках конкретного проекта
 */

function seo($paramName = null, $paramValue = null, $overwrite = false)
{
    /**
     * @var \Aniart\Main\Seo\SeoParamsCollector $seo;
     */
    $seo = app('SeoParamsCollector');
    if(!is_null($paramName)){
        if(is_null($paramValue)){
            return $seo->getParamValue($paramName);
        }
        else{
            return $seo->setParamsValue($paramName, $paramValue, $overwrite);
        }
    }
    return $seo;
}

<?php


namespace Aniart\Seo\Multilang\Interfaces;


use Aniart\Seo\Interfaces\SeoParamsInterface;

interface SeoParamsMLInterface extends SeoParamsInterface
{
    public function setSeoLang($lang);
    public function getSeoLang();
}
<?php

namespace Aniart\Main\Seo\Interfaces;


interface SeoParamsInterface
{
    public function getPageTitle();
    public function getMetaTitle();
    public function getKeywords();
    public function getDescription();
}
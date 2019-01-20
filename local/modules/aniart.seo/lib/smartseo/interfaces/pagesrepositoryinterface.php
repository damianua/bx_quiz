<?php 

namespace Aniart\Seo\SmartSeo\Interfaces;

interface PagesRepositoryInterface
{
	public function getByCurrentUri();
	public function getByUri($uri);
}
<?php 

namespace Aniart\Seo\SmartSeo\Interfaces;

interface ParserInterface
{
	public function __construct($data = null);
	public function setData($data);
	public function parse();
}
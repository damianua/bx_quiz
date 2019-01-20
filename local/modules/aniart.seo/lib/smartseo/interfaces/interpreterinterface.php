<?php 

namespace Aniart\Seo\SmartSeo\Interfaces;

use Aniart\Seo\SmartSeo\Expressions\AbstractExpression;
use Aniart\Seo\SmartSeo\Page;

interface InterpreterInterface
{
	public function setPage(Page $page);
	public function interpret(AbstractExpression $expression);
}
?>
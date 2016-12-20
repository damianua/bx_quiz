<?php 
namespace Aniart\Seo\SmartSeo\Expressions;

use Aniart\Seo\SmartSeo;

class VariableExpression extends TerminalExpression
{
	protected  $value;
	
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}
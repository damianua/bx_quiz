<?php


namespace Aniart\Main\Interfaces;


interface ProductInterface
{
	public function getId();
	public function getPreviewPicture();
	public function getName();
	public function getPrice($format = false);
	public function getDetailPageUrl();
}
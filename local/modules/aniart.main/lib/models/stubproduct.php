<?php


namespace Aniart\Main\Models;


use Aniart\Main\Interfaces\ProductInterface;

class StubProduct implements ProductInterface
{
    protected $fields = [];

    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    public function getId() {
        $defaultId = 0;
        $id = $this->getByKey('ID');
        return ($id) ?: $defaultId;
    }

    public function getPreviewPicture()
	{
        $defaultPreviewPicture = '/upload/iblock/0cb/0cbcdd686c12b9217dee4c3367cec4a9.jpg';
        $previewPicture = $this->getByKey('PREVIEW_PICTURE');
        if(empty($previewPicture))
            $previewPicture = $this->getByKey('DETAIL_PICTURE');
        return ($previewPicture) ? \CFile::GetPath($previewPicture) : $defaultPreviewPicture;

	}

	public function getName()
	{
        $defaultName = 'Товар ' . randString(8);
        $name = $this->getByKey('NAME');
        return ($name) ?: $defaultName;
	}

	public function getPrice($format = false)
	{
		$price = rand(1, 1000);
		return $format ? CurrencyFormat($price, 'RUB') : $price;
	}

	public function getDetailPageUrl()
	{
        $defaultDetailPageUrl = '/catalog/pants/pants-flower-glade/';
        $detailPageUrl = $this->getByKey('DETAIL_PAGE_URL');
        return ($detailPageUrl) ?: $defaultDetailPageUrl;
	}

	protected function getByKey($key) {
        if(isset($this->fields[$key]))
            return $this->fields[$key];
    }
}
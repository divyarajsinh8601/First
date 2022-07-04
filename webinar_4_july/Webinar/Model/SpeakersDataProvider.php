<?php
namespace HelenOfTroy\Webinar\Model;

use HelenOfTroy\Webinar\Model\ResourceModel\Speakers\CollectionFactory;
use HelenOfTroy\Webinar\Helper\General;

/**
 * Class SpeakersDataProvider
 * @package HelenOfTroy\Webinar\Model
 */
class SpeakersDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider {

    /**
     * @var $loadedData
     */
    protected $loadedData;

    /**
     * @var $collection
     */
	protected $collection;

    /**
     * @var $generalHelper
     */
	protected $generalHelper;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $speakersCollectionFactory
     * @param General $generalHelper
     * @param array $meta
     * @param array $data
     */
	public function __construct(
		$name,
		$primaryFieldName,
		$requestFieldName,
		CollectionFactory $speakersCollectionFactory,
		General $generalHelper,
		array $meta = [],
		array $data = []
	) {
		$this->collection = $speakersCollectionFactory->create();
		$this->generalHelper = $generalHelper;
		parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
	}

    /**
     * @return mixed
     */
    public function getData() {
		$items = $this->collection->getItems();
		foreach ($items as $model) {
			$this->loadedData[$model->getId()] = $model->getData();
			if ($model->getImage()) {
				$m['image'][0]['name'] = $model->getImage();
				$m['image'][0]['url'] = $this->generalHelper->getMediaUrl($model->getImage());
				$fullData = $this->loadedData;
				$this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
			}
		}

		return $this->loadedData;
	}

}

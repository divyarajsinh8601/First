<?php
namespace HelenOfTroy\Webinar\Model;

use HelenOfTroy\Webinar\Model\ResourceModel\Webinars\CollectionFactory;
use HelenOfTroy\Webinar\Helper\General;

/**
 * Class DataProvider
 * @package HelenOfTroy\Webinar\Model
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider {

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
     * @param CollectionFactory $webinarCollectionFactory
     * @param General $generalHelper
     * @param array $meta
     * @param array $data
     */
	public function __construct(
		$name,
		$primaryFieldName,
		$requestFieldName,
		CollectionFactory $webinarCollectionFactory,
		General $generalHelper,
		array $meta = [],
		array $data = []
	) {
		$this->collection = $webinarCollectionFactory->create();
		$this->generalHelper = $generalHelper;
		parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
	}

    /**
     * @return mixed
     */
    public function getData() {
		$items = $this->collection->getItems();
		foreach ($items as $model) {
            $model->setAttendeeNotification(json_decode($model->getAttendeeNotification(),true));
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

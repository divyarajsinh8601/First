<?php
namespace HelenOfTroy\Webinar\Model;

use HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers\CollectionFactory;

/**
 * Class SpecificSpeakersDataProvider
 * @package HelenOfTroy\Webinar\Model
 */
class SpecificSpeakersDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider {

    /**
     * @var $loadedData
     */
    protected $loadedData;

    /**
     * @var $collection
     */
	protected $collection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
	protected $_storeManager;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $specificSpeakersCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
	public function __construct(
		$name,
		$primaryFieldName,
		$requestFieldName,
		CollectionFactory $specificSpeakersCollectionFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		array $meta = [],
		array $data = []
	) {
		$this->collection = $specificSpeakersCollectionFactory->create();
		$this->_storeManager = $storeManager;
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
				$m['image'][0]['url'] = $this->getMediaUrl() . $model->getImage();
				$fullData = $this->loadedData;
				$this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
			}
		}

		return $this->loadedData;
	}

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
	public function getMediaUrl() {
		$mediaUrl = $this->_storeManager->getStore()
			->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'HelenOfTroy/Webinar/Speakers/';
		return $mediaUrl;
	}

}

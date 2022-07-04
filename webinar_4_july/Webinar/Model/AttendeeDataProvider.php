<?php
namespace HelenOfTroy\Webinar\Model;

use HelenOfTroy\Webinar\Model\ResourceModel\Attendees\CollectionFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class DataProvider
 * @package HelenOfTroy\Webinar\Model
 */
class AttendeeDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider {

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
     * @param CollectionFactory $attendeesCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
	public function __construct(
		$name,
		$primaryFieldName,
		$requestFieldName,
		CollectionFactory $attendeesCollectionFactory,
		SessionManagerInterface $session,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		array $meta = [],
		array $data = []
	) {
		$this->collection = $attendeesCollectionFactory->create();
		$this->session = $session;
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
		}

		return $this->loadedData;
	}

}

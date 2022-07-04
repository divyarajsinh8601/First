<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers\Grid;

use HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers\Collection as SpecificSpeakersCollection;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document as SpecificSpeakersModel;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\App\ObjectManager;
/**
 * Class Collection
 * @package HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers\Grid
 */
class Collection extends SpecificSpeakersCollection implements \Magento\Framework\Api\Search\SearchResultInterface
{
    /**
     * @var
     */
    protected $aggregations;

    // @codingStandardsIgnoreStart

    /**
     * Collection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param $mainTable
     * @param $eventPrefix
     * @param $eventObject
     * @param $resourceModel
     * @param string $model
     * @param null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = SpecificSpeakersModel::class,
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
        $this->session = ObjectManager::getInstance()->get(SessionManagerInterface::class);
    }
    // @codingStandardsIgnoreEnd

    /**
     * @return mixed
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param $aggregations
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * @return null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * @param $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * @param array|null $items
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function _beforeLoad()
    {
        parent::_beforeLoad();
        $this->addFieldToFilter('webinar_id', $this->session->getWebinarId());
        return $this;
    }
}

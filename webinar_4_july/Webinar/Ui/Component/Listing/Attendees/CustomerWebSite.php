<?php

namespace HelenOfTroy\Webinar\Ui\Component\Listing\Attendees;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Model\CustomerFactory;

/**
 * Class CustomerWebSite
 * @package HelenOfTroy\Webinar\Ui\Component\Listing\Column
 */
class CustomerWebSite extends Column {

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Action constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param CustomerFactory $customerFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		UrlInterface $urlBuilder,
		CustomerFactory $customerFactory,
		\Magento\Store\Model\Website $websiteModel,
		array $components = [],
		array $data = []
	) {
		$this->urlBuilder = $urlBuilder;
		$this->customerFactory = $customerFactory;
		$this->_websiteModel = $websiteModel;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
		if (isset($dataSource['data']['items'])) {
			foreach ($dataSource['data']['items'] as &$item) {
    		    $customerData = $this->customerFactory->create();
                $customerName = $customerData->load($item['customer_id']);
                $websiteName = $this->_websiteModel->load($customerName->getWebsiteId());
                $item[$this->getData('name')] = $websiteName->getName();
			}
		}
		return $dataSource;
	}
}

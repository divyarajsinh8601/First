<?php

namespace HelenOfTroy\Webinar\Ui\Component\Listing\Attendees;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Model\CustomerFactory;

/**
 * Class CustomerName
 * @package HelenOfTroy\Webinar\Ui\Component\Listing\Column
 */
class CustomerEmail extends Column {

	const URL_CUSTOMER_EDIT = 'customer/index/edit/id';

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
		array $components = [],
		array $data = []
	) {
		$this->urlBuilder = $urlBuilder;
		$this->customerFactory = $customerFactory;
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
                $name[$customerName->getId()] = $customerName->getEmail();
                $item[$this->getData('name')] = html_entity_decode(
                    '<a target="_blank" href="'.$this->urlBuilder->getUrl(self::URL_CUSTOMER_EDIT,
                             ['id' => $item['customer_id']]).'">'.$customerName->getEmail().'</a>');
			}

		}
		return $dataSource;
	}
}

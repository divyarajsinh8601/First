<?php
namespace HelenOfTroy\Webinar\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column {
	const NAME = 'thumbnail';

	const ALT_FIELD = 'name';

	/**
	 * @var string
	 */
	private $editUrl;

	private $_objectManager = null;
	public $_storeManager;

	/**
	 * @param ContextInterface $context
	 * @param UiComponentFactory $uiComponentFactory
	 * @param \Magento\Framework\UrlInterface $urlBuilder
	 * @param array $components
	 * @param array $data
	 */
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		\Magento\Framework\UrlInterface $urlBuilder,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		array $components = [],
		array $data = [],
		      \Magento\Store\Model\StoreManagerInterface $storeManager
	) {
		parent::__construct($context, $uiComponentFactory, $components, $data);
		$this->urlBuilder = $urlBuilder;
		$this->_objectManager = $objectManager;
          $this->_storeManager=$storeManager;
	}

	/**
	 * Prepare Data Source
	 *
	 * @param array $dataSource
	 * @return array
	 */
	public function prepareDataSource(array $dataSource) {
		if (isset($dataSource['data']['items'])) {
			$fieldName = $this->getData('name');
			foreach ($dataSource['data']['items'] as &$item) {

				if ($item['image']) {

					$filename = 'pub/media/faq/tmp/icon/' . $item['image'];

					$item[$fieldName . '_src'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB) . $filename;
					$item[$fieldName . '_alt'] = $this->getAlt($item) ?: $filename;
					$item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
						'webinars/pages/form',
						['id' => $item['id']]);
					$item[$fieldName . '_orig_src'] = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB) . $filename;
				} else {

				}
			}
		}

		return $dataSource;
	}

	/**
	 * @param array $row
	 *
	 * @return null|string
	 */
	protected function getAlt($row) {
		$altField = $this->getData('config/altField') ?: self::ALT_FIELD;
		return isset($row[$altField]) ? $row[$altField] : null;
	}
}

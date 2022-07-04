<?php

namespace HelenOfTroy\Webinar\Ui\Component\Listing\SpecificSpeakers;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Action
 * @package HelenOfTroy\Webinar\Ui\Component\Listing\SpecificSpeakers
 */
class Action extends Column {

    const URL_PATH_DELETE = 'webinars/specificspeaker/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Action constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		UrlInterface $urlBuilder,
		array $components = [],
		array $data = []
	) {
		$this->urlBuilder = $urlBuilder;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
		if (isset($dataSource['data']['items'])) {
			foreach ($dataSource['data']['items'] as &$item) {
				if (isset($item['id'])) {
					$item[$this->getData('name')] = [
						'delete' => [
							'href' => $this->urlBuilder->getUrl(
								static::URL_PATH_DELETE,
								[
									'id' => $item['id'],
								]
							),
							'label' => __('Delete'),
							'confirm' => [
								'title' => __('Delete "Record"'),
								'message' => __('Are you sure you wan\'t to delete a "This" record?'),
							],
						],
					];
				}
			}
		}

		return $dataSource;
	}
}

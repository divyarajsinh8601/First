<?php

namespace HelenOfTroy\Webinar\Block\Adminhtml\Speakers\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Cms\Api\PageRepositoryInterface;

/**
 * Class GenericButton
 * @package HelenOfTroy\Webinar\Block\Adminhtml\Speakers\Edit
 */
class GenericButton {

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
		Context $context,
		PageRepositoryInterface $pageRepository
	) {
		$this->context = $context;
		$this->pageRepository = $pageRepository;
	}

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
	public function getUrl($route = '', $params = []) {
		return $this->context->getUrlBuilder()->getUrl($route, $params);
	}
}

<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class Question extends Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	) {
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute() {
		$resultPage = $this->resultPageFactory->create();
		return $resultPage;
	}
}

<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class Form
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class Edit extends Action {

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
		PageFactory $resultPageFactory,
		SessionManagerInterface $session
	) {
		parent::__construct($context);
		$this->session = $session;
		$this->resultPageFactory = $resultPageFactory;
	}

    /**
     * @return \Magento\Framework\View\Result\Page
     */
	public function execute() {
		$resultPage = $this->resultPageFactory->create();
		$webinarId = $this->getRequest()->getParam('id');
		if ($webinarId) {
			$this->session->setWebinarId($webinarId);
		}
		$resultPage->getConfig()->getTitle()->prepend(__('Edit Webinar'));
		return $resultPage;
	}
}

<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;
use HelenOfTroy\Webinar\Model\WebinarsFactory;

/**
 * Class View
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class View extends Action {

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
		SessionManagerInterface $session,
        WebinarsFactory $webinarsFactory,
        \Magento\Framework\Url $urlHelper
	) {
		parent::__construct($context);
		$this->session = $session;
		$this->resultPageFactory = $resultPageFactory;
		$this->webinarsFactory = $webinarsFactory;
		$this->urlHelper = $urlHelper;
	}

    /**
     * @return \Magento\Framework\View\Result\Page
     */
	public function execute() {
		$resultPageFactory = $this->resultRedirectFactory->create();
		$webinarId = $this->getRequest()->getParam('id');
		$model = $this->webinarsFactory->create();
		$model->load($webinarId);
		return $resultPageFactory->setPath($this->urlHelper->getUrl('webinars/'.$model->getUrlKey()));
	}
}

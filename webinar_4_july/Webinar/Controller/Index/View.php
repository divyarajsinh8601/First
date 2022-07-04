<?php

namespace HelenOfTroy\Webinar\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use HelenOfTroy\Webinar\Helper\General;
use Magento\Framework\UrlInterface;
use HelenOfTroy\Webinar\Model\WebinarsFactory;
use Magento\Framework\Url\EncoderInterface;

class View extends Action {

	protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        General $generalHelper,
        UrlInterface $urlInterface,
        WebinarsFactory $webinarsFactory,
        EncoderInterface $encoder
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->generalHelper = $generalHelper;
        $this->urlInterface = $urlInterface;
        $this->webinarsFactory = $webinarsFactory;
        $this->encoder = $encoder;
    }
    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $webinarId = $this->getRequest()->getParam('id');
        $webinarData = $this->webinarsFactory->create()->load($webinarId);
        $state = $webinarData->getState();
        $paramState = $this->getRequest()->getParam('state');
        if ($paramState) {
            $state = $paramState;
        }
        if ($webinarId && $state != 'Pre-Webinar' && !$this->generalHelper->getIsLogin()) {
            if ($state == 'Live-Webinar' || $state == 'Post-Webinar') {
                $this->messageManager->addNoticeMessage(__("This page requires login to access. Please login first."));
                $currentUrl = $this->urlInterface->getCurrentUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
                $currentUrl = $this->encoder->encode($currentUrl);
                return $resultRedirectFactory->setPath('customer/account/login', ['referer' => $currentUrl]);
            }
            return $resultPage;
        }
        return $resultPage;
    }
}

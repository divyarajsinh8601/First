<?php

namespace HelenOfTroy\Webinar\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use HelenOfTroy\Webinar\Helper\General;
use Magento\Framework\UrlInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Index extends Action implements IdentityInterface
{
    const CACHE_TAG = 'webinar_page_block';

	protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        General $generalHelper,
        UrlInterface $urlInterface,
        EncoderInterface $encoder
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->generalHelper = $generalHelper;
        $this->urlInterface = $urlInterface;
        $this->encoder = $encoder;
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG];
    }
    
    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        $resultPage = $this->resultPageFactory->create();
        if (!$this->generalHelper->getIsLogin()) {
            $this->messageManager->addNoticeMessage(__("This page requires login to access. Please login first."));
            $currentUrl = $this->urlInterface->getCurrentUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
            $currentUrl = $this->encoder->encode($currentUrl);
            return $resultRedirectFactory->setPath('customer/account/login', ['referer' => $currentUrl]);
        }
        return $resultPage;
    }
}

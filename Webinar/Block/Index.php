<?php

namespace HelenOfTroy\Webinar\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Cms\Model\PageFactory;
use HelenOfTroy\Webinar\Helper\General;

/**
 * Class Index
 * @package HelenOfTroy\Webinar\Block
 */
class Index extends Template
{
    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param General $generalHelper
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        General $generalHelper
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->generalHelper = $generalHelper;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Webinars'));
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'home', [
                'label'=>__('Home'),
                'title'=>__('Go to Home Page'),
                'link'=>$this->getBaseUrl()
            ]
        );
        $breadcrumbs->addCrumb(
            'webinars', [
                'label'=>__('Webinars'),
            ]
        );

        return $this;
    }

    public function getCustomerId()
    {
        return $this->generalHelper->getCurrentCustomerId();
    }

    public function getMediaUrl($name) {
        return $this->generalHelper->getMediaUrl($name);
    }
}

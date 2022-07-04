<?php

namespace HelenOfTroy\Webinar\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use HelenOfTroy\Webinar\Model\WebinarsFactory;
use Magento\Cms\Model\PageFactory;
use HelenOfTroy\Webinar\Helper\General;
use Magento\Cms\Model\Template\FilterProvider;
use HelenOfTroy\Webinar\Model\ResourceModel\Attendees\CollectionFactory;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class View
 * @package HelenOfTroy\Webinar\Block
 */
class View extends Template implements IdentityInterface
{
    const CACHE_TAG = 'webinar_page_block';

    /**
     * Index constructor.
     * @param Context $context
     * @param WebinarsFactory $webinarsFactory
     * @param PageFactory $pageFactory
     * @param FilterProvider $filterProvider
     * @param General $generalHelper
     */
    public function __construct(
        Context $context,
        WebinarsFactory $webinarsFactory,
        PageFactory $pageFactory,
        FilterProvider $filterProvider,
        General $generalHelper,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->webinarsFactory = $webinarsFactory;
        $this->pageFactory = $pageFactory;
        $this->filterProvider = $filterProvider;
        $this->generalHelper = $generalHelper;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        $webinarTag = self::CACHE_TAG.'_'.$this->getRequest()->getParam('id');
        return [$webinarTag];
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $webinarData = $this->getWebinarData();
        $this->pageConfig->getTitle()->set(__($webinarData->getTitle()));
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
                'title'=>__('Webinars'),
                'link'=>$this->getUrl('webinars')
            ]
        );
        $breadcrumbs->addCrumb(
            $webinarData->getUrlKey(), [
                'label'=>__($webinarData->getTitle())
            ]
        );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentId()
    {
        return $this->getRequest()->getParam('id');
    }

    /**
     * @return mixed
     */
    public function getWebinarData()
    {
        return $this->webinarsFactory->create()->load($this->getCurrentId());
    }

    /**
     * @return mixed
     */
    public function getCmsContent()
    {
        $pageType = $this->getCmsPage();
        $cmsPage = $this->pageFactory->create();
        $cmsPage = $cmsPage->load($pageType, 'identifier');
        $cmsContent = $cmsPage->getContent() ? $this->filterProvider->getPageFilter()->filter($cmsPage->getContent()) : '';
        $registerUrl = $this->getRegisterUrl();
        $content = str_replace("{{WEBINAR_REGISTER_URL}}", $registerUrl, $cmsContent);
        return $content;
    }

    public function getParamState()
    {
        $state = $this->getRequest()->getParam('state');
        if ($state && ($state == 'Pre-Webinar' || $state == 'Post-Webinar' || $state == 'Live-Webinar')) {
            return $state;
        }
        return '';
    }

    public function getState()
    {
        $webinarData = $this->getWebinarData();
        $state = $webinarData->getState();
        $paramState = $this->getParamState();
        if ($paramState) {
            $state = $paramState;
        }
        return $state;
    }

    public function getCmsPage()
    {
        $webinarData = $this->getWebinarData();
        $state = $this->getState();
        if ($state == 'Pre-Webinar') {
            $page = $webinarData->getCmsPagePreWebinar();
        } elseif ($state == 'Live-Webinar') {
            $page = $webinarData->getCmsPageLiveWebinar();
        } else {
            $page = $webinarData->getCmsPagePostWebinar();
        }
        return $page;
    }

    public function getCustomerId()
    {
        return $this->generalHelper->getCurrentCustomerId();
    }

    public function isCurrentCustomerEnrolled($webinarId)
    {
        $customerId = $this->getCustomerId();
        $collection = $this->collectionFactory->create()
                        ->addFieldToFilter('webinar_id', $webinarId)
                        ->addFieldToFilter('customer_id', $customerId);
        return count($collection) ? true : false;
    }

    public function getRegisterUrl()
    {
        return $this->getUrl('*/register/', ['webinar_id' => $this->getCurrentId()]);
    }

    public function getQuestionUrl()
    {
        return $this->getUrl('*/*/addQuestion', ['webinar_id' => $this->getCurrentId()]);
    }
}

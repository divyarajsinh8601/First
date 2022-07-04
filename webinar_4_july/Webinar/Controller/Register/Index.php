<?php

namespace HelenOfTroy\Webinar\Controller\Register;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use HelenOfTroy\Webinar\Model\AttendeesFactory;
use HelenOfTroy\Webinar\Model\ResourceModel\Attendees\CollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Model\Session;
use HelenOfTroy\Webinar\Model\WebinarsFactory;
use HelenOfTroy\Webinar\Helper\SendMail;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use HelenOfTroy\Webinar\Helper\General;
use Magento\Framework\Url\EncoderInterface;

/**
 * Class Index
 * @package HelenOfTroy\Webinar\Controller\Register
 */
class Index extends Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AttendeesFactory $attendeesFactory
     * @param CollectionFactory $collection
     * @param UrlInterface $UrlInterface
     * @param SessionFactory $sessionFactory
     * @param Session $session
     * @param WebinarsFactory $webinarsFactory
     * @param SendMail $sendMail
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param General $generalHelper
     * @param EncoderInterface $encoder
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        AttendeesFactory $attendeesFactory,
        CollectionFactory $collection,
        UrlInterface $UrlInterface,
        SessionFactory $sessionFactory,
        Session $session,
        WebinarsFactory $webinarsFactory,
        SendMail $sendMail,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        General $generalHelper,
        EncoderInterface $encoder
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->attendeesFactory = $attendeesFactory;
        $this->collection = $collection;
        $this->UrlInterface = $UrlInterface;
        $this->sessionFactory = $sessionFactory;
        $this->session = $session;
        $this->webinarsFactory = $webinarsFactory;
        $this->sendMail = $sendMail;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->generalHelper = $generalHelper;
        $this->encoder = $encoder;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        $webinarId = $this->getRequest()->getParam('webinar_id');
        $customer = $this->sessionFactory->create()->getCustomer();
        $customerId = $this->sessionFactory->create()->getCustomer()->getId();
        $store = $this->storeManager->getStore()->getCode();
        $webinarData = $this->webinarsFactory->create()->load($webinarId);
        $urlKey = $webinarData->getUrlKey();
        $collection = $this->collection->create()
                        ->addFieldToFilter('webinar_id', $webinarId)
                        ->addFieldToFilter('customer_id', $customerId);
        $successUrl = rtrim($this->UrlInterface->getUrl('webinars/'.$urlKey.'?enrolled=true'), '/');
        $errorUrl = rtrim($this->UrlInterface->getUrl('webinars/'.$urlKey.'?enrolled=false'), '/');

        if (!count($collection)) {
            try {
                if ($customerId) {
                    $model = $this->attendeesFactory->create();
                    $model->setWebinarId($webinarId);
                    $model->setCustomerId($customerId);
                    $model->save();
                    $customerStoreId = $customer->getStoreId();
                    $attendeeNotification = json_decode($webinarData->getAttendeeNotification(),true);
                    $imageUrl = '';
                    if ($webinarData->getImage()) {
                        $imageUrl = $this->generalHelper->getMediaUrl($webinarData->getImage());
                    }
                    $customerData = ([
                        'customer_name' => $customer->getName(),
                        'customer_email' => $customer->getEmail(),
                        'customer_id' => $customerId,
                        'webinar_id' => $webinarId,
                        'title' => $webinarData->getTitle(),
                        'webinar_date' => $webinarData->getDate(),
                        'webinar_status' => $webinarData->getState(),
                        'webinar_url' => $this->UrlInterface->getUrl('webinars/'.$urlKey),
                        'store_id' => $customerStoreId,
                        'image_url' => $imageUrl
                    ]);

                    if(is_array($attendeeNotification)) {
                        foreach ($attendeeNotification as $seperateNotification) {
                            if (isset($seperateNotification['attendee_notification_template']) && isset($seperateNotification['store'])) {
                                $modelStoreId = $seperateNotification['store'];
                                if (($seperateNotification['title']) != '') {
                                    $customerData['title'] = $seperateNotification['title'];
                                } else {
                                    $customerData['title'] = $webinarData['title'];
                                }
                                $attendeeEmailTemplate = $seperateNotification['attendee_notification_template'];
                                if ($modelStoreId == $customerStoreId) {
                                    $this->sendMail->sendMail($attendeeEmailTemplate, $customerData, $customer->getEmail());
                                    break;
                                }
                            }
                        }
                    }
                    $adminEmailTemplate = $webinarData->getAdminNotification();
                    $emailSeperation = str_replace(' ', '', $this->sendMail->adminEmailSelector());
                    $adminEmailAddress =  explode(',',$emailSeperation);
                    if($adminEmailTemplate) {
                        $this->sendMail->sendMail($adminEmailTemplate,$customerData,$adminEmailAddress);
                    }
                    $this->messageManager->addSuccessMessage(__("Thank you for registering to the webinar."));
                    return $resultRedirectFactory->setUrl($successUrl);
                } else {
                    $this->messageManager->addErrorMessage(__("Login is required to register for a webinar."));
                    $currentUrl = $this->UrlInterface->getCurrentUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
                    $this->session->setWebinarRegister($currentUrl);
                    $currentUrl = $this->encoder->encode($currentUrl);
                    return $resultRedirectFactory->setPath('customer/account/login', ['referer' => $currentUrl]);
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__("Something went wrong, Please try again."));
                return $resultRedirectFactory->setUrl($errorUrl);
            }
        } else {
            $this->messageManager->addErrorMessage(__("You have already registered to this webinar."));
            return $resultRedirectFactory->setUrl($errorUrl);
        }
        return $resultRedirectFactory->setUrl($errorUrl);
    }
}

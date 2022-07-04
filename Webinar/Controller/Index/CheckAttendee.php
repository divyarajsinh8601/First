<?php

namespace HelenOfTroy\Webinar\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use HelenOfTroy\Webinar\Helper\General;
use HelenOfTroy\Webinar\Model\ResourceModel\Attendees\CollectionFactory as AttendeeCollection;

class CheckAttendee extends Action
{
    protected $resultPageFactory;
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        JsonFactory $resultJsonFactory,
        General $generalHelper,
        AttendeeCollection $attendeeCollection
    )
    {
        $this->resultPageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->generalHelper = $generalHelper;
        $this->attendeeCollection = $attendeeCollection;
        parent::__construct($context);
    }
    public function execute()
    {
        $data['registered'] = false;
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $customerId = $this->generalHelper->getCurrentCustomerId();
        if ($customerId) {
            $webinarId = $this->getRequest()->getParam('webinarId');
            $collection = $this->attendeeCollection->create()
                        ->addFieldToFilter('webinar_id', $webinarId)
                        ->addFieldToFilter('customer_id', $customerId);
            if (count($collection)) {
                $data['registered'] = true;
            }
        }
        $result->setData($data);
        return $result;
    }
}

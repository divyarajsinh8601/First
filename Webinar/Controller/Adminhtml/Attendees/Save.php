<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Attendees;

use HelenOfTroy\Webinar\Model\AttendeesFactory;
use HelenOfTroy\Webinar\Model\ResourceModel\Attendees\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class Save
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Attendees
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param JsonFactory $resultJsonFactory
     * @param AttendeesFactory $attendeesFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param SessionManagerInterface $session
     * @param CollectionFactory $collection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Customer\Model\Customer $customer
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JsonFactory $resultJsonFactory,
        AttendeesFactory $attendeesFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        SessionManagerInterface $session,
        CollectionFactory $collection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Customer\Model\Customer $customer
    ) {
        parent::__construct($context);
        $this->attendeesFactory = $attendeesFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
        $this->collection = $collection;
        $this->storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->customer = $customer;
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $error = false;
        $message = '';
        $data = $this->getRequest()->getPostValue();
        try {
            $webinarId = $this->session->getWebinarId();
            if ($webinarId && isset($data['customer_id'])) {
                $customerId = $data['customer_id'];
                $collection = $this->collection->create()
                                ->addFieldToFilter('webinar_id', $webinarId)
                                ->addFieldToFilter('customer_id', $customerId);
                if (!count($collection)) {
                    $model = $this->attendeesFactory->create();
                    $model->setWebinarId($webinarId);
                    $model->setCustomerId($customerId);
                    $model->save();
                } else {
                    $error = 'true';
                    $message = __('Customer already exist.');
                }
            } else {
                $error = true;
                $message = __('Unable to detect webinar id.');
            }
        } catch (LocalizedException $e) {
            $error = true;
            $message = __($e->getMessage());
        } catch (\Exception $e) {
            $error = true;
            $message = __('We can\'t save data right now.');
        }
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(
            [
                'messages' => $message,
                'error' => $error,
                'data' => $data,
            ]
        );
        return $resultJson;
    }
}

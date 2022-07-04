<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Questions;

use HelenOfTroy\Webinar\Model\QuestionsFactory;
use HelenOfTroy\Webinar\Model\ResourceModel\Questions\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class Save
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Questions
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param JsonFactory $resultJsonFactory
     * @param QuestionsFactory $questionsFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param SessionManagerInterface $session
     * @param CollectionFactory $collection
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JsonFactory $resultJsonFactory,
        QuestionsFactory $questionsFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        SessionManagerInterface $session,
        CollectionFactory $collection
    ) {
        parent::__construct($context);
        $this->questionsFactory = $questionsFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
        $this->collection = $collection;
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
                $question = $data['question'];
                $collection = $this->collection->create();
                    $model = $this->questionsFactory->create();
                    $model->setWebinarId($webinarId);
                    $model->setCustomerId($customerId);
                    $model->setQuestion($question);
                    $model->save();
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

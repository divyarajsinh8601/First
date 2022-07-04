<?php

namespace HelenOfTroy\Webinar\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use HelenOfTroy\Webinar\Helper\General;
use HelenOfTroy\Webinar\Model\QuestionsFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Url\EncoderInterface;

class AddQuestion extends Action {

	protected $resultPageFactory;
	protected $generalHelper;
    protected $questionsFactory;
    protected $redirect;
    protected $formKeyValidator;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        General $generalHelper,
        QuestionsFactory $questionsFactory,
        RedirectInterface $redirect,
        Validator $formKeyValidator,
        EncoderInterface $encoder
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->generalHelper = $generalHelper;
        $this->questionsFactory = $questionsFactory;
        $this->redirect = $redirect;
        $this->formKeyValidator = $formKeyValidator;
        $this->encoder = $encoder;
    }
    public function execute()
    {
        $resultRedirectFactory = $this->resultRedirectFactory->create();
        $refererUrl = $this->redirect->getRefererUrl();
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__("Form key is Invalidate"));
            return $resultRedirectFactory->setUrl($refererUrl);
        }
        $data = $this->getRequest()->getParams();
        $data['customer_id'] = $this->generalHelper->getCurrentCustomerId();
        if (isset($data['customer_id'])) {
            try {
                $model = $this->questionsFactory->create();
                $model->setData($data)->save();
                $this->messageManager->addSuccessMessage(__("Question added succesfully."));
                return $resultRedirectFactory->setUrl($refererUrl);
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__("Something went wrong, Please try again."));
                return $resultRedirectFactory->setUrl($refererUrl);
            }
        } else {
            $this->messageManager->addErrorMessage(__("Login require for ask question in webinar."));
            $currentUrl = $this->encoder->encode($refererUrl);
            return $resultRedirectFactory->setPath('customer/account/login', ['referer' => $currentUrl]);
        }
    }
}

<?php
namespace HelenOfTroy\Webinar\Controller\Adminhtml\Questions;

use HelenOfTroy\Webinar\Model\QuestionsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class Delete
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Index
 */
class Delete extends Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var questionsFactory
     */
    protected $questionsFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param QuestionsFactory $questionsFactory
     */
    public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
		QuestionsFactory $questionsFactory,
        SessionManagerInterface $session
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->questionsFactory = $questionsFactory;
		$this->session = $session;
		parent::__construct($context);
	}

    /**
     * @return mixed
     */
    public function execute() {
		try {
			$id = $this->getRequest()->getParam('id');
			if ($id) {
				$model = $this->questionsFactory->create()->load($id);
				if ($model->getData()) {
					$model->delete();
					$this->messageManager->addSuccessMessage(__("Attendees Delete Successfully."));
				} else {
					$this->messageManager->addError(__('Id is not valid.'));
				}

			} else {
				$this->messageManager->addError(__('Something is Wrong!, Please Try again'));
			}

		} catch (\Exception $e) {
			$this->messageManager->addErrorMessage($e, __("We can\'t delete record, Please try again."));
		}
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setPath('webinars/pages/edit', ['id' => $this->session->getWebinarId()]);
		return $resultRedirect;

	}
}

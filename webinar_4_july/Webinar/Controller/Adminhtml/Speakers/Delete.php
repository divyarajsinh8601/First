<?php
namespace HelenOfTroy\Webinar\Controller\Adminhtml\Speakers;

use HelenOfTroy\Webinar\Model\SpeakersFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

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
     * @var SpeakersFactory
     */
    protected $speakersFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param SpeakersFactory $speakersFactory
     */
    public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
		SpeakersFactory $speakersFactory
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->speakersFactory = $speakersFactory;
		parent::__construct($context);
	}

    /**
     * @return mixed
     */
    public function execute() {
		try {
			$id = $this->getRequest()->getParam('id');
			if ($id) {
				$model = $this->speakersFactory->create()->load($id);
				if ($model->getData()) {
					$model->delete();
					$this->messageManager->addSuccessMessage(__("Speaker Delete Successfully."));
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
		$resultRedirect->setPath('webinars/speakers/index');
		return $resultRedirect;

	}
}

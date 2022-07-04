<?php
namespace HelenOfTroy\Webinar\Controller\Adminhtml\SpecificSpeaker;

use HelenOfTroy\Webinar\Model\SpecificSpeakersFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class Delete
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\SpecificSpeaker
 */
class Delete extends Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var specificSpeakersFactory
     */
    protected $specificSpeakersFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param SpecificSpeakersFactory $specificSpeakersFactory
     */
    public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
		SpecificSpeakersFactory $specificSpeakersFactory,
        SessionManagerInterface $session
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->specificSpeakersFactory = $specificSpeakersFactory;
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
				$model = $this->specificSpeakersFactory->create()->load($id);

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
		$resultRedirect->setPath('webinars/pages/edit', ['id' => $this->session->getWebinarId()]);
		return $resultRedirect;

	}
}

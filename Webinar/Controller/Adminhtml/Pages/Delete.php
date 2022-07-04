<?php
namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use HelenOfTroy\Webinar\Model\WebinarsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;

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
     * @var
     */
    protected $contactFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param WebinarsFactory $webinarsFactory
     */
    public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
		WebinarsFactory $webinarsFactory,
        UrlRewriteCollectionFactory $urlCollectionFactory
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->webinarsFactory = $webinarsFactory;
        $this->urlCollectionFactory = $urlCollectionFactory;
		parent::__construct($context);
	}

    /**
     * @return mixed
     */
    public function execute() {
		try {
			$id = $this->getRequest()->getParam('id');
			if ($id) {
				$model = $this->webinarsFactory->create()->load($id);
				if ($model->getData()) {
					$targetPath = 'webinars/index/view/id/'.$model->getId();
			        $collection = $this->urlCollectionFactory->create();
            		$collection->addFieldToFilter('entity_type', 'custom')
                       	       ->addFieldToFilter('target_path', $targetPath)
                       	       ->walk('delete');
					$model->delete();
					$this->messageManager->addSuccessMessage(__("Webinar Delete Successfully."));
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
		$resultRedirect->setPath('webinars/pages/index');
		return $resultRedirect;

	}
}

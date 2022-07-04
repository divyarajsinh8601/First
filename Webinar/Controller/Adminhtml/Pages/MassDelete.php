<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use HelenOfTroy\Webinar\Model\Webinars as Contact;
use HelenOfTroy\Webinar\Model\ResourceModel\Webinars\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;

/**
 * Class MassDelete
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class MassDelete extends \Magento\Backend\App\Action {
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Contact $contactModel
     * @param UrlRewriteCollectionFactory $urlCollectionFactory
     */
    public function __construct(
		Context $context,
		Filter $filter,
		CollectionFactory $collectionFactory,
		Contact $contactModel,
        UrlRewriteCollectionFactory $urlCollectionFactory
	) {
		$this->filter = $filter;
		$this->collectionFactory = $collectionFactory;
		$this->contactModel = $contactModel;
        $this->urlCollectionFactory = $urlCollectionFactory;
		parent::__construct($context);
	}

    /**
     * @return mixed
     */
    public function execute() {
		$jobData = $this->collectionFactory->create();

		foreach ($jobData as $value) {
			$templateId[] = $value['id'];
		}
		$parameterData = $this->getRequest()->getParams('id');
		$selectedAppsid = $this->getRequest()->getParams('id');
		if (array_key_exists("selected", $parameterData)) {
			$selectedAppsid = $parameterData['selected'];
		}
		if (array_key_exists("excluded", $parameterData)) {
			if ($parameterData['excluded'] == 'false') {
				$selectedAppsid = $templateId;
			} else {
				$selectedAppsid = array_diff($templateId, $parameterData['excluded']);
			}
		}
		$collection = $this->collectionFactory->create();
		$collection->addFieldToFilter('id', ['in' => $selectedAppsid]);
		$delete = 0;
		$model = [];
		foreach ($collection as $item) {
			$this->deleteById($item->getId());
			$delete++;
		}
		$this->messageManager->addSuccess(__('A total of %1 Records have been deleted.', $delete));
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		return $resultRedirect->setPath('webinars/pages/index');
	}

	/**
	 * [deleteById description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function deleteById($id) {
		$targetPath = 'webinars/index/view/id/'.$id;
        $collection = $this->urlCollectionFactory->create();
		$collection->addFieldToFilter('entity_type', 'custom')
           	       ->addFieldToFilter('target_path', $targetPath)
           	       ->walk('delete');
		$item = $this->contactModel->load($id);
		$item->delete();
	}
}

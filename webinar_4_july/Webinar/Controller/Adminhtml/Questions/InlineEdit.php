<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Questions;

use HelenOfTroy\Webinar\Model\Questions;
use HelenOfTroy\Webinar\Model\ResourceModel\Questions\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class InlineEdit
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Questions
 */
class InlineEdit extends Action {
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var Questions
     */
    protected $model;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Questions $model
     * @param CollectionFactory $collectionFactory
     * @param SessionManagerInterface $session
     */
    public function __construct(
		Context $context,
		JsonFactory $jsonFactory,
		Questions $model,
		CollectionFactory $collectionFactory,
		SessionManagerInterface $session
	) {
		parent::__construct($context);
		$this->jsonFactory = $jsonFactory;
		$this->model = $model;
		$this->session = $session;
		$this->collectionFactory = $collectionFactory;
	}

    /**
     * @return mixed
     */
    public function execute() {
		$resultJson = $this->jsonFactory->create();
		$error = false;
		$messages = [];
		if ($this->getRequest()->getParam('isAjax')) {
			$postItems = $this->getRequest()->getParam('items', []);

			if (empty($postItems)) {
				$messages[] = __('Please correct the data sent.');
				$error = true;
			} else {
				$webinarId = $this->session->getWebinarId();
				foreach ($postItems as $item) {
					$collection = $this->collectionFactory->create();
						$modelData = $this->model->load($item['id']);
						try {
							$modelData->setData(array_merge($modelData->getData(), $item));
							$modelData->save();
						} catch (\Exception $e) {
							$messages[] = "[Error:]  {$e->getMessage()}";
							$error = true;
						}
				}
			}
		}

		return $resultJson->setData([
			'messages' => $messages,
			'error' => $error,
		]);
	}
}

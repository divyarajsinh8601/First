<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use HelenOfTroy\Webinar\Model\Webinars;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class InlineEdit
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class InlineEdit extends Action {
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var Webinars
     */
    protected $model;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Webinars $model
     */
    public function __construct(
		Context $context,
		JsonFactory $jsonFactory,
		Webinars $model
	) {
		parent::__construct($context);
		$this->jsonFactory = $jsonFactory;
		$this->model = $model;
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
				foreach (array_keys($postItems) as $Id) {
					$modelData = $this->model->load($Id);
					try {
						$modelData->setData(array_merge($modelData->getData(), $postItems[$Id]));
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

<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\SpecificSpeaker;

use HelenOfTroy\Webinar\Model\SpecificSpeakers;
use HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class InlineEdit
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\SpecificSpeakers
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
     * @param SpecificSpeakers $model
     * @param CollectionFactory $collectionFactory
     * @param SessionManagerInterface $session
     */
    public function __construct(
		Context $context,
		JsonFactory $jsonFactory,
		SpecificSpeakers $model,
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
					$collection = $this->collectionFactory->create()
									->addFieldToFilter('webinar_id', $webinarId)
									->addFieldToFilter('speaker_id', $item['speaker_id']);
					if (!count($collection)) {
						$modelData = $this->model->load($item['id']);
						try {
							$modelData->setData(array_merge($modelData->getData(), $item));
							$modelData->save();
						} catch (\Exception $e) {
							$messages[] = "[Error:]  {$e->getMessage()}";
							$error = true;
						}
					} else {
						$messages[] = __('Speaker already exist.');
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

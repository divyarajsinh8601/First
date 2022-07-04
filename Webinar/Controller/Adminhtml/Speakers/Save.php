<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Speakers;

use HelenOfTroy\Webinar\Model\SpeakersFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use HelenOfTroy\Webinar\Model\ImageUploader;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Cache\Manager;

/**
 * Class Save
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Speakers
 */
class Save extends \Magento\Backend\App\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var SpeakersFactory
     */
    protected $speakersFactory;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;
    /**
     * @var TypeListInterface
     */
    protected $cacheManager;

    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param WebinarsFactory $webinarsFactory
     * @param ManagerInterface $messageManager
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        SpeakersFactory $speakersFactory,
        ImageUploader $imageUploaderModel,
        ManagerInterface $messageManager,
        Manager $cacheManager
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->speakersFactory = $speakersFactory;
        $this->imageUploaderModel = $imageUploaderModel;
        $this->messageManager = $messageManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return mixed
     */
    public function execute()
    {

        try {
            $resultPageFactory = $this->resultRedirectFactory->create();
            $data = $this->getRequest()->getPostValue();
            $model = $this->speakersFactory->create();
            $model->setData($data);
            $model = $this->imageData($model, $data);
            $model->save();
            $this->messageManager->addSuccessMessage(__("Data Saved Successfully."));
            if ($model->getId()) {
                    return $resultPageFactory->setPath('webinars/speakers/form', ['id' => $model->getId()]);
            }
            $buttondata = $this->getRequest()->getParam('back');
			if ($buttondata == 'new') {
			    return $resultPageFactory->setPath('webinars/speakers/form');
			}
			if ($buttondata == 'close') {
				$this->_redirect('webinars/speakers/index');
			}
            $id = $model->getId();
            return $resultPageFactory->setPath('webinars/speakers/edit', ['id' => $id]);
		} catch (\Exception $e) {
			$this->_messageManager->addErrorMessage(__($e));
		}
        return $resultPageFactory->setPath('webinars/speakers/index');
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function imageData($model, $data)
    {
        if ($model->getId()) {
            $pageData = $this->speakersFactory->create();
            $pageData->load($model->getId());
            if (isset($data['image'][0]['name'])) {
                $Imagename1 = $pageData->getThumbnail();
                $Imagename2 = $data['image'][0]['name'];
                if ($Imagename1 != $Imagename2) {
                    $imageUrl = $data['image'][0]['url'];
                    $imageName = $data['image'][0]['name'];
                    $data['image'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
                } else {
                    $data['image'] = $data['image'][0]['name'];
                }
            } else {
                $data['image'] = '';
            }
        } else {
            if (isset($data['image'][0]['name'])) {
                $imageUrl = $data['image'][0]['url'];
                $imageName = $data['image'][0]['name'];
                $data['image'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
            }
        }
        $model->setData($data);
        return $model;
    }
}

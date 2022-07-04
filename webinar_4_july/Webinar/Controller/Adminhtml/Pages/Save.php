<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use HelenOfTroy\Webinar\Model\WebinarsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use HelenOfTroy\Webinar\Model\ImageUploader;
use Magento\Framework\Message\ManagerInterface;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\PageCache\Model\Cache\Type as PageCache;
use Magento\PageCache\Model\Config as CacheConfig;

/**
 * Class Save
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class Save extends \Magento\Backend\App\Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var WebinarsFactory
     */
    protected $webinarsFactory;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;
    
    /**
     *
     * @var PageCache
     */
    private $pageCache;

    /**
     * @var CacheConfig
     */
    private CacheConfig $cacheConfig;

    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param WebinarsFactory $webinarsFactory
     * @param ManagerInterface $messageManager
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param CollectionFactory $collectionFactory
     * @param PageCache $pageCache
     * @param CacheConfig $cacheConfig
     */
    public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
		WebinarsFactory $webinarsFactory,
        ImageUploader $imageUploaderModel,
		ManagerInterface $messageManager,
        UrlRewriteFactory $urlRewriteFactory,
        StoreRepositoryInterface $storeRepository,
        UrlRewriteCollectionFactory $collectionFactory,
        PageCache $pageCache,
        CacheConfig $cacheConfig
        
	) {
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
		$this->webinarsFactory = $webinarsFactory;
		$this->messageManager = $messageManager;
        $this->imageUploaderModel = $imageUploaderModel;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeRepository = $storeRepository;
        $this->collectionFactory = $collectionFactory;
        $this->pageCache = $pageCache;
        $this->cacheConfig = $cacheConfig;
	}

    /**
     * @return mixed
     */
    public function execute() {

		try {
			$resultPageFactory = $this->resultRedirectFactory->create();
			$data = $this->getRequest()->getPostValue();
            if (!isset($data['cms_page_pre_webinar'])) {
                $data['cms_page_pre_webinar'] = '';
            }
            if (!isset($data['cms_page_live_webinar'])) {
                $data['cms_page_live_webinar'] = '';
            }
            if (!isset($data['cms_page_post_webinar'])) {
                $data['cms_page_post_webinar'] = '';
            }
            if (!isset($data['admin_notification'])) {
                $data['admin_notification'] = '';
            }
            if(isset($data['attendee_notification'])) {
                $data['attendee_notification'] = json_encode($data['attendee_notification']);
            }else {
                $data['attendee_notification'] = '';
            }

			$model = $this->webinarsFactory->create();
			$model->setData($data);
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('entity_type', 'custom')
                       ->addFieldToFilter('request_path', 'webinars/'.$model->getUrlKey());
            if ($model->getId()) {
                $oldData = $this->webinarsFactory->create()->load($model->getId());
                if($model->getState() != $oldData->getState()) {
                    $this->cleanCache('webinar_page_block_' . $model->getId());
                }
                $collection->addFieldToFilter('target_path', ['neq' => 'webinars/index/view/id/'.$model->getId()]);
            }else{
                $this->cleanCache('webinar_page_block');
            }
            if (count($collection)) {
                $this->messageManager->addErrorMessage(__('Url key already exist.'));
                if ($model->getId()) {
                    return $resultPageFactory->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultPageFactory->setPath('*/*/form');
            } else {
                $model = $this->imageData($model, $data);
                $model->save();
                $this->urlRewrite($model->getId(), $model->getUrlKey());
            }

			$this->messageManager->addSuccessMessage(__("Data Saved Successfully."));
			$buttondata = $this->getRequest()->getParam('back');
			if ($buttondata == 'new') {
                return $resultPageFactory->setPath('*/*/form');
            }
            if ($buttondata == 'close') {
                return $resultPageFactory->setPath('*/*/index');
            }
            $id = $model->getId();
            return $resultPageFactory->setPath('*/*/edit', ['id' => $id]);
		} catch (\Exception $e) {
		}
		return $resultPageFactory->setPath('webinars/pages/index');
	}

    public function cleanCache($tag){
        if ($this->cacheConfig->getType() == CacheConfig::BUILT_IN && $this->cacheConfig->isEnabled()) {
            $this->pageCache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, [$tag]);
        }
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function imageData($model, $data)
    {
        if ($model->getId()) {
            $pageData = $this->webinarsFactory->create();
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

    /**
     * @param $id
     * @param $key
     */
    public function urlRewrite($id, $key)
    {
        $targetPath = 'webinars/index/view/id/'.$id;
        $collection = $this->collectionFactory->create()
                           ->addFieldToFilter('entity_type', 'custom')
                           ->addFieldToFilter('target_path', $targetPath);
        if (count($collection)) {
            foreach ($collection as $urlData) {
                $urlData->setRequestPath('webinars/'.$key)->save();
            }
        } else {
            foreach ($this->getStoreIds() as $storeId) {
                $urlRewriteModel = $this->urlRewriteFactory->create();
                $urlRewriteModel->setIsSystem(0)
                                ->setEntityType('custom')
                                ->setTargetPath($targetPath)
                                ->setRequestPath('webinars/'.$key)
                                ->setStoreId($storeId)
                                ->save();
            }
        }
    }

    /**
     * @return array
     */
    public function getStoreIds()
    {
        $storeIds = [];
        $stores = $this->storeRepository->getList();
        foreach ($stores as $store) {
            $storeIds[] = $store->getId();
        }
        return array_diff($storeIds, [0]);
    }
}

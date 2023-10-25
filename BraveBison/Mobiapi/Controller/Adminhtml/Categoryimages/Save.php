<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Categoryimages;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use BraveBison\Mobiapi\Model\Categoryimages;
use BraveBison\Mobiapi\Model\ImageUploader;


class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Categoryimages
     */
    protected $categoryimages;

    /**
     * @var Session
     */
    protected $adminsession;

    protected $categoryFactory;

    /**
     * @param Action\Context $context
     * @param Categoryimages    $categoryimages
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        Categoryimages $categoryimages,
        Session $adminsession,
        ImageUploader $imageUploader,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        parent::__construct($context);
        $this->categoryimages = $categoryimages;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
        $this->categoryFactory = $categoryFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            try {
                $categoryImagesId = isset($data['id']) ? $data['id'] : null;

                // Get the category name
                $categoryName = $this->getCategoryName($data['category_id']);
//var_dump($categoryName);die('er');
                if ($categoryImagesId) {
                    $categoryimages = $this->categoryimages->load($categoryImagesId);
                    if (!$categoryimages->getId()) {
                        $this->messageManager->addErrorMessage(__('This category image no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    $categoryimages = $this->categoryimages;
                }

                $selectedStoreIds = implode(',', $data['store_id']);
                $data['store_id'] = $selectedStoreIds;

                // Set the category name in the data
                $data['category_name'] = $categoryName;

                // Set the category name in the data
                $data['category_name'] = $categoryName;

                $categoryimages->setData($data);
                $categoryimages = $this->processImages($categoryimages, $data);


                $categoryimages->save();

                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    }
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/add');
        }

        return $resultRedirect->setPath('*/*/add');
    }

    protected function processImages($model, $data)
    {
        if ($model->getId()) {
            $existingData = $this->categoryimages->load($model->getId());
            // Process icon image
            if (isset($data['icon'][0]['name']) && $existingData->getIcon() != $data['icon'][0]['name']) {
                $iconImageUrl = $data['icon'][0]['url'];
                $iconImageName = $data['icon'][0]['name'];
                $data['icon'] = $this->imageUploader->saveMediaImage($iconImageName, $iconImageUrl);
            } else {
                $data['icon'] = $existingData->getIcon();
            }

            // Process banner image
            if (isset($data['banner'][0]['name']) && $existingData->getBanner() != $data['banner'][0]['name']) {
                $bannerImageUrl = $data['banner'][0]['url'];
                $bannerImageName = $data['banner'][0]['name'];
                $data['banner'] = $this->imageUploader->saveMediaImage($bannerImageName, $bannerImageUrl);
            } else {
                $data['banner'] = $existingData->getBanner();
            }

            // Process small banner image
            if (isset($data['smallbanner'][0]['name']) && $existingData->getSmallbanner() != $data['smallbanner'][0]['name']) {
                $smallBannerImageUrl = $data['smallbanner'][0]['url'];
                $smallBannerImageName = $data['smallbanner'][0]['name'];
                $data['smallbanner'] = $this->imageUploader->saveMediaImage($smallBannerImageName, $smallBannerImageUrl);
            } else {
                $data['smallbanner'] = $existingData->getSmallbanner();
            }
        } else {
            // Process icon image
            if (isset($data['icon'][0]['name'])) {
                $iconImageUrl = $data['icon'][0]['url'];
                $iconImageName = $data['icon'][0]['name'];
                $data['icon'] = $this->imageUploader->saveMediaImage($iconImageName, $iconImageUrl);
            }

            // Process banner image
            if (isset($data['banner'][0]['name'])) {
                $bannerImageUrl = $data['banner'][0]['url'];
                $bannerImageName = $data['banner'][0]['name'];
                $data['banner'] = $this->imageUploader->saveMediaImage($bannerImageName, $bannerImageUrl);
            }

            // Process small banner image
            if (isset($data['smallbanner'][0]['name'])) {
                $smallBannerImageUrl = $data['smallbanner'][0]['url'];
                $smallBannerImageName = $data['smallbanner'][0]['name'];
                $data['smallbanner'] = $this->imageUploader->saveMediaImage($smallBannerImageName, $smallBannerImageUrl);
            }
        }

        $model->setData($data);
        return $model;
    }

    protected function getCategoryName($categoryId)
    {
        $category = $this->categoryFactory->create()->load($categoryId);
        return $category->getName();
    }


    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::bannerimage");
    }
}

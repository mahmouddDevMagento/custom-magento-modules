<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Bannerimage;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use BraveBison\Mobiapi\Model\Bannerimage;
use BraveBison\Mobiapi\Model\ImageUploader;


class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Bannerimage
     */
    protected $bannerimage;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Bannerimage    $bannerimage
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        Bannerimage $bannerimage,
        Session $adminsession,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->bannerimage = $bannerimage;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            try {
                $bannerId = isset($data['id']) ? $data['id'] : null;
//                var_dump($data);die('ddd');
                if ($bannerId) {
                    // Edit existing banner image
                    $banner = $this->bannerimage->load($bannerId);
                    if (!$banner->getId()) {
                        $this->messageManager->addErrorMessage(__('This banner image no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    // Create new banner image
                    $banner = $this->bannerimage;
                }

                $selectedStoreIds = implode(',', $data['store_id']);
                $data['store_id'] = $selectedStoreIds;
                $banner->setData($data);
                $banner = $this->image($banner, $data);
//                var_dump($data);die('wwwwwwwwwwww');

                $banner->save();
//                var_dump($bannerId);die('2');

                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
//                var_dump($bannerId);die('qqq');

                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    }
                }
                // Redirect to grid after successful creation/edit
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

    public function image($model, $data)
    {
        if ($model->getId()) {
            $pageData = $this->bannerimage;
            $pageData->load($model->getId());
//            var_dump($pageData->getImage());
//            var_dump('dddd'.$data['image'][0]['name']);
//            die('page');
            if (isset($data['image'][0]['name'])) {
                $imageName1 = $pageData->getImage();
                $imageName2 = $data['image'][0]['name'];
                if ($imageName1 != $imageName2) {
                    $imageUrl = $data['image'][0]['url'];
                    $imageName = $data['image'][0]['name'];
                    $data['image'] = $this->imageUploader->saveMediaImage($imageName, $imageUrl);
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
                $data['image'] = $this->imageUploader->saveMediaImage($imageName, $imageUrl);
            }
        }
        $model->setData($data);
        return $model;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::bannerimage");
    }
}

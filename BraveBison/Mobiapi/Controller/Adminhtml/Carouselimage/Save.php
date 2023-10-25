<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Carouselimage;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use BraveBison\Mobiapi\Model\Carouselimage;
use BraveBison\Mobiapi\Model\ImageUploader;


class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Carouselimage
     */
    protected $carouselimage;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Carouselimage    $carouselimage
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        Carouselimage $carouselimage,
        Session $adminsession,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->carouselimage = $carouselimage;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            try {
                $carouselImageId = isset($data['id']) ? $data['id'] : null;
//                var_dump($data);die('ddd');
                if ($carouselImageId) {
                    // Edit existing carousel image
                    $carouselImag = $this->carouselimage->load($carouselImageId);
                    if (!$carouselImag->getId()) {
                        $this->messageManager->addErrorMessage(__('This carousel image no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    // Create new carousel image
                    $carouselImag = $this->carouselimage;
                }
                $carouselImag->setData($data);
                $carouselImag = $this->processImage($carouselImag, $data);
//                var_dump($data);die('wwwwwwwwwwww');

                $carouselImag->save();
//                var_dump($carouselImageId);die('2');

                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
//                var_dump($carouselImageId);die('qqq');

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

//    public function processImage($model, $data)
//    {
//        if ($model->getId()) {
//            $pageData = $this->carouselimage;
//            $pageData->load($model->getId());
//
//            if (isset($data['image'][0]['name'])) {
//                $imageName1 = $pageData->getImage();
//                $imageName2 = $data['image'][0]['name'];
//                if ($imageName1 != $imageName2) {
//                    $imageUrl = $data['image'][0]['url'];
//                    $imageName = $data['image'][0]['name'];
//                    $data['image'] = $this->imageUploader->saveMediaImage($imageName, $imageUrl);
//                } else {
//                    $data['image'] = $data['image'][0]['name'];
//                }
//            } else {
//                $data['image'] = '';
//            }
//        } else {
//            if (isset($data['image'][0]['name'])) {
//                $imageUrl = $data['image'][0]['url'];
//                $imageName = $data['image'][0]['name'];
//                $data['image'] = $this->imageUploader->saveMediaImage($imageName, $imageUrl);
//            }
//        }
//        $model->setData($data);
//        return $model;
//    }
    public function processImage($model, $data)
    {
        if ($model->getId()) {
            $pageData = $this->carouselimage;
            $pageData->load($model->getId());

            if (isset($data['image'][0]['name'])) {
                $imageName1 = $pageData->getImage();
                $imageName2 = $data['image'][0]['name'];
                if ($imageName1 != $imageName2) {
                    $imageUrl = $data['image'][0]['url'];
                    $imageName = $data['image'][0]['name'];
                    $fullImagePath = $this->imageUploader->saveMediaImage($imageName, $imageUrl);
                    $data['image'] = $fullImagePath;
                } else {
                    $data['image'] = $imageName1;
                }
            } else {
                $data['image'] = '';
            }
        } else {
            if (isset($data['image'][0]['name'])) {
                $imageUrl = $data['image'][0]['url'];
                $imageName = $data['image'][0]['name'];
                $fullImagePath = $this->imageUploader->saveMediaImage($imageName, $imageUrl);
                $data['image'] = $fullImagePath;
            }
        }
        $model->setData($data);
        return $model;
    }


    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::carouselimage");
    }
}

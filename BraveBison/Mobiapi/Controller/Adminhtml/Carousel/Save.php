<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Carousel;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use BraveBison\Mobiapi\Model\Carousel;
use BraveBison\Mobiapi\Model\ImageUploader;

class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Carousel
     */
    protected $carousel;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Carousel    $carousel
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        Carousel $carousel,
        Session $adminsession,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->carousel = $carousel;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
    }


    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::carousel");
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            
            try {
                $carouselId = isset($data['id']) ? $data['id'] : null;
                if ($carouselId) {
                    $carousel = $this->carousel->load($carouselId);
                    if (!$carousel->getId()) {
                        $this->messageManager->addErrorMessage(__('This carousel no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    $carousel = $this->carousel;
                }

                if (isset($data['store_id']) && is_array($data['store_id'])) {
                    $data['store_id'] = implode(',', $data['store_id']);
                }

                $carouselType = isset($data['type']) ? $data['type'] : null;
//                if ($carouselType === 'image' && isset($data['mobiapi_carousel']['product_ids'])) {
//                    throw new \Magento\Framework\Exception\LocalizedException(
//                        __('You cannot select both image and product items for the carousel.')
//                    );
//                } elseif ($carouselType === 'product' && isset($data['mobiapi_carousel']['image_ids'])) {
//                    throw new \Magento\Framework\Exception\LocalizedException(
//                        __('You cannot select both image and product items for the carousel.')
//                    );
//                }

                if ($carouselType === 'image') {
                    if (!isset($data['mobiapi_carousel']['image_ids']) || empty($data['mobiapi_carousel']['image_ids'])) {
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('You must select at least one image for the carousel.')
                        );
                    }

                    $data['image_ids'] = $data['mobiapi_carousel']['image_ids'];

                    $data['product_ids'] = '';
                    $carousel->setProductIds(null);
                } elseif ($carouselType === 'product') {
                    if (!isset($data['mobiapi_carousel']["'product_ids'"]) || empty($data['mobiapi_carousel']["'product_ids'"])) {

                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('You must select at least one product for the carousel.')
                        );
                    }
                    $productIdsString = $data['mobiapi_carousel']["'product_ids'"];

                    $productIdsString = str_replace("'", '', $productIdsString);
                    $data['product_ids'] = $productIdsString;

                    $data['mobiapi_carousel']['image_ids'] = '';

                    $data['image_ids'] = '';
                    $carousel->setImageIds(null);

                } else {
                }

//                if (isset($data['mobiapi_carousel']["'product_ids'"])) {
//                    $productIdsString = $data['mobiapi_carousel']["'product_ids'"];
//                    // Remove any single quotes from the product IDs string
//                    $productIdsString = str_replace("'", '', $productIdsString);
//                    $data['product_ids'] = $productIdsString;
//                } else {
//                    $data['product_ids'] = ''; // Set as empty if no product IDs provided
//                }

//                if (isset($data['mobiapi_carousel']['image_ids'])){
//                   $data['image_ids'] = $data['mobiapi_carousel']['image_ids'];
//               }

//                var_dump($data);
//                die('dwddd');
                $carousel->setData($data);
                $carouselImag = $this->processImage($carousel, $data);
                $carousel->save();

                $this->messageManager->addSuccess(__('The carousel has been saved.'));
                $this->adminsession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    }
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {

                $this->messageManager->addError($e->getMessage());
                $this->adminsession->setFormData($data);
                if ($carouselId) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $carouselId, '_current' => true]);
                } else {
                    return $resultRedirect->setPath('*/*/addnew', ['_current' => true]);
                }
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->adminsession->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $carouselId, '_current' => true]);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the carousel.'));
                $this->adminsession->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $carouselId, '_current' => true]);
            }

            $this->adminsession->setFormData($data);
            return $resultRedirect->setPath('*/*/add');
        }

        return $resultRedirect->setPath('*/*/add');
    }

    public function processImage($model, $data)
    {
        if ($model->getId()) {
            $pageData = $this->carousel;
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
}

<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Featuredcategories;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use BraveBison\Mobiapi\Model\Featuredcategories;
use BraveBison\Mobiapi\Model\ImageUploader;


class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Featuredcategories
     */
    protected $featuredcategories;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Featuredcategories    $featuredcategories
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        Featuredcategories $featuredcategories,
        Session $adminsession,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->featuredcategories = $featuredcategories;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
//            var_dump($data); die('featue');
            try {
                $featuredCatId = isset($data['id']) ? $data['id'] : null;
//                var_dump($data);die('ddd');
                if ($featuredCatId) {
                    // Edit existing banner image
                    $featuredCat = $this->featuredcategories->load($featuredCatId);
                    if (!$featuredCat->getId()) {
                        $this->messageManager->addErrorMessage(__('This Featured Category no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    // Create new banner image
                    $featuredCat = $this->featuredcategories;
                }

                $selectedStoreIds = implode(',', $data['store_id']);
                $data['store_id'] = $selectedStoreIds;
                $featuredCat->setData($data);
                $featuredCat = $this->image($featuredCat, $data);
//                var_dump($data);die('wwwwwwwwwwww');

                $featuredCat->save();
//                var_dump($bannerId);die('2');

                $this->messageManager->addSuccess(__('The Featured Category has been saved Successfully.'));
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
            $pageData = $this->featuredcategories;
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
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::featuredcategories");
    }
}

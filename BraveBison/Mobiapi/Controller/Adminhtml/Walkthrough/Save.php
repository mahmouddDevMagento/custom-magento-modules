<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Walkthrough;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use BraveBison\Mobiapi\Model\Walkthrough;
use BraveBison\Mobiapi\Model\ImageUploader;


class Save extends \Magento\Backend\App\Action
{
    /*
     * @var Walkthrough
     */
    protected $walkthrough;

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
        Walkthrough $walkthrough,
        Session $adminsession,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->walkthrough = $walkthrough;
        $this->adminsession = $adminsession;
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
//            var_dump($data); die('walkthrough');
            try {
                $walkthroughId = isset($data['id']) ? $data['id'] : null;
//                var_dump($data);die('ddd');
                if ($walkthroughId) {
                    $walkthrough = $this->walkthrough->load($walkthroughId);
                    if (!$walkthrough->getId()) {
                        $this->messageManager->addErrorMessage(__('This Walkthrough no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    $walkthrough = $this->walkthrough;
                }

                $walkthrough->setData($data);
                $walkthrough = $this->image($walkthrough, $data);
//                var_dump($data);die('wwwwwwwwwwww');

                $walkthrough->save();
//                var_dump($bannerId);die('2');

                $this->messageManager->addSuccess(__('The Walkthrough has been saved Successfully.'));
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
            $walkthroughData = $this->walkthrough;
            $walkthroughData->load($model->getId());
            if (isset($data['image'][0]['name'])) {
                $imageName1 = $walkthroughData->getImage();
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
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::walkthrough");
    }
}

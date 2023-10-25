<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Homepage;

class Update extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;
    protected $appcreatorFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \BraveBison\Mobiapi\Model\AppcreatorFactory $appcreatorFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->appcreatorFactory = $appcreatorFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            if (isset($params['selectedItems'])) {
                $selectedItems = json_decode($params['selectedItems'], true);

                // Create an array with the data and positions
                $dataWithPositions = [];
                foreach ($selectedItems as $key => $item) {
                    $dataWithPositions[] = [
                        'layout_id' => $item['layout_id'],
                        'label' => $item['label'],
                        'position' => $key + 1,
                        'type' => $item['type'],
                    ];
                }

                //Truncate table mobiapi_appcreator
                $app = $this->appcreatorFactory->create();
                $connection = $app->getResource()->getConnection();
                $tableName = $app->getResource()->getMainTable();
                $connection->truncateTable($tableName);

                //Save data
                $this->saveDataWithPositions($dataWithPositions);

                $response = ['success' => true, 'data' => 'Data saved successfully'];
            } else {
                $response = ['success' => false, 'data' => 'No data to save'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => $e->getMessage()];
        }

        return $resultJson->setData($response);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::homepage");
    }


    protected function saveDataWithPositions($dataWithPositions)
    {
        $layoutData = [];

        foreach ($dataWithPositions as $data) {
            $layoutId = $data['layout_id'];

            if (isset($layoutData[$layoutId])) {
                $layoutData[$layoutId]['position'][] = $data['position'];
            } else {
                $layoutData[$layoutId] = $data;
                $layoutData[$layoutId]['position'] = [(string)$data['position']];
            }
        }

        foreach ($layoutData as $layoutId => $data) {
            $existingRecord = $this->appcreatorFactory->create()->getCollection()
                ->addFieldToFilter('layout_id', $layoutId)
                ->getFirstItem();

            $data['position'] = implode(',', $data['position']);

            if ($existingRecord->getId()) {
                $existingRecord->addData($data);
                $existingRecord->save();
            } else {
                $newRecord = $this->appcreatorFactory->create();
                $newRecord->setData($data);
                $newRecord->save();
            }
        }
    }

}

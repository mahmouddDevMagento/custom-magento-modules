<?php

namespace Unioncoop\CustomerAccountReorder\Controller\Cart;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\ScopeInterface;
use Magedelight\Storepickup\Model\StorelocatorFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;

class Add extends \Magento\Framework\App\Action\Action
{
    const UC_SOURCE = 'source_default_uc';

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var \Ktpl\LocationManagement\Model\ResourceModel\LocationModelFactory
     */
    protected $locationCollectionFactory;

    /**
     * @var \Ktpl\LocationManagement\Api\BranchRepositoryInterface
     */
    protected $branchRepositoryInterface;

    /**
     * @var \Ktpl\FilterProduct\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Ktpl\Mobilecode\Helper\Data
     */
    protected $catalogInventoryHelper;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Checkout\Model\Cart $cart,
        JsonFactory $jsonFactory,
        \Ktpl\LocationManagement\Model\ResourceModel\LocationModelFactory $locationCollectionFactory,
        \Ktpl\LocationManagement\Api\BranchRepositoryInterface $branchRepositoryInterface,
        \Ktpl\Mobilecode\Helper\Data $catalogInventoryHelper,
        \Ktpl\FilterProduct\Helper\Data $dataHelper
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->cart = $cart;
        $this->jsonFactory = $jsonFactory;
        $this->locationCollectionFactory = $locationCollectionFactory;
        $this->branchRepositoryInterface = $branchRepositoryInterface;
        $this->catalogInventoryHelper = $catalogInventoryHelper;
        $this->dataHelper = $dataHelper;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $response = ['success' => false];
        $errors = [];

        $items = $this->getRequest()->getParam('items');

        $branchCode = $this->getBranchCodeFromCookie();

        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                try {
                    $productId = $item['id'];
                    $qty = trim($item['qty'] ?? 0);

                    $product = $this->productFactory->create()->load($productId);
                    $quoteItem = $this->cart->getQuote()->getItemByProduct($product);
                    if($quoteItem && $quoteItem->getItemId() && !empty($branchCode) && empty($product->getVendorId())){
                        $inventoryData = $this->catalogInventoryHelper->getInventory($product, $branchCode);
                        if(is_array($inventoryData) && count($inventoryData) > 0){
                            $minQty = $inventoryData['min_qty'] ?? 0;
                            $maxAllowedQty = $inventoryData['qty'] ?? 0;

                            $currentItemQty = (float)$quoteItem->getQty();
                            $totalItemQty = $currentItemQty + (float)$qty;
                            if($totalItemQty > 0 && $totalItemQty > $maxAllowedQty){//set maximum qty of product incase qty exceed product max qty
                                $qty = max($maxAllowedQty - $currentItemQty, 0);
                            }
                        }

                        if(!$qty){
                            continue;
                        }
                    }

                    if ($product->getId()) {
                        $this->cart->addProduct($product, ['qty' => $qty]);
                    }

                } catch (\Exception $e) {
                    $productId = $item['id'];
                    $product = $this->productFactory->create()->load($productId);
                    $productName = $product->getName();
                    $errors[] = $e->getMessage() .' for the '. "Item: $productName " ;}
            }

            $this->cart->save();

            if (empty($errors)) {
                $response['success'] = true;
                $this->messageManager->addSuccessMessage(__('Items added to cart successfully'));
            } else {
                $response['error'] = implode("\n", $errors);
                foreach ($errors as $error) {
                    $this->messageManager->addErrorMessage($error);
                }
            }
        } else {
            $this->messageManager->addErrorMessage(__('No items selected!.'));
            $response['error'] = 'No items selected.';
        }

        return $result->setData($response);
    }

    public function getBranchCodeFromCookie()
    {
        $getBranchCode = false;

        try {
            $locationId = $this->dataHelper->getLocation();
            $emirateId = $this->dataHelper->getEmirate();
            $getBranchId = $this->dataHelper->getStoreCookieValue();

            if (!empty($locationId) && !empty($emirateId)) {
                $locationModel = $this->locationCollectionFactory->create()->getBranch($emirateId, $locationId);
                if (!empty($locationModel)) {
                    $getBranchId = isset($locationModel[0]) ? $locationModel[0] : "";
                }
            }

            if (!empty($getBranchId)) {
                try {
                    $branch = $this->branchRepositoryInterface->getById($getBranchId);
                    $getBranchCode = $branch->getBranchCode();
                } catch (\Exception $exception) {

                }
            }
        } catch (\Exception $e) {

        }

        return $getBranchCode;
    }

}

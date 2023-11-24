<?php

namespace UnionCoop\MagentoTask\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartItemRepositoryInterface;
use UnionCoop\MagentoTask\Model\UnioncoopTableFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Message\ManagerInterface;

class RemoveFromCart extends Action
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CartItemRepositoryInterface
     */
    protected $cartItemRepository;

    /**
     * @var UnioncoopTableFactory
     */
    protected $unioncoopTableFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;


    /**
     * @param Context $context
     * @param Cart $cart
     * @param CheckoutSession $checkoutSession
     * @param CartItemRepositoryInterface $cartItemRepository
     * @param UnioncoopTableFactory $unioncoopTableFactory
     * @param CustomerSession $customerSession
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        Cart $cart,
        CheckoutSession $checkoutSession,
        CartItemRepositoryInterface $cartItemRepository,
        UnioncoopTableFactory $unioncoopTableFactory,
        CustomerSession $customerSession,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->cartItemRepository = $cartItemRepository;
        $this->unioncoopTableFactory = $unioncoopTableFactory;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $itemId = $this->getRequest()->getPost('id');
        try {
            $removedItem = $this->cart->getQuote()->getItemById($itemId);

            if ($removedItem) {
                $this->saveToUnioncoopTable($removedItem);
                $this->removeItemFromCart($itemId);
                $this->messageManager->addSuccessMessage(__('Item removed from the cart.'));
            } else {
                throw new \Exception('Item not found in the cart.');
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed to remove the item.'));
        }

        $this->_redirect('checkout/cart');
    }

    protected function saveToUnioncoopTable($removedItem)
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $product = $removedItem->getProduct();

        if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $childProduct = $removedItem->getOptionByCode('simple_product')->getProduct();
            $productId = $childProduct->getId();
            $productSku = $childProduct->getSku();
            $productName = $childProduct->getName();
        } else {
            $productId = $product->getId();
            $productSku = $product->getSku();
            $productName = $product->getName();
        }

        $createdAt = $removedItem->getCreatedAt();
        $unioncoopTable = $this->unioncoopTableFactory->create();
        $unioncoopTable->setData([
            'customer_id' => $customerId,
            'product_id' => $productId,
            'product_sku' => $productSku,
            'product_name' => $productName,
            'created_at' => $createdAt,
        ]);
        $unioncoopTable->save();
    }

    protected function removeItemFromCart($itemId)
    {
        $this->cart->removeItem($itemId);
        $this->cart->getQuote()->setTotalsCollectedFlag(false);
        $this->cart->save();
    }
}

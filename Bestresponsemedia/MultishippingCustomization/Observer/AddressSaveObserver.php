<?php

namespace BestResponseMedia\MultishippingCustomization\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface;

class AddressSaveObserver implements ObserverInterface
{
    protected $checkoutSession;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var \Magento\Quote\Model\Quote\AddressFactory
     */
    protected $_addressFactory;

    public function __construct(
         CheckoutSession $checkoutSession,
         AddressRepositoryInterface $addressRepository,
         \Magento\Quote\Model\Quote\AddressFactory $addressFactory,
         Http $request

    ){
        $this->checkoutSession= $checkoutSession;
        $this->addressRepository = $addressRepository;
        $this->_addressFactory = $addressFactory;
        $this->request = $request;

    }

    public function execute(Observer $observer)
    {

        $address = $observer->getCustomerAddress();
        $quote = $this->checkoutSession->getQuote();
        $addressId = $address->getId();

        // Fetch the address data
        $addressData = $this->addressRepository->getById($addressId);

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->importCustomerAddressData($addressData);
        $quote->save();


//        $address = $this->addressRepository->getById($addressId);
//        if (isset($address)) {
//            $quoteAddress = $this->_addressFactory->create()->importCustomerAddressData($address);
//            $quote->addShippingAddress($quoteAddress);
//        }
//        $quoteAddress = $quote->getShippingAddressByCustomerAddressId($address->getId());
//        $quoteAddress->setCustomerAddressId($addressId);
//
//        return $this;
//        var_dump($quoteAddress);
//        var_dump($address->getId());
//        var_dump($quote->getId());
//die('MultishippingCustomization');
    }
}
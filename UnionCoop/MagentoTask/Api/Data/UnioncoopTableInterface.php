<?php

namespace UnionCoop\MagentoTask\Api\Data;

interface UnioncoopTableInterface
{
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const PRODUCT_ID = 'product_id';
    const PRODUCT_SKU = 'product_sku';
    const PRODUCT_NAME = 'product_name';
    const CREATED_AT = 'created_at';


    public function getId();

    public function setId($id);

    public function getCustomerId();

    public function setCustomerId($customerId);

    public function getProductId();

    public function setProductId($productId);

    public function getProductSku();

    public function setProductSku($productSku);

    public function getProductName();

    public function setProductName($productName);

    public function getCreatedAt();

    public function setCreatedAt($createdAt);
}

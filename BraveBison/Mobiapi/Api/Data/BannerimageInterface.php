<?php

namespace BraveBison\Mobiapi\Api\Data;

interface BannerimageInterface
{
    const ID = "id";
    const TYPE = "type";
    const STATUS = "status";
    const IMAGE = "image";
    const STORE_ID = "store_id";
    const PRODUCT_CAT_ID = "product_cat_id";
    const SORT_ORDER = "sort_order";

    /**
     * Function getId
     *
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getType();

    /**
     * @param int $type
     */
    public function setType($type);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     */
    public function setImage($image);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     */
    public function setStoreId($storeId);

    /**
     * @return int
     */
    public function getProductCatId();

    /**
     * @param int $productCatId
     */
    public function setProductCatId($productCatId);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     */
    public function setSortOrder($sortOrder);
}

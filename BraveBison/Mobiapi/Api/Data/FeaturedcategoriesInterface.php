<?php

namespace BraveBison\Mobiapi\Api\Data;


interface FeaturedcategoriesInterface
{
    const ID = "id";
    const STATUS = "status";
    const IMAGE = "image";
    const STORE_ID = "store_id";
    const SORT_ORDER = "sort_order";
    const CATEGORY_ID = "category_id";
    const UPDATE_TIME = "update_time";
    const CREATED_TIME = "created_time";

    /**
     *
     * @return integer
     */
    public function getId();

    /**
     *
     * @param integer $id id
     */
    public function setId($id);

    /**
     *
     * @return integer
     */
    public function getStatus();

    /**
     *
     * @param integer $status status
     */
    public function setStatus($status);

    /**
     *
     * @return string
     */
    public function getImage();

    /**
     *
     * @param string $image image
     */
    public function setImage($image);

    /**
     *
     * @return integer
     */
    public function getStoreId();

    /**
     *
     * @param integer $storeId storeId
     */
    public function setStoreId($storeId);

    /**
     *
     * @return integer
     */
    public function getSortOrder();

    /**
     *
     * @param integer $sortOrder sortOrder
     */
    public function setSortOrder($sortOrder);

    /**
     *
     * @return integer
     */
    public function getCategoryId();

    /**
     *
     * @param integer $categoryId categoryId
     */
    public function setCategoryId($categoryId);

    /**
     *
     * @return string
     */
    public function getUpdateTime();

    /**
     *
     * @param string $updatedAt updatedAt
     */
    public function setUpdateTime($updatedAt);

    /**
     *
     * @return string
     */
    public function getCreatedTime();

    /**
     *
     * @param string $createdAt createdAt
     */
    public function setCreatedTime($createdAt);
}

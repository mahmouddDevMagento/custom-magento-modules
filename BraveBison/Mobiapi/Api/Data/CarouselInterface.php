<?php

namespace BraveBison\Mobiapi\Api\Data;

interface CarouselInterface
{
    const ID = 'id';
    const TITLE = 'title';
    const TYPE = 'type';
    const IMAGE = 'image';
    const COLOR_CODE = 'color_code';
    const IMAGES = 'images';
    const STATUS = 'status';
    const SORT_ORDER = 'sort_order';
    const IMAGE_IDS = 'image_ids';
    const PRODUCT_IDS = 'product_ids';
    const STORE_ID = 'store_id';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get Type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Set Type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Get Image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Set Image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Get Color Code
     *
     * @return string|null
     */
    public function getColorCode();

    /**
     * Set Color Code
     *
     * @param string $colorCode
     * @return $this
     */
    public function setColorCode($colorCode);

    /**
     * Get Images
     *
     * @return string|null
     */
    public function getImages();

    /**
     * Set Images
     *
     * @param string $images
     * @return $this
     */
    public function setImages($images);

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set Status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get Sort Order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set Sort Order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Get Image IDs
     *
     * @return string|null
     */
    public function getImageIds();

    /**
     * Set Image IDs
     *
     * @param string $imageIds
     * @return $this
     */
    public function setImageIds($imageIds);

    /**
     * Get Product IDs
     *
     * @return string|null
     */
    public function getProductIds();

    /**
     * Set Product IDs
     *
     * @param string $productIds
     * @return $this
     */
    public function setProductIds($productIds);

    /**
     * Get Store IDs
     *
     * @return array|null
     */
    public function getStoreId();

    /**
     * Set Store IDs
     *
     * @param array $storeIds
     * @return $this
     */
    public function setStoreId($storeIds);
}

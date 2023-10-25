<?php

namespace BraveBison\Mobiapi\Api\Data;

interface CarouselimageInterface
{
    const ID = "id";
    const IMAGE = "image";
    const TYPE = "type";
    const TITLE = "title";
    const PRODUCT_CAT_ID = "product_cat_id";
    const STATUS = "status";

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     */
    public function setImage($image);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

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
    public function getStatus();

    /**
     * @param int $status
     */
    public function setStatus($status);
}

<?php

namespace BraveBison\Mobiapi\Api\Data;


interface WalkthroughInterface
{
    const ID = "id";
    const TITLE = "title";
    const DESCRIPTION = "description";
    const COLOR_CODE = "color_code";
    const IMAGE = "image";
    const STATUS = "status";
    const SORT_ORDER = "sort_order";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

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
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getColorCode();

    /**
     * @param string $colorCode
     */
    public function setColorCode($colorCode);

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
    public function getStatus();

    /**
     * @param int $status
     */
    public function setStatus($status);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     */
    public function setSortOrder($sortOrder);
}

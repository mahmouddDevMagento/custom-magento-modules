<?php

namespace BraveBison\Mobiapi\Api\Data;

interface CategoryimagesInterface
{
    const ID = "id";
    const ICON = "icon";
    const BANNER = "banner";
    const SMALLBANNER = "smallbanner";
    const CATEGORY_ID = "category_id";
    const CATEGORY_NAME = "category_name";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

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
     * @return string
     */
    public function getIcon();

    /**
     *
     * @param string $icon icon
     */
    public function setIcon($icon);

    /**
     *
     * @return string
     */
    public function getBanner();

    /**
     *
     * @param string $banner banner
     */
    public function setBanner($banner);

    /**
     *
     * @return string
     */
    public function getSmallbanner();

    /**
     *
     * @param string $smallbanner smallbanner
     */
    public function setSmallbanner($smallbanner);

    /**
     *
     * @return string
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
    public function getCategoryName();

    /**
     *
     * @param string $categoryName categoryName
     */
    public function setCategoryName($categoryName);

    /**
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     *
     * @param string $createdAt createdAt
     */

    /**
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     *
     * @param string $updatedAt updatedAt
     */
    public function setUpdatedAt($updatedAt);
}

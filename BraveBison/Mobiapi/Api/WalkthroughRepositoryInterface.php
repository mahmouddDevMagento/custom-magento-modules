<?php

namespace BraveBison\Mobiapi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\WalkthroughInterface;

interface WalkthroughRepositoryInterface
{
    /**
     * @param int $walkThroughId
     */
    public function getById($walkThroughId);

    /**
     * @param int $walkThroughId
     */
    public function deleteById($walkThroughId);

    /***
     * @param WalkthroughInterface $walkThrough
     */
    public function save(WalkthroughInterface $walkThrough);

    /**
     * @param WalkthroughInterface $walkThrough
     */
    public function delete(WalkthroughInterface $walkThrough);

    /**
     * @param SearchCriteriaInterface $searchCriteria 
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}

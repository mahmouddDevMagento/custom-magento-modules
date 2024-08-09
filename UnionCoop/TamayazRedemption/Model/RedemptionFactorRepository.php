<?php
namespace Unioncoop\TamayazRedemption\Model;

use Unioncoop\TamayazRedemption\Api\Data\RedemptionFactorInterface;
use Unioncoop\TamayazRedemption\Api\RedemptionFactorRepositoryInterface;
use Unioncoop\TamayazRedemption\Model\ResourceModel\RedemptionFactor as RedemptionFactorResource;
use Unioncoop\TamayazRedemption\Model\RedemptionFactorFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class RedemptionFactorRepository implements RedemptionFactorRepositoryInterface
{
    protected $resource;
    protected $redemptionFactorFactory;

    public function __construct(
        RedemptionFactorResource $resource,
        RedemptionFactorFactory $redemptionFactorFactory
    ) {
        $this->resource = $resource;
        $this->redemptionFactorFactory = $redemptionFactorFactory;
    }

    public function save(RedemptionFactorInterface $redemptionFactor)
    {
        try {
            $this->resource->save($redemptionFactor);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $redemptionFactor;
    }

    public function getById($redemptionFactorId)
    {
        $redemptionFactor = $this->redemptionFactorFactory->create();
        $this->resource->load($redemptionFactor, $redemptionFactorId);
        if (!$redemptionFactor->getId()) {
            throw new NoSuchEntityException(__('The redemption factor with the "%1" ID doesn\'t exist.', $redemptionFactorId));
        }
        return $redemptionFactor;
    }

    public function delete(RedemptionFactorInterface $redemptionFactor)
    {
        try {
            $this->resource->delete($redemptionFactor);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($redemptionFactorId)
    {
        return $this->delete($this->getById($redemptionFactorId));
    }
}

<?php
namespace Bestresponsemedia\Customers\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class UpgradeData implements UpgradeDataInterface
{

     /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    
    /**
     * @var AttributeSetFactory
     */
    protected $attributeSetFactory;

    /**
    * construct
    *
    * @param EavSetupFactory $eavSetupFactory eavSetupFactory
    *
    * @return array $this this
    */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup,ModuleContextInterface $context ) 
    {

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            // $setup->startSetup();
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup,]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                'github_url',
                [
                    'type' => 'varchar',
                    'label' => 'Url Attribute',
                    'input' => 'text',
                    'required' => false,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'is_searchable_in_grid' => true,
                    'unique' => true,
                    'visible' => true,
                    'user_defined' => true,
                    'validate_rules' =>
                        '{"min_text_length":10,"max_text_length":200,"validate-url":true}',
                    'position' => 1000,
                    'system' => 0
                ]
            );
            
            /* to specify which place you want to display customer attribute */
            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'github_url')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => [
                        'adminhtml_customer',
                        'customer_account_create',
                        'customer_account_edit',
                    ],
                ]);
            $attribute->save();
        }
    }
}
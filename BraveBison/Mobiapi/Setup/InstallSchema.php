<?php

namespace BraveBison\Mobiapi\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable("mobiapi_bannerimage"))
            ->addColumn(
                "id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["identity"=>true, "unsigned"=>true, "nullable"=>false, "primary"=>true],
                "Id"
            )
            ->addColumn("image", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Banner Image")
            ->addColumn("status", \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Status")
            ->addColumn("type", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Type")
            ->addColumn(
                "product_cat_id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Product Category Id"
            )
            ->addColumn("store_id", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Store ID")
            ->addColumn(
                "sort_order",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Sort Order"
            )
            ->setComment("Mobiapi Banner Image Table");
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable("mobiapi_featuredcategories"))
            ->addColumn(
                "id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["identity"=>true, "unsigned"=>true, "nullable"=>false, "primary"=>true],
                "ID"
            )
            ->addColumn("image", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Featured Category Image")
            ->addColumn(
                "category_id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Category Id"
            )
            ->addColumn("store_id", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Store Id")
            ->addColumn(
                "sort_order",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Sort Order"
            )
            ->addColumn("status", \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Status")
            ->setComment("Mobiapi Featured Category Table");
        $setup->getConnection()->createTable($table);


        $table = $setup->getConnection()
            ->newTable($setup->getTable("mobiapi_categoryimages"))
            ->addColumn(
                "id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["identity"=>true, "unsigned"=>true, "nullable"=>false, "primary"=>true],
                "Id"
            )
            ->addColumn("icon", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Icon")
            ->addColumn("banner", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Banner")
            ->addColumn("smallbanner", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Small Banner")
            ->addColumn(
                "category_id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Category Id"
            )
            ->addColumn("category_name", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Category Name")
            ->addColumn("store_id", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["unsigned"=>true, "nullable"=>false, "default"=>null], "Store ID")

            ->setComment("Mobiapi Category Images Table");
        $setup->getConnection()->createTable($table);


        $table = $setup->getConnection()
            ->newTable($setup->getTable("mobiapi_carouselimage"))
            ->addColumn(
                "id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["identity"=>true, "unsigned"=>true, "nullable"=>false, "primary"=>true],
                "Id"
            )
            ->addColumn("image", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Image Path")
            ->addColumn("type", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Type")
            ->addColumn("title", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Title")
            ->addColumn(
                "product_cat_id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Product Category Id"
            )
            ->addColumn("status", \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Status")
            ->setComment("Mobiapi Carousel Image Table");
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable("mobiapi_carousel"))
            ->addColumn(
                "id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["identity"=>true, "unsigned"=>true, "nullable"=>false, "primary"=>true],
                "Id"
            )
            ->addColumn("title", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Title")
            ->addColumn("type", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Type")
            ->addColumn("image", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Background Image")
            ->addColumn("color_code", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Background Color code")
            ->addColumn("images", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Selected Images")
            ->addColumn("status", \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Status")
            ->addColumn(
                "sort_order",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Sort Order"
            )
            ->addColumn("image_ids", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Selected Image")
            ->addColumn("product_ids", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Selected Products")
            ->addColumn("store_id", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["unsigned"=>true, "nullable"=>false, "default"=>null], "Store ID")
            ->setComment("Mobiapi Carousel Table");
        $setup->getConnection()->createTable($table);


        $table = $setup->getConnection()
            ->newTable($setup->getTable("mobiapi_walkthrough"))
            ->addColumn(
                "id",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["identity"=>true, "unsigned"=>true, "nullable"=>false, "primary"=>true],
                "Id"
            )
            ->addColumn("title", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 50, ["nullable"=>true, "default"=>null], "Title")
            ->addColumn("description", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 200, ["nullable"=>true, "default"=>null], "Title")
            ->addColumn("color_code", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ["nullable"=>true, "default"=>null], "Color Code")
            ->addColumn("image", \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Selected Image")
            ->addColumn("status", \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ["unsigned"=>true, "nullable"=>false, "default"=>"0"], "Status")
            ->addColumn(
                "sort_order",
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ["unsigned"=>true, "nullable"=>false, "default"=>"0"],
                "Sort Order"
            )
            ->addColumn(
                "created_at",
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ["nullable"=>false, "default"=>\Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                "Creation Date Time"
            )
            ->addColumn(
                "updated_at",
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ["nullable"=>false, "default"=>\Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                "Updation Date Time"
            )
            ->setComment("Mobiapi Walk Through");
             $setup->getConnection()->createTable($table);

             $tableAppCreator = $setup->getConnection()
                    ->newTable($setup->getTable("mobiapi_appcreator"))
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )
                    ->addColumn(
                        'layout_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true, 'default' => ''],
                        'Status'
                    )->addColumn(
                        'label',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true, 'default' => ''],
                        'Label'
                    )->addColumn(
                        'position',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true, 'default' => ''],
                        'Position'
                    )->addColumn(
                        'type',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true, 'default' => ''],
                        'Type'
                    )
                    ->setComment('App Creator Table Data')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');
             $setup->getConnection()->createTable($tableAppCreator);

        $setup->endSetup();
    }
}

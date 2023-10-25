<?php

namespace BestResponseMedia\CurrencyConversion\Model\Logger;

use Magento\Framework\Filesystem\DriverInterface;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Handler constructor.
     * @param DriverInterface $filesystem
     * @param null $filePath
     */
    public function __construct(DriverInterface $filesystem, $filePath = null)
    {
        parent::__construct($filesystem, $filePath);
        $this->getFormatter()->ignoreEmptyContextAndExtra(true);
    }

    /**
     * Logging level
     * @var int
     */
    protected $loggerType = \Monolog\Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/bb_currency_conversion.log';
}
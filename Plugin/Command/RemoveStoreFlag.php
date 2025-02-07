<?php

namespace Blackbird\ScopedMaintenance\Plugin\Command;

use Blackbird\ScopedMaintenance\Model\CacheManager;
use Blackbird\ScopedMaintenance\Service\Maintenance;
use Magento\Backend\Console\Command\AbstractMaintenanceCommand;
use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @noinspection PhpUnused
 */
class RemoveStoreFlag
{

    /**
     * @var Maintenance
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $maintenance;

    /**
     * @var CacheManager
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $cacheManager;

    public function __construct(
        Maintenance $maintenance,
        CacheManager $cacheManager
    )
    {
        $this->maintenance = $maintenance;
        $this->cacheManager = $cacheManager;
    }


    /**
     * @param AbstractMaintenanceCommand $subject
     * @param $result
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     * @throws FileSystemException
     * @noinspection PluginInspection
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function afterRun(AbstractMaintenanceCommand $subject, $result, InputInterface $input, OutputInterface $output)
    {
        $this->maintenance->deleteStoreInfo();
        $this->cacheManager->purgeCacheForStoreIds(
            $this->maintenance->getListOfStoreIdsInMaintenance()
        );
        return $result;
    }
}

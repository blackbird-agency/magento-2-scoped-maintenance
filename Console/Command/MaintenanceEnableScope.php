<?php

namespace Blackbird\ScopedMaintenance\Console\Command;

use Blackbird\ScopedMaintenance\Block\StoreCacheTag;
use Blackbird\ScopedMaintenance\Model\CacheManager;
use Blackbird\ScopedMaintenance\Service\Maintenance;
use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaintenanceEnableScope extends Command
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

    /**
     * @param Maintenance $maintenance
     * @param CacheManager $cacheManager
     * @param string|null $name
     */
    public function __construct(Maintenance $maintenance, CacheManager $cacheManager, ?string $name = null)
    {
        $this->maintenance = $maintenance;
        parent::__construct($name);
        $this->cacheManager = $cacheManager;
    }

    /**
     * Initialization of the command.
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function configure()
    {
        $this->setName('maintenance:enable-store');
        $this->setDescription('Set the maintenance to only specific stores.');
        $this->addArgument("store_ids",InputArgument::REQUIRED, "List of store ids, separated by comma.");
        $this->addOption("ip",null,InputArgument::OPTIONAL, "List of IPs to allow, separated by comma.");
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allowedIps = $input->getOption("ip");
        $storeIds = $input->getArgument("store_ids");
        $allowedIps = explode(",", $allowedIps ?? "");
        $storeIds = explode(",", $storeIds ?? "");
        $this->maintenance->setMaintenanceForStores(true, $storeIds, $allowedIps);
        $this->cacheManager->purgeCacheForStoreIds(
            $this->maintenance->getListOfStoreIdsInMaintenance()
        );
        $output->writeln('<info>Maintenance mode enabled for stores: ' .  implode(", ", $storeIds) . '</info>');

        return 0;
    }

}

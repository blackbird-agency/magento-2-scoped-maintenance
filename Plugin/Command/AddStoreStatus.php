<?php

namespace Blackbird\ScopedMaintenance\Plugin\Command;

use Blackbird\ScopedMaintenance\Api\Service\MaintenanceInterface;
use Magento\Backend\Console\Command\MaintenanceStatusCommand;
use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @noinspection PhpUnused
 */
class AddStoreStatus
{
    /**
     * @var MaintenanceInterface
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $maintenance;

    public function __construct(
        MaintenanceInterface $maintenance
    )
    {
        $this->maintenance = $maintenance;
    }

    /**
     * @param MaintenanceStatusCommand $subject
     * @param $result
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     * @throws FileSystemException
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function afterRun(MaintenanceStatusCommand $subject, $result, InputInterface $input, OutputInterface $output)
    {
        $stores = $this->maintenance->getListOfStoreIdsInMaintenance();
        $output->writeln('<info>List of store ids in maintenance: ' . (!empty($stores) ? implode(", ", $stores) : 'none') . '</info>');
        return $result;
    }
}

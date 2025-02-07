<?php

declare(strict_types=1);

namespace Blackbird\ScopedMaintenance\Api\Service;
use Magento\Framework\Exception\FileSystemException;

interface MaintenanceInterface
{
    /**
     *
     *
     * @param  boolean  $isOn
     * @param  int[]    $storeIds
     * @param  string[] $allowedIps
     * @return void
     * @throws FileSystemException
     */
    public function setMaintenanceForStores(bool $isOn, array $storeIds=[], array $allowedIps=[]): void;


    /**
     * @param  integer $storeId
     * @return boolean
     * @throws FileSystemException
     */
    public function isMaintenanceEnabledForStore(int $storeId): bool;


    /**
     * @return array
     * @throws FileSystemException
     */
    public function getListOfStoreIdsInMaintenance():array;


}

<?php

namespace Blackbird\ScopedMaintenance\Service;

use Blackbird\ScopedMaintenance\Api\Service\MaintenanceInterface;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;

class Maintenance implements MaintenanceInterface
{
    /**
     * Hash file name.
     */
    public const MAINTENANCE_STORE_FILENAME = '.maintenance.store';

    /**
     * Path to store files.
     * @var Filesystem\Directory\WriteInterface
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $flagDir;

    /**
     * @var MaintenanceMode
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $maintenanceMode;

    /**
     * @var StoreManagerInterface
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $storeManager;

    /**
     * @param Filesystem $filesystem
     * @param MaintenanceMode $maintenanceMode
     * @param StoreManagerInterface $storeManager
     * @throws FileSystemException
     */
    public function __construct(Filesystem $filesystem, MaintenanceMode $maintenanceMode, StoreManagerInterface $storeManager)
    {
        $this->flagDir = $filesystem->getDirectoryWrite(MaintenanceMode::FLAG_DIR);
        $this->maintenanceMode = $maintenanceMode;
        $this->storeManager = $storeManager;
    }


    /**
     * @inheritDoc
     */
    public function setMaintenanceForStores(bool $isOn, array $storeIds = [], array $allowedIps = []): void
    {
        $this->maintenanceMode->set($isOn);
        $this->maintenanceMode->setAddresses(implode(",", $allowedIps));
        if ($isOn) {
            $this->setStoreInfo($storeIds);
            return;
        }
        $this->deleteStoreInfo();
    }

    /**
     * @inheritDoc
     */
    public function isMaintenanceEnabledForStore(int $storeId): bool
    {
        if (!$this->flagDir->isExist(MaintenanceMode::FLAG_FILENAME)) {
            return false;
        }
        $info = $this->getStoreInfo();
        return !empty($info) && in_array($storeId, $info);
    }

    /**
     * @return array
     * @throws FileSystemException
     */
    protected function getStoreInfo(): array
    {
        if ($this->flagDir->isExist(static::MAINTENANCE_STORE_FILENAME)) {
            $temp = $this->flagDir->readFile(static::MAINTENANCE_STORE_FILENAME);
            /** @noinspection SpellCheckingInspection */
            return array_map('intval', explode(',', trim($temp)));
        } else {
            return [];
        }
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function deleteStoreInfo(): void
    {
        if ($this->flagDir->isExist(static::MAINTENANCE_STORE_FILENAME)) {
            $this->flagDir->delete(static::MAINTENANCE_STORE_FILENAME);
        }
    }

    /**
     * @param array $storeIds
     * @return void
     * @throws FileSystemException
     */
    protected function setStoreInfo(array $storeIds): void
    {
        $this->flagDir->writeFile(static::MAINTENANCE_STORE_FILENAME, implode(',', $storeIds));
    }

    /**
     * @inheritDoc
     */
    public function getListOfStoreIdsInMaintenance(): array
    {
        if (!$this->flagDir->isExist(MaintenanceMode::FLAG_FILENAME)) {
            return [];
        }
        $storeInfo = $this->getStoreInfo();
        if(!empty($storeInfo)) {
           return $storeInfo;
        }
        return array_map(function($store) {
            /** @noinspection PhpCastIsUnnecessaryInspection */
            return (int) $store->getId();
        },  $this->storeManager->getStores(true));

    }
}

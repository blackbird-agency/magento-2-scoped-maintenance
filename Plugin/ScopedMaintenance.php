<?php

declare(strict_types=1);

namespace Blackbird\ScopedMaintenance\Plugin;

use Blackbird\ScopedMaintenance\Api\Service\MaintenanceInterface;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\MaintenanceMode;
use Throwable;

/**
 * @noinspection PhpUnused
 */
class ScopedMaintenance
{
    /**
     * @var StoreManagerInterface
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $storeManager;

    /**
     * @var MaintenanceInterface
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $maintenance;

    /**
     * @var Request
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $request;


    /**
     * @param StoreManagerInterface $storeManager
     * @param MaintenanceInterface $maintenance
     * @param Request $request
     */
    public function __construct(StoreManagerInterface $storeManager, MaintenanceInterface $maintenance, Request $request)
    {
        $this->maintenance = $maintenance;
        $this->storeManager = $storeManager;
        $this->request = $request;
    }

    /**
     * @param MaintenanceMode $subject
     * @param $result
     * @return bool|mixed
     * @noinspection PhpUnused
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function afterIsOn(MaintenanceMode $subject, $result)
    {
        if ($result) {
            try {
                $storeId = $this->getCurrentStoreId();
                return $this->maintenance->isMaintenanceEnabledForStore($storeId);
            } /** Compatibility with php < 8.0  @noinspection PhpUnusedLocalVariableInspection */ catch (Throwable $t) {
                return $result;
            }
        }
        return $result;
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    protected function getCurrentStoreId(): int
    {
        /** @noinspection PhpCastIsUnnecessaryInspection */
        $storeId = (int) $this->storeManager->getStore()->getId();
        /** @noinspection PhpCastIsUnnecessaryInspection */
        $defaultStoreId = (int) $this->storeManager->getDefaultStoreView()->getId();
        $requestUri = $this->request->getUri()->__toString();
        if (
            $defaultStoreId === $storeId
        ) {
            $adminStore = $this->storeManager->getStore(0);
            $baseUrl = $adminStore->getBaseUrl();
            $mediaBaseUrl = $adminStore->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $staticBaseUrl = $adminStore->getBaseUrl(UrlInterface::URL_TYPE_STATIC);
            /** Compatibility with php < 8.0 @noinspection PhpStrFunctionsInspection */
            if(
                strpos($requestUri, $baseUrl) === 0
                || strpos($requestUri, $mediaBaseUrl) === 0
                || strpos($requestUri, $staticBaseUrl) === 0
            ){
                $storeId = 0;
            }
        }
        return $storeId;
    }
}

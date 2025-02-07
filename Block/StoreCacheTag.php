<?php

declare(strict_types=1);

namespace Blackbird\ScopedMaintenance\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Blackbird\ScopedMaintenance\Model\CacheManager;

class StoreCacheTag extends Template implements IdentityInterface
{
    /**
     * @var StoreManagerInterface
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $storeManager;

    /**
     * @param Template\Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @return string[]
     * @throws NoSuchEntityException
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function getIdentities()
    {
        $storeId = $this->storeManager->getStore()->getId();
        return [CacheManager::CACHE_TAG . $storeId];
    }
}


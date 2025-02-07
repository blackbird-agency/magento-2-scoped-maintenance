<?php

declare(strict_types=1);

namespace Blackbird\ScopedMaintenance\Model;

use Blackbird\ScopedMaintenance\Block\StoreCacheTag;
use Magento\CacheInvalidate\Model\PurgeCache;

class CacheManager {

    /**
     * Custom cache tag to be applied on all page to allow cleaning of varnish for specific stores
     */
    public const CACHE_TAG = 'store_';

    /**
     * @var PurgeCache
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $purgeCache;


    /**
     * @param PurgeCache $purgeCache
     */
    public function __construct(
        PurgeCache $purgeCache
    )
    {
        $this->purgeCache = $purgeCache;
    }

    /**
     * @param array $storeIds
     * @return void
     */
    public function purgeCacheForStoreIds(array $storeIds):void
    {
        $identities = [];
        foreach ($storeIds as $storeId) {
            $identities[] =  static::CACHE_TAG . $storeId;
        }
        $this->purgeCache->sendPurgeRequest($identities);
    }

}

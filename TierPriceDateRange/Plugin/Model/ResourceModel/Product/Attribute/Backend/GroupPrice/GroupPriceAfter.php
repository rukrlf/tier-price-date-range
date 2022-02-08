<?php
/**
 * @category Rukshan
 * @package  Rukshan_TierPriceDateRange
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 *
 * @author   Rukshan
 */
namespace Rukshan\TierPriceDateRange\Plugin\Model\ResourceModel\Product\Attribute\Backend\GroupPrice;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\GroupPrice\AbstractGroupPrice;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;

class GroupPriceAfter
{
    /**
     * Tier price table name
     */
    const TIER_PRICE_TABLE = 'catalog_product_entity_tier_price';
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;
    /**
     * @var TimezoneInterface
     */
    private TimezoneInterface $localeDate;
    /**
     * @var DateTime
     */
    private DateTime $dateTime;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param ResourceConnection $resource
     * @param TimezoneInterface $localeDate
     * @param DateTime $dateTime
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceConnection $resource,
        TimezoneInterface $localeDate,
        DateTime $dateTime,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->localeDate = $localeDate;
        $this->dateTime = $dateTime;
        $this->storeManager = $storeManager;
    }

    /**
     * After plugin for getSelect() for catalog_product_entity_tier_price table
     *
     * @param AbstractGroupPrice $subject
     * @param Select $result
     * @return Select
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetSelect(
        AbstractGroupPrice $subject,
        Select $result
    ): Select {
        if ($subject->getMainTable() == self::TIER_PRICE_TABLE) {
            $columns = $this->resource->getConnection()->describeTable(self::TIER_PRICE_TABLE);
            if (array_key_exists('date_from', $columns) && array_key_exists('date_to', $columns)) {
                $timestamp = $this->localeDate->scopeTimeStamp($this->storeManager->getStore());
                $currentDate = $this->dateTime->formatDate($timestamp, false);
                $result
                    ->where(
                        'date_from IS NULL OR ' .
                        $this->resource->getConnection()->getDatePartSql('date_from') .' <= ?',
                        $currentDate
                    )->where(
                        'date_to IS NULL OR ' .
                        $this->resource->getConnection()->getDatePartSql('date_to') .' >= ?',
                        $currentDate
                    );
            }
        }

        return $result;
    }
}

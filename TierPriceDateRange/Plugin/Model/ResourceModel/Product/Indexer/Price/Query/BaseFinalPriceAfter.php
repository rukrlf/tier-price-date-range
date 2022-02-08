<?php
/**
 * @category Rukshan
 * @package  Rukshan_TierPriceDateRange
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 *
 * @author   Rukshan
 */
namespace Rukshan\TierPriceDateRange\Plugin\Model\ResourceModel\Product\Indexer\Price\Query;

use Magento\Catalog\Model\ResourceModel\Product\Indexer\Price\Query\BaseFinalPrice;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;
use Zend_Db_Select;
use Zend_Db_Select_Exception;

class BaseFinalPriceAfter
{
    /**
     * Tier price table name
     */
    const TIER_PRICE_TABLE = 'catalog_product_entity_tier_price';
    private ResourceConnection $resource;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * After plugin for getQuery in BaseFinalPrice
     *
     * @param BaseFinalPrice $subject
     * @param Select $result
     * @return Select
     * @throws Zend_Db_Select_Exception
     */
    public function afterGetQuery(
        BaseFinalPrice $subject,
        Select $result
    ) {
        $columns = $this->resource->getConnection()->describeTable(self::TIER_PRICE_TABLE);
        if (array_key_exists('date_from', $columns) && array_key_exists('date_to', $columns)) {
            $fromAndJoins = $result->getPart(Zend_Db_Select::FROM);
            foreach ($fromAndJoins as $key => $joins) {
                // adds the custom date_from and date_to fields of catalog_product_entity_tier_price to the join query
                // of the query which inserts to catalog_product_index_price on price reindex
                if (in_array($key, ['tier_price_1', 'tier_price_2', 'tier_price_3', 'tier_price_4'])
                    && $joins['tableName'] == self::TIER_PRICE_TABLE) {
                    $fromAndJoins[$key]['joinCondition'] .= " AND ({$key}.date_from IS NULL OR
                     DATE({$key}.date_from) <= cwd.website_date)
                      AND ({$key}.date_to IS NULL OR DATE({$key}.date_to) >= cwd.website_date)";
                }
            }
            $result->reset(Zend_Db_Select::FROM);
            $result->setPart(Zend_Db_Select::FROM, $fromAndJoins);
        }

        return $result;
    }
}

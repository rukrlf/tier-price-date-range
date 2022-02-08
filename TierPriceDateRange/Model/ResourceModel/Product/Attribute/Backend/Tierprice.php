<?php
/**
 * @category Rukshan
 * @package  Rukshan_TierPriceDateRange
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 *
 * @author   Rukshan
 */
namespace Rukshan\TierPriceDateRange\Model\ResourceModel\Product\Attribute\Backend;

class Tierprice extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice
{
    /**
     * Add date_from and date_to columns
     *
     * @param array $columns
     * @return array
     */
    protected function _loadPriceDataColumns($columns)
    {
        $columns = parent::_loadPriceDataColumns($columns);
        $columns['date_from'] = 'date_from';
        $columns['date_to'] = 'date_to';
        return $columns;
    }
}

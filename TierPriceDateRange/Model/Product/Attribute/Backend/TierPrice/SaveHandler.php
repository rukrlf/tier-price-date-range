<?php
/**
 * @category Rukshan
 * @package  Rukshan_TierPriceDateRange
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author Rukshan
 */
declare(strict_types=1);

namespace Rukshan\TierPriceDateRange\Model\Product\Attribute\Backend\TierPrice;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Process Tier price date range data for handled new product
 */
class SaveHandler extends \Magento\Catalog\Model\Product\Attribute\Backend\TierPrice\SaveHandler
{
    /**
     * SaveHandler constructor
     *
     * @param StoreManagerInterface               $storeManager
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param GroupManagementInterface            $groupManagement
     * @param MetadataPool                        $metadataPool
     * @param Tierprice                           $tierPriceResource
     * @param DateTime                            $date
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ProductAttributeRepositoryInterface $attributeRepository,
        GroupManagementInterface $groupManagement,
        MetadataPool $metadataPool,
        Tierprice $tierPriceResource,
        DateTime $date
    ) {
        parent::__construct($storeManager, $attributeRepository, $groupManagement, $metadataPool, $tierPriceResource);
    }

    /**
     * Additional fields for date_from and date_to
     *
     * @param array $objectArray
     * @return array
     */
    protected function getAdditionalFields(array $objectArray): array
    {
        $result = parent::getAdditionalFields($objectArray);

        return array_merge(
            $result,
            [
                'date_from' => $this->getDateAttribute($objectArray, 'date_from'),
                'date_to'   => $this->getDateAttribute($objectArray, 'date_to'),
            ]
        );
    }

    /**
     * Get date range attribute value
     *
     * @param  array  $row
     * @param  string $attr
     * @return string|null
     */
    private function getDateAttribute(array $row, string $attr)
    {
        return isset($row[$attr]) && !empty($row[$attr]) ? $row[$attr] : null;
    }
}

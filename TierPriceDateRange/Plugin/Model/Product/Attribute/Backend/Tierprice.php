<?php
/**
 * @category Rukshan
 * @package  Rukshan_TierPriceDateRange
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 *
 * @author   Rukshan
 */
namespace Rukshan\TierPriceDateRange\Plugin\Model\Product\Attribute\Backend;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Tierprice
{
    /**
     * @var DateTime
     */
    private DateTime $date;

    /**
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * After plugin for validate function
     *
     * @param Product\Attribute\Backend\Tierprice $subject
     * @param bool $result
     * @param Product $product
     * @return bool
     * @throws LocalizedException
     */
    public function afterValidate(
        Product\Attribute\Backend\Tierprice $subject,
        bool                                $result,
        Product                             $product
    ) {
        $attribute = $subject->getAttribute();
        $priceRows = $product->getData($attribute->getName());
        $priceRows = array_filter((array)$priceRows);

        foreach ($priceRows as $key => $priceRow) {
            $dateFrom = $this->getDateAttribute($priceRow, 'date_from');
            if ($dateFrom !== null) {
                $dateFrom = $this->date->timestamp($dateFrom);
            }
            $dateTo = $this->getDateAttribute($priceRow, 'date_to');
            if ($dateTo !== null) {
                $dateTo = $this->date->timestamp($dateTo);
            }
            if ($dateFrom > $dateTo) {
                throw new LocalizedException(
                    __(
                        'Make sure the Tier Price To Date is later than or the same as the Tier Price From Date on row %row', //phpcs:ignore Generic.Files.LineLength
                        ['row' => $key]
                    )
                );
            }
        }

        return $result;
    }

    /**
     * Get date range attribute value
     *
     * @param array $row
     * @param string $attr
     * @return string|null
     */
    private function getDateAttribute(array $row, string $attr)
    {
        return isset($row[$attr]) && !empty($row[$attr]) ? $row[$attr] : null;
    }
}

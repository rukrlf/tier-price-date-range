<?php
/**
 * @category Rukshan
 * @package  Rukshan_TierPriceDateRange
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 *
 * @author   Rukshan
 */
namespace Rukshan\TierPriceDateRange\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\DataType\Date;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;

class UpdateTierPricing extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var string
     */
    private $scopeName;

    /**
     * @var array
     */
    private $meta = [];

    /**
     * UpdateTierPricing constructor.
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     * Modifydata function
     *
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Modifymeta info
     *
     * @param array $meta
     * @return array
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->customizeTierPrice();

        return $this->meta;
    }

    /**
     * Customized tier price for date range
     *
     * @return $this
     */
    private function customizeTierPrice()
    {
        $tierPricePath = $this->arrayManager->findPath(
            ProductAttributeInterface::CODE_TIER_PRICE,
            $this->meta,
            null,
            'children'
        );

        if ($tierPricePath) {
            $this->meta = $this->arrayManager->merge(
                $tierPricePath,
                $this->meta,
                $this->getTierPriceStructure()
            );
        }

        return $this;
    }

    /**
     * Tier price structure for from and to date range fields
     *
     * @return array
     */
    private function getTierPriceStructure()
    {
        return [
            'children' => [
                'record' => [
                    'children' => [
                        'date_from' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label'            => __('From'),
                                        'component'        => 'Magento_Ui/js/form/element/date',
                                        'componentType'    => Field::NAME,
                                        'formElement'      => Input::NAME,
                                        'dataType'         => Date::NAME,
                                        'dataScope'        => 'date_from',
                                        'inputDateFormat'  => 'y-MM-dd',
                                        'outputDateFormat' => 'y-MM-dd',
                                        'sortOrder'        => 44,
                                        'options'          => [
                                            'dateFormat' => 'y-MM-dd',
                                        ],
                                        'validation' => [
                                            'validate-date' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'date_to' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label'            => __('To'),
                                        'component'        => 'Magento_Ui/js/form/element/date',
                                        'componentType'    => Field::NAME,
                                        'formElement'      => Input::NAME,
                                        'dataType'         => Date::NAME,
                                        'dataScope'        => 'date_to',
                                        'inputDateFormat'  => 'y-MM-dd',
                                        'outputDateFormat' => 'y-MM-dd',
                                        'sortOrder'        => 45,
                                        'options'          => [
                                            'dateFormat' => 'y-MM-dd',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

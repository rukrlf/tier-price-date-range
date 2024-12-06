<?php

namespace Rukshan\TierPriceDateRange\Model\Product\Type;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    /**
     * Override the getTierPrice method to exclude tier prices outside the date range.
     */
    public function getTierPrice($qty, $product)
    {
        $allGroupsId = $this->getAllCustomerGroupsId();
        $prices = $this->getExistingPrices($product, 'tier_price', true);

        if ($prices === null || !is_array($prices)) {
            if ($qty !== null) {
                return $product->getPrice();
            } else {
                return [
                    [
                        'price' => $product->getPrice(),
                        'website_price' => $product->getPrice(),
                        'price_qty' => 1,
                        'cust_group' => $allGroupsId,
                    ]
                ];
            }
        }

        $currentDate = (new \DateTime())->format('Y-m-d');
        $custGroup = $this->_getCustomerGroupId($product);

        if ($qty) {
            $prevQty = 0;
            $prevPrice = $product->getPrice();
            $prevGroup = $allGroupsId;

            foreach ($prices as $price) {
                if (!empty($price['date_from']) && $price['date_from'] > $currentDate) {
                    continue;
                }
                if (!empty($price['date_to']) && $price['date_to'] < $currentDate) {
                    continue;
                }

                if ($price['cust_group'] != $custGroup && $price['cust_group'] != $allGroupsId) {
                    continue;
                }
                if ($qty < $price['price_qty']) {
                    continue;
                }
                if ($price['price_qty'] < $prevQty) {
                    continue;
                }
                if ($price['price_qty'] == $prevQty &&
                    $prevGroup != $allGroupsId &&
                    $price['cust_group'] == $allGroupsId) {
                    continue;
                }
                if ($price['website_price'] < $prevPrice) {
                    $prevPrice = $price['website_price'];
                    $prevQty = $price['price_qty'];
                    $prevGroup = $price['cust_group'];
                }
            }

            return $prevPrice;
        } else {
            $qtyCache = [];
            foreach ($prices as $priceKey => $price) {
                if (!empty($price['date_from']) && $price['date_from'] > $currentDate) {
                    unset($prices[$priceKey]);
                    continue;
                }
                if (!empty($price['date_to']) && $price['date_to'] < $currentDate) {
                    unset($prices[$priceKey]);
                    continue;
                }

                if ($price['cust_group'] != $custGroup && $price['cust_group'] != $allGroupsId) {
                    unset($prices[$priceKey]);
                } elseif (isset($qtyCache[$price['price_qty']])) {
                    $priceQty = $qtyCache[$price['price_qty']];
                    if ($prices[$priceQty]['website_price'] > $price['website_price']) {
                        unset($prices[$priceQty]);
                        $qtyCache[$price['price_qty']] = $priceKey;
                    } else {
                        unset($prices[$priceKey]);
                    }
                } else {
                    $qtyCache[$price['price_qty']] = $priceKey;
                }
            }
        }

        return $prices ?: [];
    }
}

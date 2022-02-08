# Rukshan - Date Range for Tier Pricing
This is a complete module that will add 2 custom fields from_date and to_date into Advanced Pricing->Tier Pricing (Customer Group Price).
This was added to someone who can use this in need and avoid the pain one could go through.

Add From Date and To Date (date range) to Tier Pricing Magento 2
![image](https://user-images.githubusercontent.com/2842397/152954294-84bba364-f4b4-4ee7-871b-833a27915334.png)


## Installation

### Zip file

- Unzip the zip file in `app/code/Rukshan`
- Enable the module by running `php bin/magento module:enable Rukshan_TierPriceDateRange`
- Apply database updates by running `php bin/magento setup:upgrade`
- Flush the cache by running `php bin/magento cache:flush`

## Specifications

- Check on admin -> Catalog->Products->[Edit Product]->Advacned Pricing->Add tier prices

### PS: This is not thoroughly tested, only few basic scenarios, if you happen to find any issues pls do keep posted or make a PR

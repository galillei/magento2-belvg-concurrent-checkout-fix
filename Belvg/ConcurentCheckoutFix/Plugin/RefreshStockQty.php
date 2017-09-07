<?php
/**
 * Created by PhpStorm.
 * User: galillei
 * Date: 22.11.16
 * Time: 16.02
 */

namespace Belvg\ConcurentCheckoutFix\Plugin;

use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\StockRegistryStorage;
use Magento\Framework\App\Config\ScopeConfigInterface;

class RefreshStockQty
{
    const ENABLE = 'belvg_concurentCheckoutFix/general/turn_on';
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StockRegistryStorage
     */
    protected $_stockRegistryStorage;

    public function __construct(ScopeConfigInterface $scopeConfig,
                                StockRegistryStorage $stockRegistryStorage)
    {
        $this->scopeConfig = $scopeConfig;
        $this->_stockRegistryStorage = $stockRegistryStorage;
    }

    /**
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterLockProductsStock($subject, $result)
    {
        if ($this->scopeConfig->getValue(self::ENABLE)) {
            try {
                if (is_array($result)) {
                    foreach ($result as $item) {
                        if (isset($item['product_id'])) {
                            $this->_stockRegistryStorage->removeStockItem($item['product_id']);
                        }
                    }
                }
            } catch (\Exception $e) {

            }
        }
        return $result;

    }
}
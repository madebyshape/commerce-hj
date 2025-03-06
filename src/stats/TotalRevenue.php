<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\stats;

use craft\commerce\base\Stat;
use yii\db\Expression;

/**
 * Total Revenue Stat
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class TotalRevenue extends Stat
{
    /**
     * @since 4.1.0
     */
    public const TYPE_TOTAL = 'total';

    /**
     * @since 4.1.0
     */
    public const TYPE_TOTAL_PAID = 'totalPaid';

    /**
     * @var string
     * @since 4.1.0
     */
    public string $type = self::TYPE_TOTAL;

    /**
     * @inheritdoc
     */
    protected string $_handle = 'totalRevenue';

    /**
     * @inheritDoc
     */
    public function getData(): ?array
    {
        $query = $this->_createStatQuery();
        $query->select([new Expression('COUNT([[orders.id]]) as total')]);
        $query->andWhere(['not', ['orderStatusId' => 7]]);

        return $chartData = $this->_createChartQuery([
            new Expression('SUM([[total]]) as revenue'),
            new Expression('COUNT([[orders.id]]) as count'),
        ], [
            'revenue' => 0,
            'count' => 0,
        ], $query);

        // return $this->_createChartQuery(
        //     [
        //         new Expression(sprintf('SUM([[%s]]) as revenue', $this->type)),
        //         new Expression('COUNT([[orders.id]]) as count'),
        //     ],
        //     [
        //         'revenue' => 0,
        //         'count' => 0,
        //     ]
        // );
    }
}

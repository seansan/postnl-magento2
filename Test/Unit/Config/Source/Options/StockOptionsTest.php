<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\PostNL\Test\Unit\Config\Source\Options;

use TIG\PostNL\Config\Source\Options\StockOptions;

class StockOptionsTest extends \TIG\PostNL\Test\TestCase
{
    public $instanceClass = StockOptions::class;

    public function returnsTheCorrectOptionsProvider()
    {
        return [
            ['value' => 'in_stock'],
            ['value' => 'backordered'],
        ];
    }

    /**
     * @dataProvider returnsTheCorrectOptionsProvider
     */
    public function testReturnsTheCorrectOptions($value)
    {
        /** @var StockOptions $instance */
        $instance = $this->getInstance();

        $result = $instance->toOptionArray();

        $hasValue = false;
        foreach ($result as $option) {
            if ($option['value'] == $value) {
                $hasValue = true;
            }
        }

        $this->assertTrue($hasValue, '$result should contains ["value" => "' . $value . '"]');
    }
}

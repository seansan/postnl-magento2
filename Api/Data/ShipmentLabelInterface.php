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
namespace TIG\PostNL\Api\Data;

interface ShipmentLabelInterface
{
    const BARCODE_TYPE_LABEL = 'label';
    const BARCODE_TYPE_RETURN   = 'return';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @param int $value
     * @return \TIG\PostNL\Api\Data\ShipmentLabelInterface
     */
    public function setParentId($value);

    /**
     * @param int $value
     * @return \TIG\PostNL\Api\Data\ShipmentLabelInterface
     */
    public function setNumber($value);

    /**
     * @return int
     */
    public function getNumber();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $value
     * @return \TIG\PostNL\Api\Data\ShipmentLabelInterface
     */
    public function setLabel($value);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $value
     * @@return \TIG\PostNL\Api\Data\ShipmentLabelInterface
     */
    public function setProductCode($value);

    /**
     * @return int
     */
    public function getProductCode();

    /**
     * @param string $value
     * @return \TIG\PostNL\Api\Data\ShipmentLabelInterface
     */
    public function setType($value);

    /**
     * @return \TIG\PostNL\Api\Data\ShipmentInterface
     */
    public function getShipment();
}

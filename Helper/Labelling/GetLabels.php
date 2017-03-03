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
namespace TIG\PostNL\Helper\Labelling;

use TIG\PostNL\Model\ResourceModel\Shipment\CollectionFactory;
use TIG\PostNL\Webservices\Endpoints\Labelling;

class GetLabels
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Labelling
     */
    private $labelling;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Labelling         $labelling
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Labelling $labelling
    ) {

        $this->collectionFactory = $collectionFactory;
        $this->labelling = $labelling;
    }

    /**
     * @param int|string $shipmentId
     *
     * @return array
     */
    public function get($shipmentId)
    {
        $labels = [];

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('shipment_id', ['eq' => $shipmentId]);

         // @codingStandardsIgnoreLine
         // TODO: add a proper warning notifying of a non-postnl shipment
        if (count($collection) <= 0) {
            return $labels;
        }

        /** @var \TIG\PostNL\Model\Shipment $postnlShipment */
        foreach ($collection->getItems() as $postnlShipment) {
            $labels[$postnlShipment->getEntityId()] = $this->generateLabel($postnlShipment);
        }

        $labels = $this->checkWarnings($labels);

        return $labels;
    }

    /**
     * @param \TIG\PostNL\Model\Shipment $postnlShipment
     *
     * @return string|\Magento\Framework\Phrase
     */
    private function generateLabel($postnlShipment)
    {
        $this->labelling->setParameters($postnlShipment);
        $response = $this->labelling->call();
        $responseShipments = null;

        if (isset($response->ResponseShipments)) {
            $responseShipments = $response->ResponseShipments;
        }

        if (!is_object($response) || !isset($responseShipments->ResponseShipment)) {
            return __('Invalid generateLabel response: %1', var_export($response, true));
        }

        /**
         * @codingStandardsIgnoreLine
         * TODO: GenerateLabel call usually returns one label, but update so multiple labels are taking in account
         */
        return $responseShipments->ResponseShipment[0]->Labels->Label[0]->Content;
    }

    /**
     * @param $labels
     *
     * @return array
     */
    private function checkWarnings($labels)
    {
        /**
         * @codingStandardsIgnoreLine
         * TODO: Notify the user of the warning
         */
        $labels = array_filter(
            $labels,
            function ($value) {
                return (is_string($value));
            }
        );

        return $labels;
    }
}
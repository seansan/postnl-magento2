<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2017 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\PostNL\Helper\DeliveryOptions;

use \Magento\Quote\Api\Data\AddressInterface;
use \Magento\Checkout\Model\Session;
use \Magento\Quote\Model\Quote\AddressFactory;

/**
 * Class PickupAddress
 *
 * @package TIG\PostNL\Helper\DeliveryOptions
 */
class PickupAddress
{
    const PG_ADDRESS_TYPE = 'pakjegemak';

    /**
     * @var bool|AddressInterface
     */
    private $pickupAddress = false;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    /**
     * @param Session        $checkoutSession
     * @param AddressFactory $addressFactory
     */
    public function __construct(
        Session $checkoutSession,
        AddressFactory $addressFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->addressFactory  = $addressFactory;
    }

    /**
     * @return bool|AddressInterface
     */
    public function get()
    {
        return $this->pickupAddress;
    }

    /**
     * @param $address
     */
    public function set($address)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->checkoutSession->getQuote();

        /** @var array|\Magento\Quote\Model\Quote\Address $foundAddress */
        $foundAddress = $this->getPakjeGemakAddressInQuote($quote->getId());
        if (!empty($foundAddress) && $foundAddress->getId()) {
            $foundAddress->isDeleted(true);
            $quote->removeAddress($foundAddress->getId());
        }

        $this->pickupAddress = $this->create($address, $quote);
        $quote->addAddress($this->pickupAddress);
        $quote->save();
    }

    /**
     * @param int $quoteId
     *
     * @return array|\Magento\Quote\Model\Quote\Address
     */
    public function getPakjeGemakAddressInQuote($quoteId)
    {
        $quoteAddress = $this->addressFactory->create();

        $collection = $quoteAddress->getCollection();
        $collection->addFieldToFilter('quote_id', $quoteId);
        $collection->addFieldToFilter('address_type', self::PG_ADDRESS_TYPE);

        return $collection->setPageSize(1)->getFirstItem();
    }

    /**
     * @param $pgData
     * @param \Magento\Quote\Model\Quote $quote
     *
     * @return \Magento\Quote\Model\Quote\Address
     */
    private function create($pgData, $quote)
    {
        $address = $this->addressFactory->create();

        $address->setQuoteId($quote->getId());
        $address->setAddressType(self::PG_ADDRESS_TYPE);
        $address->setCompany($pgData['Name']);
        $address->setCity($pgData['City']);
        $address->setCountryId($pgData['Countrycode']);
        $address->setStreet([$pgData['Street'],$pgData['HouseNr']]);
        $address->setPostcode($pgData['Zipcode']);
        $address->setEmail($pgData['customer']['email']);
        $address->setFirstname($pgData['customer']['firstname']);
        $address->setLastname($pgData['customer']['lastname']);
        $address->setTelephone($pgData['customer']['telephone']);
        $address->save();

        return $address;
    }


}

<?php

namespace Omnipay\Paysera\Message;

use Omnipay\Paysera\Helper;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Paysera Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{

    public function getProjectId()
    {
        return $this->getParameter('projectid');
    }

    public function setProjectId($value)
    {
        return $this->setParameter('projectid', $value);
    }

    public function getSignPassword()
    {
        return $this->getParameter('sign_password');
    }

    public function setSignPassword($value)
    {
        return $this->setParameter('sign_password', $value);
    }

    public function getDeveloperId()
    {
        return $this->getParameter('developerid');
    }

    public function setDeveloperId($value)
    {
        return $this->setParameter('developerid', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('lang');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('lang', $value);
    }

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    public function getEndpoint()
    {
        return 'https://www.paysera.com/pay/';
    }

    public function getData()
    {

        $this->validate('card');

        $card = $this->getCard();

        $param = array(
            'projectid' => Helper::createParam($this->getProjectId(), 11),
            'orderid' => Helper::createParam($this->getTransactionId(), 11),
            'accepturl' => Helper::createParam($this->getReturnUrl()),
            'cancelurl' => Helper::createParam($this->getCancelUrl()),
            'callbackurl' => Helper::createParam($this->getNotifyUrl()),
            'version' => Helper::createParam($this->getVersion(), 9),
            'lang' => Helper::createParam($this->getlanguage(), 3, false),
            'amount' => Helper::createParam($this->getAmountInteger(), 11),
            'currency' => Helper::createParam($this->getCurrency(), 3),
            'p_firstname' => Helper::createParam($card->getFirstName()),
            'p_lastname' => Helper::createParam($card->getLastName()),
            'p_email' => Helper::createParam($card->getEmail()),
            'p_street' => Helper::createParam($card->getAddress1(), 255, false),
            'p_city' => Helper::createParam($card->getCity(), 255, false),
            'p_state' => Helper::createParam($card->getState(), 20, false),
            'p_zip' => Helper::createParam($card->getPostcode(), 20, false),
            'p_countrycode' => Helper::createParam($card->getCountry(), 2, false),
            'test' => Helper::createParam($this->getTestMode(), 1, false),
            'developerid' => Helper::createParam($this->getDeveloperId(), 11, false),

            /* TODO: make payments configurable */
            //'time_limit'
            //'country'
            //'payment'
            //'only_payments'
            //'disalow_payments'

            /* unused */
            //'paytext'
            //'personcode'
        );

        Helper::validateData($param);

        $data = array();
        $data['data'] = Helper::prepareData($param);
        $data['sign'] = Helper::signData($data['data'], $this->getSignPassword());

        return $data;
    }

    public function sendData($data)
    {
        return new Response($this, $data, $this->httpClient, true);
    }

}

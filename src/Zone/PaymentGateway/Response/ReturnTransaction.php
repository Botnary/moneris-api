<?php

namespace Zone\PaymentGateway\Response;

use Zone\PaymentGateway\Exception;
use Zone\PaymentGateway\Helper;
use Zone\PaymentGateway\Enum;

class ReturnTransaction extends Response
{

    /** @var Helper\Transaction */
    protected $transaction;

    /** @var string */
    protected $referenceNumber;

    /**
     * @param Helper\Transaction $transaction
     *
     * @return $this
     */
    public function setTransaction(Helper\Transaction $transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return Helper\Transaction
     * @throws Exception\MissingDataException
     */
    public function getTransaction()
    {
        if ($this->transaction === null)
        {
            throw new Exception\MissingDataException('Transaction not set');
        }
        return $this->transaction;
    }

    /**
     * @param $referenceNumber
     *
     * @return $this
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    protected function fetch()
    {
        $this->api->returnTransaction($this->getTransaction());
    }

    protected function postResponseAction()
    {
        $this->getTransaction()->setReferenceNumber($this->getReferenceNumber());
    }

}
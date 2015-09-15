<?php

namespace Zone\PaymentGateway\Response;

use Zone\PaymentGateway\Exception;
use Zone\PaymentGateway\Helper;
use Zone\PaymentGateway\Enum;

class VoidTransaction extends Response
{

    /** @var Helper\Transaction */
    protected $transaction;
    /**
     * @var string
     */
    protected $message;

    /**
     * @param Helper\Transaction $transaction
     *
     * @return VoidTransaction $this
     */
    public function setTransaction(Helper\Transaction $transaction) {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return Helper\Transaction
     * @throws Exception\MissingDataException
     */
    public function getTransaction() {

        if ($this->transaction === null) {
            throw new Exception\MissingDataException('Transaction not set');
        }
        return $this->transaction;
    }

    /**
     *
     */
    protected function fetch() {
        $this->api->voidTransaction($this->getTransaction());
    }

    protected function postResponseAction()
    {
        // do nothing
    }

}
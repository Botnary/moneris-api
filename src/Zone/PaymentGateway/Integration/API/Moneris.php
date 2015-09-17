<?php

namespace Zone\PaymentGateway\Integration\API;

use Zone\PaymentGateway\Exception;
use Zone\PaymentGateway\Helper;
use Zone\PaymentGateway\Enum;

class Moneris extends Core\XmlApi
{

    /** @var string */
    protected $storeId;

    /** @var string */
    protected $apiToken;

    /**
     * @param $storeId
     * @param $apiToken
     * @param bool $testMode
     */
    public function __construct($storeId, $apiToken, $testMode = false)
    {
        $this->storeId = $storeId;
        $this->apiToken = $apiToken;
        $this->setTestMode($testMode);
    }

    protected function updateEndpoint()
    {
        if (true === $this->getTestMode()) {
            $this->endpoint = 'https://esqa.moneris.com/gateway2/servlet/MpgRequest';
        } else {
            $this->endpoint = 'https://www3.moneris.com/gateway2/servlet/MpgRequest';
        }
    }

    /**
     * @param Helper\CreditCard $creditCard
     * @param Helper\Transaction $transaction
     */
    public function createCharge(Helper\CreditCard $creditCard, Helper\Transaction $transaction)
    {
        $payload = [
            'order_id' => $transaction->getTransactionId(),
            'amount' => sprintf('%.2f', $transaction->getAmount()),
            'pan' => $creditCard->getCardNumber(),
            'expdate' => $creditCard->getCardExpiry()->format('ym'),
            'crypt_type' => 7,
            'dynamic_descriptor' => $this->dynamicDescriptor ? $this->dynamicDescriptor : '',
        ];
        if ($transaction->getRecur()) {
            $payload['recur'] = [
                'recur_unit' => $transaction->getRecur()->getRecurUnit(),
                'start_now' => $transaction->getRecur()->getStartNow() ? 'true' : 'false',
                'start_date' => $transaction->getRecur()->getStartDate()->format('Y/m/t'),
                'num_recurs' => $transaction->getRecur()->getNumRecurs(),
                'period' => $transaction->getRecur()->getPeriod(),
                'recur_amount' => sprintf('%.2f', $transaction->getRecur()->getRecurAmount()),
            ];
        }
        if ($transaction->getCustomerID()) {
            $payload['cust_id'] = $transaction->getCustomerID();
        }
        $this->api(
            'purchase',
            $payload
        );
    }

    /**
     * @param Helper\Transaction $transaction
     */
    public function returnTransaction(Helper\Transaction $transaction)
    {
        $this->api(
            'refund',
            [
                'order_id' => md5($transaction->getParentTransaction()->getTransactionId()),
                'amount' => number_format($transaction->getAmount(), 2, '.', ''),
                'txn_number' => $transaction->getParentTransaction()->getApiResponse('TransID'),
                'crypt_type' => 7,
                'dynamic_descriptor' => $this->dynamicDescriptor
            ]
        );
    }

    /**
     * @param Helper\Transaction $transaction
     *
     * @throws Exception\MethodNotSupportedException
     * @return array
     */
    public function transactionInfo(Helper\Transaction $transaction)
    {
        throw new Exception\MethodNotSupportedException('Transaction Info not supported');
    }

    /**
     * @param Helper\Transaction $transaction
     */
    public function voidTransaction(Helper\Transaction $transaction)
    {
        $this->api(
            'purchasecorrection',
            [
                'order_id' => md5($transaction->getTransactionId()),
                'txn_number' => $transaction->getApiResponse('TransID'),
                'crypt_type' => 7,
                'dynamic_descriptor' => $this->dynamicDescriptor
            ]
        );

    }

    /**
     * @param Helper\CreditCard $creditCard
     */
    public function validateCard(Helper\CreditCard $creditCard)
    {
        $this->api(
            'card_verification',
            [
                'order_id' => 'vc-' . md5(time() . $creditCard->getCardNumber()),
                'pan' => $creditCard->getCardNumber(),
                'expdate' => $creditCard->getCardExpiry()->format('ym'),
                'crypt_type' => 7,
            ]
        );
    }

    /**
     * @param string $method
     * @param array $data
     */
    public function api($method, array $data = [])
    {
        if (array_key_exists('dynamic_descriptor', $data) && $data['dynamic_descriptor'] == null) {
            unset($data['dynamic_descriptor']);
        }

        $requestXML = $this->requestXML($method, $data);
        $this->curlExecute($this->endpoint, Enum\HttpMethod::POST, $requestXML, Enum\DataMode::NONE, Enum\DataMode::XML);
    }

    /**
     * @param string $method
     * @param array $data
     *
     * @return \SimpleXMLElement
     */
    private function requestXML($method, array $data)
    {

        $data = [
            'store_id' => $this->storeId,
            'api_token' => $this->apiToken,
            $method => $data
        ];

        return $this->array_to_xml($data, new \SimpleXMLElement('<request/>'));

    }

}

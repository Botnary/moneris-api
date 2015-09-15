# Simple Moneris gateway.
Usage
```
require_once 'vendor/autoload.php';
use Zone\PaymentGateway;
use Zone\PaymentGateway\Helper\CreditCard;
use Zone\PaymentGateway\Helper\Transaction;
use Zone\PaymentGateway\Integration\Moneris;
use Zone\PaymentGateway\Response\CreateCharge;
use Zone\PaymentGateway\Response\ValidateCard;

$moneris = new Moneris();
$moneris->getApi()->setTestMode(true); // using test environment
$moneris->setCredentials('store5', 'yesguy'); //this are the moneris demo credentials
$transaction = new Transaction();
$transaction->setTransactionId(sprintf("T%'.020d\n", 8));
$transaction->setTransactionDate(new DateTime('now'));
$transaction->setCurrency('CAD');
$transaction->setAmount('10.00');
$transaction->setComment('test transaction');

$card = new CreditCard();
$card->setCardNumber('4242424242424242');
$card->setCardExpiry('08', '12');
$card->setCardCVV('198');
$card->setAddress1('201');
$card->setAddress2('Michigan Ave');
$card->setCity('Montreal');
$card->setEmailAddress('test@host.com');
$card->setCountry('Canada');
$card->setZipCode('M1M1M1');

$moneris->validateCard($card, function (ValidateCard $response) use ($card, $transaction, $moneris) {
    //card is valid, continue with the charge.
    $moneris->createCharge($card, $transaction, function (CreateCharge $response) {
        //success charge
    }, function (CreateCharge $response) {
        //fail charge
    });
}, function (ValidateCard $response) {
    //card is invalid
});

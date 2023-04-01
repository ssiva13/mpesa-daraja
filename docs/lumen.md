## LARAVEL LUMEN DARAJA MPESA API Library

Source Code [Mpesa Daraja](https://github.com/ssiva13/mpesa-daraja/tree/lumen)

### Installation and Setup

1) In order to install mpesa daraja for laravel lumen, just add the following to your composer.json. Then
   run `composer update`:

    ```
    "ssiva/mpesa-daraja" : "dev-lumen"
    ```

   or run

    ```
    composer require "ssiva/mpesa-daraja":"dev-lumen"
    ```

2) Open your `bootstrap/app.php` and make the following changes:

   - uncomment and edit the following line

      ```php
      // $app->withFacades();
      ``` 
      to
      ```php
      $app->withFacades(true, [
            \Ssiva\MpesaDaraja\Facades\MpesaFacade::class => 'MpesaDaraja',
      ]);
      ``` 

   - Register the Mpesa ServiceProvider
     ```php
     // Mpesa ServiceProvider
     $app->register(\Ssiva\MpesaDaraja\MpesaServiceProvider::class);
     ```

### Configuration

Set up the config values as required

1) Account API Online Config
   ```dotenv
        MPESA_SHORTCODE=
        MPESA_RESULT_URL=
        MPESA_TIMEOUT_URL=
        MPESA_INITIATOR_NAME=
        MPESA_SECURITY_CREDENTIAL=
        MPESA_SECURITY_CERT=
        MPESA_ACCOUNT_IDENTIFIER=
   ```

2) LipaNaMpesa API Online Config
   ```dotenv
        MPESA_SHORTCODE=
        MPESA_CALLBACK_URL=
        MPESA_PASSKEY=
        MPESA_ONLINE_TRANSACTION_TYPE=
   ```

3) B2C API Config
   ```dotenv
        MPESA_SHORTCODE=
        MPESA_RESULT_URL=
        MPESA_TIMEOUT_URL=
        MPESA_INITIATOR_NAME=
        MPESA_SECURITY_CREDENTIAL=
        MPESA_SECURITY_CERT=
        MPESA_ACCOUNT_IDENTIFIER=
        MPESA_B2C_COMMAND=
   ```

4) B2B API Config
   ```dotenv
        MPESA_SHORTCODE=
        MPESA_RESULT_URL=
        MPESA_TIMEOUT_URL=
        MPESA_INITIATOR_NAME=
        MPESA_SECURITY_CREDENTIAL=
        MPESA_SECURITY_CERT=
        MPESA_ACCOUNT_IDENTIFIER=
        MPESA_B2B_COMMAND=
        MPESA_B2B_SENDER_ID=
        MPESA_B2B_RECEIVER_ID=
   ```

5) C2B API Config
   ```dotenv
        MPESA_SHORTCODE=
        MPESA_C2B_COMMAND=
        MPESA_C2B_CONFIRMATION_URL=
        MPESA_C2B_VALIDATION_URL=
        MPESA_C2B_RESPONSE_TYPE=
   ```

### Usage Examples

```php
<?php
namespace YOURNAMESPACE;

use MpesaDaraja; 
use Ssiva\MpesaDaraja\Mpesa;

class CheckoutController extends Controller {
   
   public function darajaExamples(
        $mpesaDaraja = new MpesaDaraja();
        
        // authenticate
        $mpesaDaraja::authenticate($stkParams);
        
        // STK Push
        $stkParams = [
            'Amount' => '2',
            'PartyA' => '2547XXXXXXXX',
            'PhoneNumber' => '2547XXXXXXXX',
            'AccountReference' => '13',
            'TransactionDesc' => 'Shopping',
        ];
       $mpesaDaraja::stkPush($stkParams);
       
       // stk push status query
       $stkQueryParams = [
         'CheckoutRequestID' => "ws_CO_290320231617432767XXXXXXXX",
       ];
       $mpesaDaraja::stkPushQuery($stkQueryParams);
       
       // transaction status query
       $statusParams = [
         'Remarks' => "Status test for RCC3LAPCEL",
         "TransactionID" => "RCC3LAPCEL",
         "Occasion" => "Optional Value for Occasion"
       ];
       $mpesaDaraja::transactionStatus($statusParams);

   }
}


```
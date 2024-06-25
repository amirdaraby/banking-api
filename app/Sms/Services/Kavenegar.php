<?php /** @noinspection ALL */

namespace App\Sms\Services;

use App\Sms\SmsServiceInterface;
use Kavenegar\KavenegarApi;

class Kavenegar implements SmsServiceInterface
{

    private KavenegarApi $api;

    private $sender = null;

    public function __construct()
    {
        $this->api = new KavenegarApi(config('sms.providers.kavenegar.api_key'));
        $this->sender = config('sms.providers.kavenegar.sender');
    }

    public function send(string $recipient, string $message): void
    {
        $this->api->Send($this->sender, $recipient, $message);
    }
}

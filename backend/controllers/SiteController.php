<?php

namespace backend\controllers;

use common\actions\ErrorAction;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use yii\db\Connection;
use yii\web\Response;
use function Psy\bin;

class SiteController extends AppController
{
    protected array $exceptAuthActions = ['*'];

    public function actionIndex(): void
    {
//        dd(openssl_get_curve_names());
        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($privateKey, $privateKeyPem);

        $publicKeyPem = openssl_pkey_get_details($privateKey)['key'];
        $publicKey = openssl_get_publickey($publicKeyPem);

        $address = hash('ripemd160', hash('sha256', $publicKeyPem));
        dd($privateKey, $privateKeyPem, $publicKey, $publicKeyPem, $address);

        $data = 'Hello world!';
        openssl_public_encrypt($data, $encryptedData, $publicKey);

        openssl_private_decrypt($encryptedData, $decryptedData, $privateKey);

//        dd($data, $encryptedData, $decryptedData);

        $data = 'Hello world!';
        openssl_private_encrypt($data, $encryptedData, $privateKey);

        openssl_public_decrypt($encryptedData, $decryptedData, $publicKey);

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $verify = openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        dd($data, unpack('A', $encryptedData), $encryptedData, $decryptedData, $signature, base64_encode($signature), $verify);
    }

    /**
     * @return array[]
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}
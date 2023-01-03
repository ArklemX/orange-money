<?php

class OrangeMoney
{

    //To get access token
    private static string $om_token_url;
    private static string $auth_token;

    //TO Init the Merchant Payment
    private static string $om_mp_init_url;
    private static string $om_mp_pay_url;
    private static string $om_channel_user;
    private static string $om_pin_code;
    private static string $om_notify_url;

    /**Orange Money Merchant Pay**/
    /**
     * @description Make OrangeMerchantPay
     * @param $phone
     * @param $montant
     * @param $description
     * @param $order_id
     * @return array
     */
    public static function OrangeMerchantPay($phone, $montant, $description, $order_id)
    {
        list($token, $http_code_token) = self::GetOMAccessToken(self::$om_token_url, self::$auth_token);
        $paiement = null;
        $status = false;

        if ($http_code_token == 200) {
            list($mp_init, $http_code_mp_init) = self::MerchantPaymentInit(self::$om_mp_init_url, self::$auth_token, $token->token_type, $token->access_token);
            $paiement = $mp_init;
            if ($http_code_mp_init == 200) {
                list($mp_pay, $http_code_mp_pay) = self::MerchantPaymentPay(self::$om_mp_pay_url, self::$auth_token, $token->token_type, $token->access_token, $phone, self::$om_channel_user, $montant, $description, $order_id, self::$om_pin_code, $mp_init->data->payToken, self::$om_notify_url);
                $paiement = $mp_pay;
                $status = true;
            }
        }
        return array($paiement, $status);
    }

    /**
     * @description Make Access Token Orange
     * @param $url
     * @param $auth_token
     * @return array
     */
    public static function GetOMAccessToken($url, $auth_token)
    {

        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_HEADER, false);
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $auth_token,
        ));
        \curl_setopt($ch, \CURLOPT_URL, $url);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, true);
        \curl_setopt($ch, \CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        $response = \curl_exec($ch);
        $http_code_token = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        $token = json_decode($response);

        return array($token, $http_code_token);
    }

    /**
     * @description Init Merchant Payment
     * @param $url
     * @param $auth_token
     * @param $token_type
     * @param $access_token
     * @return array
     */
    public static function MerchantPaymentInit($url, $auth_token, $token_type, $access_token)
    {

        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_HEADER, false);
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            'X-AUTH-TOKEN: ' . $auth_token,
            'Content-Type: application/json',
            'Authorization: ' . $token_type . ' ' . $access_token,
        ));
        \curl_setopt($ch, \CURLOPT_URL, $url);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, true);
        $response = \curl_exec($ch);
        $http_code_mp_init = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        $mp_init = json_decode($response);

        return array($mp_init, $http_code_mp_init);
    }

    /**
     * @description Make Merchant a Payment
     * @param $url
     * @param $auth_token
     * @param $token_type
     * @param $access_token
     * @param $subscriberUser
     * @param $channelUser
     * @param $amount
     * @param $description
     * @param $orderId
     * @param $pin
     * @param $payToken
     * @param $notifyUrl
     * @return array
     */
    public static function MerchantPaymentPay($url, $auth_token, $token_type, $access_token, $subscriberUser, $channelUser, $amount, $description, $orderId, $pin, $payToken, $notifyUrl)
    {

        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_HEADER, false);
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            'X-AUTH-TOKEN: ' . $auth_token,
            'Content-Type: application/json',
            'Authorization: ' . $token_type . ' ' . $access_token,
        ));
        \curl_setopt($ch, \CURLOPT_URL, $url);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, true);
        \curl_setopt($ch, \CURLOPT_POSTFIELDS, '{
            "subscriberMsisdn": "' . $subscriberUser . '",
            "channelUserMsisdn": "' . $channelUser . '",
            "amount": "' . $amount . '",
            "description": "' . $description . '",
            "orderId": "' . $orderId . '",
            "pin": "' . $pin . '",
            "payToken": "' . $payToken . '",
            "notifUrl": "' . $notifyUrl . '"
        }');
        $response = \curl_exec($ch);
        $http_code_mp_pay = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        $mp_pay = json_decode($response);

        return array($mp_pay, $http_code_mp_pay);
    }

    /**
     * @description Get Merchant Payment Status
     * @param $url
     * @param $auth_token
     * @param $token_type
     * @param $access_token
     * @param $payToken
     * @return array
     */
    public static function MerchantPaymentStatus($url, $auth_token, $token_type, $access_token, $payToken)
    {
        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_HEADER, false);
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            'X-AUTH-TOKEN: ' . $auth_token,
            'Content-Type: application/json',
            'Authorization: ' . $token_type . ' ' . $access_token,
        ));
        \curl_setopt($ch, \CURLOPT_URL, $url . '/' . $payToken);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'GET');
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, true);
        $response = \curl_exec($ch);
        $http_code_mp_status = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        $mp_status = json_decode($response);

        return array($mp_status, $http_code_mp_status);
    }

    /**Orange Money Cash In**/
    /**
     * @description Make OrangeCashIn
     * @param $phone
     * @param $montant
     * @param $description
     * @param $id_element
     * @return array
     */
    public static function OrangeCashIn($phone, $montant, $description, $id_element)
    {
        list($token, $http_code_token) = self::GetOMAccessToken(Yii::$app->params['OM_TOKEN_URL'], Yii::$app->params['OM_AUTHORIZATION_' . Yii::$app->params['ENV']]);
        $paiement = $token;
        $status = false;

        $order_id = self::getOderId($id_element);

        if ($http_code_token == 200) {
            list($cash_in_init, $http_code_cash_in_init) = self::CashInInit(Yii::$app->params['OM_CASHIN_INIT_URL'], Yii::$app->params['OM_AUTH_TOKEN_' . Yii::$app->params['ENV']], $token->token_type, $token->access_token);
            $paiement = $cash_in_init;
            if ($http_code_cash_in_init == 200) {
                list($cash_in_pay, $http_code_cash_in_pay) = self::CashInPay(Yii::$app->params['OM_CASHIN_PAY_URL'], Yii::$app->params['OM_AUTH_TOKEN_' . Yii::$app->params['ENV']], $token->token_type, $token->access_token, $phone, Yii::$app->params['OM_CHANNEL_USER_' . Yii::$app->params['ENV']], $montant, $description, $order_id, Yii::$app->params['OM_PIN_CODE_' . Yii::$app->params['ENV']], $cash_in_init->data->payToken, Yii::$app->params['OM_NOTIFY_URL_' . Yii::$app->params['ENV']]);
                $paiement = $cash_in_pay;
                if ($http_code_cash_in_pay == 200) {
                    $status = true;
                }
            }
        }
        return array($paiement, $status);
    }

    /**
     * @description Make CashInInit
     * @param $url
     * @param $auth_token
     * @param $token_type
     * @param $access_token
     * @return array
     */
    public static function CashInInit($url, $auth_token, $token_type, $access_token)
    {

        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_HEADER, false);
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            'X-AUTH-TOKEN: ' . $auth_token,
            'Content-Type: application/json',
            'Authorization: ' . $token_type . ' ' . $access_token,
        ));
        \curl_setopt($ch, \CURLOPT_URL, $url);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, true);
        $response = \curl_exec($ch);
        $http_code_cash_in_init = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        $cash_in_init = json_decode($response);

        return array($cash_in_init, $http_code_cash_in_init);
    }

    /**
     * @description Make CashInPay
     * @param $url
     * @param $auth_token
     * @param $token_type
     * @param $access_token
     * @param $subscriberUser
     * @param $channelUser
     * @param $amount
     * @param $description
     * @param $orderId
     * @param $pin
     * @param $payToken
     * @param $notifyUrl
     * @return array
     */
    public static function CashInPay($url, $auth_token, $token_type, $access_token, $subscriberUser, $channelUser, $amount, $description, $orderId, $pin, $payToken, $notifyUrl)
    {
        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, \CURLOPT_HEADER, false);
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, array(
            'X-AUTH-TOKEN: ' . $auth_token,
            'Content-Type: application/json',
            'Authorization: ' . $token_type . ' ' . $access_token,
        ));
        \curl_setopt($ch, \CURLOPT_URL, $url);
        \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, true);
        \curl_setopt($ch, \CURLOPT_POSTFIELDS, '{
                                                                "subscriberMsisdn": "' . $subscriberUser . '",
                                                                "channelUserMsisdn": "' . $channelUser . '",
                                                                "amount": "' . $amount . '",
                                                                "description": "' . $description . '",
                                                                "orderId": "' . $orderId . '",
                                                                "pin": "' . $pin . '",
                                                                "payToken": "' . $payToken . '",
                                                                "notifUrl": "' . $notifyUrl . '"
                                                            }');
        $response = \curl_exec($ch);
        $http_code_cash_in_pay = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);

        $cash_in_pay = json_decode($response);

        return array($cash_in_pay, $http_code_cash_in_pay);
    }

    /**
     * @return string
     */
    public static function getOmTokenUrl(): string
    {
        return self::$om_token_url;
    }

    /**
     * @param string $om_token_url
     */
    public static function setOmTokenUrl(string $om_token_url): void
    {
        self::$om_token_url = $om_token_url;
    }

    /**
     * @return string
     */
    public static function getAuthToken(): string
    {
        return self::$auth_token;
    }

    /**
     * @param string $auth_token
     */
    public static function setAuthToken(string $auth_token): void
    {
        self::$auth_token = $auth_token;
    }

    /**
     * @return string
     */
    public static function getOmMpInitUrl(): string
    {
        return self::$om_mp_init_url;
    }

    /**
     * @param string $om_mp_init_url
     */
    public static function setOmMpInitUrl(string $om_mp_init_url): void
    {
        self::$om_mp_init_url = $om_mp_init_url;
    }

    /**
     * @return string
     */
    public static function getOmMpPayUrl(): string
    {
        return self::$om_mp_pay_url;
    }

    /**
     * @param string $om_mp_pay_url
     */
    public static function setOmMpPayUrl(string $om_mp_pay_url): void
    {
        self::$om_mp_pay_url = $om_mp_pay_url;
    }

    /**
     * @return string
     */
    public static function getOmChannelUser(): string
    {
        return self::$om_channel_user;
    }

    /**
     * @param string $om_channel_user
     */
    public static function setOmChannelUser(string $om_channel_user): void
    {
        self::$om_channel_user = $om_channel_user;
    }

    /**
     * @return string
     */
    public static function getOmPinCode(): string
    {
        return self::$om_pin_code;
    }

    /**
     * @param string $om_pin_code
     */
    public static function setOmPinCode(string $om_pin_code): void
    {
        self::$om_pin_code = $om_pin_code;
    }

    /**
     * @return string
     */
    public static function getOmNotifyUrl(): string
    {
        return self::$om_notify_url;
    }

    /**
     * @param string $om_notify_url
     */
    public static function setOmNotifyUrl(string $om_notify_url): void
    {
        self::$om_notify_url = $om_notify_url;
    }



}
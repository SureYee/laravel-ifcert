<?php
namespace Sureyee\LaravelIfcert;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Sureyee\LaravelIfcert\Libs\AESHigher;
use Sureyee\LaravelIfcert\Exceptions\CertException;
use Sureyee\LaravelIfcert\Libs\PreBcrypt;

class Tools
{
    /**
     * @param $idCard
     * @return string
     * @throws CertException
     */
    public static function idCardHash($idCard)
    {
        if (!isset($idCard) || empty($idCard))
            throw new CertException('registrationNumber is empty', 1001);

        $aes = new AESHigher();
        $encText = $aes->encrypt($idCard);
        $hashstr = hash("sha256", $encText);

        return $hashstr;
    }

    /**
     * @param $name
     * @return string
     * @throws CertException
     */
    public static function nameHash($name)
    {
        return self::idCardHash($name);
    }

    /**
     * @param $phone
     * @param $salt
     * @return string
     * @throws CertException
     */
    public static function phoneHash($phone, $uuid)
    {
        // 检查 phone 是否为空
        if (empty($phone) || empty($uuid))
        {
            throw new CertException('phone or uuid is empty', 1001);
        }

        $hashstr = hash("sha256", $phone . $uuid);

        $phoneBase64 = base64_encode($hashstr);

        return (new PreBcrypt())->hash($phoneBase64);
    }

    /**
     * 获取uuid
     * @return string
     */
    public static function userUuid()
    {
        return (new PreBcrypt())->getSalt();
    }

    /**
     * @param $sourceCode
     * @param $tradeDate
     * @param $seqNum
     * @return string
     * @throws CertException
     */
    public static function batchNumber($sourceCode, $tradeDate = null, $seqNum = null)
    {
        $seqId = self::getSeqId();
        $tradeDate = $tradeDate ?? date('Ymd');
        $seqNum = $seqNum ?? 1;
        // 检查 sourceCode 是否为空
        if (empty($sourceCode) || empty($tradeDate) || empty($seqNum) || empty($seqId))
            throw new CertException('sourceCode or tradeDate or seqNum is empty', 1001);

        $batch_num = $sourceCode .'_'. $tradeDate .$seqNum .'_'. $seqId ;

        return $batch_num;
    }

    /**
     * apiKey加密
     * @param string $apiKey
     * @param string $sourceCode
     * @param string $version 版本号，如：v1.1-->110; v1.2-->120; v1.3-->130
     * @return array
     * @throws CertException
     */
    public static function getApiKey($apiKey, $sourceCode, $version)
    {
        // 检查 apiKey 是否为空
        if (!isset($apiKey) || empty($apiKey) || !isset($sourceCode) || empty($sourceCode) || !isset($version) || empty($version))
            throw new CertException('apiKey or sourceCode or version is empty', 1001);

        $vs = $version * 100;

        $currentTime = time() *1000;
        $numRand = rand(100000000,999999999);
        $nonce = dechex($numRand);
        $versionHex =  '0x'.dechex($vs);
        $str = $sourceCode . $versionHex . $apiKey . $currentTime . $nonce;
        $hashstr = hash("sha256", $str);

        return [
            'apiKey' => $hashstr,
            'timestamp' => $currentTime,
            'nonce'=> $nonce
        ];
    }

    /**
     * @param $timestamp
     * @return string
     */
    public static function checkCode($timestamp)
    {
        return md5($timestamp);
    }

    /**
     * 获取脱敏姓名
     * @param $name
     * @return string
     */
    public static function hiddenName($name)
    {
        $length = mb_strlen($name);
        if ($name > 3) {
            return mb_substr($name, 0, 2) . str_pad('', $length-2, '*');
        }
        return mb_substr($name, 0,1) . str_pad('', $length-1, '*');
    }

    /**
     * 脱敏证件号
     * @param $idCard
     * @return string
     */
    public static function hiddenIdCard($idCard)
    {
        return substr_replace($idCard, '****', -4);
    }

    /**
     * 脱敏手机号
     * @param $mobile
     * @return string
     */
    public static function hiddenPhone($mobile)
    {
        return substr_replace($mobile, '****', -4);
    }

    protected static function getSeqId()
    {
        if (Cache::has('cert_seq_id'))
            Cache::put('cert_seq_id', 1, Carbon::tomorrow());

        $seqId = Cache::get('cert_seq_id');

        Cache::increment('cert_seq_id');

        return str_pad($seqId, 5, STR_PAD_LEFT);
    }
}
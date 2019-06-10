<?php
namespace SureYee\LaravelIfcert;

use SureYee\LaravelIfcert\Libs\AESHigher;
use SureYee\LaravelIfcert\Exceptions\CertException;
use SureYee\LaravelIfcert\Libs\PreBcrypt;

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
     * @return array
     * @throws CertException
     */
    public static function phoneHash($phone)
    {
        // 检查 phone 是否为空
        if (!isset($phone) || empty($phone))
        {
            throw new CertException('phone is empty', 1001);
        }

        $bcry = new PreBcrypt();
        $salt = $bcry->getSalt();

        $hashstr = hash("sha256", $phone .''. $salt);

        $phoneBase64 = base64_encode($hashstr);

        $hash = $bcry->hash($phoneBase64);

        return [
            'phone' => $hash,
            'salt' => $salt
        ];
    }

    /**
     * 批次生成工具
     * sourceCode 平台编号
     * tradeDate交易时间
     * seqNum序列号
     */
    public static function batchNumber($sourceCode,$tradeDate,$seqNum,$seqId)
    {
        // 检查 sourceCode 是否为空
        if (!isset($sourceCode) || empty($sourceCode) || !isset($tradeDate) || empty($tradeDate) || !isset($seqNum) || empty($seqNum)
            ||!isset($seqId)||empty($seqId))
        {
            return array(
                'ret' => OPENAPI_ERROR_REQUIRED_PARAMETER_EMPTY,
                'msg' => 'sourceCode or tradeDate or seqNum is empty');
        }

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

    public static function checkCode($msgs)
    {
        return md5($msgs);
    }
}
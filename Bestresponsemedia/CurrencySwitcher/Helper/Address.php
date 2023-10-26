<?php

namespace BestResponseMedia\CurrencySwitcher\Helper;

use Exception;
use GeoIp2\Database\Reader;
use Magento\Customer\Helper\Address as CustomerAddressHelper;
use Magento\Directory\Model\Region;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Address
 * @package Mageplaza\GeoIP\Helper
 */
class Address extends AbstractHelper
{
    /**
     * @type DirectoryList
     */
    protected $_directoryList;

    /**
     * @type Resolver
     */
    protected $_localeResolver;

    /**
     * @type Region
     */
    protected $_regionModel;

    /**
     * @var CustomerAddressHelper
     */
    protected $addressHelper;

    /**
     * Address constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param Resolver $localeResolver
     * @param Region $regionModel
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        Resolver $localeResolver,
        Region $regionModel
    ) {

        $this->_directoryList  = $directoryList;
        $this->_localeResolver = $localeResolver;
        $this->_regionModel    = $regionModel;
        parent::__construct($context);
    }

    /***************************************** Maxmind Db GeoIp ******************************************************/
    /**
     * Check has library at path
     * @return bool|string
     * @throws FileSystemException
     */
    public function checkHasLibrary()
    {
        $path = $this->_directoryList->getPath('var');
        $folder   = scandir($path, true);

        $pathFile = $path . '/brm/GeoLite2-City.mmdb';
        if (!file_exists($pathFile)) {
            return false;
        }

        return $pathFile;
    }

    /**
     * @param null $storeId
     *
     * @return array
     */
    public function getGeoIpData()
    {
        try {
            $libPath = $this->checkHasLibrary();
            if ($libPath && class_exists('GeoIp2\Database\Reader')) {
                $geoIp  = new Reader($libPath, $this->getLocales());

                $record = $geoIp->city($this->getIpAddress());


                $geoIpData = [
                    'city'       => $record->city->name,
                    'country_id' => $record->country->isoCode,
                    'postcode'   => $record->postal->code,
                    'currency_code'   => $this->getCurrency($record->country->isoCode)
                ];

                if ($record->mostSpecificSubdivision) {
                    $code = $record->mostSpecificSubdivision->isoCode;
                    if ($regionId = $this->_regionModel->loadByCode($code, $record->country->isoCode)->getId()) {
                        $geoIpData['region_id'] = $regionId;
                    } else {
                        $geoIpData['region'] = $record->mostSpecificSubdivision->name;
                    }
                }
            } else {
                $geoIpData = [];
            }
        } catch (Exception $e) {
            // No Ip found in database
            $geoIpData = [];
        }

        return $geoIpData;
    }

    /**
     * Get IP
     * @return string
     */
    public function getIpAddress()
    {
        $fakeIP = $this->_request->getParam('fakeIp', false);
        if ($fakeIP) {
            return $fakeIP;
        }

        $server = $this->_getRequest()->getServer();

        $ip = $server['REMOTE_ADDR'];
        if (!empty($server['HTTP_CLIENT_IP'])) {
            $ip = $server['HTTP_CLIENT_IP'];
        } elseif (!empty($server['HTTP_X_FORWARDED_FOR'])) {
            $ip = $server['HTTP_X_FORWARDED_FOR'];
        }

        $ipArr = explode(',', $ip);

        return array_shift($ipArr);
    }

    function getLocationInfoByIp(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        $result  = array('country'=>'', 'city'=>'');
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
        if($ip_data && $ip_data->geoplugin_countryName != null){
            $result['country'] = $ip_data->geoplugin_countryCode;
            $result['city'] = $ip_data->geoplugin_city;
        }
        return $result;
    }

    /**
     * @return array
     */
    protected function getLocales()
    {
        $language = substr($this->_localeResolver->getLocale(), 0, 2) ?: 'en';

        $locales = [$language];
        if ($language !== 'en') {
            $locales[] = 'en';
        }

        return $locales;
    }

    public function getCurrency($code){
        $currencies = [
            'AD' => 'EUR',
            'AE' => 'AED',
            'AF' => 'AFN',
            'AG' => 'XCD',
            'AI' => 'XCD',
            'AL' => 'ALL',
            'AM' => 'AMD',
            'AN' => 'ANG',
            'AO' => 'AOA',
            'AQ' => '',
            'AR' => 'ARS',
            'AS' => 'USD',
            'AT' => 'EUR',
            'AU' => 'AUD',
            'AW' => 'AWG',
            'AZ' => 'AZN',
            'BA' => 'BAM',
            'BB' => 'BBD',
            'BD' => 'BDT',
            'BE' => 'EUR',
            'BF' => 'XOF',
            'BG' => 'BGN',
            'BH' => 'BHD',
            'BI' => 'BIF',
            'BJ' => 'XOF',
            'BL' => 'EUR',
            'BM' => 'BMD',
            'BN' => 'BND',
            'BO' => 'BOB',
            'BR' => 'BRL',
            'BS' => 'BSD',
            'BT' => 'BTN',
            'BV' => 'NOK',
            'BW' => 'BWP',
            'BY' => 'BYN',
            'BZ' => 'BZD',
            'CA' => 'CAD',
            'CC' => 'AUD',
            'CD' => 'CDF',
            'CF' => 'XAF',
            'CG' => 'CDF',
            'CH' => 'CHF',
            'CI' => 'XOF',
            'CK' => 'NZD',
            'CL' => 'CLP',
            'CM' => 'XAF',
            'CN' => 'CNY',
            'CO' => 'COP',
            'CR' => 'CRC',
            'CU' => 'CUP',
            'CV' => 'CVE',
            'CW' => 'ANG',
            'CX' => 'AUD',
            'CY' => 'EUR',
            'CZ' => 'CZK',
            'DE' => 'EUR',
            'DJ' => 'DJF',
            'DK' => 'DKK',
            'DM' => 'XCD',
            'DO' => 'DOP',
            'DZ' => 'DZD',
            'EC' => 'USD',
            'EE' => 'EUR',
            'EG' => 'EGP',
            'EH' => 'MAD',
            'ER' => 'ERN',
            'ES' => 'EUR',
            'ET' => 'ETB',
            'FI' => 'EUR',
            'FJ' => 'FJD',
            'FK' => 'FKP',
            'FM' => 'USD',
            'FO' => 'DKK',
            'FR' => 'EUR',
            'GA' => 'XAF',
            'GB' => 'GBP',
            'GD' => 'XCD',
            'GE' => 'GEL',
            'GF' => 'EUR',
            'GG' => 'GGP',
            'GH' => 'GHS',
            'GI' => 'GIP',
            'GL' => 'DKK',
            'GM' => 'GMD',
            'GN' => 'GNF',
            'GP' => 'EUR',
            'GQ' => 'XAF',
            'GR' => 'EUR',
            'GS' => 'GBP',
            'GT' => 'GTQ',
            'GU' => 'USD',
            'GW' => 'XOF',
            'GY' => 'GYD',
            'HK' => 'HKD',
            'HM' => 'AUD',
            'HN' => 'HNL',
            'HR' => 'HRK',
            'HT' => 'HTG',
            'HU' => 'HUF',
            'ID' => 'IDR',
            'IE' => 'EUR',
            'IL' => 'ILS',
            'IM' => 'IMP',
            'IN' => 'INR',
            'IO' => 'USD',
            'IQ' => 'IQD',
            'IR' => 'IRR',
            'IS' => 'ISK',
            'IT' => 'EUR',
            'JE' => 'JEP',
            'JM' => 'JMD',
            'JO' => 'JOD',
            'JP' => 'JPY',
            'KE' => 'KES',
            'KG' => 'KGS',
            'KH' => 'KHR',
            'KI' => 'AUD',
            'KM' => 'KMF',
            'KN' => 'XCD',
            'KP' => 'KPW',
            'KR' => 'KRW',
            'KW' => 'KWD',
            'KY' => 'KYD',
            'KZ' => 'KZT',
            'LA' => 'LAK',
            'LB' => 'LBP',
            'LC' => 'XCD',
            'LI' => 'CHF',
            'LK' => 'LKR',
            'LR' => 'LRD',
            'LS' => 'LSL',
            'LT' => 'EUR',
            'LU' => 'EUR',
            'LV' => 'EUR',
            'LY' => 'LYD',
            'MA' => 'MAD',
            'MC' => 'EUR',
            'MD' => 'MDL',
            'ME' => 'EUR',
            'MG' => 'MGA',
            'MH' => 'USD',
            'MK' => 'MKD',
            'ML' => 'XOF',
            'MM' => 'MMK',
            'MN' => 'MNT',
            'MO' => 'MOP',
            'MP' => 'USD',
            'MQ' => 'EUR',
            'MR' => 'MRO',
            'MS' => 'XCD',
            'MT' => 'EUR',
            'MU' => 'MUR',
            'MV' => 'MVR',
            'MW' => 'MWK',
            'MX' => 'MXN',
            'MY' => 'MYR',
            'MZ' => 'MZN',
            'NA' => 'NAD',
            'NC' => 'XPF',
            'NE' => 'XOF',
            'NF' => 'AUD',
            'NG' => 'NGN',
            'NI' => 'NIO',
            'NL' => 'EUR',
            'NO' => 'NOK',
            'NP' => 'NPR',
            'NR' => 'AUD',
            'NU' => 'NZD',
            'NZ' => 'NZD',
            'OM' => 'OMR',
            'PA' => 'PAB',
            'PE' => 'PEN',
            'PF' => 'XPF',
            'PG' => 'PGK',
            'PH' => 'PHP',
            'PK' => 'PKR',
            'PL' => 'PLN',
            'PM' => 'EUR',
            'PN' => 'GBP',
            'PR' => 'USD',
            'PS' => 'ILS',
            'PT' => 'EUR',
            'PW' => 'USD',
            'PY' => 'PYG',
            'QA' => 'QAR',
            'RE' => 'EUR',
            'RO' => 'RON',
            'RS' => 'RSD',
            'RU' => 'RUB',
            'RW' => 'RWF',
            'SA' => 'SAR',
            'SB' => 'SBD',
            'SC' => 'SCR',
            'SD' => 'SDG',
            'SE' => 'SEK',
            'SG' => 'SGD',
            'SH' => 'SHP',
            'SI' => 'EUR',
            'SJ' => 'NOK',
            'SK' => 'EUR',
            'SL' => 'SLL',
            'SM' => 'EUR',
            'SN' => 'XOF',
            'SO' => 'SOS',
            'SR' => 'SRD',
            'SS' => 'SSP',
            'ST' => 'STD',
            'SV' => 'USD',
            'SX' => 'ANG',
            'SY' => 'SYP',
            'SZ' => 'SZL',
            'TC' => 'USD',
            'TD' => 'XAF',
            'TF' => 'EUR',
            'TG' => 'XOF',
            'TH' => 'THB',
            'TJ' => 'TJS',
            'TK' => 'NZD',
            'TL' => 'USD',
            'TM' => 'TMT',
            'TN' => 'TND',
            'TO' => 'TOP',
            'TR' => 'TRY',
            'TT' => 'TTD',
            'TV' => 'AUD',
            'TW' => 'TWD',
            'TZ' => 'TZS',
            'UA' => 'UAH',
            'UG' => 'UGX',
            'US' => 'USD',
            'UY' => 'UYU',
            'UZ' => 'UZS',
            'VA' => 'EUR',
            'VC' => 'XCD',
            'VE' => 'VEF',
            'VG' => 'USD',
            'VI' => 'USD',
            'VN' => 'VND',
            'VU' => 'VUV',
            'WF' => 'XPF',
            'WS' => 'WST',
            'XK' => 'EUR',
            'YE' => 'YER',
            'YT' => 'EUR',
            'ZA' => 'ZAR',
            'ZM' => 'ZMK',
            'ZW' => 'ZWL',
        ];

        $currency = $currencies[$code];
        if($currency){
            return $currency;
        }

        return false;
    }
}

<?php

class BeezUp_Helper_Data extends Mage_Core_Helper_Abstract
{

    /*
     * Retrieve product image directory
     *
     * @return string
     */
    public function getImageDir()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product';
    }

    /*
     * Retrieve config path value
     *
     * @return string
     */
    public function getConfig($path = '')
    {
        if($path) {
            return Mage::getStoreConfig($path);
        }
        return '';
    }

    /*
     * Retrieve Remote Address
     *
     * @return string
     */
    public function getRemoteAddr()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /*
     * Retrieve tag
     *
     * @param string $tagName
     * @param string $content
     * @param bool $cdata
     * @return string
     */
    public function tag($tagName, $content, $cdata = 0)
    {
        $result = '<' . $tagName . '>';
        if ($cdata) $result .= '<![CDATA[';
        $result .= preg_replace('(^' . $this->__('No') . '$)', '', trim($content));
        if ($cdata) $result .= ']]>';
        $result .= '</' . $tagName . '>';
        return $result;
    }

    /*
     * Format currency
     *
     * @param float $v
     * @return string
     */
    public function currency($v)
    {
        return number_format($v, 2, '.', '');
    }

}
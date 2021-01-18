<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rss;

use DOMDocument;
use DOMElement;
use Seffeng\Rss\Contracts\Channel;
use Seffeng\Rss\Contracts\Item;

class Rss
{
    /**
     * @var string the Content-Type header for the response
     */
    protected $contentType = 'application/xml';
    /**
     * @var string the XML version
     */
    protected $version = '1.0';
    /**
     * @var string the XML encoding. If not set, it will use the value of [[Response::charset]].
     */
    protected $charset = 'UTF-8';
    /**
     * @var string the name of the root element. If set to false, null or is empty then no root tag should be added.
     */
    protected $rootTag = 'rss';
    /**
     *
     * @var string
     */
    protected $rssVersion = '2.0';

    /**
     *
     * @var array
     */
    protected $xmlns = [
        'atom' => 'http://www.w3.org/2005/Atom',
    ];

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param mixed $data
     * @return string
     */
    public function toRss(array $channelItems, array $items = [])
    {
        $dom = new DOMDocument($this->version, $this->charset);
        $root = new DOMElement($this->rootTag);
        $dom->appendChild($root);
        $this->setAttrubuteNS($dom);
        $root->setAttribute('version', $this->rssVersion);
        $channel = $this->createChannel($dom, $channelItems);
        $this->createItem($dom, $channel, $items);
        $output = $dom->saveXML();
        return $output;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param DOMDocument $dom
     * @param array $channelItems
     * @return DOMElement
     */
    protected function createChannel(DOMDocument $dom, array $channelItems)
    {
        $channel = (new Channel($dom))->load($channelItems);
        $dom->getElementsByTagName($this->rootTag)->item(0)->appendChild($channel);
        return $channel;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param DOMDocument $dom
     * @param DOMElement $channel
     * @param array $items
     */
    protected function createItem(DOMDocument $dom, DOMElement $channel, array $items)
    {
        $rssItem = new Item($dom, $channel);
        $rssItem->load($items);
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param DOMDocument $dom
     */
    protected function setAttrubuteNS(DOMDocument $dom)
    {
        if (count($this->xmlns) > 0) foreach ($this->xmlns as $key => $value) {
            $dom->createAttributeNS($value, $key . ':attr');
        }
    }
}

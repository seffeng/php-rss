<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rss\Contracts;

use DOMDocument;
use DOMElement;

class Item
{
    /**
     * 任何一个子节点都是可选的，但是 title 和 description 至少要被包含一个
     * @var string
     */
    public $title;
    /**
     * 任何一个子节点都是可选的，但是 title 和 description 至少要被包含一个
     * @var string
     */
    public $description;

    /**
     * 可选的子节点
     * @var string
     */
    public $link;
    /**
     * 可选的子节点
     * @var string
     */
    public $author;
    /**
     * 可选的子节点
     * [
     *     'domain' => 'http://',
     *     'text' => ''
     * ]
     * @var string|array
     */
    public $category;
    /**
     * 可选的子节点
     * @var string
     */
    public $comments;
    /**
     * 可选的子节点
     * ['url' => '', 'length' => '1024', 'type' => 'audio/mp3']
     * @var array
     */
    public $enclosure;
    /**
     * 可选的子节点
     * [
     *     'isPermaLink' => true,
     *     'url' => ''
     * ]
     * @var string|array
     */
    public $guid;
    /**
     * 可选的子节点
     * @var string
     */
    public $pubDate;
    /**
     * 可选的子节点
     * ['url' => '', 'text' => '']
     * @var array
     */
    public $source;

    /**
     *
     * @var DOMDocument
     */
    protected $document;

    /**
     *
     * @var DOMElement
     */
    protected $channel;

    /**
     *
     * @var array
     */
    protected $keys;

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param array $data
     * @return array
     */
    public function __construct(DOMDocument $dom, DOMElement $channel)
    {
        $this->document = $dom;
        $this->channel = $channel;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param array $items
     */
    public function load(array $items)
    {
        if ($items) foreach($items as $value){
            $item = $this->document->createElement('item');
            foreach ($value as $k => $v) {
                $node = $this->createElement($k, $v);
                if ($node) {
                    $item->appendChild($node);
                }
            }
            $this->channel->appendChild($item);
        }
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param  string $key
     * @param  string|array $value
     * @return DOMElement|NULL
     */
    protected function createElement($key, $value)
    {
        if (is_null($this->keys)) {
            $this->keys = array_keys(json_decode(json_encode($this), true));
        }
        if (in_array($key, $this->keys)) {
            if ($key === 'description' || $key === 'category') {
                $element = $this->document->createElement($key);
                $element->appendChild($this->document->createCDATASection($value));
                return $element;
            } elseif ($key === 'source') {
                $element = $this->document->createElement($key);
                if (is_array($value)) {
                    if (isset($value['text']) && $value['text']) {
                        $element->appendChild($this->document->createCDATASection($value['text']));
                    }
                    if (isset($value['url']) && $value['url']) {
                        $attr = new \DOMAttr('url', $value['url']);
                        $element->setAttributeNodeNS($attr);
                    }
                    return $element;
                } elseif (is_string($value)) {
                    $element->appendChild($this->document->createCDATASection($value));
                    return $element;
                }
            } elseif ($key === 'pubDate' && $value) {
                $value = date(DATE_RFC2822, is_numeric($value) ? $value : strtotime($value));
            }
            $element = $this->document->createElement($key);
            $element->appendChild($this->document->createTextNode($value));
            return $element;
        }
        return null;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param DOMElement $element
     * @param array $items
     */
    protected function setAttrubuteNS(DOMElement $element, array $items)
    {
        if ($items) foreach ($items as $key => $value) {
            $attr = new \DOMAttr($key, $value);
            $element->setAttributeNodeNS($attr);
        }
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $title
     * @return static
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $link
     * @return static
     */
    public function setLink(string $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $description
     * @return static
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $author
     * @return static
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param mixed $category
     * @return static
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $comments
     * @return static
     */
    public function setComments(string $comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param array $enclosure
     * @return static
     */
    public function setEnclosure(array $enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return array
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param mixed $guid
     * @return static
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return mixed
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $pubDate
     * @return static
     */
    public function setPubDate(string $pubDate)
    {
        $this->pubDate = $pubDate;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param array $cloud
     * @return static
     */
    public function setSource(array $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }
}

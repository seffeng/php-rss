<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rss\Contracts;

use DOMDocument;
use DOMElement;

class Channel
{
    /**
     * 必须的子节点
     * @var string
     */
    public $title;
    /**
     * 必须的子节点
     * @var string
     */
    public $link;
    /**
     * 必须的子节点
     * @var string
     */
    public $description;

    /**
     * 可选的子节点
     * @var string
     */
    public $language;
    /**
     * 可选的子节点
     * @var string
     */
    public $copyright;
    /**
     * 可选的子节点
     * @var string
     */
    public $managingEditor;
    /**
     * 可选的子节点
     * @var string
     */
    public $webMaster;
    /**
     * 可选的子节点
     * @var string
     */
    public $pubDate;
    /**
     * 可选的子节点
     * @var string
     */
    public $lastBuildDate;
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
    public $generator;
    /**
     * 可选的子节点
     * @var string
     */
    public $docs;
    /**
     * 可选的子节点
     * [
     *     'domain' => '必须。cloud程序所在机器的域名或IP地址',
     *     'port' => '必须。访问cloud程序所通过的端口',
     *     'path' => '必须。程序所在路径，不一定需要是真实路径',
     *     'registerProcedure' => '必须。 注册的可提供的服务或过程',
     *     'protocol' => '必须。协议，http-post、xml-rpc、soap之一',
     * ]
     * @var array
     */
    public $cloud;
    /**
     * 可选的子节点
     * @var integer
     */
    public $ttl;
    /**
     * 可选的子节点
     * [
     *     'url' => '必须。表示该频道的Gif、Jpeg或Png图像的Url',
     *     'title' => '必须。图象描述。当频道以Html呈现时用作<img>标签的alt属性',
     *     'link' => '必须。站点Url，当频道以Html呈现时，该图像会链接到此',
     *     'width' => '可选。 数字，图象的像素宽度，最大值144，默认值为88',
     *     'height' => '可选。 数字，图象的像素高度，最大值400，默认值为31',
     *     'description' => '可选。 当频道以Html呈现时，作为围绕着该图像形成的链接Tag的title属性'
     * ]
     * @var array
     */
    public $image;
    /**
     * 可选的子节点
     * @var string
     */
    public $rating;
    /**
     * 可选的子节点
     * [
     *     'title' => '必须。输入框中Submit按钮上的文字',
     *     'description' => '必须。输入框的解释'
     *     'name' => '必须。输入框对象的名字',
     *     'link' => '可选。 输入框提交的Url',
     * ]
     * @var array
     */
    public $textInput;
    /**
     * 可选的子节点
     * @var string
     */
    public $skipHours;
    /**
     * 可选的子节点
     * @var string
     */
    public $skipDays;

    /**
     * [
     *     'atom:link' => [
     *         'href' => 'https://www.wuhuawu.com',
     *         'rel' => 'self',
     *         'type' => 'application/rss+xml'
     *     ],
     *     'atom:link2' => [
     *         'href' => 'https://www.1kmi.com',
     *         'rel' => 'self',
     *         'type' => 'application/rss+xml'
     *     ]
     * ]
     * @var array
     */
    public $xmlns = [];

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
     * @param DOMDocument $dom
     */
    public function __construct(DOMDocument $dom)
    {
        $this->document = $dom;
        $this->channel = $this->document->createElement('channel');
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param array $items
     * @throws \Exception
     * @throws \Exception
     * @return DOMElement
     */
    public function load(array $items)
    {
        try {
            $keyItems = array_keys($items);
            if (!array_intersect(['title', 'link', 'description'], $keyItems) === ['title', 'link', 'description']) {
                throw new \Exception('title, link, description must be not empty!');
            }
            foreach ($items as $key => $value) {
                $node = $this->createElement($key, $value);
                if ($node) {
                    $this->channel->appendChild($node);
                }
            }
            return $this->channel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param string $key
     * @param string|array $value
     * @return DOMElement|NULL
     */
    protected function createElement($key, $value)
    {
        if (is_null($this->keys)) {
            $this->keys = array_keys(json_decode(json_encode($this), true));
        }
        if (in_array($key, $this->keys)) {
            if ($key === 'xmlns') {
                if (is_array($value)) foreach ($value as $k => $v) {
                    if (is_array($v)) {
                        $element = $this->document->createElement($k);
                        $this->setAttrubuteNS($element, $v);
                        $this->channel->appendChild($element);
                    }
                }
                return null;
            } elseif (($key === 'pubDate' || $key === 'lastBuildDate') && $value) {
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
     * @param string $language
     * @return static
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $copyright
     * @return static
     */
    public function setCopyright(string $copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $managingEditor
     * @return static
     */
    public function setManagingEditor(string $managingEditor)
    {
        $this->managingEditor = $managingEditor;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getManagingEditor()
    {
        return $this->managingEditor;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $webMaster
     * @return static
     */
    public function setWebMaster(string $webMaster)
    {
        $this->webMaster = $webMaster;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getWebMaster()
    {
        return $this->webMaster;
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
     * @param string $lastBuildDate
     * @return static
     */
    public function setLastBuildDate(string $lastBuildDate)
    {
        $this->lastBuildDate = $lastBuildDate;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getLastBuildDate()
    {
        return $this->lastBuildDate;
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
     * @param string $generator
     * @return static
     */
    public function setGenerator(string $generator)
    {
        $this->generator = $generator;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $docs
     * @return static
     */
    public function setDocs(string $docs)
    {
        $this->docs = $docs;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param array $cloud
     * @return static
     */
    public function setCloud(array $cloud)
    {
        $this->cloud = $cloud;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return array
     */
    public function getCloud()
    {
        return $this->cloud;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param integer $ttl
     * @return static
     */
    public function setTtl(int $ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param array $image
     * @return static
     */
    public function setImage(array $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return array
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $rating
     * @return static
     */
    public function setRating(array $rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param array $textInput
     * @return static
     */
    public function setTextInput(array $textInput)
    {
        $this->textInput = $textInput;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return array
     */
    public function getTextInput()
    {
        return $this->textInput;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $skipHours
     * @return static
     */
    public function setSkipHours(array $skipHours)
    {
        $this->skipHours = $skipHours;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getSkipHours()
    {
        return $this->skipHours;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @param string $skipDays
     * @return static
     */
    public function setSkipDays(string $skipDays)
    {
        $this->skipDays = $skipDays;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月15日
     * @return string
     */
    public function getSkipDays()
    {
        return $this->skipDays;
    }
}

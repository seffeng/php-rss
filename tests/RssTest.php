<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2021 seffeng
 */
namespace Seffeng\Rss\Tests;

use PHPUnit\Framework\TestCase;
use Seffeng\Rss\Rss;

class RssTest extends TestCase
{
    /**
     *
     * @author zxf
     * @date   2021年1月18日
     * @param mixed $data
     * @return string
     */
    public function testRss()
    {
        try {
            $channel = [
                'title' => '频道名称。人们就是这样引用您的服务的。如果您的HTML网站包含与RSS文件相同的信息，则频道标题应与网站标题相同。',
                'link' => 'https://www.wuhuawu.com/rss',
                'description' => '描述频道的词组或句子。',
                'copyright' => 'copyright',
                'language' => 'zh-cn',
                'ttl' => 30,
                'generator' => '峰雪幽忧',
                'xmlns' => [
                    'atom:link' => [
                        'href' => 'https://www.wuhuawu.com',
                        'rel' => 'self',
                        'type' => 'application/rss+xml'
                    ],
                    'atom:link2' => [
                        'href' => 'https://www.1kmi.com',
                        'rel' => 'self',
                        'type' => 'application/rss+xml'
                    ]
                ]
            ];
            $item = [
                ['title' => 'composer 本地包引入', 'link' => 'https://www.wuhuawu.com/view/150', 'source' => ['text' => '链接来源', 'url' => 'https://www.wuhuawu.com'], 'pubDate' => 1665555555, 'guid' => 'https://www.wuhuawu.com/view/150'],
                ['title' => 'iptables配置模版', 'link' => 'https://www.wuhuawu.com/view/116', 'category' => 'linux', 'description' => '<span style="color: #f00">iptables配置模版</span><br /><pre>iptables -A INPUT -p tcp --dport 80 -j ACCEPT</pre>', 'source' => ['text' => '来源2', 'url' => 'https://www.wuhuawu.com/rss'], 'pubDate' => '2020-11-13 11:46', 'guid' => 'https://www.wuhuawu.com/view/116'],
            ];
            $output = (new Rss())->toRss($channel, $item);
            print_r($output);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

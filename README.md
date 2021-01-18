## Rss -- 生成 Rss 结构

### 安装

```shell
# 安装
$ composer require seffeng/rss
```

### 目录说明

```
└─src
    ├─Rss.php
    └─Contracts
        Channel.php
        Item.php
```

### 示例

```php
/**
 * TestController.php
 * 示例
 */
namespace App\Http\Controllers;

use Seffeng\Rss\Rss;

class TestController extends Controller
{
    public function index()
    {
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
                    'href' => 'https://www.wuhuawu.com',
                    'rel' => 'self',
                    'type' => 'application/rss+xml'
                ],
            ],
        ];
        $item = [
            ['title' => 'composer 本地包引入', 'link' => 'https://www.wuhuawu.com/view/150', 'source' => ['text' => '链接来源', 'url' => 'https://www.wuhuawu.com'], 'pubDate' => 1665555555, 'guid' => 'https://www.wuhuawu.com/view/150'],
            ['title' => 'iptables配置模版', 'link' => 'https://www.wuhuawu.com/view/116', 'category' => 'linux', 'description' => '<span style="color: #f00">iptables配置模版</span><br /><pre>iptables -A INPUT -p tcp --dport 80 -j ACCEPT</pre>', 'source' => ['text' => '来源2', 'url' => 'https://www.wuhuawu.com/rss'], 'pubDate' => '2020-11-13 11:46', 'guid' => 'https://www.wuhuawu.com/view/116'],
        ];
        header('content-type:text/xml');
        print_r((new Rss())->toRss($channel, $item));exit;
    }
}
```

### 备注

无


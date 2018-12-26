Apollo
===============

### Installing

```shell
php composer.phar require kuainiu/apollo
```

### Examples

```php
$apollo = new Apollo('https://test.knjk.com/openapi/v1/');

$apollo->token      = 'security key';
$apollo->user       = 'apollo';
$apollo->envs       = 'DEV';
$apollo->apps       = 'tq-deposit';
$apollo->clusters   = 'default';
$apollo->namespaces = 'application';

// 创建
$response = $apollo->create('key', 'value', 'comment');

// 更新
$response = $apollo->update('key', 'value', 'comment');

// 删除
$response = $apollo->update('key');

// 发布
$response = $apollo->releases('title', 'comment');
```
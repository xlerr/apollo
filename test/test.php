<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

use apollo\Apollo;

/**
 * $apollo = new Apollo('http://www.domain.com/openapi/v1/', [
 * 'timeout'         => 0,
 * 'allow_redirects' => false,
 * 'proxy'           => '192.168.16.1:10',
 * ]);
 */
$apollo = new Apollo('https://test.knjk.com/openapi/v1/');

$apollo->token      = 'security key';
$apollo->user       = 'apollo';
$apollo->envs       = 'DEV';
$apollo->apps       = 'tq-deposit';
$apollo->clusters   = 'default';
$apollo->namespaces = 'application';

$apollo->create('test', 1, 'the tests');
$apollo->update('test', 2, 'the tests for update');

/** @var \Psr\Http\Message\ResponseInterface $response */
$response = $apollo->update('test', '!@#$%^%^&&**()', 'the tests for update');

//$apollo->update('test', json_encode(['item1' => 'a','item2' => 'b']), 'the tests for update');
//$apollo->delete('test');

$response = $apollo->releases('first releases', 'releases for test');

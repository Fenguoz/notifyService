<?php

declare(strict_types=1);

use App\Middleware\VerifyAdminTokenMiddleware;
use App\Middleware\VerifyUserTokenMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::get('/topic', 'App\Controller\TopicController@list');
Router::get('/get.notify.list', [\App\Controller\NotifyController::class, 'list']);

//用户权限
Router::addGroup(
    '', function () {
        //订阅
        Router::get('/get.my.subscription', [\App\Controller\SubscriptionController::class, 'my']);
        Router::post('/post.subscribing', [\App\Controller\SubscriptionController::class, 'subscribing']);
        Router::delete('/del.subcription', [\App\Controller\SubscriptionController::class, 'delete']);

        //消息
        Router::post('/post.usersend', [\App\Controller\NotifyController::class, 'userSend']);
        Router::patch('/patch.clientid', [\App\Controller\NotifyController::class, 'updateClientId']);
    },
    ['middleware' => [VerifyUserTokenMiddleware::class]]
);

//管理员权限
Router::addGroup(
    '', function () {
        Router::post('/post.adminsend', [\App\Controller\NotifyController::class, 'adminSend']);
    },
    ['middleware' => [VerifyAdminTokenMiddleware::class]]
);

Router::addServer('ws',function(){
    Router::get('/', 'App\Controller\WebSocketController');
});

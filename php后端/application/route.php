<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::post('login','adminapi/login/login',[],['id'=>'\d+']);
Route::resource('users','adminapi/user',[],['id'=>'\d+']);
Route::get('users/:id', 'adminapi/user/getuser',[],['id'=>'\d+']);
Route::put('users/:id', 'adminapi/user/edit',[],['id'=>'\d+']);
Route::put('users/:uid/state/:type', "adminapi/user/state",[],'');
Route::delete('users/:id','adminapi/user/delete',[],['id'=>'\d+']);
Route::get('rights/:type', 'adminapi/right/index',[]);
Route::get('menus','adminapi/menu/index',[]);
Route::post('roles', 'adminapi/role/save',[]);
Route::get('roles/:id','adminapi/role/index',[]);
Route::get('roles','adminapi/role/index',[]);
Route::put('roles/:id','adminapi/role/edit',[],['id'=>'\d+']);
Route::delete('roles/:id','adminapi/role/delete',[],['id'=>'\d+']);
Route::post('roles/:roleId/rights', 'adminapi/role/role',[]);
Route::delete('roles/:roleId/rights/:rightId', 'adminapi/role/roledelete',[]);

// 商品分类的
Route::put('categories/:id', 'adminapi/category/edit', [], ['id' => '\d+']);
Route::get('categories/:id', 'adminapi/category/read', [], ['id' => '\d+']);
Route::get('categories', 'adminapi/category/index', []);
Route::post('categories', 'adminapi/category/save', []);
Route::delete('categories/:id', 'adminapi/category/delete', []);
// 商品列表
Route::put('goods/:id', 'adminapi/goods/edit', [], ['id' => '\d+']);
Route::get('goods/:id', 'adminapi/goods/read', [], ['id' => '\d+']);
Route::get('goods', 'adminapi/goods/index', []);
Route::post('goods', 'adminapi/goods/save', []);
Route::delete('goods/:id', 'adminapi/goods/delete', []);
Route::post('upload', 'adminapi/goods/upload', []);

// 订单
Route::get('orders/:id', 'adminapi/order/read', [], ['id' => '\d+']);
Route::get('orders', 'adminapi/order/index', []);
Route::put('orders/:id', 'adminapi/order/edit', [], ['id' => '\d+']);

// 统计图
Route::get('reports', 'adminapi/report/index',[]);




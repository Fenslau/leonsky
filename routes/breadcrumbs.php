<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route('home'));
});


Breadcrumbs::for('articles', function ($trail) {
    $trail->parent('home');
    $trail->push('Статьи', route('articles.index'));
});

Breadcrumbs::for('article', function ($trail, $article) {
    $trail->parent('articles');
    $trail->push($article->title, route('articles.show', $article->slug));
});

Breadcrumbs::for('search', function ($trail) {
    $trail->parent('home');
    $trail->push('Поиск', route('search'));
});

Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Пользователи', route('users.index'));
});

Breadcrumbs::for('user', function ($trail, $user) {
    $trail->parent('users');
    $trail->push($user->name, route('users.show', $user->id));
});

Breadcrumbs::for('city.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Города', route('city.index'));
});

Breadcrumbs::for('city.show', function ($trail, $city) {
    $trail->parent('city.index');
    $trail->push($city->name, route('city.show', $city->id));
});

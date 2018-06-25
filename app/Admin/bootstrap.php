<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Grid\Column;
use App\Admin\Extensions\Info;
use App\Admin\Extensions\Neditor;
use Encore\Admin\Form;

Form::extend('editor', Neditor::class);
Column::extend('info', Info::class);

app('view')->prependNamespace('admin', resource_path('views/admin'));
Encore\Admin\Form::forget(['map']);

Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    $navbar->right(new \App\Admin\Extensions\Nav\Links());
});

<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class Neditor extends Field
{
    protected $view = 'admin.neditor';

    protected static $css = [
        // '/vendor/wangEditor-3.0.9/release/wangEditor.min.css',
    ];

    protected static $js = [
        '/neditor/neditor.config.js',
        '/neditor/neditor.all.min.js',
        '/neditor/i18n/zh-cn/zh-cn.js',
    ];

    public function render()
    {
        $name = $this->formatName($this->column);

        $this->script = <<<EOT
UE.delEditor('{$this->id}');
var ue = UE.getEditor('{$this->id}');
$(document).on('pjax:start', function() {
    UE.delEditor('{$this->id}');
    var ue = UE.getEditor('{$this->id}');
});
EOT;
        return parent::render();
    }
}

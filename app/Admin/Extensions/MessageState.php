<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class MessageState extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['state'=>'_state_']);
        return <<<EOT
$('input:radio.message-state').change(function () {
    var url = "$url".replace('_state_', $(this).val());
    $.pjax({container:'#pjax-container', url: url });
});
EOT;
    }
    public function render()
    {
        Admin::script($this->script());
        $options = [
            '9'   => '全部',
            '0'     => '未回复',
            '1'     => '已回复',
        ];
        return view('admin.tools.message', compact('options'));
    }
}
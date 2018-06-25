<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class ComplaintSataus extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['status'=>'_status_']);
        return <<<EOT
$('input:radio.complaint-status').change(function () {
    var url = "$url".replace('_status_', $(this).val());
    $.pjax({container:'#pjax-container', url: url });
});
EOT;
    }
    public function render()
    {
        Admin::script($this->script());
        $options = [
            '9'   => '全部',
            '0'     => '未解决',
            '1'     => '已解决',
        ];
        return view('admin.tools.complaint', compact('options'));
    }
}
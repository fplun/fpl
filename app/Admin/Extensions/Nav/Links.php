<?php

namespace App\Admin\Extensions\Nav;

use App\Models\Complaint;
use App\Models\Message;

class Links
{
    public function __toString()
    {
        $dispute = Complaint::where('status',0)->count();
        $message = Message::where('state',0)->count();
        $sum = $dispute + $message;
        if ($sum == 0){
            $sum = '';
        }
        return <<<HTML

<li class="dropdown notifications-menu">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
  <i class="fa fa-bell-o"></i>
  <span class="label label-warning">$sum</span>
</a>
<ul class="dropdown-menu">
  <li class="header">你有 $sum 条新通知</li>
  <li>
    <!-- inner menu: contains the actual data -->
    <ul class="menu">
      <li>
        <a href="complaint?&status=0">
          <i class="fa fa-users text-red"></i> $dispute 条未处理投诉
        </a>
      </li>

      <li>
        <a href="message?&state=0">
          <i class="fa fa-commenting-o text-green"></i> $message 条未处理留言
        </a>
      </li>
    </ul>
  </li>
  <li class="footer"><a href="#">View all</a></li>
</ul>
</li>
HTML;
    }
}
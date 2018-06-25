## 安装步骤

1 php artisan vendor:publish --provider="Rzy\Smscode\SmsServieProvider" 发布sms配置文件
2 php artisan migrate 迁移数据表
3 SmsTrait 具体发送逻辑
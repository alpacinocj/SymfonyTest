该目录下为自定义错误页面

开发环境下默认会抛出带有详细错误信息的错误页面

自定义错误页面仅生产环境可见

如果需要在开发环境下调试自定义错误页面

参考: http://www.symfonychina.com/doc/current/controller/error_pages.html

在app/config/routing_dev.yml增加配置项: 

_errors:
    resource: '@TwigBundle/Resources/config/routing/errors.xml'
    prefix: /_error

然后即可通过如下路由预览错误页面: 

http://localhost/app_dev.php/_error/{statusCode}
http://localhost/app_dev.php/_error/{statusCode}.{format}
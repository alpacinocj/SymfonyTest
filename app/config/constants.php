<?php
// 系统常量配置

// 系统运行环境 (prod, dev, test)
defined('APP_ENV') or define('APP_ENV', 'dev');
// 是否开发环境
defined('APP_ENV_DEV') or define('APP_ENV_DEV', APP_ENV === 'dev');
// 是否生产环境
defined('APP_ENV_PROD') or define('APP_ENV_PROD', APP_ENV === 'prod');
// 是否测试环境
defined('APP_ENV_TEST') or define('APP_ENV_TEST', APP_ENV === 'test');
// 是否开启DEBUG
defined('APP_DEBUG') or define('APP_DEBUG', !APP_ENV_PROD);
// 是否开启OAuth授权
defined('APP_OAUTH_SWITCH') or define('APP_OAUTH_SWITCH', false);
// 定义项目根目录
defined('APP_ROOT_PATH') or define('APP_ROOT_PATH', __DIR__ . '/../../');
// 定义项目缓存目录
defined('APP_CACHE_PATH') or define('APP_CACHE_PATH', APP_ROOT_PATH . 'app/cache/' . APP_ENV . '/');
// 定义项目配置目录
defined('APP_CONFIG_PATH') or define('APP_CONFIG_PATH', APP_ROOT_PATH . 'app/config/');
// 定义项目日志目录
defined('APP_LOGS_PATH') or define('APP_LOGS_PATH', APP_ROOT_PATH . 'app/logs/');
// 定义项目WEB目录
defined('APP_WEB_PATH') or define('APP_WEB_PATH', APP_ROOT_PATH . 'web/');
// 定义项目资源文件目录
defined('APP_ASSETS_PATH') or define('APP_ASSETS_PATH', APP_WEB_PATH . 'assets/');
// 定义项目编译目录
defined('APP_COMPILED_PATH') or define('APP_COMPILED_PATH', APP_WEB_PATH . 'compiled/');
// 定义文件上传目录
defined('APP_UPLOADS_PATH') or define('APP_UPLOADS_PATH', APP_WEB_PATH . 'uploads/');

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
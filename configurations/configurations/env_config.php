<?php

if (!class_exists('CLSWP_EnvConfig')) :
    class CLSWP_EnvConfig
    {

        private const ENV_CONFIG_FILE_NAME = 'configuration.json';

        public static function getEnvConfigs(): array
        {
            $envConfigArr = [];
            $envConfigsUrl = plugin_dir_path(__FILE__)  . self::ENV_CONFIG_FILE_NAME;
            // error_log(print_r('getEnvConfigs: ' . $envConfigsUrl, true));
            $fileContentStr = file_get_contents($envConfigsUrl);
            if ($fileContentStr) {
                $envConfigArr = json_decode($fileContentStr, true);
                // error_log(print_r('getEnvConfigs: ' . json_encode($envConfigArr), true));
            }
            return $envConfigArr;
        }
        public static function getEnvConfig($envVarName): string
        {
            $envConfigs = self::getEnvConfigs();
            return strval(empty($envConfigs) ? '' : self::getEnvConfigs()[$envVarName]);
        }
    }
endif;

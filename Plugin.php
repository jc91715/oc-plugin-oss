<?php namespace Jc91715\Oss;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

use Storage;
use OSS\OssClient;
use League\Flysystem\Filesystem;
use ApolloPY\Flysystem\AliyunOss\Plugins\PutFile;
use ApolloPY\Flysystem\AliyunOss\Plugins\SignedDownloadUrl;
use Jc91715\Oss\Models\Settings;
use ApolloPY\Flysystem\AliyunOss;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'oss',
            'description' => 'aliyun oss',
            'author'      => 'jc91715',
            'icon'        => 'icon-leaf'
        ];
    }


    public function register()
    {
        $this->app->register(\ApolloPY\Flysystem\AliyunOss\AliyunOssServiceProvider::class);

    }

    public function boot()
    {
        Storage::extend('oss', function ($app, $config) {
            $accessId = Settings::get('access_id');
            $accessKey = Settings::get('access_key');
            $endPoint = Settings::get('endpoint');
            $bucket = Settings::get('bucket');

            $prefix = null;
            if (Settings::get('prefix')) {
                $prefix = Settings::get('prefix');
            }

            $client = new OssClient($accessId, $accessKey, $endPoint);
            $adapter = new AliyunOss\AliyunOssAdapter($client, $bucket, $prefix);

            $filesystem = new Filesystem($adapter);
            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new SignedDownloadUrl());

            return $filesystem;
        });
    }

    public function registerSettings()
    {
        return [
            'location' => [
                'label'       => 'OSS',
                'description' => '阿里云oss配置.',
                'category'    => SettingsManager::CATEGORY_SYSTEM,
                'icon'        => 'icon-globe',
                'class'       => 'Jc91715\Oss\Models\Settings',
                'order'       => 500,
                'keywords'    => 'oss'
            ]
        ];
    }

}

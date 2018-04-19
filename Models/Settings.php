<?php namespace Jc91715\Oss\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'jc91715_oss_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}

<?php namespace GregoryBrown\MediaManagerFix;

use Backend;
use System\Classes\PluginBase;
use Event;
use System\Classes\MediaLibrary;

/**
 * MediaManagerFix Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen('media.file.upload', function ($widget, $filePath, $uploadedFile) {
            $original_name  = $uploadedFile->getClientOriginalName();
            $ext     = pathinfo($original_name, PATHINFO_EXTENSION);
            $original_name_no_ext = pathinfo($original_name, PATHINFO_FILENAME);
            $new_name = str_slug($original_name_no_ext, '-') . '.' . $ext;

            if ($new_name == $original_name) {
                return;
            }

            $newPath = str_replace($original_name, $new_name, $filePath);
            MediaLibrary::instance()->moveFile($filePath, $newPath);
        });
    }

}

<?php

namespace LaravelAngular\Generators;

use File;

class Utils
{
    /**
     * Parses config file and extract data. If $hasTest is set to true, it extracts data relative to test files
     * @param string  $type      type of configuration to parse and extract; can be "components", "directives", "config", "dialogs", "filters", "pages" or "services"
     * @param boolean $hasTest   whether the type specified has a test file
     * @return array [
     *    ['path']        string    base path where to store files
     *    ['suffix']      string    suffix to append to each created files of the type specified in params
     *    ['auto_import'] boolean   common configuration for all files; not used by dialogs and pages
     *    ['use_mix']     boolean   common configuration for all files; used only for components
     * ]
     */
    public static function getConfig($type, $hasTest)
    {
        $config = config('generators');

        $result = [
            'path' => $config['source']['root'].'/'.$config['source'][$type],
            'suffix' => $config['suffix'][$type],
            'auto_import' => $config['misc']['auto_import'],
            'use_mix' => $config['misc']['use_mix'],
        ];

        if($hasTest) {
            $result['enable_test'] = $config['tests']['enable'][$type];
            $result['spec_path'] = $config['tests']['source']['root'].'/'.$config['tests']['source'][$type];
        }

        return $result;
    }

    /**
     * Create files for specified type. It uses blade templates specified in input array (see below). 
     * It first check for base directory existence; if not present will create it; then all needed files will be created. 
     * If there are more than one file, files should be put in a single directory and $checkOnlyDir should be set to true.
     * If files exist (or containing directory in case of two or more files), method will stop and false will be returned.
     * 
     * @param array   $files           templates and test file in the following form:
     * [
     *     ['templates']  array   templates to be parsed with blade file, variables for blade, path to be stored, name of the final generated file
     *         ['template']  string   filename (namespaced or not) to put in view() method; 
     *                                i.e.: passing 'MyNamespace::component.template' will be used in view() method.
     *                                      This method will look for component.template.blade.php in path specified by MyNamespace.
     *                                      @see https://laravel.com/api/5.6/Illuminate/Contracts/View/Factory.html for details
     *         ['vars']      array    variables to pass to blade template.
     *                                @see https://laravel.com/docs/5.6/views#passing-data-to-views
     *         ['path']      string   base path to store rendered template. If there are more than one file, path should contain also the sub directory where to store files
     *                                i.e.: for components will be generated three files.
     *                                      If base path of components is "angular/app/components", final path passed to method should be "angular/app/components/myComponentSubFolderName"
     *         ['name']      string   full name of the file to be generated (including prefixes and suffixes)
     * ]
     * @param boolean $hasTest        whether should be also created a test file
     * @param boolean $checkOnlyDir   see in method description for details
     * @return boolean true if files are generated successfully, false if one of them (or base directory in case $checkOnlyDir is true) already exist
     */
    public static function createFiles($files, $hasTest, $checkOnlyDir)
    {
        // TODO: allow user to specify custom template

        if(!File::exists($files['templates'][0]['path'])) {
            File::makeDirectory($files['templates'][0]['path'], 0775, true);
        } else {
            if($checkOnlyDir) { // if true, determines if files to be created exist only checking if their directory exists
                return false;
            }
        }

        foreach($files['templates'] as $file) {
            $template = view($file['template'], $file['vars'])->render();
            
            if(!File::exists($file['path'].'/'.$file['name'])) {
                File::put($file['path'].'/'.$file['name'], $template);
            } else {
                return false;
            }
        }
        
        if($hasTest) {
            $spec_template = view($files['spec']['template'], $files['spec']['vars'])->render();
            
            if(! File::exists($files['spec']['path'])) {
                File::makeDirectory($files['spec']['path'], 0775, true);
            }

            File::put($files['spec']['path'].'/'.$files['spec']['name'], $spec_template);
        }

        return true;
    }

    /**
     * Auto-import in index files. If index file hasn't been generated before, it's created based on "angular_module" preferences in config file.
     * This method uses blade templates (stored in Console/Commands/Stubs/Index) to generate and update index files
     * 
     * @param string $type   can be "components", "directives", "config", "dialogs", "filters", "pages" or "services"
     * @param string $name   name (specified by the user) of file to import
     */
    public static function import($type, $name, $suffix, $reimport = false)
    {
        $index_path = base_path(config('generators.source.root'))."/index.$type.js";
        $module = self::moduleName($type);
        $studly_name = studly_case($name);
        $class_name = $studly_name.ucfirst(rtrim($type, "s")); // NameComponent, NameDirective, NameConfig, NameService, NameFilter
        $content;

        if(!File::exists($index_path)) {
            $content = "\n".$module; //save a sys call to get file content: if we're going to create it we will know what it contains
            File::put($index_path, "\n".$module);
        } else {
            $content = $reimport ? "\n".$module : File::get($index_path);
        }
        
        $vars = [
            'file_content' => $content,
            'new_import'   => "import {$class_name} from './$type/$name$suffix';",
            'studly_name'  => lcfirst($studly_name),
            'class_name'   => $class_name,
        ];

        $content = view("Stubs::Index.$type", $vars)->render();
       
        File::put($index_path, $content);
    }

    /**
     * Generates angular module name based on "angular_module" preferences in config file.
     * 
     * @param string $type   can be "components", "directives", "config", "dialogs", "filters", "pages" or "services"
     */
    protected static function moduleName($type)
    {
        $config = config("generators.angular_modules.$type");

        $module;

        if(!$config['standalone']) {
            $module = "angular.module('".config('generators.angular_modules.root')."')";
        } else {
            $module = "angular.module('".($config['use_prefix'] ? $config['prefix']."." : "").$config['suffix']."', [])";
        }
        
        return $module;
    }
}

?>
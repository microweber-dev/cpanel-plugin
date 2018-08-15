<?php

class MicroweberInstallCommand
{

    public $logger = null;

    // $opts['user'];
    // $opts['pass'];
    // $opts['email'];
    // $opts['database_driver'];
    // $opts['database_name'];
    // $opts['database_user'];
    // $opts['database_password'];
    // $opts['database_table_prefix'];
    // $opts['default_template'];
    // $opts['source_folder'];
    // $opts['public_html_folder'];
    // $opts['is_symliked'];
    // $opts['debug_email'];
    // $opts['debug_email_subject'];
    // $opts['install_debug_file'];
    // $opts['install_debug_file'];
    // $opts['options'];
    // $opts['options'][0]['option_key'];

    public function install($opts)
    {
        $is_symliked = false;
        if (isset($opts['is_symliked']) and $opts['is_symliked']) {
            $is_symliked = $opts['is_symliked'];
        }


        if (true) {

            $remove_paths = array();
            $remove_paths[] = 'config.php';
            $remove_paths[] = 'bootstrap.php';
            $remove_paths[] = 'index.php';
            $remove_paths[] = 'src';
            $remove_paths[] = 'cpanel';
            $remove_paths[] = 'userfiles/modules/';
            $remove_paths[] = 'userfiles/removed_modules';
            $remove_paths[] = 'userfiles/templates/';
            $remove_paths[] = 'userfiles/elements/';


            $mkdirs = array();
            $mkdirs[] = 'storage';
            $mkdirs[] = 'storage/framework';
            $mkdirs[] = 'storage/framework/sessions';
            $mkdirs[] = 'storage/framework/views';
            $mkdirs[] = 'storage/cache';
            $mkdirs[] = 'storage/logs';
            $mkdirs[] = 'storage/app';

            $storage_dirs = $mkdirs;

            $mkdirs[] = 'bootstrap';
            $mkdirs[] = 'bootstrap/cache';
            $mkdirs[] = 'userfiles';
            $mkdirs[] = 'userfiles/media';
            $mkdirs[] = 'userfiles/modules';
            $mkdirs[] = 'userfiles/templates';

            $copy_files = array();
            $copy_files[] = 'index.php';
            $copy_files[] = '.htaccess';
            $copy_files[] = 'favicon.ico';
            $copy_files[] = 'composer.json';
            $copy_files[] = 'artisan';
            $copy_files[] = 'config';
            $copy_files[] = 'bootstrap/app.php';
            $copy_files[] = 'bootstrap/autoload.php';
            $copy_files[] = 'storage/database.sqlite';

            if (isset($opts['source_folder'])) {
                $mw_shared_dir = $opts['source_folder']; //add slash
            } else {
                $mw_shared_dir = '/usr/share/microweber/latest/'; //add slash
            }


            $config_file = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
            $config_file_dist = __DIR__ . DIRECTORY_SEPARATOR . 'config.dist.php';
            if (is_file($config_file)) {
                include($config_file);
            } elseif (is_file($config_file_dist)) {
                include($config_file_dist);
            }
            if (!isset($opts['source_folder']) and isset($source_folder)) {
                $mw_shared_dir = $source_folder; //add slash
            }
            if (!isset($opts['debug_email']) and isset($debug_email)) {
                $opts['debug_email'] = $debug_email;
            }
            if (!isset($opts['debug_email_subject']) and isset($debug_email_subject)) {
                $opts['debug_email_subject'] = $debug_email_subject;
            }
            if (!isset($opts['user'])) {
                error_log("Error: no user is set");
                return;
            }
            set_time_limit(300);
            $message = json_encode($opts);
            $auth_user = $opts['user'];
            $auth_pass = $opts['pass'];
            $contact_email = $opts['email'];
            if (isset($opts['default_template'])) {
                $default_template = $opts['default_template'];
            } else {
                $default_template = 'liteness';
            }
            $database_name = $opts['database_name'];
            $database_user = $opts['database_user'];
            $database_password = $opts['database_password'];
            $database_driver = $opts['database_driver'];
            $user_public_html_folder = "/home/{$opts['user']}/public_html/";
            if (isset($opts['public_html_folder'])) {
                $user_public_html_folder = $opts['public_html_folder'];
                $user_public_html_folder .= (substr($user_public_html_folder, -1) == '/' ? '' : '/');
            }


            // $user_public_html_folder
            $mw_shared_dir .= (substr($mw_shared_dir, -1) == '/' ? '' : '/');


            $this->log('Source folder ' . $mw_shared_dir);
            $this->log('Destination folder ' . $user_public_html_folder);


            $exec = "rsync -a {$mw_shared_dir} {$user_public_html_folder}";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
            $exec = "rsync -a {$mw_shared_dir}.htaccess {$user_public_html_folder}";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
            if (isset($copy_files) and is_array($copy_files) and !empty($copy_files)) {
                foreach ($copy_files as $file) {
                    $file = str_replace('..', '', $file);
                    $file = $mw_shared_dir . $file;
                    $newfile = "{$user_public_html_folder}{$file}";
                    if (is_file($file)) {
                        $exec = "cp -f $file $newfile";
                        $output = exec($exec);
                    } elseif (is_dir($file)) {
                        $exec = "cp -rf $file $newfile";
                        $output = exec($exec);
                    }
                }
            }
            if (isset($copy_external) and is_array($copy_external) and !empty($copy_external)) {
                foreach ($copy_external as $source => $dest) {
                    $file = $source;
                    $newfile = "{$user_public_html_folder}{$dest}";
                    if (is_file($file)) {
                        $exec = "cp -f $file $newfile";
                        $output = exec($exec);
                    } elseif (is_dir($file)) {
                        $exec = "cp -rf $file $newfile";
                        $output = exec($exec);
                    }
                }
            }
            if (isset($remove_files) and is_array($remove_files) and !empty($remove_files)) {
                foreach ($remove_files as $dest) {
                    $dest = str_replace('..', '', $dest);
                    $rm_dest = "{$user_public_html_folder}{$dest}";
                    $exec = "rm -rf $rm_dest";
                    $output = exec($exec);
                }
            }
            if ($is_symliked) {
                $this->log('Linking paths');

                $link_paths_base = array();
                $link_paths_base[] = 'vendor';
                $link_paths_base[] = 'database';
                $link_paths_base[] = 'resources';
                $link_paths_base[] = 'tests';
                $link_paths_base[] = 'src';
                $link_paths_base[] = 'userfiles/modules/*';
                $link_paths_base[] = 'userfiles/elements/*';
                $link_paths_base[] = 'userfiles/templates/*';
                foreach ($link_paths_base as $link) {
                    $link_src = $mw_shared_dir . $link;
                    $link_dest = $user_public_html_folder . $link;
                    $exec = "rm -rvf {$link_dest}";
                    $output = shell_exec($exec);
                    $this->log('Linking ' . $link_src . ' to ' . $link_dest);
                    $this->symlink_recursive($link_src, $link_dest);
                }

                /* foreach ($link_paths as $link) {
                     $exec = "rm -rvf {$user_public_html_folder}{$link}";
                     $message = $message . "\n\n\n" . $exec;
                     $output = shell_exec($exec);
                     $message = $message . "\n\n\n" . $output;
                     $exec = " ln -s  {$mw_shared_dir}{$link} {$user_public_html_folder}{$link}";
                     $message = $message . "\n\n\n" . $exec;
                     $output = shell_exec($exec);
                     $message = $message . "\n\n\n" . $output;
                 }*/
            }

            $exec = "chown -R {$opts['user']}:{$opts['user']} {$user_public_html_folder}*";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
            $conf = array();
            if (isset($opts['database_table_prefix'])) {
                $database_prefix = $opts['database_table_prefix'];
            } else {
                $database_prefix = 'mw_';
            }
            if (isset($opts['database_host'])) {
                $database_host = $opts['database_host'];
            } else {
                $database_host = '127.0.0.1';
            }
            $this->log('Performing php artisan microweber:install');

            $exec = "cd {$user_public_html_folder} ;";
            $exec .= "php artisan microweber:install ";
            $exec .= $contact_email . " " . $auth_user . " " . escapeshellarg($auth_pass) . " " . $database_host . " " . $database_name . " " . $database_user . " " . escapeshellarg($database_password) . " " . $database_driver . " -p " . $database_prefix;
            $exec .= " -t " . $default_template . " -d 1 ";
            $message = $message . "\n\n\n" . $exec;
            shell_exec($exec);
            if (!isset($opts['options']) and isset($install_options) and is_array($install_options) and !empty($install_options)) {
                $opts['options'] = $install_options;
            }
            if (isset($opts['options']) and is_array($opts['options']) and !empty($opts['options'])) {
                foreach ($opts['options'] as $option) {
                    if (isset($option['option_key']) and isset($option['option_value']) and isset($option['option_group'])) {
                        $exec = "cd {$user_public_html_folder} ; ";
                        $exec .= " php artisan microweber:option \"{$option['option_key']}\" \"{$option['option_value']}\" \"{$option['option_group']}\"";
                        $message = $message . "\n\n\n" . $exec;
                        $output = exec($exec);
                        $message = $message . "\n\n\n" . $output;
                    }
                }
            }
            $exec = "chown -R {$opts['user']}:{$opts['user']} {$user_public_html_folder}.htaccess";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
            $exec = "chown -R {$opts['user']}:{$opts['user']} {$user_public_html_folder}*";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $exec = "chown -R {$opts['user']}:{$opts['user']} {$user_public_html_folder}.[^.]*";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
            $exec = "chmod 755 {$opts['user']}:{$opts['user']} {$user_public_html_folder}index.php";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
            $exec = "chmod 755 {$opts['user']}:{$opts['user']} {$user_public_html_folder}";
            $message = $message . "\n\n\n" . $exec;
            $output = exec($exec);
            $message = $message . "\n\n\n" . $output;
// debug email
            $to = false;
            if (isset($opts['debug_email']) and $opts['debug_email'] != false) {
                $to = $opts['debug_email'];
            }
            if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                $subject = 'new_microweber_site';
                if (isset($opts['debug_email_subject']) and $opts['debug_email_subject'] != false) {
                    $subject = $opts['debug_email_subject'];
                }
                $subject .= ' ' . $default_template;
                mail($to, $subject, $message);
            }
            if (isset($opts['install_debug_file'])) {
                file_put_contents($opts['install_debug_file'], $message);
            }

        }

    }


    public function symlink_recursive($source_folder, $dest_folder)
    {
        $recuresive = false;
        if (substr(rtrim($source_folder), -1) == "*") {
            $recuresive = true;
        }

        $do_links = array();

        if ($recuresive) {
            $link_paths = glob($source_folder);
            $source_folder_base = str_replace('*', '', $source_folder);
            $dest_folder = str_replace('*', '', $dest_folder);
            if ($link_paths) {
                foreach ($link_paths as $link) {
                    if ($link != '.' and $link != '..') {
                        $dest_link = str_replace($source_folder_base, '', $link);
                        $do_links[$link] = $dest_folder . $dest_link;
                    }
                }
            }
        } else {

            if ((is_file($source_folder) or is_dir($source_folder))) {
                $do_links[$source_folder] = $dest_folder;
            }
        }


        if ($do_links) {
            foreach ($do_links as $link_src => $link_dest) {
                if (!is_link($link_dest) and (!is_file($link_dest) and !is_dir($link_dest))) {
                    $exec = " ln -s  $link_src $link_dest";
                    exec($exec);
                }
            }
        }

    }

    public function log($msg)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($msg);
        }
    }
}
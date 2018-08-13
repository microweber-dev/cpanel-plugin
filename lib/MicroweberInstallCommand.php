<?php

class MicroweberInstallCommand
{
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
    // $opts['is_symliked'];
    // $opts['debug_email'];
    // $opts['debug_email_subject'];
    // $opts['install_debug_file'];
    // $opts['options'];
    // $opts['options'][0]['option_key'];

    public function install($opts)
    {
        $is_symliked = false;
        if (isset($opts['is_symliked']) and $opts['is_symliked']) {
            $is_symliked = $opts['is_symliked'];
        }


        if ($is_symliked) {

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

            $link_paths = array();
            $link_paths[] = 'vendor';
            $link_paths[] = 'database';
            $link_paths[] = 'resources';
            $link_paths[] = 'app';
            $link_paths[] = 'tests';
            $link_paths[] = 'src';
            $link_paths[] = 'userfiles/modules/admin';
            $link_paths[] = 'userfiles/elements';
            $link_paths[] = 'userfiles/templates/default';
            $link_paths[] = 'userfiles/templates/liteness';
            $link_paths[] = 'userfiles/modules/audio';
            $link_paths[] = 'userfiles/modules/admin';
// B
            $link_paths[] = 'userfiles/modules/btn';
            $link_paths[] = 'userfiles/modules/breadcrumb';
// C
            $link_paths[] = 'userfiles/modules/content';
            $link_paths[] = 'userfiles/modules/categories';
            $link_paths[] = 'userfiles/modules/comments';
            $link_paths[] = 'userfiles/modules/contact_form';
            $link_paths[] = 'userfiles/modules/custom_fields';
// D
// E
            $link_paths[] = 'userfiles/modules/embed';
            $link_paths[] = 'userfiles/modules/editor';
// F
            $link_paths[] = 'userfiles/modules/files';
            $link_paths[] = 'userfiles/modules/forms';
//$link_paths[] = 'userfiles/modules/free'.DS;
//G
            $link_paths[] = 'userfiles/modules/google_maps';
//H
            $link_paths[] = 'userfiles/modules/help';
            $link_paths[] = 'userfiles/modules/highlight_code';
//I
            $link_paths[] = 'userfiles/modules/ip2country';
//L
            $link_paths[] = 'userfiles/modules/layouts';
// M
//$link_paths[] = 'userfiles/modules/media';
//$link_paths[] = 'userfiles/modules/mics';
            $link_paths[] = 'userfiles/modules/menu';
            $link_paths[] = 'userfiles/modules/microweber';
//N
//$link_paths[] = 'userfiles/modules/nav'.DS;
// added for v1.0.8
            $link_paths[] = 'userfiles/modules/newsletter';
//O
            $link_paths[] = 'userfiles/modules/options';
//P
            $link_paths[] = 'userfiles/modules/picture';
            $link_paths[] = 'userfiles/modules/pictures';
            $link_paths[] = 'userfiles/modules/posts';
            $link_paths[] = 'userfiles/modules/pages';
// added for v1.0.8
            $link_paths[] = 'userfiles/modules/pdf';
            $link_paths[] = 'userfiles/modules/parallax';
//S
            $link_paths[] = 'userfiles/modules/settings';
            $link_paths[] = 'userfiles/modules/shop';
            $link_paths[] = 'userfiles/modules/search';
            $link_paths[] = 'userfiles/modules/site_stats';
// T
            $link_paths[] = 'userfiles/modules/text';
            $link_paths[] = 'userfiles/modules/title';
// added for v1.0.8
            $link_paths[] = 'userfiles/modules/teamcard';
            $link_paths[] = 'userfiles/modules/testimonials';
            $link_paths[] = 'userfiles/modules/tabs';
// U
            $link_paths[] = 'userfiles/modules/users';
            $link_paths[] = 'userfiles/modules/updates';
//$link_paths[] = 'userfiles/modules/user_profile';
//$link_paths[] = 'userfiles/modules/user_search';
//$link_paths[] = 'userfiles/modules/users_list';
// V
            $link_paths[] = 'userfiles/modules/video';
            $link_paths[] = 'userfiles/modules/default.php';
            $link_paths[] = 'userfiles/modules/default.png';
            $link_paths[] = 'userfiles/modules/non_existing.php';
// link templates
            $link_paths[] = 'userfiles/templates/liteness';
            $link_paths[] = 'userfiles/templates/default';
// link new modulules
            $link_paths[] = 'userfiles/modules/social_links';
            $link_paths[] = 'userfiles/modules/logo';
            $link_paths[] = 'userfiles/modules/rating';
            $link_paths[] = 'userfiles/modules/calendar';
            $link_paths[] = 'userfiles/modules/beforeafter';
            $link_paths[] = 'userfiles/modules/editor';
            $link_paths[] = 'userfiles/modules/bxslider';
            $link_paths[] = 'userfiles/modules/sharer';
            $link_paths[] = 'userfiles/modules/slickslider';
            $link_paths[] = 'userfiles/modules/layouts';
            $link_paths[] = 'userfiles/modules/parallax';
            $link_paths[] = 'userfiles/modules/testimonials';
            $link_paths[] = 'userfiles/modules/pricing_table';
            $link_paths[] = 'userfiles/modules/tags';
            $link_paths[] = 'userfiles/modules/magicslider';
            $link_paths[] = 'userfiles/modules/facebook_page';
            $link_paths[] = 'userfiles/modules/pdf';
            $link_paths[] = 'userfiles/modules/twitter_feed';
            $link_paths = array_unique($link_paths);


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
            $exec = "cd {$user_public_html_folder} ;";
            $exec .= "php artisan microweber:install ";
            $exec .= $contact_email . " " . $auth_user . " " . $auth_pass . " " . $database_host . " " . $database_name . " " . $database_user . " " . $database_password ." " . $database_driver . " -p " . $database_prefix;
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

//$adminEmail $adminUsername $adminPassword $dbHost $dbName $dbUsername $adminPassword $dbDriver -p $dbPrefix
    }


}
<?php

$reported_list = array('suntgospodina.net', 'gatitul.ro');

//cores parts
include 'components/pdo.php';
include 'components/functions.php';
include '../config.php';

//libraries
include 'components/scraper.php';
include 'components/envato.php';
include 'components/processor.php';
include 'components/detect.php';

$request = @$_GET['request'];
$form = @$_POST;
$get = @$_GET;
$output = array();

if ($request == 'proxy') {
    if (!$get['URL']) {
        echo 'Please enter valid URL.';
        exit;
    }

    foreach ($reported_list as $key => $value) {
        if (strpos(@$_GET['URL'], $value) > -1) {
            echo 'This URL has been reported.';
            exit;
        }
    }

    $data = new Scraper($get['URL'], $config['site'], $get['baseURL'], $get['viewMethod'], $get['user_agent'], $get['cookie'], $get['proxy']);
    echo $data->proxy();
    exit;
}

if ($request == 'detect') {
    $data = new Detect($get['URL'], $get['single_post']);
    $result = $data->getResults();

    $output = array('result' => $result);
}

if ($request == 'get_output_log') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];

    $db = connectDB($config);
    $item = $db->select('items', 'hash = "' . $hash . '"', '*', ' ORDER BY `id` DESC');
    $item = $item[0];

    $result = stripslashes(@$item['result']);

    $output = array('success' => ($item ? true : false), 'results' => json_decode($result));
}

if ($request == 'start_process') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];
    $result = '';

    $db = connectDB($config);

    $task = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"');
    $task = $task[0];

    if ($task) {
        $query = $db->insert('items', array(
            'purchase_code' => $purchase_code,
            'hash' => $hash,
            'result' => $result
                )
        );

        $query = $db->update('tasks', array(
            'total_run' => ($task['total_run'] + 1),
            'last_run' => date('Y-m-d H:i:s'),
            'running' => 1
                ), 'id = "' . $task['id'] . '"'
        );
    }

    $output = array('success' => $query);
}

if ($request == 'rss') {
    include 'components/rss.php';
    exit;
}

if ($request == 'increase_page') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];

    $db = connectDB($config);
    $task = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"');
    $task = $task[0];

    if ($task && $form['current_page_url']) {
        $query = $db->update('tasks', array(
            'current_page' => $task['current_page'] + 1,
            'current_page_url' => @$form['current_page_url'],
            'last_index' => 0
                ), 'hash = "' . $hash . '" and purchase_code = "' . $purchase_code . '"'
        );
    } else {
        $query = false;
    }

    $output = array('success' => $query);
}

if ($request == 'increase_index') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];

    $db = connectDB($config);
    $task = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"');
    $task = $task[0];

    if ($task) {
        $query = $db->update('tasks', array(
            'last_index' => $task['last_index'] + 1,
            'count_run' => $task['count_run'] + 1
                ), 'hash = "' . $hash . '" and purchase_code = "' . $purchase_code . '"'
        );
    } else {
        $query = false;
    }

    $output = array('success' => $query);
}

if ($request == 'finish_process') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];
    $result = addslashes(@$form['result']);

    $db = connectDB($config);
    $task = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"');
    $task = $task[0];

    if ($task) {
        $query = $db->update('items', array(
            'result' => $result
                ), 'hash = "' . $hash . '" and purchase_code = "' . $purchase_code . '" ORDER BY `id` DESC LIMIT 1 '
        );

        if (@!$query) {
            $query = $db->insert('items', array(
                'purchase_code' => $purchase_code,
                'hash' => $hash,
                'result' => $result
                    )
            );
        }

        $query = $db->update('tasks', array(
            'last_complete' => date('Y-m-d H:i:s'),
            'running' => 0
                ), 'id = "' . $task['id'] . '"'
        );
    }

    $output = array('success' => $query);
}

if ($request == 'check_content') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];
    $uniqueness_hash = @$form['uniqueness_hash'];

    $db = connectDB($config);
    $task = $db->select('contents', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '" and uniqueness_hash = "' . $uniqueness_hash . '"');
    $task = @$task[0];

    $output = array('is_unique' => ($task ? false : true));
}

if ($request == 'create_content') {
    $purchase_code = @$form['purchase_code'];
    $hash = @$form['hash'];
    $uniqueness_hash = @$form['uniqueness_hash'];

    $db = connectDB($config);
    $query = $db->insert('contents', array(
        'purchase_code' => $purchase_code,
        'hash' => $hash,
        'uniqueness_hash' => $uniqueness_hash
            )
    );

    $output = array('success' => true);
}

if ($request == 'run_task') {
    $purchase_code = @$form['purchase_code'];
    $next_page = @$form['next_page'];
    $data = @$form['data'];
    $fields = array();

    //Define sample task
    $task = array(
        'current_page' => 0,
        'current_page_url' => '',
        'data' => json_encode($data),
        'parse_method' => @$form['parseMethod']
    );

    //Define fields
    foreach ($data['fields'] as $key => $field) {
        if ($field['type'] == 'variable' || $field['type'] == 'tags_input') {
            $fields[] = $field['name'];
        } else {
            $fields[] = $field['type'];
        }
    }

    if (@$form['currentPage']) {
        $task['current_page'] = $form['currentPage'];
        $task['nextPage'] = $form['nextPage'];
    }

    $results = new Processor();
    $items = $results->process_task($task);

    $output = array('items' => @$items['result'], 'fields' => $fields, 'data' => $data, 'next_page' => $items['next_page']);
}

if ($request == 'create_task') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $update_hash = @$form['hash'];
    $db = connectDB($config);
    if (@$form['getDomain'] == 'yes') {
        $domainQuery = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $update_hash . '"', 'domain');
        $domain = $domainQuery[0]['domain'];
        $update_hash = '';
    }

    $token = md5($purchase_code . ' - ' . $domain);
    $name = @$form['name'];
    $data = @$form['data'];


    $categoryId = @$form['categoryId'];
    $categoryIds = @$form['categoryIds'];
    $downloadImages = @$form['downloadImages'] == 'true' ? '1' : '0';
    $trackChanges = @$form['trackChanges'] == 'true' ? '1' : '0';
    $resetTask = @$form['resetTask'] == 'true' ? '1' : '0';
    $deletePost = @$form['deletePost'] == 'true' ? '1' : '0';
    $deleteMethod = @$form['deleteMethod'];    

    $publicTask = @$form['publicTask'] == 'true' ? '1' : '0';
    $lastIndex = 0;
    $postStatus = @$form['postStatus'];
    $postUpdate = @$form['postUpdate'];
    $runInterval = @$form['runInterval'];
    $runDelay = @$form['runDelay'];
    $postType = @$form['postType'];
    $excludeTags = @$form['excludeTags'];
    $excludeField = @$form['excludeField'] ? $form['excludeField'] : '';
    $taskCondition = @$form['taskCondition'] ? $form['taskCondition'] : '';
    $taskLimit = @$form['taskLimit'] ? $form['taskLimit'] : 0;
    $parseMethod = @$form['parseMethod'];
    $filename = @$form['filename'];
    $uniquenessMethod = @$form['uniquenessMethod'];

    $hash = md5(time());
    $query = false;

    if ($token && $domain && $purchase_code && $data) {

        if (!$update_hash) {
            $query = $db->insert('tasks', array(
                'purchase_code' => $purchase_code,
                'name' => $name,
                'hash' => $hash,
                'active' => 0,
                'domain' => $domain,
                'token' => $token,
                'data' => json_encode($data),
                'run_interval' => $runInterval,
                'run_delay' => $runDelay,
                'last_run' => date('Y-m-d H:i:s'),
                'last_complete' => date('Y-m-d H:i:s'),
                'total_run' => 0,
                'count_run' => 0,
                'category_id' => $categoryId,
                'category_ids' => json_encode($categoryIds),
                'download_images' => $downloadImages,
                'filename' => $filename,
                'track_changes' => $trackChanges,
                'reset_task' => $resetTask,
                'delete_post' => $deletePost,
                'delete_method' => $deleteMethod,
                'last_index' => 0,
                'public_task' => $publicTask,
                'post_status' => $postStatus,
                'post_update' => $postUpdate,
                'post_type' => $postType,
                'exclude_tags' => $excludeTags,
                'exclude_field' => $excludeField,
                'task_condition' => $taskCondition,
                'task_limit' => $taskLimit,
                'wait_on_ban' => 0,
                'parse_method' => $parseMethod,
                'uniqueness_method' => $uniquenessMethod,
                'current_page' => 0,
                'current_page_url' => '',
                'running' => 0
                    )
            );

            if ($publicTask) {
                $query = $db->insert('templates', array(
                    'name' => $name,
                    'URL' => $data['feedURL'] ? $data['feedURL'] : $data['contentURL'],
                    'template' => json_encode($data),
                    'approved' => 0
                        )
                );
            }
        } else {
            $query = $db->update('tasks', array(
                'name' => $name,
                'data' => json_encode($data),
                'run_interval' => $runInterval,
                'run_delay' => $runDelay,
                'category_id' => $categoryId,
                'category_ids' => json_encode($categoryIds),
                'download_images' => $downloadImages,
                'filename' => $filename,
                'track_changes' => $trackChanges,
                'reset_task' => $resetTask,
                'delete_post' => $deletePost,
                'delete_method' => $deleteMethod,
                'public_task' => $publicTask,
                'post_status' => $postStatus,
                'post_update' => $postUpdate,
                'post_type' => $postType,
                'exclude_tags' => $excludeTags,
                'exclude_field' => $excludeField,
                'task_condition' => $taskCondition,
                'task_limit' => $taskLimit,
                'parse_method' => $parseMethod,
                'uniqueness_method' => $uniquenessMethod
                    ), 'purchase_code = "' . $purchase_code . '" and domain = "' . $domain . '" and hash = "' . $form['hash'] . '"'
            );
        }
    }

    $output = array('success' => $query);
}

if ($request == 'start_task') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];

    if ($hash && $domain && $purchase_code) {
        $db = connectDB($config);

        $query = $db->update('tasks', array('active' => 1), 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"'
        );

        $output = array('success' => $query);
    } else {
        $output = array('success' => false);
    }
}

if ($request == 'stop_task') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];

    if ($hash && $domain && $purchase_code) {
        $db = connectDB($config);

        $query = $db->update('tasks', array('active' => 0), 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"'
        );

        $output = array('success' => true);
    } else {
        $output = array('success' => false);
    }
}

if ($request == 'reset_task') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];

    if ($hash && $domain && $purchase_code) {
        $db = connectDB($config);

        $new_hash = md5(time());
        $query = $db->update('tasks', array(
            'hash' => $new_hash,
            'current_page' => 0,
            'current_page_url' => '',
            'last_index' => 0,
            'count_run' => 0,
            'running' => 0
                ), 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"'
        );

        $output = array('success' => true);
    } else {
        $output = array('success' => false);
    }
}

if ($request == 'reset_indexes') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];

    if ($hash && $domain && $purchase_code) {
        $db = connectDB($config);

        $query = $db->update('tasks', array(
            'current_page' => 0,
            'current_page_url' => '',
            'last_index' => 0
                ), 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"'
        );

        $output = array('success' => true);
    } else {
        $output = array('success' => false);
    }
}

if ($request == 'clone_task') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];
    $token = md5($purchase_code . ' - ' . $domain);

    if ($hash && $domain && $purchase_code) {
        $db = connectDB($config);

        $task = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"');
        $task = $task[0];

        $hash = md5(time());
        $query = $db->insert('tasks', array(
            'purchase_code' => $purchase_code,
            'name' => $task['name'] . ' - Copy',
            'hash' => $hash,
            'active' => 0,
            'domain' => $domain,
            'token' => $token,
            'data' => $task['data'],
            'run_interval' => $task['run_interval'],
            'run_delay' => $task['run_delay'],
            'last_run' => date('Y-m-d H:i:s'),
            'last_complete' => date('Y-m-d H:i:s'),
            'total_run' => 0,
            'count_run' => 0,
            'filename' => $task['filename'],
            'category_id' => $task['category_id'],
            'category_ids' => $task['category_ids'],
            'download_images' => $task['download_images'],
            'track_changes' => $task['track_changes'],
            'reset_task' => $task['reset_task'],
            'delete_post' => $task['delete_post'],
            'delete_method' => $task['delete_method'],
            'last_index' => $task['last_index'],
            'public_task' => $task['public_task'],
            'post_status' => $task['post_status'],
            'post_update' => $task['post_update'],
            'post_type' => $task['post_type'],
            'exclude_tags' => $task['exclude_tags'],
            'exclude_field' => $task['exclude_field'],
            'task_condition' => $task['task_condition'],
            'task_limit' => $task['task_limit'],
            'wait_on_ban' => 0,
            'parse_method' => $task['parse_method'],
            'uniqueness_method' => $task['uniqueness_method'],
            'current_page' => $task['current_page'],
            'current_page_url' => '',
            'running' => 0
                )
        );

        $output = array('success' => $query, 'task' => $task);
    } else {
        $output = array('success' => false);
    }
}

if ($request == 'delete_task') {
    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];

    if ($hash && $domain && $purchase_code) {
        $db = connectDB($config);

        $db->delete('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"'
        );

        $output = array('success' => true);
    } else {
        $output = array('success' => false);
    }
}

if ($request == 'get_tasks') {
    $tasks = array();

    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];

    $token = md5($purchase_code . ' - ' . $domain);
    $db = connectDB($config);
    $fields = 'name, data, active, hash, data, last_run, last_complete, run_interval, run_delay, total_run, running, count_run, post_type, exclude_tags, exclude_field, task_condition, task_limit, post_status, download_images, filename, public_task, track_changes, reset_task, delete_post, delete_method, last_index, category_id, category_ids, post_update, parse_method, uniqueness_method, current_page, current_page_url';

    if (isset($form['filterByTime']) && $form['filterByTime'] == true) {
        $query = $db->select('tasks', 'purchase_code = "' . $purchase_code . '"  and last_complete <= "' . date('Y-m-d H:i:s') . '"', $fields);
    } else {
        $query = $db->select('tasks', 'purchase_code = "' . $purchase_code . '"', $fields);
    }

    if (@$query[0]) {        
        $tasks = $query;
    }

    $output = array('tasks' => $tasks);
}

if ($request == 'get_task') {
    $task = array();

    $purchase_code = @$form['purchase_code'];
    $domain = @$form['domain'];
    $hash = @$form['hash'];

    $db = connectDB($config);
    $fields = 'name, data, active, hash, data, last_run, last_complete, run_interval, run_delay, total_run, running, count_run, post_type, exclude_tags, exclude_field, task_condition, task_limit, post_status, download_images, filename, public_task, track_changes, reset_task, delete_post, delete_method, last_index, category_id, category_ids, post_update, parse_method, uniqueness_method, current_page, current_page_url';

    $query = $db->select('tasks', 'purchase_code = "' . $purchase_code . '" and hash = "' . $hash . '"', $fields);

    if (@$query[0]) {
        $task = $query[0];
        $task['data'] = json_decode(($task['data']));
    }

    $output = $task;
}

if ($request == 'check_token') {
    $purchase_code = @$_GET['purchase_code'];
    $license_type = checkPurchaseCode($purchase_code);

    var_dump($license_type);
    exit;
}

if ($request == 'confirm_token') {

    function get_domain($url) {
        $urlobj = parse_url($url);
        $domain = $urlobj['host'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }

    $purchase_code = $form['purchase_code'];
    $domain = $form['domain'];

    $db = connectDB($config);

    $query = $db->select('purchases', 'purchase_code = "' . $purchase_code . '"');

    $db->insert('logs', array('log' => json_encode(array(
            'hash' => md5(time()),
            'purchase_code' => $purchase_code,
            'domain' => $domain,
            'ip' => $ip,
            'license' => 0
    ))));

    if (@$query[0]) {
        $output = array('status' => true);
    } else {
        //default
        $output = array('status' => false);

        //test if there is code on envato
        $license_type = checkPurchaseCode($purchase_code);
        $already_licensed = false;

        if ($license_type == 1) {
            $query = $db->select('purchases', 'purchase_code = "' . $purchase_code . '"');

            if (count($query) > 0) {
                foreach ($query as $key => $item) {
                    $domain_parse0 = get_domain($item['domain']);
                    $domain_parse1 = get_domain($domain);

                    if ($domain_parse0 != $domain_parse1) {
                        $output = array('status' => false);
                        $already_licensed = true;
                    }
                }
            }
        }

        $valid = false;

        if ($license_type > 0 && !$already_licensed) {
            $valid = $db->insert('purchases', array(
                'hash' => md5(time()),
                'purchase_code' => $purchase_code,
                'domain' => $domain,
                'ip' => $ip
            ));
            
            $output = array('status' => $valid);
        }

        $db->insert('logs', array('log' => json_encode(array(
                'hash' => md5(time()),
                'purchase_code' => $purchase_code,
                'domain' => $domain,
                'ip' => $ip,
                'license' => $license_type,
                'valid' => $valid
        ))));
    }
}

if ($request == 'library') {
    $output = array(
        0 =>
        array(
            'id' => '8',
            'name' => 'Tutsplus.com Latest Courses',
            'URL' => 'https://tutsplus.com/',
            'template' => '{"feedURL":"https:\\/\\/tutsplus.com\\/","contentURL":"https:\\/\\/design.tutsplus.com\\/courses\\/from-the-top-adobe-photoshop-for-beginners","singlePost":"false","feed":{"path":"\\/\\/body\\/div\\/main[1]\\/section[1]\\/div[2]\\/ol[1]\\/li\\/\\/article\\/header[1]\\/a[2]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"content-banner__title\\")] | \\/\\/div[2]\\/main\\/div[1]\\/div\\/div[2]\\/a\\/h1 | \\/\\/h1","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"featured_image","path":"\\/\\/head\\/meta[11]","prop":"attr:content","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"post_content","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"course__description\\")] | \\/\\/div[2]\\/main\\/div[3]\\/div[1]\\/div[3]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        1 =>
        array(
            'id' => '12',
            'name' => 'Investing.com - GBP USD - Pound ',
            'URL' => 'https://www.investing.com/currencies/gbp-usd',
            'template' => '{"feedURL":"","contentURL":"https:\\/\\/www.investing.com\\/currencies\\/gbp-usd","singlePost":"true","feed":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_content","path":"\\/\\/*[@id=\\"last_last\\"]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        2 =>
        array(
            'id' => '13',
            'name' => 'Investing.com - TSLA Stock Quote',
            'URL' => 'https://www.investing.com/equities/tesla-motors',
            'template' => '{"feedURL":"","contentURL":"https:\\/\\/www.investing.com\\/equities\\/tesla-motors","singlePost":"true","feed":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_content","path":"\\/\\/*[@id=\\"last_last\\"]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        3 =>
        array(
            'id' => '14',
            'name' => 'Investing.com - BTC/USD - Bitcoin',
            'URL' => 'https://www.investing.com/crypto/bitcoin/btc-usd',
            'template' => '{"feedURL":"","contentURL":"https:\\/\\/www.investing.com\\/crypto\\/bitcoin\\/btc-usd","singlePost":"true","feed":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_content","path":"\\/\\/*[@id=\\"last_last\\"]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        4 =>
        array(
            'id' => '25',
            'name' => 'Metacritic.com - Top 10 games with scores',
            'URL' => 'http://www.metacritic.com/game',
            'template' => '{"feedURL":"http:\\/\\/www.metacritic.com\\/game","contentURL":"\\/game\\/xbox-one\\/divinity-original-sin-ii---definitive-edition","singlePost":"false","feed":{"path":"\\/\\/*\\/div[3]\\/div[1]\\/div[2]\\/div[2]\\/ol[1]\\/li\\/\\/div\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/h3[1]\\/a[1]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"main\\"]\\/div[1]\\/div[1]\\/div[2]\\/a[1]\\/span[1]\\/h1[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"post_content","path":"\\/\\/*[@id=\\"main\\"]\\/div[1]\\/div[3]\\/div[1]\\/div[1]\\/div[2]\\/div[2]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"Expand","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"featured_image","path":"\\/\\/*[@id=\\"main\\"]\\/div[1]\\/div[3]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/img[1]","prop":"attr:src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"game_score","type":"variable","path":"\\/\\/*[@id=\\"main\\"]\\/div[1]\\/div[3]\\/div[1]\\/div[1]\\/div[2]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/a[1]\\/div[1]\\/span[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        5 =>
        array(
            'id' => '26',
            'name' => 'GameSpot.com Reviews',
            'URL' => 'https://www.gamespot.com/reviews/',
            'template' => '{"feedURL":"https:\\/\\/www.gamespot.com\\/reviews\\/","contentURL":"\\/reviews\\/planet-alpha-review-a-beautiful-planet\\/1900-6416976\\/","singlePost":"false","feed":{"path":"\\/\\/*[@id=\\"js-sort-filter-results\\"]\\/section[1]\\/\\/article\\/a","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"kubrick-lead\\"]\\/div[1]\\/div[1]\\/div[1]\\/div[2]\\/h1[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"post_content","path":"\\/\\/*[@id=\\"default-content\\"]\\/div[1]\\/article[1]\\/section[2]\\/div[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"featured_image","path":"\\/\\/head\\/meta[17]","prop":"attr:content","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        6 =>
        array(
            'id' => '27',
            'name' => 'Goodreads.com - Popular To Read Books',
            'URL' => 'https://www.goodreads.com/shelf/show/to-read',
            'template' => '{"feedURL":"https:\\/\\/www.goodreads.com\\/shelf\\/show\\/to-read","contentURL":"\\/book\\/show\\/2657.To_Kill_a_Mockingbird","singlePost":"false","feed":{"path":"\\/\\/a[@class=\\"bookTitle\\"]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"bookTitle\\"]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"post_content","path":"\\/\\/*[@id=\\"description\\"]\\/span[2]","prop":"innerHTML","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"style=\\"display: none;\\"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"featured_image","path":"\\/\\/*[@id=\\"coverImage\\"]","prop":"attr:src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        7 =>
        array(
            'id' => '29',
            'name' => 'iogames.space - Games',
            'URL' => 'http://iogames.space/',
            'template' => '{"feedURL":"http:\\/\\/iogames.space\\/","contentURL":"\\/goons-io","singlePost":"false","feed":{"path":"\\/\\/div[@class=\\"v2-game-title\\"]\\/a","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"main-content-container\\"]\\/div[1]\\/div[3]\\/div[1]\\/div[1]\\/h1[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"post_content","path":"\\/\\/*[@id=\\"main-content-container\\"]\\/div[4]\\/article[1]\\/div[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"featured_image","path":"\\/\\/head\\/meta[12]","prop":"attr:content","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"game_frame","type":"variable","path":"\\/\\/iframe[@id=\\"gameFrame\\"]","prop":"attr:src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"game_score","type":"variable","path":"\\/\\/*[@id=\\"numberCircle\\"]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        8 =>
        array(
            'id' => '30',
            'name' => 'Miniclip.com - Games Actions Genre',
            'URL' => 'https://www.miniclip.com/games/genre-13/action/en/#t-n-H',
            'template' => '{"feedURL":"https:\\/\\/www.miniclip.com\\/games\\/genre-13\\/action\\/en\\/#t-n-H","contentURL":"\\/games\\/happy-wheels\\/en\\/#t-c-f","singlePost":"false","feed":{"path":"\\/\\/*[@id=\\"category-games-list\\"]\\/ul[1]\\/\\/li\\/a[1]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"game-container\\"]\\/div[1]\\/header[1]\\/nav[1]\\/h2[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"tags_input","path":"\\/\\/*[@id=\\"game-container\\"]\\/div[2]\\/div[2]\\/\\/a","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"post_content","path":"\\/\\/*[@id=\\"site-container\\"]\\/div[4]\\/section[2]\\/div[1]\\/div[1]\\/p[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"game_id","type":"variable","path":"\\/\\/head\\/meta[20]","prop":"attr:content","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"(https:\\/\\/www.miniclip.com\\/games\\/|en\\/|\\/)","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"flash_swf","type":"variable","path":"-","prop":"innerText","element":"","display":"false","selecting":"false","content":"https:\\/\\/static.miniclipcdn.com\\/games\\/{{game_id}}\\/en\\/gameloader.swf?v=6","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        9 =>
        array(
            'id' => '31',
            'name' => 'Sondakika.com Spor Haberleri',
            'URL' => 'https://www.sondakika.com/spor/',
            'template' => '{"feedURL":"https:\\/\\/www.sondakika.com\\/spor\\/","contentURL":"\\/haber\\/haber-mourinho-nun-vergi-kacirdigi-iddialari-11203085\\/","singlePost":"false","feed":{"path":"\\/\\/*[@id=\\"main\\"]\\/ul[1]\\/\\/li\\/a[2]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"section\\"]\\/h1[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","translate":"","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"post_content","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"wrapper\\")] | \\/\\/div[2]\\/div[4]\\/section\\/div[4]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"featured_image","path":"\\/\\/img[@id=\\"haberResim\\"] | \\/\\/div[2]\\/div[4]\\/section\\/div[3]\\/div\\/img","prop":"attr:content","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripLinks":"false","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        10 =>
        array(
            'id' => '34',
            'name' => 'Facebook.com Scraping Test - Gleb&Alina\'s manufactory',
            'URL' => 'https://m.facebook.com/GlebAlinasmanufactory/posts/',
            'template' => '{"feedURL":"https:\\/\\/m.facebook.com\\/pg\\/GlebAlinasmanufactory\\/posts\\/?ref=page_internal","contentURL":"\\/story.php?story_fbid=903414306514100&id=332587120263491&ref=page_internal&__tn__=-R","singlePost":"false","feed":{"path":"\\/\\/div[contains(@class, \\"_52jc\\")]\\/\\/a","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/head\\/meta[4]","prop":"attr:content","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"featured_image","path":"\\/\\/head\\/meta[7]","prop":"attr:content","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"none","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"post_content","path":"\\/\\/*[@id=\\"u_0_0\\"]\\/div[1]\\/div[1]","prop":"innerText","display":"false","selecting":"false","content":"Go To Facebook Post","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"none","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        11 =>
        array(
            'id' => '35',
            'name' => 'Flipkart.com Mobile Accessories',
            'URL' => 'https://www.flipkart.com/mobile-accessories/cases-and-covers/pr?sid=tyy,4mr,q2u&otracker=nmenu_sub_Electronics_0_Mobile%20Cases',
            'template' => '{"feedURL":"https:\\/\\/www.flipkart.com\\/mobile-accessories\\/cases-and-covers\\/pr?sid=tyy,4mr,q2u&otracker=nmenu_sub_Electronics_0_Mobile%20Cases","contentURL":"\\/flipkart-smartbuy-back-cover-asus-zenfone-max-pro-m1\\/p\\/itmf4hzyarq8jrgj?pid=ACCF4HNZ7SYPGPGG&lid=LSTACCF4HNZ7SYPGPGGF3O8YL&marketplace=FLIPKART&srno=b_1_1&otracker=nmenu_sub_Electronics_0_Mobile%20Cases&fm=organic&iid=e98c2dde-9d74-457a-8e76-eac48a31a68f.ACCF4HNZ7SYPGPGG.SEARCH","singlePost":"false","feed":{"path":"\\/\\/a[contains(@class, \\"_2cLu-l\\")]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"container\\"]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[2]\\/div[2]\\/div[1]\\/h1[1]\\/span[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"2","type":"post_content","path":"\\/\\/*[@id=\\"container\\"]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[2]\\/div[8]\\/div[2]\\/div[1]\\/div[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"none","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"3","type":"featured_image","path":"\\/\\/*[@id=\\"container\\"]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/ul[1]\\/li[1]\\/div[1]\\/div[1]","prop":"attr:style","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"background-image","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"price","type":"variable","path":"\\/\\/*[@id=\\"container\\"]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[1]\\/div[2]\\/div[2]\\/div[3]\\/div[1]\\/div[1]\\/div[1]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"none","find":"","replace":"","isNumber":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        12 =>
        array(
            'id' => '36',
            'name' => 'Test Task',
            'URL' => 'https://tiki.vn',
            'template' => '{"feedURL":"https:\\/\\/tiki.vn\\/sach-van-hoc-viet-nam\\/c2547","contentURL":"https:\\/\\/tiki.vn\\/lop-hoc-mat-ngu-tap-9-p3141377.html?src=category-page","singlePost":"false","feed":{"path":"\\/\\/div[contains(@class, \\"product-box-list\\")]\\/div[contains(@class, \\"product-item\\")]\\/a[1]","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"\\/\\/a[contains(@class, \\"normal\\")]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"product-name\\"]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","cleanNonNumerical":"false","math":"value","clipStart":"0","clipEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","path":"\\/\\/body\\/div[9]\\/div[1]\\/div[5]\\/div[1]\\/div[3]\\/div[1]\\/div[2]","prop":"innerHTML","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"none","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","cleanNonNumerical":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"table_tiki_book","type":"variable","path":"\\/\\/body\\/div[9]\\/div[1]\\/div[5]\\/div[1]\\/div[3]\\/div[1]\\/div[1]","prop":"innerHTML","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","attributeParse":"none","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","cleanNonNumerical":"false","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1"}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        13 =>
        array(
            'id' => '41',
            'name' => 'Vbulletin Forum',
            'URL' => 'http://www.ircforumlari.net/sunuculardan-son-haberler/',
            'template' => '{"feedURL":"http:\\/\\/www.ircforumlari.net\\/sunuculardan-son-haberler\\/","contentURL":"http:\\/\\/www.ircforumlari.net\\/sunuculardan-son-haberler\\/754556-bolum-kurallari.html","singlePost":"false","feed":{"path":"//a[contains(@id, \\"thread_title\\")]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"\\/\\/div[contains(@class, \\"td-ss-main-content\\")]\\/div[contains(@class, \\"page-nav\\")]\\/\\/a","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"//span[@class=\\"Kbaslik\\"]","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"//div[contains(@id, \\"post_message_\\")]","prop":"innerHTML","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"attributeParse":"none","transform":[],"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        14 =>
        array(
            'id' => '42',
            'name' => 'Array test',
            'URL' => 'http://sport1.maariv.co.il/League/Tables/437/Ligat-haAl',
            'template' => '{"feedURL":"https:\\/\\/www.babybanden.no\\/specials","contentURL":"https:\\/\\/www.babybanden.no\\/products\\/esprit-omslagsbody-wasnt-me-off-white","fieldsMode":"simple-product","singlePost":"false","feed":{"path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"title\\")] | \\/\\/div[7]\\/div[4]\\/section[1]\\/div[2]\\/div[2]\\/div\\/ul\\/li[1]\\/div\\/div[3]\\/div[1]\\/a","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"row\\")]\\/div[2]\\/form\\/h1 | \\/\\/div[8]\\/section[2]\\/div\\/div[1]\\/div[2]\\/form\\/h1 | \\/\\/h1","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}}","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"panel-body\\")]\\/p | \\/\\/div[8]\\/section[2]\\/div\\/div[1]\\/div[3]\\/div[1]\\/div\\/div\\/div\\/div[2]\\/p","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"true","stripLinks":"true","stripAds":"true","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"gallery\\")]\\/img | \\/\\/div[8]\\/section[2]\\/div\\/div[1]\\/div[1]\\/div\\/div[1]\\/ul\\/li[1]\\/a\\/img","prop":"attr:src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"elevatezoom-gallery\\")]\\/img | \\/\\/div[8]\\/section[2]\\/div\\/div[1]\\/div[1]\\/div\\/div[2]\\/ul\\/li[1]\\/a\\/img","prop":"attr:src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_5","type":"_price","filename":"","path":"\\/\\/s[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"products_price_old\\")]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"true","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"true","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"product_tag","type":"tags_input","filename":"","path":"-","prop":"innerText","element":"","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"product_cat","type":"tags_input","filename":"","path":"-","prop":"innerText","element":"","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_8","type":"_product_url","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"breadcrumb\\")]\\/a[5] | \\/\\/div[8]\\/section[2]\\/section[1]\\/div\\/div\\/a[5]","prop":"attr:href","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_9","type":"_sale_price","filename":"","path":"\\/\\/span[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"product-price\\")]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"true","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"true","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":"","ignore_params":"false","total_run":"10"}}',
            'approved' => '1',
        ),
        15 =>
        array(
            'id' => '61',
            'name' => 'liga-indonesia.id',
            'URL' => 'http://liga-indonesia.id',
            'template' => '{"feedURL":"https:\\/\\/liga-indonesia.id\\/berita?tag=persebayau19","contentURL":"https:\\/\\/liga-indonesia.id\\/berita\\/persebaya-u-19-tidak-sabar-bersaing-di-8-besar","fieldsMode":"simple-post","singlePost":"false","feed":{"path":"\\/\\/div[contains(@class, \\"card__image-wrapper\\")]\\/div[contains(@class, \\"responsive\\")]\\/\\/a","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(@class, \\"container\\")][1]\\/div[contains(@class, \\"news--content\\")][1]\\/div[contains(@class, \\"row\\")][1]\\/div[contains(@class, \\"col-md-\\")][2]\\/div[contains(@class, \\"news--header\\")][1]\\/h1","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}}","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(@class, \\"container\\")][1]\\/div[contains(@class, \\"news--content\\")][1]\\/div[contains(@class, \\"row\\")][1]\\/div[contains(@class, \\"col-md-\\")][2]\\/div[contains(@class, \\"news--info\\")][1]","prop":"innerText","display":"true","selecting":"false","content":"{{content}} {{gallery}}","translate":"","customContent":"false","stripTags":"true","stripLinks":"true","stripAds":"true","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(@class, \\"article__headline\\")][1]\\/div[contains(@class, \\"image\\")][1]\\/div[contains(@class, \\"wrapper\\")][1]\\/div[contains(@class, \\"responsive\\")][1]\\/a\\/img","prop":"attr:src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        16 =>
        array(
            'id' => '62',
            'name' => 'Task for marcelmn',
            'URL' => 'https://www.concretedisciples.com/skateparks/afghanistan',
            'template' => '{"feedURL":"https:\\/\\/www.concretedisciples.com\\/skateparks\\/afghanistan","contentURL":"\\/skateparks\\/68-afghanistan\\/17833-skateistan-mazar-e-sharif","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(@class, \\"jrListingContent\\")]\\/div[contains(@class, \\"jrContentTitle\\")]\\/\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(@class, \\"content-inner\\")][1]\\/div[contains(@class, \\"item-page\\")][1]\\/div[contains(@class, \\"item-body\\")][1]\\/div[contains(@class, \\"jr-page\\")][1]\\/h1[contains(@class, \\"contentheading\\")][1]\\/span","prop":"innerText","filename":"","element":{},"display":false,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(@class, \\"content-inner\\")][1]\\/div[contains(@class, \\"item-page\\")][1]\\/div[contains(@class, \\"item-body\\")][1]\\/div[contains(@class, \\"jr-page\\")][1]\\/div[contains(@class, \\"jrCustomFields\\")][1]\\/div[contains(@class, \\"jrFieldGroup\\")][3]","prop":"innerText","element":{},"display":false,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(@class, \\"item-page\\")][1]\\/div[contains(@class, \\"item-body\\")][1]\\/div[contains(@class, \\"jr-page\\")][1]\\/div[contains(@class, \\"jrListingMainImage\\")][1]\\/a[contains(@class, \\"fancybox\\")][1]\\/img[contains(@class, \\"jrMediaPhoto\\")][1]","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        17 =>
        array(
            'id' => '63',
            'name' => 'Template bureau-vallee',
            'URL' => 'https://www.bureau-vallee.fr/cartouches-toners/toners-laser/brother.html',
            'template' => '{"feedURL":"https:\\/\\/www.bureau-vallee.fr\\/cartouches-toners\\/toners-laser\\/brother.html","contentURL":"https:\\/\\/www.bureau-vallee.fr\\/cart-laser-bro-tn241-bk-2500p-70916.html","fieldsMode":"simple-product","singlePost":false,"feed":{"path":"\\/\\/div[contains(@class, \\"product-shop-inner\\")]\\/h2[contains(@class, \\"product-name\\")]\\/\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"\\/\\/li[contains(@class, \\"next\\")]\\/a","selecting":false,"samples":[],"sampleIndex":0,"element":null,"siblings":[]},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(@class, \\"product-view\\")][1]\\/form\\/div[contains(@class, \\"product-shop\\")][1]\\/div[contains(@class, \\"mainzone\\")][1]\\/div[contains(@class, \\"product-name\\")][1]\\/h1","prop":"innerText","filename":"","element":{},"display":false,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(@class, \\"product-view\\")][1]\\/div[contains(@class, \\"box-additional\\")][3]\\/div[contains(@class, \\"gen-tabs\\")][1]\\/div[contains(@class, \\"tabs-panels\\")][1]\\/div[contains(@class, \\"panel\\")][1]\\/div[contains(@class, \\"std\\")][1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":true,"stripLinks":true,"stripAds":true,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(@class, \\"product-img-column\\")][1]\\/div[contains(@class, \\"img-box\\")][1]\\/div[contains(@class, \\"amlabel-div\\")][1]\\/p[contains(@class, \\"product-image\\")][1]\\/a[contains(@class, \\"cloud-zoom\\")][1]\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/img[contains(@itemprop, \\"image\\")][contains(@class, \\"lazyOwl\\")]","prop":"attr:data-src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[{},{},{},{},{}],"regexIndex":-1,"regexResults":[]},{"name":"variable_5","type":"_price","filename":"","path":"\\/\\/div[contains(@class, \\"inner\\")][1]\\/div[contains(@class, \\"product-type-data\\")][1]\\/div[contains(@class, \\"price-box\\")][1]\\/span[contains(@class, \\"price-excluding-tax\\")][1]\\/span[contains(@class, \\"price\\")][1]\\/span[contains(@class, \\"int\\")][1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":true,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"product_tag","type":"tags_input","filename":"","path":"\\/\\/div[1]\\/div[contains(@class, \\"main\\")][2]\\/div[contains(@class, \\"grid-full\\")]\\/\\/ul\\/\\/li\\/span[contains(@class, \\"title\\")]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":true,"splitDelimiter":"-","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"product_cat","type":"tags_input","filename":"","path":"\\/\\/div[1]\\/div[contains(@class, \\"main\\")][2]\\/div[contains(@class, \\"grid-full\\")]\\/\\/ul\\/\\/li\\/span[contains(@class, \\"title\\")]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":true,"splitDelimiter":"-","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        18 =>
        array(
            'id' => '64',
            'name' => 'Openload.co Embed - fixed',
            'URL' => 'https://openload.co/f/rfU84UuB-b0/VID_163440705_204631_124.mp4',
            'template' => '{"feedURL":"","contentURL":"https:\\/\\/openload.co\\/f\\/rfU84UuB-b0\\/VID_163440705_204631_124.mp4","fieldsMode":"simple-post","singlePost":"true","feed":{"path":"","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/title","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}} - test","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"video_url","type":"video_url","filename":"","path":"\\/\\/textarea","prop":"innerHTML","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"true","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"(.*)src=\\"(.*?)\\"(.*)","replace":"$2"},{"find":"
","replace":""},{"find":"(\\\\r|\\\\n)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/meta[contains(@name, \\"image\\")]","prop":"attr:content","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        19 =>
        array(
            'id' => '65',
            'name' => 'Task for superbikegeorge',
            'URL' => 'https://www.returnofthecaferacers.com/feed/',
            'template' => '{"feedURL":"http:\\/\\/scraper.site\\/visual-editor\\/service\\/?request=rss&url=https:\\/\\/www.returnofthecaferacers.com\\/feed\\/","contentURL":"https:\\/\\/www.returnofthecaferacers.com\\/ride-review\\/husqvarna-vitpilen-701\\/","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(@class, \\"inner_field\\")]\\/a[contains(@class, \\"root-channel_item_link\\")]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(@class, \\"grid-x\\")][1]\\/div[contains(@class, \\"cell\\")][1]\\/div[contains(@class, \\"single-post__content-wrapper\\")][1]\\/div[contains(@class, \\"grid-x\\")][1]\\/div[contains(@class, \\"cell\\")][1]\\/h1[contains(@class, \\"single-post__title\\")][1] | \\/\\/h1[contains(@class, \\"single-post__title\\")][1]","prop":"innerText","filename":"","element":{},"display":false,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(@class, \\"cell\\")][1]\\/div[contains(@class, \\"single-post__content-wrapper\\")][1]\\/div[contains(@class, \\"grid-x\\")][1]\\/div[contains(@class, \\"cell\\")][1]\\/div[contains(@class, \\"content-block__content\\")][1]\\/p | \\/\\/p","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}} {{gallery}}","translate":"","customContent":false,"stripTags":true,"stripLinks":true,"stripAds":true,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/section[contains(@class, \\"featured-post\\")][1]\\/div[contains(@class, \\"grid-container\\")][1]\\/div[contains(@class, \\"grid-x\\")][1]\\/div[contains(@class, \\"cell\\")][1]\\/a\\/img[contains(@class, \\"featured-post__image\\")][1] | \\/\\/img[contains(@class, \\"featured-post__image\\")][1]","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_tag","type":"tags_input","filename":"","path":"\\/\\/div[contains(@class, \\"single-post__categories\\")]\\/ul[contains(@class, \\"post-categories\\")]\\/\\/a","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[{},{}],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        20 =>
        array(
            'id' => '66',
            'name' => 'Task for yurialfano',
            'URL' => 'https://cults3d.com/',
            'template' => '{"feedURL":"https:\\/\\/cults3d.com\\/en\\/categories\\/art","contentURL":"\\/en\\/3d-model\\/art\\/elegant-cat","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"tbox-thumb\\")]\\/img\\/ancestor::a | \\/\\/div[4]\\/div\\/div[5]\\/article[1]\\/div\\/a\\/img\\/ancestor::a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"creation-title\\")] | \\/\\/div[3]\\/div\\/div[1]\\/div[2]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"rich\\")]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[3]\\/div\\/div[1]\\/div[2]\\/div[4]\\/p[2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"thumb\\")] | \\/\\/div[3]\\/div\\/div[1]\\/div[1]\\/div[2]\\/div[1]\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"thumb\\")]\\/img | \\/\\/div[3]\\/div\\/div[1]\\/div[1]\\/div[2]\\/div[2]\\/a[1]\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"product_tag","type":"tags_input","filename":"","path":"\\/\\/ul[contains(@class, \\"inline-list\\")]\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"btn\\")]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"product_cat","type":"tags_input","filename":"","path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"btn-tabs__tab\\")]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"_cults_session=aXI2dkdvQWFtcGdBRWdGaVRBN2xUUkp0MUduZlJoOEl3SDBJZmdyczV2VnAyVjc0Z1hLbGZtREsrbVQ2b0hQbjdvejdWVjRhRXR3MktpaVk4ZG5pQk1tUTVkRU5xdWRtaHNjYk9FNXRNbmlvL0hFUGdDUkxvbmZTTjlGMnB0cUo0UHZKM0FPaUZIY2pJbWliSlU0MktLNytORlJaOUtnU2lRL3BXS0FBWS9uY1JtMUw3a0RabHVkUkNlRGZZR3dHRHNQUU5CYzdaYWxKWUZJUkRtNVpjc3IrWHdzVStqL2Ftd1NxaU1hWUVjVE9lMWhBYmNXSUtqNDUwWStRNE51MXFEdHhOcUVyTnlkb3hZUE9NNkJUL1luMmVYZUhvcEc5NjlHTzNBc3BNQVJITW1QNVBDOFlxTGF4K1l3b00ycjFZaGxSQWQ2UU5VM1hkZ1pTTUpYaEdkL1cyaE1VUmVSTXJ3dENOS1YxK3p6d1EweXdORVBVM2tRL3dtMVdMR1U5S2kwV3AyQ1ZUWXlibHVYOEgzVzRrQT09LS0wN005ZlNkRUZMV0tIZ1VxTzJBMHBRPT0%3D--94f37230ea1c2ff0495e507eb4fca552c207eb21;","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        21 =>
        array(
            'id' => '67',
            'name' => 'Test task for iframe test (mature content)',
            'URL' => 'https://www.sexy-youtubers.com/main-categories/twitch-streamer/',
            'template' => '{"feedURL":"https:\\/\\/www.sexy-youtubers.com\\/main-categories\\/twitch-streamer\\/","contentURL":"https:\\/\\/www.sexy-youtubers.com\\/eskimokisses-leaked","fieldsMode":"custom-post","singlePost":"true","feed":{"path":"\\/\\/div[contains(@class, \\"td-module-meta-info\\")]\\/h3[contains(@class, \\"entry-title\\")]\\/\\/a","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"\\/\\/link[@rel=\\"next\\"]","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/*[@id=\\"td-outer-wrap\\"]\\/div[2]\\/div[1]\\/div[2]\\/div[1]\\/div[1]\\/article[1]\\/div[1]\\/header[1]\\/h1[1]","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}}","find":"","replace":"","replaces":[{"find":"pics","replace":"pictures"}],"isNumber":"false","isMultiple":"false","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","customContent":"false","regexIndex":"-1","galleryColumns":"3","gallerySize":"medium","isJSON":"false","decodeBitly":"false","isRequired":"false"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/article\\/\\/img[1]","prop":"attr:src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","attributeParse":"none","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1","galleryColumns":"3","gallerySize":"medium","isJSON":"false","decodeBitly":"false","isRequired":"false","element":""},{"name":"variable_4","type":"post_category","filename":"","path":"\\/\\/a[contains(@title, \\"View all posts in\\")]","prop":"innerText","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","attributeParse":"none","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1","galleryColumns":"3","gallerySize":"medium","isJSON":"false","decodeBitly":"false","isRequired":"false"},{"name":"variable_5","type":"gallery","filename":"{{index}}","path":"\\/\\/article[contains(@class, \\"post\\")]\\/div[contains(@class, \\"td-post-content\\")]\\/\\/a","prop":"attr:original-href","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","attributeParse":"none","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","spinner":"false","regexIndex":"-1","galleryColumns":"3","gallerySize":"large","isJSON":"false","decodeBitly":"false","isRequired":"false"},{"name":"iframe_video","type":"variable","filename":"","path":"\\/\\/iframe[1]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","isRequired":"false"},{"name":"iframe_video2","type":"variable","filename":"","path":"\\/\\/iframe[2]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","element":""},{"name":"iframe_video3","type":"variable","filename":"","path":"\\/\\/iframe[3]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","element":""},{"name":"iframe_video4","type":"variable","filename":"","path":"\\/\\/iframe[4]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","element":""},{"name":"iframe_video5","type":"variable","filename":"","path":"\\/\\/iframe[5]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","element":""},{"name":"iframe_video6","type":"variable","filename":"","path":"\\/\\/iframe[6]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","element":""},{"name":"iframe_video7","type":"variable","filename":"","path":"\\/\\/iframe[7]","prop":"attr:data-lazy-src","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1","element":""},{"name":"iframe_video8","type":"variable","filename":"","path":"\\/\\/iframe[8]","prop":"innerText","element":"","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"iframe_video9","type":"variable","filename":"","path":"\\/\\/iframe[9]","prop":"innerText","element":"","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"iframe_video10","type":"variable","filename":"","path":"\\/\\/iframe[10]","prop":"innerText","element":"","display":"false","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"http(.*)","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"content_variable","type":"variable","filename":"","path":"\\/\\/*[@id=\\"td-outer-wrap\\"]\\/div[2]\\/div[1]\\/div[2]\\/div[1]\\/div[1]\\/article[1]\\/div[2]","prop":"innerHTML","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"true","stripLinks":"true","stripAds":"true","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"Share this:","replace":""},{"find":"Tweet","replace":""},{"find":"Related","replace":""},{"find":"See more of her here.","replace":""},{"find":"See more of her here","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"undefined_16","type":"post_content","filename":"","path":"-","prop":"innerText","element":"","display":"true","selecting":"false","content":"{{content_variable}} {{gallery}} {{iframe_video}} {{iframe_video2}} {{iframe_video3}} {{iframe_video4}} {{iframe_video5}} {{iframe_video6}} {{iframe_video7}} {{iframe_video8}} {{iframe_video9}} {{iframe_video10}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"See","replace":"View"}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        22 =>
        array(
            'id' => '68',
            'name' => 'Task for thingiverse',
            'URL' => 'https://www.thingiverse.com/Nitrostorm/collections/anycubic-i3-mega-upgrades',
            'template' => '{"feedURL":"https:\\/\\/www.thingiverse.com\\/Nitrostorm\\/collections\\/anycubic-i3-mega-upgrades","contentURL":"\\/thing:2706580","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/a[contains(@class, \\"card-img-holder\\")]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"item-page-info\\")]\\/h1 | \\/\\/div[1]\\/div[1]\\/div\\/div\\/div[2]\\/div[1]\\/div[1]\\/div\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[@id=\\"description\\"]\\/div[1]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[1]\\/div[1]\\/div\\/div\\/div[2]\\/div[1]\\/div[4]\\/div[2]\\/div[1]\\/div\\/div[1]\\/p[2] | \\/\\/div[@id=\\"description\\"]\\/parent::*[(self::p or self::div or self::span or self::li)]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"thing-img\\")] | \\/\\/div[1]\\/div[1]\\/div\\/div\\/div[2]\\/div[1]\\/div[2]\\/div[1]\\/div[1]\\/div[1]\\/span[2]\\/div[1]\\/img","prop":"attr:data-src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"thing-img\\")]","prop":"attr:data-src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"product_tag","type":"tags_input","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"taglist\\")]\\/\\/a | \\/\\/div[1]\\/div[1]\\/div\\/div\\/div[2]\\/div[1]\\/div[4]\\/div[1]\\/div[4]\\/div\\/div\\/a[1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"product_cat","type":"tags_input","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"taglist\\")]\\/\\/a | \\/\\/div[1]\\/div[1]\\/div\\/div\\/div[2]\\/div[1]\\/div[4]\\/div[1]\\/div[4]\\/div\\/div\\/a[2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        23 =>
        array(
            'id' => '69',
            'name' => 'Task for kapakhaberler',
            'URL' => 'http://www.kapakhaberler.com/meteoroloji-cok-onemli-uyari-bu-geceye-dikkat-resimleri,5081.html',
            'template' => '{"feedURL":"http:\\/\\/www.kapakhaberler.com\\/meteoroloji-cok-onemli-uyari-bu-geceye-dikkat-resimleri,5081.html","contentURL":"album-p2-aid,5081.html#galeri","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"pagination\\")]\\/span\\/\\/a[position() > 1] | \\/\\/div[2]\\/div[1]\\/div\\/div\\/div\\/div\\/div\\/div[1]\\/div[2]\\/span\\/a[2]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"\\/\\/link[@rel=\\"next\\"] | \\/\\/a[contains(concat (\\" \\", normalize-space(text()), \\" \\"), \\"»\\")] | \\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"page_next\\")] | \\/\\/div[2]\\/div[1]\\/div\\/div\\/div\\/div\\/div\\/div[1]\\/div[2]\\/span\\/a[10]","selecting":false,"samples":[],"sampleIndex":0,"element":{},"siblings":[{}]},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"gallery-header\\")]\\/p | \\/\\/div[2]\\/div[1]\\/div\\/div\\/div\\/div\\/div\\/div[2]\\/div\\/div[1]\\/p | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"featured_image","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"text-center\\")]\\/a\\/img | \\/\\/div[2]\\/div[1]\\/div\\/div\\/div\\/div\\/div\\/div[1]\\/div[1]\\/a\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        24 =>
        array(
            'id' => '70',
            'name' => 'Task for returnofthecaferacers',
            'URL' => 'http://returnofthecaferacers.com',
            'template' => '{"feedURL":"https:\\/\\/www.returnofthecaferacers.com\\/","contentURL":"https:\\/\\/www.returnofthecaferacers.com\\/something-different\\/mz-skorpion-cafe-racer\\/","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"btn\\")] | \\/\\/div[2]\\/div[2]\\/div\\/main\\/section[4]\\/div\\/div[1]\\/div[1]\\/a[2]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"single-post__title\\")] | \\/\\/div[2]\\/div[2]\\/div\\/main\\/article\\/section[1]\\/div\\/div[1]\\/div[1]\\/div\\/div[1]\\/div[1]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":false,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"content-block__content\\")]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[2]\\/div[2]\\/div\\/main\\/article\\/section[1]\\/div\\/div[1]\\/div[1]\\/div\\/div[1]\\/div[1]\\/div[2]\\/p[1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}} {{gallery}}","translate":"","customContent":false,"stripTags":true,"stripLinks":true,"stripAds":true,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"featured-post__image\\")] | \\/\\/div[2]\\/div[2]\\/div\\/main\\/section\\/div[2]\\/div\\/div\\/a\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_tag","type":"tags_input","filename":"","path":"\\/\\/ul[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"post-categories\\")]\\/li\\/\\/a | \\/\\/div[2]\\/div[2]\\/div\\/main\\/article\\/section[1]\\/div\\/div[1]\\/div[1]\\/div\\/div[1]\\/div[1]\\/div[1]\\/ul\\/li[1]\\/a","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        25 =>
        array(
            'id' => '71',
            'name' => 'Task template boredpanda for BigKev',
            'URL' => 'https://www.boredpanda.com/',
            'template' => '{"feedURL":"https:\\/\\/www.boredpanda.com\\/","contentURL":"https:\\/\\/www.boredpanda.com\\/deep-sea-creatures-photos-roman-fedortsov\\/?cexp_id=13609&cexp_var=31&_f=featured","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"title\\")] | \\/\\/main\\/section\\/article[1]\\/h2\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/meta[contains(@property, \\"og:title\\")]","prop":"attr:content","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"hidden-mobile-paragraphs\\")]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/main\\/div\\/div\\/div\\/div\\/i\\/div\\/i\\/div\\/div\\/div\\/div\\/div[2]\\/div\\/div[1]\\/div[3]\\/p[1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}} {{gallery}}","translate":"","customContent":false,"stripTags":true,"stripLinks":true,"stripAds":true,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"image-size-full\\")] | \\/\\/main\\/p[1]\\/a[2]\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"image-size-full\\")]","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        26 =>
        array(
            'id' => '72',
            'name' => 'Task template sankakucomplex for BigKev',
            'URL' => 'https://www.sankakucomplex.com/',
            'template' => '{"feedURL":"https:\\/\\/www.sankakucomplex.com\\/category\\/manga\\/","contentURL":"https:\\/\\/www.sankakucomplex.com\\/2018\\/11\\/13\\/rei-hiroes-new-manga-due-next-spring\\/","fieldsMode":"simple-post","singlePost":"false","feed":{"path":"\\/\\/h2[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-title\\")]\\/a | \\/\\/div[2]\\/div\\/div[2]\\/div\\/div\\/div[2]\\/div\\/article[1]\\/header\\/h2\\/a","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-title\\")]\\/a | \\/\\/div[2]\\/div\\/div[2]\\/div\\/main\\/article\\/header\\/h1\\/a | \\/\\/h1","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}}","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-content\\")]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[2]\\/div\\/div[2]\\/div\\/main\\/article\\/div[1]\\/p[2]","prop":"innerText","display":"true","selecting":"false","content":"{{content}} {{gallery}}","translate":"","customContent":"false","stripTags":"true","stripLinks":"true","stripAds":"true","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"lazy\\")] | \\/\\/div[2]\\/div\\/div[2]\\/div\\/main\\/article\\/div[1]\\/p[1]\\/a\\/img","prop":"attr:data-lazy-src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"lazy\\")]","prop":"attr:data-lazy-src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"post_tag","type":"tags_input","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"meta-tags\\")]\\/\\/a | \\/\\/div[2]\\/div\\/div[2]\\/div\\/div[1]\\/div\\/div\\/a[5]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"bp-activity-oldestpage=1;hmn_cp_visitor=213.194.89.33;","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        27 =>
        array(
            'id' => '73',
            'name' => 'Foursquare Template',
            'URL' => 'https://foursquare.com/explore?mode=url&near=Istanbul%2C%20Turkey&nearGeoId=72057594038672980',
            'template' => '{"feedURL":"https:\\/\\/foursquare.com\\/explore?mode=url&near=Istanbul%2C%20Turkey&nearGeoId=72057594038672980","contentURL":"\\/v\\/galata-kulesi\\/4b732d5bf964a52011a02de3","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"venueName\\")]\\/h2\\/a | \\/\\/div[2]\\/div[2]\\/div\\/div[2]\\/div[2]\\/div[2]\\/ul\\/li[1]\\/div[2]\\/div[1]\\/div[1]\\/div\\/div[1]\\/h2\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"venueName\\")] | \\/\\/div[2]\\/div[2]\\/div[1]\\/div[3]\\/div[1]\\/div[1]\\/div[2]\\/div[1]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"venueRowContent\\")] | \\/\\/div[2]\\/div[2]\\/div[1]\\/div[3]\\/div[3]\\/div[1]\\/div[2]\\/div[2]\\/div[2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/li[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"photo\\")]\\/img | \\/\\/div[2]\\/div[2]\\/div[1]\\/div[2]\\/div\\/ul\\/li[1]\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"rating","type":"variable","filename":"","path":"\\/\\/div[2]\\/div[2]\\/div[1]\\/div[3]\\/div[1]\\/div[2]\\/div[2]\\/span\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        28 =>
        array(
            'id' => '74',
            'name' => 'Task for marcareklam',
            'URL' => 'https://therighthairstyles.com/20-classy-short-bob-haircuts-and-hairstyles-with-bangs/28/',
            'template' => '{"feedURL":"https:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/16\\/","contentURL":"https:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/17\\/","fieldsMode":"simple-post","singlePost":true,"feed":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"siblings":[]},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"theiaPostSlider_slides\\")]\\/div\\/h3 | \\/\\/div[2]\\/div\\/div[2]\\/div[2]\\/div\\/h3 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"theiaPostSlider_slides\\")]\\/div\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[2]\\/div\\/div[2]\\/div[2]\\/div\\/p","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"wp-caption\\")]\\/img | \\/\\/div[2]\\/div\\/div[2]\\/div[2]\\/div\\/div\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""},"other":{"noStatusChange":false,"postFormat":"0","bulkURL":"https:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/1\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/2\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/3\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/4\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/5\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/6\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/7\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/8\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/9\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/10\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/11\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/12\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/13\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/14\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/15\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/16\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/17\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/18\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/19\\/\\nhttps:\\/\\/therighthairstyles.com\\/20-classy-short-bob-haircuts-and-hairstyles-with-bangs\\/20\\/"}}',
            'approved' => '1',
        ),
        29 =>
        array(
            'id' => '77',
            'name' => 'fortniteinsider',
            'URL' => 'https://fortniteinsider.com/',
            'template' => '{"feedURL":"https:\\/\\/fortniteinsider.com\\/","contentURL":"https:\\/\\/fortniteinsider.com\\/epic-games-have-announced-account-merging-is-delayed\\/","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/h3[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-title\\")]\\/a | \\/\\/div[6]\\/div[2]\\/div\\/div[2]\\/div\\/div[1]\\/div\\/div[2]\\/div[2]\\/div[1]\\/div[1]\\/div\\/h3\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-title\\")] | \\/\\/div[6]\\/article\\/div[1]\\/div[2]\\/header\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":false,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"td-post-content\\")]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[6]\\/article\\/div[2]\\/div\\/div[1]\\/div\\/div[2]\\/p[1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}} {{gallery}}","translate":"","customContent":false,"stripTags":true,"stripLinks":true,"stripAds":true,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"Advertisement","replace":""},{"find":"(Facebook|Twitter|Google|Pinterest|WhatsApp)","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-thumb\\")] | \\/\\/div[6]\\/article\\/div[1]\\/div[2]\\/div\\/a\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_tag","type":"tags_input","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"td-post-small-box\\")]\\/a | \\/\\/div[6]\\/article\\/div[2]\\/div\\/div[1]\\/div\\/footer\\/div[1]\\/div\\/div\\/a","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""},"other":{"noStatusChange":false,"postFormat":"0","bulkURL":""}}',
            'approved' => '1',
        ),
        30 =>
        array(
            'id' => '78',
            'name' => 'Task for Lucian',
            'URL' => 'https://www.imobiliare.ro/vanzare-apartamente/bucuresti/prelungirea-ghencea/apartament-de-vanzare-2-camere-X7QH0006B',
            'template' => '{"feedURL":"","contentURL":"https:\\/\\/www.imobiliare.ro\\/vanzare-apartamente\\/bucuresti\\/prelungirea-ghencea\\/apartament-de-vanzare-2-camere-X7QH0006B","fieldsMode":"simple-post","singlePost":true,"feed":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"siblings":[]},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"titlu\\")]\\/h1 | \\/\\/div[3]\\/div[2]\\/div\\/main\\/div\\/div[2]\\/div[1]\\/div\\/div[1]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":false,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[@id=\\"galerie_detalii\\"]\\/\\/img | \\/\\/div[3]\\/div[2]\\/div\\/main\\/div\\/div[2]\\/div[2]\\/div\\/div[1]\\/div[2]\\/\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/li[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"imagine\\")]\\/figure\\/a","prop":"attr:original-href","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":true,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"room_number","type":"variable","filename":"","path":"\\/\\/*[@id=\\"b_detalii_caracteristici\\"]\\/div\\/div[1]\\/ul\\/li[1]\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"area","type":"variable","filename":"","path":"\\/\\/div[3]\\/div[2]\\/div\\/main\\/div\\/div[2]\\/div[1]\\/div\\/div[1]\\/div\\/div\\/div[1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"price","type":"variable","filename":"","path":"\\/\\/div[3]\\/div[2]\\/div\\/main\\/div\\/div[2]\\/div[2]\\/div\\/div[1]\\/div[1]\\/div[1]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":true,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":true,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"year_of_construction","type":"variable","filename":"","path":"\\/\\/*[@id=\\"b_detalii_caracteristici\\"]\\/div\\/div[2]\\/ul\\/li[1]\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"utilitati","type":"variable","filename":"","path":"\\/\\/ul[contains(@class, \\"utilitati\\")]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"finishes","type":"variable","filename":"","path":"\\/\\/ul[contains(@class, \\"utilitati\\")][2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"comprising","type":"variable","filename":"","path":"\\/\\/*[@id=\\"b_detalii_caracteristici\\"]\\/div\\/div[1]\\/ul\\/li[5]\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"details","type":"variable","filename":"","path":"\\/\\/*[@id=\\"b_detalii_text\\"]\\/p","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_12","type":"post_category","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"container-breadcrumbs\\")]\\/ul[1]\\/li[7]\\/a\\/span | \\/\\/div[3]\\/div[2]\\/div\\/div[2]\\/div\\/ul[1]\\/li[7]\\/a\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_tag","type":"tags_input","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"container-breadcrumbs\\")]\\/ul\\/\\/li\\/\\/a\\/span | \\/\\/div[3]\\/div[2]\\/div\\/div[2]\\/div\\/ul[1]\\/li[5]\\/a\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"map_coordinates","type":"variable","filename":"","path":"\\/\\/a[contains(@id, \\"meniu-poi-localizare\\")]","prop":"attr:rel","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"(.*?)lat\\\\\\/(.*?)\\\\\\/lon\\\\\\/(.*?)\\\\\\/(.*)","replace":"$2, $3"}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        31 =>
        array(
            'id' => '79',
            'name' => 'Wikipedia - Single Post Test',
            'URL' => 'https://en.wikipedia.org/wiki/Entropy',
            'template' => '{"feedURL":"","contentURL":"https:\\/\\/en.wikipedia.org\\/wiki\\/Entropy","fieldsMode":"simple-post","singlePost":"true","feed":{"path":"","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[@id=\\"firstHeading\\"] | \\/\\/div[3]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","display":"true","selecting":"false","content":"{{content}}","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"mw-parser-output\\")]\\/p | \\/\\/div[3]\\/div[3]\\/div[4]\\/div\\/p","prop":"innerHTML","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        32 =>
        array(
            'id' => '80',
            'name' => 'Task for manuelscp',
            'URL' => 'https://www.nortravel.pt/optitravel/online/www/layout15/reserve/listagem.php?pkt_type=EUR&texto=CIRCUITOS%20NA%20EUROPA',
            'template' => '{"feedURL":"https:\\/\\/www.nortravel.pt\\/optitravel\\/online\\/www\\/layout15\\/reserve\\/listagem.php?pkt_type=EUR&texto=CIRCUITOS%20NA%20EUROPA","contentURL":"\\/optitravel\\/online\\/www\\/layout15\\/reserve\\/pkt_info.php?id=676","fieldsMode":"simple-post","singlePost":"false","feed":{"path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"botao-ver\\")] | \\/\\/div[1]\\/div[3]\\/div[1]\\/div\\/div\\/a\\/div\\/ancestor::a","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"titulo_pkt_static\\")] | \\/\\/div[1]\\/div\\/div[1]\\/div[2] | \\/\\/h1","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}}","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[@id=\\"itinerario\\"]\\/parent::*[(self::p or self::div or self::span or self::li)] | \\/\\/div[1]\\/div\\/div[1]\\/div[4]\\/div[3]\\/div\\/div\\/div[1]\\/p[2] | \\/\\/div[@id=\\"itinerario\\"]\\/parent::*[(self::p or self::div or self::span or self::li)]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"item\\")]\\/a\\/img | \\/\\/div[1]\\/div\\/div[1]\\/div[3]\\/div[1]\\/div\\/div\\/div[1]\\/a\\/img","prop":"attr:src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"product_tag","type":"tags_input","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"link-path\\")]\\/\\/a | \\/\\/div[1]\\/div\\/div[1]\\/div[1]\\/a[2]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        33 =>
        array(
            'id' => '81',
            'name' => 'Task for stuvera',
            'URL' => 'https://hogfurniture.com.ng/collections/bar-stools',
            'template' => '{"feedURL":"https:\\/\\/hogfurniture.com.ng\\/collections\\/bar-stools","contentURL":"\\/collections\\/bar-stools\\/products\\/home-bar-cabinet-system-bgt-bespoke","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"desc\\")]\\/h5\\/a | \\/\\/div[1]\\/div[4]\\/div\\/div\\/div[3]\\/div[1]\\/div\\/div[2]\\/div[1]\\/div\\/div[2]\\/h5\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"title\\")]\\/h1 | \\/\\/div[1]\\/div[4]\\/div\\/div\\/div[1]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"rte\\")]\\/p\\/span\\/p | \\/\\/div[1]\\/div[4]\\/div\\/div\\/div[2]\\/div[1]\\/div[2]\\/div\\/form\\/div\\/div[3]\\/p\\/span\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/meta[contains(@property, \\"og:image\\")]","prop":"attr:content","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"_price","filename":"","path":"\\/\\/div[1]\\/div[4]\\/div\\/div\\/div[2]\\/div[1]\\/div[2]\\/div\\/form\\/div\\/div[2]\\/div[2]\\/span[1]\\/span","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":true,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":true,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_tag","type":"tags_input","filename":"","path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"tag\\")]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        34 =>
        array(
            'id' => '82',
            'name' => 'Task for stuvera',
            'URL' => 'https://www.jumia.com.ng/smartphones/',
            'template' => '{"feedURL":"https:\\/\\/www.jumia.com.ng\\/smartphones\\/","contentURL":"https:\\/\\/www.jumia.com.ng\\/y9-2019-4g-6.5-4gb-64gb-android-8.1-oreo-16mp-2mp-13mp-2mp-4000-mah-sapphire-blue-huawei-mpg263594.html","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(@class, \\"sku\\")]\\/\\/a[contains(@class, \\"link\\")]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/h1[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"title\\")] | \\/\\/main\\/section[1]\\/div[2]\\/div[1]\\/span\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[{}],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(@class, \\"features\\")]\\/\\/li","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[@id=\\"productImage\\"] | \\/\\/main\\/section[1]\\/div[1]\\/div[3]\\/img","prop":"attr:data-zoom","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[{}],"regexIndex":-1,"regexResults":[]},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/img[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"lazy\\")]","prop":"attr:data-zoom","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"regexIndex":-1,"regexResults":[]},{"name":"variable_5","type":"_price","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"price\\")]\\/span[2] | \\/\\/main\\/section[1]\\/div[2]\\/div[1]\\/div[6]\\/div[3]\\/div[2]\\/div\\/div\\/span[2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":true,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":true,"splitContent":false,"splitDelimiter":",","math":"value \\/ 1000","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[{},{}],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        35 =>
        array(
            'id' => '83',
            'name' => 'Task codelist.cc',
            'URL' => 'http://codelist.cc',
            'template' => '{"feedURL":"http:\\/\\/www.codelist.cc\\/","contentURL":"http:\\/\\/www.codelist.cc\\/plugins\\/235849-image-overlay-flip-box-v142-page-builder-add-on.html","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"news-title\\")]\\/h3\\/a | \\/\\/div[1]\\/div\\/div[1]\\/div[2]\\/div[6]\\/div[2]\\/h3\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"\\/\\/link[@rel=\\"next\\"] | \\/\\/a[contains(concat (\\" \\", normalize-space(text()), \\" \\"), \\"Next\\")] | \\/\\/ul[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"pagination\\")]\\/li[3]\\/a | \\/\\/div[1]\\/div\\/div[1]\\/div[2]\\/div[13]\\/ul\\/li[3]\\/a","selecting":false,"samples":[],"sampleIndex":0,"element":{},"siblings":[{}]},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"full\\")]\\/h1\\/b | \\/\\/div[1]\\/div\\/div[1]\\/div[2]\\/div[1]\\/h1\\/b | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"full-news\\")] | \\/\\/div[1]\\/div\\/div[1]\\/div[2]\\/div[1]\\/div[3]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"full-news\\")]\\/img | \\/\\/div[1]\\/div\\/div[1]\\/div[2]\\/div[1]\\/div[3]\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""},"other":{"noStatusChange":false,"postFormat":"0","bulkURL":""}}',
            'approved' => '1',
        ),
        36 =>
        array(
            'id' => '84',
            'name' => 'Task for webmaster1453',
            'URL' => 'https://www.zkturkiye.com/',
            'template' => '{"feedURL":"https:\\/\\/www.zkturkiye.com\\/","contentURL":"\\/urun\\/k70-personel-takip-terminali","fieldsMode":"simple-product","singlePost":"false","feed":{"path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"showcaseView\\")] | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div\\/table[1]\\/\\/tr\\/td[1]\\/div\\/div\\/a","selecting":"false","sampleIndex":"0"},"nextPage":{"path":"","selecting":"false","sampleIndex":"0"},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/tr[@id=\\"urun_adi\\"]\\/td | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/div[2]\\/div[1]\\/table\\/\\/tr\\/td[2]\\/div\\/div[1]\\/table\\/\\/tr[1]\\/td | \\/\\/h1","prop":"innerText","filename":"","display":"false","selecting":"false","content":"{{content}}","isRequired":"false","extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","attributeParse":"none","spinner":"false","translate":"","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","customContent":"false","regexIndex":"-1"},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"ProductDetail\\")]\\/ul[1] | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/div[2]\\/div[3]\\/div[2]\\/div[1]\\/div\\/ul[1]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"true","stripLinks":"true","stripAds":"true","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/img[@id=\\"PrimaryImage\\"] | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/div[2]\\/div[1]\\/table\\/\\/tr\\/td[1]\\/div\\/div[1]\\/div\\/div\\/div\\/div\\/a\\/img","prop":"attr:src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"_min","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_4","type":"gallery","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"_floatLeft\\")]\\/a\\/img | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/div[2]\\/div[1]\\/table\\/\\/tr\\/td[1]\\/div\\/div[3]\\/div[2]\\/a\\/img","prop":"attr:src","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"_min","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_5","type":"_price","filename":"","path":"\\/\\/tr[@id=\\"kdv_dahil_cevrilmis_fiyat\\"]\\/td | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/div[2]\\/div[1]\\/table\\/\\/tr\\/td[2]\\/div\\/div[1]\\/table\\/\\/tr[3]\\/td","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"true","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"true","splitContent":"false","splitDelimiter":",","math":"value \\/ 100","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"product_tag","type":"tags_input","filename":"","path":"\\/\\/tr[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"rowspan\\")]\\/td\\/\\/a | \\/\\/div[4]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/div[2]\\/div[1]\\/table\\/\\/tr\\/td[2]\\/div\\/div[2]\\/table\\/\\/tr[1]\\/td[2]\\/a","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"product_cat","type":"tags_input","filename":"","path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"_displayInlineBlock\\")]","prop":"innerText","display":"true","selecting":"false","content":"{{content}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"true","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"},{"name":"variable_8","type":"_product_url","filename":"","path":"-","prop":"attr:href","element":"","display":"true","selecting":"false","content":"{{source_url}}","translate":"","customContent":"false","stripTags":"false","stripLinks":"false","stripAds":"false","decodeBitly":"false","attributeParse":"none","extract":"html","isRequired":"false","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":"false","isMultiple":"false","isJSON":"false","galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":"false","splitContent":"false","splitDelimiter":",","math":"value","clipStart":"0","clipEnd":"0","clipWordStart":"0","clipWordEnd":"0","spinner":"false","regexIndex":"-1"}],"other":{"noStatusChange":"false","postFormat":"0","bulkURL":""},"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""}}',
            'approved' => '1',
        ),
        37 =>
        array(
            'id' => '85',
            'name' => 'Task for Ssavita9986',
            'URL' => 'https://www.full4movies.co/',
            'template' => '{"feedURL":"https:\\/\\/www.full4movies.co\\/","contentURL":"https:\\/\\/www.full4movies.co\\/watch-the-vanishing-online-english-full-movie-free-download\\/","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/div[contains(@class, \\"item\\")]\\/a","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"data\\")]\\/h1 | \\/\\/div[3]\\/div\\/div[2]\\/div[15]\\/div[1]\\/div[2]\\/h1 | \\/\\/h1","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"entry-content\\")]\\/div[1]\\/p | \\/\\/div[3]\\/div\\/div[2]\\/div[15]\\/div[3]\\/div\\/div[1]\\/p[2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"featured_image_3","type":"featured_image","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"fix\\")]\\/img | \\/\\/div[3]\\/div\\/div[2]\\/div[15]\\/div[1]\\/div[1]\\/div\\/img","prop":"attr:src","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"download_1","type":"variable","filename":"","path":"\\/\\/a[contains(@class, \\"myButton\\")]","prop":"attr:original-href","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"download_2","type":"variable","filename":"","path":"\\/\\/a[contains(@class, \\"myButton\\")][2]","prop":"attr:original-href","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"download_3","type":"variable","filename":"","path":"\\/\\/a[contains(@class, \\"myButton\\")][3]","prop":"attr:original-href","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":""},"other":{"noStatusChange":false,"postFormat":"0","bulkURL":""}}',
            'approved' => '1',
        ),
        38 =>
        array(
            'id' => '86',
            'name' => 'Task for greyhound',
            'URL' => 'https://www.timeform.com/greyhound-racing',
            'template' => '{"feedURL":"https:\\/\\/www.timeform.com\\/greyhound-racing","contentURL":"\\/greyhound-racing\\/racecards\\/henlow\\/934\\/20190125\\/647129","fieldsMode":"simple-post","singlePost":false,"feed":{"path":"\\/\\/a[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"bg-blue\\")]","selecting":false,"samples":[],"sampleIndex":0,"siblings":[{},{},{},{},{},{},{},{},{},{}],"element":{}},"nextPage":{"path":"","selecting":false,"samples":[],"sampleIndex":0,"element":{}},"fields":[{"name":"post_title","type":"post_title","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"rph-race-details-col\\")]\\/b","prop":"innerText","filename":"","element":{},"display":true,"selecting":false,"content":"{{content}}","isRequired":false,"extract":"html","find":"","replace":"","replaces":[{"find":"^[ \\\\t]*$\\\\r?\\\\n","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"attributeParse":"none","spinner":false,"translate":"","stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"customContent":false,"transform":[],"siblings":[],"regexIndex":-1,"regexResults":[]},{"name":"post_content_2","type":"post_content","filename":"","path":"\\/\\/div[contains(concat (\\" \\", normalize-space(@class), \\" \\"), \\"rph-race-details\\")] | \\/\\/main\\/section[2]\\/section[1]\\/div[2]","prop":"innerText","element":{},"display":true,"selecting":false,"content":"{{content}}","translate":"","customContent":false,"stripTags":false,"stripLinks":false,"stripAds":false,"decodeBitly":false,"attributeParse":"none","transform":[],"extract":"html","isRequired":false,"find":"","replace":"","replaces":[{"find":"","replace":""}],"isNumber":false,"isMultiple":false,"isJSON":false,"galleryColumns":"3","gallerySize":"medium","cleanNonNumerical":false,"splitContent":false,"splitDelimiter":",","math":"value","clipStart":0,"clipEnd":0,"clipWordStart":0,"clipWordEnd":0,"spinner":false,"siblings":[],"regexIndex":-1,"regexResults":[]}],"connection":{"cookie":"","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/65.0.3325.181 Safari\\/537.36","proxy":"","ignore_params":false,"total_run":0}}',
            'approved' => '1',
        ),
    );
}

header('Content-Type: application/json');
echo json_encode($output);

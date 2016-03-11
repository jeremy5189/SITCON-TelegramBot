<?php
set_time_limit(60);
date_default_timezone_set('Asia/Taipei');
header('Accept: application/json');

// Rand Seed
srand(time());

include_once('config.php');

$cmd_list = array(
    'test',
    'help',
    'moo',
    'burn',
    'url',
    'uptime',
    '燒毀',
);

$dict = array(
    '靈的轉移',
    '巫術的權勢',
    '被東方閃電擄去了',
    '耶穌的寶血塗抹潔淨！聖靈的活水沖洗乾淨！',
    '拿勝利寶劍！',
    '斷開魂結，斷開鎖鏈！斷開一切的牽連！',
    '燒毀同性戀的網羅'
);

$sitcon_cap = array(
	'SITCON 全大寫！',
	'叭叭！抓到了！SITCON 沒全大寫！',
	'逼逼！SITCON 沒全大寫！請退選！',
	'叭叭！SITCON 沒全大寫！這樣對嗎？',
	'抓到了齁，SITCON 沒大寫',
	'Make SITCON great again'
);

// Get telegram data
$json = file_get_contents('php://input') . PHP_EOL;
$data = json_decode($json, true);

// Log it
$time = date('Y-m-d H:i:s', time());
logging("<" .$time . "> Hook invoked\n");
logging($data);

// Get Data
$updateID = $data['update_id'];
$messageID = $data['message']['message_id'];
$fromID = $data['message']['from']['id'];
$chatID = $data['message']['chat']['id'];
$date = $data['message']['date'];
$userName = $data['message']['from']['username'];
$message = $data['message']['text'];

// Skip user without telegram ID
if( $userName != "" ) {
    
    // ---------------
    // SITCON 全大寫
    // ---------------

    if ( preg_match('/([^\\.#]|^)(s[Ii][Tt][Cc][Oo][Nn]|[sS]i[Tt][Cc][Oo][Nn]|[sS][Ii]t[Cc][Oo][Nn]|[sS][Ii][Tt]c[Oo][Nn]|[sS][Ii][Tt][Cc]o[Nn]|[sS][Ii][Tt][Cc][Oo]n)([^a-zA-Z.]|$)/', $message) === 1 ) {

	logging('Dealing with SITCON: ' . $message);

        $msg = '@' .$userName . ' ';
        $msg .= $sitcon_cap[rand(0, count($sitcon_cap)-1)];
        sendMsg($msg);

    }


    // Is command
    if( substr($message, 0, 1) == "/" ) {
        
        $cmd = str_replace('@'.BOT_NAME, '', $message);
        $message = strtolower($message);
        logging($cmd);
        $cmd = split(' ', $cmd);

	if($cmd[0] == '/url') {
            sendMsg('SITCON group link: http://sitcon.org/tg');
	}
        else if( !(intval($chatID) == -1001033025246) ) { 

            switch ($cmd[0]) {

                case "/test":
                    test();
                    break;

                case "/help":
                    help($cmd_list);
                    break;

                case "/moo":
                    moo();
                    break;

                case "/burn":
                case "/燒毀":
                    burn($dict);
                    break;

        		case "/uptime":
        		    uptime();
        		    break;
                
            }

        } 
        else {
           	// In SITCON group
            // Do nothing
    	}

    } // if(substr($message, 0, 1) == "/")
    else { 

        // 訊息不是 / 開頭

    }
}
else {

    // No user name
    sendMsg('請先設定 Telegram User ID，謝謝');

}

function burn($burn_dict) {

    $index = rand(0, count($burn_dict));
    sendMsg($burn_dict[$index]);
}

function run_shell_cmd($cmd, $param, $do_sprint = true, $private = false) {

    if($do_sprint)
        $cmd = sprintf($cmd, $param);

    logging("Shell Command: " . $cmd);

    exec("$cmd", $output, $status);
    $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
    foreach($output as $line){
        $msg .= $line . PHP_EOL;
    }

    if($private)
        sendPrivateMsg($msg);
    else
        sendMsg($msg);

}

function moo() {
    run_shell_cmd('apt-get moo');
}

function uptime() {
    run_shell_cmd('uptime');
}

function test(){
    sendMsg('Make SITCON great again.');
}

function help($cmd_list){
    $str = 'Available Commands' . "\n(以下指令不適用於群組內)\n\n";
    foreach( $cmd_list as $cmd ) {
        $str .= "/$cmd\n";
    }
    sendMsg($str);
}


function sendMsg($m){
    $cid = $GLOBALS['chatID'];
    $mid = $GLOBALS['messageID'];
    $m = urlencode($m);
    $ch = curl_init("https://api.telegram.org/bot" . TOKEN . "/sendMessage?chat_id=" . $cid . "&reply_to_message_id=" . $mid . "&text=" . $m);
    curl_exec($ch);
    curl_close($ch);
}

function sendPrivateMsg($m){
    $cid = $GLOBALS['fromID'];
    $m = urlencode($m);
    $ch = curl_init("https://api.telegram.org/bot" . TOKEN . "/sendMessage?chat_id=" . $cid . "&text=" . $m);
    curl_exec($ch);
    curl_close($ch);
}

function error($id){
    switch ($id) {
        case 1:
            $msg = '@' . $GLOBALS['userName'] . ': Request Timeout!!';
            sendMsg($msg);
            break;

        case 2:
            $msg = '@' . $GLOBALS['userName'] . ': Permission Denied!!';
            sendMsg($msg);
            break;

        case 3:
            $msg = '@' . $GLOBALS['userName'] . ': Command Not Found!!';
            sendMsg($msg);
            break;

        case 4:
            $msg = '@' . $GLOBALS['userName'] . ': Bad Parameters!!';
            sendMsg($msg);
            break;
        
        default:
            $msg = '@' . $GLOBALS['userName'] . ': Unknown Error!!';
            sendMsg($msg);
            break;
    }
}

function logging($d){
    $dump = print_r($d, true) . PHP_EOL;
    $f = fopen('bot.log', 'a');
    fwrite($f, $dump);
    fclose($f);
}

function debug($d){
    $dump = print_r($d, true) . PHP_EOL;
    $f = fopen('debug.log', 'a');
    fwrite($f, $dump);
    fclose($f);
}

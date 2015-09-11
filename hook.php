<?php
set_time_limit(60);
date_default_timezone_set('Asia/Taipei');
header('Accept: application/json');

srand(time());

include_once('config.php');

$cmd_list = array(
    'ping (!群組)',
    'traceroute (!群組)',
    'nslookup (!群組)',
    'whois (!群組)',
    'test',
    'help',
    'moo',
    'pull',
    'status',
    'log',
    'keygen (Message bot first)',
    'burn',
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

// Get telegram data
$json = file_get_contents('php://input') . PHP_EOL;
$data = json_decode($json, true);

// Log it
$time = date('Y-m-d H:i:s', time());
logging("<" .$time . "> Hook invoked\n");
logging($data);

$updateID = $data['update_id'];
$messageID = $data['message']['message_id'];
$fromID = $data['message']['from']['id'];
$chatID = $data['message']['chat']['id'];
$date = $data['message']['date'];
$userName = $data['message']['from']['username'];
$message = $data['message']['text'];

if($userName != ""){
    
    if(substr($message, 0, 1) == "/") {
        if( true ) { // For user check

            $cmd = str_replace('@'.BOT_NAME, '', $message);
            $message = strtolower($message);
            logging($cmd);
            $cmd = split(' ', $cmd);

            switch ($cmd[0]) {
                case "/ping":
                    if(intval($chatID) < 0)
                        break;

                    if(count($cmd) == 2){
                        ping($cmd[1]);  
                    }else{
                        error(4);
                    }
                    break;

                /*case "/ping6":
                    if(count($cmd) == 2){
                        ping6($cmd[1]); 
                    }else{
                        error(4);
                    }
                    break;*/

                case "/traceroute":
                    if(intval($chatID) < 0)
                        break;
                    if(count($cmd) == 2){
                        traceroute($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;

                /*case "/traceroute6":
                    if(count($cmd) == 2){
                        traceroute6($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;*/
                
                case "/nslookup":
                    if(intval($chatID) < 0)
                        break;
                    if(count($cmd) == 3){
                        nslookup($cmd[1], $cmd[2]);
                    }if(count($cmd) == 2){
                        nslookup($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;

                case "/whois":
                    if(intval($chatID) < 0)
                        break;
                    if(count($cmd) == 2){
                        whois($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;

                case "/test":
                    test();
                    break;

                case "/curl":
                    curl($cmd[1]);
                    //run_shell_cmd('curl %s', $cmd[1]);
                    break;

                case "/help":
                    help($cmd_list);
                    break;

                /*case "/search":
                    if(count($cmd) == 3){
                        search($cmd[1], $cmd[2]);
                    }else{
                        error(4);
                    }
                    break;*/

                case "/moo":
		            moo();
                    break;

                case "/pull":
                    git("pull github master");
                    break;

                case "/status":
                    git("status");
                    break;

                case "/log":
                    git('log --pretty=format:"%h - %an, %ar : %s"');
                    break;

                case "/burn":
                case "/燒毀":
                    burn($dict);
                    break;

                case "/keygen":
                    keygen();
                    break;

                default:
                    if(strpos($message, "@".BOT_NAME)){
                        sendMsg("我沒這指令, 你想做什麼??");
                    }
                    //error(3);
                    break;
            }

        }else{
            error(2);
        }
    }
    else {

        // 訊息不是 / 開頭

        if(strpos($message, "@".BOT_NAME) !== false){
            sendMsg("嗨~ Tag 我幹嘛?");
        }

        if ( preg_match('/[Ss][Ii][Tt][Cc][Oo][Nn][f]{0,1}/',$message) === 1 && 
             preg_match('/SITCON/',$message) !== 1 ) {
             $msg = '@' .$userName . PHP_EOL;
             $msg = ' SITCON 全大寫！';
             sendMsg($msg);
        }
    }
}

function keygen() {
    $uniq = sha1(time().uniqid());
    run_shell_cmd("ssh-keygen -t rsa -b 1024 -f /tmp/%s.key -N ''", $uniq);
    run_shell_cmd("cat /tmp/%s.key.pub", $uniq);
    run_shell_cmd("cat /tmp/%s.key", $uniq, true, true);
}

function curl($url) {

    if( filter_var($url, FILTER_VALIDATE_URL) ) {

        //run_shell_cmd('curl %s', $url);
        sendMsg('維修中...');

    } else {

        error(4);
        
    }
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

function ping($host) {

    if( filter_var($host, FILTER_VALIDATE_IP) ||
        filter_var(gethostbyname($host), FILTER_VALIDATE_IP) ||
        is_domain($host)) {

        run_shell_cmd('timeout 30 /bin/ping -c 4 %s', $host);

    } else {

        error(4);

    }
}

function ping6($host) {

    if( filter_var($host, FILTER_VALIDATE_IP) ||
        filter_var(gethostbyname($host), FILTER_VALIDATE_IP) ||
        is_domain($host)) {

        run_shell_cmd('timeout 30 /bin/ping6 -c 4 %s', $host);

    } else {

        error(4);
        
    }
}

function traceroute($host)  {

    if( filter_var($host, FILTER_VALIDATE_IP) ||
        filter_var(gethostbyname($host), FILTER_VALIDATE_IP) ||
        is_domain($host)) {

        run_shell_cmd("timeout 30 /usr/bin/traceroute -n -w 15 %s | grep -vi '* * *'", $host);

    } else {

        error(4);
        
    }
}

function traceroute6($host) {

    if( filter_var($host, FILTER_VALIDATE_IP) ||
        filter_var(gethostbyname($host), FILTER_VALIDATE_IP) ||
        is_domain($host)) {

        run_shell_cmd("timeout 30 /usr/bin/traceroute6 -n -w 15 %s | grep -vi '* * *'", $host);

    } else {

        error(4);
        
    }

}

function nslookup($host, $server = "8.8.8.8") {

    if ((filter_var($host, FILTER_VALIDATE_IP) ||
         filter_var(gethostbyname($host), FILTER_VALIDATE_IP) ||
         is_domain($host) 
        ) && (
         filter_var($server, FILTER_VALIDATE_IP)
        )) {

        run_shell_cmd('timeout 30 /usr/bin/nslookup %s', $host . ' ' . $server);

    } else {

        error(4);
        
    }
}

function whois($host){

    if( filter_var($host, FILTER_VALIDATE_IP) ||
        filter_var(gethostbyname($host), FILTER_VALIDATE_IP) ||
        is_domain($host)) {

        run_shell_cmd('timeout 30 /usr/bin/whois %s', $host . ' ' . $server);

    } else {

        error(4);
        
    }
}

function git($git_cmd) {
    run_shell_cmd("git $git_cmd", '', false);
}

function moo() {
    run_shell_cmd('apt-get moo');
}

function test(){
    sendMsg('安安你好我是機器人');
}

function help($cmd_list){
    $str = 'Available Command' . "\n";
    foreach( $cmd_list as $cmd ) {
        $str .= "/$cmd\n";
    }
    sendMsg($str);
}

function search($u, $i){
    return null;
}

function is_domain($d,$clean=false){
    if($clean === true) $d = checkName::cleanURL($d);
    $tlds = "/^[-a-z0-9]{1,63}\.(ac\.nz|co\.nz|geek\.nz|gen\.nz|kiwi\.nz|maori\.nz|net\.nz|org\.nz|school\.nz|ae|ae\.org|com\.af|asia|asn\.au|auz\.info|auz\.net|com\.au|id\.au|net\.au|org\.au|auz\.biz|az|com\.az|int\.az|net\.az|org\.az|pp\.az|biz\.fj|com\.fj|info\.fj|name\.fj|net\.fj|org\.fj|pro\.fj|or\.id|biz\.id|co\.id|my\.id|web\.id|biz\.ki|com\.ki|info\.ki|ki|mobi\.ki|net\.ki|org\.ki|phone\.ki|biz\.pk|com\.pk|net\.pk|org\.pk|pk|web\.pk|cc|cn|com\.cn|net\.cn|org\.cn|co\.in|firm\.in|gen\.in|in|in\.net|ind\.in|net\.in|org\.in|co\.ir|ir|co\.jp|jp|jp\.net|ne\.jp|or\.jp|co\.kr|kr|ne\.kr|or\.kr|co\.th|in\.th|com\.bd|com\.hk|hk|idv\.hk|org\.hk|com\.jo|jo|com\.kz|kz|org\.kz|com\.lk|lk|org\.lk|com\.my|my|com\.nf|info\.nf|net\.nf|nf|web\.nf|com\.ph|ph|com\.ps|net\.ps|org\.ps|ps|com\.sa|com\.sb|net\.sb|org\.sb|com\.sg|edu\.sg|org\.sg|per\.sg|sg|com\.tw|tw|com\.vn|net\.vn|org\.vn|vn|cx|fm|io|la|mn|nu|qa|tk|tl|tm|to|tv|ws|academy|careers|education|training|bike|biz|cat|co|com|info|me|mobi|name|net|org|pro|tel|travel|xxx|blackfriday|clothing|diamonds|shoes|tattoo|voyage|build|builders|construction|contractors|equipment|glass|lighting|plumbing|repair|solutions|buzz|sexy|singles|support|cab|limo|camera|camp|gallery|graphics|guitars|hiphop|photo|photography|photos|pics|center|florist|institute|christmas|coffee|kitchen|menu|recipes|company|enterprises|holdings|management|ventures|computer|systems|technology|directory|guru|tips|wiki|domains|link|estate|international|land|onl|pw|today|ac\.im|co\.im|com\.im|im|ltd\.co\.im|net\.im|org\.im|plc\.co\.im|am|at|co\.at|or\.at|ba|be|bg|biz\.pl|com\.pl|info\.pl|net\.pl|org\.pl|pl|biz\.tr|com\.tr|info\.tr|tv\.tr|web\.tr|by|ch|co\.ee|ee|co\.gg|gg|co\.gl|com\.gl|co\.hu|hu|co\.il|org\.il|co\.je|je|co\.nl|nl|co\.no|no|co\.rs|in\.rs|rs|co\.uk|org\.uk|uk\.net|com\.de|de|com\.es|es|nom\.es|org\.es|com\.gr|gr|com\.hr|com\.mk|mk|com\.mt|net\.mt|org\.mt|com\.pt|pt|com\.ro|ro|com\.ru|net\.ru|ru|su|com\.ua|ua|cz|dk|eu|fi|fr|pm|re|tf|wf|yt|gb\.net|ie|is|it|li|lt|lu|lv|md|mp|se|se\.net|si|sk|ac|ag|co\.ag|com\.ag|net\.ag|nom\.ag|org\.ag|ai|com\.ai|com\.ar|as|biz\.pr|com\.pr|net\.pr|org\.pr|pr|biz\.tt|co\.tt|com\.tt|tt|bo|com\.bo|com\.br|net\.br|tv\.br|bs|com\.bs|bz|co\.bz|com\.bz|net\.bz|org\.bz|ca|cl|co\.cr|cr|co\.dm|dm|co\.gy|com\.gy|gy|co\.lc|com\.lc|lc|co\.ms|com\.ms|ms|org\.ms|co\.ni|com\.ni|co\.ve|com\.ve|co\.vi|com\.vi|com\.co|net\.co|nom\.co|com\.cu|cu|com\.do|do|com\.ec|ec|info\.ec|net\.ec|com\.gt|gt|com\.hn|hn|com\.ht|ht|net\.ht|org\.ht|com\.jm|com\.kn|kn|com\.mx|mx|com\.pa|com\.pe|pe|com\.py|com\.sv|com\.uy|uy|com\.vc|net\.vc|org\.vc|vc|gd|gs|north\.am|south\.am|us|us\.org|sx|tc|vg|cd|cg|cm|co\.cm|com\.cm|net\.cm|co\.ke|or\.ke|co\.mg|com\.mg|mg|net\.mg|org\.mg|co\.mw|com\.mw|coop\.mw|mw|co\.na|com\.na|na|org\.na|co\.ug|ug|co\.za|com\.ly|ly|com\.ng|ng|com\.sc|sc|mu|rw|sh|so|st|club|kiwi|uno|email|ruhr)$/i";
    if (preg_match($tlds, $d)) return true;
    else return false;
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

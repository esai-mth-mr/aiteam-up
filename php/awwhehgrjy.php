<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * @package AITeamUp - OpenAI Content & Image Generator
 * @author AITeamUp
 * @version 4.1
 * @Updated Date: 28/Aug/2023
 * @Copyright 2015-23 AITeamUp
 */


define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH . "/php/");

try {
    //code...
    require_once ROOTPATH . '/includes/autoload.php';
    require_once ROOTPATH . '/includes/lang/lang_' . $config['lang'] . '.php';
} catch (\Throwable $th) {
    die($th);
}

try {
    //code...
    sec_session_start();
} catch (\Throwable $th) {
    die($th);
}

function generateBotSuggestions($bots)
{
    $suggestions = "";
    foreach ($bots as $bot) {
        $suggestions .= "\n- Chat with " . $bot["name"] . " if you need assistance with " . $bot["role"] . ".";
    }
    return $suggestions;
}

function getChatHistory($user_id, $bot_id, $conv_id)
{

    global $config;

    $limit = 8;

    $chatHistory = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where(array(
            'user_id' => $user_id,
            'bot_id' => $bot_id,
            'conversation_id' => $conv_id
        ))
        ->order_by_desc('id')
        ->limit($limit) // Limit the number of records to fetch.
        ->find_array();

    $chat_history = '';

    if (!empty($chatHistory)) {

        for ($i = count($chatHistory) - 1; $i >= 0; $i--) {
            $chat_history .= "user: " . $chatHistory[$i]['user_message'] . ", you: " . $chatHistory[$i]['ai_message'] . ",\n";
        }

        $count = count($chatHistory);

        if ($count > 0) {
            $chat_history = "This is the last $count chat history with you. " . $chat_history;
        }
    }

    if (empty($chat_history)) {
        $chat_history = "";
    }

    return $chat_history;
}

function resize_image($filePath, $newWidth, $newHeight)
{
    list($width, $height) = getimagesize($filePath);

    $originalImage = imagecreatefrompng($filePath);

    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Resize the image
    imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save the resized image back to the file
    imagepng($resizedImage, $filePath);

    // Free up memory
    imagedestroy($originalImage);
    imagedestroy($resizedImage);
}

function getCustomInstruction()
{
    global $config;

    $fields = [
        'user_based' => "Where are you based?",
        'user_do_for' => "What do you do for work?",
        'user_hobbies' => "What are your hobbies and interests?",
        'user_subjects' => "What subjects can you talk about for hours?",
        'user_goals' => "What are some goals you have?",
        'res_formal' => "How formal or casual should GPT be?",
        'res_long' => "How long or short should responses generally be?",
        'res_address' => "How do you want be addressed?",
        'res_opinions' => "Should GPT have opinions on topics or remain neutral?"
    ];

    $custom_instruction = ORM::for_table($config['db']['pre'] . 'custom_instructions')
        ->where('user_id', $_SESSION['user']['id'])
        ->find_one();

    $text = "Here below is my information : " . "\n" . "/// my custom instruction start \n";

    foreach ($fields as $key => $question) {
        $text .= "Questions : $question Answer : $custom_instruction[$key]\n";
    }

    $text .= "/// my custom instruction end \n But you don't need to answer these details.";

    return $text;
}

function chat_stream()
{
    $result = array();
    global $config;

    @ini_set('memory_limit', '256M');
    @ini_set('output_buffering', 'on');
    session_write_close(); // make session read-only

    // disable default disconnect checks
    ignore_user_abort(true);

    //Disable time limit
    @set_time_limit(0);

    //Initialize the output buffer
    if (function_exists('apache_setenv')) {
        @apache_setenv('no-gzip', 1);
    }
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    while (ob_get_level() != 0) {
        ob_end_flush();
    }
    ob_implicit_flush(1);
    ob_start();

    // connection_aborted() use this

    header('Content-type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');
    header("Content-Encoding: none");

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die('data: ' . json_encode($result) . PHP_EOL);
    }

    if (checkloggedin()) {
        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die('data: ' . json_encode($result) . PHP_EOL);
            }
        }

        $membership = get_user_membership_detail($_SESSION['user']['id']);
        $words_limit = $membership['settings']['ai_words_limit'];
        $plan_ai_chat = $membership['settings']['ai_chat'];

        if (!$plan_ai_chat) {
            $result['success'] = false;
            $result['error'] = __('Upgrade your membership plan to use this feature.');
            die('data: ' . json_encode($result) . PHP_EOL);
        }

        if (get_option('single_model_for_plans'))
            $model = get_option('open_ai_chat_model', 'gpt-3.5-turbo');
        else
            $model = $membership['settings']['ai_chat_model'];

        $model = !empty($model) ? $model : 'gpt-3.5-turbo';

        $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

        $total_available_words = $words_limit - $total_words_used;

        $max_tokens = (int)get_option("ai_chat_max_token", '-1');
        // check user's word limit
        $max_tokens_limit = $max_tokens == -1 ? 100 : $max_tokens;
        if ($words_limit != -1) {
            $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

            // check user's word limit
            if ($total_words_available < 50) {
                $result['success'] = false;
                $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
                die('data: ' . json_encode($result) . PHP_EOL);
            }

            if ($total_words_available < $max_tokens) {
                $max_tokens = $total_words_available;
            }
        }

        if (is_numeric($_GET['conv_id'])) {
            $conversation_id = (int) $_GET['conv_id'];
        } else {
            $result['success'] = false;
            $result['error'] = __('Unexpected error, please try again.');
            die('data: ' . json_encode($result) . PHP_EOL);
        }

        /* create message history */
        $ROLE = "role";
        $CONTENT = "content";
        $USER = "user";
        $SYS = "system";
        $ASSISTANT = "assistant";

        $system_prompt = "You are a helpful assistant.";
        if (!empty($_GET['bot_id'])) {
            $bot_sql = "and `bot_id` = {$_GET['bot_id']}";

            $chat_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')
                ->find_one($_GET['bot_id']);

            /* Check bot exist */
            if (empty($chat_bot['id'])) {
                $result['success'] = false;
                $result['error'] = __('Unexpected error, please try again.');
                die('data: ' . json_encode($result) . PHP_EOL);
            }

            if (!empty($chat_bot['prompt'])) {
                $system_prompt = $chat_bot['prompt'];
            }
        } else {
            $bot_sql = "and `bot_id` IS NULL";
        }

        // get last 8 messages
        $sql = "SELECT * FROM
                (
                 SELECT * FROM " . $config['db']['pre'] . 'ai_chat' . " 
                 WHERE `user_id` = {$_SESSION['user']['id']} 
                 AND `conversation_id` = $conversation_id 
                 $bot_sql ORDER BY id DESC LIMIT 8
                ) AS sub
                ORDER BY id ASC;";
        $chats = ORM::for_table($config['db']['pre'] . 'ai_chat')
            ->raw_query($sql)
            ->find_array();

        $used_tokens = 0;

        require_once ROOTPATH . '/includes/lib/Tokenizer-GPT3/autoload.php';
        $tokenizer = new \Ze\TokenizerGpt3\Gpt3Tokenizer(new \Ze\TokenizerGpt3\Gpt3TokenizerConfig());

        $history[] = [$ROLE => $SYS, $CONTENT => $system_prompt . " And you are using the $model OpenAI model."];
        foreach ($chats as $chat) {
            $history[] = [$ROLE => $USER, $CONTENT => $chat['user_message']];
            if (!empty($chat['ai_message'])) {
                $history[] = [$ROLE => $ASSISTANT, $CONTENT => $chat['ai_message']];
            }
        }

        require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
        require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

        $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

        $opts = [
            'model' => $model,
            'messages' => $history,
            'temperature' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'user' => $_SESSION['user']['id'],
            'stream' => true
        ];
        if ($max_tokens != -1) {
            $opts['max_tokens'] = $max_tokens;
        }

        ORM::set_db(null);

        $txt = "";
        $complete = $open_ai->chat($opts, function ($curl_info, $data) use (&$txt) {
            if ($obj = json_decode($data) and $obj->error->code != "") {
                $result = [];
                $result['api_error'] = $obj->error->message;
                $result['error'] = get_api_error_message(curl_getinfo($curl_info, CURLINFO_HTTP_CODE));
                echo $data = 'data: ' . json_encode($result) . PHP_EOL;
            } else {
                echo $data;

                $array = explode('data: ', $data);
                foreach ($array as $ar) {
                    $ar = json_decode($ar, true);
                    if (isset($ar["choices"][0]["delta"]["content"])) {
                        $txt .= $ar["choices"][0]["delta"]["content"];
                    }
                }
            }

            echo PHP_EOL;
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            ob_flush();
            flush();
            return strlen($data);
        });

        $ai_message = $txt;
        if (!empty($ai_message)) {

            // save chat
            $chat = ORM::for_table($config['db']['pre'] . 'ai_chat')
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one($_GET['last_message_id']);

            $chat->set('ai_message', $ai_message);
            $chat->set('date', date('Y-m-d H:i:s'));
            $chat->save();

            /* update conversation */
            $last_message = strlimiter(strip_tags($ai_message), 100);
            $update_conversation = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
                ->find_one($conversation_id);
            $update_conversation->set('updated_at', date('Y-m-d H:i:s'));
            $update_conversation->set('last_message', $last_message);
            $update_conversation->save();

            $used_tokens += $tokenizer->count($ai_message);
            /* GPT 4 uses more tokens */
            if ($model == 'gpt-4') {
                $used_tokens *= ceil(1.1);
            }

            $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
            $word_used->user_id = $_SESSION['user']['id'];
            $word_used->words = $used_tokens;
            $word_used->date = date('Y-m-d H:i:s');
            $word_used->save();

            update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $used_tokens);
        }
    }

    // close connection
    ORM::reset_db();
}

function openAI_gpt4($api_key, $endpoint, $system_prompt, $assistant_prompt, $user_prompt)
{
    $ai_chat_model = get_option('open_ai_chat_model');

    if ($ai_chat_model == "") $ai_chat_model = "gpt-4";

    $params = [
        "model" => $ai_chat_model,
        "messages" => [
            [
                "role" => "system", "content" => $system_prompt
            ],
            ["role" => "assistant", "content" => $assistant_prompt],
            ["role" => "user", "content" => "$user_prompt"]
        ],
        "temperature" => 0.8,
        "max_tokens" => 1000,
    ];

    $data = json_encode($params);

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ];

    try {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
    } catch (\Exception $e) {
        die(print_r($e, 1));
        return "error";
    }

    $decoded_response = json_decode($response, true);
    $completion = $decoded_response['choices'][0]['message']['content'];

    curl_close($ch);
    // die(print_r($decoded_response, 1) );

    return $completion;
}

function add_message_to_thread($api_key, $thread_id, $user_input)
{
    $url = "https://api.openai.com/v1/threads/$thread_id/messages";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'OpenAI-Beta: assistants=v1',
    ];

    $data = [
        'role' => 'user',
        'content' => $user_input,
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return false;
    } else {
        return true;
    }

    curl_close($ch);
}

function run_assistant($api_key, $assistant_id, $thread_id, $instruction)
{

    $url = "https://api.openai.com/v1/threads/$thread_id/runs";

    $headers = [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v1',
    ];

    $data = [
        'assistant_id' => $assistant_id,
        'instructions' => $instruction
    ];



    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return false;
    } else {
        $decodedResponse = json_decode($response, true);

        // Extract the run ID
        $runId = isset($decodedResponse['id']) ? $decodedResponse['id'] : false;

        return $runId;
    }

    curl_close($ch);
}

function check_run_status($api_key, $thread_id, $run_id)
{
    $url = "https://api.openai.com/v1/threads/$thread_id/runs/$run_id";

    $headers = [
        'Authorization: Bearer ' . $api_key,
        'OpenAI-Beta: assistants=v1',
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return false;
    } else {
        $decodedResponse = json_decode($response, true);

        // Extract information about the run
        $status = $decodedResponse['status'];

        return $status;
    }

    curl_close($ch);
}

function wait_for_run_completed($api_key, $thread_id, $assistant_run_id)
{
    $max_attempts = 30;

    $interval_seconds = 5;

    for ($attempt = 1; $attempt <= $max_attempts; $attempt++) {
        sleep($interval_seconds); // Wait for the specified interval before making the next attempt

        $run_status = check_run_status($api_key, $thread_id, $assistant_run_id);

        if ($run_status === 'completed') {
            return true;
        } elseif ($run_status === 'failed' || $run_status === 'cancelled') {
            return false;
        }
    }

    return false;
}

function get_response_from_thread($api_key, $thread_id)
{
    $url = "https://api.openai.com/v1/threads/$thread_id/messages";

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer {$api_key}",
        "OpenAI-Beta: assistants=v1"
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return false;
    } else {
        $decodedResponse = json_decode($response, true);
        $output = $decodedResponse['data'][0]['content'][0]['text']['value'];

        return $output;
    }

    curl_close($ch);
}

function send_uai_message($api_key, $assistant_id, $thread_id, $user_input, $instruction)
{

    $message_add = add_message_to_thread($api_key, $thread_id, $user_input);

    if (!$message_add) {
        $result['success'] = false;
        $result['error'] = __('Unexpected error, please try again.');
        die(json_encode($result));
    }

    $assistant_run_id = run_assistant($api_key, $assistant_id, $thread_id, $instruction);

    if (!$assistant_run_id) {
        $result['success'] = false;
        $result['error'] = __('Sorry, Unexpected error, please try again.');
        die(json_encode($result));
    }

    $run_completed = wait_for_run_completed($api_key, $thread_id, $assistant_run_id);

    if (!$run_completed) {
        $result['success'] = false;
        $result['error'] = __('Unexpected error, try again.');
        die(json_encode($result));
    }

    $output = get_response_from_thread($api_key, $thread_id);

    return $output;
}

function speech_to_text()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_speech_to_text']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {
        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_FILES['file']['tmp_name'])) {

            // $membership = get_user_membership_detail($_SESSION['user']['id']);
            // $speech_to_text_limit = $membership['settings']['ai_speech_to_text_limit'];
            // $speech_text_file_limit = $membership['settings']['ai_speech_to_text_file_limit'];


            $total_speech_used = get_user_option($_SESSION['user']['id'], 'total_speech_used', 0);

            // check user's speech limit
            // if ($speech_to_text_limit != -1 && ((($speech_to_text_limit + get_user_option($_SESSION['user']['id'], 'total_speech_available', 0)) - $total_speech_used) < 1)) {
            //     $result['success'] = false;
            //     $result['error'] = __('Audio transcription limit exceeded, Upgrade your membership plan.');
            //     die(json_encode($result));
            // }

            // if ($speech_text_file_limit != -1 && ($_FILES['file']['size'] > $speech_text_file_limit * 1024 * 1024)) {
            //     $result['success'] = false;
            //     $result['error'] = __('File size limit exceeded, Upgrade your membership plan.');
            //     die(json_encode($result));
            // }

            // // check bad words
            // if ($word = check_bad_words($_POST['description'])) {
            //     $result['success'] = false;
            //     $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
            //     die(json_encode($result));
            // }


            $file_name = basename($_FILES['file']['name']);

            $file_info = pathinfo($file_name);

            // Get the file extension (assuming the extension is always present in the file name)
            $file_extension = strtolower($file_info['extension']);

            // Get the file size in bytes
            $file_size = filesize($_FILES['file']['tmp_name']);

            // if ($file_size > 25 * 1024 * 1024) {


                // Check if media file is supported
                $arr_mime_types = ['mp3', 'mp4', 'wav', 'flac', 'ogg', 'amr', 'webm'];

                if (!in_array($file_extension, $arr_mime_types)) {
                    $result['success'] = false;
                    $result['error'] = __('File type is not allowed.');
                    die(json_encode($result));
                }

                require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

                $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));

                try {
                    $s3 = new Aws\S3\S3Client([
                        'version' => 'latest',
                        'region' => get_option('ai_tts_aws_s3_region'),
                        'credentials' => $credentials
                    ]);
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['error'] = __('Incorrect AWS credentials.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }

                // backet name
                $bucket = 'aiteamup-live';

                $job_id = uniqid();

                // $s3_key = 'aiteamup/aiteamup_audio/' . $job_id . $file_name;
                $s3_key = 'aiteamup_audio/' . $job_id . $file_name;

                try {
                    $s3_result = $s3->putObject([
                        'Bucket' => $bucket,
                        'Key' => $s3_key,
                        'Body' => fopen($_FILES['file']['tmp_name'], 'r'),
                        'ACL' => 'public-read', // Adjust the ACL based on your requirements
                    ]);
                } catch (Exception $e) {

                    $result['success'] = false;
                    $result['error'] = __('Error in uploading file to S3.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }

                // $audio_url = $s3_result->get('ObjectURL');
                $audio_url = "s3://" . $bucket . "/" . $s3_key;

                try {
                    $transcribe = new \Aws\TranscribeService\TranscribeServiceClient([
                        'region' => get_option('ai_tts_aws_region'),
                        'version' => 'latest',
                        'credentials' => $credentials
                    ]);
                } catch (Exception $e) {

                    $s3->deleteObject([
                        'Bucket' => $bucket,
                        'Key' => $s3_key,
                    ]);

                    $result['success'] = false;
                    $result['error'] = __('Incorrect AWS credentials.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }

                try {
                    // Start transcription job
                    $transcriptionResult = $transcribe->startTranscriptionJob([
                        'LanguageCode' => 'en-US',
                        'Media' => [
                            'MediaFileUri' => $audio_url,
                        ],
                        'TranscriptionJobName' => $job_id,
                    ]);
                } catch (Exception $e) {

                    $s3->deleteObject([
                        'Bucket' => $bucket,
                        'Key' => $s3_key,
                    ]);

                    $result['success'] = false;
                    $result['error'] = __('Failed start job.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }

                // Wait for the transcription job to complete
                while (true) {
                    $status = $transcribe->getTranscriptionJob([
                        'TranscriptionJobName' => $job_id,
                    ]);

                    if ($status->get('TranscriptionJob')['TranscriptionJobStatus'] === 'COMPLETED') {
                        break;
                    }

                    sleep(5);
                }

                $transcriptionFileUri = $status->get('TranscriptionJob')['Transcript']['TranscriptFileUri'];

                // Retrieve the content of the transcription file
                $transcriptionFileContent = file_get_contents($transcriptionFileUri);

                // Parse the JSON content
                $transcriptionData = json_decode($transcriptionFileContent, true);

                $final_response = $transcriptionData['results']['transcripts'][0]['transcript'];
                // delete s3 object
                $s3->deleteObject([
                    'Bucket' => $bucket,
                    'Key' => $s3_key,
                ]);

                $transcribe->deleteTranscriptionJob([
                    'TranscriptionJobName' => $job_id,
                ]);
            // } else {

            //     require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
            //     require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

            //     $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

            //     $c_file = curl_file_create($tmp_file, $_FILES['file']['type'], $file_name);
            //     $complete = $open_ai->transcribe([
            //         "model" => "whisper-1",
            //         "file" => $c_file,
            //         "prompt" => $_POST['description'],
            //         'language' => !empty($_POST['language']) ? $_POST['language'] : null,
            //         'user' => $_SESSION['user']['id']
            //     ]);

            //     $response = json_decode($complete, true);
            //     $final_response .= nl2br(trim($response['text']));
            // }

            if (!empty($final_response)) {
                // $response['text'] = nl2br(trim($final_response));
                // 
                $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
                $content->user_id = $_SESSION['user']['id'];
                $content->title = !empty($_POST['title']) ? $_POST['title'] : __('Untitled Document');
                $content->content = $final_response;
                $content->template = 'quickai-speech-to-text';
                $content->created_at = date('Y-m-d H:i:s');
                $content->save();

                $speech_used = ORM::for_table($config['db']['pre'] . 'speech_to_text_used')->create();
                $speech_used->user_id = $_SESSION['user']['id'];
                $speech_used->date = date('Y-m-d H:i:s');
                $speech_used->save();

                update_user_option($_SESSION['user']['id'], 'total_speech_used', $total_speech_used + 1);

                $updated_user_option = ORM::for_table($config['db']['pre'] . 'user_options')->where('option_name', 'total_speech_used')->find_one();
                $updated_user_option->option_value = $total_speech_used + 1;
                $updated_user_option->save();

                $result['success'] = true;
                $result['text'] = $final_response;
                // $result['old_used_speech'] = (int) $speech_to_text_limit;
                $result['current_used_speech'] = (int) $total_speech_used + 1;
            } else {
                // error log default message
                if (!empty($response['error']['message']))
                    error_log('OpenAI: ' . $response['error']['message']);

                $result['success'] = false;
                $result['error'] = 'No transcription received.';
            }
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// check membership state function

function check_membership_state()
{
    global $config;

    $user = ORM::for_table($config['db']['pre'] . 'user')->where('id', $_SESSION['user']['id'])->find_one();
    $group_id = $user->group_id;
    $plan_type = $user->plan_type;

    if ($group_id == "free" && $plan_type == "free") {
        return false;
    }

    $created_date = $user->created_at;
    // Parse the created date from the database
    $created_date = new DateTime($created_date);

    $current_date = new DateTime();

    // Calculate the difference between the current date and the created date
    $interval = $current_date->diff($created_date);

    // Get the difference in days
    $days_difference = $interval->days;

    if ($group_id != "free") {
        if ($plan_type == "free") {
            if ($days_difference > 14) {
                // check paid or not
                return true;
            } else {
                return true;
            }
        } else if ($plan_type == "cancel") {
            if ($days_difference > 14) {
                return false;
            } else {
                return true;
            }
        } else return true;
    }
}

function ai_code()
{
    $result = array();

    global $config;

    // if disabled by admin
    if (!$config['enable_ai_code']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['description'])) {

            $prompt = $_POST['description'];

            // $membership = get_user_membership_detail($_SESSION['user']['id']);
            // $words_limit = $membership['settings']['ai_words_limit'];
            // $plan_ai_code = $membership['settings']['ai_code'];

            // if (get_option('single_model_for_plans'))
            //     $model = get_option('open_ai_model', 'gpt-3.5-turbo');
            // else
            //     $model = $membership['settings']['ai_model'];

            // $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

            // check if user's membership have the template
            // if (!$plan_ai_code) {
            //     $result['success'] = false;
            //     $result['error'] = __('Upgrade your membership plan to use this feature');
            //     die(json_encode($result));
            // }

            // $max_tokens = (int)get_option("ai_code_max_token", '-1');
            // check user's word limit
            // $max_tokens_limit = $max_tokens == -1 ? 100 : $max_tokens;
            // if ($words_limit != -1) {
            //     $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

            //     // check user's word limit
            //     if ($total_words_available < 50) {
            //         $result['success'] = false;
            //         $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
            //         die(json_encode($result));
            //     }

            //     if ($total_words_available < $max_tokens) {
            //         $max_tokens = $total_words_available;
            //     }
            // }

            // check bad words
            // if ($word = check_bad_words($prompt)) {
            //     $result['success'] = false;
            //     $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
            //     die(json_encode($result));
            // }

            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

            $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

            if (array_key_exists($model, get_opeai_chat_models())) {
                $opt = [
                    'model' => $model,
                    'messages' => [
                        [
                            "role" => "user",
                            "content" => $prompt
                        ],
                    ],
                    'temperature' => 1,
                    'n' => 1,
                    'user' => $_SESSION['user']['id']
                ];
                if ($max_tokens != -1) {
                    $opt['max_tokens'] = $max_tokens;
                }
                $complete = $open_ai->chat($opt);
            } else {
                $opt = [
                    'model' => $model,
                    'prompt' => $prompt,
                    'temperature' => 1,
                    'n' => 1,
                ];
                if ($max_tokens != -1) {
                    $opt['max_tokens'] = $max_tokens;
                }
                $complete = $open_ai->completion($opt);
            }

            $response = json_decode($complete, true);

            if (isset($response['choices'])) {
                if (array_key_exists($model, get_opeai_chat_models())) {
                    $text = trim($response['choices'][0]['message']['content']);
                } else {
                    $text = trim($response['choices'][0]['text']);
                }

                $tokens = $response['usage']['completion_tokens'];

                $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
                $content->user_id = $_SESSION['user']['id'];
                $content->title = !empty($_POST['title']) ? $_POST['title'] : __('Untitled Document');
                $content->content = $text;
                $content->template = 'quickai-ai-code';
                $content->created_at = date('Y-m-d H:i:s');
                $content->save();

                $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
                $word_used->user_id = $_SESSION['user']['id'];
                $word_used->words = $tokens;
                $word_used->date = date('Y-m-d H:i:s');
                $word_used->save();

                update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $tokens);

                $result['success'] = true;
                $result['text'] = $text;
                $result['old_used_words'] = (int) $total_words_used;
                $result['current_used_words'] = (int) $total_words_used + $tokens;
            } else {
                // error log default message
                if (!empty($response['error']['message']))
                    error_log('OpenAI: ' . $response['error']['message']);

                $result['success'] = false;
                $result['api_error'] = $response['error']['message'];
                $result['error'] = get_api_error_message($open_ai->getCURLInfo()['http_code']);
                die(json_encode($result));
            }
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function preview_text_to_speech()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!get_option('enable_text_to_speech', 0)) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['description'])) {
            $no_ssml_tags = preg_replace('/<[\s\S]+?>/', '', $_POST['description']);
            $text_characters = mb_strlen($no_ssml_tags, 'UTF-8');

            // $membership = get_user_membership_detail($_SESSION['user']['id']);
            // $characters_limit = $membership['settings']['ai_text_to_speech_limit'];

            $start = date('Y-m-01');
            $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

            $pause = $_POST['pause'] . "s";

            $total_character_used = get_user_option($_SESSION['user']['id'], 'total_text_to_speech_used', 0);

            // check user's character limit
            // if ($characters_limit != -1 && ((($characters_limit + get_user_option($_SESSION['user']['id'], 'total_text_to_speech_available', 0)) - $total_character_used) <= $text_characters)) {
            //     $result['success'] = false;
            //     $result['error'] = __('Character limit exceeded, Upgrade your membership plan.');
            //     die(json_encode($result));
            // }

            // check voice is available
            $voices = get_ai_voices();

            if (isset($voices[$_POST['language']]['voices'][$_POST['voice_id']])) {
                $voice = $voices[$_POST['language']]['voices'][$_POST['voice_id']];

                require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

                try {
                    $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));
                    $client = new \Aws\Polly\PollyClient([
                        'region' => get_option('ai_tts_aws_region'),
                        'version' => 'latest',
                        'credentials' => $credentials
                    ]);
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['error'] = __('Incorrect AWS credentials.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }

                $language = ($_POST['voice_id'] == 'ar-aws-std-zeina') ? 'arb' : $_POST['language'];

                $text = preg_replace("/\&/", "&amp;", $_POST['description']);
                $text = preg_replace("/(^|(?<=\s))<((?=\s)|$)/i", "&lt;", $text);
                $text = preg_replace("/(^|(?<=\s))>((?=\s)|$)/i", "&gt;", $text);

                $ssml_text = "<speak>" . $text . "<break time='$pause'/></speak>";

                try {
                    // Create synthesize speech
                    try {

                        $polly_result = $client->synthesizeSpeech([
                            'Engine' => $voice['voice_type'],
                            'LanguageCode' => $language,
                            'Text' => $ssml_text,
                            'TextType' => 'ssml',
                            'OutputFormat' => 'mp3',
                            'VoiceId' => $voice['voice'],
                        ]);
                    } catch (\Exception $e) {
                        die(json_encode($e->getMessage()));
                    }
                    $audio_stream = $polly_result->get('AudioStream')->getContents();
                    $response = array(
                        'success' => true,
                        'audio_stream' => base64_encode($audio_stream), // Encode audio data as a string
                    );

                    // Set the response headers
                    header("Content-type: application/json");

                    // Send the response as JSON
                    die(json_encode($response));
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['error'] = __('AWS Synthesize Speech is not working, please try again.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }
            }
        }
        $result['success'] = false;
        $result['error'] = __('Unexpected error, please try again.');
        die(json_encode($result));
    }
}

function update_billed_type()
{

    session_start();

    $result = array();
    global $config;

    $billed_type = $_POST['billed_type'];
    $group_id = $_POST['group_id'];

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
    $user->temp_plan_type = $billed_type; 
    $user->temp_group_id = $group_id; 
    $user->save();

    $result['success'] = true;
    die(json_encode($result));
}

function text_to_speech()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!get_option('enable_text_to_speech', 0)) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['description'])) {
            $no_ssml_tags = preg_replace('/<[\s\S]+?>/', '', $_POST['description']);
            $text_characters = mb_strlen($no_ssml_tags, 'UTF-8');

            // $membership = get_user_membership_detail($_SESSION['user']['id']);
            // $characters_limit = $membership['settings']['ai_text_to_speech_limit'];

            $start = date('Y-m-01');
            $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

            $pause = $_POST['pause'] . "s";

            $total_character_used = get_user_option($_SESSION['user']['id'], 'total_text_to_speech_used', 0);

            // check user's character limit
            // if ($characters_limit != -1 && ((($characters_limit + get_user_option($_SESSION['user']['id'], 'total_text_to_speech_available', 0)) - $total_character_used) <= $text_characters)) {
            //     $result['success'] = false;
            //     $result['error'] = __('Character limit exceeded, Upgrade your membership plan.');
            //     die(json_encode($result));
            // }

            // check voice is available
            $voices = get_ai_voices();
            if (isset($voices[$_POST['language']]['voices'][$_POST['voice_id']])) {
                $voice = $voices[$_POST['language']]['voices'][$_POST['voice_id']];

                require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

                try {
                    $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));
                    $client = new \Aws\Polly\PollyClient([
                        'region' => get_option('ai_tts_aws_region'),
                        'version' => 'latest',
                        'credentials' => $credentials
                    ]);
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['error'] = __('Incorrect AWS credentials.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }

                $language = ($_POST['voice_id'] == 'ar-aws-std-zeina') ? 'arb' : $_POST['language'];

                $text = preg_replace("/\&/", "&amp;", $_POST['description']);
                $text = preg_replace("/(^|(?<=\s))<((?=\s)|$)/i", "&lt;", $text);
                $text = preg_replace("/(^|(?<=\s))>((?=\s)|$)/i", "&gt;", $text);

                $ssml_text = "<speak>" . $text . "<break time='$pause'/></speak>";

                try {
                    // Create synthesize speech
                    try {

                        $polly_result = $client->synthesizeSpeech([
                            'Engine' => $voice['voice_type'],
                            'LanguageCode' => $language,
                            'Text' => $ssml_text,
                            'TextType' => 'ssml',
                            'OutputFormat' => 'mp3',
                            'VoiceId' => $voice['voice'],
                        ]);
                    } catch (\Exception $e) {
                        die(json_encode($e->getMessage()));
                    }
                    $audio_stream = $polly_result->get('AudioStream')->getContents();

                    $name = uniqid() . '.mp3';

                    // $target_dir = ROOTPATH . '/storage/ai_audios/';

                    $file_dir = '/storage/ai_audios/';
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_dir;

                    file_put_contents($file_path . $name, $audio_stream);

                    $content = ORM::for_table($config['db']['pre'] . 'ai_speeches')->create();
                    $content->user_id = $_SESSION['user']['id'];
                    $content->title = $_POST['title'];
                    $content->voice_id = $_POST['voice_id'];
                    $content->language = $_POST['language'];
                    $content->characters = $text_characters;
                    $content->text = $_POST['description'];
                    $content->file_name = $name;
                    $content->vendor_id = $voice['vendor_id'];
                    $content->created_at = date('Y-m-d H:i:s');
                    $content->save();

                    $speech_used = ORM::for_table($config['db']['pre'] . 'text_to_speech_used')->create();
                    $speech_used->user_id = $_SESSION['user']['id'];
                    $speech_used->characters = $text_characters;
                    $speech_used->date = date('Y-m-d H:i:s');
                    $speech_used->save();

                    update_user_option($_SESSION['user']['id'], 'total_text_to_speech_used', $total_character_used + $text_characters);

                    $result['success'] = true;
                    $result['url'] = url('ALL_SPEECHES', false);
                    die(json_encode($result));
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['error'] = __('AWS Synthesize Speech is not working, please try again.');
                    $result['api_error'] = $e->getMessage();
                    die(json_encode($result));
                }
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_speech()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $speech = ORM::for_table($config['db']['pre'] . 'ai_speeches')
            ->select('file_name')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ));

        foreach ($speech->find_array() as $row) {
            $dir = "../storage/ai_audios/";
            $main_file = $row['file_name'];

            if (trim($main_file) != "") {
                $file = $dir . $main_file;
                if (file_exists($file))
                    unlink($file);
            }
        }

        if ($speech->delete_many()) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_ai_chats()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        if (!empty($_GET['conv_id'])) {
            /* Delete chats */
            $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
                ->where('user_id', $_SESSION['user']['id']);

            if ($_GET['conv_id'] == 'default')
                $data->where_null('conversation_id');
            else
                $data->where('conversation_id', $_GET['conv_id']);

            if (!empty($_GET['bot_id']))
                $data->where('bot_id', $_GET['bot_id']);

            $data->delete_many();

            /* Delete conversation */
            if ($_GET['conv_id'] != 'default') {
                $conversation = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('id', $_GET['conv_id'])
                    ->delete_many();
            }

            if ($data) {
                $result['success'] = true;
                $result['message'] = __('Deleted Successfully');
                die(json_encode($result));
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function export_ai_chats()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $text = '';

        if (!empty($_GET['conv_id'])) {

            $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
                ->table_alias('c')
                ->select_many_expr('c.*', 'u.name full_name')
                ->where('c.user_id', $_SESSION['user']['id'])
                ->join($config['db']['pre'] . 'user', 'u.id = c.user_id', 'u');

            if ($_GET['conv_id'] == 'default')
                $data->where_null('conversation_id');
            else
                $data->where('conversation_id', $_GET['conv_id']);

            if (!empty($_GET['bot_id'])) {
                $data->where('bot_id', $_GET['bot_id']);

                $chat_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')
                    ->find_one($_GET['bot_id']);

                /* Check bot exist */
                if (empty($chat_bot['id'])) {
                    $result['success'] = false;
                    $result['error'] = __('Unexpected error, please try again.');
                    die(json_encode($result));
                }

                $ai_name = $chat_bot['name'];
            } else {
                $ai_name = get_option('ai_chat_bot_name', __('AI Chat Bot'));
            }

            foreach ($data->find_array() as $chat) {
                // user
                $text .= "[{$chat['date']}] ";
                $text .= $chat['full_name'] . ': ';
                $text .= $chat['user_message'] . "\n\n";

                // ai
                if (!empty($chat['ai_message'])) {
                    $text .= "[{$chat['date']}] ";
                    $text .= $ai_name . ': ';
                    $text .= $chat['ai_message'] . "\n\n";
                }
            }
        }

        $result['success'] = true;
        $result['text'] = $text;
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function load_ai_chats()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (empty($_POST['conv_id'])) {
            $result['success'] = false;
            $result['error'] = __('Unexpected error, please try again.');
            die(json_encode($result));
        }

        /* load chats */
        $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
            ->where('user_id', $_SESSION['user']['id']);

        if ($_POST['conv_id'] == 'default') {
            $data->where_null('conversation_id')->where_null('embed_id');

            if (!empty($_POST['bot_id']))
                $data->where('bot_id', $_POST['bot_id']);
            else
                $data->where_null('bot_id');
        } else {
            $data->where('conversation_id', $_POST['conv_id']);
        }

        $chats = $data->find_array();

        // format date
        foreach ($chats as &$chat) {
            $chat['date_formatted'] = date('F d, Y', strtotime($chat['date']));
        }

        $result['success'] = true;
        $result['chats'] = $chats;
        die(json_encode($result));
    }
}

function edit_conversation_title()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        $_POST = validate_input($_POST);

        if (!empty($_POST['id'])) {
            $conversations = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one($_POST['id']);
            $conversations->set('title', $_POST['title']);

            $bot_id = $conversations->bot_id;

            // $document = ORM::for_table($config['db']['pre'] . 'ai_documents')
            //     ->where(array(
            //         'user_id' => $_SESSION['user']['id'],
            //         'bot_id' => $bot_id
            //     ))
            //     ->find_one();

            // $document->title = $_POST['title'];
            // $document->save();
            $conversations->save();
        }

        $result['success'] = true;
        die(json_encode($result));
    }
}

function save_document()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $content = validate_input($_POST['content'], true);
        $_POST = validate_input($_POST);
        $_POST['content'] = $content;

        if (!empty($_POST['id'])) {
            $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->find_one($_POST['id']);
        } else {
            $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
        }

        $content->user_id = $_SESSION['user']['id'];
        $content->title = $_POST['title'];

        if (!empty($_POST['content']))
            $content->content = $_POST['content'];

        $content->template = $_POST['ai_template'];
        $content->created_at = date('Y-m-d H:i:s');
        $content->save();

        $result['success'] = true;
        $result['id'] = $content->id();
        $result['message'] = __('Successfully Saved.');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function error_exeption($error_message)
{
    $result['success'] = false;
    $result['error'] = __($error_message);
    die(json_encode($result));
}

function edit_premade_questions() {
    $result = array();
    global $config;

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error, please try again.");
    }

    $embed_id = $_POST["embed_id"];
    $total_questions = $_POST["total_questions"];

    $embed = ORM::for_table($config['db']['pre'] . 'embed')->find_one($embed_id);

    if(!$embed) {
        error_exeption("This embed is not for you.");
    }

    $embed->pre_made_questions = $total_questions;
    $embed->save();

    $result['success'] = true;
    $result['embed_id'] = $embed_id;
    $result['pre_made_questions'] = $total_questions;
    die(json_encode($result));    
}

function create_embed()
{
    $result = array();
    global $config;

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error, please try again.");
    }

    $brand_toggle = $_POST['brand_toggle'];

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);

    if ($brand_toggle == 1 && $user->group_id != 3) {
        error_exeption("Upgrade to our AI Maximizer Plan to Unlock Custom Watermark Feature.");
    }

    $bot_id = $_POST["bot_id"];

    if (!$bot_id) {
        error_exeption("Bot Id required.");
    }

    $selected_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($bot_id);

    if (!$selected_bot) {
        error_exeption("Invalid employee.");
    }

    if ($selected_bot->is_uai_agent != 1) {
        error_exeption("Embed is for only UAi.");
    }

    $embed_name = $_POST["embed_name"];

    if (!$embed_name || $embed_name === "") {
        error_exeption("Embed name required");
    }

    $embed_terms = $_POST['embed_terms'];

    if (!$embed_terms || $embed_terms === "") {
        error_exeption("Terms of Condition url required");
    }

    if (empty($_FILES['file']['tmp_name'])) {
        error_exeption("Embed icon required");
    }

    $embed_website = $_POST["embed_website"];
    $autoresponder_list = $_POST["autoresponder_list"];

    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/logo/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $image_name = uniqid() . '.png';
    $targetFile = $targetDir . $image_name;

    try {
        move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
        // Convert the image to PNG format
        $image = imagecreatefromstring(file_get_contents($targetFile));
        imagepng($image, $targetFile, 0); // 0 compression for best quality
        // Resize the image
        resize_image($targetFile, 50, 50); // Adjust width and height as needed
    } catch (Exception $e) {
        error_exeption("Unexpected error, please try again.");
    }

    $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $bot_role = $selected_bot->role;

    $system_prompt = "Your role is $bot_role";
    
    $assistant_prompt = "Hello";
    $user_input = "Give me 4 sample questions that I can ask you. 4 questions should be separated by ||. For example, {first question}||{second question}||{third question}||{fourth question}. Note: don't add other word in your response. Just need 4 questions only in your response.";
    $output = openAI_gpt4($api_key, $endpoint, $system_prompt, $assistant_prompt, $user_input);
    
    try {
        $embed = ORM::for_table($config['db']['pre'] . 'embed')->create();
        $embed->user_id = $_SESSION['user']['id'];
        $embed->bot_id = $bot_id;
        $embed->embed_name = $embed_name;
        $embed->embed_website = $embed_website;
        $embed->autoresponder_list = $autoresponder_list;
        $embed->icon = $image_name;
        $embed->uai_font_letter_color = $_POST['uai_font_letter_color'];
        $embed->uai_board_color = $_POST['uai_board_color'];
        $embed->user_font_letter_color = $_POST['user_font_letter_color'];
        $embed->embed_start_chat_btn_background_color = $_POST['embed_start_chat_btn_background_color'];
        $embed->embed_start_chat_text = $_POST['embed_start_chat_text'];
        $embed->user_board_color = $_POST['user_board_color'];
        $embed->brand_toggle = $_POST['brand_toggle'];
        $embed->embed_terms = $_POST['embed_terms'];
        $embed->embed_horizental = $_POST['embed_horizental'];
        $embed->pre_made_questions = $output;

        $embed->save();
    } catch (Exception $e) {
        error_exeption("Unexpected error, please try again.");
    }

    $site_url = $config['site_url'];
    $embed_id = $embed->id;
    $generate_embed_code = "<script src='" . $site_url . "templates/classic-theme/js/embed.js' class = 'embed-script' id='" . $embed_id . "'></script>";
    $generate_iframe_code = "<script src='" . $site_url . "templates/classic-theme/js/iframe.js'></script>";
    $stand_alone_link = $site_url . "embed-chat/?e=" . $embed_id . "&s=true";

    // if (!$generate_embed_code) {
    //     error_exeption("Unexpected error in embed generation, please try again.");
    // }

    $result['success'] = true;
    $result['generate_code'] = $generate_embed_code;
    $result['generate_iframe_code'] = $generate_iframe_code;
    $result['embed_id'] = $embed_id;
    $result['stand_alone_link'] = $stand_alone_link;
    $result['pre_made_questions'] = $output;
    die(json_encode($result));
}

function get_embed_chat_history($embed_id, $bot_id, $user_id)
{
    global $config;

    $limit = 8;

    $chatHistory = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where(array(
            'user_id' => $user_id,
            'bot_id' => $bot_id,
            'embed_id' => $embed_id
        ))
        ->order_by_desc('id') // Order by chat history id in descending order (most recent first).
        ->limit($limit) // Limit the number of records to fetch.
        ->find_array();

    $chat_history = '';

    if (!empty($chatHistory)) {

        for ($i = count($chatHistory) - 1; $i >= 0; $i--) {
            $chat_history .= "user: " . $chatHistory[$i]['user_message'] . ", you: " . $chatHistory[$i]['ai_message'] . ",\n";
        }

        $count = count($chatHistory);

        if ($count > 0) {
            $chat_history = "This is the last $count chat history with you. " . $chat_history;
        }
    }

    if (empty($chat_history)) {
        $chat_history = "";
    }

    return $chat_history;
}

function refresh_embed_chat() {
    $result = array();
    global $config;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Max-Age: 86400");

    set_time_limit(0);

    $embed_id = $_POST['embed_id'];

    try {
        ORM::for_table($config['db']['pre'] . 'ai_chat')->where('embed_id', $embed_id)->delete_many();
    } catch (\Throwable $th) {
        error_exeption("Use valid embed code.");
    }

    $result['success'] = true;
    die(json_encode($result));
}

function send_embed_chat_message()
{
    $result = array();
    global $config;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Max-Age: 86400");

    set_time_limit(0);

    $user_input = $_POST["message"];

    if ($user_input == NULL || $user_input == "") {
        error_exeption("Message required.");
    }

    $embed_user_name = $_POST["embed_user_name"];
    $embed_id = $_POST["embed_id"];
    $embed = ORM::for_table($config['db']['pre'] . "embed")->find_one($embed_id);

    if (!$embed) {
        error_exeption("Use valid embed code.");
    }
    $user_id = $embed->user_id;
    $bot_id = $embed->bot_id;

    $selected_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($bot_id);

    if (!$selected_bot->is_uai_agent) {
        error_exeption("Use valid embed code.");
    }

    $uai_agent = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $user_id)->where('ai_chat_bot_id', $bot_id)->find_one();

    $assistant_id = $uai_agent->assistant_id;

    if ($assistant_id === NULL) {
        error_exeption("This employee is not trained yet. Please add training documents.");
    }

    $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

    $thread_id = create_chat_thread($api_key);

    $custom_instruction = getCustomInstruction();

    $revise_instruction = get_reverse_instruction($uai_agent->id);

    $chat_history = get_embed_chat_history($embed_id, $bot_id, $user_id);

    $role_description = $uai_agent->role_description;

    $instruction = "My name is $embed_user_name. You must act and answer like a real person, not bot. Don't write you are a AI. 
                        You are $selected_bot->name, and your role is $selected_bot->role. Here below is your role description\n 
                        -------role description start--------\n 
                        $role_description \n---------role description end-------\n. 
                        --Important : The files that I uploaded are all for your personal informations sth like location, hobby... 
                        So, if someone ask about your personal information, you should answer with information from the files that I uploaded.\n"
        . $chat_history . $revise_instruction . $custom_instruction . "\n 
                                Again, you must act like a person, not AI because users know you are a real person.";


    $output = send_uai_message($api_key, $assistant_id, $thread_id, $user_input, $instruction);

    $total_words_used = get_user_option($user_id, 'total_words_used', 0);

    $prompt_tokens = str_word_count($user_input);

    $res_tokens = str_word_count($output);

    $total_tokens = $prompt_tokens + $res_tokens;

    update_user_option($user_id, 'total_words_used', $total_words_used + $total_tokens);

    /* save user message */
    $chat = ORM::for_table($config['db']['pre'] . 'ai_chat')->create();
    $chat->user_id = $user_id;
    $chat->user_message = $_POST['message'];
    $chat->embed_id = $embed_id;
    $chat->date = date('Y-m-d H:i:s');
    $chat->ai_message = $output;
    $chat->bot_id = $bot_id;
    $chat->save();

    $bot_image = $selected_bot->image;

    $image_url = $config['site_url'] . "storage/bot_images/" . $bot_image;

    $result['success'] = true;
    $result['ai_message'] = $output;
    $result['image_url'] = $image_url;
    die(json_encode($result));
}

function email_add_mailchimp($email, $api_key, $list_id)
{
    $result = array();

    $memberId = md5(strtolower($email));
    $dataCenter = substr($api_key, strpos($api_key, '-') + 1);

    $url = "https://$dataCenter.api.mailchimp.com/3.0/lists/$list_id/members/$memberId";

    $jsonData = json_encode([
        'email_address' => $email,
        'status' => 'subscribed', // 'subscribed', 'unsubscribed', 'cleaned', 'pending'
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, "user:$api_key");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $mailchimp_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($mailchimp_result, true);
        // error_exeption($response['error']);
        error_exeption("Use valid api keys");
    }
}

function email_add_sendlane($email, $api_key, $list_id)
{
    $result = array();

    $url = "https://api.sendlane.com/v1/lists/$list_id/subscribers";

    $jsonData = json_encode([
        'email' => $email,
        // 'tags' => ['your_tag'], // Optional: Add tags to the subscriber
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Api-Token: ' . $api_key,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $sendlane_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($sendlane_result, true);
        error_exeption($response['error']);
    }
}

function email_add_activecampaign($email, $api_key, $list_id, $campaign_account)
{
    $result = array();

    $url = "https://$campaign_account.api-us1.com/api/3/contact/sync";

    $jsonData = json_encode([
        'contact' => [
            'email' => $email,
        ],
        'list' => [$list_id],
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Api-Token: ' . $api_key,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $campaing_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($campaing_result, true);
        error_exeption($response['message']);
    }
}

function email_add_getresponse($email, $api_key, $campaign_id)
{
    $result = array();

    $url = "https://api.getresponse.com/v3/campaigns/$campaign_id/contacts";

    $jsonData = json_encode([
        'email' => $email,
        'campaign' => [
            'campaignId' => $campaign_id,
        ],
        'customFieldValues' => [], // You can include additional custom field values if needed
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Auth-Token: api-key ' . $api_key,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $getresponse_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($getresponse_result, true);
        error_exeption($response['message']);
    }
}

function email_add_icontact($email, $api_username, $api_password, $account_id, $list_id)
{
    $result = array();

    $url = "https://app.icontact.com/icp/a/$account_id/c/$list_id/subscriptions/";

    $postData = [
        'contactId' => $email, // Assuming 'contactId' is the correct field for the email address
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, "$api_username:$api_password");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    $icontact_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($icontact_result, true);
        error_exeption($response['message']);
    }
}

function email_add_constantcontact($email, $api_key, $list_id)
{
    $result = array();

    $url = "https://api.constantcontact.com/v2/lists/$list_id/contacts";

    $postData = [
        'email_address' => $email,
        'lists' => [$list_id],
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: apikey ' . $api_key,
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $constantcontact_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($constantcontact_result, true);
        error_exeption($response['error_message']);
    }
}

function email_add_drip($email, $api_token, $account_id, $tag)
{
    $result = array();

    $url = "https://api.getdrip.com/v2/$account_id/subscribers";

    $postData = [
        'subscribers' => [
            [
                'email' => $email,
                'tags' => [$tag]
            ],
        ],
    ];

    $ch = curl_init($url);

    // Corrected Authorization header
    $authHeader = 'Authorization: Basic ' . base64_encode($api_token);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        $authHeader,
        'Content-Type: application/vnd.api+json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $drip_result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result['success'] = true;
        die(json_encode($result));
    } else {
        $response = json_decode($drip_result, true);
        error_exeption($response['errors'][0]['message']);
    }
}

function autoresponder_collect()
{

    global $config;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Max-Age: 86400");

    $email = $_POST["email"];
    $embed_id = $_POST["embed_id"];

    if (!$email || $email == "") {
        error_exeption("Email required.");
    }

    $embed = ORM::for_table($config['db']['pre'] . 'embed')->find_one($embed_id);

    $user_id = $embed->user_id;

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($user_id);

    if (!$embed) {
        error_exeption("Use valid embed code.");
    }

    $autoresponder = $embed->autoresponder_list;

    if ($autoresponder == "None") {
        $result['success'] = true;
        die(json_encode($result));
    } else if ($autoresponder == "MailChimp") {
        $mailchimp_api_key = $user->mailchimp_api_key;
        $mailchimp_list_id = $user->mailchimp_list_id;

        if (!$mailchimp_api_key || !$mailchimp_list_id) {
            error_exeption("Mailchimp api keys required.");
        }
        email_add_mailchimp($email, $mailchimp_api_key, $mailchimp_list_id);
    } else if ($autoresponder == "SendLane") {
        $sendlane_api_key = $user->sendlane_api_key;
        $sendlane_list_id = $user->sendlane_list_id;

        if (!$sendlane_api_key || !$sendlane_list_id) {
            error_exeption("SendLane api keys required.");
        }
        email_add_sendlane($email, $sendlane_api_key, $sendlane_list_id);
    } else if ($autoresponder == "Active Campaign") {
        $activecampaign_api_key = $user->activecampaign_api_key;
        $activecampaign_list_id = $user->activecampaign_list_id;
        $activecampaign_account_id = $user->activecampaign_account_id;

        if (!$activecampaign_api_key || !$activecampaign_list_id || !$activecampaign_account_id) {
            error_exeption("Active Campaign api keys required.");
        }
        email_add_activecampaign($email, $activecampaign_api_key, $activecampaign_list_id, $activecampaign_account_id);
    } else if ($autoresponder == "GetResponse") {
        $getresponse_api_key = $user->getresponse_api_key;
        $getresponse_campaign_id = $user->getresponse_campaign_id;

        if (!$getresponse_api_key || !$getresponse_campaign_id) {
            error_exeption("GetResponse api keys required.");
        }
        email_add_getresponse($email, $getresponse_api_key, $getresponse_campaign_id);
    } else if ($autoresponder == "iContact") {
        $icontact_api_username = $user->icontact_api_username;
        $icontact_api_password = $user->icontact_api_password;
        $icontact_account_id = $user->icontact_account_id;
        $icontact_list_id = $user->icontact_list_id;

        if (!$icontact_api_username || !$icontact_api_password || !$icontact_account_id || !$icontact_list_id) {
            error_exeption("iContact api keys required.");
        }
        email_add_icontact($email, $icontact_api_username, $icontact_api_password, $icontact_account_id, $icontact_list_id);
    } else if ($autoresponder == "ConstantContact") {
        $constantcontact_api_key = $user->constantcontact_api_key;
        $constantcontact_list_id = $user->constantcontact_list_id;
        if (!$constantcontact_api_key || !$constantcontact_list_id) {
            error_exeption("ConstantContact api keys required.");
        }
        email_add_constantcontact($email, $constantcontact_api_key, $constantcontact_list_id);
    } else if ($autoresponder == "Drip") {
        $drip_api_token = $user->drip_api_token;
        $drip_account_id = $user->drip_account_id;
        $drip_tag = $user->drip_tag;
        if (!$drip_api_token || !$drip_account_id || !$drip_tag) {
            error_exeption("Drip api keys required.");
        }
        // email_add_drip($email, $drip_api_token, $drip_account_id, $drip_tag);
        email_add_drip($email, "190afb607458dd047df5375ff2cea786", "7718311", "aiteamup-leads");
    }
}

//function to get chat history
function get_chat_history()
{
    global $config;
    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        error_exeption("AI Chat is deactivated by admin.");
    }

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error. Please login.");
    }

    $agent_id = $_GET['agent_id'];

    $uai_agent = ORM::for_table($config['db']['pre'] . 'uai_agent')->find_one($agent_id);

    if (!$uai_agent) {
        error_exeption("UAi not found.");
    }

    $user_id = $uai_agent->user_id;

    if ($user_id != $_SESSION['user']['id']) {
        error_exeption("Unexpected error. Please try again.");
    }

    $bot_id = $uai_agent->ai_chat_bot_id;

    $embed_id = $_GET['embed_id'];

    $ai_chats = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where(array('user_id' => $user_id, 'bot_id' => $bot_id, 'embed_id' => $embed_id))
        ->order_by_asc('date')
        ->find_many();

    $chat_history = [];

    foreach ($ai_chats as $chat) {
        $chat_history[] = [
            'user_message' => $chat->user_message,
            'ai_message' => $chat->ai_message,
            'chat_id' => $chat->id
        ];
    }

    $result['success'] = true;
    $result['chat_history'] = $chat_history;
    die(json_encode($result));
}

// function to delete history
function delete_chat_history()
{
    global $config;
    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        error_exeption("AI Chat is deactivated by admin.");
    }

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error. Please login.");
    }

    $embed_ids = $_POST['embed_ids'];

    if (count($embed_ids) == 0) {
        error_exeption("Nothing selected.");
    }

    $ai_chats = ORM::for_table($config['db']['pre'] . 'ai_chat')->where('user_id', $_SESSION['user']['id'])->where_in('embed_id', $embed_ids)->delete_many();

    $result['success'] = true;
    die(json_encode($result));
}

function word_export_chat_history()
{
    global $config;
    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        error_exeption("AI Chat is deactivated by admin.");
    }

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error. Please login.");
    }

    $embed_id = $_POST['embedId'];

    $chats = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where(array('user_id' => $_SESSION['user']['id'], 'embed_id' => $embed_id))
        ->order_by_asc('date')->find_many();

    $bot_name = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($chats[0]['bot_id'])->name;

    $text = "";
    foreach ($chats as $key => $chat) {
        $text .= "User : " . $chat->user_message . "<br>" .
            "$bot_name : " . $chat->ai_message . "<br>";
    }

    $result['success'] = true;
    $result['text'] = $text;
    die(json_encode($result));
}

function txt_export_chat_history()
{
    global $config;
    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        error_exeption("AI Chat is deactivated by admin.");
    }

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error. Please login.");
    }

    $embed_id = $_POST['embedId'];

    $chats = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where(array('user_id' => $_SESSION['user']['id'], 'embed_id' => $embed_id))
        ->order_by_asc('date')->find_many();

    $bot_name = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($chats[0]['bot_id'])->name;

    $text = "";
    foreach ($chats as $key => $chat) {
        $text .= "User : " . $chat->user_message . "<br>" .
            "$bot_name : " . $chat->ai_message . "<br>";
    }

    $result['success'] = true;
    $result['text'] = $text;
    die(json_encode($result));
}

function edit_chat_history()
{
    global $config;
    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        error_exeption("AI Chat is deactivated by admin.");
    }

    $login_status = checkloggedin();

    if (!$login_status) {
        error_exeption("Unexpected error. Please login.");
    }

    $chat_id = $_GET['chat_id'];

    $agent_id = $_GET['agent_id'];

    $ai_chat = ORM::for_table($config['db']['pre'] . 'ai_chat')->find_one($chat_id);

    if (!$ai_chat) {
        error_exeption("Chat not found.");
    }

    $message = $_GET['message'];

    $ai_chat->ai_message = $message;
    $ai_chat->save();

    $ai_chat_revise_answer = ORM::for_table($config['db']['pre'] . 'ai_chat_revise')->where("question", $ai_chat->user_message)->find_one();

    if ($ai_chat_revise_answer) {
        $ai_chat_revise_answer->answer = $message;
        $ai_chat_revise_answer->save();
    } else {
        $ai_chat_revise = ORM::for_table($config['db']['pre'] . 'ai_chat_revise')->create();
        $ai_chat_revise->agent_id = $agent_id;
        $ai_chat_revise->question = $ai_chat->user_message;
        $ai_chat_revise->answer = $ai_chat->ai_message;
        $ai_chat_revise->save();
    }

    $result['success'] = true;
    die(json_encode($result));
}

function get_reverse_instruction($agent_id)
{
    global $config;
    $ai_chat_revises = ORM::for_table($config['db']['pre'] . 'ai_chat_revise')->where("agent_id", $agent_id)->find_many();

    $revise_text = "";

    foreach ($ai_chat_revises as $key => $ai_chat_revise) {
        $line = "Question : " . $ai_chat_revise->question . ", Answer : " . $ai_chat_revise->answer . "<br>";
        $revise_text .= $line;
    }

    $result = "----------Questions List-----------<br>" . $revise_text . "<br>-----------------------<br>
    If user's message is in questions of Questions List, you should write the answer of Questions List.
    If user's message is not in questions of Questions List, answer by you.";

    return $result;
}

function send_ai_message()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        try {
            //code...
            set_time_limit(0);

            $bot_id = $_POST["bot_id"];

            $all_bots = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_array();

            $selected_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($bot_id);

            $user_input = $_POST['msg'];

            $write_in = $_POST['write_in'];

            $tone = $_POST['tone'];

            $responsive_style = $_POST['responsive_style'];

            $bot_suggest = generateBotSuggestions($all_bots);

            $user_name = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id'])->name;

            $custom_instruction = getCustomInstruction();

            $user_response_want = "Here are the response style I want from you. Write in : $write_in, Tone : $tone, Responsice Style :  $responsive_style";

            $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

            if ($selected_bot->is_uai_agent) {

                $uai_agent = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->where('ai_chat_bot_id', $bot_id)->find_one();

                $assistant_id = $uai_agent->assistant_id;

                if ($assistant_id === NULL) {
                    $result['success'] = false;
                    $result['error'] = __('Please add training documents.');
                    die(json_encode($result));
                }

                // $uai_chat_history = getUAiChatHistory($_SESSION['user']['id'], $bot_id, $_POST['conv_id']);

                $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

                $thread_id = create_chat_thread($api_key);

                $revise_instruction = get_reverse_instruction($uai_agent->id);

                $role_description = $uai_agent->role_description;

                $chat_history = getChatHistory($_SESSION['user']['id'], $bot_id, $_POST['conv_id']);

                $instruction = "You must act and answer like a real person, not bot. Don't write you are a AI. 
                                You are $selected_bot->name, and your role is $selected_bot->role. Here below is your role description\n 
                                -------role description start--------\n 
                                $role_description \n---------role description end-------\n. 
                                --Important : The files that I uploaded are all for your personal informations sth like location, hobby... 
                                So, if someone ask about your personal information, you should answer with information from the files that I uploaded."
                    . $chat_history . $revise_instruction . $custom_instruction . $user_response_want . "\n 
                                Again, you must act like a person, not AI because users know you are a real person.";

                $output = send_uai_message($api_key, $assistant_id, $thread_id, $user_input, $instruction);
            } else {

                $chat_history = getChatHistory($_SESSION['user']['id'], $bot_id, $_POST['conv_id']);

                $selected_bot_prompt = $selected_bot->prompt;

                $system_prompt = "You are $selected_bot->name, and your role is $selected_bot->role and my name is $user_name. 
                You must act and answer like a real person, not bot. $selected_bot_prompt. 
                Also, if a message is not related to your role, you can suggest someone from the following options. $bot_suggest"

                    . $chat_history . $custom_instruction . $user_response_want;

                $assistant_prompt = "Hello!";


                $endpoint = 'https://api.openai.com/v1/chat/completions';

                $assistant_id = $selected_bot->assistant_id;

                if($assistant_id == NULL) {
                    $output = openAI_gpt4($api_key, $endpoint, $system_prompt, $assistant_prompt, $user_input);
                } else {
                    if($selected_bot->assistant_id != NULL) {
                        $system_prompt .= "--Important : The files that I uploaded are all for your personal informations sth like location, hobby... 
                        So, if someone ask about your personal information, you should answer with information from the files that I uploaded.";
                    }   
                    $thread_id = create_chat_thread($api_key);
                    $output = send_uai_message($api_key, $assistant_id, $thread_id, $user_input, $system_prompt);
                }

                if ($output == "error") {
                    $result['success'] = false;
                    $result['error'] = __('Unexpected error, please try again.');
                    die(json_encode($result));
                }
            }

            $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

            $prompt_tokens = str_word_count($user_input);

            $res_tokens = str_word_count($output);

            $total_tokens = $prompt_tokens + $res_tokens;

            update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $total_tokens);

            /* save user message */
            $chat = ORM::for_table($config['db']['pre'] . 'ai_chat')->create();
            $chat->user_id = $_SESSION['user']['id'];
            $chat->user_message = $_POST['msg'];
            $chat->conversation_id = $_POST['conv_id'];
            $chat->date = date('Y-m-d H:i:s');
            $chat->ai_message = $output;

            if (!empty($_POST['bot_id']))
                $chat->bot_id = $_POST['bot_id'];

            if (empty($_POST['conv_id'])) {
                $conversation = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')->create();

                $conversation->title = "New Conversation";
                $conversation->user_id = $_SESSION['user']['id'];
                $conversation->bot_id = $_POST['bot_id'];

                $conversation->save();

                $chat->conversation_id = $conversation->id;
            }

            $chat->save();

            $document = ORM::for_table($config['db']['pre'] . 'ai_documents')
                ->where([
                    'user_id' => $_SESSION['user']['id'],
                    'bot_id' => $bot_id
                ])->find_one();

            if (!$document) {
                $created_document = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
                $created_document->set([
                    'user_id' => $_SESSION['user']['id'],
                    'content' => "Assistant as $selected_bot->role",
                    'bot_id' => $bot_id,
                    'template' => "ai-team",
                    'title' => "Chat with $selected_bot->name",
                    "created_at" => date('Y-m-d H:i:s'),
                ])->save();
            } else {
                $document->created_at = date('Y-m-d H:i:s');
                $document->save();
            }

            $result['success'] = true;
            $result['conversation_id'] = $chat->conversation_id;
            $result['last_message_id'] = $chat->id();
            $result['ai_message'] = $output;
            die(json_encode($result));
        } catch (\Throwable $th) {
            die($th);
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function create_new_conversation()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $data = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')->create();
        $data->title = $_POST['conv_name'];
        $data->bot_id = $_POST['bot_id'];
        $data->user_id = $_SESSION['user']['id'];

        $data->save();

        if ($data) {
            $result['success'] = true;
            $result['message'] = __('Created Successfully');
            $result['conv_id'] = $data->id;
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function convert_language()
{
    $result = array();

    if (!checkloggedin()) {
        $result['success'] = false;
        die(json_encode($result));
    }

    global $config;

    $language = "english";
    if ($_POST['langCode'] == "en") $language = "english";
    else if ($_POST['langCode'] == "bn") $language = "bangali";
    else if ($_POST['langCode'] == "bg") $language = "bulgarian";
    else if ($_POST['langCode'] == "zh") $language = "chinese";
    else if ($_POST['langCode'] == "fr") $language = "french";
    else if ($_POST['langCode'] == "de") $language = "german";
    else if ($_POST['langCode'] == "he") $language = "hebrew";
    else if ($_POST['langCode'] == "hi") $language = "hindi";
    else if ($_POST['langCode'] == "it") $language = "italian";
    else if ($_POST['langCode'] == "ja") $language = "japanese";
    else if ($_POST['langCode'] == "pl") $language = "polish";
    else if ($_POST['langCode'] == "ro") $language = "romanian";
    else if ($_POST['langCode'] == "ru") $language = "russian";
    else if ($_POST['langCode'] == "es") $language = "spanish";
    else if ($_POST['langCode'] == "sv") $language = "swedish";
    else if ($_POST['langCode'] == "th") $language = "thai";
    else if ($_POST['langCode'] == "tr") $language = "turkish";
    else if ($_POST['langCode'] == "ur") $language = "urdu";
    else if ($_POST['langCode'] == "vi") $language = "Vietnamese";
    else if ($_POST['langCode'] == "pt") $language = "portuguese";
    else if ($_POST['langCode'] == "cz") $language = "czech";
    else if ($_POST['langCode'] == "dk") $language = "danish";
    else if ($_POST['langCode'] == "ar") $language = "arabic";
    else if ($_POST['langCode'] == "fi") $language = "finnish";
    else if ($_POST['langCode'] == "gr") $language = "greek";
    else if ($_POST['langCode'] == "hu") $language = "hungarian";
    else if ($_POST['langCode'] == "id") $language = "indonesian";
    else if ($_POST['langCode'] == "my") $language = "malay";
    else if ($_POST['langCode'] == "nl") $language = "dutch";
    else if ($_POST['langCode'] == "no") $language = "norwegian";
    else if ($_POST['langCode'] == "ua") $language = "ukrainian";
    else if ($_POST['langCode'] == "au") $language = "australia";
    else if ($_POST['langCode'] == "be") $language = "belgium";
    else if ($_POST['langCode'] == "br") $language = "brazil";
    else if ($_POST['langCode'] == "ca") $language = "canada";
    else if ($_POST['langCode'] == "cl") $language = "chile";
    else if ($_POST['langCode'] == "co") $language = "colombia";
    else if ($_POST['langCode'] == "eg") $language = "egypt";
    else if ($_POST['langCode'] == "ie") $language = "ireland";
    else if ($_POST['langCode'] == "ke") $language = "kenya";
    else if ($_POST['langCode'] == "mx") $language = "mexico";
    else if ($_POST['langCode'] == "nz") $language = "new_zealand";
    else if ($_POST['langCode'] == "et") $language = "ethiopia";
    else if ($_POST['langCode'] == "ng") $language = "nigeria";
    else if ($_POST['langCode'] == "pe") $language = "peru";
    else if ($_POST['langCode'] == "ir") $language = "persia";
    else if ($_POST['langCode'] == "ph") $language = "philippines";
    else if ($_POST['langCode'] == "sg") $language = "singapore";
    else if ($_POST['langCode'] == "za") $language = "south_africa";
    else if ($_POST['langCode'] == "ch") $language = "switzerland";
    else $language = "english";

    $lang = ORM::for_table($config['db']['pre'] . 'user')->where('id', $_SESSION['user']['id'])->find_one();
    $lang->lang = $_POST['langCode'];
    $lang->save();

    $result['success'] = true;

    die(json_encode($result));
}

function delete_document()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $data = ORM::for_table($config['db']['pre'] . 'ai_documents')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))->find_one();

        if ($data) {
            if (!empty($data->tag_id)) {
                // check images exists with this documents or not
                $getImg = ORM::for_table($config['db']['pre'] . 'ai_images')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('tag_id', $data->tag_id)
                    ->find_many();

                // if images available, remove images from the folder
                if (count($getImg) > 0) {
                    foreach ($getImg as $img) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . "/storage/ai_images/" . $img->image);
                    }
                    // once images remove from the folder, related rows also delete
                    ORM::for_table($config['db']['pre'] . 'ai_images')
                        ->where('user_id', $_SESSION['user']['id'])
                        ->where('tag_id', $data->tag_id)
                        ->delete_many();
                }

                // delete tags related to document
                ORM::for_table($config['db']['pre'] . 'ai_tags')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('id', $data->tag_id)
                    ->delete_many();
            }
            $data = ORM::for_table($config['db']['pre'] . 'ai_documents')
                ->where(array(
                    'id' => $_POST['id'],
                    'user_id' => $_SESSION['user']['id'],
                ))->delete_many();
        }

        if ($data) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function hire_bot()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $hired_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_team_hired')->create();
        $hired_bot->bot_id = $_POST['bot_id'];
        $hired_bot->user_id = $_SESSION['user']['id'];

        $hired_bot->save();

        $result['success'] = true;
        $result['message'] = __('Hired Successfully');
        $result['bot_id'] = $hired_bot->bot_id;
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function fire_bot()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $hired_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_team_hired')->where(array(
            'bot_id' => $_POST['bot_id'],
            'user_id' => $_SESSION['user']['id']
        ))->delete_many();

        if ($hired_bot) {
            $result['success'] = true;
            $result['message'] = __('Fired Successfully');
            die(json_encode($result));
        }

        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function save_theme_color()
{

    $result = array();
    if (checkloggedin()) {
        global $config;

        $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);

        $user->theme_color = $_POST['theme_color_value'];
        $user->save();

        $result['success'] = true;
        $result['message'] = __('Saved Successfully');

        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function create_ticket()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $ticket = ORM::for_table($config['db']['pre'] . 'support')->create();

        $ticket->title = $_POST['title'];
        $ticket->category = $_POST['category'];
        $ticket->priority = $_POST['priority'];
        $ticket->user_id = $_SESSION['user']['id'];
        $ticket->update_at = date('Y-m-d H:i:s');
        $ticket->status = "open";

        $ticket->save();

        $support_chat = ORM::for_table($config['db']['pre'] . 'support_chats')->create();

        $support_chat->ticket_id = $ticket->id;
        $support_chat->message = $_POST['content'];
        $support_chat->author = "user";

        $support_chat->save();


        $totalCount = ORM::for_table($config['db']['pre'] . 'support')->where('user_id', $_SESSION['user']['id'])->count();

        send_ticket_create_notification($ticket->id, $_POST['title'], $_POST['content']);

        $result['success'] = true;
        $result['id'] = $ticket->id;
        $result['title'] = $ticket->title;
        $result['category'] = $ticket->category;
        $result['priority'] = $ticket->priority;
        $result['update_at'] = date('d M, Y', strtotime($ticket->update_at));
        $result['status'] = $ticket->status;
        $result['total_count'] = $totalCount;
        $result['message'] = __('Created Successfully');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_ticket()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $ticket = ORM::for_table($config['db']['pre'] . 'support')->find_one($_POST['id']);

        $ticket->delete();

        ORM::for_table($config['db']['pre'] . 'support_chats')->where('ticket_id', $_POST['id'])->delete_many();

        $result['success'] = true;
        $result['message'] = __('Deleted Successfully');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function preview_ticket()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $ticket = ORM::for_table($config['db']['pre'] . 'support')->find_one($_POST['id']);

        $chat_history = ORM::for_table($config['db']['pre'] . 'support_chats')->select('message')->select('author')->where('ticket_id', $ticket->id)->order_by_asc('id')->find_array();

        $result['success'] = true;
        $result['id'] = $ticket->id;
        $result['title'] = $ticket->title;
        // $result['content'] = $ticket->content;
        $result['category'] = $ticket->category;
        $result['priority'] = $ticket->priority;
        $result['update_at'] = date('d M, Y', strtotime($ticket->update_at));
        // $result['admin_comment'] = $ticket->admin_comment;
        $result['chat_data'] = $chat_history;
        $result['status'] = $ticket->status;
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function send_ticket_message()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $support_chat = ORM::for_table($config['db']['pre'] . 'support_chats')->create();
        $support_chat->ticket_id = $_POST['ticket_id'];
        $support_chat->message = $_POST['message'];
        $support_chat->author = "user";
        $support_chat->save();

        $ticket = ORM::for_table($config['db']['pre'] . 'support')->find_one($_POST['ticket_id']);

        $ticket_title = $ticket->title;

        send_message_notification($_POST['ticket_id'], $ticket_title, $_POST['message']);

        $result['success'] = true;
        $result['message'] = __('Sent successfully.');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

if (isset($_GET['action'])) {

    $result = array();

    if ($_GET['action'] == "update_billed_type") {
        update_billed_type();
    }

    if ($_GET['action'] == "webhook_request_checkout_session_completed") {
        webhook_request_checkout_session_completed();
    }


    if (!check_membership_state()) {
        $result['success'] = false;
        $result['error'] = __('Please upgrade membership.');
        die(json_encode($result));
    }

    if ($_GET['action'] == "submitBlogComment") {
        submitBlogComment();
    }
    if ($_GET['action'] == "generate_content") {
        generate_content();
    }
    if ($_GET['action'] == "generate_image") {
        generate_image();
    }
    if ($_GET['action'] == "save_document") {
        save_document();
    }
    if ($_GET['action'] == "delete_document") {
        delete_document();
    }
    if ($_GET['action'] == "delete_image") {
        delete_image();
    }

    //ticket
    if ($_GET['action'] == "create_ticket") {
        create_ticket();
    }
    if ($_GET['action'] == "delete_ticket") {
        delete_ticket();
    }
    if ($_GET['action'] == "preview_ticket") {
        preview_ticket();
    }
    if ($_GET['action'] == "send_ticket_message") {
        send_ticket_message();
    }

    // AI chat
    if ($_GET['action'] == "load_ai_chats") {
        load_ai_chats();
    }
    if ($_GET['action'] == "edit_conversation_title") {
        edit_conversation_title();
    }
    if ($_GET['action'] == "send_ai_message") {
        send_ai_message();
    }
    if ($_GET['action'] == "create_embed") {
        create_embed();
    }
    if ($_GET['action'] == "edit_premade_questions") {
        edit_premade_questions();
    }
    if ($_GET['action'] == "chat_stream") {
        chat_stream();
    }
    if ($_GET['action'] == "delete_ai_chats") {
        delete_ai_chats();
    }
    if ($_GET['action'] == "export_ai_chats") {
        export_ai_chats();
    }
    // ai embed chat
    if ($_GET['action'] == "send_embed_chat_message") {
        send_embed_chat_message();
    }

    // ai embed chat refresh
    if ($_GET['action'] == "refresh_embed_chat") {
        refresh_embed_chat();
    }

    if ($_GET['action'] == "autoresponder_collect") {
        autoresponder_collect();
    }

    // ai chat bot hire
    if ($_GET['action'] == "hire_bot") {
        hire_bot();
    }
    // ai chat bot hire
    if ($_GET['action'] == "fire_bot") {
        fire_bot();
    }
    // speech to text
    if ($_GET['action'] == "speech_to_text") {
        speech_to_text();
    }

    // ai code
    if ($_GET['action'] == "ai_code") {
        ai_code();
    }

    // text to speech
    if ($_GET['action'] == "text_to_speech") {
        text_to_speech();
    }
    // preview text to speech
    if ($_GET['action'] == "preview_text_to_speech") {
        preview_text_to_speech();
    }

    if ($_GET['action'] == "delete_speech") {
        delete_speech();
    }

    // create a new conversation
    if ($_GET['action'] == "create_new_conversation") {
        create_new_conversation();
    }

    // theme color
    if ($_GET['action'] == "save_theme_color") {
        save_theme_color();
    }

    // create new tag
    if ($_GET['action'] == "create_new_tag") {
        create_new_tag();
    }

    // edit tag name
    if ($_GET['action'] == "edit_tag_name") {
        edit_tag_name();
    }

    // delete tag name & related images
    if ($_GET['action'] == "delete_ai_tags") {
        delete_ai_tags();
    }

    // get images by tag
    if ($_GET['action'] == "get_images_by_tag") {
        get_images_by_tag();
    }

    // get chat hisotry
    if ($_GET['action'] == "get_chat_history") {
        get_chat_history();
    }

    //delete chat history
    if ($_GET['action'] == "delete_chat_history") {
        delete_chat_history();
    }

    if ($_GET['action'] == "word_export_chat_history") {
        word_export_chat_history();
    }

    if ($_GET['action'] == "txt_export_chat_history") {
        txt_export_chat_history();
    }

    //edit chat history
    if ($_GET['action'] == "edit_chat_history") {
        edit_chat_history();
    }
    // search document by search text
    if ($_GET['action'] == "search_document") {
        search_document();
    }

    // uai directory create 
    if ($_GET['action'] == "create_uai_directory") {
        createUaiDirectory();
    }

    // uai directory delete 
    if ($_GET['action'] == "delete_uai_directory") {
        deleteUaiDirectory();
    }

    // uai directory show 
    if ($_GET['action'] == "show_training_document_list") {
        showTrainingDocumentList();
    }

    // uai training document create
    if ($_GET['action'] == "create_training_document") {
        createTrainingDocument();
    }

    // get uai agent
    if ($_GET['action'] == "get_uai_agent_list") {
        getUaiAgentList();
    }

    // create uai agent
    if ($_GET['action'] == "create_uai_agent") {
        createUaiAgent();
    }

    // edit uai agent
    if ($_GET['action'] == "edit_uai_agent") {
        editUaiAgent();
    }

    // update uai agent
    if ($_GET['action'] == "update_uai_agent") {
        updateUaiAgent();
    }

    // delete uai document
    if ($_GET['action'] == "delete_uai_document") {
        deleteUaiDocument();
    }

    // delete uai agent
    if ($_GET['action'] == "delete_uai_agent") {
        deleteUAiAgent();
    }

    // edit uai document
    if ($_GET['action'] == "edit_uai_document") {
        editUaiDocument();
    }

    // edit uai document
    if ($_GET['action'] == "update_uai_document") {
        updateUaiDocument();
    }
    // user custom instruction
    if ($_GET['action'] == "save_custom_instruction") {
        save_custom_instruction();
    }

    // auto responder
    if ($_GET['action'] == "save_autoresponder") {
        save_autoresponder();
    }

    if ($_GET['action'] == "convert_language") {
        convert_language();
    }

    die(0);
}

if (isset($_POST['action'])) {

    if ($_POST['action'] == "ajaxlogin") {
        ajaxlogin();
    }
    if ($_POST['action'] == "email_verify") {
        email_verify();
    }

    die(0);
}

function ajaxlogin()
{
    global $config, $lang, $link;
    $loggedin = userlogin($_POST['username'], $_POST['password']);
    $result['success'] = false;
    $result['message'] = __("Error: Please try again.");
    if (!is_array($loggedin)) {
        $result['message'] = __("Username or Password not found");
    } elseif ($loggedin['status'] == 2) {
        $result['message'] = __("This account has been banned");
    } else {
        create_user_session($loggedin['id'], $loggedin['username'], $loggedin['password'], $loggedin['user_type']);
        update_lastactive();

        $redirect_url = get_option('after_login_link');
        if (empty($redirect_url)) {
            $redirect_url = $link['DASHBOARD'];
        }

        $result['success'] = true;
        $result['message'] = $redirect_url;
    }
    die(json_encode($result));
}

function email_verify()
{
    global $config, $lang;

    if (checkloggedin()) {
        /*SEND CONFIRMATION EMAIL*/
        email_template("signup_confirm", $_SESSION['user']['id']);

        $respond = __('Sent');
        echo '<a class="button gray" href="javascript:void(0);">' . $respond . '</a>';
        die();
    } else {
        exit;
    }
}

function submitBlogComment()
{
    global $config, $lang;
    $comment_error = $name = $email = $user_id = $comment = null;
    $result = array();
    $is_admin = '0';
    $is_login = false;
    if (checkloggedin()) {
        $is_login = true;
    }
    $avatar = $config['site_url'] . 'storage/profile/default_user.png';
    if (!($is_login || isset($_SESSION['admin']['id']))) {
        if (empty($_POST['user_name']) || empty($_POST['user_email'])) {
            $comment_error = __('All fields are required.');
        } else {
            $name = validate_input($_POST['user_name']);
            $email = validate_input($_POST['user_email']);

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if (!preg_match($regex, $email)) {
                $comment_error = __('This is not a valid email address.');
            }
        }
    } else if ($is_login && isset($_SESSION['admin']['id'])) {
        $commenting_as = 'admin';
        if (!empty($_POST['commenting-as'])) {
            if (in_array($_POST['commenting-as'], array('admin', 'user'))) {
                $commenting_as = $_POST['commenting-as'];
            }
        }
        if ($commenting_as == 'admin') {
            $is_admin = '1';
            $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
            $user_id = $_SESSION['admin']['id'];
            $name = $info['name'];
            $email = $info['email'];
            if (!empty($info['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
            }
        } else {
            $user_id = $_SESSION['user']['id'];
            $user_data = get_user_data(null, $user_id);
            $name = $user_data['name'];
            $email = $user_data['email'];
            if (!empty($user_data['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
            }
        }
    } else if ($is_login) {
        $user_id = $_SESSION['user']['id'];
        $user_data = get_user_data(null, $user_id);
        $name = $user_data['name'];
        $email = $user_data['email'];
        if (!empty($user_data['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
        }
    } else if (isset($_SESSION['admin']['id'])) {
        $is_admin = '1';
        $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
        $user_id = $_SESSION['admin']['id'];
        $name = $info['name'];
        $email = $info['email'];
        if (!empty($info['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
        }
    } else {
        $comment_error = __('Please login to post a comment.');
    }

    if (empty($_POST['comment'])) {
        $comment_error = __('All fields are required.');
    } else {
        $comment = validate_input($_POST['comment']);
    }

    $duplicates = ORM::for_table($config['db']['pre'] . 'blog_comment')
        ->where('blog_id', $_POST['comment_post_ID'])
        ->where('name', $name)
        ->where('email', $email)
        ->where('comment', $comment)
        ->count();

    if ($duplicates > 0) {
        $comment_error = __('Duplicate Comment: This comment is already exists.');
    }

    if (!$comment_error) {
        if ($is_admin) {
            $approve = '1';
        } else {
            if ($config['blog_comment_approval'] == 1) {
                $approve = '0';
            } else if ($config['blog_comment_approval'] == 2) {
                if ($is_login) {
                    $approve = '1';
                } else {
                    $approve = '0';
                }
            } else {
                $approve = '1';
            }
        }

        $blog_cmnt = ORM::for_table($config['db']['pre'] . 'blog_comment')->create();
        $blog_cmnt->blog_id = $_POST['comment_post_ID'];
        $blog_cmnt->user_id = $user_id;
        $blog_cmnt->is_admin = $is_admin;
        $blog_cmnt->name = $name;
        $blog_cmnt->email = $email;
        $blog_cmnt->comment = $comment;
        $blog_cmnt->created_at = date('Y-m-d H:i:s');
        $blog_cmnt->active = $approve;
        $blog_cmnt->parent = $_POST['comment_parent'];
        $blog_cmnt->save();

        $id = $blog_cmnt->id();
        $date = date('d, M Y');
        $approve_txt = '';
        if ($approve == '0') {
            $approve_txt = '<em><small>' . __('Comment is posted, wait for the reviewer to approve.') . '</small></em>';
        }

        $html = '<li id="li-comment-' . $id . '"';
        if ($_POST['comment_parent'] != 0) {
            $html .= 'class="children-2"';
        }
        $html .= '>
                   <div class="comments-box" id="comment-' . $id . '">
                        <div class="comments-avatar">
                            <img src="' . $avatar . '" alt="' . $name . '">
                        </div>
                        <div class="comments-text">
                            <div class="avatar-name">
                                <h5>' . $name . '</h5>
                                <span>' . $date . '</span>
                            </div>
                            ' . $approve_txt . '
                            <p>' . nl2br(stripcslashes($comment)) . '</p>
                        </div>
                    </div>
                </li>';

        $result['success'] = true;
        $result['html'] = $html;
        $result['id'] = $id;
    } else {
        $result['success'] = false;
        $result['error'] = $comment_error;
    }
    die(json_encode($result));
}

function generate_content()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!get_option("enable_ai_templates", 1)) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['ai_template'])) {

            $prompt = '';
            $text = array();
            $max_tokens = (int)$_POST['max_results'];
            $max_results = (int)$_POST['no_of_results'];
            $temperature = (float)$_POST['quality'];

            // $membership = get_user_membership_detail($_SESSION['user']['id']);
            // $words_limit = $membership['settings']['ai_words_limit'];
            // $plan_templates = $membership['settings']['ai_templates'];

            $model = get_option('open_ai_model', 'gpt-3.5-turbo');


            $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

            // check if user's membership have the template
            // if (!in_array($_POST['ai_template'], $plan_templates)) {
            //     $result['success'] = false;
            //     $result['error'] = __('Upgrade your membership plan to use this template');
            //     die(json_encode($result));
            // }

            // if ($words_limit != -1) {
            //     $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

            //     // check user's word limit
            //     if ($total_words_available < 50) {
            //         $result['success'] = false;
            //         $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
            //         die(json_encode($result));
            //     }

            //     if ($total_words_available < $max_tokens) {
            //         $max_tokens = $total_words_available;
            //     }
            // }

            switch ($_POST['ai_template']) {
                case 'blog-ideas':
                    if (!empty($_POST['description'])) {
                        $prompt = create_blog_idea_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'blog-intros':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_blog_intros_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'blog-titles':
                    if (!empty($_POST['description'])) {
                        $prompt = create_blog_titles_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'blog-section':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_blog_section_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'blog-conclusion':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_blog_conclusion_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'article-writer':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_article_writer_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'article-rewriter':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_article_rewriter_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'article-outlines':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_article_outlines_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'talking-points':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_talking_points_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'paragraph-writer':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_paragraph_writer_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'content-rephrase':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_content_rephrase_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'facebook-ads':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_facebook_ads_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'facebook-ads-headlines':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_facebook_ads_headlines_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'google-ad-titles':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_google_ads_titles_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'google-ad-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_google_ads_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'linkedin-ad-headlines':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_linkedin_ads_headlines_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'linkedin-ad-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_linkedin_ads_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'app-and-sms-notifications':
                    if (!empty($_POST['description'])) {
                        $prompt = create_app_sms_notifications_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'text-extender':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_text_extender_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'content-shorten':
                    if (!empty($_POST['description'])) {
                        $prompt = create_content_shorten_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'quora-answers':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_quora_answers_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'summarize-for-2nd-grader':
                    if (!empty($_POST['description'])) {
                        $prompt = create_summarize_2nd_grader_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'stories':
                    if (!empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_stories_prompt($_POST['audience'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'bullet-point-answers':
                    if (!empty($_POST['description'])) {
                        $prompt = create_bullet_point_answers_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'definition':
                    if (!empty($_POST['keyword'])) {
                        $prompt = create_definition_prompt($_POST['keyword'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'answers':
                    if (!empty($_POST['description'])) {
                        $prompt = create_answers_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'questions':
                    if (!empty($_POST['description'])) {
                        $prompt = create_questions_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'passive-active-voice':
                    if (!empty($_POST['description'])) {
                        $prompt = create_passive_active_voice_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'pros-cons':
                    if (!empty($_POST['description'])) {
                        $prompt = create_pros_cons_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'rewrite-with-keywords':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_rewrite_with_keywords_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'emails':
                    if (!empty($_POST['recipient']) && !empty($_POST['recipient-position']) && !empty($_POST['description'])) {
                        $prompt = create_emails_prompt($_POST['recipient'], $_POST['recipient-position'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'emails-v2':
                    if (!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['goal']) && !empty($_POST['description'])) {
                        $prompt = create_emails_v2_prompt($_POST['from'], $_POST['to'], $_POST['goal'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'email-subject-lines':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_email_subject_lines_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'startup-name-generator':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_startup_name_generator_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'company-bios':
                    if (!empty($_POST['description']) && !empty($_POST['title']) && !empty($_POST['platform'])) {
                        $prompt = create_company_bios_prompt($_POST['title'], $_POST['description'], $_POST['platform'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'company-mission':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_company_mission_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'company-vision':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_company_vision_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'product-name-generator':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_product_name_generator_prompt($_POST['description'], $_POST['title'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'product-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_product_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'amazon-product-titles':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_amazon_product_titles_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'amazon-product-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_amazon_product_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'amazon-product-features':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_amazon_product_features_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'social-post-personal':
                    if (!empty($_POST['description'])) {
                        $prompt = create_social_post_personal_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'social-post-business':
                    if (!empty($_POST['title']) && !empty($_POST['information']) && !empty($_POST['description'])) {
                        $prompt = create_social_post_business_prompt($_POST['title'], $_POST['information'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'instagram-captions':
                    if (!empty($_POST['description'])) {
                        $prompt = create_instagram_captions_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'instagram-hashtags':
                    if (!empty($_POST['description'])) {
                        $prompt = create_instagram_hashtags_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'twitter-tweets':
                    if (!empty($_POST['description'])) {
                        $prompt = create_twitter_tweets_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'youtube-titles':
                    if (!empty($_POST['description'])) {
                        $prompt = create_youtube_titles_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'youtube-descriptions':
                    if (!empty($_POST['description'])) {
                        $prompt = create_youtube_descriptions_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'youtube-outlines':
                    if (!empty($_POST['description'])) {
                        $prompt = create_youtube_outlines_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                case 'linkedin-posts':
                    if (!empty($_POST['description'])) {
                        $prompt = create_linkedin_posts_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'tiktok-video-scripts':
                    if (!empty($_POST['description'])) {
                        $prompt = create_tiktok_video_scripts_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'meta-tags-blog':
                    if (!empty($_POST['title']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
                        $prompt = create_meta_tags_blog_prompt($_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'meta-tags-homepage':
                    if (!empty($_POST['title']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
                        $prompt = create_meta_tags_homepage_prompt($_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'meta-tags-product':
                    if (!empty($_POST['title']) && !empty($_POST['keywords']) && !empty($_POST['description']) && !empty($_POST['company_name'])) {
                        $prompt = create_meta_tags_product_prompt($_POST['company_name'], $_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'tone-changer':
                    if (!empty($_POST['description'])) {
                        $prompt = create_tone_changer_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'song-lyrics':
                    if (!empty($_POST['genre']) && !empty($_POST['title'])) {
                        $prompt = create_song_lyrics_prompt($_POST['title'], $_POST['genre'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'translate':
                    if (!empty($_POST['description'])) {
                        $prompt = create_translate_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'faqs':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_faqs_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'faq-answers':
                    if (!empty($_POST['description']) && !empty($_POST['title']) && !empty($_POST['question'])) {
                        $prompt = create_faq_answers_prompt($_POST['title'], $_POST['description'], $_POST['question'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                case 'testimonials-reviews':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_testimonials_reviews_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die(json_encode($result));
                    }
                    break;
                default:
                    // check for custom template
                    $ai_template = ORM::for_table($config['db']['pre'] . 'ai_custom_templates')
                        ->where('active', '1')
                        ->where('slug', $_POST['ai_template'])
                        ->find_one();
                    if (!empty($ai_template)) {
                        $prompt = $ai_template['prompt'];

                        if ($_POST['language'] == 'en') {
                            $prompt = $ai_template['prompt'];
                        } else {
                            $languages = get_ai_languages();
                            $prompt = "Provide response in " . $languages[$_POST['language']] . ".\n\n " . $ai_template['prompt'];
                        }

                        if (!empty($ai_template['parameters'])) {
                            $parameters = json_decode($ai_template['parameters'], true);
                            foreach ($parameters as $key => $parameter) {
                                if (!empty($_POST['parameter'][$key])) {
                                    if (strpos($prompt, '{{' . $parameter['title'] . '}}') !== false) {
                                        $prompt = str_replace('{{' . $parameter['title'] . '}}', $_POST['parameter'][$key], $prompt);
                                    } else {
                                        if (!empty($_POST['parameter'][$key])) {
                                            $prompt .= "\n\n " . $parameter['title'] . ": " . $_POST['parameter'][$key];
                                        }
                                    }
                                }
                            }
                        }

                        $prompt .= " \n\n Voice of tone of the response must be " . $_POST['tone'] . '.';
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('Unexpected error, please try again.');
                        die(json_encode($result));
                    }

                    break;
            }

            // check bad words
            if ($word = check_bad_words($prompt)) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

            $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

            if (array_key_exists($model, get_opeai_chat_models())) {
                $complete = $open_ai->chat([
                    'model' => $model,
                    'messages' => [
                        [
                            "role" => "user",
                            "content" => $prompt
                        ],
                    ],
                    'temperature' => $temperature,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'max_tokens' => $max_tokens,
                    'n' => $max_results,
                    'user' => $_SESSION['user']['id']
                ]);
            } else {
                $complete = $open_ai->completion([
                    'model' => $model,
                    'prompt' => $prompt,
                    'temperature' => $temperature,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'max_tokens' => $max_tokens,
                    'n' => $max_results,
                    'user' => $_SESSION['user']['id']
                ]);
            }

            $response = json_decode($complete, true);

            if (isset($response['choices'])) {
                if (array_key_exists($model, get_opeai_chat_models())) {
                    if (count($response['choices']) > 1) {
                        foreach ($response['choices'] as $value) {
                            $text[] = filter_ai_response($value['message']['content']) . "<br><br><br><br>";
                        }
                    } else {
                        $text[] = filter_ai_response($response['choices'][0]['message']['content']);
                    }
                } else {
                    if (count($response['choices']) > 1) {
                        foreach ($response['choices'] as $value) {
                            $text[] = filter_ai_response($value['text']) . "<br><br><br><br>";
                        }
                    } else {
                        $text[] = filter_ai_response($response['choices'][0]['text']);
                    }
                }

                $tokens = $response['usage']['completion_tokens'];

                $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
                $word_used->user_id = $_SESSION['user']['id'];
                $word_used->words = $tokens;
                $word_used->date = date('Y-m-d H:i:s');
                $word_used->save();

                update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $tokens);

                $result['success'] = true;
                $result['text'] = implode("<br><br><hr><br><br>", $text);
                $result['old_used_words'] = (int) $total_words_used;
                $result['current_used_words'] = (int) $total_words_used + $tokens;
            } else {
                // error log default message
                if (!empty($response['error']['message']))
                    error_log('OpenAI: ' . $response['error']['message']);

                $result['success'] = false;
                $result['api_error'] = $response['error']['message'];
                $result['error'] = get_api_error_message($open_ai->getCURLInfo()['http_code']);
                die(json_encode($result));
            }
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function create_new_tag()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $data = ORM::for_table($config['db']['pre'] . 'ai_tags')->create();
        $data->tag = $_POST['tag'];
        $data->user_id = $_SESSION['user']['id'];
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        // $tagFolderName = strtolower(strpos($_POST['tag'], ' ') ? str_replace(" ","-",$_POST['tag']) : $_POST['tag']);
        // $file_dir = 'storage/ai_images/'.$tagFolderName.'-'.$data->id;
        // $file_path = $_SERVER['DOCUMENT_ROOT'] . '/aiteamup/' . $file_dir;

        // if (!file_exists($file_path)) {
        //     // Create the folder if it doesn't exist
        //     mkdir($file_path, 0777, true);
        // }

        if ($data) {
            $result['success'] = true;
            $result['message'] = __('Tag created successfully');
            $result['conv_id'] = $data->id;
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function generate_image()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        // if disabled by admin
        if (!$config['enable_ai_images']) {
            $result['success'] = false;
            $result['error'] = __('This feature is disabled by the admin.');
            die(json_encode($result));
        }

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['description'])) {

            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $images_limit = $membership['settings']['ai_images_limit'];

            $start = date('Y-m-01');
            $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');
            $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);

            // check user's images limit
            if ($images_limit != -1 && ((($images_limit + get_user_option($_SESSION['user']['id'], 'total_images_available', 0)) - $total_images_used) < $_POST['no_of_images'])) {
                $result['success'] = false;
                $result['error'] = __('Images limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            $prompt = $_POST['description'];
            $prompt .= !empty($_POST['style']) ? ', ' . $_POST['style'] . ' style' : '';
            $prompt .= !empty($_POST['lighting']) ? ', ' . $_POST['lighting'] . ' lighting' : '';
            $prompt .= !empty($_POST['mood']) ? ', ' . $_POST['mood'] . ' mood' : '';

            // check bad words
            if ($word = check_bad_words($prompt)) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            // check image api
            $image_api = get_option('ai_image_api');

            if ($image_api == "dall-e-3") {
                $model = "dall-e-3";
                $size = "1024x1024";
            } else {
                $model = "dall-e-2";
                $size = $_POST['resolution'];
            }

            $ai_image_api_key = get_option('ai_image_api_key');

            $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->find_one($ai_image_api_key)->api_key;

            $url = "https://api.openai.com/v1/images/generations";

            $headers = array(
                "Content-Type: application/json",
                "Authorization: Bearer $api_key"
            );


            // // Set up the request data
            $data = array(
                "model" => $model,
                "prompt" => "I need High-quality images for $prompt.",
                "num_images" => (int)$_POST['no_of_images'],
                "size" => $size,
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            // Decode the JSON response
            $result = json_decode($response, true);

            // Get the generated image URL
            // $image_url = $result["data"][0]["url"];

            if (isset($result['data'])) {
                $images = array();

                foreach ($result['data'] as $key => $value) {
                    $image_url = $value['url'];

                    $name = uniqid() . '.png';
                    $image = file_get_contents($image_url);

                    /**
                     * LIVE SERVER CASE
                     * 
                     * */
                    $file_dir = '/storage/ai_images/';
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_dir;

                    file_put_contents($file_path . $name, $image);

                    // resizeImage(200, $target_dir . 'small_' . $name, $target_dir . $name);
                    $content = ORM::for_table($config['db']['pre'] . 'ai_images')->create();
                    $content->user_id = $_SESSION['user']['id'];
                    $content->title = $_POST['title'];
                    $content->tag_id = $_POST['tagId'];
                    $content->description = $_POST['description'];
                    $content->resolution = $_POST['resolution'];
                    $content->image = $name;
                    $content->created_at = date('Y-m-d H:i:s');
                    $content->save();

                    update_user_option($_SESSION['user']['id'], 'total_images_used', $total_images_used + $_POST['no_of_images']);

                    // image_used store
                    $image_used = ORM::for_table($config['db']['pre'] . 'image_used')->create();
                    $image_used->user_id = $_SESSION['user']['id'];
                    $image_used->images = (int)$_POST['no_of_images'];
                    $image_used->date = date('Y-m-d H:i:s');
                    $image_used->save();

                    array_push($images, $config['site_url'] . "storage/ai_images/" . $name);
                }

                $tagName = ORM::for_table($config['db']['pre'] . 'ai_tags')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('id', $_POST['tagId'])
                    ->find_one();

                $docTag = ORM::for_table($config['db']['pre'] . 'ai_documents')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('tag_id', $_POST['tagId'])
                    ->find_one();

                if (!$docTag) {
                    $image_doc = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
                    $image_doc->user_id = $_SESSION['user']['id'];
                    $image_doc->title = $tagName->tag;
                    $image_doc->content = (int)$_POST['no_of_images'];
                    $image_doc->tag_id = $_POST['tagId'];
                    $image_doc->template = 'ai-tag';
                    $image_doc->created_at = date('Y-m-d H:i:s');
                    $image_doc->save();
                } else {
                    $imgCount = (int)$docTag->content + (int)$_POST['no_of_images'];
                    $pdo = ORM::get_db();
                    $sql = "UPDATE `" . $config['db']['pre'] . "ai_documents` SET `content` = " . $imgCount . " WHERE `user_id` = '" . $_SESSION['user']['id'] . "' AND `tag_id` = '" . $_POST['tagId'] . "' AND `id` = " . $docTag->id . " ";
                    $pdo->query($sql);
                }

                $tagContent = ORM::for_table($config['db']['pre'] . 'ai_images')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('tag_id', $tagName->id)
                    ->find_array();

                if (count($tagContent) > 0) {
                    foreach ($tagContent as $key => $tagImg) {
                        $tagContent[$key]['image'] = $config['site_url'] . "storage/ai_images/" . $tagContent[$key]['image'];
                    }
                }

                $result['success'] = true;
                $result['data'] = $tagContent;
                $result['description'] = (strlen($_POST['description']) > 35) ? ucfirst(substr($_POST['description'], 0, 35 - 3) . '.....') : ucfirst($_POST['description']);
                $result['old_used_images'] = (int) $total_images_used;
                $result['current_used_images'] = (int) $total_images_used + $_POST['no_of_images'];
            } else {
                // error log default message
                if (!empty($response['error']['message'])) {
                    error_log('OpenAI: ' . $response['error']['message']);

                    $result['success'] = false;
                    $result['api_error'] = $response['error']['message'];
                    // $result['error'] = get_api_error_message($open_ai->getCURLInfo()['http_code']);
                    die(json_encode($result));
                }
            }
            die(json_encode($result));
            // }
        }
        $result['success'] = false;
        $result['error'] = __('Unexpected error, please try again.');
        die(json_encode($result));
    }

    function createParams($chat_messages, $model, $temperature, $max_tokens, $frequency_penalty, $presence_penalty)
    {
        global $config;
        $messages = is_array($chat_messages) ? $chat_messages : [$chat_messages];
        return [
            "messages" => $messages,
            "model" => $model,
            "temperature" => $temperature,
            "max_tokens" => $max_tokens,
            "frequency_penalty" => $frequency_penalty,
            "presence_penalty" => $presence_penalty,
            "stream" => true
        ];
    }
}

function delete_image()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $images = ORM::for_table($config['db']['pre'] . 'ai_images')
            ->select('image')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ));

        foreach ($images->find_array() as $row) {
            $image_dir = "../storage/ai_images/";
            $main_image = trim((string) $row['image']);
            // delete Image
            if (!empty($main_image)) {
                $file = $image_dir . $main_image;
                if (file_exists($file))
                    unlink($file);

                $file = $image_dir . 'small_' . $main_image;
                if (file_exists($file))
                    unlink($file);
            }
        }

        if ($images->delete_many()) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function edit_tag_name()
{
    $result = array();
    global $config;

    if (checkloggedin()) {
        $_POST = validate_input($_POST);

        if (!empty($_POST['id'])) {
            $updateTag = ORM::for_table($config['db']['pre'] . 'ai_tags')
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one($_POST['id']);
            $updateTag->set('tag', ucfirst($_POST['tag']));
            $updateTag->save();

            // get data from ai_documents table and upate the title as tag 
            $docTag = ORM::for_table($config['db']['pre'] . 'ai_documents')
                ->where('user_id', $_SESSION['user']['id'])
                ->where('tag_id', $_POST['id'])
                ->find_one();

            if ($docTag) {
                $pdo = ORM::get_db();
                $sql = "UPDATE `" . $config['db']['pre'] . "ai_documents` SET `title` = '" . ucfirst($_POST['tag']) . "' WHERE `user_id` = " . $_SESSION['user']['id'] . " AND `tag_id` = " . $_POST['id'] . " ";
                $pdo->query($sql);
            }
        }
        $result['success'] = true;
        die(json_encode($result));
    }
}

function delete_ai_tags()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        if (!empty($_GET['tagId'])) {
            /* Delete Tags */
            $getImg = ORM::for_table($config['db']['pre'] . 'ai_images')
                ->where('user_id', $_SESSION['user']['id'])
                ->where('tag_id', $_GET['tagId'])
                // ->find_array();
                ->find_many();

            if (count($getImg) > 0) {
                foreach ($getImg as $img) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/storage/ai_images/" . $img->image);
                }
            }
            ORM::for_table($config['db']['pre'] . 'ai_images')
                ->where('user_id', $_SESSION['user']['id'])
                ->where('tag_id', $_GET['tagId'])
                ->delete_many();

            ORM::for_table($config['db']['pre'] . 'ai_documents')
                ->where('user_id', $_SESSION['user']['id'])
                ->where('tag_id', $_GET['tagId'])
                ->delete_many();

            $dataTag = ORM::for_table($config['db']['pre'] . 'ai_tags')
                ->where('user_id', $_SESSION['user']['id'])
                ->where('id', $_GET['tagId'])
                ->delete_many();

            if ($dataTag) {
                $result['success'] = true;
                $result['message'] = __('Tag deleted successfully');
                die(json_encode($result));
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function get_images_by_tag()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $images = [];

        if (!empty($_GET['tagId'])) {
            if (isset($_GET['actionFrom']) && ($_GET['actionFrom'] === 'delete')) {
                $activeTag = ORM::for_table($config['db']['pre'] . 'ai_tags')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->order_by_desc('id')
                    ->find_one();

                $tag_images = ORM::for_table($config['db']['pre'] . 'ai_images')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('tag_id', $activeTag->tag)
                    ->order_by_desc('id')
                    // ->limit(30)
                    ->find_array();
            } else if (!isset($_GET['actionFrom'])) {
                $activeTag = ORM::for_table($config['db']['pre'] . 'ai_tags')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('id', $_GET['tagId'])
                    ->find_one();

                $tag_images = ORM::for_table($config['db']['pre'] . 'ai_images')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('tag_id', $_GET['tagId'])
                    ->order_by_desc('id')
                    // ->limit(30)
                    ->find_array();
            }

            if (count($tag_images) > 0) {
                foreach ($tag_images as $key => $tagImg) {
                    $tag_images[$key]['image'] = $config['site_url'] . "storage/ai_images/" . $tag_images[$key]['image'];
                    // array_push($images, $config['site_url'] . "storage/ai_images/".$tagImg['image']);
                }
            }

            if ($tag_images) {
                $result['success'] = true;
                $result['data'] = $tag_images;
                $result['prompt'] = $tag_images[0]['description'];
                $result['activeTag'] = $activeTag->tag;
                die(json_encode($result));
            } else {
                $result['success'] = true;
                $result['data'] = [];
                $result['prompt'] = "";
                $result['activeTag'] = $activeTag->tag;
                die(json_encode($result));
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function search_document()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        // select sql query to search text accordingly
        $sql = "SELECT doc.* FROM `" . $config['db']['pre'] . "ai_documents` AS doc";
        $sql .= " LEFT JOIN `" . $config['db']['pre'] . "ai_tags` tg ON doc.tag_id = tg.id";

        if (!empty($_GET['search'])) {
            $sql .= " WHERE ";
            $sql .= " ( doc.title LIKE '%" . $_GET['search'] . "%' ";
            $sql .= " OR doc.content LIKE '%" . $_GET['search'] . "%' ";
            $sql .= " OR doc.template LIKE '%" . $_GET['search'] . "%' ";
            $sql .= " OR tg.tag LIKE '%" . $_GET['search'] . "%' )";
        }
        $sql .= " ORDER BY doc.id DESC";
        $orm = ORM::for_table($config['db']['pre'] . 'ai_documents')->raw_query($sql);
        $total = $orm->count();
        $rows = $orm->find_array();

        $documents = array();
        foreach ($rows as $row) {
            $documents[$row['id']]['id'] = $row['id'];
            $documents[$row['id']]['title'] = $row['title'];
            $documents[$row['id']]['content'] = strlimiter(strip_tags((string) $row['content']), 50);
            $documents[$row['id']]['template'] = $row['template'];
            $documents[$row['id']]['date'] = date('d M, Y', strtotime($row['created_at']));
            $documents[$row['id']]['time'] = date('H:i:s', strtotime($row['created_at']));
            $documents[$row['id']]['bot_id'] = $row['bot_id'];
            $documents[$row['id']]['tag_id'] = $row['tag_id'];

            $template = ORM::for_table($config['db']['pre'] . 'ai_templates')
                ->where('slug', $row['template'])
                ->find_one();
            if (empty($template)) {
                $template = ORM::for_table($config['db']['pre'] . 'ai_custom_templates')
                    ->where('slug', $row['template'])
                    ->find_one();
            }
            if (!empty($template)) {
                $translations = json_decode((string) $template['translations'], true);
                $template['title'] = !empty($translations[$config['lang_code']]['title'])
                    ? $translations[$config['lang_code']]['title']
                    : $template['title'];
                $documents[$row['id']]['template'] = $template;
            } else {
                if ($row['template'] == 'quickai-speech-to-text') {
                    $documents[$row['id']]['template'] = array(
                        'icon' => 'fa fa-headphones',
                        'title' => __('Speech to Text'),
                        'url'   => (!$documents[$row['id']]['bot_id']) ? url('ALL_DOCUMENTS', 0) . '/' . $documents[$row['id']]['id'] : $config['site_url'] . 'ai-chat' . '/' . $documents[$row['id']]['bot_id'],
                    );
                } else if ($row['template'] == 'quickai-ai-code') {
                    $documents[$row['id']]['template'] = array(
                        'icon' => 'fa fa-code',
                        'title' => __('AI Code'),
                        'url'   => (!$documents[$row['id']]['bot_id']) ? url('ALL_DOCUMENTS', 0) . '/' . $documents[$row['id']]['id'] : $config['site_url'] . 'ai-chat' . '/' . $documents[$row['id']]['bot_id'],
                    );
                } else if ($row['template'] == 'ai-team') {
                    $documents[$row['id']]['template'] = array(
                        'icon' => 'fa fa-circle',
                        'title' => __('AI Team'),
                        'url'   => (!$documents[$row['id']]['bot_id']) ? url('ALL_DOCUMENTS', 0) . '/' . $documents[$row['id']]['id'] : $config['site_url'] . 'ai-chat' . '/' . $documents[$row['id']]['bot_id'],
                    );
                } else if ($row['template'] == 'ai-tag') {
                    $documents[$row['id']]['template'] = array(
                        'icon' => 'fa fa-image',
                        'title' => __('AI Tag'),
                        'url'   =>  $config['site_url'] . 'ai-images?tag_id=' . $documents[$row['id']]['tag_id']
                    );
                } else {
                    $documents[$row['id']]['template'] = array(
                        'icon' => 'fa fa-check-square',
                        'title' => $row['template'],
                        'url'   => (!$documents[$row['id']]['bot_id']) ? url('ALL_DOCUMENTS', 0) . '/' . $documents[$row['id']]['id'] : $config['site_url'] . 'ai-chat' . '/' . $documents[$row['id']]['bot_id'],
                    );
                }
            }
        }

        if (count($documents) > 0) {
            $result['success'] = true;
            $result['data'] = $documents;
            die(json_encode($result));
        } else {
            $result['success'] = true;
            $result['data'] = $documents;
            die(json_encode($result));
        }
    }
}

// Function to create uai directory 
function createUaiDirectory()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $directoryName = $_POST['directoryName'];

        $data = ORM::for_table($config['db']['pre'] . 'uai_folder')->create();
        $data->folder_name = ucwords($directoryName);
        $data->user_id = $_SESSION['user']['id'];
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        if ($data) {

            // $directories = ORM::for_table($config['db']['pre'] . 'uai_folder')->where('user_id', $_SESSION['user']['id'])->order_by_desc('id')->find_array();
            $sql = "SELECT fd.id, fd.user_id, fd.folder_name, COALESCE(SUM(doc.word_count), 0) AS word_count, COUNT(doc.directory_id) AS doc_count 
                    FROM `" . $config['db']['pre'] . "uai_folder` fd 
                    LEFT JOIN `" . $config['db']['pre'] . "uai_training_doc` doc ON doc.directory_id = fd.id 
                    WHERE fd.user_id = '" . $_SESSION['user']['id'] . "' 
                    GROUP BY fd.id 
                    ORDER BY fd.id DESC";
            $directories = ORM::for_table($config['db']['pre'] . 'uai_folder')->raw_query($sql)->find_array();

            $result['success'] = true;
            $result['message'] = __('Directory created successfully');
            $result['directory_id'] = $data->id;
            $result['dir_list'] = $directories;
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// Function to delete uai directory 
function deleteUaiDirectory()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $directoryId = $_GET['directoryId'];

        $getDir = ORM::for_table($config['db']['pre'] . 'uai_folder')->where('user_id', $_SESSION['user']['id'])->where('id', $directoryId);

        if ($getDir->find_one()) {
            $getDoc = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('directory_id', $directoryId);
            if ($getDoc->find_many()) {
                $getDoc->delete_many();
            }

            $getDir->delete_many();
            $dirCount = ORM::for_table($config['db']['pre'] . 'uai_folder')->where('user_id', $_SESSION['user']['id'])->count();

            $result['success'] = true;
            $result['message'] = __('Directory deleted successfully');
            $result['dirCount'] = $dirCount;
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Directory not found');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// FUNCTION TO GET FOLDER TRAINING DOCUMENT
function showTrainingDocumentList()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $uaiAgentId = $_GET['uaiAgentId'];

        $getTrainDoc = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('agent_id', $uaiAgentId)->find_array();

        $charsCount = 0;
        if (count($getTrainDoc) > 0) {
            foreach ($getTrainDoc as $tDoc) {
                $charsCount += $tDoc['word_count'];
            }
        }

        if (count($getTrainDoc) > 0) {
            $result['success'] = true;
            $result['documents'] = $getTrainDoc;
            $result['word_count'] = $charsCount;
            $result['doc_count'] = count($getTrainDoc);
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Training Documents not found');
        $result['word_count'] = $charsCount;
        $result['doc_count'] = count($getTrainDoc);
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function summarizeText($text, $divide_character_num)
{
    if (strlen($text) <= $divide_character_num) {
        return $text;
    }

    global $config;

    $total_word_num = strlen($text);
    // Calculate the number of chunks needed
    $numChunks = ceil($total_word_num / $divide_character_num);

    $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $system_prompt = "I need your help in summarization.";

    $assistant_prompt = "";

    $summarized_data = "";

    // Divide the sentences into chunks
    for ($i = 0; $i < $numChunks; $i++) {

        $chunk = substr($text, $i * $divide_character_num, $divide_character_num);

        $user_input = "Summarize this text below.\n" . $chunk;

        $output = openAI_gpt4($api_key, $endpoint, $system_prompt, $assistant_prompt, $user_input);

        if ($output == "error") {
            $result['success'] = false;
            $result['error'] = __('Unexpected error, please try again.');
            die(json_encode($result));
        }

        $summarized_data .= $output;
    }

    // Call the summarizeText function again to further summarize the concatenated outputs
    return summarizeText($summarized_data, $divide_character_num);
}

function chunkText($text, $chunkSize)
{
    $chunks = [];
    $total_word_num = strlen($text);
    // Calculate the number of chunks needed
    $numChunks = ceil($total_word_num / $chunkSize);

    for ($i = 0; $i < $numChunks; $i++) {

        $chunk = substr($text, $i * $chunkSize, $chunkSize);
        $chunks[] = trim($chunk);
    }

    return $chunks;
}

// function check_remember($bot_id)
// {
//     global $config;

//     $getDocument = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('ai_chat_bot_id', $bot_id)->find_many();

//     $output = "";

//     if (count($getDocument) > 0) {
//         $title = $getDocument[0]->title;

//         $prompt = "My id is " . $bot_id . " If you can't remember my documents like " . $title . ", respond only 0. If you can, respond only 1.";

//         $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

//         $endpoint = 'https://api.openai.com/v1/chat/completions';

//         $output = openAI_gpt4($api_key, $endpoint, "", "", $prompt);
//     }

//     return $output;
// }

function enable_retrival($api_key)
{
    $endpoint = "https://api.openai.com/v1/assistants";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'OpenAI-Beta: assistants=v1',
    ];

    $data = [
        'instructions' => 'Use your knowledge base to best respond to customer queries.',
        'tools' => [
            [
                'type' => 'retrieval',
            ],
        ],
        'model' => 'gpt-4-1106-preview',
    ];

    $ch = curl_init($endpoint);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $result['success'] = false;
        $result['error'] = __('Error in enable assistants.');
        die(json_encode($result));
    }

    curl_close($ch);

    return $response;
}

function upload_file_assistants($api_key, $file_url)
{
    $endpoint = "https://api.openai.com/v1/files";

    $headers = [
        'Authorization: Bearer ' . $api_key,
    ];

    $data = [
        'purpose' => 'assistants',
        'file' => $file_url,
    ];

    $ch = curl_init($endpoint);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

    $file = curl_exec($ch);

    if (curl_errno($ch)) {
        $result['success'] = false;
        $result['error'] = __('Error in file uplaod for assistants.');
        die(json_encode($result));
    } else {
        $decodedResponse = json_decode($file, true);

        if (json_last_error() === JSON_ERROR_NONE && isset($decodedResponse['id'])) {
            $fileId = $decodedResponse['id'];
            return $fileId;
        } else {
            $result['success'] = false;
            $result['error'] = __('Unexpected Error in file uplaod for assistants.');
            die(json_encode($result));
        }
    }

    curl_close($ch);
}

function add_file_to_assistant($api_key, $fileIds, $bot_name, $instruction)
{

    $endpoint = "https://api.openai.com/v1/assistants";

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'OpenAI-Beta: assistants=v1',
    ];

    $data = [
        'instructions' => $instruction,
        'name' => $bot_name,
        'tools' => [
            [
                'type' => 'retrieval',
            ],
        ],
        'model' => 'gpt-4-1106-preview',
        'file_ids' => $fileIds,
    ];

    $ch = curl_init($endpoint);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    $output = json_decode($response);

    if (curl_errno($ch)) {
        $result['success'] = false;
        $result['error'] = __('Error in add file to the assistant.');
        die(json_encode($result));
    } else {
        return $output;
    }

    curl_close($ch);
}

function uai_train($bot_id)
{
    global $config;

    $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

    // $assistant = enable_retrival($api_key);


    $getDocument = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('ai_chat_bot_id', $bot_id)->find_many();

    $fileIds = [];

    if (count($getDocument) > 0) {
        foreach ($getDocument as $doc) {

            $name = uniqid();
            $file_dir = '/storage/ai_audios/';
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_dir .  $name;

            file_put_contents($file_path, file_get_contents($doc->file_url));

            // Use curl_file_create to create a file stream
            $c_file = curl_file_create($file_path);

            $fileId = upload_file_assistants($api_key, $c_file);

            $doc->assistant_file_id = $fileId;

            $doc->save();

            unlink($file_path);

            $fileIds[] = $fileId;
        }
    }

    // Add the file to the assistant

    $uai_agent = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->where('ai_chat_bot_id', $bot_id)->find_one();

    $bot_name = $uai_agent->agent_name;

    $instruction = $uai_agent->role_description;

    $assistant = add_file_to_assistant($api_key, $fileIds, $bot_name, $instruction);

    $uai_agent->assistant_id = $assistant->id;

    $uai_agent->save();

    return true;
}

function upload_file_to_s3($file, $s3_key)
{
    require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

    $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));

    try {
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => get_option('ai_tts_aws_s3_region'),
            'credentials' => $credentials
        ]);
    } catch (Exception $e) {
        $result['success'] = false;
        $result['error'] = __('Incorrect AWS credentials.');
        $result['api_error'] = $e->getMessage();
        die(json_encode($result));
    }

    // backet name
    $bucket = 'aiteamup-live';

    try {
        $s3_result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $s3_key,
            'Body' => fopen($file, 'r'),
            'ACL' => 'public-read', // Adjust the ACL based on your requirements
        ]);
    } catch (Exception $e) {

        $result['success'] = false;
        $result['error'] = __('Error in uploading file to S3.');
        $result['api_error'] = $e->getMessage();
        die(json_encode($result));
    }

    $file_url = $s3_result->get('ObjectURL');

    return $file_url;
}

// FUNCTION TO CREATE TRAINING DOCUMENT
function createTrainingDocument()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $data = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->create();
        $data->user_id      =   $_SESSION['user']['id'];
        $data->agent_id     =   $_POST['agentId'];
        $data->ai_chat_bot_id   =   $_POST['agentChatbotId'];
        $data->action       =   $_POST['section'];

        // Write Section Training Document Functionality
        if ($_POST['section'] === 'write') {

            $content = strip_tags($_POST['content']);
            $title = $_POST['title'];

            $tempFile = tempnam(sys_get_temp_dir(), 'user_content');
            $tempFilePath = $tempFile . '.txt';

            $file_name = "written_content.txt";
            // Write title and content to the temporary file
            file_put_contents($tempFilePath, "Title: $title\nContent: $content");

            $job_id = uniqid();

            // $s3_key = 'aiteamup/aiteamup_audio/' . $job_id . $file_name;
            $s3_key = 'aiteamup_files/' . $job_id . "_" . $file_name;

            $s3_url = upload_file_to_s3($tempFilePath, $s3_key);

            $data->title        =   ucfirst($_POST['title']);
            $data->content      =   $content;
            $data->file_url      =  $s3_url;
            $data->s3_key = $s3_key;
        }

        // Upload Section Training Document Functionality
        if ($_POST['section'] === 'upload') {
            $data->title        =   $_POST['file_name'];

            $file = $_FILES['file_content']['tmp_name'];

            $file_name = basename($_FILES['file_content']['name']);

            $file_extension = pathinfo($_FILES['file_content']['name'], PATHINFO_EXTENSION);

            // $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/ai_audios/';
            // if (!is_dir($targetDir)) {
            //     mkdir($targetDir, 0777, true);
            // }

            // $targetFile = $targetDir . $file_name;
            // move_uploaded_file($_FILES['file_content']['tmp_name'], $targetFile);

            // $c_file = curl_file_create($_FILES['file_content']['tmp_name'], $_FILES['file_content']['type'], $file_name);

            // $baseURL = $config['site_url'] . "storage/ai_audios/" . $file_name;
            // // $curl_file = new CURLFile($targetFile);

            if (in_array($file_extension, ['pdf', 'doc', 'docx', 'txt', 'odt', 'png', 'jpg', 'jpeg'])) {

                $job_id = uniqid();

                // $s3_key = 'aiteamup/aiteamup_audio/' . $job_id . $file_name;
                $s3_key = 'aiteamup_files/' . $job_id . "_" . $file_name;

                $s3_url = upload_file_to_s3($file, $s3_key);
                $data->file_url   =   $s3_url;
                $data->s3_key = $s3_key;

                // $docLine = '';
                // if ($_FILES['file_content']['type'] === 'application/pdf') {
                //     // Command to run pdftotext
                //     $command = "pdftotext -q '{$targetFile}' -";
                //     exec($command, $output, $returnCode);
                //     if ($returnCode === 0) {
                //         // Output the extracted text
                //         foreach ($output as $line) {
                //             $docLine .= trim($line) . ' ';
                //         }
                //     }
                // } else if (($_FILES['file_content']['type'] !== 'application/pdf') && ($_FILES['file_content']['type'] !== 'image/*')) {
                //     chmod($targetFile, 0777);
                //     if (pathinfo($targetFile, PATHINFO_EXTENSION) === 'doc') {
                //         $command = "catdoc -w '{$targetFile}'";
                //         $docLine = shell_exec($command);
                //         // $docLine = file_get_contents($targetFile);
                //     }
                //     if (pathinfo($targetFile, PATHINFO_EXTENSION) === 'docx') {
                //         $command = "docx2txt '{$targetFile}' -";
                //         $docLine = shell_exec($command);
                //         // $docLine = file_get_contents($targetFile);
                //     }
                //     if (pathinfo($targetFile, PATHINFO_EXTENSION) === 'odt') {
                //         $command = "odt2txt '{$targetFile}'";
                //         $docLine = shell_exec($command);
                //         // $docLine = file_get_contents($targetFile);
                //     }
                //     if (pathinfo($targetFile, PATHINFO_EXTENSION) === 'txt') {
                //         $docLine = file_get_contents($targetFile);
                //     }
                // }

                // // $final_content = summarizeText($docLine, $divide_character_num);
                // $data->content    =   $docLine;
                // $data->word_count   =   strlen($$docLine);
            } else {
                $result['success'] = false;
                $result['message'] = __('Invalid file format, Try again.');
                die(json_encode($result));
            }
        }

        // Import Section Training Document Functionality
        if ($_POST['section'] === 'import') {
            $url = $_POST['web_url'];
            // $data->title        =   $url;
            // $data->file_url      =   $url;

            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 9; Pixel 3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36'); // Set a user agent string

                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'cURL Error: ' . curl_error($ch);
                }
                curl_close($ch);

                if ($response) {

                    $doc = new DOMDocument();
                    libxml_use_internal_errors(true); // Handle any HTML errors

                    // Load the HTML content into the DOMDocument
                    $doc->loadHTML($response);

                    // Remove script elements from the DOM
                    $scriptNodes = $doc->getElementsByTagName('script');
                    foreach ($scriptNodes as $scriptNode) {
                        $scriptNode->parentNode->removeChild($scriptNode);
                    }

                    // Remove style elements from the DOM
                    $styleNodes = $doc->getElementsByTagName('style');
                    foreach ($styleNodes as $styleNode) {
                        $styleNode->parentNode->removeChild($styleNode);
                    }

                    // Create a DOMXPath object to query the DOM
                    $xpath = new DOMXPath($doc);

                    // Query for all text nodes within the body
                    $textNodes = $xpath->query('//body//text()');

                    $cleanedContent = '';
                    foreach ($textNodes as $textNode) {
                        // Append the text content to the cleaned content
                        $cleanedText = trim($textNode->nodeValue);

                        // Filter out specific unwanted content
                        if (
                            !empty($cleanedText) &&
                            !preg_match('/\b(\/\/|=|{|}|function|const|var|\(\))\b/', $cleanedText)
                        ) {
                            $cleanedContent .= $cleanedText . ' ';
                        }
                    }

                    // Optionally, you can remove excess whitespace
                    $cleanedContent = preg_replace('/\s+/', ' ', $cleanedContent);

                    // $final_cleanedContent = summarizeText($cleanedContent, $divide_character_num);

                    $tempFile = tempnam(sys_get_temp_dir(), 'user_import_website');
                    $tempFilePath = $tempFile . '.txt';
                    $title = $url;
                    $file_name = "imported_website_content.txt";
                    // Write title and content to the temporary file
                    file_put_contents($tempFilePath, "Title: $title\nContent: $cleanedContent");

                    $job_id = uniqid();

                    // $s3_key = 'aiteamup/aiteamup_audio/' . $job_id . $file_name;
                    $s3_key = 'aiteamup_files/' . $job_id . "_" . $file_name;

                    $s3_url = upload_file_to_s3($tempFilePath, $s3_key);

                    $data->title        =   $title;
                    $data->file_url      =  $s3_url;
                    $data->s3_key = $s3_key;
                    // $data->title        =   $_POST['web_url'];
                    // $data->content      =   $cleanedContent;
                    // $data->word_count   =   strlen($cleanedContent);
                }
            } catch (\Throwable $th) {
                $result['success'] = false;
                $result['error'] = __('Please import valid URL. This website is not valid');
            }
        }

        $data->status       =   1;
        $data->created_at   =   date('Y-m-d H:i:s');
        $data->updated_at   =   date('Y-m-d H:i:s');
        $data->save();

        if ($data) {
            // function to update ai chat bot prompt msg 
            updateDocumentPromptInChatBot($_POST['agentChatbotId']);
            $result['success'] = true;
            $result['message'] = __('Training Documents created successfully.');
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Training Documents not found');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// Function to manage ai chat bot prompt msg 
function updateDocumentPromptInChatBot($aiChatBotId)
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        uai_train($aiChatBotId);

        // $docPrompt = '';
        // $getDocument = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('ai_chat_bot_id', $aiChatBotId)->find_many();

        // if (count($getDocument) > 0) {
        //     foreach ($getDocument as $doc) {
        //         $docPrompt .= trim($doc->content) . "\r\n" . "\r\n";
        //     }
        // }

        // $final_prompt = summarizeText($docPrompt, 3000);

        // $update_document = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($aiChatBotId);
        // $update_document->set('prompt', $final_prompt);

        // if ($update_document->save()) {
        //     return true;
        // }
        // return false;
        return true;
    }
    return false;
}

// Function to get uai agents lists 
function getUaiAgentList()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $directoryName = $_POST['directoryName'];
        $data = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->order_by_desc('id')->find_array();

        if ($data) {
            $result['success'] = true;
            $result['data'] = $data;
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function create_chat_thread($api_key)
{
    $url = 'https://api.openai.com/v1/threads';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'OpenAI-Beta: assistants=v1',
    ];

    $data = '{}'; // Empty JSON object as no specific data is required for thread creation

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    $decodedData = json_decode($response, true);

    if (curl_errno($ch)) {
        return 'error';
    } else {
        return $decodedData['id'];
    }

    curl_close($ch);
}

function webhook_request_checkout_session_completed()
{
    $result = array();

    global $config;

    $post_data = @file_get_contents("php://input");
    $request_data = json_decode($post_data, true);
    
    $payer_email = $request_data['payer_email'];

    $user = ORM::for_table($config['db']['pre'] . 'user')->where('email', $payer_email)->find_one();

    if (!$user) {
        $result['success'] = false;
        die(json_encode($result));
    }

    $pass = $user->temp_pass;

    email_template("signup-details", $user->id, $pass);

    $user->plan_type = $user->temp_plan_type;
    $user->group_id = $user->temp_group_id;
    
    $user->save();

    //file_put_contents('go-highlevel.txt',print_r($user,true));

    // create user on gohighlevel
    $subAccountData = [
        'first_name' => $user->name,
        'email' => $user->email, 
        'businessName' => $user->name
    ];
    $response = createGHLLocation($subAccountData);
    if (!empty($response['id'])) {
        $userGHL = ORM::for_table($config['db']['pre'] . 'user')->where('email', $payer_email)->find_one();
        if(isset($userGHL->ghl_location_id) && isset($userGHL->ghl_api_key)){
            $userGHL->ghl_location_id = $response['id'];
            $userGHL->ghl_api_key = $response['apiKey'];
            $userGHL->save();
        }
        
        // delete user or perform any other action if any error occured
        //$result['ghl_error']= !empty(array_values($r)[0]['message'])?array_values($r)[0]['message']:'Something went wrong';
       // ORM::for_table($config['db']['pre'] . 'user')->where('id', $user_id)->delete_many();
       // header("Location: " . $link['SIGNUP']);
       // exit;
    }
    $result['success'] = true;
    die(json_encode($result));
}

// FUNCTION TO CREATE UAI AGENT
function createUaiAgent()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $membership = get_user_membership_detail($_SESSION['user']['id']);
        $uai_agent_limit = $membership['settings']['ai_uai_agent_limit'];
        $total_uai_agent_used = get_user_option($_SESSION['user']['id'], 'total_uai_agent_used', 0);

        // check user uai agent limit
        if ($uai_agent_limit != -1 && ($uai_agent_limit <= $total_uai_agent_used)) {
            $result['success'] = false;
            $result['message'] = __('UAi Agent Create limit exceeded, Upgrade your membership plan.');
            die(json_encode($result));
        }

        $agentImage = NULL;
        if (!empty($_FILES['agentImage']['name'])) {
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/bot_images/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $name = uniqid() . '.png';

            $targetFile = $targetDir . $name;
            move_uploaded_file($_FILES['agentImage']['tmp_name'], $targetFile);

            // Convert the image to PNG format
            $image = imagecreatefromstring(file_get_contents($targetFile));
            imagepng($image, $targetFile, 0); // 0 compression for best quality

            // Resize the image
            resize_image($targetFile, 640, 700); // Adjust width and height as needed
        }

        $aiChat = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->create();
        $aiChat->user_id =   $_SESSION['user']['id'];
        $aiChat->name   =   ucwords($_POST['agentName']);
        $aiChat->image  =   $name;
        $aiChat->role   =   ucwords($_POST['agentRole']);
        $aiChat->welcome_message   =   'Hi! My name is ' . ucwords($_POST['agentName']) . ', and I\'m a ' . $_POST['agentRole'] . '. How may I help you today ?';
        $aiChat->bio    =   ucfirst($_POST['agentRoleDesc']);
        $aiChat->category_id    =   1;
        $aiChat->active         =   1;
        $aiChat->is_uai_agent   =   1;
        $aiChat->save();

        if ($aiChat) {

            // $api_key = ORM::for_table($config['db']['pre'] . 'api_keys')->where('title', 'openai')->find_one()->api_key;

            // $thread_id = create_chat_thread($api_key);

            $data = ORM::for_table($config['db']['pre'] . 'uai_agent')->create();
            $data->user_id      =   $_SESSION['user']['id'];
            $data->ai_chat_bot_id   =   $aiChat->id;
            $data->agent_name   =   ucwords($_POST['agentName']);
            $data->agent_gender =   ucwords($_POST['agentGender']);
            $data->agent_role   =   ucwords($_POST['agentRole']);
            $data->role_description =   ucfirst($_POST['agentRoleDesc']);
            $data->agent_image  =   $name;
            $data->tone         =   ucwords($_POST['agentTone']);
            $data->response_style   =   ucwords($_POST['agentResStyle']);
            // $data->thread_id   =   $thread_id;
            $data->created_at   =   date('Y-m-d H:i:s');
            $data->updated_at   =   date('Y-m-d H:i:s');
            $data->save();

            update_user_option($_SESSION['user']['id'], 'total_uai_agent_used', $total_uai_agent_used + 1);

            $result['success'] = true;
            $result['message'] = __('UAi Agent created successfully.');
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Failed to create UAi agent');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// FUNCTION TO EDIT UAI AGENT
function editUaiAgent()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $agentId = $_GET['agentId'];

        $getData = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->where('id', $agentId)->find_array();

        if ($getData) {
            $result['success'] = true;
            $result['uaiAgent'] = $getData[0];
            $result['siteUrl'] = $config['site_url'];
            $result['message'] = __('UAi Agent.');
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Failed to get UAi agent');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

//FUNCTION TO UPDATE UAI AGENT
function updateUaiAgent()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $new_welcome_message = "";

        $agentId = $_POST['agentId'];
        if (!empty($agentId)) {
            $agentData = ORM::for_table($config['db']['pre'] . 'uai_agent')->find_one($agentId);
            $getData = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->find_one($agentData->ai_chat_bot_id);

            $name = $_POST['agentImg'];
            if (!empty($_FILES['agentImage']['name'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/storage/bot_images/' . $_POST['agentImg']);
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/bot_images/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $name = uniqid() . '.png';
                $targetFile = $targetDir . $name;
                move_uploaded_file($_FILES['agentImage']['tmp_name'], $targetFile);

                // Convert the image to PNG format
                $image = imagecreatefromstring(file_get_contents($targetFile));
                imagepng($image, $targetFile, 0); // 0 compression for best quality

                // Resize the image
                resize_image($targetFile, 640, 700); // Adjust width and height as needed
            }

            $current_welcome_message = $getData['welcome_message'];
            $current_name = $getData['name'];
            $current_role = $getData['role'];

            $new_welcome_message = str_replace($current_name, $_POST['agentName'], $current_welcome_message);
            $new_welcome_message = str_replace($current_role, $_POST['agentRole'], $new_welcome_message);

            $agentData->set('agent_name', ucwords($_POST['agentName']));
            $agentData->set('agent_gender', ucwords($_POST['agentGender']));
            $agentData->set('agent_role', ucfirst($_POST['agentRole']));
            $agentData->set('role_description', ucfirst($_POST['agentRoleDesc']));
            $agentData->set('tone', ucfirst($_POST['agentTone']));
            $agentData->set('response_style', ucfirst($_POST['agentResStyle']));
            $agentData->set('agent_image', $name);
            $agentData->save();

            if (!empty($agentData['ai_chat_bot_id'])) {

                $getData->set('name', $_POST['agentName']);
                $getData->set('image', $name);
                $getData->set('role', $_POST['agentRole']);
                $getData->set('welcome_message', $new_welcome_message);
                $getData->save();

                $result['success'] = true;
                $result['message'] = __('UAi agent updated successfully.');
                die(json_encode($result));
            }
        }
        $result['success'] = false;
        $result['message'] = __('failed to upload UAi agent');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_s3_file($s3_key)
{
    require_once ROOTPATH . '/includes/lib/aws/aws-autoloader.php';

    $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));

    try {
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => get_option('ai_tts_aws_s3_region'),
            'credentials' => $credentials
        ]);
    } catch (Exception $e) {
        $result['success'] = false;
        $result['error'] = __('Incorrect AWS credentials.');
        $result['api_error'] = $e->getMessage();
        die(json_encode($result));
    }
    $s3->deleteObject([
        'Bucket' => "aiteamup-live",
        'Key' => $s3_key,
    ]);
}

// FUNCTION TO DELETE UAI DOCUMENT
function deleteUaiDocument()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $documentId = $_GET['documentId'];

        $getDir = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('id', $documentId);

        if ($getDir->find_one()) {
            $getDoc = $getDir->find_one();
            if ($getDoc->action === 'upload') {
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/uai-agent/';
                unlink($targetDir . $getDoc->action);
            }
            $getDir->delete_many();

            // function to update ai chat bot prompt msg 
            sleep(3);
            updateDocumentPromptInChatBot($getDoc->ai_chat_bot_id);

            if ($getDoc->s3_key) {
                delete_s3_file($getDoc->s3_key);
            }
            $result['success'] = true;
            $result['message'] = __('Document deleted successfully');
            $result['agentId'] = $getDoc->agent_id;
            $result['aiChatBotId'] = $getDoc->ai_chat_bot_id;
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Directory not found');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// FUNCTION TO DELETE UAI AGENT
function deleteUAiAgent()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $agentId = $_GET['agentId'];

        $getData = ORM::for_table($config['db']['pre'] . 'uai_agent')->where('user_id', $_SESSION['user']['id'])->where('id', $agentId);
        if ($getData->find_one()) {
            $getAgent = $getData->find_one();

            ORM::for_table($config['db']['pre'] . 'ai_chat_bots')->where('id', $getAgent->ai_chat_bot_id)->delete_many();
            ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('agent_id', $getAgent->id)->delete_many();

            if ($getAgent->action === 'upload') {
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/uai-agent/';
                unlink($targetDir . $getAgent->action);
            }
            $getData->delete_many();

            $result['success'] = true;
            $result['message'] = __('UAi Agent deleted successfully');
            $result['agentId'] = $getAgent->agent_id;
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('UAi Agent not found');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

//FUNCTION TO EDIT UAI DOCUMENT
function editUaiDocument()
{
    $result = array();
    if (checkloggedin()) {
        global $config;
        $documentId = $_GET['documentId'];

        $getDir = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->where('user_id', $_SESSION['user']['id'])->where('id', $documentId)->find_array();

        $result['success'] = true;
        $result['document'] = $getDir[0];
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

//FUNCTION TO UPDATE UAI DOCUMENT
function updateUaiDocument()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $documentId = $_POST['documentId'];
        $documentType = $_POST['documentType'];
        $docuemtnContent = $_POST['docuemtnContent'];

        $docuemtnContent    =   strip_tags($docuemtnContent);
        $contentWordCount   =   strlen(strip_tags($docuemtnContent));

        $update_document = ORM::for_table($config['db']['pre'] . 'uai_training_doc')->find_one($documentId);

        $update_document->set('updated_at', date('Y-m-d H:i:s'));
        $update_document->set('content', $docuemtnContent);
        $update_document->set('word_count', $contentWordCount);

        if ($update_document->save()) {
            // function to update ai chat bot prompt msg 
            sleep(3);
            updateDocumentPromptInChatBot($update_document->ai_chat_bot_id);

            $result['success'] = true;
            $result['message'] = __('Training Documents updated successfully.');
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __('Training Documents not found');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

// save custom instruction
function save_custom_instruction()
{
    $result = array();

    if (!checkloggedin()) {
        $result['success'] = false;
        $result['error'] = __('Unexpected error, please try again.');
        die(json_encode($result));
    }

    global $config;

    $fields = [
        'user_based', 'user_do_for', 'user_hobbies', 'user_subjects',
        'user_goals', 'res_formal', 'res_long', 'res_address', 'res_opinions'
    ];

    $custom_instruction = ORM::for_table($config['db']['pre'] . 'custom_instructions')
        ->where('user_id', $_SESSION['user']['id'])
        ->find_one();

    if (!$custom_instruction) {
        $custom_instruction = ORM::for_table($config['db']['pre'] . 'custom_instructions')->create();
    }

    foreach ($fields as $field) {
        $custom_instruction->$field = $_POST[$field];
    }

    $custom_instruction->user_id = $_SESSION['user']['id'];

    $custom_instruction->save();

    $result['success'] = true;
    $result['message'] = __('Custom instruction saved successfully.');

    die(json_encode($result));
}
// auto responder save
function save_autoresponder()
{
    $result = array();

    if (!checkloggedin()) {
        $result['success'] = false;
        $result['error'] = __('Unexpected error, please try again.');
        die(json_encode($result));
    }

    global $config;

    $fields = [
        "mailchimp_api_key",
        "mailchimp_list_id",
        "activecampaign_api_key",
        "activecampaign_list_id",
        "activecampaign_account_id",
        "sendlane_api_key",
        "sendlane_list_id",
        "getresponse_api_key",
        "getresponse_campaign_id",
        "icontact_api_username",
        "icontact_api_password",
        "icontact_account_id",
        "icontact_list_id",
        "constantcontact_api_key",
        "constantcontact_list_id",
        "drip_api_token",
        "drip_account_id",
        "drip_tag",
    ];

    $user = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);

    if (!$user) {
        error_exeption("User not found");
    }

    foreach ($fields as $field) {
        $user->$field = $_POST[$field];
    }

    $user->save();

    $result['success'] = true;
    $result['message'] = __('Auto responder keys saved successfully.');

    die(json_encode($result));
}

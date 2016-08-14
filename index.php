<?php
/*
Shorten URL bot
Need PHP 7
Creator : @Negative | @Taylor_Team

هرگونه کپی برداری بدون ذکر منبع پیگرد قانونی دارد
*/
define('API_KEY', 'توکن شما');
$admin = 'ID';
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
include('config.php');
$db = new mysqli($server, $username, $password, $databasename);
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$message = $update['message'];
if($message['text']){
  $chat_id = $update['message']['chat']['id'];
  $text = $update['message']['text'];
  $from = $update['message']['from']['id'];
  if(preg_match('/^([Hh]ttp|[Hh]ttps)(.*)/',$text)){
    bot('sendMessage',array('chat_id'=>$chat_id,'text'=>'Please Wait 😉'));
    bot('sendChatAction',array('chat_id'=>$chat_id,'action'=>'typing'));
    $get = file_get_contents('http://yeo.ir/api.php?url='.$text);
    $get2 = file_get_contents('http://llink.ir/yourls-api.php?signature=a13360d6d8&action=shorturl&url='.$text.'&format=sample');
    bot('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"<b>1</b> : ".$get."\n\n<b>2</b> : ".$get2."\n\n",
      'parse_mode'=>'HTML'
    ]);
  }
  if(preg_match('/^\/([sS]tart)|([Hh]elp)/',$text)){
    $slll = $db->query('SELECT id FROM member WHERE id = '.$chat_id);
    if($slll->num_rows == 0){
      $db->query('INSERT INTO member (id) VALUES ('.$chat_id.')');
    }
    bot('sendMessage',array(
      'chat_id'=>$chat_id,
      'text'=>"Hello Welcome To <b>Shorten URL bot</b>\nCreator : @Negative\n\nSend a URL",
      'parse_mode'=>'HTML'
    ));
  }
  if(preg_match('/^\/([Bb]c) (.*)/',$text) and $from == $admin){
    preg_match('/^\/([Bb]c) (.*)/',$text,$match);
    $sendreq = $db->query('SELECT id FROM member');
    while($rw = $sendreq->fetch_assoc()){
      bot('sendMessage',[
        'chat_id'=>$rw['id'],
        'text'=>$match[1],
        'parse_mode'=>'HTML'
      ]);
    }
  }
}
/*if($update['inline_query']){
  $query = $update['inline_query']['query'];
  $id = $update['inline_query']['id'];
  if(preg_match('/^([Hh]ttp|[Hh]ttps)(.*)/',$query)){
    $get = file_get_contents('http://yeo.ir/api.php?url='.$text);
    $get2 = file_get_contents('http://llink.ir/yourls-api.php?signature=a13360d6d8&action=shorturl&url='.$text.'&format=sample');
    bot('answerInlineQuery',[
      'inline_query_id'=>$id,
      'results'=>json_encode([
        [
          'type'=>'article','id'=>base64_encode('1'),'title'=>'Shorten By yeo.ir','input_message_content'=>['message_text'=>"$get"]
        ],
        [
          'type'=>'article','id'=>base64_encode('2'),'title'=>'Shorten By llink.ir','input_message_content'=>['message_text'=>"$get2"]
        ]
      ])
    ]);
  }
}*/

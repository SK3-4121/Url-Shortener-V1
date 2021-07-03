<?php
  $link = "Your Domain";
  $main = $_GET["main"];
  $amount = $_GET["amount"];

  function getIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $ip;
  }

  function send_webhook($Url, $New_Url) {
    $webhookurl = "Your Discord Webhook";
    $ipaddr = getIp();
    $timestamp = date("c", strtotime("now"));
    $json_data = json_encode([
        "content" => "",
        "username" => "Website Logs V1",
        "tts" => false,
        "embeds" => [
            [
                "title" => "",
                "type" => "rich",
                "description" => "Your URL Shortener logs. This is just some thing i made in like 2 hrs :/ bye bye.",
                "url" => "",
                "timestamp" => $timestamp,
                "color" => hexdec("3366ff"),
                "footer" => [
                    "text" => "Made by SK3#3160",
                    "icon_url" => "https://cdn.discordapp.com/avatars/781337038040727583/228a493dc1f39718e584c8890c94deeb.webp?size=128"
                ],
                "author" => [
                    "name" => "Logs",
                    "url" => ""
                ],
                "fields" => [
                    [
                        "name" => "IP",
                        "value" => $ipaddr,
                        "inline" => true
                    ],
                    [
                        "name" => "Url / NewUrl",
                        "value" => $Url . " / " . $New_Url,
                        "inline" => true
                    ]
                ]
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $ch = curl_init($webhookurl);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
  }

  function gen_string($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  if ($main and $amount) {
    $file_name = gen_string($amount);
    $new_folder = mkdir($file_name);
    $new_index = fopen($file_name . "/index.php",'w');
    fwrite($new_index,'<?php header("Location: ' . $main . '") ?>');
    echo $link . $file_name . "/index.php";
    fclose($new_index);
    send_webhook($main, $link . $file_name . "/index.php");
  } else {
    die("There was no Url sent :((((");
  }
?>

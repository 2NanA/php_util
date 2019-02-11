<?php 
 
error_reporting(E_ALL);
set_time_limit(0);
  

// $str = 'HTTP Visit lzksockethttpserver ^ 5651 $ 8908D59B-00D0-85D3-4FDB-07BDCD6335BF client send';

// var_dump(get_between($str, '^', '$'));


// var_dump(strlen("liu"));
// var_dump(strlen("刘22d六"));


// die;




// $url = 'http://127.0.0.1:50081/';
// $http_headers = ['']; //正常
// $ch = curl_init();
// curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); 
// curl_setopt( $ch, CURLOPT_HEADER, false );
// curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 8);
// curl_setopt( $ch, CURLOPT_TIMEOUT, 15);
// curl_setopt( $ch, CURLOPT_MAXREDIRS, 5); 
// curl_setopt( $ch, CURLOPT_NOSIGNAL, true);
// curl_setopt( $ch, CURLOPT_URL, $url );
// curl_setopt( $ch, CURLOPT_HTTPHEADER, $http_headers ); // 注释掉 或者传空数组都 返回正常值
// curl_setopt( $ch, CURLOPT_ENCODING, 'gzip' );
// curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
// curl_setopt( $ch, CURLOPT_HEADER, true ); 
// curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);
// $response = curl_exec( $ch );
// curl_close($ch);
// echo $response;
// die;



$sapi_type = php_sapi_name();
if($sapi_type == 'cli'){
    $isCli = true;
} else {
    $isCli = false;
}

$fp = fopen('E:\phpenv\www\guzzlestream.txt', 'r');
$port = fgets($fp, 1024);
fclose($fp);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, '127.0.0.1', $port);
// socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);// 无效

 #一次传输单条信息：
// $message = '我爱你 socket' . guid() . ' client send ';
// $message = mb_convert_encoding($message, 'GBK', 'UTF-8');
// socket_write($socket, $message, strlen($message));

// $callback = socket_read($socket, 1024);
// echo 'server return message is:'.PHP_EOL.$callback;


# 持续输入
if($isCli){
    while (true) {
        fwrite(STDOUT, "Send to server: ");
        $name = trim(fgets(STDIN));
        if ($name == 'exit') { 
            break; 
        }

        $message = $name . ' -- ' . guid() . ' client send ';
        $message = mb_convert_encoding($message, 'GBK', 'UTF-8');
    
        // echo "fafaf";
        // var_dump(socket_write($socket, $message, strlen($message)));
        // var_dump(socket_read($socket, 1024));
    
        if (socket_write($socket, $message, strlen($message)) == false) {
            echo 'fail to write'.socket_strerror(socket_last_error());
        }
        //  else {
            // $servercallback = socket_read($socket, 1024);
            // echo 'server return message is:' . PHP_EOL . $servercallback . PHP_EOL;
            
            // socket_close($socket);
        // }
        $servercallback = socket_read($socket, 8192);
        echo 'server return message is:' . PHP_EOL . $servercallback . PHP_EOL;
        // fwrite(STDOUT, "Hello, $name!");
    }
} else {
    if(@$_GET['param']){
        $pad = $_GET['param'] ?? 'default param';
    } else {
        $pad = '';
    }
    $message = "HTTP Visit lzksockethttpserver ^ {$pad} $ " . guid() . ' client send ';
    $message = mb_convert_encoding($message, 'GBK', 'UTF-8');

    // echo "fafaf";
    // var_dump(socket_write($socket, $message, strlen($message)));
    // var_dump(socket_read($socket, 1024));

    if (socket_write($socket, $message, strlen($message)) == false) {
        echo 'fail to write'.socket_strerror(socket_last_error());
    }
    //  else {
        // $servercallback = socket_read($socket, 1024);
        // echo 'server return message is:' . PHP_EOL . $servercallback . PHP_EOL;
        
        // socket_close($socket);
    // }
    $servercallback = socket_read($socket, 8192);
    echo $servercallback;
    // echo '<b>server return message is: <br>' . $servercallback . '</b>';
}




# 下面2行代码没有运行
// echo "owalida";
socket_close($socket);

# http way
exit;




// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// /****************设置socket连接选项，这两个步骤你可以省略*************/
// //接收套接流的最大超时时间1秒，后面是微秒单位超时时间，设置为零，表示不管它
// socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
//  //发送套接流的最大超时时间为6秒
// socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
// /****************设置socket连接选项，这两个步骤你可以省略*************/

// //连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
// if (socket_connect($socket, '127.0.0.1', 8000) == false) {
//     echo 'connect fail massege:'.socket_strerror(socket_last_error());
// } else {
//     while (true) {
//         fwrite(STDOUT, "Enter the message:");
//         $message = trim(fgets(STDIN));
//         //转为GBK编码，处理乱码问题，这要看你的编码情况而定，每个人的编码都不同
//         $message = mb_convert_encoding($message, 'GBK', 'UTF-8');
//         //向服务端写入字符串信息

//         if (socket_write($socket, $message, strlen($message)) == false) {
//             echo 'fail to write'.socket_strerror(socket_last_error());
//         } 

//         $servercallback = socket_read($socket, 8192);
//         echo 'client write success'. $servercallback . PHP_EOL;
//         // else {
//         //     echo 'client write success'.PHP_EOL;
//         // }
//     }
// }
// socket_close($socket);//工作完毕，关闭套接流





// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    /****************设置socket连接选项，这两个步骤你可以省略*************/
     //接收套接流的最大超时时间1秒，后面是微秒单位超时时间，设置为零，表示不管它
// socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
 //发送套接流的最大超时时间为6秒
// socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
/****************设置socket连接选项，这两个步骤你可以省略*************/

//连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
// if (socket_connect($socket, '127.0.0.1', 8888) == false) {
//     echo 'connect fail massege:'.socket_strerror(socket_last_error());
// } else {
//     while (true) {
//         fwrite(STDOUT, "Enter the message:");
//         $message = trim(fgets(STDIN));
//         //转为GBK编码，处理乱码问题，这要看你的编码情况而定，每个人的编码都不同
//         $message = mb_convert_encoding($message, 'GBK', 'UTF-8');
//         //向服务端写入字符串信息

//         if (socket_write($socket, $message, strlen($message)) == false) {
//             echo 'fail to write'.socket_strerror(socket_last_error());
//         } else {
//             echo 'client write success'.PHP_EOL;
//         }
//     }
// }
// socket_close($socket);//工作完毕，关闭套接流











function guid()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid = substr($charid, 0, 8) . $hyphen
                  . substr($charid, 8, 4) . $hyphen
                 . substr($charid, 12, 4) . $hyphen
                 . substr($charid, 16, 4) . $hyphen
                 . substr($charid, 20, 12);
        return $uuid;
    }
}









// //创建一个socket套接流
// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// /****************设置socket连接选项，这两个步骤你可以省略*************/
//  //接收套接流的最大超时时间1秒，后面是微秒单位超时时间，设置为零，表示不管它
// socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
//  //发送套接流的最大超时时间为6秒
// socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
// /****************设置socket连接选项，这两个步骤你可以省略*************/

// //连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
// if (socket_connect($socket, '127.0.0.1', 8888) == false) {
//     echo 'connect fail massege:'.socket_strerror(socket_last_error());
// } else {
//     $message = 'l love you 我爱你 socket';
//     //转为GBK编码，处理乱码问题，这要看你的编码情况而定，每个人的编码都不同
//     $message = mb_convert_encoding($message, 'GBK', 'UTF-8');
//     //向服务端写入字符串信息

//     if (socket_write($socket, $message, strlen($message)) == false) {
//         echo 'fail to write'.socket_strerror(socket_last_error());
//     } else {
//         echo 'client write success'.PHP_EOL;
//         //读取服务端返回来的套接流信息
//         while ($callback = socket_read($socket, 1024)) {
//             echo 'server return message is:'.PHP_EOL.$callback;
//         }
//     }
// }
// socket_close($socket);//工作完毕，关闭套接流




// $protocol = 'tcp';
// $get_prot = getprotobyname($protocol); // C:\Windows\System32\drivers\etc\protocol
// var_dump($get_prot);











// $host = "172.16.233.9"; // 局域网IP
// $port = 8000;
// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n"); // 创建一个Socket
// $connection = socket_connect($socket, $host, $port) or die("Could not connet server\n"); // 连接 +



// if ($socket < 0) {
//     echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
// } else {
//     echo "OK.\n";
// }
// echo "try to conect  '$host' port is  '$port'...\n";
// if ($connection < 0) {
//     echo "socket_connect() failed.\nReason: ($connection) " . socket_strerror($connection) . "\n";
// } else {
//     echo "connect OK\n";
// }

// $count = 1;

// $in = "Ho   blood \r\n";
// $out = '';




// if (!socket_write($socket, $in, strlen($in))) {
//     echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
// } else {
//     do{
//         $count++;
//         // $in = str_replace("first", $count, $in);
//         $in = substr_replace($in, $count, 3,1);
//         $send = "send to server success !\n" . "client has sent :  $in";
//         echo $send;
//         socket_write($socket, $in, strlen($in));
    
//         sleep(1);//等待时间，进行下一次操作。
//     }while($count < 3 );
// }




// do{
//     $count++;
//     // $in = str_replace("first", $count, $in);
//     $in = substr_replace($in, $count, 3,1);
//     $send = "send to server success !\n" . "client has sent :  $in";
//     echo $send;
//     socket_write($socket, $in, strlen($in));

//     sleep(1);//等待时间，进行下一次操作。
// }while($count < 10 );




// while ($out = socket_read($socket, 8192)) {
//     echo "receive server msg success ! \n";
//     echo "receice content is :", $out;
// }
// echo "close SOCKET...\n";
// socket_close($socket);
// echo "close OK\n";

// socket_write($socket, "hello socket") or die("Write failed\n"); // 数据传送 向服务器发送消息


// $send_data = "This data will Send to server! -    -----------------  ";
// // $send_data = "shutdown";

// while ($buffer = @socket_read($socket, 1024, PHP_NORMAL_READ)) {
//     if (preg_match("/not connect/",$buffer)) {
//         echo "don`t connect\n";
//         break;
//     } else {
//         //服务端传来的信息
//         echo "Buffer Data: " . $buffer . "\n";
//         echo "Writing to Socket\n";
//         // 将客户的信息写到通道中，传给服务器端
//         if (!socket_write($socket, "$send_data\n")) {
//             echo "Write failed\n";
//         }
//         //服务器端收到信息后，客户端接收服务端传给客户端的回应信息。
//         while ($buffer = socket_read($socket, 1024, PHP_NORMAL_READ)) {
//             echo "sent to server:$send_data\n response from server was:" . $buffer . "\n";
//         }

//     }
// }

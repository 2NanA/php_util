<?php

error_reporting(E_ALL);
set_time_limit(0);
// $a = get_defined_constants(TRUE);


// echo "<pre>";
// var_dump($a['sockets']);

// phpinfo(); // port 80
// die;
// foreach ( $a['sockets'] as $constant => $value ) {
//     printf("%-35s %d\r\n", $constant, $value) ;
// }


// error_reporting(E_ALL);
// set_time_limit(0);
// ob_start();
// ob_implicit_flush();
    


/******************** 手册范例 ***************************/

// $address = '172.16.233.9';
// // $address = '172.16.234.911212';
// $port = 8000;
// // telnet 172.16.233.9 8000

// // // example 1  开第二个客户端的时候 收不到response了
// if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
//     echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
// }

// if (socket_bind($sock, $address, $port) === false) {
//     echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
// }

// if (socket_listen($sock, 5) === false) {
//     echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
// }


// $count = 0;
// do {
//     if (($msgsock = socket_accept($sock)) === false) {
//         echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
//         break;
//     }
//     /* Send instructions. */
//     $msg = "\nWelcome to the PHP Test Server. \n" .
//         "To quit, type 'quit'. To shut down the server type 'shutdown'.\n";
//     socket_write($msgsock, $msg, strlen($msg));

//  /**  接收客户端的内容 **/
//     do {

        
//         if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
//             echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
//             break 2;
//         }
//         if (!$buf = trim($buf)) {
//             socket_write($msgsock, "test", strlen("test"));
//             continue;
//         }
//         if ($buf == 'quit') {
//             break;
//         }
//         if ($buf == 'shutdown') {
//             socket_close($msgsock);
//             break 2;
//         }
//         socket_write($msgsock, "haga", strlen("haga"));
//         // socket_recv($msgsock, $buf,20480, 0); // 连续的

//         $talkback = "PHP: You said '$buf'.\n";
//         echo "$buf\n"; // 不会echo
//         socket_write($msgsock, $talkback, strlen($talkback));
//         // echo "$buf\n"; // 不会echo

//         if ($count > 3) {
//             break; // client 只能发送5次
//         };
//         $count++;
//     } while (true);
//     /**  接收客户端的内容 **/

//     socket_close($msgsock);
// } while (true);

// socket_close($sock);



// example 2


// class MySocketServer
// {
//     protected $socket;
//     protected $clients = [];
//     protected $changed;
   
//     function __construct($host = 'localhost', $port = 8000)
//     {
//         set_time_limit(0);
//         $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//         socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//         //bind socket to specified host
//         socket_bind($socket, 0, $port);
//         //listen to port
//         socket_listen($socket);
//         $this->socket = $socket;
//     }
   
//     function __destruct()
//     {
//         foreach($this->clients as $client) {
//             socket_close($client);
//         }
//         socket_close($this->socket);
//     }
   
//     function run()
//     {
//         while(true) {
//             $this->waitForChange();
//             $this->checkNewClients();
//             $this->checkMessageRecieved();
//             $this->checkDisconnect();
//         }
//     }
   
//     function checkDisconnect()
//     {
//         foreach ($this->changed as $changed_socket) {
//             $buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
//             if ($buf !== false) { // check disconnected client
//                 continue;
//             }
//             // remove client for $clients array
//             $found_socket = array_search($changed_socket, $this->clients);
//             socket_getpeername($changed_socket, $ip);
//             unset($this->clients[$found_socket]);
//             $response = 'client ' . $ip . ' has disconnected';
//             $this->sendMessage($response);
//         }
//     }
   
//     function checkMessageRecieved()
//     {
//         foreach ($this->changed as $key => $socket) {
//             $buffer = null;
//             while(socket_recv($socket, $buffer, 1024, 0) >= 1) {
//                 $this->sendMessage(trim($buffer) . PHP_EOL);
//                 unset($this->changed[$key]);
//                 break;
//             }
//         }
//     }
   
//     function waitForChange()
//     {
//         //reset changed
//         $this->changed = array_merge([$this->socket], $this->clients);
//         //variable call time pass by reference req of socket_select
//         $null = null;
//         //this next part is blocking so that we dont run away with cpu
//         socket_select($this->changed, $null, $null, null);
//     }
   
//     function checkNewClients()
//     {
//         if (!in_array($this->socket, $this->changed)) {
//             return; //no new clients
//         }
//         $socket_new = socket_accept($this->socket); //accept new socket
//         $first_line = socket_read($socket_new, 1024);
//         $this->sendMessage('a new client has connected' . PHP_EOL);
//         $this->sendMessage('the new client says ' . trim($first_line) . PHP_EOL);
//         $this->clients[] = $socket_new;
//         unset($this->changed[0]);
//     }
   
   
//     function sendMessage($msg)
//     {
//         foreach($this->clients as $client)
//         {
//             @socket_write($client,$msg,strlen($msg));
//         }
//         return true;
//     }
// }

// (new MySocketServer())->run();
/***********************************************/

// $socket=new socket('127.0.0.1','8000');
// $socket=new socket('172.16.234.9','8000');







// $socket=new socket('172.16.233.9','8000');
// $socket->run();

class socket
{
    protected $hand;
    public $soc;
    public $socs;
    
    public function __construct($address, $port)
    {
        //建立套接字
        $this->soc=$this->createSocket($address, $port);
        $this->socs=array($this->soc);
    }

    //建立套接字
    public function createSocket($address, $port)
    {
        //创建一个套接字
        // TCP用主机的IP地址加上主机上的端口号作为TCP连接的端点，这种端点就叫做套接字（socket）或插口。 也就是 server
        $socket= socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //设置套接字选项
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        //绑定IP地址和端口
        socket_bind($socket, $address, $port);
        //监听套接字
        socket_listen($socket);
        return $socket;
    }

    public function run()
    {
        //挂起进程
        while (true) {
            $arr=$this->socs;
            $write=$except=null;
            //接收套接字数字 监听他们的状态
            socket_select($arr, $write, $except, null);
            //遍历套接字数组
            foreach ($arr as $k=>$v) {
                //如果是新建立的套接字返回一个有效的 套接字资源
                if ($this->soc == $v) {
                    $client=socket_accept($this->soc);
                    if ($client <0) {
                        echo "socket_accept() failed";
                    } else {
                        // array_push($this->socs,$client);
                        // unset($this[]);
                        //将有效的套接字资源放到套接字数组
                        $this->socs[]=$client;
                    }
                } else {
                    //从已连接的socket接收数据  返回的是从socket中接收的字节数
                    $byte=socket_recv($v, $buff, 20480, 0);
                    //如果接收的字节是0
                    if ($byte<7) {
                        continue;
                    }
                    //判断有没有握手没有握手则进行握手,如果握手了 则进行处理
                    if (!$this->hand[(int)$client]) {
                        //进行握手操作
                        $this->hands($client, $buff, $v);
                    } else {
                        //处理数据操作
                        $mess=$this->decodeData($buff);
                        //发送数据
                        $this->send($mess, $v);
                    }
                }
            }
        }
    }
    
    //进行握手 response header
    public function hands($client, $buff, $v)
    {
        //提取websocket传的key并进行加密  （这是固定的握手机制获取Sec-WebSocket-Key:里面的key）
        $buf  = substr($buff, strpos($buff, 'Sec-WebSocket-Key:')+18);
        //去除换行空格字符
        $key  = trim(substr($buf, 0, strpos($buf, "\r\n")));
        //固定的加密算法
        $new_key = base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
        // $new_key = base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B119das9",true)); // 改了加密key 就连不上了
        $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $new_message .= "Upgrade: websocket\r\n";
        $new_message .= "Sec-WebSocket-Version: 13\r\n";
        $new_message .= "Connection: Upgrade\r\n";
        $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
        //将套接字写入缓冲区
        socket_write($v, $new_message, strlen($new_message));
        // socket_write(socket,$upgrade.chr(0), strlen($upgrade.chr(0)));
        //标记此套接字握手成功
        $this->hand[(int)$client]=true;
    }
    
    //解析数据
    public function decodeData($buff)
    {
        //$buff  解析数据帧
        $mask = array();
        $data = '';
        $msg = unpack('H*', $buff);  //用unpack函数从二进制将数据解码
        $head = substr($msg[1], 0, 2);
        if (hexdec($head{1}) === 8) {
            $data = false;
        } elseif (hexdec($head{1}) === 1) {
            $mask[] = hexdec(substr($msg[1], 4, 2));
            $mask[] = hexdec(substr($msg[1], 6, 2));
            $mask[] = hexdec(substr($msg[1], 8, 2));
            $mask[] = hexdec(substr($msg[1], 10, 2));
            //遇到的问题  刚连接的时候就发送数据  显示 state connecting
            $s = 12;
            $e = strlen($msg[1])-2;
            $n = 0;
            for ($i=$s; $i<= $e; $i+= 2) {
                $data .= chr($mask[$n%4]^hexdec(substr($msg[1], $i, 2)));
                $n++;
            }
            //发送数据到客户端
            //如果长度大于125 将数据分块
            $block=str_split($data, 125);
            $mess=array(
                'mess'=>$block[0],
                );
            return $mess;
        }
    }
    //发送数据
    public function send($mess, $v)
    {
        //遍历套接字数组 成功握手的  进行数据群发
        foreach ($this->socs as $keys => $values) {
            //用系统分配的套接字资源id作为用户昵称
            $mess['name']="Tourist's socket: ### {$v} ### "; // $v Resource id #6
            $str=json_encode($mess);
            $writes ="\x81".chr(strlen($str)).$str;
            // ob_flush();
            // flush();
            // sleep(3);
            if ($this->hand[(int)$values]) {
                socket_write($values, $writes, strlen($writes));
            }
        }
    }
}

# step 1
//创建服务端的socket套接流,net协议为IPv4，protocol协议为TCP
// echo "2";
// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

//     # step 2
//     /*绑定接收的套接流主机和端口,与客户端相对应*/
//     if (socket_bind($socket, '127.0.0.1', 8000) == false) { // 一直阻塞
//         echo 'server bind fail:'.socket_strerror(socket_last_error());
//         /*这里的127.0.0.1是在本地主机测试，你如果有多台电脑，可以写IP地址*/
//         // die;
//     }

//     # step 3
//     //监听套接流
//     if (socket_listen($socket, 4)==false) {
//         echo 'server listen fail:'.socket_strerror(socket_last_error());
//     }

// //让服务器无限获取客户端传过来的信息
//     do {
//         /*接收客户端传过来的信息*/
//         $accept_resource = socket_accept($socket);
//         /*socket_accept的作用就是接受socket_bind()所绑定的主机发过来的套接流*/

//         if ($accept_resource !== false) {
//             /*读取客户端传过来的资源，并转化为字符串*/
//             $string = socket_read($accept_resource, 1024);
//             /*socket_read的作用就是读出socket_accept()的资源并把它转化为字符串*/

//             echo 'server receive is :'.$string.PHP_EOL;//PHP_EOL为php的换行预定义常量
//             if ($string != false) {
//                 $return_client = 'server receive is : '.$string.PHP_EOL;
//                 /*向socket_accept的套接流写入信息，也就是回馈信息给socket_bind()所绑定的主机客户端*/
//                 socket_write($accept_resource, $return_client, strlen($return_client));
//             /*socket_write的作用是向socket_create的套接流写入信息，或者向socket_accept的套接流写入信息*/
//             } else {
//                 echo 'socket_read is fail';
//             }
//             /*socket_close的作用是关闭socket_create()或者socket_accept()所建立的套接流*/
//             socket_close($accept_resource);
//         }
//     } while (true);
//     echo "end";
// socket_close($socket);



#### 不能多个客户端一起使用

# 公共代码
$sock = socket_create_listen(0);
socket_getsockname($sock, $addr, $port);
print "Server Listening on $addr:$port\n";

$fp = fopen("E:\phpenv\www\guzzlestream.txt", 'w');
fwrite($fp, $port);
fclose($fp);

if (FALSE === $sock)
{
    $errcode = socket_last_error();
    fwrite(STDERR, "socket create fail: " . socket_strerror($errcode));
    exit(-1);
}
 
// var_dump($addr);
// var_dump($port);
// if (!socket_bind($sock, $addr, $port))    // 绑定ip地址及端口 socket_create_listen 不能绑定随机地址
// {
//     $errcode = socket_last_error();
//     fwrite(STDERR, "socket bind fail: " . socket_strerror($errcode));
//     exit(-1);
// }
 
if (!socket_listen($sock, 128))      // 允许多少个客户端来排队连接
{
    $errcode = socket_last_error();
    fwrite(STDERR, "socket listen fail: " . socket_strerror($errcode));
    exit(-1);
}
// socket_set_nonblock($sock); // 如果设置非阻塞的话， 客户端发送数据过来 就会一直运行程序
// socket_set_block($sock);
# 公共代码
 


// while ($c = socket_accept($sock)) {  // 这是关键的一步  这么写只能读取一次数据
//     socket_getpeername($c, $raddr, $rport);
//     $string = socket_read($c, 1024);
//     if ($string != false) {
//         $str_send_client = guid() . ' server send';
//         socket_write($c, $str_send_client, strlen($str_send_client)); 
//     }
//     print "Received Connection from $raddr:$rport and receive data : {$string} \n";
// }

// $c = socket_accept($sock);
// socket_getpeername($c, $raddr, $rport);
// if($c !== false){
//     while (true) {
//     	$string = socket_read($c,1024);
//         $str_send_client = guid() . ' server send';
//         socket_write($c, $str_send_client, strlen($str_send_client));
//         print "Received Connection from $raddr:$rport and receive data : {$string} \n";
//     }
// }
// socket_close($sock);





#######  支持并发的 socket server

/* 要监听的三个sockets数组 */
$read_socks = array();
$write_socks = array();
$except_socks = NULL;  // 注意 php 不支持直接将NULL作为引用传参，所以这里定义一个变量
 
$read_socks[] = $sock;

// echo 'zusai';

while (1) {
    /* 这两个数组会被改变，所以用两个临时变量 */
    $tmp_reads = $read_socks;
    $tmp_writes = $write_socks;
 
    // int socket_select ( array &$read , array &$write , array &$except , int $tv_sec [, int $tv_usec = 0 ] )
    // timeout 传 NULL 会一直阻塞直到有结果返回  当没有套字节可以读写继续等待， 第四个参数为null为阻塞， 为0位非阻塞， 为 >0 为等待时间

    // echo 'zusai';
    $count = socket_select($tmp_reads, $tmp_writes, $except_socks, null);  
    // echo 'zusai'; // 被上一句阻塞了
    // var_dump('count : ' . $count);

    // echo 'tmp_reads : ';
    // var_dump($tmp_reads);

    // echo 'tmp_writes : ';
    // var_dump($tmp_writes);

    // echo 'except_socks : ';
    // var_dump($except_socks);

    //   连上一个客户端的时候
    // string(9) "count : 1"
    // tmp_reads : array(0) {
    // }
    // tmp_writes : array(1) {
    //   [0]=>
    //   resource(7) of type (Socket)
    // }
    // except_socks : NULL
    #   连上2个客户端的时候
    // string(9) "count : 2"
    // tmp_reads : array(0) {
    // }
    // tmp_writes : array(2) {
    // [0]=>
    // resource(7) of type (Socket)
    // [1]=>
    // resource(8) of type (Socket)
    // }
    // except_socks : NULL



    foreach ($tmp_reads as $read) {
        // var_dump("fafa");
        // var_dump($tmp_reads); // 这个数组只会有一个client 连接，只有在读取的时候才有值，读取完立即清空这个数组
        
        if ($read == $sock) {
            // 这个是server socket resource 这个if 只有在有新连接进来的时候才会执行
            // var_dump("read");
            // var_dump($read); 
            // var_dump("sock");
            // var_dump($sock); 
            /* 有新的客户端连接请求 */
            $connsock = socket_accept($sock);  //响应客户端连接， 此时不会造成阻塞
            if ($connsock) {
                socket_getpeername($connsock, $raddr, $rport);  //获取远程客户端ip地址和端口
                echo "new client connect server: ip = $raddr, port = $rport" . PHP_EOL;
 
                // 把新的连接sokcet加入监听
                $read_socks[] = $connsock;
                $write_socks[] = $connsock;
            }
            // var_dump("read_socks");
            // var_dump($read_socks); 
            // var_dump("write_socks");
            // var_dump($write_socks); 
        } else {
            /* 客户端传输数据 */
            $data = socket_read($read, 1024);  //从客户端读取数据, 此时一定会读到数组而不会产生阻塞
            // socket_recv($read, $data, 1, MSG_WAITALL); // 没有发送到 8192 字符给阻塞住了

            if ($data === '') {
                //移除对该 socket 监听
                foreach ($read_socks as $key => $val) {
                    if ($val == $read) unset($read_socks[$key]); // 读取完立即清空这个数组
                }
 
                foreach ($write_socks as $key => $val) {
                    if ($val == $read) unset($write_socks[$key]); // 回复完立即清空这个数组
                }
 
                socket_close($read);
                echo "client close" . PHP_EOL;
 
            } else {
                socket_getpeername($read, $raddr, $rport);  //获取远程客户端ip地址和端口
 
                // echo "read from client # $addr:$port # " . $data;
                echo "read data from client --- $raddr:$rport : {$data} \n";

                // $responseContent = 'ok, you got me ' . get_between($data, '^', '$') . " \n";
                // $response = "HTTP/1.1 200 OK\r\n";
                // $response .= "Server: lzk-self-made-server\r\n";
                // $response .= "Content-Type: text/html\r\n";
                // $response .= "Content-Length: " . strlen($responseContent) . "\r\n\r\n";
                // $response .= $responseContent;
                // // 简易的http server    
                // if (in_array($read, $tmp_writes)) {
                //     //如果该客户端可写 把数据回写给客户端
                //     socket_write($read, $response);
                //     socket_close($read);  // 主动关闭客户端连接  http 链接不是长连接
                //     //移除对该 socket 监听
                //     foreach ($read_socks as $key => $val) {
                //         if ($val == $read) unset($read_socks[$key]);
                //     }
 
                //     foreach ($write_socks as $key => $val) {
                //         if ($val == $read) unset($write_socks[$key]);
                //     }
                // }

 
                // #  $data = strtoupper($data);  //小写转大写
                if (in_array($read, $tmp_writes)) {
                    //如果该客户端可写 把数据回写给客户端
                    // socket_write($read, $data);
                    $str_send_client = guid() . ' server send';
                    socket_write($read, $str_send_client, strlen($str_send_client));
                }
            }
        }
    }
}
socket_close($sock);








//创建服务端的socket套接流,net协议为IPv4，protocol协议为TCP
// $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
// /*绑定接收的套接流主机和端口,与客户端相对应*/
// if(socket_bind($socket,'127.0.0.1',8000) == false){
//     echo 'server bind fail:'.socket_strerror(socket_last_error());
//     /*这里的127.0.0.1是在本地主机测试，你如果有多台电脑，可以写IP地址*/
// }
// //监听套接流
// if(socket_listen($socket,4)==false){
//     echo 'server listen fail:'.socket_strerror(socket_last_error());
// }
// //让服务器无限获取客户端传过来的信息
// /*接收客户端传过来的信息*/
// $accept_resource = socket_accept($socket);
// /*socket_accept的作用就是接受socket_bind()所绑定的主机发过来的套接流*/
// if($accept_resource !== false){
//     /*读取客户端传过来的资源，并转化为字符串*/
//     echo("connect success".PHP_EOL);
//     while (true) {
//     	$string = socket_read($accept_resource,1024);
//         /*socket_read的作用就是读出socket_accept()的资源并把它转化为字符串*/
//         // echo 'server receive is :'.$string.PHP_EOL;

//         $str_send_client = guid() . ' server send';
//         socket_write($accept_resource, $str_send_client, strlen($str_send_client)); 

//         print "Received Connection from and receive data : {$string} \n";
//     }
//     /*socket_close的作用是关闭socket_create()或者socket_accept()所建立的套接流*/
//     //socket_close($accept_resource);
// }
// socket_close($socket);







function get_between($input, $start, $end) 
{
    $substr = substr($input, strlen($start)+strpos($input, $start),(strlen($input) - strpos($input, $end))*(-1));  
    return $substr; 
}

function guid()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid   = substr($charid, 0, 8).$hyphen
                 .substr($charid, 8, 4).$hyphen
                 .substr($charid, 12, 4).$hyphen
                 .substr($charid, 16, 4).$hyphen
                 .substr($charid, 20, 12);
        return $uuid;
    }
}

// var_dump(strlen('fafa'));
// var_dump(strlen('刘子康'));
<?php

error_reporting(E_ALL);
set_time_limit(0);
// $a = get_defined_constants(TRUE);


// echo "<pre>";
// var_dump($a['sockets']);

// phpinfo(); // port 80
// die;
// foreach ( $a['sockets'] as $constant => $value ) {
//     printf("%-35s %d\r\n", $constant, $value) ;
// }


// error_reporting(E_ALL);
// set_time_limit(0);
// ob_start();
// ob_implicit_flush();
    


/******************** 手册范例 ***************************/

// $address = '172.16.233.9';
// // $address = '172.16.234.911212';
// $port = 8000;
// // telnet 172.16.233.9 8000

// // // example 1  开第二个客户端的时候 收不到response了
// if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
//     echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
// }

// if (socket_bind($sock, $address, $port) === false) {
//     echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
// }

// if (socket_listen($sock, 5) === false) {
//     echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
// }


// $count = 0;
// do {
//     if (($msgsock = socket_accept($sock)) === false) {
//         echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
//         break;
//     }
//     /* Send instructions. */
//     $msg = "\nWelcome to the PHP Test Server. \n" .
//         "To quit, type 'quit'. To shut down the server type 'shutdown'.\n";
//     socket_write($msgsock, $msg, strlen($msg));

//  /**  接收客户端的内容 **/
//     do {

        
//         if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
//             echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
//             break 2;
//         }
//         if (!$buf = trim($buf)) {
//             socket_write($msgsock, "test", strlen("test"));
//             continue;
//         }
//         if ($buf == 'quit') {
//             break;
//         }
//         if ($buf == 'shutdown') {
//             socket_close($msgsock);
//             break 2;
//         }
//         socket_write($msgsock, "haga", strlen("haga"));
//         // socket_recv($msgsock, $buf,20480, 0); // 连续的

//         $talkback = "PHP: You said '$buf'.\n";
//         echo "$buf\n"; // 不会echo
//         socket_write($msgsock, $talkback, strlen($talkback));
//         // echo "$buf\n"; // 不会echo

//         if ($count > 3) {
//             break; // client 只能发送5次
//         };
//         $count++;
//     } while (true);
//     /**  接收客户端的内容 **/

//     socket_close($msgsock);
// } while (true);

// socket_close($sock);



// example 2


// class MySocketServer
// {
//     protected $socket;
//     protected $clients = [];
//     protected $changed;
   
//     function __construct($host = 'localhost', $port = 8000)
//     {
//         set_time_limit(0);
//         $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//         socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//         //bind socket to specified host
//         socket_bind($socket, 0, $port);
//         //listen to port
//         socket_listen($socket);
//         $this->socket = $socket;
//     }
   
//     function __destruct()
//     {
//         foreach($this->clients as $client) {
//             socket_close($client);
//         }
//         socket_close($this->socket);
//     }
   
//     function run()
//     {
//         while(true) {
//             $this->waitForChange();
//             $this->checkNewClients();
//             $this->checkMessageRecieved();
//             $this->checkDisconnect();
//         }
//     }
   
//     function checkDisconnect()
//     {
//         foreach ($this->changed as $changed_socket) {
//             $buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
//             if ($buf !== false) { // check disconnected client
//                 continue;
//             }
//             // remove client for $clients array
//             $found_socket = array_search($changed_socket, $this->clients);
//             socket_getpeername($changed_socket, $ip);
//             unset($this->clients[$found_socket]);
//             $response = 'client ' . $ip . ' has disconnected';
//             $this->sendMessage($response);
//         }
//     }
   
//     function checkMessageRecieved()
//     {
//         foreach ($this->changed as $key => $socket) {
//             $buffer = null;
//             while(socket_recv($socket, $buffer, 1024, 0) >= 1) {
//                 $this->sendMessage(trim($buffer) . PHP_EOL);
//                 unset($this->changed[$key]);
//                 break;
//             }
//         }
//     }
   
//     function waitForChange()
//     {
//         //reset changed
//         $this->changed = array_merge([$this->socket], $this->clients);
//         //variable call time pass by reference req of socket_select
//         $null = null;
//         //this next part is blocking so that we dont run away with cpu
//         socket_select($this->changed, $null, $null, null);
//     }
   
//     function checkNewClients()
//     {
//         if (!in_array($this->socket, $this->changed)) {
//             return; //no new clients
//         }
//         $socket_new = socket_accept($this->socket); //accept new socket
//         $first_line = socket_read($socket_new, 1024);
//         $this->sendMessage('a new client has connected' . PHP_EOL);
//         $this->sendMessage('the new client says ' . trim($first_line) . PHP_EOL);
//         $this->clients[] = $socket_new;
//         unset($this->changed[0]);
//     }
   
   
//     function sendMessage($msg)
//     {
//         foreach($this->clients as $client)
//         {
//             @socket_write($client,$msg,strlen($msg));
//         }
//         return true;
//     }
// }

// (new MySocketServer())->run();
/***********************************************/

// $socket=new socket('127.0.0.1','8000');
// $socket=new socket('172.16.234.9','8000');







// $socket=new socket('172.16.233.9','8000');
// $socket->run();

class socket
{
    protected $hand;
    public $soc;
    public $socs;
    
    public function __construct($address, $port)
    {
        //建立套接字
        $this->soc=$this->createSocket($address, $port);
        $this->socs=array($this->soc);
    }

    //建立套接字
    public function createSocket($address, $port)
    {
        //创建一个套接字
        // TCP用主机的IP地址加上主机上的端口号作为TCP连接的端点，这种端点就叫做套接字（socket）或插口。 也就是 server
        $socket= socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //设置套接字选项
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        //绑定IP地址和端口
        socket_bind($socket, $address, $port);
        //监听套接字
        socket_listen($socket);
        return $socket;
    }

    public function run()
    {
        //挂起进程
        while (true) {
            $arr=$this->socs;
            $write=$except=null;
            //接收套接字数字 监听他们的状态
            socket_select($arr, $write, $except, null);
            //遍历套接字数组
            foreach ($arr as $k=>$v) {
                //如果是新建立的套接字返回一个有效的 套接字资源
                if ($this->soc == $v) {
                    $client=socket_accept($this->soc);
                    if ($client <0) {
                        echo "socket_accept() failed";
                    } else {
                        // array_push($this->socs,$client);
                        // unset($this[]);
                        //将有效的套接字资源放到套接字数组
                        $this->socs[]=$client;
                    }
                } else {
                    //从已连接的socket接收数据  返回的是从socket中接收的字节数
                    $byte=socket_recv($v, $buff, 20480, 0);
                    //如果接收的字节是0
                    if ($byte<7) {
                        continue;
                    }
                    //判断有没有握手没有握手则进行握手,如果握手了 则进行处理
                    if (!$this->hand[(int)$client]) {
                        //进行握手操作
                        $this->hands($client, $buff, $v);
                    } else {
                        //处理数据操作
                        $mess=$this->decodeData($buff);
                        //发送数据
                        $this->send($mess, $v);
                    }
                }
            }
        }
    }
    
    //进行握手 response header
    public function hands($client, $buff, $v)
    {
        //提取websocket传的key并进行加密  （这是固定的握手机制获取Sec-WebSocket-Key:里面的key）
        $buf  = substr($buff, strpos($buff, 'Sec-WebSocket-Key:')+18);
        //去除换行空格字符
        $key  = trim(substr($buf, 0, strpos($buf, "\r\n")));
        //固定的加密算法
        $new_key = base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
        // $new_key = base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B119das9",true)); // 改了加密key 就连不上了
        $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $new_message .= "Upgrade: websocket\r\n";
        $new_message .= "Sec-WebSocket-Version: 13\r\n";
        $new_message .= "Connection: Upgrade\r\n";
        $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
        //将套接字写入缓冲区
        socket_write($v, $new_message, strlen($new_message));
        // socket_write(socket,$upgrade.chr(0), strlen($upgrade.chr(0)));
        //标记此套接字握手成功
        $this->hand[(int)$client]=true;
    }
    
    //解析数据
    public function decodeData($buff)
    {
        //$buff  解析数据帧
        $mask = array();
        $data = '';
        $msg = unpack('H*', $buff);  //用unpack函数从二进制将数据解码
        $head = substr($msg[1], 0, 2);
        if (hexdec($head{1}) === 8) {
            $data = false;
        } elseif (hexdec($head{1}) === 1) {
            $mask[] = hexdec(substr($msg[1], 4, 2));
            $mask[] = hexdec(substr($msg[1], 6, 2));
            $mask[] = hexdec(substr($msg[1], 8, 2));
            $mask[] = hexdec(substr($msg[1], 10, 2));
            //遇到的问题  刚连接的时候就发送数据  显示 state connecting
            $s = 12;
            $e = strlen($msg[1])-2;
            $n = 0;
            for ($i=$s; $i<= $e; $i+= 2) {
                $data .= chr($mask[$n%4]^hexdec(substr($msg[1], $i, 2)));
                $n++;
            }
            //发送数据到客户端
            //如果长度大于125 将数据分块
            $block=str_split($data, 125);
            $mess=array(
                'mess'=>$block[0],
                );
            return $mess;
        }
    }
    //发送数据
    public function send($mess, $v)
    {
        //遍历套接字数组 成功握手的  进行数据群发
        foreach ($this->socs as $keys => $values) {
            //用系统分配的套接字资源id作为用户昵称
            $mess['name']="Tourist's socket: ### {$v} ### "; // $v Resource id #6
            $str=json_encode($mess);
            $writes ="\x81".chr(strlen($str)).$str;
            // ob_flush();
            // flush();
            // sleep(3);
            if ($this->hand[(int)$values]) {
                socket_write($values, $writes, strlen($writes));
            }
        }
    }
}

# step 1
//创建服务端的socket套接流,net协议为IPv4，protocol协议为TCP
// echo "2";
// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

//     # step 2
//     /*绑定接收的套接流主机和端口,与客户端相对应*/
//     if (socket_bind($socket, '127.0.0.1', 8000) == false) { // 一直阻塞
//         echo 'server bind fail:'.socket_strerror(socket_last_error());
//         /*这里的127.0.0.1是在本地主机测试，你如果有多台电脑，可以写IP地址*/
//         // die;
//     }

//     # step 3
//     //监听套接流
//     if (socket_listen($socket, 4)==false) {
//         echo 'server listen fail:'.socket_strerror(socket_last_error());
//     }

// //让服务器无限获取客户端传过来的信息
//     do {
//         /*接收客户端传过来的信息*/
//         $accept_resource = socket_accept($socket);
//         /*socket_accept的作用就是接受socket_bind()所绑定的主机发过来的套接流*/

//         if ($accept_resource !== false) {
//             /*读取客户端传过来的资源，并转化为字符串*/
//             $string = socket_read($accept_resource, 1024);
//             /*socket_read的作用就是读出socket_accept()的资源并把它转化为字符串*/

//             echo 'server receive is :'.$string.PHP_EOL;//PHP_EOL为php的换行预定义常量
//             if ($string != false) {
//                 $return_client = 'server receive is : '.$string.PHP_EOL;
//                 /*向socket_accept的套接流写入信息，也就是回馈信息给socket_bind()所绑定的主机客户端*/
//                 socket_write($accept_resource, $return_client, strlen($return_client));
//             /*socket_write的作用是向socket_create的套接流写入信息，或者向socket_accept的套接流写入信息*/
//             } else {
//                 echo 'socket_read is fail';
//             }
//             /*socket_close的作用是关闭socket_create()或者socket_accept()所建立的套接流*/
//             socket_close($accept_resource);
//         }
//     } while (true);
//     echo "end";
// socket_close($socket);



#### 不能多个客户端一起使用

# 公共代码
$sock = socket_create_listen(0);
socket_getsockname($sock, $addr, $port);
print "Server Listening on $addr:$port\n";

$fp = fopen("E:\phpenv\www\guzzlestream.txt", 'w');
fwrite($fp, $port);
fclose($fp);

if (FALSE === $sock)
{
    $errcode = socket_last_error();
    fwrite(STDERR, "socket create fail: " . socket_strerror($errcode));
    exit(-1);
}
 
// var_dump($addr);
// var_dump($port);
// if (!socket_bind($sock, $addr, $port))    // 绑定ip地址及端口 socket_create_listen 不能绑定随机地址
// {
//     $errcode = socket_last_error();
//     fwrite(STDERR, "socket bind fail: " . socket_strerror($errcode));
//     exit(-1);
// }
 
if (!socket_listen($sock, 128))      // 允许多少个客户端来排队连接
{
    $errcode = socket_last_error();
    fwrite(STDERR, "socket listen fail: " . socket_strerror($errcode));
    exit(-1);
}
// socket_set_nonblock($sock); // 如果设置非阻塞的话， 客户端发送数据过来 就会一直运行程序
// socket_set_block($sock);
# 公共代码
 


// while ($c = socket_accept($sock)) {  // 这是关键的一步  这么写只能读取一次数据
//     socket_getpeername($c, $raddr, $rport);
//     $string = socket_read($c, 1024);
//     if ($string != false) {
//         $str_send_client = guid() . ' server send';
//         socket_write($c, $str_send_client, strlen($str_send_client)); 
//     }
//     print "Received Connection from $raddr:$rport and receive data : {$string} \n";
// }

// $c = socket_accept($sock);
// socket_getpeername($c, $raddr, $rport);
// if($c !== false){
//     while (true) {
//     	$string = socket_read($c,1024);
//         $str_send_client = guid() . ' server send';
//         socket_write($c, $str_send_client, strlen($str_send_client));
//         print "Received Connection from $raddr:$rport and receive data : {$string} \n";
//     }
// }
// socket_close($sock);





#######  支持并发的 socket server

/* 要监听的三个sockets数组 */
$read_socks = array();
$write_socks = array();
$except_socks = NULL;  // 注意 php 不支持直接将NULL作为引用传参，所以这里定义一个变量
 
$read_socks[] = $sock;

// echo 'zusai';

while (1) {
    /* 这两个数组会被改变，所以用两个临时变量 */
    $tmp_reads = $read_socks;
    $tmp_writes = $write_socks;
 
    // int socket_select ( array &$read , array &$write , array &$except , int $tv_sec [, int $tv_usec = 0 ] )
    // timeout 传 NULL 会一直阻塞直到有结果返回  当没有套字节可以读写继续等待， 第四个参数为null为阻塞， 为0位非阻塞， 为 >0 为等待时间

    // echo 'zusai';
    $count = socket_select($tmp_reads, $tmp_writes, $except_socks, null);  
    // echo 'zusai'; // 被上一句阻塞了
    // var_dump('count : ' . $count);

    // echo 'tmp_reads : ';
    // var_dump($tmp_reads);

    // echo 'tmp_writes : ';
    // var_dump($tmp_writes);

    // echo 'except_socks : ';
    // var_dump($except_socks);

    //   连上一个客户端的时候
    // string(9) "count : 1"
    // tmp_reads : array(0) {
    // }
    // tmp_writes : array(1) {
    //   [0]=>
    //   resource(7) of type (Socket)
    // }
    // except_socks : NULL
    #   连上2个客户端的时候
    // string(9) "count : 2"
    // tmp_reads : array(0) {
    // }
    // tmp_writes : array(2) {
    // [0]=>
    // resource(7) of type (Socket)
    // [1]=>
    // resource(8) of type (Socket)
    // }
    // except_socks : NULL



    foreach ($tmp_reads as $read) {
        // var_dump("fafa");
        // var_dump($tmp_reads); // 这个数组只会有一个client 连接，只有在读取的时候才有值，读取完立即清空这个数组
        
        if ($read == $sock) {
            // 这个是server socket resource 这个if 只有在有新连接进来的时候才会执行
            // var_dump("read");
            // var_dump($read); 
            // var_dump("sock");
            // var_dump($sock); 
            /* 有新的客户端连接请求 */
            $connsock = socket_accept($sock);  //响应客户端连接， 此时不会造成阻塞
            if ($connsock) {
                socket_getpeername($connsock, $raddr, $rport);  //获取远程客户端ip地址和端口
                echo "new client connect server: ip = $raddr, port = $rport" . PHP_EOL;
 
                // 把新的连接sokcet加入监听
                $read_socks[] = $connsock;
                $write_socks[] = $connsock;
            }
            // var_dump("read_socks");
            // var_dump($read_socks); 
            // var_dump("write_socks");
            // var_dump($write_socks); 
        } else {
            /* 客户端传输数据 */
            $data = socket_read($read, 1024);  //从客户端读取数据, 此时一定会读到数组而不会产生阻塞
            // socket_recv($read, $data, 1, MSG_WAITALL); // 没有发送到 8192 字符给阻塞住了

            if ($data === '') {
                //移除对该 socket 监听
                foreach ($read_socks as $key => $val) {
                    if ($val == $read) unset($read_socks[$key]); // 读取完立即清空这个数组
                }
 
                foreach ($write_socks as $key => $val) {
                    if ($val == $read) unset($write_socks[$key]); // 回复完立即清空这个数组
                }
 
                socket_close($read);
                echo "client close" . PHP_EOL;
 
            } else {
                socket_getpeername($read, $raddr, $rport);  //获取远程客户端ip地址和端口
 
                // echo "read from client # $addr:$port # " . $data;
                echo "read data from client --- $raddr:$rport : {$data} \n";

                // $responseContent = 'ok, you got me ' . get_between($data, '^', '$') . " \n";
                // $response = "HTTP/1.1 200 OK\r\n";
                // $response .= "Server: lzk-self-made-server\r\n";
                // $response .= "Content-Type: text/html\r\n";
                // $response .= "Content-Length: " . strlen($responseContent) . "\r\n\r\n";
                // $response .= $responseContent;
                // // 简易的http server    
                // if (in_array($read, $tmp_writes)) {
                //     //如果该客户端可写 把数据回写给客户端
                //     socket_write($read, $response);
                //     socket_close($read);  // 主动关闭客户端连接  http 链接不是长连接
                //     //移除对该 socket 监听
                //     foreach ($read_socks as $key => $val) {
                //         if ($val == $read) unset($read_socks[$key]);
                //     }
 
                //     foreach ($write_socks as $key => $val) {
                //         if ($val == $read) unset($write_socks[$key]);
                //     }
                // }

 
                // #  $data = strtoupper($data);  //小写转大写
                if (in_array($read, $tmp_writes)) {
                    //如果该客户端可写 把数据回写给客户端
                    // socket_write($read, $data);
                    $str_send_client = guid() . ' server send';
                    socket_write($read, $str_send_client, strlen($str_send_client));
                }
            }
        }
    }
}
socket_close($sock);








//创建服务端的socket套接流,net协议为IPv4，protocol协议为TCP
// $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
// /*绑定接收的套接流主机和端口,与客户端相对应*/
// if(socket_bind($socket,'127.0.0.1',8000) == false){
//     echo 'server bind fail:'.socket_strerror(socket_last_error());
//     /*这里的127.0.0.1是在本地主机测试，你如果有多台电脑，可以写IP地址*/
// }
// //监听套接流
// if(socket_listen($socket,4)==false){
//     echo 'server listen fail:'.socket_strerror(socket_last_error());
// }
// //让服务器无限获取客户端传过来的信息
// /*接收客户端传过来的信息*/
// $accept_resource = socket_accept($socket);
// /*socket_accept的作用就是接受socket_bind()所绑定的主机发过来的套接流*/
// if($accept_resource !== false){
//     /*读取客户端传过来的资源，并转化为字符串*/
//     echo("connect success".PHP_EOL);
//     while (true) {
//     	$string = socket_read($accept_resource,1024);
//         /*socket_read的作用就是读出socket_accept()的资源并把它转化为字符串*/
//         // echo 'server receive is :'.$string.PHP_EOL;

//         $str_send_client = guid() . ' server send';
//         socket_write($accept_resource, $str_send_client, strlen($str_send_client)); 

//         print "Received Connection from and receive data : {$string} \n";
//     }
//     /*socket_close的作用是关闭socket_create()或者socket_accept()所建立的套接流*/
//     //socket_close($accept_resource);
// }
// socket_close($socket);







function get_between($input, $start, $end) 
{
    $substr = substr($input, strlen($start)+strpos($input, $start),(strlen($input) - strpos($input, $end))*(-1));  
    return $substr; 
}

function guid()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid   = substr($charid, 0, 8).$hyphen
                 .substr($charid, 8, 4).$hyphen
                 .substr($charid, 12, 4).$hyphen
                 .substr($charid, 16, 4).$hyphen
                 .substr($charid, 20, 12);
        return $uuid;
    }
}

// var_dump(strlen('fafa'));
// var_dump(strlen('刘子康'));

<?php
error_reporting(E_ALL);
set_time_limit(0);

// $web_root = dirname(__FILE__);
// require_once $web_root . '/layout/header.php';

// $client = new Swoole\Client(SWOOLE_SOCK_TCP);
// if (!$client->connect('127.0.0.1', 9501, -1))
// {
//     exit("connect failed. Error: {$client->errCode}\n");
// }
// $client->send("hello world\n");
// echo $client->recv();
// $client->close();


# 异步的TCP client
// $client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

// // Register the function for the event `connect`
// $client->on("connect", function($client){
// 	$client->send("Async TCP Client");
// });

// // Register the function for the event `receive`
// $client->on("receive", function($client, $data){
// 	echo "Received :" . $data . "\n";
// });

// // Register the function for the event `error`
// $client->on("error", function($client){
// 	echo "Connect failed";
// });

// // Register the function for the event `close`
// $client->on("close", function($client){
// 	echo "Connection close\n";
// });

// // // Start to connect to the server
// $client->connect("127.0.0.1", 9501, 0.5);







# 同步的TCP client
// $client = new swoole_client(SWOOLE_SOCK_TCP);

// // Connect to the tcp server
// if(!$client->connect('127.0.0.1', 9501, 0.5))
// {
//     die("connect failed");
// }

// // Send data to the tcp server
// if(!$client->send("Hello World"))
// {
//     die("send failed");
// }

// // Receive data from the tcp server
// $data = $client->recv();
// if(!$data)
// {
//     die("recv failed");
// }
// echo $data;

// // Close the connection
// $client->close();




// class Client
// {
//     private $client;

//     public function __construct()
//     {
//         $this->client = new swoole_client(SWOOLE_SOCK_TCP);
//     }
    
//     public function connect()
//     {
//         if (!$this->client->connect("127.0.0.1", 9501, 1)) {
//             echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
//         }
//         $message = $this->client->recv();
//         echo "Get Message From Server:{$message}\n";

//         fwrite(STDOUT, "Send to Server: ");
//         $msg = trim(fgets(STDIN));
//         $this->client->send($msg);
//     }
// }

// $client = new Client();
// $client->connect();

class Client
{
    private $client;
    private $i = 0;
    private $time;
    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $this->client->on('Connect', array($this, 'onConnect'));
        $this->client->on('Receive', array($this, 'onReceive'));
        $this->client->on('Close', array($this, 'onClose'));
        $this->client->on('Error', array($this, 'onError'));
    }
    public function connect()
    {
        $fp = $this->client->connect("127.0.0.1", 9501, 1);
        if (!$fp) {
            echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
            return;
        }
    }
    public function onReceive($cli, $data)
    {
        $this->i ++;
        if ($this->i >= 1000) { // Use Time: 74.772 second
            $t1 = microtime(true);
            echo "Use Time: " . (round($t1-$this->time, 3)) . " second \n";
            exit(0);
        } else {
            $cli->send("client data Get {$this->i} \n"); // 循环发送
        }
    }
    public function onConnect($cli)
    {
        $cli->send("I'm connect "); // 发送一次
        $this->time = microtime(true);
        ;
    }
    public function onClose($cli)
    {
        echo "Client close connection\n";
    }
    public function onError()
    {
    }
    public function send($data)
    {
        $this->client->send($data);
    }
    public function isConnected()
    {
        return $this->client->isConnected();
    }
}
// $cli = new Client();
// $cli->connect();


$cli = new Client();
$cli->connect();


die;













// class MySQLPool
// {
//         public function __construct() {
//                 $this->serv = new swoole_server("0.0.0.0", 9501);
//         $this->serv->set(array(
//             'worker_num' => 4,
//             'daemonize' => false,
//             'max_request' => 10000,
//             'dispatch_mode' => 3,//这个模式下无法收到onclose这样的事件
//             'debug_mode'=> 1 ,
//             'task_worker_num' => 4
//         ));
// /*
// dispatch_mode
// 数据包分发策略。可以选择3种类型，默认为2
// 1，轮循模式，收到会轮循分配给每一个worker进程
// 2，固定模式，根据连接的文件描述符分配worker。这样可以保证同一个连接发来的数据只会被同一个worker处理
// 3，抢占模式，主进程会根据Worker的忙闲状态选择投递，只会投递给处于闲置状态的Worker
// 使用建议
// 无状态Server可以使用1或3，同步阻塞Server使用3，异步非阻塞Server使用1
// 有状态使用2、4、5
// dispatch_mode 4,5两种模式，在1.7.8以上版本可用
// 非请求响应式的服务器程序，请不要使用模式1或3
// UDP协议
// dispatch_mode=1/3时随机分配到不同的worker进程
// BASE模式
// */
 
//         $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
//         $this->serv->on('Connect', array($this, 'onConnect'));
//         $this->serv->on('Receive', array($this, 'onReceive'));
//         $this->serv->on('Close', array($this, 'onClose'));
//                 // bind callback
//         $this->serv->on('Task', array($this, 'onTask'));
//         $this->serv->on('Finish', array($this, 'onFinish'));
//         $this->serv->start();
//         }
//         public function onWorkerStart( $serv , $worker_id) {
//         echo "onWorkerStart\n";
//         // 判定是否为Task Worker进程
//         if( $worker_id >= $serv->setting['worker_num'] ) {
//                 $this->pdo = new PDO(
//                         "mysql:host=localhost;port=3306;dbname=testxcx",
//                         "root",
//                         "111111",
//                         array(
//                         PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
//                         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                         PDO::ATTR_PERSISTENT => true
//                 )
//             );
//         }
//     }
//     public function onConnect( $serv, $fd, $from_id ) {
//         echo "Client {$fd} connect\n";
//     }
//     public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
//         $sql = array(
//                 'sql'=>'insert into test values(?,?)',
//                 'param' => array(
//                         0 ,
//                         "name"
//                 ),
//                 'fd' => $fd
//         );
//         $serv->task( json_encode($sql) );
//     }
//     public function onClose( $serv, $fd, $from_id ) {
//         echo "a\n";
//         echo "Client {$fd} close connection\n";
//     }
//         public function onTask($serv,$task_id,$from_id, $data) {
//         try{
//             $sql = json_decode( $data , true );
 
//             //print_r($sql);
// /*
// Array
// (
//     [sql] => insert into test values(?,?)
//     [param] => Array
//         (
//             [0] => 0
//             [1] => name
//         )
//     [fd] => 1
// )
// */
//             $statement = $this->pdo->prepare($sql['sql']);
//            // $statement->execute($sql['param'][0],$sql['param'][1]);
//             $statement->execute($sql['param']);
//             $serv->send( $sql['fd'],"Insert");
//             return true;
//         } catch( PDOException $e ) {
//             var_dump( $e );
//             return false;
//         }
//     }
//     public function onFinish($serv,$task_id, $data) {
//     }
// }
// new MySQLPool();


?>
<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <section class="content-header">
                <h1>General Form Elements</h1>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Input</h3>
                            </div>
                            <form class="form-horizontal" action="http://127.0.0.1:9501" method="POST">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="inputNormal" class="col-sm-2 control-label">Normal</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="text1" id="inputNormal" placeholder="Enter...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNormal" class="col-sm-2 control-label">
                                            Star_mark
                                            <span class="star_mark">*</span>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="text2" id="inputNormal" placeholder="Enter...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDisabled" class="col-sm-2 control-label">Disabled</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="text3" id="inputDisabled" placeholder="Enter..." disabled="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Prompt</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="text4" id="inputEmail3" placeholder="Enter...">
                                            <span class="help-block">Heip block with error</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-left">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-1"> </div>
                    <div class="col-sm-3">
                        <div class=" context-info">
                            <h3>Note</h3>
                            <p>The length of the input bar is determined by the content，and use 12 columns to control the length</p>
                            <p>In most cases, our default name is right-aligned and the input fields are left-aligned</p>
                        </div>
                    </div>
                </div>
        
            </section>
        </div>
    </div>
</body>
</html>
                            -->
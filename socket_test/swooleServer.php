<?php
error_reporting(E_ALL);
set_time_limit(0);

// var_dump(SWOOLE_VERSION );

## swoole 协程
// use Swoole\Coroutine as Co;

// go(function () {
//     // yield 1;
//     Co::sleep(1); // 第一个sleep 和第二个sleep的 阻塞时间 互不干涉
//     echo "hello go1 \n";
// });

// echo "hello main \n";

// go(function () {
//     // yield 2;
//     Co::sleep(3);
//     echo "hello go2 \n";
// });

// $n = 4;
// for ($i = 0; $i < $n; $i++) {
//     sleep(1);
//     echo microtime(true) . ": hello $i \n";
// };
// echo "hello main \n";

// go(function () use ($n) {
//     for ($i = 0; $i < $n; $i++) {
//         Co::sleep(1);
//         echo microtime(true) . ": hello $i \n";
//     };
// });
// echo "hello main \n";

// for ($i = 0; $i < $n; $i++) {
//     go(function () use ($i) {
//         // Co::sleep(1);
//         sleep(1);
//         echo microtime(true) . ": hello $i \n";
//     });
// };
// echo "hello main \n";

## swoole 协程


class SwooleServer
{
    private $serv;
    private $pdo;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            // 'dispatch_mode' => 2,
            'dispatch_mode' => 3, //抢占模式，主进程会根据Worker的忙闲状态选择投递，只会投递给处于闲置状态的Worker
            'debug_mode'=> 1,
            'task_worker_num' => 10,
            
            // 'heartbeat_check_interval' => 10, // 30 秒检查一次所有连接
            // 'heartbeat_idle_time' => 20 // 如果有连接 60s 内没有数据交互 关闭该链接
            
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        // $this->serv->on('Timer', array($this, 'onTimer'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
    }

    public function start()
    {
        $this->serv->start();
    }

    public function onStart($serv)
    {
        // var_dump($serv);
        echo "Start\n";
    }

    public function onWorkerStart($serv, $worker_id)
    {
        echo "onWorkerStart {$serv->taskworker} \n"; // 先 10个 task 后8个worker
        
    // true表示当前的进程是Task工作进程
    // false表示当前的进程是Worker进程

        // global $argv;
        // if($worker_id >= $serv->setting['worker_num']) {
        //     swoole_set_process_name("php {$argv[0]} task worker");
        // } else {
        //     swoole_set_process_name("php {$argv[0]} event worker");
        // }
        // // 判定是否为Task Worker进程

        if ($worker_id >= $serv->setting['worker_num']) {
            $this->pdo = new PDO(
                    "mysql:host=127.0.0.1;port=3306;dbname=mydb",
                    "root",
                    "",
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_PERSISTENT => true
                    )
            );
        }
    }

    // public function onTimer($serv, $interval) {
    // 	switch( $interval ) {
    // 		case 500: {	//
    // 			echo "Do Thing A at interval 500\n";
    // 			break;
    // 		}
    // 		case 1000:{
    // 			echo "Do Thing B at interval 1000\n";
    // 			break;
    // 		}
    // 		case 1500:{
    // 			echo "Do Thing C at interval 1500\n";
    // 			break;
    // 		}
    // 	}
    // }

    public function onConnect($serv, $fd, $from_id)
    {
        $serv->send($fd, "Hello {$fd}!");
    }

    # mysql 连接池
    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        // $sql = array(
        //     'sql'=>'select ArticleName,AuthorName from cnblogs where id = ?',
        //     'param' => array(
        //         1
        //     ),
        //     'fd' => $fd
        // );
        // $serv->task( json_encode($sql) );

        $sql = array(
            'sql'=>'insert hero (`name`) VALUES (?);',
            'param' => array(
                "fafaf"
            ),
            'fd' => $fd
        );
        $serv->task( json_encode($sql) );

    }
    public function onTask($serv,$task_id,$from_id, $data) {
        try{
            $sql = json_decode( $data , true );
            $statement = $this->pdo->prepare($sql['sql']);
           // $statement->execute($sql['param'][0],$sql['param'][1]);
            $statement->execute($sql['param']);
            $serv->send( $sql['fd'],"Insert ");
            return true;
        } catch( PDOException $e ) {
            var_dump( $e );
            return false;
        }
    }

    // public function onTask($serv,$task_id,$from_id, $data) {
    //     $sql = json_decode( $data , true );
    //     $statement = $this->pdo->prepare($sql['sql']);
    //     $statement->execute($sql['param']);
    //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    //     $serv->send( $sql['fd'],json_encode($result));
    //     return true;
    // }
    # mysql 连接池
    

    ### normal 每次客户端发送消息都会触发 task
    // public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
    //     echo "Get Message From Client {$fd}:{$data}\n";

    //     // send a task to task worker.
    //     $param = array(
    //         'fd' => $fd
    //     );
    //     // $param2 = array(
    //     //     'fd' => $fd
    //     // );
    //     // start a task
    //     $serv->task( json_encode( $param ) );
    //     // $serv->task( json_encode( $param2 ) );
        
    //     echo "Continue Handle Worker \n";
    // }

    // public function onTask($serv,$task_id,$from_id, $data) {
    //     echo "This Task {$task_id} from Worker {$from_id}\n";
    //     echo "Data from task : {$data} \n";

    //     for($i = 0 ; $i < 5 ; $i ++ ) {
    //         sleep(1);
    //         echo "Task {$task_id} Handle {$i} times...\n";
    //     }

    //     $fd = json_decode( $data , true )['fd'];
    //     $serv->send( $fd , "Data in Task {$task_id}");
    //     return "Task {$task_id}'s result"; // 返回给 onFinish
    // }

    public function onFinish($serv,$task_id, $data) {
        echo "Task {$task_id} finish \n";
        echo "Result: {$data} \n\n\n";
    }
    ### normal

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection \n";
    }
}
// 启动服务器
$server = new SwooleServer();
$server->start();



// $pdo = null;
// $option = array(
//     PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
//     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_PERSISTENT => true
// );
// try {
//     $t1 = microtime(true);
//     $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=mydb", "root", "",$option);
//     // $pdo = new PDO("mysql:host=localhost;port=3306;dbname=mydb", "root", "",$option);

//     $sql = array(
//         'sql'=>'insert hero (`name`) VALUES (?);',
//         'param' => array(
//             "fafaf"
//         ),
//     );
//     // json_encode($sql);
//     // $sql = json_decode( $data , true );
    
//     $statement = $pdo->prepare($sql['sql']);
//     for ($i=0; $i < 1000; $i++) { 
//         $statement->execute($sql['param']);
//     }
//     $t2 = microtime(true);
//     echo 'cost '.round($t2-$t1, 3).' second<br>'; // cost 47.495 second<br>  
// } catch (PDOException $e) {
//     echo $e->getMessage();
// }

// var_dump($pdo);



// $http = new swoole_http_server("127.0.0.1", 9501);
// $serv = new Swoole\Http\Server('0.0.0.0', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);

// ab -c 100 -n 100 http://127.0.0.1:9501/
// -c10表示并发用户数为10
// -n100表示请求总数为100

// $serv->set(array(
//     'worker_num' => 4,
//     'daemonize' => true,
//     'backlog' => 128,
// ));

// $serv->on('connect', function ($serv, $fd){
//     echo "Client:Connect.\n";
// });
// $serv->on('receive', function ($serv, $fd, $reactor_id, $data) {
//     $serv->send($fd, 'Swoole: '.$data);
//     echo $data;
//     $serv->close($fd);
// });




// $serv->on('Close', function($serv, $fd){
//     // var_dump($fd); // int(1)
//     echo "Swoole Client is closed \n";
// });
// $serv->on("start", function ($server) {
//     echo "Swoole http server is started at http://127.0.0.1:9501\n";
// });
// $serv->on("request", function ($request, $response) {
//     // var_dump($request->get, $request->post);
//     var_dump($request->post);
//     $response->header("Content-Type", "text/plain");
//     $response->end("Hello World\n");
// });
// $serv->start();
// var_dump($http);



// $server = new swoole_server("127.0.0.1", 9501);

// // Register the function for the event `connect`
// $server->on('connect', function($server, $fd){
//     echo "Client : Connect.\n";
// });

// // Register the function for the event `receive`
// $server->on('receive', function($server, $fd, $from_id, $data){
//     echo "Receive the data {$data} from client {$fd} and reactor_id is {$from_id} \n";
//     $server->send($fd, "Server: " . $data);
// });

// // Register the function for the event `close`
// $server->on('close', function($server, $fd){
//     echo "Client: {$fd} close.\n";
// });

// // Start the server
// $server->start();







//Create the websocket server object
// $websocket_server = new swoole_websocket_server("0.0.0.0", 9502);

// // Register function of the opening connection event
// $websocket_server->on('open', function($websocket_server, $request){
//     var_dump($request->fd, $request->get, $request->server);
//     $websocket_server->push($request->fd, "Hello welcome\n");
// });

// // Register function of the receiving message event
// $websocket_server->on('message', function($websocket_server, $frame){
//     echo "Message : {$frame->data}\n";
//     $websocket_server->push($frame->fd, "Server : {$frame->data}");
// });

// // Register function of the close event
// $websocket_server->on('close', function($websocket_server, $fd){
//     echo "client_{$fd} is closed\n";
// });

// // Start the server
// $websocket_server->start();




// $serv = new Swoole\Server('0.0.0.0', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);
// $serv->set(array(
//     'worker_num' => 4,
//     'daemonize' => true,
//     'backlog' => 128,
// ));

// $serv->on('Connect', 'my_onConnect');
// $serv->on('Receive', 'my_onReceive');
// $serv->on('Close', 'my_onClose');

// $serv->start();

// echo "<pre>";

// var_dump(10106.53 + 19469.33 + 3051.88 + 2435.96 + 3051.88 + 10106.53 + 19469.33 + 3051.88 + 79.41);

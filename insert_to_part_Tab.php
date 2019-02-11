<?php
class PartTab
{
    public static $db = array(
            'dbhost' => '127.0.0.1',
            // 'dbhost' => '127.0.0.1',
            // 'dbname' => 'tkchina',
            'dbname' => 'mydb',
            'dbuser' => 'root',
            'dbpswd' => '',
        );

    public static $months = array(
"01",
"02",
"03",
"04",
"05",
"06",
"07",
"08",
"09",
"10",
"11",
"12"
        );

    public static $aids = array(
            1,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22
                    );
                    
    public static $years = array(
"2011",
"2012",
"2013",
"2014",
"2015",
"2016",
"2017",
"2018"


        );

    public static function insert()
    {
        srand((float) microtime() * 10000000);


        // $rn = array_rand($years);
        // var_dump($years[$rn]);


        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }


        $contenid = 5;

        for ($i=1; $i <= 50; $i++) {
            // $query = "INSERT INTO city (Name) values";
            // $query = "INSERT INTO traveldistance_3 (Content,contentid) values";
            $query = "INSERT INTO traveldistance_4 (Year,Month,Content,contentid) values";
            $subsql = "";
            $paramData = array();
            for ($j=1; $j <= 1000; $j++) {
                $y = array_rand(self::$years);
                $m = array_rand(self::$months);
                $aid = array_rand(self::$aids);

                $id = md5($i . $j);
                

                $paramData[":year" . $id] = self::$years[$y];
                $paramData[":month" . $id] = self::$months[$y];
                $paramData[":content" . $id] = $id;
                $paramData[":contentid" . $id] = $contenid;
                // $paramData[":aid" . $id] =  self::$aids[$aid];
                // $paramData[":name" . $id] = $id;

                $sub = '(:year' . $id . ',' . ':month' . $id . ',' . ':content' . $id  . ','.  ':contentid' . $id  .'),';
                // $sub = '(:aid' . $id . ',' . ':name' . $id . '),';
                // $sub = '(:name' . $id  . '),';                                         
                $subsql .= $sub;
                $contenid++;
            }
            $query .= rtrim($subsql, ",").";";
            // //

            // echo "<pre>";
            // var_dump($query);
            // die;

            $stmt = $pdo->prepare($query);
            $bool = $stmt->execute($paramData);
            $subsql = "";

            // echo "Inserted 5 records !";
            // echo "\n";
            // echo "<br>";
        }
    }

    public static function delete()
    {
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }


        do {
            $sql="DELETE from traveldistance_1 where `year` < '2017' or (`year` = '2017' and `month` <= '05' ) limit 1000";
            $res=$pdo->exec($sql);
        } while ($res);
    }

    public static function select()
    {
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $sql = "SELECT * FROM biaoa";
        $sth = $pdo->query($sql);
        echo "<pre>";
        // var_dump($sth);
        // $ref = new ReflectionClass($sth);
        // var_dump($ref->getMethods());
        $row = $sth->fetchALl();
        var_dump($row);
      
    }

    public static function selectBySql($sql)
    {
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $sth = $pdo->query($sql);

        
// $rows = $pdo->query('select * from expense where state = 2')->fetchAll(PDO::FETCH_ASSOC);
// echo count($rows);

        // echo "<pre>";
        // var_dump($sth);
        // $ref = new ReflectionClass($sth);
        // var_dump($ref->getMethods());
        $row = $sth->fetch();
        return $row;
        // var_dump($row);
      
    }

    public static function fetchColumn($table)
    {
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $stmt = $pdo->prepare('DESC ' . $table);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $table_fields;
    }

    public static function fetchAllR($sql)
    {
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $sth = $pdo->query($sql);
        // $table_fields = $sth->fetchAll(PDO::FETCH_ASSOC);// 按字段名返回的数组
        $table_fields = $sth->fetchAll(PDO::FETCH_NUM);// 按字段的顺序返回的数组
        return $table_fields;
    }

    
    public static function prepareTest($var)
    {
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 这段代码可以防止 插入特殊字符

            //设置禁止本地模拟prepare
            $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            
            // $pdo->query('SET NAMES gbk');
            // $var = "\xbf\x27 OR 1=1 /*";

            // $query = 'SELECT * FROM acount WHERE name = ? LIMIT 1';
            // $stmt = $pdo->prepare($query);
            // $stmt->execute(array($var));

            $stmt = $pdo->prepare('select * from acount where `NAME` = :name  LIMIT 1');
            $stmt->execute( array(':name' => $var) );

            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        


        // $var = "\xbf\x27 OR 1=1 /*";
        // // $query = 'SELECT * FROM test WHERE name = ? LIMIT 1';
        // // $stmt = $pdo->prepare($query);
        // // $stmt->execute(array($var));

        // $stmt = $pdo->prepare('select * from acount where `NAME` = :name');
        // $stmt->execute( array(':name' => $var) );

        


        
        return $result;
    }

    public static function bindParamTest(){
        $the_db = self::$db;
        extract($the_db);
        $dsn = "mysql:host={$dbhost};dbname={$dbname}";

        try {
            $pdo = new PDO($dsn, $dbuser, $dbpswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

$query = <<<QUERY
    INSERT INTO `acount` (`NAME`, `num`) VALUES (:username, :password);
QUERY;
        $statement = $pdo->prepare($query);
 
        // $bind_params = array(':username' => "laruence", ':password' => 432);

        $name = "laruence";
        $num = 432;
        $bind_params = array(':username' => $name, ':password' => $num);

        foreach( $bind_params as $key => &$value ){
            $statement->bindParam($key, $value);
        }
        $statement->execute();

    }

}

// set_time_limit(0);
// $t1 = microtime(true);
// // ... 执行代码 ...
// PartTab::insert();
// $t2 = microtime(true);
// echo '耗时'.round($t2-$t1, 3).'秒<br>';
// echo 'Now memory_get_usage: ' . memory_get_usage() . '<br />';




// ob_end_clean();
// ob_implicit_flush(1);
// while(1){
//     //部分浏览器需要内容达到一定长度了才输出
//     echo str_repeat("<div></div>", 200).'hello sjolzy.cn<br />';
//     sleep(1);
//     //ob_end_flush();
//     //ob_flush();
//     //flush();
// }

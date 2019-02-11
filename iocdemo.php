<?php


/*******************************************************************************/
/*
	超人的属性会越来越多
*/
class PowerS {
    /**
     * 能力值
     */
    protected $ability;

    /**
     * 能力范围或距离
     */
    protected $range;

    public function __construct($ability, $range)
    {
        $this->ability = $ability;
        $this->range = $range;
    }
}

class Flight
{
    protected $speed;
    protected $holdtime;
    public function __construct($speed, $holdtime) {}
}

class Force
{
    protected $force;
    public function __construct($force) {}
}

class Shot
{
    protected $atk;
    protected $range;
    protected $limit;
    public function __construct($atk, $range, $limit) {}
}




/**
 * X-超能量
 */
class XPower implements SuperModuleInterface
{
    public function activate(array $target)
    {
        // 这只是个例子。。具体自行脑补
        echo "I'm Xpower Nne" . $target[0];
    }
}


/**
 * 终极炸弹 
 */
class UltraBomb implements SuperModuleInterface
{
    public function activate(array $target)
    {
        // 这只是个例子。。具体自行脑补
        echo "I'm UltraBomb Nne 2222222222222222". $target[0];
    }
}
/*******************************************************************************/




/*

IoC 容器（原始模式）

*/

class SupermanS
{
	/*
		instance of PowerS
	*/
    protected $power;

    public function __construct()
    {
        $this->power = new Power(999, 100);
        /* 容器（原始模式） 需要手动在构造函数中实例化属性  */
        // $this->power = new Force(45);
        // $this->power = new Shot(99, 50, 2);
        /*
        $this->power = array(
            new Force(45),
            new Shot(99, 50, 2)
        );
        */
		/*******************************************/

    }
}




/*

IoC 容器（工厂模式）

*/
class SuperModuleFactory
{
    public function makeModule($moduleName, $options)
    {
        switch ($moduleName) {
            case 'Fight':     return new Fight($options[0], $options[1]);
            case 'Force':     return new Force($options[0]);
            case 'Shot':     return new Shot($options[0], $options[1], $options[2]);
        }
    }
}

class SupermanF
{
    protected $power;

    public function __construct()
    {
        // 初始化工厂
        $factory = new SuperModuleFactory;

        // 通过工厂提供的方法制造需要的模块
        $this->power = $factory->makeModule('Fight', [9, 100]);
        // $this->power = $factory->makeModule('Force', [45]);
        // $this->power = $factory->makeModule('Shot', [99, 50, 2]);
        /*
        $this->power = array(
            $factory->makeModule('Force', [45]),
            $factory->makeModule('Shot', [99, 50, 2])
        );
        */

        // 通过工厂提供的方法批量制造需要的模块
        // foreach ($modules as $moduleName => $moduleOptions) {
        //     $this->power[] = $factory->makeModule($moduleName, $moduleOptions);
        // }
    }
}

// 工厂模式创建超人
// $superman_F = new SupermanF([
//     'Fight' => [9, 100], 
//     'Shot' => [99, 50, 2]
//     ]);









/*

IoC （依赖注入）
工厂模式依赖并未解除，只是由原来对多个外部的依赖变成了对一个 “工厂” 的依赖

*/
interface SuperModuleInterface
{
    /**
     * 超能力激活方法
     *
     * 任何一个超能力都得有该方法，并拥有一个参数
     *@param array $target 针对目标，可以是一个或多个，自己或他人
     */
    public function activate(array $target);
}

class SupermanIron
{
    public $module;

    public function __construct(SuperModuleInterface $module)
    {
        $this->module = $module;
    }
}

// 代码必须放在所有类定义的后面
// 超能力模组
// $superModule = new XPower();
// 初始化一个超人，并注入一个超能力模组依赖
// $superMan_I = new SupermanIron($superModule);






/*

IoC 容器 科技时代（IoC容器）

*/
class Container
{
    protected $binds;

    protected $instances;

    // 注册
    public function bind($abstract, $concrete)
    {
        if ($concrete instanceof Closure) {
            $this->binds[$abstract] = $concrete;
        } else {
            $this->instances[$abstract] = $concrete;
        }
    }

    // 创建对象
    public function make($abstract, $parameters = [])
    {
    	// echo "<pre>"; 
     //    var_dump(333); 
     //    var_dump($abstract); 
     //    var_dump($parameters); 
     //    die;

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // echo "<pre>"; 
        // var_dump(333); 
        // var_dump($abstract); 
        // var_dump($this->binds[$abstract]); // 是一个callback 类型的参数
        // var_dump($parameters); 
        // die;

        array_unshift($parameters, $this);

        // echo "<pre>"; 
        // var_dump(333); 
        // var_dump($abstract); 
        // var_dump($this->binds[$abstract]); // 是一个callback 类型的参数
        // var_dump($parameters); 
        // var_dump(call_user_func_array($this->binds[$abstract], $parameters)); 
        // die;

        return call_user_func_array($this->binds[$abstract], $parameters);
    }
}


// 创建一个容器（后面称作超级工厂）
$container = new Container;


// 闭包方式 绑定对象
$container->bind('clark', function($container,$module){
	return new SupermanIron($container->make($module)); // bind 时候 调用make
});

$container->bind('nengli1', function() {
    return new XPower;
});

$container->bind('nengli2', function() {
    return new UltraBomb;
});

echo "<pre>";
// var_dump($container);


$superman_2 = $container->make('clark', array('nengli1'));
// 第一步 call_user_func_array(clark 关联的callback， container对象)
// 第二步 执行clark 对应的call back，call_user_func_array数组参数的 第一个参数是container对象，第二个参数是字符串nengli1
// 第三步 $container->make($module) 根据第二个nengli2 生成SuperModuleInterface 对象
// 返回clark superman


// 能力对象也可以单个make出来：
// $nengli = $container->make('nengli1');
// var_dump($nengli);
// $nengli->activate(['biubiubiu']);




// $superman_2 = $container->make('clark'); // 不能单个make 依赖SuperModuleInterface





// var_dump($superman_2); // 现在用不了ativate 方法 只是个SupermanIron的对象

// var_dump($superman_2);


// 向该 超级工厂 添加 超人 的生产脚本
// $container->bind('SupermanIron', function($container, $moduleName) {
//     return new SupermanIron($container->make($moduleName));
// });
// //  向该 超级工厂 添加 超能力模组 的生产脚本
// $container->bind('xpower', function($container) {
//     return new XPower; // $container 参数是可以不要的, 目的就是为了返回一个闭包
// });
// // // 同上
// $container->bind('ultrabomb', function($container) {
//     return new UltraBomb;
// });

// ******************    **********************
// 开始启动生产
// $superman_1 = $container->make('SupermanIron', array('xpower','ultrabomb'));
// $superman_2 = $container->make('SupermanIron', array('ultrabomb'));
// $superman_3 = $container->make('superman', 'xpower');
// ...随意添加

// $superman_1->module->activate(array("goog"));
// echo "<br>";
// $superman_2->module->activate(array("gggggggggggggg"));
// var_dump($superman_1 );



























// $a = new Closure();
// $ref = new ReflectionClass($a );
// echo "<pre>";
// var_dump($ref);
/* php 闭包 */

$closureFunc = function($str){
	echo $str;
};
// $closureFunc("hello world!");
if ($closureFunc instanceof Closure) {
    // var_dump("bibao");
}


//在函数里定义一个匿名函数，并且调用它
function printStr() {
	$func = function( $str ) {
		echo $str;
	};
	$func( ' hello my girlfriend ! ' );
}
// printStr();


//在函数中把匿名函数返回，并且调用它
function getPrintStrFunc() {
	$func = function( $str ) {
		echo $str;
	};
	return $func;
}
$printStrFunc = getPrintStrFunc();
// $printStrFunc( ' do you love me ? ' );


//把匿名函数当做参数传递，并且调用它
function callFunc( $func ) {
	$func( ' no!i hate you ' );
}
$printStrFunc = function( $str ) {
	echo $str.'<br>';
};
// callFunc( $printStrFunc );
// //也可以直接将匿名函数进行传递。
// callFunc( function( $str ) {
// 	echo $str; //输出no!i hate you
// });


// 连接闭包和外界变量的关键字：USE
// use所引用的也只不过是变量的一个副本clone而已。但是我想要完全引用变量，而不是复制呢?要达到这种效果，其实在变量前加一个 & 符号就可以了：
function getMoney() {
	$rmb = 1;
	$dollar = 8;
	$func = function() use ( $rmb,&$dollar ) {
		echo $rmb;
		$dollar++;
		echo $dollar;
	};
	$func();
}
// getMoney();


//调用一个类里面的匿名函数
class A {
	public static function testA() {
		return function($i) { //返回匿名函数
			return $i+100;
		};
	}
}
function B(Closure $callback)
{
	return $callback(200);
}
$a = B(A::testA());
// print_r($a);//输出 300



// 绑定匿名函数
class Aq {
	public $base = 100;
}
class Bq {
	private $base = 1000;
}
$f = function () {
    // return $this->base + 3;
	// var_dump($this->base + 3) ;
};
$aqq = Closure::bind($f, new Aq);
// print_r($a());//输出 103


// var_dump();
// call_user_func_array($a, array(new Bq));

// $aone = new Aq;
// $bone = new Bq;

$tetsp = function($fa, $fd){
    return $fa . ' oooo ' . $fd;
};
$tetsobj = function($fa, $fd){
    return new XPower;

};


// $rety = call_user_func_array($tetsobj , array("ddd", 'fafa', 'gggg'));  //第二个数组参数 多出来的元素是无效的

// var_dump("当第一个回调参数返回对象时候，后面的数组参数已经没有意义了 ");
// var_dump($rety);

// echo PHP_EOL;
// $b = Closure::bind($f, new Bq , 'Bq');
// print_r($b());//输出1003


// $anonyFun1c=function($name){
// return "hello ".$name;
// };
// echo $anonyFun1c->__invoke("lc");




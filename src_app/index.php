<?php

require_once 'vendor/autoload.php';

use App\Models\User;
//use App\Models\Country;
//use App\Security\User as UserSecurity;
use Illuminate\Database\Capsule\Manager as Capsule;


$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'mysql',
    'database'  => 'db',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Set the event dispatcher used by Eloquent models... (optional)
//use Illuminate\Events\Dispatcher;
//use Illuminate\Container\Container;
//$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();




//Select
$user = Capsule::table('users')->find(1);

echo "$user->firstname\n";
echo "$user->lastname\n";
echo "$user->email\n";


//Update
$user = User::find(1);
$user->firstname = 'Juancito';
$user->save();


//Insert
/*$newUser = new User();
$newUser->firstname = 'Santiago';
$newUser->lastname ='Palacios';
$newUser->email= 's.palacios@itec.org.ar';
$newUser->password = md5('random123');
$newUser->save();
*/


//var_dump($users->firstname);

//
//
//
//
//
//$user = new User();
//
//$user->setName('Santiago Palacios');
//
//echo $user->getName();
//
//
//$userSecurity = new UserSecurity();
//
//$userSecurity->setName('Usario de santiago');
//
//echo ($userSecurity->getName());
//
//
//$country = new Country();




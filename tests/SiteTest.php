<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Model\User;
use Model\Building;
use Model\Room;
use Model\RoomType;

class SiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $projectRoot = dirname(__DIR__);
        $_SERVER['DOCUMENT_ROOT'] = $projectRoot;
        
        if (!file_exists($projectRoot . '/config/db.php')) {
            $this->markTestSkipped('Файл config/db.php не найден.');
            return;
        }
        
        $dbConfig = include $projectRoot . '/config/db.php';
        $appConfig = include $projectRoot . '/config/app.php';
        $pathConfig = include $projectRoot . '/config/path.php';

        $capsule = new \Illuminate\Database\Capsule\Manager;

        $capsule->addConnection([
            'driver'    => $dbConfig['driver'] ?? 'mysql',
            'host'      => $dbConfig['host'] ?? 'localhost',
            'database'  => $dbConfig['database'],
            'username'  => $dbConfig['username'],
            'password'  => $dbConfig['password'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $settingsArray = [
            'app' => $appConfig,
            'db' => $dbConfig,
            'path' => $pathConfig,
        ];
        
        try {
            $GLOBALS['app'] = new Src\Application($settingsArray);
        } catch (\TypeError $e) {
        }
        
        if (!function_exists('app')) {
            function app() {
                return $GLOBALS['app'];
            }
        }

        $_SESSION = [];
    }

    #[DataProvider('registrationProvider')]
    public function testUserRegistration(array $data, bool $shouldBeCreated, string $expectedMessagePart): void
    {
        $user = null;
        $error = null;

        if (trim($data['full_name']) === '' || trim($data['login']) === '' || trim($data['password']) === '') {
             $error = "Field cannot be empty"; 
        } else {
            $exists = User::where('login', $data['login'])->exists();
            if ($exists) {
                $error = "Login is busy";
            } else {
                try {
                    $hashedPassword = md5($data['password']);
                    
                    $user = User::create([
                        'full_name' => $data['full_name'],
                        'login' => $data['login'],
                        'password_hash' => $hashedPassword, 
                        'role_id' => 2
                    ]);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        if ($shouldBeCreated) {
            $this->assertNotNull($user, "Пользователь должен был быть создан");
            if ($user) {
                $this->assertEquals(md5($data['password']), $user->password_hash, "Пароль должен быть захеширован MD5");
                $user->delete();
            }
        } else {
            $this->assertNull($user, "Пользователь не должен был быть создан");
            
            $isContain = (stripos($error ?? '', $expectedMessagePart) !== false);
            $this->assertTrue($isContain, "Ожидалась ошибка содержащая '{$expectedMessagePart}', получено: '{$error}'");
        }
    }

    public static function registrationProvider(): array
    {
        return [
            'Успешная регистрация' => [
                ['full_name' => 'Test User', 'login' => 'test_user_' . uniqid(), 'password' => '123456'],
                true,
                ''
            ],
            'Пустое имя' => [
                ['full_name' => '', 'login' => 'test_login_' . uniqid(), 'password' => '123456'],
                false,
                'empty'
            ],
            'Пустой пароль' => [
                ['full_name' => 'Test', 'login' => 'test_login_' . uniqid(), 'password' => ''],
                false,
                'empty'
            ],
        ];
    }

    #[DataProvider('loginProvider')]
    public function testUserLogin(string $loginInput, string $passwordInput, bool $expectSuccess): void
    {
        $testLogin = 'login_test_' . uniqid();
        $testPassPlain = 'secret';
        
        $user = null;
        try {
            $hashedPass = md5($testPassPlain);
            
            $user = User::create([
                'full_name' => 'Login Tester',
                'login' => $testLogin,
                'password_hash' => $hashedPass, 
                'role_id' => 1
            ]);

            $authInstance = new User();
            $authUser = $authInstance->attemptIdentity([
                'login' => ($loginInput === 'USE_TEST_USER' ? $testLogin : $loginInput),
                'password' => $passwordInput
            ]);

            if ($expectSuccess) {
                $this->assertNotNull($authUser, "Вход должен быть успешным.");
                $this->assertEquals($user->id, $authUser->id);
            } else {
                $this->assertNull($authUser, "Вход должен быть неудачным");
            }
        } finally {
            if ($user) {
                $user->delete();
            }
        }
    }

    public static function loginProvider(): array
    {
        return [
            'Верный логин и пароль' => ['USE_TEST_USER', 'secret', true],
            'Неверный пароль' => ['USE_TEST_USER', 'wrong', false],
            'Неверный логин' => ['non_existent', 'secret', false],
        ];
    }

    public function testBuildingRecalculation(): void
    {
        $building = null;
        $room1 = null;
        $room2 = null;
        $roomType = null;

        try {
            $building = Building::create([
                'name' => 'Test Building ' . uniqid(),
                'address' => 'Test Address'
            ]);

            $roomType = RoomType::first();
            if (!$roomType) {
                $roomType = RoomType::create(['type_name' => 'Classroom', 'is_educational' => 1]);
            }

            $room1 = Room::create([
                'building_id' => $building->id,
                'room_number' => '101',
                'room_type_id' => $roomType->id,
                'area' => 50.5,
                'seat_total' => 20
            ]);

            $room2 = Room::create([
                'building_id' => $building->id,
                'room_number' => '102',
                'room_type_id' => $roomType->id,
                'area' => 30.0,
                'seat_total' => 15
            ]);

            $calculatedArea = $building->calculated_area; 
            $calculatedSeats = $building->calculated_seats;

            $this->assertEqualsWithDelta(80.5, $calculatedArea, 0.01, "Общая площадь должна быть 80.5");
            $this->assertEquals(35, $calculatedSeats, "Общее кол-во мест должно быть 35");

        } finally {
            if ($room1) $room1->delete();
            if ($room2) $room2->delete();
            if ($building) $building->delete();
        }
    }

    public function testRoomCreationValidation(): void
    {
        $building = null;
        $room = null;
        $roomType = null;

        try {
            $building = Building::create([
                'name' => 'Build for Room Test ' . uniqid(),
                'address' => 'Addr'
            ]);
            
            $roomType = RoomType::first();
            if (!$roomType) {
                $roomType = RoomType::create(['type_name' => 'Lab', 'is_educational' => 1]);
            }

            $room = Room::create([
                'building_id' => $building->id,
                'room_number' => '999',
                'room_type_id' => $roomType->id,
                'area' => 100,
                'seat_total' => 50
            ]);

            $this->assertNotNull($room->id);
            $this->assertEquals(100, $room->area);

        } finally {
            if ($room) $room->delete();
            if ($building) $building->delete();
        }
    }
}
<?php


use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    public function run()
    {
        /** @var \MMoney\Application $app */
        $app = require __DIR__ . "/../bootstrap.php";

        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");

        $faker = \Faker\Factory::create("pt_BR");

        $users = $this->table("users");
        $users->insert([
            "first_name" => $faker->firstName,
            "last_name" => $faker->lastName,
            "email" => "admin@users.com",
            "password" => $auth->hashPassword("123456"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        $data = [];

        foreach (range(1, 3) as $value) {
            $data[] = [
                "first_name" => $faker->firstName,
                "last_name" => $faker->lastName,
                "email" => $faker->unique()->email,
                "password" => $auth->hashPassword("123456"),
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];
        }

        $users->insert($data)->save();
    }
}

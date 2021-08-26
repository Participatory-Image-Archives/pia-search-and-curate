<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Image::class, static function (Faker\Generator $faker) {
    return [
        'salsah_id' => $faker->randomNumber(5),
        'oldnr' => $faker->sentence,
        'signature' => $faker->sentence,
        'title' => $faker->sentence,
        'original_title' => $faker->sentence,
        'file_name' => $faker->sentence,
        'original_file_name' => $faker->sentence,
        'salsah_date' => $faker->sentence,
        'sequence_number' => $faker->sentence,
        'location' => $faker->randomNumber(5),
        'collection' => $faker->randomNumber(5),
        'verso' => $faker->randomNumber(5),
        'objecttype' => $faker->randomNumber(5),
        'model' => $faker->randomNumber(5),
        'format' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => true,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'last_login_at' => $faker->dateTime,
        
    ];
});/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Location::class, static function (Faker\Generator $faker) {
    return [
        'label' => $faker->sentence,
        'geonames_id' => $faker->randomNumber(5),
        'geonames_url' => $faker->sentence,
        'latitude' => $faker->randomFloat,
        'longitude' => $faker->randomFloat,
        'place_id' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});

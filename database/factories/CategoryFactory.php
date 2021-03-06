<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'photo' => 'backendtemplate/categoryimg'.$faker->image('public/backendtemplate/categoryimg',200,150,null,false)
    ];
});

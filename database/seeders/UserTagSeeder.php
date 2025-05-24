<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tag;

class UserTagSeeder extends Seeder
{
    public function run()
{
    $users = User::all();
    $tagIds = Tag::all()->pluck('id');

    foreach ($users as $user) {
        // Assign 1 to 3 random tags
        $user->tags()->attach($tagIds->random(rand(1, 3)));
    }
}
}

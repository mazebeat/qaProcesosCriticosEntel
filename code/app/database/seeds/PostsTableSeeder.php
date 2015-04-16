<?php
use Faker;

class PostsTableSeeder extends Seeder
{

	public function run()
	{
		$count = 20;

		$this->command->info('Deleting existing Posts table ...');
		DB::table('post')->delete();

		$faker = Factory::create('es_ES');

		// Uncomment the below to wipe the table clean before populating
		// DB::table('posts')->truncate();

		$this->command->info('Inserting ' . $count . ' sample records using Faker ...');

		for ($i = 0; $i < $count; $i++) {
			$posts = Post::create(array(
				                      'title' => $faker->userName,
				                      'body'  => $faker->firstName,
			                      ));
		}

		$this->command->info('Posts table seeded using Faker ...');

		// Uncomment the below to run the seeder
		// DB::table('posts')->insert($posts);
	}

}

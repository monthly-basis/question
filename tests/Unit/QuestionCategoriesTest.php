<?php

namespace Tests\Unit;

use Illuminate\Database\Capsule\Manager as Capsule;
use MonthlyBasis\Question\Models\QuestionCategories;
use PHPUnit\Framework\TestCase;

class QuestionCategoriesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $config = $this->getConfigArray();
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->migrate();
    }

    protected function migrate(): void
    {
        Capsule::statement('SET FOREIGN_KEY_CHECKS=0;');
        Capsule::table('category')->truncate();
        Capsule::table('question')->truncate();
        Capsule::statement('SET FOREIGN_KEY_CHECKS=1;');

        Capsule::schema()->dropIfExists('question_categories');
        Capsule::schema()->create('question_categories', function ($table) {
            $table->id();
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('category_id_1');
            $table->unsignedInteger('category_id_2')->nullable();
            $table->unsignedInteger('category_id_3')->nullable();
            $table->dateTime('question_created_datetime_cached')->nullable();

            $table->primary('id');
            $table->unique('question_id');

            $table->foreign('question_id')->references('question_id')->on('question')->onDelete('cascade');
            $table->foreign('category_id_1')->references('category_id')->on('category')->onDelete('cascade');
            $table->foreign('category_id_2')->references('category_id')->on('category')->onDelete('set null');
            $table->foreign('category_id_3')->references('category_id')->on('category')->onDelete('set null');
        });
    }

    protected function getConfigArray(): array
    {
        $configArray = require($_SERVER['PWD'] . '/config/autoload/local.php');
        return $configArray['db']['adapters']['test'];
    }

    public function test_something()
    {
        Capsule::table('category')->insert([
            'slug' => 'mathematics',
            'name' => 'Mathematics',
        ]);
        Capsule::table('category')->insert([
            'slug' => 'algebra',
            'name' => 'Algebra',
        ]);
        Capsule::table('category')->insert([
            'slug' => 'formulas',
            'name' => 'Formulas',
        ]);
        Capsule::table('question')->insert([
            'question_id' => 123,
            'message' => 'question message',
        ]);

        $questionCategories = QuestionCategories::create([
            'question_id' => 123,
            'category_id_1' => 1,
            'category_id_2' => 2,
            'category_id_3' => 3,
        ]);
        $this->assertSame(1, $questionCategories->id);
        $this->assertSame(123, $questionCategories->question_id);
        $this->assertSame(1, $questionCategories->category_id_1);
        $this->assertSame(2, $questionCategories->category_id_2);
        $this->assertSame(3, $questionCategories->category_id_3);
    }
}

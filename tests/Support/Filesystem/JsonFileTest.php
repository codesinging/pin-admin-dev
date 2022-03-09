<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Filesystem;

use CodeSinging\PinAdmin\Support\Filesystem\JsonFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class JsonFileTest extends TestCase
{
    public function testRead()
    {
        $jsonFile = new JsonFile(base_path('composer.json'));

        self::assertIsArray($jsonFile->data());
    }

    public function testGet()
    {
        $jsonFile = new JsonFile(base_path('composer.json'));

        self::assertEquals('laravel/laravel', $jsonFile->get('name'));
        self::assertEquals('app/', $jsonFile->get('autoload.psr-4.App\\'));
    }

    public function testHas()
    {
        $jsonFile = new JsonFile(base_path('composer.json'));

        self::assertTrue($jsonFile->has('name'));
        self::assertFalse($jsonFile->has('name_not_exists'));
    }

    public function testSet()
    {
        $file = base_path('test_123_433.json');
        $jsonFile = new JsonFile($file);

        $jsonFile->set('name', 'codesinging/pin-admin');
        $jsonFile->set('author.name', 'codesinging');

        self::assertEquals('codesinging/pin-admin', $jsonFile->get('name'));
        self::assertEquals(['name' => 'codesinging'], $jsonFile->get('author'));
        self::assertEquals('codesinging', $jsonFile->get('author.name'));

    }

    public function testMerge()
    {
        $file = base_path('test_123_433.json');
        $jsonFile = new JsonFile($file);

        $jsonFile->set('author.name', 'codesinging');

        self::assertEquals(['name' => 'codesinging'], $jsonFile->get('author'));

        $jsonFile->merge('author', ['email' => 'codesinging@gmail.com']);

        self::assertEquals(['name' => 'codesinging', 'email' => 'codesinging@gmail.com'], $jsonFile->get('author'));
        self::assertEquals('codesinging@gmail.com', $jsonFile->get('author.email'));
    }

    public function testSort()
    {
        $file = base_path('test_123_433.json');
        $jsonFile = new JsonFile($file);

        $jsonFile->set('lists', ['b' => 'bb', 'a' => 'aa', 'c' => 'cc']);

        self::assertEquals(['b', 'a', 'c'], array_keys($jsonFile->get('lists')));

        $jsonFile->sort('lists');

        self::assertEquals(['a', 'b', 'c'], array_keys($jsonFile->get('lists')));
    }

    public function testWrite()
    {
        $file = base_path('test_123_433.json');
        $jsonFile = new JsonFile($file);

        self::assertFileDoesNotExist($file);

        $jsonFile->write();

        self::assertFileExists($file);

        File::delete($file);
    }

    public function testData()
    {
        $file = base_path('test_123_433.json');
        $jsonFile = new JsonFile($file);

        $jsonFile->set('name', 'codesinging/pin-admin');

        self::assertEquals(['name' => 'codesinging/pin-admin'], $jsonFile->data());
    }

    public function testFilename()
    {
        $file = base_path('test_123_433.json');
        $jsonFile = new JsonFile($file);

        self::assertEquals($file, $jsonFile->filename());
    }
}

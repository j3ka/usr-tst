<?php

namespace Tests\App\Form;

use App\Form\UsersImportForm;
use PHPUnit\Framework\TestCase;

class UsersImportFormTest extends TestCase
{
    public const VALID_FILE_NAME = __DIR__.'/test.txt';

    public const INVALID_FILE_NAME = __DIR__.'/test.php';

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::VALID_FILE_NAME, 'test');
        file_put_contents(self::INVALID_FILE_NAME, '<?php');
    }

    public static function tearDownAfterClass(): void
    {
        foreach ([self::VALID_FILE_NAME, self::INVALID_FILE_NAME] as $fileName) {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }

    public function testEmptyData()
    {
        $form = new UsersImportForm();
        $form->validate([]);

        $this->assertFalse($form->isValid());
    }

    public function testInvalidFile()
    {
        $form = new UsersImportForm();
        $form->validate([
            'users' => ['tmp_name' => self::INVALID_FILE_NAME],
        ]);

        $this->assertFalse($form->isValid());
    }

    public function testValidate()
    {
        $form = new UsersImportForm();
        $form->validate([
            'users' => ['tmp_name' => self::VALID_FILE_NAME],
        ]);

        $this->assertTrue($form->isValid());
    }
}
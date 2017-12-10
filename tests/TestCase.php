<?php

namespace pointdnd\comments\tests;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();

        $this->setupTestDbData();

        $this->createRuntimeFolder();
    }

    protected function tearDown()
    {
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'request' => [
                    'hostInfo' => 'http://domain.com',
                    'scriptUrl' => 'index.php',
                ],
                'user' => [
                    'identityClass' => 'pointdnd\comments\tests\data\User',
                ],
                'i18n' => [
                    'translations' => [
                        'pointdnd.comments' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => '@pointdnd/comments/messages',
                        ],
                    ],
                ],
            ],
            'modules' => [
                'comment' => [
                    'class' => 'pointdnd\comments\Module',
                    'userIdentityClass' => '',
                    'controllerNamespace' => 'pointdnd\comments\tests\data',
                ],
            ],
        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * Setup tables for test ActiveRecord
     */
    protected function setupTestDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :

        $db->createCommand()->createTable('comment', [
            'id' => 'pk',
            'entity' => 'char(10) not null',
            'entityId' => 'integer not null',
            'content' => 'text not null',
            'parentId' => 'integer null',
            'level' => 'smallint not null default 1',
            'createdBy' => 'integer not null',
            'updatedBy' => 'integer not null',
            'relatedTo' => 'string(500) not null',
            'url' => 'text',
            'status' => 'smallint not null default 1',
            'createdAt' => 'integer not null',
            'updatedAt' => 'integer not null',
        ])->execute();

        $db->createCommand()->createTable('user', [
            'id' => 'pk',
            'username' => 'string',
            'email' => 'string',
        ])->execute();

        $db->createCommand()->createTable('post', [
            'id' => 'pk',
            'title' => 'string',
            'description' => 'string',
            'createdAt' => 'integer',
        ])->execute();

        // Data :

        $db->createCommand()->insert('comment', [
            'entity' => '025c69f4',
            'entityId' => 1,
            'content' => 'test content',
            'createdBy' => 1,
            'updatedBy' => 1,
            'relatedTo' => 'test comment',
            'url' => 'custom-url',
            'createdAt' => time(),
            'updatedAt' => time(),
        ])->execute();

        $db->createCommand()->insert('user', [
            'username' => 'John Doe',
            'email' => 'johndoe@domain.com',
        ])->execute();

        $db->createCommand()->insert('post', [
            'title' => 'Post Title',
            'description' => 'some description',
            'createdAt' => time(),
        ])->execute();
    }

    /**
     * Create runtime folder
     */
    protected function createRuntimeFolder()
    {
        FileHelper::createDirectory(dirname(__DIR__) . '/tests/runtime');
    }
}

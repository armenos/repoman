<?php
/**
 * This must be installed somewhere inside of your MODx web root.
 *
 * To run these tests, pass the test directory as the 1st argument to phpunit:
 *
 *   phpunit path/to/tests
 *
 * or if you're having any trouble running phpunit, download its .phar file, and
 * then run the tests like this:
 *
 *  php phpunit.phar path/to/tests
 *
 */
namespace Repoman;

use Repoman\Repoman;
use Repoman\Utils;
use Repoman\Config;
use Repoman\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Output\OutputInterface;

class graphTest extends \PHPUnit_Framework_TestCase
{
    // Must be static because we set it up inside a static function
    public static $modx;
    public static $repoman;

    /**
     * Load up MODX for our tests.
     *
     */
    public static function setUpBeforeClass()
    {

        self::$modx = Utils::getMODX();
        self::$modx->initialize('mgr');
        self::$repoman = new Repoman(self::$modx, new Config());
    }

    public static function tearDownAfterClass()
    {
//        $criteria = self::$modx->newQuery('modChunk');
//        $criteria->where(array('name:LIKE' => 'test-%'));
//        $results = self::$modx->getCollection('modChunk', $criteria);
//        foreach ($results as $r) {
//            $r->remove();
//        }
    }

    //

    public function testGraph()
    {
        $out = self::$repoman->graph(null,array());
        //error_log($out);
        $this->assertTrue((bool) strpos($out,'All Available Classes'));

    }

    public function testGraphChunk()
    {
        $out = self::$repoman->graph('modChunk',array());
        //error_log($out);
        $this->assertTrue((bool) strpos($out,'property_preprocess'));
        $this->assertTrue((bool) strpos($out,'CategoryAcls'));

    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Path is not a directory
     */
    public function testMissingDir()
    {
        $out = self::$repoman->graph(null,array('load'=>'/does/not/exist'));
    }

    /**
     * @expectedException        \Exception
     * @expectedExceptionMessage Classname not found
     */
    public function testInvalidClassname()
    {
        $out = self::$repoman->graph('class_does_not_exist');
    }
}
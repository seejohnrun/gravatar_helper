<?php

define('BASEPATH', true); // allow script access
require dirname(__FILE__) . '/../helpers/gravatar_helper.php';

class gravatar_helper_test extends PHPUnit_Framework_TestCase {

    private static $base = 'http://gravatar.com/avatar.php';

    function testEmailGeneration() {
        $this->assertEquals(Gravatar_helper::from_email('john@crepezzi.com'), self::$base . '?gravatar_id=5b480b6a4d63cffb5a6a494ee599333f');
    }

    function testHashGeneration() {
        $this->assertEquals(Gravatar_helper::from_hash('1234'), self::$base . '?gravatar_id=1234');
    }

    function testWithRating() {
        $this->assertEquals(Gravatar_helper::from_hash('1', 'X'), self::$base . '?gravatar_id=1&rating=X');
    }

    function testWithSize() {
        $this->assertEquals(Gravatar_helper::from_hash('1', null, 80), self::$base . '?gravatar_id=1&size=80');
    }

    function testWithDefault() {
        $url = 'http://goo.gl/HOtWh';
        $this->assertEquals(Gravatar_helper::from_hash('1', null, null, $url), self::$base . "?gravatar_id=1&default=$url");
    }

    function testProfileWithEmail() {
        $res = Gravatar_helper::profile_from_email('seejohnrun@gmail.com');
        $this->assertObjectHasAttribute('id', $res);
    }

    function testProfileWithBadEmail() {
        $res = Gravatar_helper::profile_from_email('john.crepezzi@a.com');
        $this->assertNull($res);
    }

}

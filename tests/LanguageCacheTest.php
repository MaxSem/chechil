<?php


class LanguageCacheTest extends PHPUnit_Framework_TestCase {
    /**
     * @dataProvider provideInvalidLanguageThrows
     * @expectedException Chechil\InvalidLanguageCodeException
     */
    public function testInvalidLanguageThrows($language) {
        $cache = new Chechil\LanguageCache();
        $cache->get($language);
    }

    public function provideInvalidLanguageThrows() {
        return array(
            array('<nope>'),
            array(null),
            array("I don't exist!"),
            array(''),
            array('nonexistent_language'),
        );
    }
}

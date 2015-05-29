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

    public function testGetLanguages() {
        $cache = new Chechil\LanguageCache();
        $prevSize = count($cache->getLanguages());
        $cache->registerCustomLanguage('fnord', '');
        $cache->registerCustomLanguage('c', ''); // Override
        $list = $cache->getLanguages();
        $this->assertEquals($prevSize + 1, count($list));

        // Test the list contains a few languages
        $this->assertContains('php', $list);
        $this->assertContains('html5', $list);
        $this->assertContains('fnord', $list);
    }
}

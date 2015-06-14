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

    /**
     * @dataProvider provideGetLanguageNames
     */
    public function testGetLanguageNames($code, $expected) {
        $cache = new Chechil\LanguageCache();
        $cache->registerCustomLanguage('dummy', __DIR__ . '/data/dummyLanguage.php');
        $list = $cache->getLanguageNames();
        if (!$expected) {
            $this->assertFalse(isset($list[$code]), "Language $code shouldn't exist");
        }
        $this->assertEquals($expected, $list[$code]);
    }

    public function provideGetLanguageNames() {
        return array(
            array('php', 'PHP'),
            array('cpp', 'C++'),
            array('dummy', 'Dummy!'),
            array('bogus', false),
        );
    }

	public function testRegExps() {
	        $cache = new Chechil\LanguageCache();
		$list = $cache->getLanguages();

		function checkLanguage( $testcase, $cache, $lang ) {
			$langData = $cache->get( $lang );

			switch ( $lang ) {
			case 'dcs':
			case 'jcl':
			case 'qml':
				/* These are currently broken */
				return;
			}

			if( isset( $langData['COMMENT_REGEXP'] ) ) {
				foreach ( $langData['COMMENT_REGEXP'] as $key => $regexp ) {
					$testcase->assertTrue( @preg_match( $regexp, null ) !== false, 'Invalid COMMENT_REGEXP for: ' . $lang );
				}
			}
			if( isset( $langData['ESCAPE_REGEXP'] ) ) {
				foreach ( $langData['ESCAPE_REGEXP'] as $key => $regexp ) {
					$testcase->assertTrue( @preg_match( $regexp, null ) !== false, 'Invalid ESCAPE_REGEXP for: ' . $lang );
				}
			}
		}

		foreach ( $list as $lang ) {
			checkLanguage( $this, $cache, $lang );
		}

	}
}

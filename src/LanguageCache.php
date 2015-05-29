<?php
/**
 * Copyright (c) 2015 Chechil contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

namespace Chechil;

use InvalidArgumentException;

/**
 * Manages a LRU cache of language information
 * Cache size is limited to avoid memory overuse in long-running scripts
 */
class LanguageCache {
    /** Default number of languages to store in LRU cache */
    const DEFAULT_CACHE_SIZE = 5;

    /** @var array Cache, array(language code => array(language data)) in reverse order (first element is oldest) */
    private $cache = array();

    /** @var int Maximum cache size */
    private $cacheSize = self::DEFAULT_CACHE_SIZE;

    /** @var array List of custom languages: array(language code => filename) */
    private $customLanguages = array();

    /**
     * Retrieves language information
     *
     * @param string $languageCode
     * @return array
     * @throws InvalidLanguageCodeException
     */
    public function get($languageCode) {
        if (isset($this->cache[$languageCode])) {
            $result = $this->cache[$languageCode];
            // Change position in LRU cache
            unset($this->cache[$languageCode]);
            $this->cache[$languageCode] = $result;

            return $result;
        }
        $result = $this->loadLanguage($languageCode);
        if (count($this->cache) >= $this->cacheSize) {
            array_shift($this->cache);
        }
        $this->cache[] = $result;

        return $result;
    }

    /**
     * Sets maximum cache size
     *
     * @param int $size
     */
    public function setCacheSize($size) {
        if (!is_int($size) || $size < 0) {
            throw new InvalidArgumentException(__METHOD__ . "(): invalid cache size '$size");
        }
        $this->cacheSize = $size;
        // Truncate on shrink
        while (count($this->cache) > $this->cacheSize) {
            array_shift($this->cache);
        }
    }

    /**
     * Registers a custom language definition
     *
     * @param string $code
     * @param string $fileName
     * @throws InvalidLanguageCodeException
     */
    public function registerCustomLanguage($code, $fileName) {
        $this->validateLanguageCode($code);
        $this->customLanguages[$code] = $fileName;
    }

    /**
     * Returns a list of supported language codes
     * @return array
     */
    public function getLanguages() {
        $result = array();
        foreach (glob(__DIR__ . '/geshi/*.php') as $file) {
            $result[] = pathinfo($file, PATHINFO_FILENAME);
        }
        if ($this->customLanguages) {
            $result = array_merge($result, array_keys($this->customLanguages));
            sort($result);
            $result = array_unique($result);
        }
        return $result;
    }

    /**
     * Loads language information from file
     *
     * @param string $languageCode
     * @return array
     * @throws InvalidLanguageCodeException
     */
    private function loadLanguage($languageCode) {
        $this->validateLanguageCode($languageCode);
        if (isset($this->customLanguages[$languageCode])) {
            $fileName = $this->customLanguages[$languageCode];
        } else {
            $fileName = __DIR__ . "/geshi/$languageCode.php";
        }
        if (!is_readable($fileName)) {
            throw new InvalidLanguageCodeException($languageCode);
        }

        $language_data = array();
        require $fileName;

        return $language_data;
    }

    /**
     * Throws an exception if given language code is not valid
     *
     * @param string $code
     * @throws InvalidLanguageCodeException
     */
    private function validateLanguageCode($code) {
        if(!preg_match('#^[a-z0-9_-]*$#', $code)) {
            throw new InvalidLanguageCodeException($code);
        }
    }
}

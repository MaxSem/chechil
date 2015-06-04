<?php
/**
 * This script generates a JSON blob with human-readable names of all bundled languages
 * Usage: save its output to src/data/languageNames.json
 *
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


error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../vendor/autoload.php';

// Hack: load constants
new Chechil\Highlighter();
$cache = new Chechil\LanguageCache();

$result = array();
foreach ($cache->getLanguages() as $lang) {
    $data = $cache->get($lang);
    $result[$lang] = $data['LANG_NAME'];
}

echo json_encode($result, JSON_PRETTY_PRINT);

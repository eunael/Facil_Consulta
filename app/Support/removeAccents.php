<?php

/**
 * Remove accents from a string
 *
 * @param string $string
 * @return string
 */
function removeAccents(string $string): string {
    return transliterator_transliterate('Any-Latin; Latin-ASCII;', $string);
};

<?php

function removeDrDra(string $string): string {
    return trim(preg_replace('/\b(dr|dra)\b/i', '', $string));
}

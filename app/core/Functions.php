<?php


function randChar($int = 15)
{
    return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", $int)), 0, $int);
}
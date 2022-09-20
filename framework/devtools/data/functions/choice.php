<?php

function choice(string $question,string $reactions = '[Y/n]',array $true_choices_in_uppercase = ['YES','Y']) :bool {
    $choice = readline("{$question} {$reactions} ");                // simple Enter, nothing
    return in_array(strtoupper($choice),array_merge($true_choices_in_uppercase, [ '' ]));
}
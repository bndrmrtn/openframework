<?php

function choice(string $question,string $reactions = '[Y/n]',array $true_choices_in_uppercase = ['YES','Y']) :bool {
    $choice = readline("{$question} {$reactions} ");
    return in_array(strtoupper($choice),$true_choices_in_uppercase);
}
<?php
Header::statuscode($code);

Components::import('MDoc');

MDocComponent::load([
    'title' => $code . ' - ' . $title,
    'description' => 'Something went wrong, <a href="' . BASE_URL . '" rlink>Home</a>.',
]);
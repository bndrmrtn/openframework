<?php
Header::statuscode($code);

Components::import('mdoc');

MdocComponent::load([
    'title' => $code . ' - ' . $title,
    'description' => 'Something went wrong, <a href="' . BASE_URL . '" rlink>Home</a>.',
]);

<?php
namespace core\interfaces;


interface ViewInterface
{
    function render(string $view, array $params, bool $return): ?string;
    function getViewFile(string $view): ?string;
    function getLayOutFile(string $layout): ?string;
}
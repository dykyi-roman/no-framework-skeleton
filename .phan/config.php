<?php
{
    return [
        'target_php_version' => null,
        'directory_list' => [
            'src',
            'vendor'
        ],
        "exclude_analysis_directory_list" => [
            'vendor/',
            'data/',
        ],
        'plugins' => [
            'AlwaysReturnPlugin',
            'UnreachableCodePlugin',
            'DollarDollarPlugin',
            'DuplicateArrayKeyPlugin',
            'PregRegexCheckerPlugin',
            'PrintfCheckerPlugin',
        ],
    ];
}
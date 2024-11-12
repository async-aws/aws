<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect(null, 300))
    ->setCacheFile(__DIR__.'/.cache/php-cs-fixer/.php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_indentation' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => true,
        'class_attributes_separation' => ['elements' => ['property' => 'one', 'method' => 'one']],
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'none'],
        'declare_strict_types' => false,
        'header_comment' => false,
        'is_null' => true,
        'lowercase_cast' => true,
        'modernize_types_casting' => true,
        'multiline_comment_opening_closing' => true,
        'native_constant_invocation' => ['include' => ['ReturnTypeWillChange', 'UUID_TYPE_RANDOM']],
        'native_function_invocation' => ['include' => ['@compiler_optimized']],
        'no_alias_functions' => true,
        'no_extra_blank_lines' => ['tokens' => [
            'attribute',
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
        ]],
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_in_blank_line' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'ordered_class_elements' => ['sort_algorithm' => 'none'],
        'ordered_imports' => true,
        'ordered_interfaces' => true,
        'php_unit_dedicate_assert_internal_type' => ['target' => 'newest'],
        'php_unit_expectation' => ['target' => 'newest'],
        'php_unit_mock' => ['target' => 'newest'],
        'php_unit_mock_short_will_return' => true,
        'php_unit_no_expectation_annotation' => ['target' => 'newest'],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        'phpdoc_line_span' => ['method' => 'multi', 'property' => 'multi'],
        'phpdoc_to_comment' => false,
        'short_scalar_cast' => true,
        'single_trait_insert_per_statement' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => ['after_heredoc' => true, 'elements' => ['array_destructuring', 'arrays']],
        'visibility_required' => true,
    ])
    ->setFinder($finder)
;

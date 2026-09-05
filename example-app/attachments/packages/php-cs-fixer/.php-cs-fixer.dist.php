<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// =============================================================================
// КОНФИГУРАЦИЯ PHP CS FIXER
// =============================================================================
// Этот файл настраивает автоматическое форматирование кода согласно стандартам
// =============================================================================

/**
 * Правила форматирования кода.
 *
 * @var array<string, mixed> $rules
 */
$rules = [
    // =========================================================================
    // БАЗОВЫЕ СТАНДАРТЫ
    // =========================================================================

    /*
     * @PSR12 - применяет стандарт PSR-12 (расширенный PSR-2)
     * Обеспечивает совместимость с современными PHP-стандартами
     */
    '@PSR12' => true,

    /*
     * @PhpCsFixer - основной набор правил php-cs-fixer
     * Включает дополнительные правила, не входящие в PSR
     */
    '@PhpCsFixer' => true,

    /*
     * @PER-CS2x0 - правила PER Coding Style 2.x
     * Современный стандарт, основанный на PSR-12
     *
     * @link https://www.php-fig.org/per/coding-style/
     */
    '@PER-CS2x0' => true,

    // =========================================================================
    // СИНТАКСИС МАССИВОВ
    // =========================================================================

    /*
     * array_syntax - преобразует объявления массивов
     * 'short' - использовать короткий синтаксис [] вместо array()
     */
    'array_syntax' => ['syntax' => 'short'],

    /*
     * array_indentation - выравнивает элементы массивов
     * Улучшает читаемость многострочных массивов
     */
    'array_indentation' => true,

    /*
     * no_multiline_whitespace_around_double_arrow - убирает лишние пробелы вокруг =>
     * Обеспечивает единообразное форматирование массивов
     */
    'no_multiline_whitespace_around_double_arrow' => true,

    /*
     * trim_array_spaces - обрезает пробелы внутри скобок массивов
     * Убирает пробелы после [ и перед ]
     */
    'trim_array_spaces' => true,

    // =========================================================================
    // ФОРМАТИРОВАНИЕ КОДА
    // =========================================================================

    /*
     * indentation_type - обеспечивает правильные отступы
     * Использует пробелы для отступов (по умолчанию 4 пробела)
     */
    'indentation_type' => true,

    /*
     * line_ending - унифицирует окончания строк
     * Использует Unix-стиль (\n) для всех файлов
     */
    'line_ending' => true,

    /*
     * no_trailing_whitespace - удаляет пробелы в конце строк
     * Обеспечивает чистоту кода без висячих пробелов
     */
    'no_trailing_whitespace' => true,

    /*
     * no_whitespace_in_blank_line - убирает пробелы в пустых строках
     * Пустые строки должны быть действительно пустыми
     */
    'no_whitespace_in_blank_line' => true,

    // =========================================================================
    // РАЗДЕЛЕНИЕ ЭЛЕМЕНТОВ КЛАССА
    // =========================================================================

    /*
     * class_attributes_separation - управляет разделением элементов класса
     * 'method' => 'one' - методы разделяются одной пустой строкой
     */
    'class_attributes_separation' => [
        'elements' => [
            'method' => 'one',
            'trait_import' => 'one',
        ]
    ],

    /*
     * ordered_class_elements - сортирует элементы класса
     * Упорядочивает константы, свойства, методы в логическом порядке
     */
    'ordered_class_elements' => [
        'order' => [
            'use_trait',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public',
            'method_protected',
            'method_private',
        ],
    ],

    /*
     * modifier_keywords - требует явного указания видимости
     * Все свойства и методы должны иметь public/protected/private
     */
    'modifier_keywords' => ['elements' => ['property', 'method', 'const']],

    // =========================================================================
    // ИМПОРТЫ И ПРОСТРАНСТВА ИМЕН
    // =========================================================================

    /*
     * no_unused_imports - удаляет неиспользуемые импорты
     * Очищает код от лишних use-выражений
     */
    'no_unused_imports' => true,

    /*
     * ordered_imports - сортирует импорты по алфавиту
     * Улучшает навигацию по импортам в начале файла
     */
    'ordered_imports' => ['sort_algorithm' => 'alpha'],

    /*
     * single_import_per_statement - каждый импорт на отдельной строке
     * Запрещает объединение импортов через запятую
     */
    'single_import_per_statement' => true,

    /*
     * no_leading_import_slash - убирает слеш в начале импортов
     * use \Exception; -> use Exception;
     */
    'no_leading_import_slash' => true,

    /*
     * global_namespace_import - контролирует импорт в глобальное пространство
     * Управляет импортом классов, функций, констант
     */
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ],

    // =========================================================================
    // ОПЕРАТОРЫ И ВЫРАЖЕНИЯ
    // =========================================================================

    /*
     * binary_operator_spaces - выравнивает бинарные операторы
     * Добавляет пробелы вокруг операторов (=, +, -, и т.д.)
     */
    'binary_operator_spaces' => true,

    /*
     * strict_comparison - преобразует == в === и != в !==
     * Обеспечивает строгое сравнение типов
     * ! ОТКЛЮЧЕНО ТАК КАК ВЛИЯЕТ НА ИМЕНОВАННЫЕ АРГУМЕНТЫ (ФАТАЛЬНАЯ ОШИБКА)
     */
    'strict_comparison' => false,

    /*
     * strict_param - требует строгие типы для функций
     * Добавляет strict_types declaration
     * ! ОТКЛЮЧЕНО ТАК КАК ВЛИЯЕТ НА ИМЕНОВАННЫЕ АРГУМЕНТЫ (ФАТАЛЬНАЯ ОШИБКА)
     */
    'strict_param' => false,

    /*
     * declare_strict_types - добавляет declare(strict_types=1);
     * Включает строгую типизацию для файла
     */
    'declare_strict_types' => true,

    /*
     * ternary_operator_spaces - форматирует тернарные операторы
     * Добавляет пробелы вокруг ? и :
     */
    'ternary_operator_spaces' => true,

    /*
     * ternary_to_null_coalescing - преобразует isset в ??
     * isset($a) ? $a : $b -> $a ?? $b
     */
    'ternary_to_null_coalescing' => true,

    /*
     * unary_operator_spaces - форматирует унарные операторы
     * Убирает пробелы между ++/-- и переменной
     */
    'unary_operator_spaces' => true,

    // =========================================================================
    // ПУСТЫЕ СТРОКИ ПЕРЕД КОНСТРУКЦИЯМИ
    // =========================================================================

    /*
     * blank_line_before_statement - добавляет пустую строку перед конструкциями
     * Улучшает читаемость кода, отделяя логические блоки
     */
    'blank_line_before_statement' => [
        'statements' => [
            'return',    // перед return
            'if',        // перед if
            'for',       // перед for
            'foreach',   // перед foreach
            'while',     // перед while
            'switch',    // перед switch
            'break',     // перед break
            'continue',  // перед continue
            'declare',   // перед declare
            'try',       // перед try
            'throw',     // перед throw
            'do',        // перед do
        ],
    ],

    /*
     * blank_line_after_namespace - добавляет пустую строку после namespace
     * Отделяет объявление пространства имен от остального кода
     */
    'blank_line_after_namespace' => true,

    /*
     * blank_line_after_opening_tag - добавляет пустую строку после <?php
     * Отделяет открывающий тег от остального кода
     */
    'blank_line_after_opening_tag' => true,

    // =========================================================================
    // КОНТРОЛЬНЫЕ СТРУКТУРЫ (IF, FOR, WHILE)
    // =========================================================================

    /*
     * include - форматирует include/require
     * Убирает лишние скобки вокруг файлов
     */
    'include' => true,

    /*
     * no_extra_blank_lines - убирает лишние пустые строки
     * Оставляет не более одной пустой строки подряд
     */
    'no_extra_blank_lines' => [
        'tokens' => [
            'extra',
            'throw',
            'use',
            'curly_brace_block',
            'parenthesis_brace_block',
            'square_brace_block',
            'return',
        ],
    ],

    /*
     * no_useless_else - удаляет бесполезный else
     * Если в if есть return, else не нужен
     */
    'no_useless_else' => true,

    /*
     * no_useless_return - удаляет бесполезный return в конце функции
     * Если return последний, его можно опустить
     */
    'no_useless_return' => true,

    /*
     * yoda_style - запрещает стиль Yoda
     * if ($a == null) -> if (null == $a)
     * Отключаем, так как это ухудшает читаемость
     */
    'yoda_style' => false,

    // =========================================================================
    // ФУНКЦИИ И МЕТОДЫ
    // =========================================================================

    /*
     * method_argument_space - форматирует аргументы методов
     * Управляет пробелами вокруг скобок и запятых
     */
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => false,
    ],

    /*
     * no_spaces_after_function_name - убирает пробелы после имени функции
     * function_name () -> function_name()
     */
    'no_spaces_after_function_name' => true,

    /*
     * return_type_declaration - форматирует объявление типа возврата
     * function(): string -> function():string
     */
    'return_type_declaration' => ['space_before' => 'none'],

    /*
     * void_return - добавляет void в возвращаемые типы
     * Требует явного указания void для функций без return
     */
    'void_return' => true,

    /*
     * nullable_type_declaration - форматирует объявление nullable типов
     * Убирает пробел перед ? в ?string
     */
    'nullable_type_declaration' => true,

    // =========================================================================
    // ДОК-БЛОКИ (PHPDOC)
    // =========================================================================

    /*
     * align_multiline_comment - выравнивает многострочные комментарии
     * Приводит комментарии к единому форматированию
     */
    'align_multiline_comment' => true,

    /*
     * general_phpdoc_tag_rename - нормализует теги в док-блоках
     * Например, @inheritDoc -> @inheritdoc
     */
    'general_phpdoc_tag_rename' => true,

    /*
     * multiline_comment_opening_closing - форматирует открывающие/закрывающие строки
     * Обеспечивает правильное форматирование многострочных комментариев
     */
    'multiline_comment_opening_closing' => true,

    'no_empty_phpdoc' => true,

    // =========================================================================
    // ПАРАМЕТРЫ В PHPDOC
    // =========================================================================

    /*
     * phpdoc_add_missing_param_annotation - добавляет недостающие @param
     * 'only_untyped' => true - только для нетипизированных параметров
     */
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],

    /*
     * phpdoc_align - выравнивает теги в док-блоках
     * Создает "колонки" из @param, @return и т.д.
     */
    'phpdoc_align' => true,

    /*
     * phpdoc_annotation_without_dot - удаляет точки в конце аннотаций
     * В описаниях @param, @return точки не ставятся
     */
    'phpdoc_annotation_without_dot' => true,

    /*
     * phpdoc_no_access - удаляет @access
     * Современный стандарт не рекомендует использовать @access
     */
    'phpdoc_no_access' => true,

    /*
     * phpdoc_no_empty_return - удаляет @return void если он не нужен
     * Убирает избыточные аннотации
     */
    'phpdoc_no_empty_return' => true,

    /*
     * phpdoc_no_package - удаляет @package
     * namespace лучше отражает организацию кода
     */
    'phpdoc_no_package' => true,

    // =========================================================================
    // ОТСТУПЫ И ФОРМАТИРОВАНИЕ PHPDOC
    // =========================================================================

    /*
     * phpdoc_indent - выравнивает отступы в док-блоках
     * Обеспечивает единообразное форматирование
     */
    'phpdoc_indent' => true,

    /*
     * phpdoc_inline_tag_normalizer - нормализует inline-теги
     * Приводит {@inheritdoc} к стандартному виду
     */
    'phpdoc_inline_tag_normalizer' => true,

    /*
     * phpdoc_line_span - управляет переносом строк в док-блоках
     * Аннотации должны быть на отдельных строках
     */
    'phpdoc_line_span' => [
        'const' => 'single',
        'method' => 'single',
        'property' => 'single',
    ],

    /*
     * phpdoc_no_useless_inheritdoc - удаляет бесполезный @inheritdoc
     * Убирает @inheritdoc там, где он не нужен
     */
    'phpdoc_no_useless_inheritdoc' => true,

    // =========================================================================
    // ПОРЯДОК АННОТАЦИЙ
    // =========================================================================

    /*
     * phpdoc_order - сортирует аннотации в док-блоках
     * Стандартный порядок: @param, @return, @throws
     */
    'phpdoc_order' => true,

    /*
     * phpdoc_separation - добавляет пустые строки между секциями
     * Отделяет @param от @return и т.д.
     */
    'phpdoc_separation' => true,

    /*
     * phpdoc_single_line_var_spacing - форматирует @var
     * Убирает лишние пробелы в @var
     */
    'phpdoc_single_line_var_spacing' => true,

    /*
     * phpdoc_summary - проверяет наличие краткого описания
     * Требует, чтобы док-блок начинался с краткого описания
     */
    'phpdoc_summary' => true,

    /*
     * phpdoc_types - форматирует указание типов
     * Приводит типы к стандартному виду (array -> array, boolean -> bool)
     */
    'phpdoc_types' => true,

    /*
     * phpdoc_types_order - сортирует типы
     * Упорядочивает объединения типов (string|null -> string|null)
     */
    'phpdoc_types_order' => [
        'null_adjustment' => 'always_last',
        'sort_algorithm' => 'alpha',
    ],

    // =========================================================================
    // ПРЕОБРАЗОВАНИЕ ДОК-БЛОКОВ
    // =========================================================================

    // используется как обычный комментарий, превращает в /
    'phpdoc_to_comment' => true,

    /*
     * phpdoc_to_param_type - преобразует @param в type hints
     * Добавляет type hints на основе аннотаций
     */
    'phpdoc_to_param_type' => true,

    /*
     * phpdoc_to_property_type - преобразует @var в type hints свойств
     * Добавляет type hints для свойств на основе @var
     */
    'phpdoc_to_property_type' => true,

    /*
     * phpdoc_to_return_type - преобразует @return в type hints
     * Добавляет return type на основе @return
     */
    'phpdoc_to_return_type' => true,

    // =========================================================================
    // ОЧИСТКА PHPDOC
    // =========================================================================

    /*
     * phpdoc_trim - обрезает лишние пробелы в док-блоках
     * Удаляет пустые строки в начале и конце
     */
    'phpdoc_trim' => true,

    /*
     * phpdoc_trim_consecutive_blank_line_separation - удаляет лишние пустые строки
     * Оставляет только одну пустую строку между элементами
     */
    'phpdoc_trim_consecutive_blank_line_separation' => true,

    /*
     * phpdoc_var_annotation_correct_order - исправляет порядок @var
     * @var $var Type -> @var Type $var
     */
    'phpdoc_var_annotation_correct_order' => true,

    /*
     * phpdoc_var_without_name - убирает имя переменной из @var
     * @var Type $var -> @var Type
     */
    'phpdoc_var_without_name' => true,

    // =========================================================================
    // СТРОКИ
    // =========================================================================

    /*
     * single_quote - использует одинарные кавычки
     * Двойные кавычки только при наличии переменных
     */
    'single_quote' => true,

    /*
     * heredoc_indentation - форматирует отступы в heredoc
     * Обеспечивает правильные отступы в heredoc/nowdoc
     */
    'heredoc_indentation' => true,

    /*
     * no_binary_string - запрещает бинарные строки
     * Убирает префикс b перед строками
     */
    'no_binary_string' => true,

    // =========================================================================
    // ПРОЧИЕ ПОЛЕЗНЫЕ ПРАВИЛА
    // =========================================================================

    /*
     * combine_consecutive_issets - объединяет последовательные isset
     * isset($a) && isset($b) -> isset($a, $b)
     */
    'combine_consecutive_issets' => true,

    /*
     * combine_consecutive_unsets - объединяет последовательные unset
     * unset($a); unset($b); -> unset($a, $b);
     */
    'combine_consecutive_unsets' => true,

    /*
     * dir_constant - использует __DIR__ вместо dirname(__FILE__)
     * dirname(__FILE__) -> __DIR__
     */
    'dir_constant' => true,

    /*
     * modernize_strpos - использует str_contains вместо strpos
     * strpos !== false -> str_contains
     */
    'modernize_strpos' => true,

    /*
     * random_api_migration - использует современный random_int/random_bytes
     * Заменяет устаревшие rand/mt_rand
     */
    'random_api_migration' => true,

    /*
     * is_null - использует null === вместо is_null
     * is_null($var) -> null === $var
     */
    'is_null' => true,
];

// =============================================================================
// ПОИСК ФАЙЛОВ ДЛЯ ОБРАБОТКИ
// =============================================================================

/**
 * Настройка поиска файлов в проекте.
 */
$finder = (new Finder())
    // Директории для поиска
    ->in([
        __DIR__ . '/src',
    ])
    // Файлы для поиска
    ->append([
        __DIR__ . '/bin/console',
    ])
    // Добавляем поддержку дополнительных расширений
    ->name('*.php')
    ->name('*.php.dist')
    ->ignoreDotFiles(false)
    ->ignoreVCS(true);

// =============================================================================
// ВОЗВРАТ КОНФИГУРАЦИИ
// =============================================================================

return (new Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
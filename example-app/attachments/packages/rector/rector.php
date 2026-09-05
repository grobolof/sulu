<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector;
use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyFuncGetArgsCountRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyInArrayValuesRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector;
use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector;
use Rector\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector;

// =============================================================================
// КОНФИГУРАЦИЯ RECTOR
// =============================================================================
// Этот файл настраивает автоматическое обновление и рефакторинг PHP-кода
// =============================================================================

return RectorConfig::configure()
    // =========================================================================
    // ПУТИ ДЛЯ ОБРАБОТКИ
    // =========================================================================

    /*
     * Пути к директориям с кодом для обработки
     * Указываем только кастомный код, исключая системные папки
     */
    ->withPaths([
        __DIR__ . '/src',
    ])

    // =========================================================================
    // ИСКЛЮЧЕНИЯ
    // =========================================================================

    /*
    * Директории, которые нужно пропустить при обработке
    * Системные папки и сторонние библиотеки
    */
    ->withSkip([
        ReadOnlyPropertyRector::class,
    ])

    // =========================================================================
    // НАБОРЫ ПРАВИЛ (Sets)
    // =========================================================================

    /*
     * @php84 - набор правил для PHP 8.4
     * Включает правила для новых возможностей PHP 8.4
     */
    ->withPhpSets(
        php85: true
    )
    /*
     * LevelSetList::UP_TO_PHP_84 - обновление кода до PHP 8.5
     * Применяет все необходимые изменения для совместимости с PHP 8.5
     */
    ->withSets([
        LevelSetList::UP_TO_PHP_85,
    ])

    // =========================================================================
    // ИНДИВИДУАЛЬНЫЕ ПРАВИЛА (ТОЛЬКО НЕ ВХОДЯЩИЕ В НАБОРЫ)
    // =========================================================================

    /*
     * ТИПИЗАЦИЯ (Type Declaration)
     * =========================================================================
     * Правила для добавления и улучшения объявлений типов
     */

    /*
     * AddVoidReturnTypeWhereNoReturnRector - добавляет void для методов без return
     * Пример: function test() {} -> function test(): void {}
     */
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,

        /*
         * AddArrowFunctionReturnTypeRector - добавляет return type для стрелочных функций
         * Пример: fn() => 5 -> fn(): int => 5
         */
        AddArrowFunctionReturnTypeRector::class,

        /*
         * TypedPropertyFromStrictConstructorRector - добавляет типы свойств из конструктора
         * Пример: __construct(private $name) {} -> __construct(private string $name) {}
         */
        TypedPropertyFromStrictConstructorRector::class,
    ])

    // =========================================================================
    // КАЧЕСТВО КОДА (Code Quality)
    // =========================================================================

    /*
     * Правила для улучшения качества кода
     *
     * ПРИМЕЧАНИЕ: Из этого списка удалены все правила, которые уже входят в
     * LevelSetList::UP_TO_PHP_84, чтобы избежать дублирования:
     * - NullToStrictStringFuncCallArgRector
     * - ReadOnlyClassRector
     * - AddTypeToConstRector
     * - FirstClassCallableRector (заменен на ArrayToFirstClassCallableRector)
     */
    ->withRules([
        /*
         * InlineConstructorDefaultToPropertyRector - перемещает инициализацию свойств из конструктора
         * Пример: __construct() { $this->name = 'default'; } -> private string $name = 'default';
         */
        InlineConstructorDefaultToPropertyRector::class,

        /*
         * SimplifyEmptyCheckOnEmptyArrayRector - упрощает проверки пустых массивов
         * Пример: empty($array) ? true : false -> $array === []
         */
        SimplifyEmptyCheckOnEmptyArrayRector::class,

        /*
         * ForeachItemsAssignToEmptyArrayToAssignRector - упрощает присваивание в foreach
         */
        ForeachItemsAssignToEmptyArrayToAssignRector::class,

        /*
         * ChangeArrayPushToArrayAssignRector - преобразует array_push в []
         * Пример: array_push($array, 5) -> $array[] = 5
         */
        ChangeArrayPushToArrayAssignRector::class,

        /*
         * CompactToVariablesRector - преобразует compact('var1', 'var2') в ['var1' => $var1, 'var2' => $var2]
         */
        CompactToVariablesRector::class,

        /*
         * SimplifyFuncGetArgsCountRector - упрощает подсчет аргументов функций
         */
        SimplifyFuncGetArgsCountRector::class,

        /*
         * SimplifyInArrayValuesRector - упрощает in_array с array_values
         */
        SimplifyInArrayValuesRector::class,

        /*
         * SimplifyStrposLowerRector - упрощает strpos с strtolower
         */
        SimplifyStrposLowerRector::class,

        /*
         * BooleanNotIdenticalToNotIdenticalRector - упрощает логические сравнения
         */
        BooleanNotIdenticalToNotIdenticalRector::class,

        /*
         * FlipTypeControlToUseExclusiveTypeRector - улучшает контроль типов
         */
        FlipTypeControlToUseExclusiveTypeRector::class,

        /*
         * CombineIfRector - объединяет вложенные if
         * Пример: if ($a) { if ($b) { ... } } -> if ($a && $b) { ... }
         */
        CombineIfRector::class,

        /*
         * ExplicitBoolCompareRector - делает явные сравнения с bool
         */
        ExplicitBoolCompareRector::class,

        /*
         * SimplifyIfElseToTernaryRector - преобразует if-else в тернарные операторы
         */
        SimplifyIfElseToTernaryRector::class,

        /*
         * SimplifyIfNotNullReturnRector - упрощает проверки на null
         */
        SimplifyIfNotNullReturnRector::class,

        /*
         * ArrayKeyExistsTernaryThenValueToCoalescingRector - преобразует в ??
         */
        ArrayKeyExistsTernaryThenValueToCoalescingRector::class,

        /*
         * SwitchNegatedTernaryRector - упрощает отрицательные switch
         */
        SwitchNegatedTernaryRector::class,

        /*
         * TernaryEmptyArrayArrayDimFetchToCoalesceRector - преобразует в ?? []
         */
        TernaryEmptyArrayArrayDimFetchToCoalesceRector::class,
    ])

    // =========================================================================
    // УРОВНИ ОБРАБОТКИ (Levels)
    // =========================================================================

    /*
     * withTypeCoverageLevel - уровень покрытия типами (0-15)
     * 0 - минимальный, 15 - максимальный
     */
    ->withTypeCoverageLevel(5)
    /*
     * withDeadCodeLevel - уровень удаления мертвого кода (0-15)
     * 0 - минимальный, 15 - максимальный
     */
    ->withDeadCodeLevel(5)
    /*
     * withCodeQualityLevel - уровень улучшения качества кода (0-15)
     * 0 - минимальный, 15 - максимальный
     */
    ->withCodeQualityLevel(5)

    // =========================================================================
    // ДОПОЛНИТЕЛЬНЫЕ НАСТРОЙКИ
    // =========================================================================

    /*
     * withMemoryLimit - ограничение памяти
     * Увеличиваем для обработки больших проектов
     */
    ->withMemoryLimit('1024M')
    /*
     * withParallel - параллельная обработка
     * Ускоряет выполнение на многоядерных процессорах
     */
    ->withParallel(maxNumberOfProcess: 2)
    /*
     * withImportNames - импортировать имена классов
     * Добавляет use statements для классов
     */
    ->withImportNames(importNames: true, importDocBlockNames: true, importShortClasses: false);
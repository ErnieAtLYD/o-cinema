<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit913ab17c84b3bb55a0cc4e09c90acb83
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tribe\\Tests\\Pro\\' => 16,
            'Tribe\\Tests\\Modules\\Pro\\' => 24,
            'Tribe\\Events\\Pro\\Views\\' => 23,
            'Tribe\\Events\\Pro\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tribe\\Tests\\Pro\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests/_support',
        ),
        'Tribe\\Tests\\Modules\\Pro\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests/_support/Modules',
        ),
        'Tribe\\Events\\Pro\\Views\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views',
        ),
        'Tribe\\Events\\Pro\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Tribe',
        ),
    );

    public static $classMap = array (
        'Tribe\\Events\\Pro\\Rewrite\\Provider' => __DIR__ . '/../..' . '/src/Tribe/Rewrite/Provider.php',
        'Tribe\\Events\\Pro\\Rewrite\\Rewrite' => __DIR__ . '/../..' . '/src/Tribe/Rewrite/Rewrite.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Assets' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Assets.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Hooks' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Hooks.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Photo_View\\NavTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Photo_View/NavTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Photo_View\\Nav\\NextTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Photo_View/Nav/NextTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Photo_View\\Nav\\Next_DisabledTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Photo_View/Nav/Next_DisabledTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Photo_View\\Nav\\PrevTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Photo_View/Nav/PrevTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Photo_View\\Nav\\Prev_DisabledTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Photo_View/Nav/Prev_DisabledTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Week\\Grid_BodyTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Week/Grid_BodyTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Partials\\Week\\Grid_HeaderTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Partials/Week/Grid_HeaderTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Service_Provider' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Service_Provider.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Shortcodes\\Manager' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Shortcodes/Manager.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Shortcodes\\Shortcode_Abstract' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Shortcodes/Shortcode_Abstract.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Shortcodes\\Shortcode_Interface' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Shortcodes/Shortcode_Interface.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Shortcodes\\Tribe_Events' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Shortcodes/Tribe_Events.php',
        'Tribe\\Events\\Pro\\Views\\V2\\View_Filters' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/View_Filters.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\Hide_Recurring_Events_ToggleTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/Hide_Recurring_Events_ToggleTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\Location_Search_FieldTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/Location_Search_FieldTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\PhotoTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/PhotoTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\PhotoView\\Event\\PhotoEventDateTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/PhotoView/Event/PhotoEventDateTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\PhotoView\\Event\\PhotoEventTitleTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/PhotoView/Event/PhotoEventTitleTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\PhotoView\\Event\\PhotooEventtFeaturedImageTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/PhotoView/Event/PhotoEventFeaturedImageTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\PhotoView\\PhotoEventTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/PhotoView/PhotoEventTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\WeekView\\GridEvent\\WeekGridEventDateTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/WeekView/GridEvent/WeekGridEventDateTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\WeekView\\GridEvent\\WeekGridEventTitleTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/WeekView/GridEvent/WeekGridEventTitleTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\WeekView\\MobileEvents\\Event\\WeekMobileEventDateTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/WeekView/MobileEvents/Event/WeekMobileEventDateTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\WeekView\\MobileEvents\\Event\\WeekMobileEventTitleTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/WeekView/MobileEvents/Event/WeekMobileEventTitleTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\WeekView\\MobileEvents\\WeekMobileEventTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/WeekView/MobileEvents/WeekMobileEventTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\HTML\\WeekView\\WeekEventMultidayTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/HTML/WeekView/WeekEventMultidayTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\List_ViewTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Views/List_ViewTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\Partials\\Hide_Recurring_Events_Toggle' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Views/Partials/Hide_Recurring_Events_Toggle.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\Partials\\Location_Search_Field' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Views/Partials/Location_Search_Field.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\Photo_View' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Views/Photo_View.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\Photo_ViewTest' => __DIR__ . '/../..' . '/tests/views_integration/Tribe/Events/Pro/Views/V2/Views/Photo_ViewTest.php',
        'Tribe\\Events\\Pro\\Views\\V2\\Views\\Week_View' => __DIR__ . '/../..' . '/src/Tribe/Views/V2/Views/Week_View.php',
        'Tribe\\Tests\\Modules\\Pro\\Acceptance\\Options' => __DIR__ . '/../..' . '/tests/_support/Modules/Acceptance/Options.php',
        'Tribe\\Tests\\Modules\\Pro\\Acceptance\\Theme' => __DIR__ . '/../..' . '/tests/_support/Modules/Acceptance/Theme.php',
        'Tribe\\Tests\\Modules\\Pro\\Acceptance\\Widgets' => __DIR__ . '/../..' . '/tests/_support/Modules/Acceptance/Widgets.php',
        'Tribe\\Tests\\Pro\\Factories\\Event' => __DIR__ . '/../..' . '/tests/_support/Factories/Event.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit913ab17c84b3bb55a0cc4e09c90acb83::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit913ab17c84b3bb55a0cc4e09c90acb83::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit913ab17c84b3bb55a0cc4e09c90acb83::$classMap;

        }, null, ClassLoader::class);
    }
}

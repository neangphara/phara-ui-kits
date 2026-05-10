<?php

namespace Phara\UIKit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Phara\UIKit\Components\Accordion;
use Phara\UIKit\Components\AccordionItem;
use Phara\UIKit\Components\Autocomplete;
use Phara\UIKit\Components\Avatar;
use Phara\UIKit\Components\Badge;
use Phara\UIKit\Components\Breadcrumbs;
use Phara\UIKit\Components\BreadcrumbsItem;
use Phara\UIKit\Components\Button;
use Phara\UIKit\Components\Calendar;
use Phara\UIKit\Components\Callout;
use Phara\UIKit\Components\Card;
use Phara\UIKit\Components\Carousel;
use Phara\UIKit\Components\CarouselItem;
use Phara\UIKit\Components\Chart;
use Phara\UIKit\Components\Checkbox;
use Phara\UIKit\Components\CodeBlock;
use Phara\UIKit\Components\ColorPicker;
use Phara\UIKit\Components\Composer;
use Phara\UIKit\Components\ComposerAction;
use Phara\UIKit\Components\Context;
use Phara\UIKit\Components\ContextItem;
use Phara\UIKit\Components\ContextSeparator;
use Phara\UIKit\Components\CopyToClipboard;
use Phara\UIKit\Components\DatePicker;
use Phara\UIKit\Components\DocumentPrint;
use Phara\UIKit\Components\Dropdown;
use Phara\UIKit\Components\DropdownItem;
use Phara\UIKit\Components\Editor;
use Phara\UIKit\Components\FileUpload;
use Phara\UIKit\Components\Heading;
use Phara\UIKit\Components\Icon;
use Phara\UIKit\Components\Input;
use Phara\UIKit\Components\ListGroup;
use Phara\UIKit\Components\ListItem;
use Phara\UIKit\Components\Marquee;
use Phara\UIKit\Components\Modal;
use Phara\UIKit\Components\ModalClose;
use Phara\UIKit\Components\ModalTrigger;
use Phara\UIKit\Components\Navbar;
use Phara\UIKit\Components\NavbarItem;
use Phara\UIKit\Components\NavList;
use Phara\UIKit\Components\NavListGroup;
use Phara\UIKit\Components\NavListItem;
use Phara\UIKit\Components\Otp;
use Phara\UIKit\Components\Pagination;
use Phara\UIKit\Components\Pillbox;
use Phara\UIKit\Components\PillboxOption;
use Phara\UIKit\Components\Profile;
use Phara\UIKit\Components\Progress;
use Phara\UIKit\Components\QrCode;
use Phara\UIKit\Components\Radio;
use Phara\UIKit\Components\Select;
use Phara\UIKit\Components\Separator;
use Phara\UIKit\Components\SidebarToc;
use Phara\UIKit\Components\Skeleton;
use Phara\UIKit\Components\Slider;
use Phara\UIKit\Components\SliderTick;
use Phara\UIKit\Components\Spinner;
use Phara\UIKit\Components\StatsCard;
use Phara\UIKit\Components\SwitchInput;
use Phara\UIKit\Components\Tab;
use Phara\UIKit\Components\Table;
use Phara\UIKit\Components\TableCell;
use Phara\UIKit\Components\TableColumn;
use Phara\UIKit\Components\TableColumns;
use Phara\UIKit\Components\TableRow;
use Phara\UIKit\Components\TableRows;
use Phara\UIKit\Components\TabPanel;
use Phara\UIKit\Components\Tabs;
use Phara\UIKit\Components\Text;
use Phara\UIKit\Components\Textarea;
use Phara\UIKit\Components\ThemeToggle;
use Phara\UIKit\Components\Timeline;
use Phara\UIKit\Components\TimelineIndicator;
use Phara\UIKit\Components\TimelineItem;
use Phara\UIKit\Components\TimePicker;
use Phara\UIKit\Components\Toast;
use Phara\UIKit\Components\Tooltip;

class UIKitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ui');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/ui.php' => config_path('ui.php'),
        ], 'ui-config');

        // Register Blade directives
        Blade::directive('uiStyles', function () {
            return '<?php echo \Phara\UIKit\UiKit::styles(); ?>';
        });

        Blade::directive('uiScripts', function () {
            return '<?php echo \Phara\UIKit\UiKit::scripts(); ?>';
        });

        // Register Blade components
        Blade::componentNamespace('Phara\\UIKit\\Components', 'ui');
        Blade::component('ui::calendar', Calendar::class);
        Blade::component('ui::carousel', Carousel::class);
        Blade::component('ui::carousel-item', CarouselItem::class);
        Blade::component('ui::chart', Chart::class);
        Blade::component('ui::card', Card::class);
        Blade::component('ui::composer', Composer::class);
        Blade::component('ui::composer-action', ComposerAction::class);
        Blade::component('ui::context', Context::class);
        Blade::component('ui::context-item', ContextItem::class);
        Blade::component('ui::context-separator', ContextSeparator::class);
        Blade::component('ui::button', Button::class);
        Blade::component('ui::icon', Icon::class);
        Blade::component('ui::theme-toggle', ThemeToggle::class);
        Blade::component('ui::input', Input::class);
        Blade::component('ui::heading', Heading::class);
        Blade::component('ui::text', Text::class);
        Blade::component('ui::copy-to-clipboard', CopyToClipboard::class);
        Blade::component('ui::sidebar-toc', SidebarToc::class);
        Blade::component('ui::editor', Editor::class);
        Blade::component('ui::dropdown', Dropdown::class);
        Blade::component('ui::dropdown-item', DropdownItem::class);
        Blade::component('ui::select', Select::class);
        Blade::component('ui::slider', Slider::class);
        Blade::component('ui::slider-tick', SliderTick::class);
        Blade::component('ui::code-block', CodeBlock::class);
        Blade::component('ui::badge', Badge::class);

        Blade::component('ui::breadcrumbs', Breadcrumbs::class);
        Blade::component('ui::breadcrumbs-item', BreadcrumbsItem::class);

        Blade::component('ui::separator', Separator::class);

        Blade::component('ui::accordion', Accordion::class);
        Blade::component('ui::accordion-item', AccordionItem::class);

        Blade::component('ui::autocomplete', Autocomplete::class);
        Blade::component('ui::avatar', Avatar::class);
        Blade::component('ui::callout', Callout::class);
        Blade::component('ui::checkbox', Checkbox::class);
        Blade::component('ui::color-picker', ColorPicker::class);
        Blade::component('ui::radio', Radio::class);
        Blade::component('ui::file-upload', FileUpload::class);
        Blade::component('ui::date-picker', DatePicker::class);
        Blade::component('ui::modal', Modal::class);
        Blade::component('ui::modal-trigger', ModalTrigger::class);
        Blade::component('ui::modal-close', ModalClose::class);
        Blade::component('ui::list-group', ListGroup::class);
        Blade::component('ui::marquee', Marquee::class);
        Blade::component('ui::list-item', ListItem::class);
        Blade::component('ui::otp', Otp::class);
        Blade::component('ui::navbar', Navbar::class);
        Blade::component('ui::navbar-item', NavbarItem::class);
        Blade::component('ui::navlist', NavList::class);
        Blade::component('ui::navlist-item', NavListItem::class);
        Blade::component('ui::navlist-group', NavListGroup::class);
        Blade::component('ui::pillbox', Pillbox::class);
        Blade::component('ui::pillbox-option', PillboxOption::class);
        Blade::component('ui::profile', Profile::class);
        Blade::component('ui::pagination', Pagination::class);
        Blade::component('ui::progress', Progress::class);
        Blade::component('ui::skeleton', Skeleton::class);
        Blade::component('ui::spinner', Spinner::class);
        Blade::component('ui::switch', SwitchInput::class);
        Blade::component('ui::tabs', Tabs::class);
        Blade::component('ui::tab', Tab::class);
        Blade::component('ui::tab-panel', TabPanel::class);
        Blade::component('ui::textarea', Textarea::class);
        Blade::component('ui::timeline', Timeline::class);
        Blade::component('ui::timeline-item', TimelineItem::class);
        Blade::component('ui::timeline-indicator', TimelineIndicator::class);
        Blade::component('ui::time-picker', TimePicker::class);
        Blade::component('ui::toast', Toast::class);
        Blade::component('ui::tooltip', Tooltip::class);
        Blade::component('ui::table', Table::class);
        Blade::component('ui::table-columns', TableColumns::class);
        Blade::component('ui::table-column', TableColumn::class);
        Blade::component('ui::table-rows', TableRows::class);
        Blade::component('ui::table-row', TableRow::class);
        Blade::component('ui::table-cell', TableCell::class);
        Blade::component('ui::document-print', DocumentPrint::class);
        Blade::component('ui::qr-code', QrCode::class);
        Blade::component('ui::stats-card', StatsCard::class);
    }
}

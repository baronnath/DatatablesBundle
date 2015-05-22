<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Exception;

/**
 * Class Options
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Options
{
    /**
     * Options container.
     *
     * @var array
     */
    protected $options;

    /**
     * An OptionsResolver instance.
     *
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * Initial paging start point.
     *
     * @var integer
     */
    protected $displayStart;

    /**
     * Define the table control elements to appear on the page and in what order.
     *
     * @var string
     */
    protected $dom;

    /**
     * Change the options in the page length select list.
     *
     * @var array
     */
    protected $lengthMenu;

    /**
     * Highlight the columns being ordered in the table's body.
     *
     * @var boolean
     */
    protected $orderClasses;

    /**
     * Initial order (sort) to apply to the table.
     *
     * @var array
     */
    protected $order;

    /**
     * Multiple column ordering ability control.
     *
     * @var boolean
     */
    protected $orderMulti;

    /**
     * Change the initial page length (number of rows per page).
     *
     * @var integer
     */
    protected $pageLength;

    /**
     * Pagination button display options.
     *
     * @var string
     */
    protected $pagingType;

    /**
     * Display component renderer types.
     *
     * @var string
     */
    protected $renderer;

    /**
     * Allow the table to reduce in height when a limited number of rows are shown.
     *
     * @var boolean
     */
    protected $scrollCollapse;

    /**
     * Set a throttle frequency for searching.
     *
     * @var integer
     */
    protected $searchDelay;

    /**
     * Saved state validity duration.
     *
     * @var integer
     */
    protected $stateDuration;

    /**
     * Set the zebra stripe class names for the rows in the table.
     *
     * @var array
     */
    protected $stripeClasses;

    /**
     * Enable the Responsive extension for DataTables.
     *
     * @var boolean
     */
    protected $responsive;

    /**
     * Table class names.
     *
     * @var string
     */
    protected $class;

    /**
     * Enable or disable individual filtering.
     *
     * @var boolean
     */
    protected $individualFiltering;

    /**
     * Position of individual search filter ("head", "foot" or "both").
     *
     * @var string
     */
    protected $individualFilteringPosition;

    /**
     * DataTables provides direct integration support (https://github.com/DataTables/Plugins/tree/master/integration) for:
     * - Bootstrap
     * - Foundation
     * - jQuery UI
     *
     * This flag is set in the layout, the "dom" and "renderer" options for the integration plugin (i.e. bootstrap).
     *
     * @var boolean
     */
    protected $useIntegrationOptions;

    /**
     * SearchType is the type of comparision operator, using in search query.
     * The possible values are: 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'like', 'notLike', 'in', 'notIn', 'null', 'notNull'
     * Default value is 'like'
     */
    protected $searchType;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options = array();
        $this->resolver = new OptionsResolver();
        $this->configureOptions($this->resolver);
        $this->setOptions($this->options);
    }


    //-------------------------------------------------
    // Setup Options
    //-------------------------------------------------

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $this->resolver->resolve($options);
        $this->callingSettersWithOptions($this->options);

        return $this;
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "display_start" => 0,
            "dom" => "lfrtip",
            "length_menu" => array(10, 25, 50, 100),
            "order_classes" => true,
            "order" => [[0, "asc"]],
            "order_multi" => true,
            "page_length" => 10,
            "paging_type" => Style::FULL_NUMBERS_PAGINATION,
            "renderer" => "",
            "scroll_collapse" => false,
            "search_delay" => 0,
            "state_duration" => 7200,
            "stripe_classes" => array(),
            "responsive" => false,
            "class" => Style::BASE_STYLE,
            "individual_filtering" => false,
            "individual_filtering_position" => "foot",
            "use_integration_options" => false,
            "searchType" => "like",
        ));

        $resolver->setAllowedTypes(array(
            "display_start" => "int",
            "dom" => "string",
            "length_menu" => "array",
            "order_classes" => "bool",
            "order" => "array",
            "order_multi" => "bool",
            "page_length" => "int",
            "paging_type" => "string",
            "renderer" => "string",
            "scroll_collapse" => "bool",
            "search_delay" => "int",
            "state_duration" => "int",
            "stripe_classes" => "array",
            "responsive" => "bool",
            "class" => "string",
            "individual_filtering" => "bool",
            "individual_filtering_position" => "string",
            "use_integration_options" => "bool"
        ));

        $resolver->setAllowedValues("individual_filtering_position", array("head", "foot", "both"));

        return $this;
    }

    /**
     * Calling setters with options.
     *
     * @param array $options
     *
     * @return $this
     * @throws Exception
     */
    private function callingSettersWithOptions(array $options)
    {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = "set" . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                throw new Exception("callingSettersWithOptions(): {$method} invalid method name");
            }
        }

        return $this;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set DisplayStart.
     *
     * @param int $displayStart
     *
     * @return $this
     */
    protected function setDisplayStart($displayStart)
    {
        $this->displayStart = (integer) $displayStart;

        return $this;
    }

    /**
     * Get DisplayStart.
     *
     * @return int
     */
    public function getDisplayStart()
    {
        return (integer) $this->displayStart;
    }

    /**
     * Set Dom.
     *
     * @param string $dom
     *
     * @return $this
     */
    protected function setDom($dom)
    {
        $this->dom = $dom;

        return $this;
    }

    /**
     * Get Dom.
     *
     * @return string
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * Set LengthMenu.
     *
     * @param array $lengthMenu
     *
     * @return $this
     */
    protected function setLengthMenu(array $lengthMenu)
    {
        $this->lengthMenu = $lengthMenu;

        return $this;
    }

    /**
     * Get LengthMenu.
     *
     * @return array
     */
    public function getLengthMenu()
    {
        return $this->lengthMenu;
    }

    /**
     * Set OrderClasses.
     *
     * @param boolean $orderClasses
     *
     * @return $this
     */
    protected function setOrderClasses($orderClasses)
    {
        $this->orderClasses = (boolean) $orderClasses;

        return $this;
    }

    /**
     * Get OrderClasses.
     *
     * @return boolean
     */
    public function getOrderClasses()
    {
        return (boolean) $this->orderClasses;
    }

    /**
     * Set Order.
     *
     * @param array $order
     *
     * @throws Exception
     * @return $this
     */
    protected function setOrder(array $order)
    {
        foreach($order as $o) {
            if( !is_array($o) ||
                !array_key_exists(0, $o) ||
                !is_numeric($o[0]) ||
                !array_key_exists(1, $o) ||
                !in_array($o[1], ['desc', 'asc'])){
                throw new \Exception("setOrder(): Invalid array format.");
            }
        }
        
        $this->order = $order;

        return $this;
    }

    /**
     * Get Order.
     *
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set OrderMulti.
     *
     * @param boolean $orderMulti
     *
     * @return $this
     */
    protected function setOrderMulti($orderMulti)
    {
        $this->orderMulti = (boolean) $orderMulti;

        return $this;
    }

    /**
     * Get OrderMulti.
     *
     * @return boolean
     */
    public function getOrderMulti()
    {
        return (boolean) $this->orderMulti;
    }

    /**
     * Set PageLength.
     *
     * @param int $pageLength
     *
     * @return $this
     */
    protected function setPageLength($pageLength)
    {
        $this->pageLength = (integer) $pageLength;

        return $this;
    }

    /**
     * Get PageLength.
     *
     * @return int
     */
    public function getPageLength()
    {
        return (integer) $this->pageLength;
    }

    /**
     * Set PagingType.
     *
     * @param string $pagingType
     *
     * @return $this
     */
    protected function setPagingType($pagingType)
    {
        $this->pagingType = $pagingType;

        return $this;
    }

    /**
     * Get PagingType.
     *
     * @return string
     */
    public function getPagingType()
    {
        return $this->pagingType;
    }

    /**
     * Set Renderer.
     *
     * @param string $renderer
     *
     * @return $this
     */
    protected function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Get Renderer.
     *
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set ScrollCollapse.
     *
     * @param boolean $scrollCollapse
     *
     * @return $this
     */
    protected function setScrollCollapse($scrollCollapse)
    {
        $this->scrollCollapse = (boolean) $scrollCollapse;

        return $this;
    }

    /**
     * Get ScrollCollapse.
     *
     * @return boolean
     */
    public function getScrollCollapse()
    {
        return (boolean) $this->scrollCollapse;
    }

    /**
     * Set searchDelay.
     *
     * @param int $searchDelay
     *
     * @return $this
     */
    protected function setSearchDelay($searchDelay)
    {
        $this->searchDelay = $searchDelay;

        return $this;
    }

    /**
     * Get searchDelay.
     *
     * @return int
     */
    public function getSearchDelay()
    {
        return $this->searchDelay;
    }

    /**
     * Set StateDuration.
     *
     * @param int $stateDuration
     *
     * @return $this
     */
    protected function setStateDuration($stateDuration)
    {
        $this->stateDuration = (integer) $stateDuration;

        return $this;
    }

    /**
     * Get StateDuration.
     *
     * @return int
     */
    public function getStateDuration()
    {
        return (integer) $this->stateDuration;
    }

    /**
     * Set StripClasses.
     *
     * @param array $stripeClasses
     *
     * @return $this
     */
    protected function setStripeClasses(array $stripeClasses)
    {
        $this->stripeClasses = $stripeClasses;

        return $this;
    }

    /**
     * Get StripClasses.
     *
     * @return array
     */
    public function getStripeClasses()
    {
        return $this->stripeClasses;
    }

    /**
     * Set responsive.
     *
     * @param boolean $responsive
     *
     * @return $this
     */
    protected function setResponsive($responsive)
    {
        $this->responsive = (boolean) $responsive;

        return $this;
    }

    /**
     * Get responsive.
     *
     * @return boolean
     */
    public function getResponsive()
    {
        return (boolean) $this->responsive;
    }

    /**
     * Set class.
     *
     * @param string $class
     *
     * @return $this
     */
    protected function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set individual filtering.
     *
     * @param boolean $individualFiltering
     *
     * @return $this
     */
    protected function setIndividualFiltering($individualFiltering)
    {
        $this->individualFiltering = (boolean) $individualFiltering;

        return $this;
    }

    /**
     * Get individual filtering.
     *
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return (boolean) $this->individualFiltering;
    }

    /**
     * Set individual filtering position.
     *
     * @param string $individualFilteringPosition
     *
     * @return $this
     */
    public function setIndividualFilteringPosition($individualFilteringPosition)
    {
        $this->individualFilteringPosition = $individualFilteringPosition;

        return $this;
    }

    /**
     * Set individual filtering position.
     *
     * @return string
     */
    public function getIndividualFilteringPosition()
    {
        return $this->individualFilteringPosition;
    }

    /**
     * Set use integration options.
     *
     * @param boolean $useIntegrationOptions
     *
     * @return $this
     */
    public function setUseIntegrationOptions($useIntegrationOptions)
    {
        $this->useIntegrationOptions = $useIntegrationOptions;

        return $this;
    }

    /**
     * Set use integration options.
     *
     * @return boolean
     */
    public function getUseIntegrationOptions()
    {
        return $this->useIntegrationOptions;
    }

    /**
     * Set search type
     *
     * @param string $searchType
     * @return bool
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;
    }
}

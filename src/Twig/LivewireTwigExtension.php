<?php

namespace Anolek\Twig;

use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Illuminate\Support\Str;
use Livewire\Livewire;

class LivewireTwigExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'twig_livewire';
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('livewireStyles', [$this, 'livewireStyles'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('livewireScripts', [$this, 'livewireScripts'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('livewire', [$this, 'livewire'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('entangle', [$this, 'entangle'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('this', [$this, 'this'], ['is_safe' => ['html']]),
        );
    }

    public function livewireStyles($expression = null)
    {
        return Livewire::styles($expression);
    }

    public function livewireScripts($expression = null)
    {
        return Livewire::scripts($expression);
    }

    public static function livewire($expression)
    {
        static $_instance;

        $lastArg = trim(last(explode(',', $expression)));

        if (Str::startsWith($lastArg, 'key(') && Str::endsWith($lastArg, ')')) {
            $cachedKey = Str::replaceFirst('key(', '', Str::replaceLast(')', '', $lastArg));
            $args = explode(',', $expression);
            array_pop($args);
            $expression = implode(',', $args);
        } else {
            $cachedKey = "'".Str::random(7)."'";
        }
        
        if (! isset($_instance)) {
            $html = \Livewire\Livewire::mount($expression)->html();
        } elseif ($_instance->childHasBeenRendered($cachedKey)) {
            $componentId = $_instance->getRenderedChildComponentId($cachedKey);
            $componentTag = $_instance->getRenderedChildComponentTagName($cachedKey);
            $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
            $_instance->preserveRenderedChild($cachedKey);
        } else {
            $response = \Livewire\Livewire::mount($expression);
            $html = $response->html();
            $_instance->logRenderedChild($cachedKey, $response->id(), \Livewire\Livewire::getRootElementTagName($html));
        }

        return $html;
    }

    public function entangle($expression)
    {
        static $_instance;

        if ((object) ($expression) instanceof \Livewire\WireDirective) {
            return 'window.Livewire.find(' . $_instance->id . ').entangle(' . $expression->value() . ')' . ($expression->hasModifier('defer') ? '.defer' : '');
        }
        
        return 'window.Livewire.find(' . $_instance->id . ').entangle(' . $expression . ')';
    }

    public static function this()
    {
        static $_instance;

        return 'window.livewire.find(' . $_instance->id . ')';
    }
}
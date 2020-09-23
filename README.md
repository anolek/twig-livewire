# Twig Livewire

A [Laravel Livewire](https://laravel-livewire.com/) integration for Twig template engine. 

## Installation
Pull in your package with composer
```bash
composer require anolek/twig-livewire
```

## Configuration

Add this Twig Extension in twigbridge.php

```
Anolek\Twig\LivewireTwigExtension
```

## General documentation
[Laravel Livewire Docs](https://laravel-livewire.com/docs/quickstart)

## How to be used with Twig template engine

Include the JavaScript (on every page that will be using Livewire).

```html
...
    {{ livewireStyles() }}
</head>
<body>

    ...
    {{ livewireScripts() }}
</body>
</html>
```

### Include components with Twig
You can create Livewire components as described in the general documentation. To include your Livewire component:
```html
<head>
    ...
    {{ livewireStyles() }}
</head>
<body>
    {{ livewire('your-component-name') }}

    ...

    {{ livewireScripts() }}
</body>
</html>
```

### Blade or Twig? Both!
When you create a Livewire component, you need to render a template file

```
namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public function render()
    {
        return view('livewire.counter');
    }
}
```
More Information: (https://laravel-livewire.com/docs/quickstart#create-a-component)

Normally your template file would be a blade file, named `counter.blade.php`. 
If you want to use Twig, rename your template to `counter.twig`, use Twig syntax and do wathever you like. **No need to change** anything inside your component Controller.

### Passing Initial Parameters
You can pass data into a component by passing additional parameters
```html
{{ livewire('your-component-name', {'contact': contact}) }}
```

Livewire will automatically assign parameters to matching public properties.
To intercept manually those parameters, mount them and store the data as public properties.

```php
use Livewire\Component;

class ShowContact extends Component
{
    public $name;
    public $email;

    public function mount($contact)
    {
        $this->name = $contact->name;
        $this->email = $contact->email;
    }

    ...
}
```

The [Official Livewire documentation](https://laravel-livewire.com/docs/rendering-components)

## Requirements
- PHP 7.4
- Laravel 7
- Twig >= 2

# License 
This plugin is published under the MIT license.

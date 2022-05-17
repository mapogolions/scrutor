#### Scrutor

> Just decorate already registered services

Based on [Illuminate container](https://github.com/illuminate/container)

Inspired by [Scrutor](https://github.com/khellang/Scrutor)

Let's say you want to add an extra layer on top of the existing service functionality. It doesn't matter what it is - logging, caching, performance measurement. The decorator pattern is one option that suits this purpose.

```php
interface ServiceContract
{
	public function callMe();
}

class Service implements ServiceContract
{
	public function callMe() { /* do something */ }
}

class CachedService implements ServiceContract
{
	private $result = null;
	private ServiceContract $origin;

	public function __construct(ServiceContract $origin)
	{
		$this->origin = $origin;
	}

	public callMe()
	{
		if (!isset($result))
		{
			$this->result = $this->origin->callMe();
		}
		return $this->result;
	}
}

$container = new Container();
$container->singleton(ServiceContract::class, Service::class);
$container->decorate(ServiceContract::class, CachedService::class);

```

Please see unit tests for more details

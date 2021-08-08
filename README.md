# Pest GraphQL Plugin

Test your GraphQL API in style, with Pest!

## Installation

Simply install through Composer!

```bash
composer require --dev miniaturebase/pest-plugin-graphql
```

## What's Added?

- Test your schema as code;
- Assert PSR-7 GraphQL response data _and_ errors;
- Testing resolvers _(Coming Soon!)_;

### Expectations

- `schema(string|Schema $document)`
- `isValidSdl()`
- `toHaveDirective(string $directive)`
- `toHaveEnum(string $enum)`
- `toHaveInput(string $input)`
- `toHaveInterface(string $interface)`
- `toHaveScalar(string $scalar)`
- `toHaveType(string $type)`
- `toHaveUnion(string $union)`
- `toBeGraphQlResponse()`
- `toHavePath(string $path, $value = null)`
- `toHaveData(array $data)`
- `toHaveErrors(array $errors)`

And more on the way!

#### `schema(string|Schema $document)`

Create a new expectation with a `GraphQL\Type\Schema` instance as the underlying
value.

```php
test('my schema')->schema();
it('is my schema')->schema();
Pest\GraphQl\schema();
```

You can also provide an alternative schema path or document contents, like so.

```php
it('uses any schema you want')->schema(sprintf('%s/../app/schema.graphql', __DIR__));
test('inline content')->schema(<<<'GRAPHQL'
type Query {
    foo: Int
    bar: Int
}
GRAPHQL);
it('even uses your instance')->schema(AcmeSchemaBuilder::build());
```

#### `isValidSdl()`

Assert that the schema is valid and written to the GraphQL specification.

```php
it('validates your schema')->schema()->isValidSdl();
```

#### `toHaveDirective(string $directive)`

Assert that the given directive definition exists with the schema document.

```php
it('has a directive')->schema()->toHaveDirective('auth')
it('will also use base classnames')->schema()->toHaveDirective(Auth::class);
```

#### `toHaveEnum(string $enum)`

Assert that the given enum definition exists with the schema document.

```php
it('has an enum')->schema()->toHaveEnum('Status')
it('will also use base classnames')->schema()->toHaveEnum(Status::class);
```

#### `toHaveInput(string $input)`

Assert that the given input definition exists with the schema document.

```php
it('has a input')->schema()->toHaveInput('Message')
it('will also use base classnames')->schema()->toHaveInput(Message::class);
```

#### `toHaveInterface(string $interface)`

Assert that the given interface definition exists with the schema document.

```php
it('has a interface')->schema()->toHaveInterface('Notification')
it('will also use base classnames')->schema()->toHaveInterface(Notification::class);
```

#### `toHaveScalar(string $scalar)`

Assert that the given scalar definition exists with the schema document.

```php
it('has a scalar')->schema()->toHaveScalar('Date')
it('will also use base classnames')->schema()->toHaveScalar(Date::class);
```

#### `toHaveType(string $type)`

Assert that the given (object) type has been defined within the schema document.

```php
it('has mutations')->schema()->toHaveType('Mutation');
test('user defined types too')->schema()->toHaveType('User');
it('will also use your base classnames')->schema()->toHaveType(User::class);
```

#### `toHaveUnion(string $union)`

Assert that the given union definition exists with the schema document.

```php
it('has a union')->schema()->toHaveUnion('Character')
it('will also use your base classnames')->schema()->toHaveUnion(Character::class);
```

#### `toBeGraphQlResponse()`

Assert that an underlying (PSR-7) response value is a compliant with the GraphQL
specification.

```php
test('response validity')
    ->expect($response) // a Psr\Http\Message\ResponseInterface instance
    ->toBeGraphQlResponse();
```

#### `toHavePath(string $path, $value = null)`

Assert that the underlying GraphQL response contains data at the given path.
Optionally provide a value to be checked as well!

```php
it('reads paths')
    ->expect($response) // a Psr\Http\Message\ResponseInterface instance
    ->toHavePath('foo')
    ->toHavePath('foo', 1)
    ->not()
    ->toHavePath('foo.bar')
    ->not()
    ->toHavePath('foo', 0);
```

#### `toHaveData(array $data)`

Assert that the underlying response GraphQL data is canonically equal to the
expected data.

```php
it('contains response data')
    ->expect($response) // a Psr\Http\Message\ResponseInterface instance
    ->toHaveData([
        'foo' => 1,
    ]);
```

#### `toHaveErrors(array $errors)`

Assert that the underlying response GraphQL errors are canonically equal to the
exepected set of errors.

```php
it('has errors')
    ->expect($response) // a Psr\Http\Message\ResponseInterface instance
    ->toHaveErrors([
        [
            'message'   => 'Oops, I did it again',
            'locations' => [['line' => 1, 'column' => 5]],
            'path'      => ['foo'],
        ],
    ]);
```

---

This repository was based off of the Pest Plugin Template.

Pest was created by **[Nuno Maduro](https://twitter.com/enunomaduro)** under the **[Sponsorware license](https://github.com/sponsorware/docs)**. It got open-sourced and is now licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.

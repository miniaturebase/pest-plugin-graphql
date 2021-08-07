# Pest GraphQL Plugin

Test your GraphQL API in style, with Pest!

## What's Added?

- Test your schema as code;
- Assert PSR-7 GraphQL response data _and_ errors;
- Testing resolvers _(Coming Soon!)_;

### Expectations

- `schema(string|Schema $document)`
- `isValidSdl()`
- `toHaveType(string $type)`
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
schema();
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

#### `toHaveType(string $type)`

Assert that the given type has been defined within the schema document.

> **Note:** that enums, unions, etc. are all types, _except_ directives.

```php
it('has mutations')->schema()->toHaveType('Mutation');
test('user defined types too')->schema()->toHaveType('User');
it('will also use your base classnames')->schema()->toHaveType(User::class);
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

# Schema Validator

[![codecov](https://codecov.io/gh/WebdevCave/schema-validator-php/graph/badge.svg?token=lC2scjv7Co)](https://codecov.io/gh/WebdevCave/schema-validator-php)
![StyleCI](https://github.styleci.io/repos/911763600/shield?branch=main)

A simple schema validation library for PHP. This package allows you to define rules for your data and validate it easily.

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
    - [Basic Usage](#basic-usage)
    - [Dataset Schema Example](#dataset-validation-example)
- [Contributing](#contributing)
- [License](#license)

## Installation

To install the Schema Validator PHP library, you can use Composer. Run the following command:

```bash
composer require webdevcave/schema-validator-php
```

## Usage

### Basic Usage

```php
use Webdevcave\SchemaValidator\Validator;

$validator = Validator::string()
    ->min(3)
    ->max(50);

// Validate the data
$isValid = $validator->validate('Carlos');

if ($isValid) {
    echo "Data is valid!";
} else {
    echo "Data is invalid!";
}
```

### Dataset Validation Example

The library also allows you to define more complex validation rules for nested structures or arrays. For example:

```php
use Webdevcave\SchemaValidator\Validator;

$validator = Validator::array([
    'name' => Validator::string()
        ->min(3)
        ->max(50),
    'email' => Validator::string()
        ->pattern('/^\w+@(\w+|\.)+$/'),
    'address' => Validator::array([
        'street' => Validator::string()->max(200),
        'city' => Validator::string()->max(50),
        'postal_code' => Validator::string()
            ->min(1)
            ->max(15),
    ])
]);

// Data to be validated
$data = [
    'name' => 'Alice Johnson',
    'email' => 'alice.johnson@example.com',
    'address' => [
        'street' => '123 Maple St',
        'city' => 'Somewhere',
        'postal_code' => '12345'
    ]
];

// Validate the data
$isValid = $validator->validate($data);

if ($isValid) {
    echo "Data is valid!";
} else {
    echo "Data is invalid!";
}
```

For objects, just use `Validator::object()` in a similar way.

### Validation Error Handling

If the data is invalid, you can get more detailed error information:

```php
use Webdevcave\SchemaValidator\Validator;

// Data to be validated
$data = [
    'name' => 'John Doe',
    'age' => 'thirty'
];

// Define the validation schema
$validator = Validator::array([
    'name'  => Validator::string(),
    'age'   => Validator::numeric()
        ->integer('Only integer numbers are allowed')
        ->positive('Age field must be positive')
]);

// Validate the data
if (!$validator->validate($data)) {
    // Get and print the validation errors
    print_r($validator->errorMessages());
}
```

This will output something like:
```
Array
(
    [age] => Array 
    (
        [0] => Only integer numbers are allowed,
        [1] => Age field must be positive,   
    )
)
```

## Contributing

We welcome contributions to this project! If you'd like to help improve the Schema Validator PHP library, please follow these steps:

### How to Contribute

1. Fork this repository to your GitHub account.
2. Create a new branch for your feature or fix.
3. Make your changes and test them thoroughly.
4. Submit a pull request with a description of your changes and why they're needed.

### Code Style

Please follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards for PHP when making contributions.

### Issues

If you encounter any bugs or have suggestions for improvements, please open an issue in the GitHub repository.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

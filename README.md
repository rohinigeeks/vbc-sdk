vbc-sdk
=======

An SDK for the VBC API in PHP. Enjoy!

## Installation

The library uses Composer as it's dependency manager. Thus, installing this library follows Composers and the PSR standards. You can read more about composer at this link https://getcomposer.org/

Before anything, you must create/update your Composer.json and add the following
```
...
    "repositories": [
        {
            "url": "https://github.com/incube8/vbc-sdk.git",
            "type": "git"
        }
    ]
...
```
and add the library to the require block..
```
...
    "require": {
        "incube8/vbc-sdk": "dev-master"
    }
...
```
Run composer update or composer install to include the library to your application

## Usage
You must obtain an AppKey and a SecretKey from VBC before using this SDK.

```php
<?php
require_once 'vendor/autoload.php';
 
$config = [
    'AppKey' => '',
    'SecretKey' => ''
];
 
// oop
$client = new Vbc\Client($config);
$request = new Vbc\Request($client);
 
// static
$request = Vbc\Request::factory($config);
 
// create a request
$tokenOrPin = $request->create([
    'uid' => 1234567890,
    'caption' => 'Hi! my name is john from the lovely island of Singapore',
    'data' => [
        'foo' => 'bar'
    ]
]);
 
print_r($tokenOrPin);
```
As you can see there are 2 ways to create a Vbc\Request instance, by either the object oriented way, or statically; both yield the same result.

## API Reference

### Request

#### create(array $data [, array $options])
Starts the create a request workflow and issue tokens to the App.

##### Parameters

###### data
An array of data to be passed to the API to help create the request.

| key | description |
| --- | ----------- |
| uid | a unique identifier for the request that can be associated with your application. |
| callback | a custom callback URL whenever an action is made for this particular user. |
| caption | a caption that the user should say in the recording window. |
| data | a collection of key-value stores to be passed back to the callback. |

###### options
An array of options to help create the request

| key | defaults | description |
| --- | -------- | ----------- |
| auto | true | determine whether the api should return a link or a pin depending on the request's User Agent. |
| pin | false | return a PIN from the API. overrides the auto flag. |

--

#### verify(string $requestId)
Verify a Request.

##### Parameters

###### requestId
A Mongo ObjectId that corresponds to the request specified.

-- 

#### reject(string $requestId)
Reject a Request.

##### Parameters

###### requestId
A Mongo ObjectId that corresponds to the request specified.

-- 

#### list()
... not available yet

--

### Client

#### __constructor(array $config)
Creates a GuzzleHttp client to the API url.

##### Parameters

###### config
An array of data to help create the GuzzleHttp Client.

| key | description |
| --- | ----------- |
| AppKey | Application Key from the Registered Application. |
| SecretKey | Secret Key from the Registered Application. |

--

#### getInstance()
A static alias of __constructor.

## License

The MIT License (MIT)

Copyright (c) 2014 Incube8

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

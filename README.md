# optimize-image

### Setup/Install
Make sure `runtime/temp` and `runtime/logs` folders are writable by the server.

``` php
cp config/constants.local.php config/constants.php
```

Inside of `config/constants.php` you can set the `API_ENV` constant to your needs.

Next you will need to configure your user repository.
``` php
copy config/userRepository.local.php config/userRepository.php
```
You can add more users to this configuration as needed.


### Example request using GuzzleHTTP

This example shows how you would send a `compress` request to the API.  It will return the base64 encoded image contents.

``` php
$request = [
    'url' => 'https://_hostname.of.your.image_/image.jpg'
];

$headers = [
    'Authorization' => 'Basic ' . base64_encode('101-token:')
];

$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://_your.api.hostname_/v1/'
]);

try {

    $response = $client->request('POST', 'compress', [
        'json' => $request,
        'headers' => $headers
    ]);

    $contents = json_decode($response->getBody()->getContents(), true);


    file_put_contents('test.jpg', base64_decode($contents['image']));

} catch(Exception $exception) {

    echo $exception->getMessage();
    
}
```

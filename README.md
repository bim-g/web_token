# web_token
simple module to help generate a simple token using php 

# INTEGRATION
create a new instance of the class `Token`, that will help you generate a new token, or validate an existing token.
```php
$data=[
    "data"=>"hello Github",
    "expired"=>300
];
$tken= new Token;
$_token=$tken->generate($data)
echo $_token;
```
the class Token has 2 Method:
 * generate:
 use this method to generate a new token key.
 it take 2 params.
 - the first parameter is an array of information according to the data to be encoded.
 it has 2 key : 
 1: `data`: where you can store all your information you want to be enconded;
 2: `expired`:(iptional) how long your token will be used; the value in second, and it optional, by default the token propose `3600 second`.

 - the second parameter (optional) is `cypherkye` to encode your data, by default the token generate a cypherkey for the token key.

 * decode
 use this method to valid your token, and restore the encoded the data, and it take 2 parameters.
 
 - the first parameter is your token string generated with is devide in 3 parts:
the `encryption_iv`,the `encoded_data`,the `cypherkye`.

 - the second parameter (optional) is `cypherkye` to encode your data, by default the token generate a cypherkey for the token key.
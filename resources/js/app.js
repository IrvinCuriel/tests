require('./bootstrap');

/*
{
    "compositeRequest":[{
        "method":"POST",
        "url":"/services/data/v47.0/sobjects/Account",
        "referenceId":"refAccount",
        "body":{"Name":"Sample Account"}
    },{
        "method":"POST",
        "url":"/services/data/v47.0/sobjects/Contact",
        "referenceId":"refContact",
        "body":{
            "LastName":"Sample Account",
            "AccountId":"@{refAccount.id}"
        }
    }]
}
*/

/*
{
    "compositeResponse":[
        {
            "body":{
                "id":"00XXXXXXXXX",
                "success":true,
                "errors":[]
            },
            "httpHeaders":{
                "Location":"/services/data/v47.0/sobject/Account/00XXXXXXXXX"
            },
            "httpStatusCode":201,
            "referenceId":"refAccount"
        },
        {
            "body":{
                "id":"003YYYYYYYYY",
                "success":true,
                "errors":[]
            },
            "httpHeaders":{
                "Location":"/services/data/v47.0/sobject/Contact/003YYYYYYYYY"
            },
            "httpStatusCode":201,
            "referenceId":"refContact"
        }
    ]
}
*/

/*
Home
PLATFORM TOOLS
Apps->App Manager->New conected App (button)

SETUP(App Manager)
New cConnected App

Basic Information
Connected App Name: 
API Name:
Contact Email:


API (Enable OAuth Settings)
Enable
Callback URL: Https://despues/oauth/callback

Scope
Access and manage your data(api)
Full access(full)
Perfom request on your behalf ay any time (refresh_toke, offline_acces)

Save
continue

Preview:
Consummer Key
Consumer Scret

despues de agregar "salesforce\\": "salesfroce/"
composer dump-autoload

en .env
SALESFORCE_CALLBACK_URI=/oauth/callback
SALESFORCE_CLIENT_ID=
SALESFORCE_CLIENT_SECRET=



+++++++++++++++++++++++++++++++++++++++++++++++++++++++

SETUP
Build
Cretae
Apps
Connect app -> new (buttom)

Basic Information
Connected App Name: 
API Name:
Contact Email:

Callback URL: https://localhost
Full access(full)
save

API(eNABLE OAuth Settings)
copy
Consummer Key
Consumer Scret
->Manage
->Edit Policies
->OAuth Policites->Permited Users-> All users may self-authorize
->IP relaxacions->Relax IP restriction for activated devices
save



CONSUMER_KEY = 
CONSUMER_SECRET = 
DOMAIN_NAME=



request.post(DOMAIN_NAME + '/services/oauth2/token')

In Step One: Set Up autorization

curl https://MyDomainName.my.salesforce.com/services/data/v58.0/sobjects/Account/ -H 'Authorization Bearer 00DE0X0A0M0PeLE!AQcAQH0dMHEXAMPLEzmpkb58urFRkgeBGsxL_QJWwYMfAbUeeG7c1EXAMPLEDUkWe6H34r1AAwOR8B8fLEz6nEXAMPLE' -H "Content-Type: application/json" —d @new-account.json -X POST

https://www.youtube.com/watch?v=N96cZh6Kiho
https://www.youtube.com/watch?v=aHxlVhJrACo




<td><a target="_blank" href="https://wa.me/{{$consulta->phone}}?text=Hola {{$consulta->nombre}}">{{$consulta->phone}}</a></td>

*/

//Adquirir access token
CONSUMER_KEY = 
CONSUMER_SECRET = 
DOMAIN_NAME=

data = {
    'grant_type':'password',
    'client_id':CONSUMER_KEY,
    'client_secret':CONSUMER_SECRET,
    'username':USERNAME,
    'password':PASSWORD
}
response_access_token = request.post(DOMAIN_NAME + '/services/oauth2/token', data=json_data)
console.log(response_access_token.status_code);
console.log(response_access_token.reason);
console.log(response_access_token.json());

if(response_access_token.status_code == 200){
    access_token_id = response_access_token.json()['accsess_token'];
    console.log('access token creado');
}

//Retrieve medadata ejemplo sObjectBasic Information
headers = {
    'Authorization': 'Bearer ' + access_token_id
}
response_sObject = request.post(DOMAIN_NAME + '/services/data/v53.0/sobjects/sObject', headers=headers);
console.log(response_sObject.reason)
console.log(response_sObject.json())

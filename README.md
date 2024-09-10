# Template-informasi-rekening-php

This is a simple template for Virtual Account SNAP BI using PHP.

module:
- [Virtual Account - Briva Online](https://developers.bri.co.id/en/snap-bi/apidocs-virtual-account-briva-online-snap-bi)
- [Virtual Account - Briva WS](https://developers.bri.co.id/en/snap-bi/apidocs-virtual-account-briva-ws-snap-bi)

## List of Content
- [Instalasi](#instalasi)
  - [Prerequisites](#prerequisites)
  - [How to Setup Project](#how-to-setup-project)
  - [Payment](#payment)
  - [Payment Status](#payment-status)
  - [Refund Payment](#refund-payment)
- [Caution](#caution)
- [Disclaimer](#disclaimer)

## Instalasi

### Prerequisites
- php
- composer

### How to Setup Project

```bash
1. copy .env file by typing 'cp .env.example .env' in the terminal
2. fill the .env file with the required values
3. run composer install to install all dependencies
```

### Payment
```bash
1. fill partnerId, eg: 'cM8GIPvoEpzLaHQfvL1e9g'
2. fill channelId, eg: '12345'
3. fill partnerReferenceNo is Transaction identification on service provider system example '426306015178'
4. fill url is URL for callback, example https://5fdc5f1948321c00170119e0.mockapi.io/api/v1/simulation/simulation
5. fill type, type is URL Type PAY_RETURN/PAY_NOTIFY
6. fill isDeepLink, to define is url deep link or not with Y/N
7. fill value, Transaction amount that will be paid using this payment method If it’s IDR then value. includes 2 decimal digits. e.g. IDR 10.000,- will be placed with 10000.00
8. fill currency eg: IDR
9. fill chargeToken, chargeToken string code for verification OTP can filled by 'null'
10. fill bankCardToken, eg: 'card_.eyJpYXQiOjE3MDgwNTAzNTYsImlzcyI6IkJhbmsgQlJJIC0gRENFIiwianRpIjoiNmY2MmE4ZjUtMGUwMS00NjFjLWJlZmQtYjk3ZWE5YjNmMmIwIiwicGFydG5lcklkIjoi77-9Iiwic2VydmljZU5hbWUiOiJERF9FWFRFUk5BTF9TRVJWSUNFIn0.HR4P9PecyfCZLJ-ibeuxuuWtHzWHrzgunjxiEQJZEjZHO2fQqrMgaO8IUnmACtNJilGOpIQAc7Jsa5W_tCF4KmIpC5jB-tDw40tpqImZ9Famt_hzgacrDcByw2jT9UAPMH444kGAQa7z44PV6jcHdQoaIAfiOkChHw-b11Vg4LyETbsEExvOcL2hKomG_JXpDq5bYmuHcJ2SJ8lRnGomi-7oz_dyM0_wUe1fmE6UyLnvEFz6o6q8nXtm_3g29cLP_4uw5BT54DuSXrRdmw4J7PK3zl2qUnM7CBpYVRLr74iCx9SLGYIMMROE7aGe_DkNfK-dnLKgcvIaN0q-rnLbhg'
11. fill otpStatus fill wit YES/NO, this is optional
12. fill settlementAccount, eg: 020601000109305, this is optional
13. fill merchantTrxId, eg: '0206010001', this is optional
14. fill remarks, eg: 'test'
11. run command `php src/payment.php serve`
```

### Payment Status
```bash
1. fill partnerId, eg: 'cM8GIPvoEpzLaHQfvL1e9g'
2. fill channelId, eg: '12345'
3. fill originalPartnerReferenceNo is Original transaction identifier on service consumer system, eg: '815027979003'
4. fill originalReferenceNo is Original transaction identifier on service provider system, eg: '574929794216'
5. fill serviceCode is Transaction type indicator (service code of the original transaction request), eg: '54'
6. run command `php src/payment_status.php serve`
```

### Refund Payment
```bash
1. fill partnerId, eg: 'cM8GIPvoEpzLaHQfvL1e9g'
2. fill channelId, eg: '12345'
3. fill originalPartnerReferenceNo, eg: '815027979003'
4. fill originalReferenceNo, eg: '574929794216'
5. fill partnerRefundNo, eg: '341406425579'
6. fill value, eg: '10000.00'
7. fill currency, eg: 'IDR'
8. fill reason, eg: 'testing coba'
9. fill callbackUrl, eg: 'https://5fdc5f1948321c00170119e0.mockapi.io/api/v1/simulation/simulation'
10. fill settlementAccount, eg: '020601000109305'
11. run command `php src/refund_payment.php serve`
```

## How to get CONSUMER_KEY and CONSUMER_SECRET
1. Go to https://developers.bri.co.id/en then login
2. Click menu  My Apps
3. select app
4. copy Consumer Key and Consumer Secret
![alt text](assets/image.png)

## How to get Private Key
1. Go to https://developers.bri.co.id/en then login
2. Click menu  My Apps
3. Click Manage Snap Key
4. Click Add Snap Key
5. Generate RSA key with https://cryptotools.net/rsagen (recomended)
6. Save your private key and fill your public key in Snap key then save
![alt text](assets/image-1.png)

## .ENV Example
```bash
CONSUMER_KEY=pqYYBsSc6rHwCqp6o4R8ExmBRubEpqtY 

CONSUMER_SECRET=idbaNFh0mGSZ7xol 

PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCOkAAcgCOTpZPgmxQKovWho6G3GJmxet6kYqi1wj5jTFuB8lLe
QhppR8ppYIxdvM1mnn2MTJFpHQr1zXwshpsT5YiaFIRxN/VMUi5QPBpgO8BMCBKc
wTL6Kq1pSaeTbdRdtRYNZjidxNWyvWVVbbbVmzH6edelT03YrO/r0aUKYQIDAQAB
AoGAa5D5lIeW0GuplVpNl+z3Wzvk5Ar6xHBKF0ydsW7btf7CON1Ha44C8ZcKgdIo
dv3jGV/SqQ6I1P/l6iteWxZBYXIInrNERaA5l6afUcHES8LBWKwDr6kBpAVXaCaV
yjdzknKMbN2PBNURbL3+O4v3Al8bCp1/e9EwBd99jkSYcMECQQDaEq+Q2ybw7tKi
bW7OEe1p7kMkF73sBW4p8gHRM53WJEfDh1X9DDKsgQqpqm0RASo1kGXIM9D1i7Ip
lcxKZs+pAkEAp1tX+SjnruA1DE8U9OEe83x7U9AReepRS5G8mhv59J3zdruMLWex
jJCDvLgz07YuKIoykgnqBK34UMvBaKH3+QJBAMxQWYFkcpWljF92HCyiC0gGXY2B
WQW7CL3v6dfxfl3V3A7Ly7qsJQYOWMkhzdHyv3Mz+MicE5ka6y+fE6pZrRECQACO
gzpm8m5YfJSv5qfx38J7lYVv2b8IEoEn2PLCSRCRPfAVK6AzChonmOiVzEZWAs1L
uGNX+RlO4taR9vC8KTECQQCLe+kQ/k24bH8RC5cCmvxMaPnYN3mXIoPZYCchxjPv
UHUebON90WEIiQazoXugkCkyRVsXHnglLXUm7CpDjFXt 
-----END RSA PRIVATE KEY-----"
```

## Caution

Please delete the .env file before pushing to github or bitbucket

## Disclaimer

Please note that this project is just a template on the use of BRI-API php sdk and may have bugs or errors.

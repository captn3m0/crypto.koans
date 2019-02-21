---
theme: default
paginate: true
footer: Razorpay
---

# <!--fit--> Practical Cryptography

# Requirements

-   `openssl version` (1.1.1a)
-   `curl --version` (7.64.0)
-   `php --version` (7.3.2)
-   `php -m |grep -e openssl -e curl`
-   `composer --version` (See <https://getcomposer.org/download/>) (1.8.4)
-   `docker --version` (18.09.2-ce)
-   `libtasn` (`brew install libtasn1`) (4.13)

---

# <!--fit--> practical cryptography

[_nemo_](nemo@razorpay.com)

---

<!--
_backgroundColor: rebeccapurple
_color: white
_class: lead
-->

# <!--fit--> Why

---

# Objectives

-   Get familiar with Crypto primitives
-   Hands-on with
    -   OpenSSL
    -   TLS
    -   Curl

---

# Non-Goals

-   Understanding all Crypto-Attacks (we'll discuss a couple)
-   Elliptic Curve Crypto
-   crypto-currencies :money_mouth_face:
-   Math
-   Intermediate Certs

---

# Requirements

-   `openssl version` (1.1.1a)
-   `curl --version` (7.64.0)
-   `php --version` (7.3.2)
-   `php -m |grep -e openssl -e curl`
-   `composer --version` (See <https://getcomposer.org/download/>) (1.8.4)
-   `docker --version` (18.09.2-ce)
-   `libtasn` (`brew install libtasn1`) (4.13)

---

# Setup

1.  Have a browser open with Google (Lots of googling needed for this)
2.  `git clone git@github.com/captn3m0/crypto.koans.git && cd crypto.koans`
3.  `composer install`

---

# <!--fit--> Koans 💃

---

# **koan**

_noun_, **plural ko·ans, ko·an.** _Zen._

1. a nonsensical or paradoxical question to a student for which an answer is demanded, the stress of meditation on the question often being illuminating.

---

# <!--fit--> What is the colour of wind?

---

## Ruby 💎

```
ruby path_to_enlightenment.rb

Thinking AboutAsserts
test_assert_truth has damaged your karma.

You have not yet reached enlightenment ...
<false> is not true.

Please meditate on the following code:
./about_asserts.rb:10:in `test_assert_truth'
path_to_enlightenment.rb:27

mountains are merely mountains
```

---

```ruby
 # We shall contemplate truth by testing reality, via asserts.
def test_assert_truth
  assert false # This should be true
end
```

---

# tl;dr

1.  Run tests
2.  Why is the test failing? (`koans/files` directories)
3.  Get it to pass

👌🏼 Don't Cheat

-   ❗ Means you must do something here
-   Keep a solutions.md file listing down commands as you run them

---

# Setup

1.  Have a browser open with Google (Lots of googling needed for this)
2.  `git clone git@github.com/captn3m0/crypto.koans.git && cd crypto.koans`
3.  `composer install`
4.  `vendor/bin/phpunit`
5.  `man openssl`, `man curl`

🧘‍♀️🧘‍♂️

---

# <!--fit--> `vendor/bin/phpunit`

---

# `OpensslKeyGenerationKoans.php`

`vendor/bin/phpunit --filter BOpensslKeyGenerationKoans`

## <!--fit--> Questions❓

---

# `FileFormatKoans.php`

`vendor/bin/phpunit --filter CFileFormatKoans`

## <!--fit--> Questions❓

-   What is PEM vs DER?

---

# Theory Break 1

-   Keys
-   Certificates
-   Signatures

---

# `CA Certificates`

`vendor/bin/phpunit --filter DCaCertificateKoans.php`

---

# <!--fit--> Generate A CA Certificate

---

# testCaCertificateExists

```haskell
openssl req -x509
-newkey rsa:1024
-keyout files/ca.key
-nodes
-out files/ca.pem
-subj '/CN=crypto.koans.invalid'
```

---

# <!--fit--> Generate a Certificate Signing Request

---

# <!--fit--> Generate a Certificate Signing Request

```haskell
openssl req -new
-key files/1.key
-subj '/CN=server.crypto.koans.invalid'
-out files/1.csr
```

---

# <!--fit--> Sign your CSR with your CA

---

# <!--fit--> Sign your CSR with your CA

```haskell
openssl x509 -req
-in files/1.csr
-CA files/ca.pem
-CAkey files/ca.key
-CAcreateserial
-out files/1.crt
```

---

# <!--fit--> What can a Certificate Do?

---

# What can a Certificate Do?

```perl
openssl x509
-in google.pem
-purpose
-noout #Remove this and retry
```

---

# <!--fit--> Generate a Client Certificate

---

# Generate a Client Certificate

## Step 1

```bash
printf "extendedKeyUsage=clientAuth\nkeyUsage=digitalSignature" > client.cnf
```

---

# Generate a Client Certificate

## Step 1

```bash
printf "extendedKeyUsage = clientAuth\nkeyUsage = " > client.cnf
```

## Step 2

```bash
# As Alice
openssl req -subj '/CN=client.crypto.koans'
-key files/client.key
-new
-out files/client.csr
# As Bob
openssl x509 -req -in files/alice.csr
-CA files/ca.pem
-CAkey files/ca.key
-CAcreateserial
-extfile client.cnf
-out files/alice.crt
```

## Step 3

1. Save alice.crt as `client.crt`
2. Save the CA pem file as `bob.pem`
3. See `testClientBundleGenerated`

---

# Theory Break 2

---

# What Alice Has

1. Client (`client.key`, `client.csr`, `client.crt`)
2. Bob's CA (`bob.pem`)

# What Bob Has

1. Server (`1.key`, `1.csr`, `1.crt`)
2. CA (`ca.pem`, `ca.key`)

---

# What Alice Has

1. Client (`client.key`, `client.crt`)
2. Bob's CA (`bob.pem`)

# What Bob Has

1. Server (`1.key`, `1.crt`)
2. CA (`ca.pem`)

---

# Where we're going

# <!--fit--> :whale: :rocket:

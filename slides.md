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

-   Understanding Crypto-Attacks
-   Elliptic Curve Crypto
-   crypto-currencies :money_mouth_face:

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

1. Have a browser open with Google (Lots of googling needed for this)
2. `git clone git@github.com/captn3m0/crypto.koans.git && cd crypto.koans`
3. `composer install`
4. `vendor/bin/phpunit`
5. `man openssl`, `man curl`

🧘‍♀️🧘‍♂️

---

# <!--fit--> Koans 💃

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

1. Run tests
2. Why is the test failing? (`koans/files` directories)
3. Get it to pass

👌🏼 Don't Cheat

-   ❗ Means you must do something here

---

# `OpensslKeyGenerationKoans.php`

## <!--fit--> Questions❓

---

# `FileFormatKoans.php`

## <!--fit--> Questions❓

-   What is PEM vs DER?

---

# Theory Break 1

-   Keys
-   Certificates
-   Signatures

---

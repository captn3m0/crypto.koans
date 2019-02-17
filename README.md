# crypto.koans

A collection of koans for learning basics of practical cryptography using koans.

_Target Audience_: People working with libcurl, libopenssl and having to deal with PKI. You should have a reasonable understanding of shell scripts and code.

# Requirements

-   `openssl version` (1.1.1a)
-   `curl --version` (7.64.0)
-   `php --version` (7.3.2)
-   `php -m |grep -e openssl -e curl`
-   `composer --version` (See <https://getcomposer.org/download/>) (1.8.4)
-   `docker --version` (18.09.2-ce)

## Setup

## Koans:

1.  OpenSSL Basics
2.  Generating Private Keys
3.  Understanding File Formats in Crypto
4.  Curl Basics
5.  CA Certificates and CSRs

This tries to prevent high-level tools and abstractions to get a better appreciation of how things are under the hood. However, while writing real-world crypto code, please use higher level abstractions such as `NaCl`.

## Learning Resources:

A few other links you should give a read while you are going through this:

### TLS

-   [Everything you should know about certificates and PKI but are too afraid to ask](https://smallstep.com/blog/everything-pki.html)
-   [HTTPS in teh real world](https://robertheaton.com/2018/11/28/https-in-the-real-world/)
-   [How does HTTPS actually work?](https://robertheaton.com/2014/03/27/how-does-https-actually-work/)
-   [TLS Illustrated: Every byte explained](https://tls.ulfheim.net/)

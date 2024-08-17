![build](https://github.com/tarach/self-signed-ssl-generator/actions/workflows/build.yaml/badge.svg)
##### Table of Contents
* [Installation](#installation)
* [Features](#features)
* [Basic examples](#basic-examples)
* [Advanced examples](#advanced-examples)
  * [Generating Alpine Docker SSL certificate](#generating-alpine-docker-ssl-certificate)
    * [Generate](#generate)
    * [Upload](#upload)
    * [Test](#test)
* [Credits](#credits)
  *  [Why](#why)

# Installation
Use as ```sslgen --help```
```bash
docker pull tarach/sslg
echo 'alias sslgen="docker run -it --rm -v \$(pwd):/app tarach/sslg sslgen.sh"' >> ~/.bashrc
source ~/.bashrc
```

# Features
* Generate SSL by answering questions
* Generate SSL by passing arguments on cli
* Generate SSL by selecting presets ( schema ) from [configuration file](/example/sslgen.yaml) or on CLI ```-e, --schema```
* Overwrite schema options

# Basic examples
## Generate by answering questions
Most basic version of the command will ask user some questions necessary to generate the SSL certificate.
### Command
```bash
sslgen -vv
```
### Output
```text
Country Name (2 letter code - ISO 3166-1 alfa-2)
: UK
State or province
: Wales
Locality name (e.g., city)
: Newport
Organization Name (e.g., company)
: My Company ltd.
Organization Unit Name (e.g., section)
: IT Department
Common Name (e.g., server FQDN)
: domain.com
Email address
: email@address.com
[2024-02-03T03:37:41.139266+00:00] logger.INFO: Output directory set to [/app/ssl-cert/]. [] []
[2024-02-03T03:37:41.140534+00:00] logger.INFO: Saving certificate signing request (CSR) as file [csr.req]. [] []
[2024-02-03T03:37:41.140662+00:00] logger.INFO: Saving certificate as file [cert.pem]. [] []
[2024-02-03T03:37:41.140735+00:00] logger.INFO: Saving private key as file [pkey.key]. [] []
```
## Generate by providing answers as arguments
Options used below set default answers to questions. 
Using ``-s`` or ``--skip`` make confirming those answers not necessary.
### Command
```bash
sslgen -vv -s --un=DE \
  --sp=Hesse \
  --ln=Frankfurt \
  --on="Name der Firma" \
  --oun="IT Abteilung" \
  --cn=domain.com \
  --ea=email@address.com
```
### Output
```text
[2024-02-03T03:22:07.988076+00:00] logger.INFO: Output directory set to [/app/ssl-cert/]. [] []
[2024-02-03T03:22:07.988709+00:00] logger.INFO: Saving certificate signing request (CSR) as file [csr.req]. [] []
[2024-02-03T03:22:07.988827+00:00] logger.INFO: Saving certificate as file [cert.pem]. [] []
[2024-02-03T03:22:07.988958+00:00] logger.INFO: Saving private key as file [pkey.key]. [] []
```
# Advanced examples

## Generating Alpine Docker SSL certificate
Code snippets to generate and upload SSL certificate for Docker API calls 

### Generate
#### Get configuration file
```bash
wget https://raw.githubusercontent.com/tarach/self-signed-ssl-generator/master/example/sslgen.yaml
```
#### Generate certificate authority and it's private key
When asked to choose schema type ``1`` ( caSchema ) and press ``Enter``
##### Command
```bash
sslgen -vv
```
##### Output
```text
[1] caSchema
[2] serverSchema
[3] clientSchema
Choose schema: 1
[2024-01-28T05:10:48.683176+00:00] logger.INFO: Using schema [caSchema]. [] []
[2024-01-28T05:10:48.712605+00:00] logger.INFO: Output directory set to [/app/ssl-cert/]. [] []
[2024-01-28T05:10:48.712759+00:00] logger.INFO: Saving certificate as file [ca.pem]. [] []
[2024-01-28T05:10:48.713027+00:00] logger.INFO: Saving private key as file [privkey.pem]. [] []
```
#### Generate server set of files
##### Command
Use IP Address or domain name of your docker server that will be used to invoke the connection. When incorrect the client connecting to the server won't be able to confirm server identity, and it will cause an error. 
```bash
export COMMON_NAME=192.168.56.10
sslgen -vv --schema=2 -o --cn=${COMMON_NAME}
```
##### Output
```text
[2024-01-28T05:15:41.434671+00:00] logger.INFO: Using schema [serverSchema]. [] []
[2024-01-28T05:15:41.435481+00:00] logger.INFO: Directory [/app/ssl-cert/] already exists. Will overwrite files. [] []
[2024-01-28T05:15:41.453789+00:00] logger.INFO: Output directory set to [/app/ssl-cert/]. [] []
[2024-01-28T05:15:41.454168+00:00] logger.INFO: Saving certificate signing request (CSR) as file [server.req]. [] []
[2024-01-28T05:15:41.454423+00:00] logger.INFO: Saving certificate as file [server.pem]. [] []
[2024-01-28T05:15:41.455042+00:00] logger.INFO: Saving private key as file [server.key]. [] []
```
#### Generate client set of files
##### Command
```bash
sslgen -vv --schema=clientSchema
```
##### Output
```text
[2024-01-28T05:36:23.078259+00:00] logger.INFO: Using schema [clientSchema]. [] []
[2024-01-28T05:36:23.079187+00:00] logger.INFO: Directory [/app/ssl-cert/] already exists. Will overwrite files. [] []
Country Name (2 letter code - ISO 3166-1 alfa-2)
default: "PL"
:
State or province
default: "Mazovia"
:
Locality name (e.g., city)
default: "Warsaw"
:
Organization Name (e.g., company)
default: "My Company Ltd."
:
Organization Unit Name (e.g., section)
default: "IT Dept."
:
Common Name (e.g., server FQDN)
default: "docker-client"
:
Email address
default: "address@email.com"
:
[2024-01-28T05:36:23.144258+00:00] logger.INFO: Output directory set to [/app/ssl-cert/]. [] []
[2024-01-28T05:36:23.144507+00:00] logger.INFO: Saving certificate signing request (CSR) as file [client.req]. [] []
[2024-01-28T05:36:23.144620+00:00] logger.INFO: Saving certificate as file [client.pem]. [] []
[2024-01-28T05:36:23.144810+00:00] logger.INFO: Saving private key as file [client.key]. [] []
```

### Upload
```bash
export USR=userName
ssh ${USR}@192.168.56.10 mkdir ssl-cert/
cd ssl-cert/
sftp ${USR}@192.168.56.10:ssl-cert
put *

ssh ${USR}@192.168.56.10:ssl-cert
sudo cp ca.pem /root/.docker/
sudo cp server.key /root/.docker/key.pem
sudo cp server.pem /root/.docker/cert.pem
```

### Test
```bash
cat client.pem >> cert-and-key.pem
cat client.key >> cert-and-key.pem
curl -vv --cacert ca.pem --cert cert-and-key.pem https://192.168.56.10:2376/version
```

# Credits
Some time ago I've stumbled upon necessity to generate a ``self-signed SSL certificate`` and convert it a bunch of times.
I found myself completely lost trying to navigate between CSR, PKEY, CRT, PEM and a bunch of other files.
[The topic I've created on SO](https://stackoverflow.com/questions/63195304/difference-between-pem-crt-key-files) clearly proves than I'm not the only person this is confusing.

Then again after a while I needed to generate a certificate again to communicate with Docker API. After going through complete hell re-learning how to do it again 
I've found [this code](https://github.com/php-http/socket-client/blob/2.x/tests/server/ssl/generate.sh).

What struck me the most was a complete lack of generators written in PHP. Some existed but relied on terminal openssl command. 

## Why

* To create a PHP CLI capable of generating Self-Signed SSL Certificate without relying on external command tools.
* To over-engineer the hell out of [symfony/console](https://symfony.com/doc/current/components/console.html)
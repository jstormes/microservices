# PHP Training and Development tools

This repository is a quick start tool for PHP Training and Development.  It has several infrastructure services and 
tools to simulate many of the services found in a PHP production environment.

To start this development container:

* Make sure you have Docker and Docker-Compose installed on your workstation.
    * [https://www.docker.com/community-edition](https://www.docker.com/community-edition)
    * Disable any existing web server running on ports 443 and 80
    * Disable any MySQL or MariaDB server running on port 3306 and 4000
* Start the Docker Container from the project directory:
    * `docker-compose run --service-ports lamp bash`
* Create your web app
    * `composer create-project zendframework/zend-expressive-skeleton expressive`
    * Open your browser to [https://expressive.loopback.world](https://expressive.loopback.world).

## Apache2 hosted sites

You can quickly create a new Apache2 hosted site by creating a set of directories and files in the root project 
directory.  The top-level directory will become part of the domain name and will serve and contentment under that in a 
public directory.

If you create a directory called "mysite" and inside that, create a directory called "public" with the file "index.php" 
inside that, you can see the contents by browsing to “https://mysite.loopback.world”.

## PHP hosted sites

In addition to Apache2 hosted websites you can create sites hosted directly hosted by PHP.  These sites can be hosted 
on ports 8081 and 8082, and can be opened with your browser at http://loopback.world:8081 or http://loopback.world:8082.

You can find a list of tools by opening your browser to https://loopback.world.

## XDebug

By default this setup will try and connect to the hosting machines ip address for PHP debugging.  The default port is 
9000.  For help with XDebug see [https://xdebug.org/docs/remote](https://xdebug.org/docs/remote).

# Tools Quick Start

 * [https://redis.loopback.world](https://redis.loopback.world) 
 * [https://sql.loopback.world](https://sql.loopback.world) 
 * [https://ldap.loopback.world](https://ldap.loopback.world)
 
## Local MySQL
 
 * [https://sql.loopback.world](https://sql.loopback.world)
 * user: `root`
 * password: `naked`
 * external port: 4000
 
## Local LDAP

 * [https://ldap.loopback.world](https://ldap.loopback.world)
 
### Pre-installed LDAP users for development and testing:

 * Admin
    * Distinguished Name: cn=admin,dc=loopback,dc=world
    * Password = naked
 * Test User
    * Distinguished Name: cn=test.u,dc=us,dc=loopback,dc=world
    * Password = test

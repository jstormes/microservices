<!DOCTYPE html>
<html>
<body>

<h1>Docker image for Training and Development</h1>

<p>This Docker image is for training and rapid development.  This purpose of this image is to simulate the
    infrastructure present in many PHP production environments with minimum headaches for setup.  This image also
    includes several tools for managing that infrastructure like PhpMyAdmin and PhpLDAPadmin.</p>

<h2>Tools:</h2>

<ul>
    <li><a href="https://sql.loopback.world">PhpMyAdmin</a></li>
    <li><a href="https://ldap.loopback.world">PhpLDAPadmin</a></li>
    <li><a href="https://redis.loopback.world">PhpRedisAdmin</a></li>
</ul>

<h1>Creating Apache2 hosted websites</h1>

<p>You can quickly create a new Apache2 hosted site by creating a set of directories and files in the root project
    directory.  The top-level directory will become part of the domain name and will serve and contentment under that
    in a public directory.</p>

<p>If you create a directory called "mysite" and inside that, create a directory called "public" with the file
    "index.php" inside that, you can see the contents by browsing to "https://mysite.loopback.world".</p>

<h1>Creating PHP hosted websites</h1>

<p>In addition to Apache2 hosted websites you can create sites hosted directly hosted by PHP.  These sites can be
    hosted on ports 8081 and 8082, and can be opened with your browser at "http://loopback.world:8081" or
    "http://loopback.world:8082".</p>

</body>
</html>

Installation
============

```bash
cd /var/www/html
git clone https://github.com/asmundstavdahl/sccm-log-viewer.git sccm
firefox http://localhost/sccm/log
```

What is this?
=============

This is a small piece of sortware that makes it easeier to view SCCM log files. With it you can search with regular expressions and sort log entries.

Requirements
============

 - A web server with PHP
 - Sufficiently high "post_max_size" and "upload_max_filesize" in php.ini
 - A reasonably modern browser

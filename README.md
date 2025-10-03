# Twindle-Build-Filesystem-Manager

This is a PHP script used to manage the filesystem for builds on our build server. This is mainly used to circumvent [weird behavior](https://github.com/SamKirkland/ftp-deploy/pull/20) in the FTP deploy action that prohibits us from uploading builds while preserving old ones.

This utility may also be used to manage pruning old builds as well later on without having to resort to a cron job.

## Testing

To run tests run:

```sh
./vendor/bin/phpunit test
```

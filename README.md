# Google-Drive-Stream-Proxy
[![Software License](https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat-square)](LICENSE)
> Alternative Google Drive Streaming

An open source code which streams using the host itself to provide the initiation of proxy. You can view the demo [here](https://japnimeserver.com/drive/).

## What's in this script?
- Drive Proxy Link
- Cache system
- Randomizer Key
- Drive Proxy Link Streamer

## Example
```php
<?php
// Drive Proxy Class
require_once(__DIR__ .'/class.DriveProxy.php');

// Create new class for id
$drive = new DriveProxy();
$drive->driveid('');
$drive->proxy_link()
?>
```

## Information
- You can check the update logs [here](https://github.com/japnimedev/Google-Drive-Stream-Proxy/blob/master/LOG.md).
- If you have questions, just drop from the issues tab so I can answer it as soon as possible.

## Contribution
- Fork and star this repository.
- Pull requests can be follow up here, let's help each other to make the script better.

## Suggestion
- [Facebook Stream Player](https://github.com/japnimedev/Facebook-Stream-Player)

## Credits
- [p4v800m/google-drive-proxy-jwplayer](https://github.com/p4v800m/google-drive-proxy-jwplayer)

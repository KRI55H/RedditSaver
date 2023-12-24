<img src="assets/logo.svg?v1.3" alt="Reddit Saver" height="80">

![Packagist Version](https://img.shields.io/packagist/v/kri55h/reddit-saver.svg)
![Latest Version](https://img.shields.io/github/v/release/kri55h/reddit-saver)
![Packagist Downloads](https://img.shields.io/packagist/dt/kri55h/reddit-saver)
![PHP Version](https://img.shields.io/badge/PHP-^8.0-mediumslateblue)
![License](https://img.shields.io/github/license/kri55h/reddit-saver)


### Installation

To install this package, follow these steps:
```bash
composer require kri55h/reddit-saver
```

Once the package is installed, add the following configuration to your `composer.json` file within the `autoload` section:

```json
{
  "autoload": {
    "psr-4": {
      "Kri55h\\RedditSaver\\": "vendor/kri55h/reddit-saver/src/"
    }
  }
} 
```

Ensure to modify the namespace and path accordingly if needed.

After updating the `composer.json` file, run the following command to let Composer autoload your package:
```bash
composer dump-autoload
```
## Usage
Here's an example demonstrating how to use the `RedditSaver` class from this package:
```php
use Kri55h\RedditSaver\RedditSaver;

try {
    $reddit = new RedditSaver();
    $reddit->setPostURL('<reddit_post_url>');
    $videoSaved = $reddit->saveVIDEO();

    if ($videoSaved) {
        // Video saved successfully
        return 'Video saved!';
    } else {
        // Handle if video saving failed
        return 'Failed to save video.';
    }
} catch (Exception $e) {
    // Handle any exceptions or errors that occurred during the process
    return 'An error occurred: ' . $e->getMessage();
}
```
Replace `<reddit_post_url>` with the actual URL of the Reddit post you want to save as a video.

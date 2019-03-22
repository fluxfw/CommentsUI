Comments ui

### Usage

#### Composer
First add the following to your `composer.json` file:
```json
"require": {
  "srag/commentsui": ">=0.1.0"
},
```
And run a `composer install`.

If you deliver your plugin, the plugin has it's own copy of this library and the user doesn't need to install the library.

Tip: Because of multiple autoloaders of plugins, it could be, that different versions of this library exists and suddenly your plugin use an older or a newer version of an other plugin!

So I recommand to use [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger) in your plugin.

#### Comment ActiveRecord
First you need to implement a `Comment` active record class with your own table name
```php
...
use srag\CommentsUI\x\Comment\AbstractComment;
...
class Comment extends AbstractComment {

	const TABLE_NAME = "x";
}
```

Add an update step to your `dbupdate.php`
```php
...
<#x>
<?php
\srag\Plugins\x\Comment\Comment::updateDB();
?>
```

and not forget to add an uninstaller step in your plugin class too
```php
...
use srag\Plugins\x\Comment\Comment;
...
self::dic()->database()->dropTable(Comment::TABLE_NAME, false);
...
```

#### Async ctrl class
```php
...
use srag\CommentsUI\x\Ctrl\AbstractCtrl;
use srag\Plugins\x\Comment\Comment;
...
/**
 * ...
 *
 * @ilCtrl_isCalledBy srag\Plugins\x\Comment\Ctrl\Ctrl: ilUIPluginRouterGUI
 */
class Ctrl extends AbstractCtrl {
	...
	const COMMENTS_CLASS_NAME = Comment::class;
	...
	/**
	 * @inheritdoc
	 */
	public function getCommentsArray(int $report_obj_id, int $report_user_id): array {
		...
	}
}
```

#### Trait usage
Your class in this you want to use CommentsUI needs to use the trait `CommentsUITrait`
```php
...
use srag\CommentsUI\x\Utils\CommentsUITrait;
...
class x {
...
use CommentsUITrait;
...
```

#### UI usage
```php
...
use srag\Plugins\x\Comment\Ctrl\Ctrl;
...
self::output()->getHTML(self::commentsUI()->withCtrlClass(new Ctrl()));
```

### Dependencies
* ILIAS 5.3 or ILIAS 5.4
* PHP >=7.0
* [composer](https://getcomposer.org)
* [npm](https://nodejs.org)
* [jquery-comments](https://www.npmjs.com/package/jquery-comments)
* [srag/dic](https://packagist.org/packages/srag/dic)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/LCOMMENTSUI
* Bug reports under https://jira.studer-raimann.ch/projects/LCOMMENTSUI
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_LCOMMENTSUI

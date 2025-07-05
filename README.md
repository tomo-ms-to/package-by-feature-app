# Package by feature実装方法

## laravel10のインストール
laravel12じゃうまくいかなかったから

webコンテナに入って以下を実行
```
composer create-project --prefer-dist laravel/laravel . "10.*"
```

localhostでlaravelのtop画面が映ることを確認

## packageディレクトリの作成
package-app配下にpackagesディレクトを作成し、ディレクトリ構成を以下のようにする。
```
package-app
├── app/
└── packages
    └──message
       ├── src/
       │   ├──Http
       │   │  └─Controllers
       │   └──Providers
       └──routes
          └──web.php
```

## オートロードの実装
composer.jsonに以下を追加
```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Package\\Message\\": "packages/message/src", //この行を追加
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
```

これにより、名前空間でPackage\Message\~とすると、packages/message/src配下のファイルを探してくれるようになる。

<br>

# Message機能の作成(json返すだけ)
## Providerの追加
Providers配下にMessageServiceProvider.phpを作成し、以下をコピペ
```
<?php

namespace Package\Message\Providers;

use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ルーティングの読み込み
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
```

## Controllerの追加
Controllers配下にMessageController.phpを追加し、以下をコピペ
```
<?php

namespace Package\Message\Http\Controllers;

use App\Http\Controllers\Controller; // Appのベースコントローラーを継承

class MessageController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'message' => 'Hello from the MessageController!'
        ]);
    }
}
```

## ルーティングの追加
web.phpに以下をコピペ
```
<?php

use Illuminate\Support\Facades\Route;
use Package\Message\Http\Controllers\MessageController;

Route::prefix('message')->group(function () {
    Route::get('/', MessageController::class);
});
```

## サービスプロバイダーに登録
```
// ...
'providers' => ServiceProvider::defaultProviders()->merge([
    /*
     * Package Service Providers...
     */
    Package\Message\Providers\MessageServiceProvider::class, // この行を追加

    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
])->toArray(),
// ...
```

最後にcomposer.jsonを変更したので、Webコンテナ内で以下のコマンドを実行してAutoloaderを更新します。
```
composer dump-autoload
```

そしてlocalhost/messageにアクセスすると、JSONが表示されました。

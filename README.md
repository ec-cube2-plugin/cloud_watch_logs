CloudWatch Logs プラグイン
=========================

本プラグインについて
-----------------

EC-CUBE2のロギングをMonologを利用し、CloudWatch Logsに置き換えます。
EC-CUBE2内で利用されている `GC_Utils_Ex::gfPrintLog` `GC_Utils::gfPrintLog` メソッドを置き換えます。

管理画面の システム設定＞EC-CUBE ログ表示 でCloudWatch Logsのログが確認可能です。

`SC_Helper_HandleError` も置換され、Json formatのログで非常に綺麗な形で出力されます。
そのため、CloudWatch Logs Insights でのログ分析も非常に容易になります。

ブラウザ上にはセキュリティ上 `session_id` をできるだけ表示しないようにします。


ログ
----

Monolog の設定は以下となっています。

### Formatter

#### JsonFormatter

`GC_Utils_Ex::gfPrintLog` で `verbose` が指定されている場合と、`error` への記録の場合には、 `includeStacktraces` が自動的に有効になります。
Stacktraces は `trace` と `previous` に記録されます。


### Processor

#### UserProcessor

EC-CUBE2のログイン情報と `session_id` を記録します。
フロントでは `customer_id` `email` 管理画面では `login_id` `authority` が記録されます。

#### WebProcessor

Webリクエストの情報を `extra` 以下に記録します。
記録される値は、 `url` `ip` `http_method` `server` `referrer` です。



アーキテクチャ
------------

以下の2点はプラグインをComposerでインストールした場合、EC-CUBE2上でプラグインを有効にする前に有効化されます。
もし、直接インストールしている場合には、プラグインの `vendor/autoload.php` を `html/define.php` の一番最後で読み込む必要があります。

`GC_Utils` は拡張できないためトリッキーな方法を利用しクラスを拡張しています。
また、振る舞いの悪い決済プラグインでは、 `GC_Utils_Ex` を利用せず `GC_Utils` を直接利用しているため、本プラグインでは `GC_Utils` を拡張しています。

`SC_Helper_HandleError_Ex` も同様に置き換えられます。
こちらは、 `app_initial.php` にて読み込まれるため、事前に置換します。


設定
----

`config.php` に以下を追加してください。

```
define('AWS_REGION', 'ap-northeast-1');
define('AWS_ACCESS_KEY_ID', '');
define('AWS_SECRET_ACCESS_KEY', '');
define('CLOUDWATCH_LOGS_GROUP_NAME', '/eccube/SampleLogGroupName/%name%');
define('CLOUDWATCH_LOGS_STREAM_NAME', 'eccube');
define('CLOUDWATCH_LOGS_RETENTION', null);
```

### CLOUDWATCH_LOGS_GROUP_NAME

CloudWatch Logs のロググループ

`%name%` はログファイルに指定されているファイル名のうち、 `.log` を省いたものに置換されます。

例)  `ERROR_LOG_REALFILE` -> `error`

### CLOUDWATCH_LOGS_STREAM_NAME

CloudWatch Logs のログストリーム

`%name%` はログファイルに指定されているファイル名のうち、 `.log` を省いたものに置換されます。

例)  `ERROR_LOG_REALFILE` -> `error`

### CLOUDWATCH_LOGS_RETENTION

CloudWatch Logs のログ保持期間。 `null` を設定すると失効しなくなります。
作成時のみ有効です。


ログの保存期間について
-------------------

過去の情報流出からもフォレンジックの際にはログを必ず活用します。
そのため、ログがない場合にはフォレンジックにおける結論が非常に難しくなる場合も多く、適切な期間ログを保存することが必要です。

ログの保存と廃棄に CloudWatch Logs を利用することで、適切なロギングを行える環境を本プラグインで提供します。
また、CloudWatch Logsでは膨大なログであっても迅速な調査が可能となります。


IAM
---

AWSでの動作では、IAM Role を利用することを *強く* 推奨します。EC2インスタンスなどに IAM Role を付与してください。
IAM User の `AWS_ACCESS_KEY_ID` `AWS_SECRET_ACCESS_KEY` は利用しないようにしましょう。

必要な権限は以下です。

`123456789012` `SampleLogGroupName` 部分は適切に読み替えてください。

```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "logs:CreateLogGroup",
                "logs:DescribeLogGroups"
            ],
            "Resource": "arn:aws:logs:ap-northeast-1:123456789012:*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "logs:CreateLogStream",
                "logs:DescribeLogStreams",
                "logs:PutRetentionPolicy"
            ],
            "Resource": "arn:aws:logs:ap-northeast-1:123456789012:log-group:/eccube/SampleLogGroupName/*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "logs:PutLogEvents",
                "logs:GetLogEvents"
            ],
            "Resource": "arn:aws:logs:ap-northeast-1:123456789012:log-group:/eccube/SampleLogGroupName/*:*:*"
        }
    ]
}
```

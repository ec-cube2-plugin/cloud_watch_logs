CloudWatch Logs プラグイン
=========================

本プラグインについて
-----------------

EC-CUBE2のロギングをCloudWatch Logsに置き換えます。
`GC_Utils::gfPrintLog` メソッドを置き換えます。


設定
----

`%name%` は、 `error` のようにファイル名に置き換えられます。

```
define('AWS_REGION', 'ap-northeast-1');
define('CLOUDWATCH_LOGS_GROUP_NAME', '/eccube/SampleLogGroupName/%name%');
define('CLOUDWATCH_LOGS_STREAM_NAME', 'eccube');
```

オプションで以下のパラメーターも設定できます。

```
define('CLOUDWATCH_LOGS_RETENTION', 14);
```


アーキテクチャ
------------

本来、 `GC_Utils` は拡張できないためトリッキーな方法を利用しクラスを拡張しています。
また、振る舞いの悪い決済プラグインでは、 `GC_Utils_Ex` を利用せず `GC_Utils` を直接利用しているため、本プラグインでは `GC_Utils` を拡張しています。


IAM
---

AWSでの動作では、IAM Role を利用することを *強く* 推奨します。EC2インスタンスなどに IAM Role を付与してください。
IAM User の `AWS_ACCESS_KEY_ID` `AWS_SECRET_ACCESS_KEY` は利用してはいけません。

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
                "logs:DescribeLogGroups",
                "logs:CreateLogStream",
                "logs:DescribeLogStreams",
                "logs:PutRetentionPolicy",
                "logs:PutLogEvents",
                "logs:GetLogEvents"
            ],
            "Resource": "arn:aws:logs:ap-northeast-1:123456789012:log-group:/eccube/SampleLogGroupName/*:*"
        }
    ]
}
```

CloudWatch Logs プラグイン
=========================

本プラグインについて
-----------------

EC-CUBE2のロギングをCloudWatch Logsに置き換えます。
`GC_Utils::gfPrintLog` メソッドを置き換えます。


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
            "Resource": "arn:aws:logs:ap-northeast-1:123456789012:log-group:SampleLogGroupName:*"
        }
    ]
}
```

<link href="<!--{$smarty.const.PLUGIN_HTML_URLPATH}-->CloudWatchLogs/fontawesome/css/all.css" rel="stylesheet">
<style>
    .c-log {
        font-size: 12px;
        line-height: 16px;
        border: 1px solid #dddddd;
    }

    .c-log__block {
        border-bottom: 1px solid #dddddd;
    }
    .c-log__block:last-child {
        border-bottom: none;
    }

    .c-log__row {
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 5px 0;
        padding: 0 20px;
    }
    .c-log__row:last-child {
        margin-bottom: 10px;
    }
    .c-log__row.is-header {
        position: relative;
        margin: 0 0 10px 0;
        padding: 10px 20px;
        background-color: #fafafa;
    }
    .c-log__row.is-header:before {
        position: absolute;
        left: 0;
        content: "";
        display: block;
        width: 6px;
        height: 100%;
        background-color: #666;
    }
    .c-log__row.is-header.is-debug:before {
        background-color: #777;
    }
    .c-log__row.is-header.is-info:before {
        background-color: #5bc0de;
    }
    .c-log__row.is-header.is-notice:before {
        background-color: #5bc0de;
    }
    .c-log__row.is-header.is-warning:before {
        background-color: #f0ad4e;
    }
    .c-log__row.is-header.is-error:before {
        background-color: #d9534f;
    }
    .c-log__row.is-header.is-critical:before {
        background-color: #d9534f;
    }
    .c-log__row.is-header.is-alert:before {
        background-color: #d9534f;
    }
    .c-log__row.is-header.is-emergency:before {
        background-color: #d9534f;
    }
    .c-log__row.is-header + .c-log__row {
        margin: 10px 0;
    }

    .c-log__level {
        margin-right: 20px;
        white-space: nowrap;
        font-weight: bold;
    }

    .c-log__http_method {
        margin-right: 10px;
    }

    .c-log__url {
        margin-right: 10px;
        overflow: hidden;
    }

    .c-log__date {
        margin-left: auto;
        white-space: nowrap;
    }

    .c-log__message {
    }

    .c-log__context {
    }

    .c-log__trace-button {
        margin-left: 10px;
    }

    .c-log__trace {
        display: none;
        padding: 10px;
        border: 1px solid #dddddd;
        background-color: #fcfcfc;
    }

    .c-log__member {
        margin-right: 10px;
        font-size: 10px;
    }

    .c-log__customer {
        margin-right: 10px;
        font-size: 10px;
    }

    .c-log__ip {
        margin-right: 10px;
        font-size: 10px;
    }

    /* c-icon */
    .c-icon {
        display: inline-block;
        padding: 4px;
        border-radius: 4px;
        border: 1px solid #666;
        color: #666;
        font-size: 10px;
        line-height: 10px;
    }
    a.c-icon:link,
    a.c-icon:visited {
        color: #666;
    }
    a.c-icon:hover {
        color: #666;
        background-color: #efefef;
    }
    .c-icon.is-small {
        font-size: 9px;
        line-height: 9px;
    }
    .c-icon.is-large {
        padding: 4px 6px;
        font-size: 14px;
        line-height: 14px;
    }

    .c-icon.is-get {
        border-color: #777;
        color: #777;
    }
    .c-icon.is-post {
        border-color: #777;
        color: #777;
    }

    .c-icon.is-debug {
        border-color: #777;
        background-color: #777;
        color: #fff;
    }
    .c-icon.is-info {
        border-color: #5bc0de;
        background-color: #5bc0de;
        color: #fff;
    }
    .c-icon.is-notice {
        border-color: #5bc0de;
        background-color: #5bc0de;
        color: #fff;
    }
    .c-icon.is-warning {
        border-color: #f0ad4e;
        background-color: #f0ad4e;
        color: #fff;
    }
    .c-icon.is-error {
        border-color: #d9534f;
        background-color: #d9534f;
        color: #fff;
    }
    .c-icon.is-critical {
        border-color: #d9534f;
        background-color: #d9534f;
        color: #fff;
    }
    .c-icon.is-alert {
        border-color: #d9534f;
        background-color: #d9534f;
        color: #fff;
    }
    .c-icon.is-emergency {
        border-color: #d9534f;
        background-color: #d9534f;
        color: #fff;
    }

    /* c-text */
    .c-text {
        color: #666;
        font-size: 10px;
    }
    .c-text.is-small {
        font-size: 9px;
    }
    .c-text.is-large {
        font-size: 14px;
    }

    .c-text.is-debug {
        color: #777;
    }
    .c-text.is-info {
        color: #5bc0de;
    }
    .c-text.is-notice {
        color: #5bc0de;
    }
    .c-text.is-warning {
        color: #f0ad4e;
    }
    .c-text.is-error {
        color: #d9534f;
    }
    .c-text.is-critical {
        color: #d9534f;
    }
    .c-text.is-alert {
        color: #d9534f;
    }
    .c-text.is-emergency {
        color: #d9534f;
    }

    /* c-date */
    .c-date {
        color: #7c7c7c;
    }

    /* c-message */
    .c-message {
        font-family: Monaco,Consolas,Courier New,monospace;
        font-size: 13px;
        line-height: 22px;
        word-break: break-all;
        color: #333;
    }

    /* c-url */
    .c-url {
        font-family: Monaco,Consolas,Courier New,monospace;
        color: #7c7c7c;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    /* c-context */
    .c-context {
        word-break: break-all;
        color: #c0c0c0;
    }
    .c-context__file {
        font-family: Monaco,Consolas,Courier New,monospace;
        color: #7c7c7c;
        font-weight: bold;
    }
    .c-context__line {
        font-family: Monaco,Consolas,Courier New,monospace;
        color: #7c7c7c;
        font-weight: bold;
    }
    .c-context__class {
        font-family: Monaco,Consolas,Courier New,monospace;
        color: #7c7c7c;
        font-weight: bold;
    }
    .c-context__type {
        font-family: Monaco,Consolas,Courier New,monospace;
        color: #7c7c7c;
    }
    .c-context__function {
        font-family: Monaco,Consolas,Courier New,monospace;
        color: #7c7c7c;
        font-weight: bold;
    }

    /* c-member */
    .c-member {
        color: #7c7c7c;
    }
    .c-member__authority {
        color: #999;
    }

    /* c-customer */
    .c-customer {
        color: #7c7c7c;
    }

    /* c-ip */
    .c-ip {
        color: #7c7c7c;
    }

    /* c-list */
    ol.c-list {
        counter-reset: number;
    }
    .c-list__item {
        padding-left: 30px;
        position: relative;
    }
    .c-list__item:before{
        counter-increment: number;
        content: counter(number) ".";
        position: absolute;
        left: 0;
        font-family: Monaco,Consolas,Courier New,monospace;
        line-height: 17px;
        color: #7c7c7c;
    }
    .c-list__item + .c-list__item {
        margin-top: 7px;
    }
</style>

<!--{if count($arrErr) >= 1}-->
<div class="attention">
    <!--{foreach from=$arrErr item=err}-->
    <!--{$err}-->
    <!--{/foreach}-->
</div>
<!--{/if}-->

<form action="?" name="form1" style="margin-bottom: 1ex;">
    <!--{assign var=key value="log"}-->
    <select name="<!--{$key|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
        <!--{html_options options=$arrLogList selected=$arrForm[$key]}-->
    </select>
    <!--{assign var=key value="line_max"}-->
    直近の<input type="text" name="<!--{$key|h}-->" value="<!--{$arrForm[$key].value|h}-->" size="6" maxlength="<!--{$arrForm[$key].length|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />行
    <a class="btn-normal" href="javascript:;" onclick="form1.submit(); return false;"><span>読み込む</span></a>
</form>

<div class="c-log">
    <!--{foreach from=$tpl_ec_log item=line}-->
    <!--{assign var=log value=$line.log}-->
    <div class="c-log__block">
        <div class="c-log__row is-header is-<!--{$log.level_name|lower}-->">
            <div class="c-log__level">
                <span class="c-text is-<!--{$log.level_name|lower}--> is-large"><!--{$log.level_name}--></span>
            </div>

            <!--{if $log.extra.url}-->
            <div class="c-log__http_method">
                <div class="c-http_method">
                    <span class="c-icon is-<!--{$log.extra.http_method|lower}-->"><!--{$log.extra.http_method}--></span>
                </div>
            </div>

            <div class="c-log__url">
                <div class="c-url">
                    <!--{$log.extra.url}-->
                </div>
            </div>
            <!--{/if}-->

            <div class="c-log__date">
                <span class="c-date"><i class="far fa-clock"></i> <!--{$log.datetime.date}--></span>
            </div>
        </div>

        <div class="c-log__row">
            <div class="c-log__message">
                <div class="c-message">
                    <!--{$line.body|h|nl2br}-->
                </div>
            </div>
        </div>

        <!--{if $log.context}-->
        <div class="c-log__row">
            <div class="c-log__context">
                <div class="c-context">
                    <span class="c-context__file"><!--{$log.context.file|replace:$smarty.const.ROOT_REALDIR:''}--></span> at line <span class="c-context__line"><!--{$log.context.line}--></span>
                </div>
            </div>

            <!--{if $log.context.trace}-->
            <div class="c-log__trace-button">
                <a href="#"><i class="fas fa-angle-down"></i> Trace</a>
            </div>
            <!--{/if}-->
        </div>

        <!--{if $log.context.trace}-->
        <div class="c-log__row">
            <div class="c-log__trace">
                <ol class="c-list">
                    <!--{foreach from=$log.context.trace item=trace}-->
                    <li class="c-list__item">
                        <div class="c-context">
                            <!--{if $trace.file}--><span class="c-context__file"><!--{$trace.file|replace:$smarty.const.ROOT_REALDIR:''}--></span> in <!--{else}-->call <!--{/if}-->
                            <!--{if $trace.class}--><span class="c-context__class"><!--{$trace.class}--></span><span class="c-context__type"><!--{$trace.type}--></span><!--{/if}--><span class="c-context__function"><!--{$trace.function}--></span>
                            <!--{if $trace.line}-->at line <span class="c-context__line"><!--{$trace.line}--></span><!--{/if}-->
                        </div>
                    </li>
                    <!--{/foreach}-->
                </ol>
            </div>
        </div>
        <!--{/if}-->
        <!--{/if}-->

        <!--{if $log.extra.member || $log.extra.customer.customer_id || $log.extra.ip}-->
        <div class="c-log__row">
            <!--{if $log.extra.member}-->
            <div class="c-log__member">
                <div class="c-member">
                    <span class="c-member__login_id"><i class="fas fa-user-tie"></i> <!--{$log.extra.member.login_id}--></span> <span class="c-member__authority">(<!--{$arrAUTHORITY[$log.extra.member.authority]}-->)</span>
                </div>
            </div>
            <!--{/if}-->

            <!--{if $log.extra.customer.customer_id}-->
            <div class="c-log__customer">
                <div class="c-customer">
                    <span class="c-customer__customer_id"><i class="fas fa-user"></i> <!--{$log.extra.customer.customer_id}--></span>
                    <!--{if $log.extra.customer.email}--><span class="c-customer__email"><i class="far fa-envelope"></i> <!--{$log.extra.customer.email}--></span><!--{/if}-->
                </div>
            </div>
            <!--{/if}-->

            <!--{if $log.extra.ip}-->
            <div class="c-log__ip">
                <div class="c-ip">
                    <span class="c-ip"><i class="fas fa-map-marker"></i> <!--{$log.extra.ip}--></span>
                </div>
            </div>
            <!--{/if}-->
        </div>
        <!--{/if}-->
    </div>
    <!--{/foreach}-->
</div>

<script>
$('.c-log__trace-button a').click(function (e) {
    var $trace = $(this).closest('.c-log__block').find('.c-log__trace');
    if ($trace.is(':visible')) {
        $trace.hide();
        $(this).html('<i class="fas fa-angle-down"></i> Trace');
    } else {
        $trace.show();
        $(this).html('<i class="fas fa-angle-up"></i> Trace');
    }

    e.stopPropagation();
    e.preventDefault();
});
</script>

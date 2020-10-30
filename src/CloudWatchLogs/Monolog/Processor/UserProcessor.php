<?php

namespace CloudWatchLogs\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;

class UserProcessor implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $records)
    {
        if (\GC_Utils_Ex::isFrontFunction() && isset($_SESSION['customer'])) {
            $records['extra']['customer']['customer_id'] = $_SESSION['customer']['customer_id'];
            $records['extra']['customer']['email'] = $_SESSION['customer']['email'];
        }

        if (\GC_Utils_Ex::isAdminFunction() && isset($_SESSION['login_id'])) {
            $records['extra']['member']['login_id'] = $_SESSION['login_id'];
            $records['extra']['member']['authority'] = $_SESSION['authority'];
            $records['extra']['session_id'] = session_id();
        }

        return $records;
    }
}

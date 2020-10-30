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
        if ($this->isFrontFunction()) {
            if (isset($_SESSION['customer']) && $_SESSION['customer']) {
                $records['extra']['customer']['customer_id'] = $_SESSION['customer']['customer_id'];
                $records['extra']['customer']['email'] = $_SESSION['customer']['email'];
            } else {
                $records['extra']['customer']['customer_id'] = null;
                $records['extra']['customer']['email'] = null;
            }
            $records['extra']['session_id'] = session_id();
        }

        if ($this->isAdminFunction()) {
            if (isset($_SESSION['login_id'])) {
                $records['extra']['member']['login_id'] = $_SESSION['login_id'];
                $records['extra']['member']['authority'] = $_SESSION['authority'];
            } else {
                $records['extra']['member']['login_id'] = null;
                $records['extra']['member']['authority'] = null;
            }
            $records['extra']['session_id'] = session_id();
        }

        return $records;
    }

    public function isFrontFunction()
    {
        return defined('FRONT_FUNCTION') && FRONT_FUNCTION === true;
    }

    public function isAdminFunction()
    {
        return defined('ADMIN_FUNCTION') && ADMIN_FUNCTION === true;
    }
}

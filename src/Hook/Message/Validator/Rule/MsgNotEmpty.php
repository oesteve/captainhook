<?php
/**
 * This file is part of HookMeUp.
 *
 * (c) Sebastian Feldmann <sf@sebastian.feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HookMeUp\Hook\Message\Validator\Rule;

use HookMeUp\Git\CommitMessage;

/**
 * Class MsgNotEmpty
 *
 * @package HookMeUp
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/hookmeup
 * @since   Class available since Release 0.9.0
 */
class MsgNotEmpty extends Base
{
    /**
     * SubjectStartsUpperCase constructor.
     */
    public function __construct()
    {
        $this->hint = 'Commit message can not be empty';
    }

    /**
     * Check if commit message is not empty.
     *
     * @param  \HookMeUp\Git\CommitMessage $msg
     * @return bool
     */
    public function pass(CommitMessage $msg)
    {
        return !$msg->isEmpty();
    }
}
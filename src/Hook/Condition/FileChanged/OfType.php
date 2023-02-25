<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\FileChanged;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Input;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Class OfType
 *
 * Example configuration:
 *
 * "action": "some-action"
 * "conditions": [
 *   {"exec": "\\CaptainHook\\App\\Hook\\Condition\\FileChanged\\OfType",
 *    "args": [
 *      "php"
 *    ]}
 * ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhookphp/captainhook
 * @since   Class available since Release 5.0.0
 */
class OfType implements Condition, Constrained
{
    /**
     * File type to check e.g. 'php' or 'html'
     *
     * @var string
     */
    private $suffix;

    /**
     * OfType constructor
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->suffix = $type;
    }

    /**
     * Return the hook restriction information
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::PRE_PUSH]);
    }

    /**
     * Evaluates the condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        $refsToPush = Input\PrePush::createFromStdIn($io->getStandardInput());

        foreach ($refsToPush->all() as $ref) {
            if ($ref->remote()->isZeroHash()) {
                continue;
            }
            $files = $repository->getDiffOperator()->getChangedFilesOfType(
                $ref->remote()->hash(),
                $ref->local()->hash(),
                $this->suffix
            );
            if (count($files) > 0) {
                return true;
            }
        }

        return false;
    }
}
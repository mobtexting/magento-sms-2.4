<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Util
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Util;

/**
 * Template Processor Interface
 *
 * @package Mobtexting\SMSNotifications\Util
 */
interface TemplateProcessorInterface
{
    /**
     * Replaces variables in a template with their real values
     *
     * @param string $template
     * @param string[] $data Key-value pairs to replace in template (key is variable, value is replacement)
     * @return string
     */
    public function process(string $template, array $data, string $listSeparator = ', '): string;
}

<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Block\System\Config\Form\Fieldset
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Block\System\Config\Form\Fieldset;

use Magento\Backend\Block\Template;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Extension Information Configuration Fieldset Block
 *
 * @package Mobtexting\SMSNotifications\Block\System\Config\Form\Fieldset
 */
class Info extends Fieldset
{
    private const TEMPLATE = 'Mobtexting_SMSNotifications::system/config/form/fieldset/info.phtml';

    /**
     * {@inheritdoc}
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function render(AbstractElement $element)
    {
        $block = $this->getLayout()
            ->createBlock(Template::class, 'sms_notifications_config_header')
            ->setTemplate(self::TEMPLATE)
            ->setData([
                'info_text' => $element->getComment() ?? ''
            ]);

        return $block->_toHtml();
    }
}

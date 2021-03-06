<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusMailchimpPlugin\Behat\Page\Admin\ManageConfig;

use Behat\Mink\Element\ElementInterface;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Sylius\Behat\Service\JQueryHelper;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    public function fillCode(string $code): void
    {
        $this->getLastListItem()->setValue($code);
    }

    public function fillId(string $id): void
    {
        $this->getLastListItem()->setValue($id);
    }

    public function containsList(string $id): bool
    {
        $value = $this->getLastListItem()->getValue();

        if ($value === $id) {
            return true;
        }

        return false;
    }

    public function removeLastList(): void
    {
        $this->getDocument()
            ->find('xpath',
                '//*[@id="setono_sylius_mailchimp_export_config_lists"]/div/div[' . $this->countLists() . ']/a')
            ->click()
        ;
    }

    public function clickAddList(): void
    {
        $this->getDocument()->findLink('Add')->click();
    }

    public function countLists(): int
    {
        $lists = $this->getDocument()->find('css', 'div[data-form-collection="list"]');
        $listsArray = $lists->findAll('css', 'div[data-form-collection="item"]');

        return count($listsArray);
    }

    public function waitForRedirect(): bool
    {
        JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());

        return $this->isOpen();
    }

    private function getLastListItem(): ElementInterface
    {
        return $this->getDocument()
            ->findById('setono_sylius_mailchimp_export_config_lists_' . ($this->countLists() - 1) . '_listId')
        ;
    }
}

<?php

namespace common\base\interfaces\notifier;

interface EmailNotificationInterface
{
    public function getHtml(): string|null;
    public function getText(): string|null;
    public function getParams(): array;
    public function getFromEmail(): string;
    public function getFromName(): string;
    public function getSubject(): string;
}
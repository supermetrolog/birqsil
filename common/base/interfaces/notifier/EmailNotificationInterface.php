<?php

namespace common\base\interfaces\notifier;

interface EmailNotificationInterface
{
    public function getHtml(): string;
    public function getText(): string;
    public function getFromEmail(): string;
    public function getFromName(): string;
    public function getSubject(): string;
}
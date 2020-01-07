<?php

namespace modules\forms\models;

use modules\forms\contracts\Saveable;
use modules\forms\models\Form;

use Craft;
use craft\elements\Entry;
use craft\mail\Message;

class Contact extends Form implements Saveable
{
    const SECTION_ID = 15;
    const TYPE_ID = 34;

    private $contact = '';
    private $fromEmail = '';

    public $name;
    public $email;
    public $phone;
    public $message;

    public function __construct()
    {
        $this->contact = Entry::find()->section('contact')->one();
        $this->fromEmail = Craft::$app->getSystemSettings()->getEmailSettings()->fromEmail;
    }

    public function rules()
    {
        return [
            [['name', 'email', 'phone'], 'required'],
            ['name', 'string'],
            ['email', 'email'],
            ['phone', 'string'],
            ['message', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'First Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'message' => 'Breifly describe your project.',
        ];
    }

    public function attributePlaceholders(): array
    {
        return [
            'name' => 'First Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'message' => 'Breifly describe your project.',
        ];
    }

    public function attributeTypes(): array
    {
        return [
            'name' => 'text',
            'email' => 'email',
            'phone' => 'tel',
            'message' => 'textarea',
        ];
    }

    public function attributeSizes(): array
    {
        return [
            'name' => 'half',
            'email' => 'half',
            'phone' => 'half',
            'message' => 'full',
        ];
    }

    public function saveCraftEntry(): bool
    {
        $entry = new Entry([
            'sectionId' => 7, // Form Submissions
            'typeId' => 7, // Contact
            'authorId' => 1,
            'enabled' => true,
        ]);

        $entry->setFieldValues([
            'formName' => $this->name,
            'formEmail' => $this->email,
            'formPhoneNumber' => $this->phone,
            'formMessage' => $this->message,
        ]);


        if (!Craft::$app->getElements()->saveElement($entry)) {
            $this->addModelErrors($entry);

            return false;
        }

        return true;
    }

    public function sendNotificationEmail(): bool
    {
        $to = $this->fromEmail;
        if ($this->contact !== null) {
            $to = array_map(function ($row) {
                return $row['emailAddress'];
            }, $this->contact->contactNotificationEmails);
        }
        $heading = sprintf('%s Form Submission', $this->formName());

        $message = new Message();
        $message->setFrom($this->fromEmail);
        $message->setTo($to);
        $message->setSubject($heading);
        $message->setHtmlBody($this->printAttributesSummaryTable($heading));

        return Craft::$app->getMailer()->send($message);
    }

    public function sendConfirmationEmail(): bool
    {
        if ($this->contact === null) {
            return false;
        }

        $message = new Message();
        $message->setFrom($this->fromEmail);
        $message->setTo($this->email);
        $message->setSubject($this->contact->contactEmailSubject);
        $message->setHtmlBody($this->contact->contactEmailMessage);

        return Craft::$app->getMailer()->send($message);
    }
}

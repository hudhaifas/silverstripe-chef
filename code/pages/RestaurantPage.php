<?php

/*
 * MIT License
 *  
 * Copyright (c) 2016 Hudhaifa Shatnawi
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Oct 24, 2016 - 12:55:22 PM
 */
class RestaurantPage
        extends Page {

    private static $db = array(
        'Address' => 'Varchar(255)',
        'Phone' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
    );
    private static $translate = array(
        'Address',
    );
    private static $has_one = array(
        'About' => 'AboutItem'
    );
    private static $has_many = array(
        'OpenTimes' => 'OpenTime',
        'MealsMenus' => 'MealsMenu',
        'Chefs' => 'Chef',
        'GalleryItems' => 'GalleryItem',
        'CarouselItems' => 'CarouselItem',
    );
    private static $many_many = array(
    );
    private static $icon = "chef/images/restaurant.png";

    /**
     */
    private static $group_code = 'chefs';
    private static $group_title = 'Chefs';
    private static $group_permission = 'CMS_ACCESS_CMSMain';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Address'] = _t('Restaurant.ADDRESS', 'Address');
        $labels['Phone'] = _t('Restaurant.PHONE', 'Phone');
        $labels['Email'] = _t('Restaurant.EMAIL', 'Email');
        
        $labels['Menus'] = _t('Restaurant.MENUS', 'Menus');
        $labels['OpenTimes'] = _t('Restaurant.OPEN_TIMES', 'Open Times');
        $labels['Contact'] = _t('Restaurant.CONTACT', 'Contact');
        $labels['About'] = _t('Restaurant.ABOUT', 'About');
        $labels['Chefs'] = _t('Restaurant.CHEFS', 'Chefs');
        $labels['GalleryItems'] = _t('Restaurant.GALLERY_ITEMS', 'Gallery Items');
        $labels['CarouselItems'] = _t('Restaurant.CAROUSEL_ITEMS', 'Carousel Items');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        // Removed unnecessary fields
        $fields->removeByName("Content");
        $fields->removeByName("Metadata");

        // Meals Menu
        $fields->addFieldToTab('Root.Menus', GridField::create(
                        'MealsMenus', //
                        'MealsMenus', //
                        $this->MealsMenus(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        // Open Times
        $fields->addFieldToTab('Root.OpenTimes', GridField::create(
                        'OpenTimes', //
                        'OpenTimes', //
                        $this->OpenTimes(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        // Contact
        $fields->addFieldToTab('Root.Contact', TextField::create('Address'));
        $fields->addFieldToTab('Root.Contact', TextField::create('Phone'));
        $fields->addFieldToTab('Root.Contact', TextField::create('Email'));

        // About
        if ($this->About()->exists()) {
            $fields->addFieldsToTab("Root.About", array(
                ReadonlyField::create("add", "About", $this->About()->toString())
            ));
        }
        $fields->removeByName("AboutID");
        $fields->addFieldToTab("Root.About", HasOneButtonField::create("About", "About", $this) //here!
        );

        // Chefs
        $fields->addFieldToTab('Root.Chefs', GridField::create(
                        'Chefs', //
                        'Chefs', //
                        $this->Chefs(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        // Gallery Items
        $fields->addFieldToTab('Root.GalleryItems', GridField::create(
                        'GalleryItems', //
                        'GalleryItems', //
                        $this->GalleryItems(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        // Carousel Items
        $fields->addFieldToTab('Root.CarouselItems', GridField::create(
                        'CarouselItems', //
                        'CarouselItems', //
                        $this->CarouselItems(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        return $fields;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();
        $this->getUserGroup();
    }

    /**
     * Returns/Creates the librarians group to assign CMS access.
     *
     * @return Group Librarians group
     */
    protected function getUserGroup() {
        $code = $this->config()->group_code;

        $group = Group::get()->filter('Code', $code)->first();

        if (!$group) {
            $group = new Group();
            $group->Title = $this->config()->group_title;
            $group->Code = $code;

            $group->write();

            $permission = new Permission();
            $permission->Code = $this->config()->group_permission;

            $group->Permissions()->add($permission);
        }

        return $group;
    }

}

class RestaurantPage_Controller
        extends Page_Controller {

    private static $allowed_actions = array(
        // Reservation Actions
        'ReservationForm',
        'doReservation',
        'ContactForm',
        'sendContactMessage',
        'foodMenu'
    );
    private static $url_handlers = array(
    );

    public function ReservationForm() {
        // Create fields          
        $fields = new FieldList(
                TextField::create('Name'), //
                TextField::create('Email'), //
                TextField::create('PhoneNo'), //
                TextField::create('Date'), //
                TextField::create('Time'), //
                TextField::create('People'), //
                TextareaField::create('Message') //
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doReservation')
        );

        // Create Validators
        $validator = new RequiredFields();

        $form = new Form($this, 'ReservationForm', $fields, $actions, $validator);
        $form->setTemplate('Form_Reservation');

        return $form;
    }

    public function doReservation($data, $form) {
        die('submited');
        $reservation = new Reservation();
        $form->saveInto($reservation);
        $reservation->write();

        return $this->redirectBack();
    }

    public function ContactForm() {
        // Create fields          
        $fields = new FieldList(
                TextField::create('Name'), //
                TextField::create('Email'), //
                TextField::create('Subject'), //
                TextareaField::create('Message')
        );

        // Create action
        $actions = new FieldList(
                new FormAction('sendContactMessage')
        );

        // Create Validators
        $validator = new RequiredFields();

        $form = new Form($this, 'ContactForm', $fields, $actions, $validator);
        $form->setTemplate('Form_Contact');

        return $form;
    }

    public function sendContactMessage($data, $form) {
//        die('submited');
        $message = new ContactMessage();
        $form->saveInto($message);
        $message->write();

        return $this->redirectBack();
    }

    public function getMeals() {
        return Meal::get();
    }

    public function getCarousels() {
        return CarouselItem::get();
    }

}

<?php
/**
 * This file is part of Community plugin for FacturaScripts.
 * Copyright (C) 2018 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\Community\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Base\Utils;
use FacturaScripts\Core\Model\Base;
use FacturaScripts\Core\Model\Contacto;
use FacturaScripts\Plugins\webportal\Model\WebPage;

/**
 * Description of WebProject model.
 *
 * @author Carlos García Gómez
 */
class WebProject extends Base\ModelClass
{

    use Base\ModelTrait;

    /**
     * Creation date.
     *
     * @var string
     */
    public $creationdate;

    /**
     *
     * @var string
     */
    public $description;

    /**
     * Contact identifier.
     *
     * @var int
     */
    public $idcontacto;

    /**
     * Primary key.
     *
     * @var int
     */
    public $idproject;

    /**
     * Project name.
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var bool
     */
    public $plugin;

    /**
     *
     * @var string
     */
    public $publicrepo;

    /**
     *
     * @var array
     */
    private static $urls = [];

    /**
     * Reset the values of all model properties.
     */
    public function clear()
    {
        parent::clear();
        $this->creationdate = date('d-m-Y');
        $this->plugin = true;
    }

    /**
     * Returns a maximun legth of $legth form the body property of this block.
     * 
     * @param int $length
     *
     * @return string
     */
    public function description(int $length = 300): string
    {
        return Utils::trueTextBreak($this->description, $length);
    }

    /**
     * Returns contact name.
     *
     * @return string
     */
    public function getContactName()
    {
        $contact = new Contacto();
        if (!empty($this->idcontacto) && $contact->loadFromCode($this->idcontacto)) {
            return $contact->fullName();
        }

        return '-';
    }

    /**
     * Returns the name of the column that is the primary key of the model.
     *
     * @return string
     */
    public static function primaryColumn()
    {
        return 'idproject';
    }
    
    public function primaryDescriptionColumn()
    {
        return 'name';
    }

    /**
     * Returns the name of the table that uses this model.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'webprojects';
    }

    /**
     * Returns True if there is no errors on properties values.
     *
     * @return bool
     */
    public function test()
    {
        $this->description = Utils::noHtml($this->description);
        $this->name = Utils::noHtml($this->name);
        if (strlen($this->name) < 1) {
            self::$miniLog->alert(self::$i18n->trans('invalid-column-lenght', ['%column%' => 'name', '%min%' => '1', '%max%' => '50']));
        }

        return parent::test();
    }

    public function url(string $type = 'auto', string $list = 'List')
    {
        switch ($type) {
            case 'public-list':
                return $this->getCustomUrl($type);

            case 'public':
                return $this->getCustomUrl($type) . $this->name;
        }

        return parent::url($type, $list);
    }

    protected function getCustomUrl(string $type): string
    {
        if (isset(self::$urls[$type])) {
            return self::$urls[$type];
        }

        $controller = ('public-list' === $type) ? 'PluginList' : 'ViewPlugin';
        $webPage = new WebPage();
        foreach ($webPage->all([new DataBaseWhere('customcontroller', $controller)]) as $wpage) {
            self::$urls[$type] = $wpage->url('public');
            return self::$urls[$type];
        }

        return '#';
    }
}

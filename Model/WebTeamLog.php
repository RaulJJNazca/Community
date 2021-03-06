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

use FacturaScripts\Core\Model\Base;
use FacturaScripts\Core\Model\Contacto;

/**
 * Description of WebTeamLog
 *
 * @author carlos
 */
class WebTeamLog extends Base\ModelClass
{

    use Base\ModelTrait;

    /**
     *
     * @var string
     */
    public $description;

    /**
     * Primary key.
     *
     * @var int
     */
    public $id;

    /**
     * Contact identifier.
     *
     * @var int
     */
    public $idcontacto;

    /**
     * Team identifier.
     *
     * @var int
     */
    public $idteam;

    /**
     *
     * @var string
     */
    public $link;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var array
     */
    private static $contacts = [];

    /**
     *
     * @var array
     */
    private static $teams = [];

    public function clear()
    {
        parent::clear();
        $this->time = date('d-m-Y H:i:s');
    }

    /**
     * Returns contact name.
     *
     * @return string
     */
    public function getContactName()
    {
        if (empty($this->idcontacto)) {
            return '-';
        }

        if (isset(self::$contacts[$this->idcontacto])) {
            return self::$contacts[$this->idcontacto]->fullName();
        }

        self::$contacts[$this->idcontacto] = new Contacto();
        if (self::$contacts[$this->idcontacto]->loadFromCode($this->idcontacto)) {
            return self::$contacts[$this->idcontacto]->fullName();
        }

        return '-';
    }

    /**
     * Returns team.
     * 
     * @return WebTeam
     */
    public function getTeam()
    {
        if (isset(self::$teams[$this->idteam])) {
            return self::$teams[$this->idteam];
        }

        self::$teams[$this->idteam] = new WebTeam();
        self::$teams[$this->idteam]->loadFromCode($this->idteam);
        return self::$teams[$this->idteam];
    }

    public static function primaryColumn()
    {
        return 'id';
    }

    public static function tableName()
    {
        return 'webteams_logs';
    }

    public function url(string $type = 'auto', string $list = 'List')
    {
        return parent::url($type, 'ListWebProject?active=List');
    }
}

<?php

namespace UR\DB\OldBundle\Entity;

/**
 * Religion
 */
class Religion
{
    /**
     * @var boolean
     */
    private $order;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $konfession;

    /**
     * @var string
     */
    private $belegt;

    /**
     * @var string
     */
    private $vonAb;

    /**
     * @var string
     */
    private $konversion;

    /**
     * @var string
     */
    private $kommentar;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return Religion
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return boolean
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Religion
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set konfession
     *
     * @param string $konfession
     *
     * @return Religion
     */
    public function setKonfession($konfession)
    {
        $this->konfession = $konfession;

        return $this;
    }

    /**
     * Get konfession
     *
     * @return string
     */
    public function getKonfession()
    {
        return $this->konfession;
    }

    /**
     * Set belegt
     *
     * @param string $belegt
     *
     * @return Religion
     */
    public function setBelegt($belegt)
    {
        $this->belegt = $belegt;

        return $this;
    }

    /**
     * Get belegt
     *
     * @return string
     */
    public function getBelegt()
    {
        return $this->belegt;
    }

    /**
     * Set vonAb
     *
     * @param string $vonAb
     *
     * @return Religion
     */
    public function setVonAb($vonAb)
    {
        $this->vonAb = $vonAb;

        return $this;
    }

    /**
     * Get vonAb
     *
     * @return string
     */
    public function getVonAb()
    {
        return $this->vonAb;
    }

    /**
     * Set konversion
     *
     * @param string $konversion
     *
     * @return Religion
     */
    public function setKonversion($konversion)
    {
        $this->konversion = $konversion;

        return $this;
    }

    /**
     * Get konversion
     *
     * @return string
     */
    public function getKonversion()
    {
        return $this->konversion;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return Religion
     */
    public function setKommentar($kommentar)
    {
        $this->kommentar = $kommentar;

        return $this;
    }

    /**
     * Get kommentar
     *
     * @return string
     */
    public function getKommentar()
    {
        return $this->kommentar;
    }
}


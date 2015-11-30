<?php

namespace UR\DB\OldBundle\Entity;

/**
 * EhepartnerDesGeschwisters
 */
class EhepartnerDesGeschwisters
{
    /**
     * @var boolean
     */
    private $order;

    /**
     * @var boolean
     */
    private $order2;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $hochzeitsort;

    /**
     * @var string
     */
    private $hochzeitstag;

    /**
     * @var string
     */
    private $vornamen;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $verheiratet;

    /**
     * @var string
     */
    private $stand;

    /**
     * @var string
     */
    private $geschwisterpartnerIdNr;

    /**
     * @var string
     */
    private $beruf;

    /**
     * @var string
     */
    private $kommentar;

    /**
     * @var string
     */
    private $russVornamen;

    /**
     * @var string
     */
    private $bildungsabschluss;

    /**
     * @var string
     */
    private $geboren;

    /**
     * @var string
     */
    private $gestorben;

    /**
     * @var string
     */
    private $ehren;

    /**
     * @var string
     */
    private $rang;

    /**
     * @var string
     */
    private $begraben;

    /**
     * @var string
     */
    private $begräbnisort;

    /**
     * @var string
     */
    private $vorherNachher;

    /**
     * @var string
     */
    private $herkunftsort;

    /**
     * @var string
     */
    private $auflösung;

    /**
     * @var string
     */
    private $konfession;

    /**
     * @var string
     */
    private $friedhof;


    /**
     * Set order
     *
     * @param boolean $order
     *
     * @return EhepartnerDesGeschwisters
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
     * Set order2
     *
     * @param boolean $order2
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setOrder2($order2)
    {
        $this->order2 = $order2;

        return $this;
    }

    /**
     * Get order2
     *
     * @return boolean
     */
    public function getOrder2()
    {
        return $this->order2;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return EhepartnerDesGeschwisters
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
     * Set hochzeitsort
     *
     * @param string $hochzeitsort
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setHochzeitsort($hochzeitsort)
    {
        $this->hochzeitsort = $hochzeitsort;

        return $this;
    }

    /**
     * Get hochzeitsort
     *
     * @return string
     */
    public function getHochzeitsort()
    {
        return $this->hochzeitsort;
    }

    /**
     * Set hochzeitstag
     *
     * @param string $hochzeitstag
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setHochzeitstag($hochzeitstag)
    {
        $this->hochzeitstag = $hochzeitstag;

        return $this;
    }

    /**
     * Get hochzeitstag
     *
     * @return string
     */
    public function getHochzeitstag()
    {
        return $this->hochzeitstag;
    }

    /**
     * Set vornamen
     *
     * @param string $vornamen
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setVornamen($vornamen)
    {
        $this->vornamen = $vornamen;

        return $this;
    }

    /**
     * Get vornamen
     *
     * @return string
     */
    public function getVornamen()
    {
        return $this->vornamen;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set verheiratet
     *
     * @param string $verheiratet
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setVerheiratet($verheiratet)
    {
        $this->verheiratet = $verheiratet;

        return $this;
    }

    /**
     * Get verheiratet
     *
     * @return string
     */
    public function getVerheiratet()
    {
        return $this->verheiratet;
    }

    /**
     * Set stand
     *
     * @param string $stand
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setStand($stand)
    {
        $this->stand = $stand;

        return $this;
    }

    /**
     * Get stand
     *
     * @return string
     */
    public function getStand()
    {
        return $this->stand;
    }

    /**
     * Set geschwisterpartnerIdNr
     *
     * @param string $geschwisterpartnerIdNr
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setGeschwisterpartnerIdNr($geschwisterpartnerIdNr)
    {
        $this->geschwisterpartnerIdNr = $geschwisterpartnerIdNr;

        return $this;
    }

    /**
     * Get geschwisterpartnerIdNr
     *
     * @return string
     */
    public function getGeschwisterpartnerIdNr()
    {
        return $this->geschwisterpartnerIdNr;
    }

    /**
     * Set beruf
     *
     * @param string $beruf
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setBeruf($beruf)
    {
        $this->beruf = $beruf;

        return $this;
    }

    /**
     * Get beruf
     *
     * @return string
     */
    public function getBeruf()
    {
        return $this->beruf;
    }

    /**
     * Set kommentar
     *
     * @param string $kommentar
     *
     * @return EhepartnerDesGeschwisters
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

    /**
     * Set russVornamen
     *
     * @param string $russVornamen
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setRussVornamen($russVornamen)
    {
        $this->russVornamen = $russVornamen;

        return $this;
    }

    /**
     * Get russVornamen
     *
     * @return string
     */
    public function getRussVornamen()
    {
        return $this->russVornamen;
    }

    /**
     * Set bildungsabschluss
     *
     * @param string $bildungsabschluss
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setBildungsabschluss($bildungsabschluss)
    {
        $this->bildungsabschluss = $bildungsabschluss;

        return $this;
    }

    /**
     * Get bildungsabschluss
     *
     * @return string
     */
    public function getBildungsabschluss()
    {
        return $this->bildungsabschluss;
    }

    /**
     * Set geboren
     *
     * @param string $geboren
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setGeboren($geboren)
    {
        $this->geboren = $geboren;

        return $this;
    }

    /**
     * Get geboren
     *
     * @return string
     */
    public function getGeboren()
    {
        return $this->geboren;
    }

    /**
     * Set gestorben
     *
     * @param string $gestorben
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setGestorben($gestorben)
    {
        $this->gestorben = $gestorben;

        return $this;
    }

    /**
     * Get gestorben
     *
     * @return string
     */
    public function getGestorben()
    {
        return $this->gestorben;
    }

    /**
     * Set ehren
     *
     * @param string $ehren
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setEhren($ehren)
    {
        $this->ehren = $ehren;

        return $this;
    }

    /**
     * Get ehren
     *
     * @return string
     */
    public function getEhren()
    {
        return $this->ehren;
    }

    /**
     * Set rang
     *
     * @param string $rang
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return string
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set begraben
     *
     * @param string $begraben
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setBegraben($begraben)
    {
        $this->begraben = $begraben;

        return $this;
    }

    /**
     * Get begraben
     *
     * @return string
     */
    public function getBegraben()
    {
        return $this->begraben;
    }

    /**
     * Set begräbnisort
     *
     * @param string $begräbnisort
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setBegräbnisort($begräbnisort)
    {
        $this->begräbnisort = $begräbnisort;

        return $this;
    }

    /**
     * Get begräbnisort
     *
     * @return string
     */
    public function getBegräbnisort()
    {
        return $this->begräbnisort;
    }

    /**
     * Set vorherNachher
     *
     * @param string $vorherNachher
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setVorherNachher($vorherNachher)
    {
        $this->vorherNachher = $vorherNachher;

        return $this;
    }

    /**
     * Get vorherNachher
     *
     * @return string
     */
    public function getVorherNachher()
    {
        return $this->vorherNachher;
    }

    /**
     * Set herkunftsort
     *
     * @param string $herkunftsort
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setHerkunftsort($herkunftsort)
    {
        $this->herkunftsort = $herkunftsort;

        return $this;
    }

    /**
     * Get herkunftsort
     *
     * @return string
     */
    public function getHerkunftsort()
    {
        return $this->herkunftsort;
    }

    /**
     * Set auflösung
     *
     * @param string $auflösung
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setAuflösung($auflösung)
    {
        $this->auflösung = $auflösung;

        return $this;
    }

    /**
     * Get auflösung
     *
     * @return string
     */
    public function getAuflösung()
    {
        return $this->auflösung;
    }

    /**
     * Set konfession
     *
     * @param string $konfession
     *
     * @return EhepartnerDesGeschwisters
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
     * Set friedhof
     *
     * @param string $friedhof
     *
     * @return EhepartnerDesGeschwisters
     */
    public function setFriedhof($friedhof)
    {
        $this->friedhof = $friedhof;

        return $this;
    }

    /**
     * Get friedhof
     *
     * @return string
     */
    public function getFriedhof()
    {
        return $this->friedhof;
    }
}

